<?php

namespace App\Http\Controllers;

use App\Models\MissingReport;
use App\Models\JewelryCertificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MissingReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * نمایش لیست گزارش‌ها با قابلیت فیلتر
     */
    public function index(Request $request)
    {
        $query = MissingReport::with(['jewelry.owner', 'user'])
                              ->where('user_id', Auth::id());
        
        // فیلتر بر اساس نوع (سرقت/مفقودی)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        // فیلتر بر اساس وضعیت
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // فیلتر بر اساس جستجو در نام قطعه یا شماره سریال
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('jewelry', function($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }
        
        // فیلتر بر اساس تاریخ
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // مرتب‌سازی
        $query->latest();
        
        // دریافت نتایج
        $reports = $query->get();
        
        // ارسال پارامترهای فیلتر به ویو برای نمایش در فرم
        return view('admin.jewelry.missing_reports.index', [
            'reports' => $reports,
            'filters' => $request->only(['search', 'type', 'status', 'date_from', 'date_to'])
        ]);
    }
    
    /**
     * نمایش فرم ثبت گزارش
     */
    public function create()
    {
        // فقط قطعاتی که مالکش کاربر فعلی هست رو نمایش بده
        $jewelries = JewelryCertificate::with('owner')
                                       ->whereHas('owner', function($query) {
                                           $query->where('user_id', Auth::id());
                                       })
                                       ->get();
        
        return view('admin.jewelry.missing_reports.create', compact('jewelries'));
    }
    
    /**
     * ذخیره گزارش جدید
     */
    public function store(Request $request)
    {
        $request->validate([
            'jewelry_id' => 'required|exists:jewelry_certificates,id',
            'type' => 'required|in:theft,lost',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        // چک میکنیم قطعه واقعاً مال این کاربر هست؟
        $jewelry = JewelryCertificate::with('owner')
                                     ->where('id', $request->jewelry_id)
                                     ->whereHas('owner', function($query) {
                                         $query->where('user_id', Auth::id());
                                     })
                                     ->first();
                                     
        if (!$jewelry) {
            return back()->withErrors(['شما مالک این قطعه نیستید'])->withInput();
        }
        
        MissingReport::create([
            'user_id' => Auth::id(),
            'jewelry_id' => $request->jewelry_id,
            'type' => $request->type,
            'location' => $request->location,
            'description' => $request->description,
            'status' => 'pending'
        ]);
        
        return redirect()->route('admin.jewelry.missing_reports.index')
                        ->with('success', 'گزارش با موفقیت ثبت شد');
    }
    
    /**
     * نمایش جزییات یک گزارش
     */
    public function show($id)
    {
        $report = MissingReport::with(['jewelry.owner', 'user'])
                               ->where('user_id', Auth::id())
                               ->findOrFail($id);
                               
        return view('admin.jewelry.missing_reports.show', compact('report'));
    }
    
    /**
     * بروزرسانی وضعیت گزارش
     */
    public function updateStatus(Request $request, $id)
    {
        $report = MissingReport::where('user_id', Auth::id())->findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:pending,found'
        ]);
        
        $report->update([
            'status' => $request->status
        ]);
        
        $message = $request->status == 'found' ? 'وضعیت به پیدا شده تغییر کرد' : 'وضعیت بروزرسانی شد';
        
        return redirect()->route('admin.jewelry.missing_reports.show', $id)
                         ->with('success', $message);
    }
    
    /**
     * چاپ گزارش
     */
    public function print($id)
    {
        $report = MissingReport::with(['jewelry.owner', 'user'])
                               ->where('user_id', Auth::id())
                               ->findOrFail($id);
                               
        return view('admin.jewelry.missing_reports.print', compact('report'));
    }
    
    /**
     * دریافت اطلاعات قطعه برای AJAX
     */
    public function getJewelryInfo($id)
    {
        $jewelry = JewelryCertificate::with('owner')
                                     ->where('id', $id)
                                     ->whereHas('owner', function($query) {
                                         $query->where('user_id', Auth::id());
                                     })
                                     ->first();
                                     
        if (!$jewelry) {
            return response()->json(['error' => 'قطعه مورد نظر یافت نشد'], 404);
        }
        
        return response()->json([
            'name' => $jewelry->product_name ?? 'بدون نام',
            'serial_number' => $jewelry->serial_number ?? 'بدون سریال',
            'metal_type' => $jewelry->metal_type ?? 'نامشخص',
            'purity' => $jewelry->purity ?? 'نامشخص',
            'weight' => $jewelry->weight ?? 0,
            'stone_type' => $jewelry->stone_type ?? 'ندارد',
            'owner_name' => $jewelry->owner->full_name ?? 'نامشخص',
            'owner_phone' => $jewelry->owner->phone ?? '---',
            'owner_national_id' => $jewelry->owner->national_id ?? '---',
            'certificate_file' => $jewelry->certificate_file ?? null
        ]);
    }
}