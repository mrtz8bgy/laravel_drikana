<?php

namespace App\Http\Controllers;

use App\Models\RepairOrder;
use App\Models\JewelryCertificate;
use App\Models\RepairTechnician;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RepairOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * نمایش لیست سفارشات تعمیر
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = RepairOrder::with(['jewelry.owner', 'technician']);
        
        // دسترسی‌ها بر اساس user_type
        if ($user->user_type == 'customer') {
            $query->where('user_id', $user->id);
        } elseif ($user->user_type == 'technician') {
            $query->where('assigned_to', $user->id);
        }
        // مدیر همه را می‌بیند (user_type = 'admin')
        
        // فیلترها
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('repair_type')) {
            $query->where('repair_type', $request->repair_type);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('jewelry', function($sq) use ($search) {
                      $sq->where('product_name', 'like', "%{$search}%")
                         ->orWhere('serial_number', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $orders = $query->latest()->paginate(15);
        
        return view('admin.jewelry.repair-orders.index', compact('orders'));
    }
    
    /**
     * نمایش فرم ثبت سفارش جدید
     */
    public function create()
    {
        // فقط قطعاتی که مالکش کاربر فعلی هست
        $jewelries = JewelryCertificate::with('owner')
                                       ->whereHas('owner', function($query) {
                                           $query->where('user_id', Auth::id());
                                       })
                                       ->get();
        
        // لیست تکنسین‌ها برای مدیر
        $technicians = [];
        if (Auth::user()->user_type == 'admin') {
            $technicians = RepairTechnician::with('user')
                                          ->where('is_active', true)
                                          ->get();
        }
        
        return view('admin.jewelry.repair-orders.create', compact('jewelries', 'technicians'));
    }
    
    /**
     * ذخیره سفارش جدید
     */
    public function store(Request $request)
    {
        $request->validate([
            'jewelry_certificate_id' => 'required|exists:jewelry_certificates,id',
            'repair_type' => 'required|in:cleaning,restoration,resizing,other',
            'description' => 'required|string|min:10',
            'location' => 'nullable|in:customer,workshop',
            'scheduled_date' => 'nullable|date|after_or_equal:today',
            'estimated_delivery_date' => 'nullable|date|after_or_equal:today',
            'estimated_cost' => 'nullable|numeric|min:0',
            'assigned_to' => 'nullable|exists:users,id',
            'priority' => 'nullable|in:normal,urgent',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notes' => 'nullable|string|max:1000',
        ]);
        
        // چک مالکیت قطعه
        $jewelry = JewelryCertificate::whereHas('owner', function($query) {
                                         $query->where('user_id', Auth::id());
                                     })
                                     ->findOrFail($request->jewelry_certificate_id);
        
        // آپلود تصاویر
        $photos = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('repair-orders', 'public');
                $photos[] = $path;
            }
        }
        
        // ایجاد سفارش
        $order = RepairOrder::create([
            'order_number' => RepairOrder::generateOrderNumber(),
            'user_id' => Auth::id(),
            'jewelry_certificate_id' => $request->jewelry_certificate_id,
            'repair_type' => $request->repair_type,
            'description' => $request->description,
            'location' => $request->location ?? 'workshop',
            'scheduled_date' => $request->scheduled_date,
            'estimated_delivery_date' => $request->estimated_delivery_date,
            'estimated_cost' => $request->estimated_cost,
            'assigned_to' => $request->assigned_to,
            'priority' => $request->priority ?? 'normal',
            'photo' => !empty($photos) ? json_encode($photos) : null,
            'notes' => $request->notes,
            'status' => 'pending'
        ]);
        
        // ثبت در تاریخچه
        $order->updateStatus('pending', Auth::id(), 'ثبت اولیه سفارش');
        
        return redirect()->route('admin.jewelry.repair-orders.show', $order->id)
                         ->with('success', 'سفارش تعمیر با موفقیت ثبت شد');
    }
    
    /**
     * نمایش جزییات سفارش
     */
    public function show($id)
    {
        $order = RepairOrder::with(['jewelry.owner', 'user', 'technician', 'statusHistory.user'])
                            ->findOrFail($id);
        
        // بررسی دسترسی
        $this->checkAccess($order);
        
        return view('admin.jewelry.repair-orders.show', compact('order'));
    }
    
    /**
     * نمایش فرم ویرایش
     */
    public function edit($id)
    {
        $order = RepairOrder::findOrFail($id);
        $this->checkAccess($order, 'admin'); // فقط مدیر می‌تونه ویرایش کنه
        
        $jewelries = JewelryCertificate::with('owner')
                                       ->whereHas('owner', function($query) use ($order) {
                                           $query->where('user_id', $order->user_id);
                                       })
                                       ->get();
        
        $technicians = RepairTechnician::with('user')
                                      ->where('is_active', true)
                                      ->get();
        
        return view('admin.jewelry.repair-orders.edit', compact('order', 'jewelries', 'technicians'));
    }
    
    /**
     * بروزرسانی سفارش
     */
    public function update(Request $request, $id)
    {
        $order = RepairOrder::findOrFail($id);
        $this->checkAccess($order, 'admin');
        
        $request->validate([
            'jewelry_certificate_id' => 'required|exists:jewelry_certificates,id',
            'description' => 'required|string|min:10',
            'repair_type' => 'required|in:cleaning,restoration,resizing,other',
            'location' => 'nullable|in:customer,workshop',
            'scheduled_date' => 'nullable|date',
            'estimated_delivery_date' => 'nullable|date',
            'estimated_cost' => 'nullable|numeric|min:0',
            'assigned_to' => 'nullable|exists:users,id',
            'priority' => 'nullable|in:normal,urgent',
            'notes' => 'nullable|string',
        ]);
        
        // بررسی مالکیت قطعه
        $jewelry = JewelryCertificate::whereHas('owner', function($query) use ($order) {
                                         $query->where('user_id', $order->user_id);
                                     })
                                     ->findOrFail($request->jewelry_certificate_id);
        
        $order->update($request->only([
            'jewelry_certificate_id', 'description', 'repair_type', 'location',
            'scheduled_date', 'estimated_delivery_date', 'estimated_cost',
            'assigned_to', 'priority', 'notes'
        ]));
        
        return redirect()->route('admin.jewelry.repair-orders.show', $order->id)
                         ->with('success', 'سفارش با موفقیت بروزرسانی شد');
    }
    
    /**
     * حذف سفارش
     */
    public function destroy($id)
    {
        $order = RepairOrder::findOrFail($id);
        $this->checkAccess($order, 'admin');
        
        // فقط سفارشات در حالت pending قابل حذف هستند
        if ($order->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'فقط سفارش‌های در انتظار قابل حذف هستند.');
        }
        
        // حذف تصاویر
        if ($order->photo) {
            $photos = json_decode($order->photo, true) ?? [];
            foreach ($photos as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }
        
        // حذف تاریخچه وضعیت
        $order->statusHistory()->delete();
        
        $order->delete();
        
        return redirect()->route('admin.jewelry.repair-orders.index')
                         ->with('success', 'سفارش با موفقیت حذف شد');
    }
    
    /**
     * تغییر وضعیت سفارش
     */
    public function updateStatus(Request $request, $id)
    {
        $order = RepairOrder::findOrFail($id);
        $this->checkAccess($order, 'technician'); // تکنسین و مدیر می‌تونن
        
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,delivered,cancelled',
            'notes' => 'nullable|string',
            'final_cost' => 'nullable|numeric|min:0',
        ]);
        
        // اگر وضعیت به completed تغییر کرد، هزینه نهایی رو ثبت کن
        if ($request->status == 'completed' && $request->filled('final_cost')) {
            $order->final_cost = $request->final_cost;
        }
        
        $order->updateStatus($request->status, Auth::id(), $request->notes);
        
        $message = 'وضعیت سفارش با موفقیت به ' . $order->status_label . ' تغییر کرد';
        
        return redirect()->route('admin.jewelry.repair-orders.show', $order->id)
                         ->with('success', $message);
    }
    
    /**
     * دریافت لیست سفارشات برای یک قطعه (سابقه تعمیرات)
     */
    public function jewelryHistory($jewelryId)
    {
        $orders = RepairOrder::with(['technician', 'statusHistory'])
                            ->where('jewelry_certificate_id', $jewelryId)
                            ->latest()
                            ->get();
        
        return response()->json($orders);
    }
    
    /**
     * بررسی دسترسی
     */
    private function checkAccess($order, $minimumRole = 'customer')
    {
        $user = Auth::user();
        
        if ($user->user_type == 'admin') {
            return true;
        }
        
        if ($minimumRole == 'admin' && $user->user_type != 'admin') {
            abort(403, 'شما اجازه دسترسی به این بخش را ندارید');
        }
        
        if ($user->user_type == 'technician') {
            if ($order->assigned_to == $user->id) {
                return true;
            }
            abort(403, 'این سفارش به شما اختصاص داده نشده است');
        }
        
        // customer
        if ($order->user_id != $user->id) {
            abort(403, 'شما اجازه دسترسی به این سفارش را ندارید');
        }
        
        return true;
    }
}