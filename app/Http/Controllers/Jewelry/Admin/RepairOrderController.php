<?php

namespace App\Http\Controllers\Jewelry\Admin;

use App\Http\Controllers\Controller;
use App\Models\RepairOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RepairOrderController extends Controller
{
    /**
     * نمایش لیست تمام سفارشات تعمیر برای ادمین
     */
    public function index(Request $request)
    {
        $query = RepairOrder::with(['user', 'jewelry.owner']);
        
        // فیلتر بر اساس وضعیت
        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }
        
        // فیلتر بر اساس نوع تعمیر
        if ($request->has('repair_type')) {
            $query->where('repair_type', 'LIKE', "%{$request->get('repair_type')}%");
        }
        
        // جستجو بر اساس توضیحات یا شماره سفارش
        if ($request->has('search')) {
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
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }
        
        $repairOrders = $query->orderBy('created_at', 'desc')->paginate(20);
        
        $stats = [
            'total' => RepairOrder::count(),
            'pending' => RepairOrder::where('status', 'در انتظار')->count(),
            'in_progress' => RepairOrder::where('status', 'در حال تعمیر')->count(),
            'completed' => RepairOrder::where('status', 'تکمیل شده')->count(),
            'cancelled' => RepairOrder::where('status', 'لغو شده')->count(),
        ];
        
        $statuses = ['در انتظار', 'در حال تعمیر', 'تکمیل شده', 'لغو شده'];
        
        return view('admin.jewelry.repair-orders.index', compact('repairOrders', 'stats', 'statuses'));
    }
    
    /**
     * نمایش فرم ایجاد سفارش تعمیر جدید برای ادمین
     */
    public function create()
    {
        $jewelries = \App\Models\JewelryCertificate::with('owner.user')->get();
        
        return view('admin.jewelry.repair-orders.create', compact('jewelries'));
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
        ]);
        
        // Get the user_id from the jewelry certificate's owner
        $jewelry = \App\Models\JewelryCertificate::findOrFail($validated['jewelry_certificate_id']);
        $validated['user_id'] = $jewelry->owner->user_id;
        
        $validated['status'] = 'در انتظار';
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
            'status' => 'required|in:در انتظار,در حال تعمیر,تکمیل شده,لغو شده',
            'status_notes' => 'nullable|string|max:500',
        ]);
        
        $repairOrder = RepairOrder::findOrFail($id);
        
        // بررسی انتقال وضعیت مجاز
        $allowedTransitions = $this->getAllowedStatusTransitions($repairOrder->status);
        if (!in_array($request->get('status'), $allowedTransitions)) {
            return redirect()->back()
                ->with('error', 'تغییر وضعیت از ' . $repairOrder->status . ' به ' . $request->get('status') . ' مجاز نیست.');
        }
        
        $oldStatus = $repairOrder->status;
        
        $updateData = [
            'status' => $request->get('status'),
            'status_notes' => $request->get('status_notes'),
        ];
        
        // اگر وضعیت به "تکمیل شده" تغییر کرد
        if ($request->get('status') == 'تکمیل شده') {
            $updateData['completed_at'] = Carbon::now();
            $updateData['completed_by'] = auth()->id();
        }
        
        // اگر وضعیت به "لغو شده" تغییر کرد
        if ($request->get('status') == 'لغو شده') {
            $updateData['cancelled_at'] = Carbon::now();
            $updateData['cancelled_by'] = auth()->id();
        }
        
        $repairOrder->update($updateData);
        
        // TODO: ارسال نوتیفیکیشن به کاربر
        
        // لاگ تغییر وضعیت
        DB::table('repair_order_status_logs')->insert([
            'repair_order_id' => $repairOrder->id,
            'old_status' => $oldStatus,
            'new_status' => $request->get('status'),
            'changed_by' => auth()->id(),
            'notes' => $request->get('status_notes'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        
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
            'status' => 'در حال تعمیر', // هنگام اختصاص، وضعیت به "در حال تعمیر" تغییر می‌کند
        ]);
        
        // TODO: ارسال نوتیفیکیشن به تکنسین
        
        return redirect()->route('admin.jewelry.repair-orders.show', $repairOrder)
            ->with('success', 'سفارش تعمیر با موفقیت به تکنسین اختصاص داده شد.');
    }
    
    /**
     * مشاهده تاریخچه وضعیت‌های سفارش تعمیر
     */
    public function statusHistory($id)
    {
        $repairOrder = RepairOrder::with('user')->findOrFail($id);
        
        $statusHistory = DB::table('repair_order_status_logs')
            ->where('repair_order_id', $id)
            ->join('users', 'repair_order_status_logs.changed_by', '=', 'users.id')
            ->select('repair_order_status_logs.*', 'users.name as changer_name')
            ->orderBy('created_at', 'desc')
            ->get();
        
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
        DB::table('repair_order_photos')->insert([
            'repair_order_id' => $repairOrder->id,
            'photo_path' => $path,
            'uploaded_by' => auth()->id(),
            'description' => $request->get('photo_description'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        
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
        if (!in_array($repairOrder->status, ['لغو شده', 'تکمیل شده'])) {
            return redirect()->back()
                ->with('error', 'فقط سفارشات تکمیل شده یا لغو شده قابل حذف هستند.');
        }
        
        DB::transaction(function () use ($repairOrder) {
            // حذف عکس اصلی
            if ($repairOrder->photo && \Storage::disk('public')->exists($repairOrder->photo)) {
                \Storage::disk('public')->delete($repairOrder->photo);
            }
            
            // حذف عکس‌های اضافی
            $additionalPhotos = DB::table('repair_order_photos')
                ->where('repair_order_id', $repairOrder->id)
                ->get();
                
            foreach ($additionalPhotos as $photo) {
                if (\Storage::disk('public')->exists($photo->photo_path)) {
                    \Storage::disk('public')->delete($photo->photo_path);
                }
            }
            
            // حذف رکوردهای مرتبط
            DB::table('repair_order_status_logs')->where('repair_order_id', $repairOrder->id)->delete();
            DB::table('repair_order_photos')->where('repair_order_id', $repairOrder->id)->delete();
            
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
            'در انتظار' => ['در حال تعمیر', 'لغو شده'],
            'در حال تعمیر' => ['تکمیل شده', 'لغو شده'],
            'تکمیل شده' => [], // پس از تکمیل، هیچ تغییری مجاز نیست
            'لغو شده' => [], // پس از لغو، هیچ تغییری مجاز نیست
        ];
        
        return $transitions[$currentStatus] ?? [];
    }
}