<?php

namespace App\Http\Controllers\Jewelry;

use App\Http\Controllers\Controller;
use App\Models\RepairOrder;
use App\Models\JewelryCertificate;
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
        $query = RepairOrder::where('user_id', Auth::id())
            ->with('certificate');
        
        // فیلتر بر اساس وضعیت
        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }
        
        // فیلتر بر اساس نوع تعمیر
        if ($request->has('repair_type')) {
            $query->where('repair_type', 'LIKE', "%{$request->get('repair_type')}%");
        }
        
        // جستجو
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('description', 'LIKE', "%{$search}%")
                  ->orWhere('id', 'LIKE', "%{$search}%")
                  ->orWhereHas('certificate', function($q2) use ($search) {
                      $q2->where('serial_number', 'LIKE', "%{$search}%")
                         ->orWhere('product_name', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        $repairOrders = $query->orderBy('created_at', 'desc')->paginate(15);
        
        $statuses = [
            'all' => 'همه',
            'در انتظار' => 'در انتظار',
            'در حال تعمیر' => 'در حال تعمیر',
            'تکمیل شده' => 'تکمیل شده',
            'لغو شده' => 'لغو شده'
        ];
        
        return view('jewelry.repair-orders.index', compact('repairOrders', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $certificates = JewelryCertificate::whereHas('owner', function($q) {
            $q->where('user_id', Auth::id());
        })->get();
        
        return view('jewelry.repair-orders.create', compact('certificates'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jewelry_certificate_id' => 'nullable|exists:jewelry_certificates,id',
            'description' => 'required|string|min:10|max:2000',
            'repair_type' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'urgency_level' => 'nullable|in:عادی,فوری',
            'estimated_cost' => 'nullable|numeric|min:0',
            'preferred_completion_date' => 'nullable|date|after:today',
        ]);
        
        // اگر شناسنامه انتخاب شده، بررسی مالکیت
        if (!empty($validated['jewelry_certificate_id'])) {
            $certificate = JewelryCertificate::findOrFail($validated['jewelry_certificate_id']);
            if ($certificate->owner->user_id !== Auth::id()) {
                abort(403, 'دسترسی غیرمجاز به شناسنامه');
            }
        }
        
        $validated['user_id'] = Auth::id();
        $validated['status'] = 'در انتظار';
        
        // آپلود عکس
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('repair-orders', 'public');
            $validated['photo'] = $path;
        }
        
        $repairOrder = RepairOrder::create($validated);
        
        return redirect()->route('my-jewelry.repair-orders.show', $repairOrder)
            ->with('success', 'سفارش تعمیر با موفقیت ثبت شد.');
    }

    /**
     * Display the specified resource.
     */
    public function show(RepairOrder $repairOrder)
    {
        // بررسی دسترسی
        if ($repairOrder->user_id !== Auth::id()) {
            abort(403, 'دسترسی غیرمجاز');
        }
        
        return view('jewelry.repair-orders.show', compact('repairOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RepairOrder $repairOrder)
    {
        // بررسی دسترسی
        if ($repairOrder->user_id !== Auth::id()) {
            abort(403, 'دسترسی غیرمجاز');
        }
        
        // فقط سفارشات در حالت "در انتظار" قابل ویرایش هستند
        if ($repairOrder->status !== 'در انتظار') {
            return redirect()->route('my-jewelry.repair-orders.show', $repairOrder)
                ->with('error', 'سفارش‌های در حال تعمیر یا تکمیل شده قابل ویرایش نیستند.');
        }
        
        $certificates = JewelryCertificate::whereHas('owner', function($q) {
            $q->where('user_id', Auth::id());
        })->get();
        
        return view('jewelry.repair-orders.edit', compact('repairOrder', 'certificates'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RepairOrder $repairOrder)
    {
        // بررسی دسترسی
        if ($repairOrder->user_id !== Auth::id()) {
            abort(403, 'دسترسی غیرمجاز');
        }
        
        // فقط سفارشات در حالت "در انتظار" قابل ویرایش هستند
        if ($repairOrder->status !== 'در انتظار') {
            return redirect()->route('my-jewelry.repair-orders.show', $repairOrder)
                ->with('error', 'سفارش‌های در حال تعمیر یا تکمیل شده قابل ویرایش نیستند.');
        }
        
        $validated = $request->validate([
            'jewelry_certificate_id' => 'nullable|exists:jewelry_certificates,id',
            'description' => 'required|string|min:10|max:2000',
            'repair_type' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'urgency_level' => 'nullable|in:عادی,فوری',
            'estimated_cost' => 'nullable|numeric|min:0',
            'preferred_completion_date' => 'nullable|date|after:today',
        ]);
        
        // اگر شناسنامه انتخاب شده، بررسی مالکیت
        if (!empty($validated['jewelry_certificate_id'])) {
            $certificate = JewelryCertificate::findOrFail($validated['jewelry_certificate_id']);
            if ($certificate->owner->user_id !== Auth::id()) {
                abort(403, 'دسترسی غیرمجاز به شناسنامه');
            }
        }
        
        // آپلود عکس جدید
        if ($request->hasFile('photo')) {
            // حذف عکس قبلی
            if ($repairOrder->photo) {
                Storage::disk('public')->delete($repairOrder->photo);
            }
            
            $path = $request->file('photo')->store('repair-orders', 'public');
            $validated['photo'] = $path;
        } else {
            // حفظ عکس قبلی
            $validated['photo'] = $repairOrder->photo;
        }
        
        $repairOrder->update($validated);
        
        return redirect()->route('my-jewelry.repair-orders.show', $repairOrder)
            ->with('success', 'سفارش تعمیر با موفقیت بروزرسانی شد.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RepairOrder $repairOrder)
    {
        // بررسی دسترسی
        if ($repairOrder->user_id !== Auth::id()) {
            abort(403, 'دسترسی غیرمجاز');
        }
        
        // فقط سفارشات در حالت "در انتظار" قابل حذف هستند
        if ($repairOrder->status !== 'در انتظار') {
            return redirect()->back()
                ->with('error', 'فقط سفارش‌های در انتظار قابل حذف هستند.');
        }
        
        // حذف عکس
        if ($repairOrder->photo) {
            Storage::disk('public')->delete($repairOrder->photo);
        }
        
        $repairOrder->delete();
        
        return redirect()->route('my-jewelry.repair-orders.index')
            ->with('success', 'سفارش تعمیر با موفقیت حذف شد.');
    }
    
    /**
     * تغییر وضعیت سفارش توسط کاربر
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:لغو شده',
        ]);
        
        $repairOrder = RepairOrder::findOrFail($id);
        
        if ($repairOrder->user_id !== Auth::id()) {
            abort(403, 'دسترسی غیرمجاز');
        }
        
        // کاربران فقط می‌توانند لغو کنند
        if ($request->status !== 'لغو شده') {
            abort(403, 'شما فقط می‌توانید سفارش را لغو کنید.');
        }
        
        // فقط سفارشات در حالت "در انتظار" قابل لغو هستند
        if ($repairOrder->status !== 'در انتظار') {
            return redirect()->back()
                ->with('error', 'فقط سفارش‌های در انتظار قابل لغو هستند.');
        }
        
        $repairOrder->update([
            'status' => $request->status,
            'cancelled_at' => now(),
            'cancelled_by' => Auth::id(),
        ]);
        
        return redirect()->route('my-jewelry.repair-orders.show', $repairOrder)
            ->with('success', 'سفارش تعمیر با موفقیت لغو شد.');
    }
    
    /**
     * آپلود عکس اضافی
     */
    public function uploadPhoto(Request $request, $id)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string|max:255',
        ]);
        
        $repairOrder = RepairOrder::findOrFail($id);
        
        if ($repairOrder->user_id !== Auth::id()) {
            abort(403, 'دسترسی غیرمجاز');
        }
        
        // آپلود عکس جدید
        $path = $request->file('photo')->store('repair-orders/additional', 'public');
        
        // ذخیره اطلاعات عکس در دیتابیس (اگر جدول جداگانه‌ای برای عکس‌های اضافی دارید)
        // \App\Models\RepairOrderPhoto::create([
        //     'repair_order_id' => $repairOrder->id,
        //     'photo_path' => $path,
        //     'description' => $request->get('description'),
        // ]);
        
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
        $repairOrder = RepairOrder::findOrFail($id);
        
        if ($repairOrder->user_id !== Auth::id()) {
            return response()->json(['error' => 'دسترسی غیرمجاز'], 403);
        }
        
        return response()->json([
            'id' => $repairOrder->id,
            'status' => $repairOrder->status,
            'status_text' => $repairOrder->status,
            'updated_at' => $repairOrder->updated_at->diffForHumans(),
            'assigned_to' => $repairOrder->assignedTo ? $repairOrder->assignedTo->name : null,
            'estimated_completion' => $repairOrder->estimated_completion_date,
        ]);
    }
}