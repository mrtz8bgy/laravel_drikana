<?php

namespace App\Http\Controllers\Jewelry\Admin;

use App\Http\Controllers\Controller;
use App\Models\RepairTechnician;
use App\Models\RepairOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RepairOrderController extends Controller
{
    /**
     * وضعیت‌های مجاز سفارش تعمیر.
     *
     * فهرست سفید (allowlist) برای اعتبارسنجی وضعیت — از پذیرش هر مقدار
     * دلخواه از سمت کاربر جلوگیری می‌کند.
     *
     * @var array
     */
    const STATUSES = [
        'pending'     => 'در انتظار بررسی',
        'accepted'    => 'پذیرفته‌شده',
        'in_progress' => 'در حال تعمیر',
        'completed'   => 'تعمیر تمام‌شده',
        'delivered'   => 'تحویل‌شده',
        'cancelled'   => 'لغوشده',
    ];

    /**
     * نمایش لیست تمام سفارشات تعمیر برای ادمین
     */
    public function index(Request $request)
    {
        $query = RepairOrder::with(['user', 'jewelry.owner']);
        
        // فیلتر بر اساس وضعیت
        if ($request->has('status') && !empty($request->get('status'))) {
            $query->where('status', $request->get('status'));
        }
        
        // فیلتر بر اساس نوع تعمیر - اصلاح شده
        if ($request->has('repair_type') && !empty($request->get('repair_type'))) {
            $repairType = $request->get('repair_type');
            $query->where('repair_type', 'LIKE', "%{$repairType}%");
        }
        
        // جستجو بر اساس توضیحات یا شماره سفارش
        if ($request->has('search') && !empty($request->get('search'))) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('description', 'LIKE', "%{$search}%")
                  ->orWhere('id', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('email', 'LIKE', "%{$search}%")
                         ->orWhere('phone', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        // فیلتر بر اساس تاریخ ایجاد
        if ($request->has('date_from') && !empty($request->get('date_from'))) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }
        if ($request->has('date_to') && !empty($request->get('date_to'))) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }
        
        $repairOrders = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // استفاده از ثابت‌های ثابت به جای متن فارسی مستقیم
        $stats = [
            'total' => RepairOrder::count(),
            'pending' => RepairOrder::where('status', 'pending')->count(),
            'in_progress' => RepairOrder::where('status', 'in_progress')->count(),
            'completed' => RepairOrder::where('status', 'completed')->count(),
            'cancelled' => RepairOrder::where('status', 'cancelled')->count(),
        ];
        
        $statuses = ['pending', 'in_progress', 'completed', 'cancelled'];
        
        return view('admin.jewelry.repair-orders.index', compact('repairOrders', 'stats', 'statuses'));
    }
    
    /**
     * نمایش فرم ایجاد سفارش تعمیر جدید برای ادمین
     */
    public function create()
    {
        $jewelries = \App\Models\JewelryCertificate::with('owner.user')->get();
        $technicians = RepairTechnician::with('user')
            ->active()
            ->whereHas('user')
            ->get();
        
        return view('admin.jewelry.repair-orders.create', compact('jewelries', 'technicians'));
    }
    
    /**
     * ذخیره سفارش تعمیر جدید (توسط ادمین)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jewelry_certificate_id' => 'required|exists:jewelry_certificates,id',
            'description' => 'required|string|min:10|max:2000',
            'repair_type' => 'required|string|max:255',
            'location' => 'required|in:workshop,customer',
            'scheduled_date' => 'nullable|date',
            'estimated_cost' => 'nullable|numeric|min:0',
            'priority' => 'nullable|in:normal,urgent',
            'assigned_to' => 'nullable|exists:repair_technicians,user_id,is_active,1',
        ]);
        
        // Get the user_id from the jewelry certificate's owner - اصلاح شده با بررسی null
        $jewelry = \App\Models\JewelryCertificate::findOrFail($validated['jewelry_certificate_id']);
        
        // بررسی وجود owner و user_id
        if ($jewelry->owner && $jewelry->owner->user_id) {
            $validated['user_id'] = $jewelry->owner->user_id;
        } else {
            // اگر کاربر وجود نداشت، از کاربر لاگین شده استفاده کن
            $validated['user_id'] = auth()->id();
        }
        
        $validated['status'] = 'pending';
        $validated['order_number'] = RepairOrder::generateOrderNumber();
        
        $repairOrder = RepairOrder::create($validated);
        
        return redirect()->route('admin.jewelry.repair-orders.show', $repairOrder)
            ->with('success', 'سفارش تعمیر با موفقیت ایجاد شد.');
    }
    
    /**
     * نمایش جزئیات یک سفارش تعمیر برای ادمین
     */
    public function show($id)
    {
        $repairOrder = RepairOrder::with([
            'user', 
            'jewelry.owner.user',
            'technician'
        ])->findOrFail($id);
        
        $technicians = User::where('user_type', 'staff')
            ->orWhere('user_type', 'admin')
            ->where('id', '!=', auth()->id())
            ->get();
        
        return view('admin.jewelry.repair-orders.show', compact('repairOrder', 'technicians'));
    }
    
    /**
     * به‌روزرسانی وضعیت سفارش تعمیر توسط ادمین
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled,delivered',
            'notes' => 'nullable|string|max:500',
            'final_cost' => 'nullable|numeric|min:0',
        ]);
        
        $repairOrder = RepairOrder::findOrFail($id);
        
        // بررسی انتقال وضعیت مجاز
        $allowedTransitions = $this->getAllowedStatusTransitions($repairOrder->status);
        if (!in_array($request->get('status'), $allowedTransitions)) {
            return redirect()->back()
                ->with('error', 'تغییر وضعیت مجاز نیست.');
        }
        
        $oldStatus = $repairOrder->status;
        
        $updateData = [
            'status' => $request->get('status'),
        ];
        
        if ($request->has('notes')) {
            $updateData['notes'] = $request->get('notes');
        }
        
        if ($request->has('final_cost')) {
            $updateData['final_cost'] = $request->get('final_cost');
        }
        
        // اگر وضعیت به "completed" تغییر کرد
        if ($request->get('status') == 'completed') {
            $updateData['completion_date'] = Carbon::now();
        }
        
        // اگر وضعیت به "delivered" تغییر کرد
        if ($request->get('status') == 'delivered') {
            $updateData['delivery_date'] = Carbon::now();
        }
        
        // اگر وضعیت به "cancelled" تغییر کرد
        if ($request->get('status') == 'cancelled') {
            $updateData['cancelled_at'] = Carbon::now();
            $updateData['cancelled_by'] = auth()->id();
        }
        
        $repairOrder->update($updateData);
        
        // لاگ تغییر وضعیت - با بررسی وجود جدول
        try {
            DB::table('repair_order_status_logs')->insert([
                'repair_order_id' => $repairOrder->id,
                'old_status' => $oldStatus,
                'new_status' => $request->get('status'),
                'changed_by' => auth()->id(),
                'notes' => $request->get('notes'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        } catch (\Exception $e) {
            // جدول لاگ وجود ندارد - نادیده گرفته شود
        }
        
        return redirect()->route('admin.jewelry.repair-orders.show', $repairOrder)
            ->with('success', 'وضعیت سفارش تعمیر با موفقیت به‌روزرسانی شد.');
    }
    
    /**
     * نمایش فرم اختصاص سفارش به تکنسین
     */
    public function assignForm($id)
    {
        $repairOrder = RepairOrder::with('user')->findOrFail($id);
        
        $technicians = User::where('user_type', 'staff')
            ->orWhere('user_type', 'admin')
            ->where('id', '!=', auth()->id())
            ->get(['id', 'name', 'email', 'phone']);
        
        return view('admin.jewelry.repair-orders.assign', compact('repairOrder', 'technicians'));
    }
    
    /**
     * اختصاص سفارش به تکنسین
     */
    public function assign(Request $request, $id)
    {
        $request->validate([
            'technician_id' => 'required|exists:users,id',
            'assignment_notes' => 'nullable|string|max:500',
            'estimated_completion_date' => 'nullable|date|after:today',
        ]);
        
        $repairOrder = RepairOrder::findOrFail($id);
        
        // بررسی اینکه تکنسین انتخاب شده مجاز باشد
        $technician = User::findOrFail($request->get('technician_id'));
        if (!in_array($technician->user_type, ['staff', 'admin'])) {
            return redirect()->back()
                ->with('error', 'فقط کارمندان و ادمین‌ها می‌توانند به عنوان تکنسین انتخاب شوند.');
        }
        
        $repairOrder->update([
            'assigned_to' => $request->get('technician_id'),
            'assigned_at' => Carbon::now(),
            'assignment_notes' => $request->get('assignment_notes'),
            'estimated_completion_date' => $request->get('estimated_completion_date'),
            'status' => 'in_progress',
        ]);
        
        return redirect()->route('admin.jewelry.repair-orders.show', $repairOrder)
            ->with('success', 'سفارش تعمیر با موفقیت به تکنسین اختصاص داده شد.');
    }
    
    /**
     * مشاهده تاریخچه وضعیت‌های سفارش تعمیر
     */
    public function statusHistory($id)
    {
        $repairOrder = RepairOrder::with('user')->findOrFail($id);
        
        try {
            $statusHistory = DB::table('repair_order_status_logs')
                ->where('repair_order_id', $id)
                ->join('users', 'repair_order_status_logs.changed_by', '=', 'users.id')
                ->select('repair_order_status_logs.*', 'users.name as changer_name')
                ->orderBy('created_at', 'desc')
                ->get();
        } catch (\Exception $e) {
            $statusHistory = collect();
        }
        
        return view('admin.jewelry.repair-orders.status-history', compact('repairOrder', 'statusHistory'));
    }
    
    /**
     * آپلود تصویر تکمیلی توسط ادمین
     */
    public function uploadAdminPhoto(Request $request, $id)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'photo_description' => 'nullable|string|max:255',
        ]);
        
        $repairOrder = RepairOrder::findOrFail($id);
        
        // آپلود تصویر
        $path = $request->file('photo')->store('repair-orders/admin', 'public');
        
        // ذخیره اطلاعات تصویر در دیتابیس
        try {
            DB::table('repair_order_photos')->insert([
                'repair_order_id' => $repairOrder->id,
                'photo_path' => $path,
                'uploaded_by' => auth()->id(),
                'description' => $request->get('photo_description'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        } catch (\Exception $e) {
            // جدول عکس وجود ندارد
        }
        
        return redirect()->route('admin.jewelry.repair-orders.show', $repairOrder)
            ->with('success', 'تصویر با موفقیت آپلود شد.');
    }
    
    /**
     * حذف سفارش تعمیر توسط ادمین
     */
    public function destroy($id)
    {
        $repairOrder = RepairOrder::findOrFail($id);
        
        // فقط سفارشات لغو شده یا تکمیل شده قدیمی قابل حذف هستند
        if (!in_array($repairOrder->status, ['cancelled', 'completed'])) {
            return redirect()->back()
                ->with('error', 'فقط سفارشات تکمیل شده یا لغو شده قابل حذف هستند.');
        }
        
        DB::transaction(function () use ($repairOrder) {
            // حذف عکس اصلی
            if ($repairOrder->photo && \Storage::disk('public')->exists($repairOrder->photo)) {
                \Storage::disk('public')->delete($repairOrder->photo);
            }
            
            // حذف عکس‌های اضافی
            try {
                $additionalPhotos = DB::table('repair_order_photos')
                    ->where('repair_order_id', $repairOrder->id)
                    ->get();
                    
                foreach ($additionalPhotos as $photo) {
                    if (\Storage::disk('public')->exists($photo->photo_path)) {
                        \Storage::disk('public')->delete($photo->photo_path);
                    }
                }
                
                DB::table('repair_order_status_logs')->where('repair_order_id', $repairOrder->id)->delete();
                DB::table('repair_order_photos')->where('repair_order_id', $repairOrder->id)->delete();
            } catch (\Exception $e) {
                // جداول وجود ندارند
            }
            
            $repairOrder->delete();
        });
        
        return redirect()->route('admin.jewelry.repair-orders.index')
            ->with('success', 'سفارش تعمیر با موفقیت حذف شد.');
    }
    
    /**
     * تعیین مجوزهای تغییر وضعیت
     */
    private function getAllowedStatusTransitions($currentStatus)
    {
        $transitions = [
            'pending' => ['in_progress', 'cancelled'],
            'in_progress' => ['completed', 'cancelled'],
            'completed' => ['delivered'],
            'delivered' => [],
            'cancelled' => [],
        ];
        
        return $transitions[$currentStatus] ?? [];
    }

    // =========================================================
    // ویرایش سفارش تعمیر
    // =========================================================

    /**
     * فرم ویرایش سفارش تعمیر.
     *
     * [رفع باگ — Stage 2]
     * روت `GET /admin/jewelry/repair-orders/{repair_order}/edit`
     * (نام: admin.jewelry.repair-orders.edit) به این متد اشاره می‌کرد
     * اما متد وجود نداشت و ویرایش سفارش با خطای 500 متوقف می‌شد.
     *
     * @param  int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $repairOrder = RepairOrder::with(['user', 'jewelryCertificate', 'technician'])
            ->findOrFail($id);

        $jewelries = \App\Models\JewelryCertificate::query()
            ->orderBy('serial_number')
            ->get();

        $technicians = User::query()
            ->where('user_type', 'admin')
            ->orWhere('user_type', 'staff')
            ->orderBy('name')
            ->get();

        return view('admin.jewelry.repair-orders.edit',
            compact('repairOrder', 'jewelries', 'technicians'));
    }

    /**
     * ذخیره تغییرات سفارش تعمیر.
     *
     * [رفع باگ — Stage 2]
     * روت `PUT/PATCH /admin/jewelry/repair-orders/{repair_order}`
     * (نام: admin.jewelry.repair-orders.update) به این متد اشاره می‌کرد
     * اما متد وجود نداشت.
     *
     * نکات امنیتی:
     *   • تمام ورودی‌ها اعتبارسنجی می‌شوند.
     *   • فیلدهای حساس مانند `status` و `final_cost` تنها با مجوز
     *     `jewelry.repairs.update_status` تغییر می‌کنند؛ در غیر این صورت
     *     مقدار فعلی حفظ می‌شود.
     *   • تغییرات در تراکنش انجام می‌شود و در لاگ حسابرسی ثبت می‌گردد.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                        $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $repairOrder = RepairOrder::findOrFail($id);

        $validated = $request->validate([
            'jewelry_certificate_id' => 'nullable|integer|exists:jewelry_certificates,id',
            'repair_type'            => 'required|string|max:120',
            'description'            => 'required|string|max:2000',
            'location'               => 'nullable|string|max:255',
            'scheduled_date'         => 'nullable|date',
            'estimated_delivery_date'=> 'nullable|date|after_or_equal:scheduled_date',
            'estimated_cost'         => 'nullable|numeric|min:0',
            'final_cost'             => 'nullable|numeric|min:0',
            'assigned_to'            => 'nullable|integer|exists:users,id',
            'priority'               => 'nullable|in:low,normal,high,urgent',
            'notes'                  => 'nullable|string|max:2000',
            'status'                 => 'nullable|string|in:' . implode(',', array_keys(self::STATUSES)),
        ], [
            'repair_type.required'    => __('نوع تعمیر الزامی است.'),
            'description.required'    => __('شرح مشکل الزامی است.'),
            'final_cost.min'          => __('هزینه نهایی نمی‌تواند منفی باشد.'),
            'estimated_delivery_date.after_or_equal' => __('تاریخ تحویل نمی‌تواند پیش از تاریخ پذیرش باشد.'),
        ]);

        // تغییر وضعیت و هزینه نهایی نیازمند مجوز مستقل است
        $canUpdateStatus = Auth::user()->hasPermissionTo('jewelry.repairs.update_status');

        if (!$canUpdateStatus) {
            unset($validated['status'], $validated['final_cost']);
        }

        // تنها مجوز `jewelry.repairs.assign` اجازه تغییر تکنسین می‌دهد
        if (!Auth::user()->hasPermissionTo('jewelry.repairs.assign')) {
            unset($validated['assigned_to']);
        }

        DB::transaction(function () use ($repairOrder, $validated) {

            $before = $repairOrder->only([
                'status', 'final_cost', 'assigned_to', 'priority',
            ]);

            $repairOrder->fill($validated);

            // پر کردن تاریخ تکمیل در صورت تغییر وضعیت به «تحویل‌شده»
            if (isset($validated['status'])
                && in_array($validated['status'], ['delivered', 'completed'], true)
                && empty($repairOrder->completion_date)) {
                $repairOrder->completion_date = Carbon::now();
            }

            $repairOrder->save();

            $after = $repairOrder->only([
                'status', 'final_cost', 'assigned_to', 'priority',
            ]);

            // ثبت تغییرات حساس در Audit Log
            if ($before != $after) {
                app(\App\Services\AuditLogger::class)->logModelChange(
                    'repair_order.updated',
                    $repairOrder,
                    $before,
                    $after,
                    'ویرایش سفارش تعمیر ' . $repairOrder->order_number
                );
            }
        });

        flash(__('سفارش تعمیر با موفقیت به‌روزرسانی شد.'))->success();

        return redirect()->route('admin.jewelry.repair-orders.show', $repairOrder->id);
    }

    /**
     * تاریخچه تعمیرات یک قطعه جواهر.
     *
     * [رفع باگ — Stage 2]
     * روت `GET /admin/jewelry/repair-orders/jewelry/{jewelryId}/history`
     * (نام: admin.jewelry.repair-orders.jewelry_history) به این متد اشاره
     * می‌کرد اما متد وجود نداشت.
     *
     * @param  int $jewelryId شناسه شناسنامه جواهر
     * @return \Illuminate\View\View
     */
    public function jewelryHistory($jewelryId)
    {
        $certificate = \App\Models\JewelryCertificate::with('owner')
            ->findOrFail($jewelryId);

        $repairOrders = RepairOrder::query()
            ->where('jewelry_certificate_id', $certificate->id)
            ->with(['technician', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.jewelry.repair-orders.jewelry-history',
            compact('certificate', 'repairOrders'));
    }
}