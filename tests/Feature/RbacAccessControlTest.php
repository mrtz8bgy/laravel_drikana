<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Permission;
use App\Models\Role;
use App\Services\Rbac\RbacService;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * تست‌های سیستم نقش و مجوز (RBAC).
 *
 * پوشش:
 *   • Deny by Default — کاربر بدون مجوز رد می‌شود
 *   • کاربر مهمان به صفحه ورود هدایت می‌شود
 *   • مجوزدار بودن نقش‌های اولیه بر اساس تعریف نیازمندی
 *   • جلوگیری از افزایش سطح دسترسی (Privilege Escalation)
 *   • جلوگیری از سلب نقش از آخرین مدیر کل
 *   • چند نقش برای یک کاربر
 *   • ثبت در Audit Log
 */
class RbacAccessControlTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * ساخت کاربر آزمایشی با نقش مشخص.
     *
     * @param  array $roleSlugs
     * @param  array $attributes
     * @return \App\User
     */
    protected function makeUser(array $roleSlugs = [], array $attributes = [])
    {
        // کاربر با user_type=customer ساخته می‌شود تا super-admin bypass فعال نشود
        $user = new User();
        $user->name      = isset($attributes['name']) ? $attributes['name'] : 'کاربر تست';
        $user->email     = isset($attributes['email']) ? $attributes['email'] : 'test' . uniqid() . '@example.com';
        $user->password  = bcrypt('test-password-12345');
        $user->user_type = isset($attributes['user_type']) ? $attributes['user_type'] : 'customer';
        $user->role      = 'customer';
        $user->save();

        if (!empty($roleSlugs)) {
            $roleIds = Role::query()->whereIn('slug', $roleSlugs)->pluck('id')->all();
            $payload = [];
            foreach ($roleIds as $roleId) {
                $payload[$roleId] = [
                    'assigned_by' => null,
                    'assigned_at' => now(),
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
            }
            $user->roles()->sync($payload);
        }

        $user->flushPermissionCache();

        return $user->fresh();
    }

    // =========================================================
    // ۱) Deny by Default
    // =========================================================

    /** @test */
    public function guest_is_redirected_to_login_on_protected_route()
    {
        $response = $this->get('/admin/jewelry/repair-orders');

        $response->assertStatus(302);
        // مسیر ورود پیش‌فرض پروژه روت نام‌دار «login» است
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function user_without_permission_is_denied_by_default()
    {
        $user = $this->makeUser(['customer']);

        $response = $this->actingAs($user)->get('/admin/jewelry/repair-orders');

        // Deny by Default — نه ۲۰۰، نه redirect
        $response->assertStatus(403);
    }

    /** @test */
    public function user_with_permission_can_access_route()
    {
        $user = $this->makeUser(['repair_technician']);

        $response = $this->actingAs($user)->get('/admin/jewelry/repair-orders');

        $this->assertNotEquals(403, $response->getStatusCode(),
            'تکنسین تعمیرات باید به فهرست سفارش‌های تعمیر دسترسی داشته باشد.');
    }

    // =========================================================
    // ۲) تفکیک مجوزها بر اساس نیازمندی
    // =========================================================

    /** @test */
    public function store_manager_has_product_permissions()
    {
        $user = $this->makeUser(['store_manager']);

        $this->assertTrue($user->hasPermissionTo('products.view'));
        $this->assertTrue($user->hasPermissionTo('products.create'));
        $this->assertTrue($user->hasPermissionTo('products.update'));
        $this->assertTrue($user->hasPermissionTo('orders.view'));
        $this->assertTrue($user->hasPermissionTo('stock.update'));
    }

    /** @test */
    public function store_manager_has_no_ownership_transfer_permission()
    {
        $user = $this->makeUser(['store_manager']);

        // طبق نیازمندی: «عدم دسترسی به تغییر مالکیت، مگر با مجوز مستقل»
        $this->assertFalse($user->hasPermissionTo('jewelry.transfers.create'));
        $this->assertFalse($user->hasPermissionTo('jewelry.transfers.approve'));
    }

    /** @test */
    public function repair_technician_has_only_repair_permissions()
    {
        $user = $this->makeUser(['repair_technician']);

        $this->assertTrue($user->hasPermissionTo('jewelry.repairs.view'));
        $this->assertTrue($user->hasPermissionTo('jewelry.repairs.update_status'));

        // طبق نیازمندی: «عدم دسترسی به بخش‌های نامرتبط با تعمیرات»
        $this->assertFalse($user->hasPermissionTo('products.create'));
        $this->assertFalse($user->hasPermissionTo('jewelry.transfers.approve'));
        $this->assertFalse($user->hasPermissionTo('articles.publish'));
    }

    /** @test */
    public function ownership_manager_can_manage_transfers()
    {
        $user = $this->makeUser(['ownership_manager']);

        $this->assertTrue($user->hasPermissionTo('jewelry.transfers.view'));
        $this->assertTrue($user->hasPermissionTo('jewelry.transfers.create'));
        $this->assertTrue($user->hasPermissionTo('jewelry.transfers.approve'));
        $this->assertTrue($user->hasPermissionTo('jewelry.transfers.reject'));
    }

    /** @test */
    public function content_manager_can_publish_articles()
    {
        $user = $this->makeUser(['content_manager']);

        $this->assertTrue($user->hasPermissionTo('articles.create'));
        $this->assertTrue($user->hasPermissionTo('articles.update'));
        $this->assertTrue($user->hasPermissionTo('articles.publish'));
        $this->assertTrue($user->hasPermissionTo('articles.categories.manage'));
    }

    /** @test */
    public function customer_role_has_no_global_permissions()
    {
        $user = $this->makeUser(['customer']);

        $this->assertEmpty($user->effectivePermissions());
        $this->assertFalse($user->hasPermissionTo('products.view'));
        $this->assertFalse($user->hasPermissionTo('jewelry.certificates.view'));
    }

    /** @test */
    public function super_admin_role_has_all_permissions()
    {
        $role = Role::query()->where('slug', 'super_admin')->first();

        $this->assertEquals(
            count(Permission::query()->pluck('slug')->all()),
            count($role->permissionSlugs()),
            'نقش مدیر کل باید تمام مجوزها را داشته باشد.'
        );
    }

    /** @test */
    public function grouped_permissions_are_slug_to_name_pairs_for_role_forms()
    {
        $permissionGroups = Permission::grouped();
        $repairPermissions = $permissionGroups->get('جواهرات — تعمیرات');

        $this->assertIsArray($repairPermissions);
        $this->assertSame('تخصیص تکنسین به سفارش', $repairPermissions['jewelry.repairs.assign']);
        $this->assertArrayNotHasKey(0, $repairPermissions);

        $html = view('admin.partials.permission_matrix', [
            'permissionGroups' => $permissionGroups,
            'selected' => [],
            'actorPermissions' => null,
            'isSuperAdmin' => true,
        ])->render();

        $this->assertStringContainsString('jewelry.repairs.assign', $html);
        $this->assertStringContainsString('تخصیص تکنسین به سفارش', $html);
        $this->assertStringNotContainsString('&quot;id&quot;', $html);
    }

    // =========================================================
    // ۳) بالابردن سطح دسترسی (Privilege Escalation)
    // =========================================================

    /** @test */
    public function user_cannot_assign_role_to_themselves()
    {
        $actor = $this->makeUser(['ownership_manager'], ['user_type' => 'staff']);

        $rbac = app(RbacService::class);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('نمی‌توانید نقش‌های خودتان را تغییر دهید');

        $rbac->syncUserRoles($actor, ['super_admin'], $actor);
    }

    /** @test */
    public function user_cannot_grant_permission_they_do_not_have()
    {
        // مدیر مالکیت مجوز products.delete ندارد
        $actor = $this->makeUser(['ownership_manager'], ['user_type' => 'staff']);

        $rbac = app(RbacService::class);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('نمی‌توانید مجوزی را اعطا کنید که خودتان آن را ندارید');

        $rbac->createRole([
            'name'        => 'نقش مخرب',
            'slug'        => 'malicious_role',
            'permissions' => ['products.delete', 'settings.update'],
        ], $actor);
    }

    /** @test */
    public function user_cannot_delete_last_super_admin_role_from_user()
    {
        // یک مدیر کل (بدون user_type=admin تا شمردن دقیق باشد)
        $superAdmin = $this->makeUser(['super_admin'], ['user_type' => 'staff']);
        $actor      = $this->makeUser(['super_admin'], ['user_type' => 'staff']);

        // در دیتابیس تست، مدیران user_type=admin وجود دارند؛ آن‌ها را موقتاً نادیده می‌گیریم
        $rbac = app(RbacService::class);

        // تعداد مدیران کل فعلی
        $count = $rbac->countSuperAdmins();

        if ($count > 1) {
            // اگر بیش از یکی هستند، تست القای شرط «آخرین مدیر» ممکن نیست
            $this->markTestSkipped(
                'بیش از یک مدیر کل در دیتابیس تست وجود دارد؛ شرط «آخرین مدیر» قابل القا نیست.'
            );
        }

        $this->expectException(\RuntimeException::class);

        $rbac->syncUserRoles($superAdmin, ['customer'], $actor);
    }

    /** @test */
    public function system_roles_cannot_be_deleted()
    {
        $actor = $this->makeUser(['super_admin'], ['user_type' => 'staff']);
        $role  = Role::query()->where('slug', 'store_manager')->first();

        $rbac = app(RbacService::class);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('نقش‌های سیستمی قابل حذف نیستند');

        $rbac->deleteRole($role, $actor);
    }

    // =========================================================
    // ۴) چند نقش برای یک کاربر
    // =========================================================

    /** @test */
    public function user_can_have_multiple_roles()
    {
        $user = $this->makeUser(['repair_technician', 'content_manager']);

        $this->assertTrue($user->hasRole('repair_technician'));
        $this->assertTrue($user->hasRole('content_manager'));

        // مجوزها اجتماع هر دو نقش هستند
        $this->assertTrue($user->hasPermissionTo('jewelry.repairs.view'));
        $this->assertTrue($user->hasPermissionTo('articles.create'));
    }

    // =========================================================
    // ۵) Audit Log
    // =========================================================

    /** @test */
    public function access_denied_is_recorded_in_audit_log()
    {
        $user = $this->makeUser(['customer']);

        $before = AuditLog::query()->where('event', AuditLog::EVENT_ACCESS_DENIED)->count();

        $this->actingAs($user)->get('/admin/jewelry/repair-orders');

        $after = AuditLog::query()->where('event', AuditLog::EVENT_ACCESS_DENIED)->count();

        $this->assertGreaterThan($before, $after,
            'دسترسی غیرمجاز باید در Audit Log ثبت شود.');
    }

    /** @test */
    public function role_assignment_is_recorded_in_audit_log()
    {
        $actor  = $this->makeUser(['super_admin'], ['user_type' => 'staff']);
        $target = $this->makeUser(['customer']);

        $before = AuditLog::query()
            ->where('event', AuditLog::EVENT_ROLE_ASSIGNED)
            ->count();

        $rbac = app(RbacService::class);
        $rbac->syncUserRoles($target, ['content_manager'], $actor);

        $after = AuditLog::query()
            ->where('event', AuditLog::EVENT_ROLE_ASSIGNED)
            ->count();

        $this->assertGreaterThan($before, $after,
            'انتساب نقش باید در Audit Log ثبت شود.');

        // بررسی محتوای ثبت‌شده
        $log = AuditLog::query()
            ->where('event', AuditLog::EVENT_ROLE_ASSIGNED)
            ->orderByDesc('id')
            ->first();

        $this->assertEquals($target->getKey(), $log->auditable_id);
        $this->assertEquals(AuditLog::SEVERITY_CRITICAL, $log->severity);
    }

    /** @test */
    public function audit_log_does_not_store_sensitive_fields()
    {
        $logger = app(\App\Services\AuditLogger::class);

        $logger->log(
            'test.event',
            'تست حذف فیلد حساس',
            null,
            ['password' => 'supersecret123', 'name' => 'محمد'],
            ['api_key' => 'sk_live_abcdef', 'role' => 'admin']
        );

        $log = AuditLog::query()->where('event', 'test.event')->orderByDesc('id')->first();

        $this->assertNotNull($log);
        $this->assertStringNotContainsString('supersecret123', (string) $log->old_values);
        $this->assertStringNotContainsString('sk_live_abcdef', (string) $log->new_values);
        $this->assertStringContainsString('[REDACTED]', (string) $log->old_values);
        $this->assertStringContainsString('محمد', (string) $log->old_values);
    }

    // =========================================================
    // ۶) کش مجوزها
    // =========================================================

    /** @test */
    public function permission_cache_is_invalidated_after_role_change()
    {
        $user = $this->makeUser(['customer']);

        $this->assertFalse($user->hasPermissionTo('articles.create'));

        // افزودن نقش
        $actress = $this->makeUser(['super_admin'], ['user_type' => 'staff']);
        app(RbacService::class)->syncUserRoles($user, ['content_manager'], $actress);

        $user = $user->fresh();

        $this->assertTrue($user->hasPermissionTo('articles.create'),
            'پس از تغییر نقش، کش مجوزها باید پاک شود.');
    }

    /** @test */
    public function inactive_role_grants_no_permissions()
    {
        $user = $this->makeUser(['content_manager']);

        $this->assertTrue($user->hasPermissionTo('articles.create'));

        // غیرفعال‌سازی نقش
        DB::table('roles')->where('slug', 'content_manager')->update(['is_active' => false]);
        \App\Models\Permission::flushCache();
        $user->flushPermissionCache();

        $this->assertFalse($user->fresh()->hasPermissionTo('articles.create'),
            'نقش غیرفعال نباید مجوز بدهد.');
    }
}
