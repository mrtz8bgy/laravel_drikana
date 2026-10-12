<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Contracts\Encryption\DecryptException;
use Throwable;

/**
 * رمزگشایی ایمن شناسه‌های رمزنگاری‌شدهٔ آدرس.
 *
 * ── مسئله ────────────────────────────────────────────────────────────
 * در این پروژه بسیاری از آدرس‌ها شناسهٔ رکورد را رمزنگاری‌شده حمل می‌کنند
 * (مثل `/admin/products/admin/{id}/edit`) و کنترلر با `decrypt($id)` آن را
 * برمی‌گرداند. اگر کاربر مقدار آدرس را دست‌کاری کند یا شناسه ناقص/جعلی
 * بفرستد، لاراول استثنای `DecryptException` می‌دهد و صفحه با خطای ۵۰۰ و
 * متن استثنا نمایش داده می‌شود؛ این هم «نشت اطلاعات» است و هم تجربهٔ بدی
 * برای کاربر.
 *
 * ── راه‌حل ───────────────────────────────────────────────────────────
 * این trait یک نقطهٔ واحد برای رمزگشایی فراهم می‌کند: در صورت نامعتبر بودن
 * ورودی، پاسخ ۴۰۴ (Not Found) برگردانده می‌شود، یعنی همان چیزی که برای
 * «رکورد ناموجود» انتظار می‌رود. هیچ شناسه‌ای «حدس زده» نمی‌شود و هیچ
 * دسترسی‌ای باز نمی‌شود.
 *
 * استفاده:
 *     class FooController extends Controller
 *     {
 *         use \App\Http\Controllers\Concerns\HandlesEncryptedIds;
 *
 *         public function edit($id)
 *         {
 *             $model = Foo::findOrFail($this->decryptId($id));
 *         }
 *     }
 */
trait HandlesEncryptedIds
{
    /**
     * رمزگشایی شناسه؛ در صورت نامعتبر بودن، ۴۰۴.
     *
     * @param  string|null $value مقدار رمزنگاری‌شدهٔ آدرس
     * @return mixed        شناسهٔ رمزگشایی‌شده
     */
    protected function decryptId($value)
    {
        if ($value === null || $value === '') {
            abort(404);
        }

        try {
            return decrypt($value);
        } catch (DecryptException $e) {
            // شناسه جعلی یا دست‌کاری‌شده — رکوردی وجود ندارد
            abort(404);
        } catch (Throwable $e) {
            // هر خطای دیگر رمزگشایی (طول نامعتبر، کلید عوض‌شده و ...)
            report($e);
            abort(404);
        }
    }
}
