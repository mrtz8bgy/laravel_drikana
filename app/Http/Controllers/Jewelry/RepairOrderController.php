<?php

namespace App\Http\Controllers\Jewelry;

use App\Http\Controllers\Controller;
use App\Models\RepairOrder;
use App\Models\JewelryCertificate;
use App\Models\RepairStatusHistory;
use App\Models\RepairTechnician;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RepairOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = RepairOrder::with(['jewelry', 'technician', 'user']);
        
        // دسترسی‌ها بر اساس نقش
        if ($user->role == 'customer') {
            $query->where('user_id', $user->id);
        } elseif ($user->role == 'technician') {
            $query->where('assigned_to', $user->id);
        }
        // مدیر همه را می‌بیند
        
        // فیلتر بر اساس وضعیت
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // فیلتر بر اساس نوع تعمیر
        if ($request->filled('repair_type')) {
            $query->where('repair_type', $request->repair_type);
        }
        
        // جستجو
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhereHas('jewelry', function($q2) use ($search) {
                      $q2->where('serial_number', 'LIKE', "%{$search}%")
                         ->orWhere('product_name', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        // فیلتر تاریخ
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $repairOrders = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // آمار برای نمایش
        $stats = [
            'total' => RepairOrder::count(),
            'pending' => RepairOrder::where('status', 'pending')->count(),
            'in_progress' => RepairOrder::where('status', 'in_progress')->count(),
            'completed' => RepairOrder::where('status', 'completed')->count(),
            'delivered' => RepairOrder::where('status', 'delivered')->count(),
        ];
        
        $repairTypes = [
            'cleaning' => 'تمیزکاری',
            'restoration' => 'بازسازی',
            'resizing' => 'تغییر اندازه',
            'other' => 'سایر'
        ];
        
        $statuses = [
            'pending' => 'در انتظار',
            'in_progress' => 'در حال انجام',
            'completed' => 'تکمیل شده',
            'delivered' => 'تحویل شده'
        ];
        
        return view('admin.jewelry.repair-orders.index', compact('repairOrders', 'stats', 'repairTypes', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // قطعات متعلق به کاربر
        $jewelries = JewelryCertificate::whereHas('owner', function($q) {
            $q->where('user_id', Auth::id());
        })->get();
        
        // لیست تکنسین‌ها برای مدیر
        $technicians = [];
        if (Auth::user()->role == 'admin') {
            $technicians = RepairTechnician::with('user')
                                          ->where('is_active', true)
                                          ->get();
        }
        
        return view('admin.jewelry.repair-orders.create', compact('jewelries', 'technicians'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jewelry_certificate_id' => 'required|exists:jewelry_certificates,id',
            'description' => 'required|string|min:10|max:2000',
            'repair_type' => 'required|in:cleaning,restoration,resizing,other',
            'location' => 'nullable|in:workshop,customer',
            'scheduled_date' => 'nullable|date|after_or_equal:today',
            'estimated_delivery_date' => 'nullable|date|after_or_equal:today',
            'estimated_cost' => 'nullable|numeric|min:0',
            'assigned_to' => 'nullable|exists:users,id',
            'priority' => 'nullable|in:normal,urgent',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notes' => 'nullable|string|max:1000',
        ], [
            'jewelry_certificate_id.required' => 'انتخاب قطعه الزامی است',
            'description.required' => 'توضیحات مشکل الزامی است',
            'description.min' => 'توضیحات باید حداقل 10 کاراکتر باشد',
            'repair_type.required' => 'نوع تعمیر الزامی است',
            'images.*.image' => 'فایل انتخاب شده باید تصویر باشد',
            'images.*.mimes' => 'فرمت تصویر باید jpeg, png, jpg یا gif باشد',
            'images.*.max' => 'حجم تصویر نباید بیشتر از 2 مگابایت باشد',
        ]);
        
        // بررسی مالکیت قطعه
        $jewelry = JewelryCertificate::whereHas('owner', function($q) {
                $q->where('user_id', Auth::id());
            })
            ->findOrFail($validated['jewelry_certificate_id']);
        
        // آپلود تصاویر
        $photos = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('repair-orders', 'public');
                $photos[] = $path;
            }
        }
        
        // ایجاد سفارش
        $repairOrder = RepairOrder::create([
            'order_number' => RepairOrder::generateOrderNumber(),
            'user_id' => Auth::id(),
            'jewelry_certificate_id' => $validated['jewelry_certificate_id'],
            'repair_type' => $validated['repair_type'],
            'description' => $validated['description'],
            'location' => $validated['location'] ?? 'workshop',
            'scheduled_date' => $validated['scheduled_date'] ?? null,
            'estimated_delivery_date' => $validated['estimated_delivery_date'] ?? null,
            'estimated_cost' => $validated['estimated_cost'] ?? null,
            'assigned_to' => $validated['assigned_to'] ?? null,
            'priority' => $validated['priority'] ?? 'normal',
            'photo' => !empty($photos) ? json_encode($photos) : null,
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending'
        ]);
        
        // ثبت در تاریخچه
        $repairOrder->updateStatus('pending', Auth::id(), 'ثبت اولیه سفارش');
        
        return redirect()->route('admin.jewelry.repair-orders.show', $repairOrder->id)
            ->with('success', 'سفارش تعمیر با موفقیت ثبت شد.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $repairOrder = RepairOrder::with([
            'jewelry' => function($query) {
                $query->with('owner');
            }, 
            'user', 
            'technician', 
            'statusHistory.user'
        ])->findOrFail($id);
        
        // بررسی دسترسی
        $this->checkAccess($repairOrder);
        
        // برای دیباگ - اگر می‌خواهید ببینید چه داده‌هایی وجود دارد
        // dd($repairOrder->toArray());
        
        return view('admin.jewelry.repair-orders.show', compact('repairOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $repairOrder = RepairOrder::findOrFail($id);
        
        // بررسی دسترسی (فقط مدیر)
        if (Auth::user()->role != 'admin') {
            abort(403, 'دسترسی غیرمجاز');
        }
        
        // فقط سفارشات در حالت "pending" قابل ویرایش هستند
        if ($repairOrder->status !== 'pending') {
            return redirect()->route('admin.jewelry.repair-orders.show', $repairOrder->id)
                ->with('error', 'سفارش‌های در حال انجام یا تکمیل شده قابل ویرایش نیستند.');
        }
        
        $jewelries = JewelryCertificate::whereHas('owner', function($q) use ($repairOrder) {
            $q->where('user_id', $repairOrder->user_id);
        })->get();
        
        $technicians = RepairTechnician::with('user')
                                      ->where('is_active', true)
                                      ->get();
        
        return view('admin.jewelry.repair-orders.edit', compact('repairOrder', 'jewelries', 'technicians'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $repairOrder = RepairOrder::findOrFail($id);
        
        // بررسی دسترسی (فقط مدیر)
        if (Auth::user()->role != 'admin') {
            abort(403, 'دسترسی غیرمجاز');
        }
        
        // فقط سفارشات در حالت "pending" قابل ویرایش هستند
        if ($repairOrder->status !== 'pending') {
            return redirect()->route('admin.jewelry.repair-orders.show', $repairOrder->id)
                ->with('error', 'سفارش‌های در حال انجام یا تکمیل شده قابل ویرایش نیستند.');
        }
        
        $validated = $request->validate([
            'jewelry_certificate_id' => 'required|exists:jewelry_certificates,id',
            'description' => 'required|string|min:10|max:2000',
            'repair_type' => 'required|in:cleaning,restoration,resizing,other',
            'location' => 'nullable|in:workshop,customer',
            'scheduled_date' => 'nullable|date|after_or_equal:today',
            'estimated_delivery_date' => 'nullable|date|after_or_equal:today',
            'estimated_cost' => 'nullable|numeric|min:0',
            'assigned_to' => 'nullable|exists:users,id',
            'priority' => 'nullable|in:normal,urgent',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notes' => 'nullable|string|max:1000',
        ]);
        
        // بررسی مالکیت قطعه
        $jewelry = JewelryCertificate::whereHas('owner', function($q) use ($repairOrder) {
                $q->where('user_id', $repairOrder->user_id);
            })
            ->findOrFail($validated['jewelry_certificate_id']);
        
        // آپلود تصاویر جدید
        if ($request->hasFile('images')) {
            // حذف عکس‌های قبلی
            if ($repairOrder->photo) {
                $oldPhotos = json_decode($repairOrder->photo, true) ?? [];
                foreach ($oldPhotos as $oldPhoto) {
                    Storage::disk('public')->delete($oldPhoto);
                }
            }
            
            $photos = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('repair-orders', 'public');
                $photos[] = $path;
            }
            $validated['photo'] = json_encode($photos);
        } else {
            // حفظ عکس‌های قبلی
            $validated['photo'] = $repairOrder->photo;
        }
        
        $repairOrder->update($validated);
        
        return redirect()->route('admin.jewelry.repair-orders.show', $repairOrder->id)
            ->with('success', 'سفارش تعمیر با موفقیت بروزرسانی شد.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $repairOrder = RepairOrder::findOrFail($id);
        
        // بررسی دسترسی (فقط مدیر)
        if (Auth::user()->role != 'admin') {
            abort(403, 'دسترسی غیرمجاز');
        }
        
        // فقط سفارشات در حالت "pending" قابل حذف هستند
        if ($repairOrder->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'فقط سفارش‌های در انتظار قابل حذف هستند.');
        }
        
        // حذف عکس‌ها
        if ($repairOrder->photo) {
            $photos = json_decode($repairOrder->photo, true) ?? [];
            foreach ($photos as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }
        
        // حذف تاریخچه وضعیت
        $repairOrder->statusHistory()->delete();
        
        $repairOrder->delete();
        
        return redirect()->route('admin.jewelry.repair-orders.index')
            ->with('success', 'سفارش تعمیر با موفقیت حذف شد.');
    }
    
    /**
     * تغییر وضعیت سفارش (برای تکنسین و مدیر)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,delivered,cancelled',
            'final_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);
        
        $repairOrder = RepairOrder::findOrFail($id);
        
        // بررسی دسترسی
        if (!in_array(Auth::user()->role, ['admin', 'technician'])) {
            abort(403, 'دسترسی غیرمجاز');
        }
        
        // اگر تکنسین است، فقط سفارش‌های خودش
        if (Auth::user()->role == 'technician' && $repairOrder->assigned_to != Auth::id()) {
            abort(403, 'این سفارش به شما اختصاص داده نشده است');
        }
        
        // اگر وضعیت به completed تغییر کرد، هزینه نهایی رو ثبت کن
        if ($request->status == 'completed' && $request->filled('final_cost')) {
            $repairOrder->final_cost = $request->final_cost;
            $repairOrder->completion_date = now();
        }
        
        // اگر وضعیت به delivered تغییر کرد
        if ($request->status == 'delivered') {
            $repairOrder->delivery_date = now();
        }
        
        // اگر وضعیت به cancelled تغییر کرد
        if ($request->status == 'cancelled') {
            $repairOrder->cancelled_at = now();
            $repairOrder->cancelled_by = Auth::id();
        }
        
        $repairOrder->updateStatus($request->status, Auth::id(), $request->notes);
        
        $message = 'وضعیت سفارش با موفقیت به ' . $repairOrder->status_label . ' تغییر کرد';
        
        return redirect()->route('admin.jewelry.repair-orders.show', $repairOrder->id)
            ->with('success', $message);
    }
    
    /**
     * آپلود عکس اضافی
     */
    public function uploadPhoto(Request $request, $id)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $repairOrder = RepairOrder::findOrFail($id);
        
        // بررسی دسترسی
        if (!in_array(Auth::user()->role, ['admin', 'technician']) && $repairOrder->user_id != Auth::id()) {
            abort(403, 'دسترسی غیرمجاز');
        }
        
        // آپلود عکس جدید
        $path = $request->file('photo')->store('repair-orders/additional', 'public');
        
        // اضافه کردن به عکس‌های موجود
        $existingPhotos = json_decode($repairOrder->photo, true) ?? [];
        $existingPhotos[] = $path;
        $repairOrder->photo = json_encode($existingPhotos);
        $repairOrder->save();
        
        return response()->json([
            'success' => true,
            'message' => 'عکس با موفقیت آپلود شد.',
            'photo_url' => Storage::url($path)
        ]);
    }
    
    /**
     * مشاهده وضعیت سفارش (برای AJAX)
     */
    public function checkStatus($id)
    {
        $repairOrder = RepairOrder::with('statusHistory.user')
            ->findOrFail($id);
        
        // بررسی دسترسی
        if ($repairOrder->user_id != Auth::id() && !in_array(Auth::user()->role, ['admin', 'technician'])) {
            return response()->json(['error' => 'دسترسی غیرمجاز'], 403);
        }
        
        return response()->json([
            'id' => $repairOrder->id,
            'order_number' => $repairOrder->order_number,
            'status' => $repairOrder->status,
            'status_label' => $repairOrder->status_label,
            'status_color' => $repairOrder->status_color,
            'updated_at' => $repairOrder->updated_at->diffForHumans(),
            'last_status' => $repairOrder->statusHistory()->latest()->first(),
        ]);
    }
    
    /**
     * بررسی دسترسی
     */
    private function checkAccess($repairOrder)
    {
        $user = Auth::user();
        
        if ($user->role == 'admin') {
            return true;
        }
        
        if ($user->role == 'technician') {
            if ($repairOrder->assigned_to == $user->id) {
                return true;
            }
            abort(403, 'این سفارش به شما اختصاص داده نشده است');
        }
        
        // customer
        if ($repairOrder->user_id != $user->id) {
            abort(403, 'شما اجازه دسترسی به این سفارش را ندارید');
        }
        
        return true;
    }
}