<?php

namespace App\Http\Controllers\Jewelry\Admin;

use App\Http\Controllers\Controller;
use App\Models\JewelryCertificate;
use App\Models\JewelryTransfer;
use App\Models\JewelryOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JewelryCertificateController extends Controller
{
    /**
     * نمایش لیست تمام شناسنامه‌ها برای ادمین
     */
    public function index(Request $request)
    {
        $query = JewelryCertificate::with(['owner.user']);
        
        // فیلتر بر اساس وضعیت تأیید
        if ($request->has('verification_status')) {
            $status = $request->get('verification_status');
            if ($status == 'verified') {
                $query->where('is_verified', true);
            } elseif ($status == 'pending') {
                $query->where('is_verified', false)->whereNull('rejected_at');
            } elseif ($status == 'rejected') {
                $query->whereNotNull('rejected_at');
            }
        }
        
        // جستجو بر اساس شماره سریال یا نام محصول
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('serial_number', 'LIKE', "%{$search}%")
                  ->orWhere('product_name', 'LIKE', "%{$search}%");
            });
        }
        
        // فیلتر بر اساس نوع فلز
        if ($request->has('metal_type')) {
            $query->where('metal_type', $request->get('metal_type'));
        }
        
        // فیلتر بر اساس تاریخ ایجاد
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }
        
        $certificates = $query->orderBy('created_at', 'desc')->paginate(20);
        
        $stats = [
            'total' => JewelryCertificate::count(),
            'verified' => JewelryCertificate::where('is_verified', true)->count(),
            'pending' => JewelryCertificate::where('is_verified', false)->whereNull('rejected_at')->count(),
            'rejected' => JewelryCertificate::whereNotNull('rejected_at')->count(),
        ];
        
        return view('admin.jewelry.certificates.index', compact('certificates', 'stats'));
    }
    
    /**
     * نمایش جزئیات یک شناسنامه برای ادمین
     */
    public function show($id)
    {
        $certificate = JewelryCertificate::with([
            'owner.user', 
            'transfers.fromOwner.user', 
            'transfers.toOwner.user',
            'repairOrders.user'
        ])->findOrFail($id);
        
        $transfers = $certificate->transfers()->orderBy('transfer_date', 'desc')->get();
        $repairOrders = $certificate->repairOrders()->orderBy('created_at', 'desc')->get();
        
        return view('admin.jewelry.certificates.show', compact('certificate', 'transfers', 'repairOrders'));
    }
    
    /**
     * تأیید شناسنامه توسط ادمین
     */
    public function verify(Request $request, $id)
    {
        $request->validate([
            'verification_notes' => 'nullable|string|max:500',
        ]);
        
        $certificate = JewelryCertificate::findOrFail($id);
        
        // بررسی اینکه قبلاً رد نشده باشد
        if ($certificate->rejected_at) {
            return redirect()->back()
                ->with('error', 'این شناسنامه قبلاً رد شده است و قابل تأیید نیست.');
        }
        
        $certificate->update([
            'is_verified' => true,
            'verified_at' => Carbon::now(),
            'verified_by' => auth()->id(),
            'verification_notes' => $request->get('verification_notes'),
            'rejected_at' => null,
            'rejection_reason' => null,
        ]);
        
        // TODO: ارسال نوتیفیکیشن به کاربر
        
        return redirect()->route('admin.jewelry.certificates.show', $certificate)
            ->with('success', 'شناسنامه با موفقیت تأیید شد.');
    }
    
    /**
     * رد شناسنامه توسط ادمین
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);
        
        $certificate = JewelryCertificate::findOrFail($id);
        
        // بررسی اینکه قبلاً تأیید نشده باشد
        if ($certificate->is_verified) {
            return redirect()->back()
                ->with('error', 'این شناسنامه قبلاً تأیید شده است و قابل رد نیست.');
        }
        
        $certificate->update([
            'is_verified' => false,
            'verified_at' => null,
            'verified_by' => null,
            'verification_notes' => null,
            'rejected_at' => Carbon::now(),
            'rejection_reason' => $request->get('rejection_reason'),
        ]);
        
        // TODO: ارسال نوتیفیکیشن به کاربر
        
        return redirect()->route('admin.jewelry.certificates.show', $certificate)
            ->with('success', 'شناسنامه با موفقیت رد شد.');
    }
    
    /**
     * حذف شناسنامه توسط ادمین
     */
    public function destroy($id)
    {
        $certificate = JewelryCertificate::findOrFail($id);
        
        // بررسی آیا نقل و انتقالاتی دارد
        if ($certificate->transfers()->count() > 0) {
            return redirect()->back()
                ->with('error', 'امکان حذف شناسنامه دارای تاریخچه انتقال وجود ندارد.');
        }
        
        // بررسی آیا سفارش تعمیر فعال دارد
        $activeRepairs = $certificate->repairOrders()
            ->whereIn('status', ['در انتظار', 'در حال تعمیر'])
            ->count();
            
        if ($activeRepairs > 0) {
            return redirect()->back()
                ->with('error', 'امکان حذف شناسنامه با سفارش تعمیر فعال وجود ندارد.');
        }
        
        DB::transaction(function () use ($certificate) {
            // حذف فایل پیوست
            if ($certificate->certificate_file && \Storage::disk('public')->exists($certificate->certificate_file)) {
                \Storage::disk('public')->delete($certificate->certificate_file);
            }
            
            $certificate->delete();
        });
        
        return redirect()->route('admin.jewelry.certificates.index')
            ->with('success', 'شناسنامه با موفقیت حذف شد.');
    }
    
    /**
     * مشاهده تاریخچه انتقالات یک شناسنامه
     */
    public function transferHistory($id)
    {
        $certificate = JewelryCertificate::with(['owner.user'])->findOrFail($id);
        $transfers = $certificate->transfers()
            ->with(['fromOwner.user', 'toOwner.user'])
            ->orderBy('transfer_date', 'desc')
            ->paginate(15);
            
        return view('admin.jewelry.certificates.transfer-history', compact('certificate', 'transfers'));
    }
    
    /**
     * بررسی سریع شناسنامه (AJAX)
     */
    public function quickVerify(Request $request)
    {
        $request->validate([
            'certificate_id' => 'required|exists:jewelry_certificates,id',
            'status' => 'required|in:verify,reject',
            'notes' => 'nullable|string|max:500',
        ]);
        
        $certificate = JewelryCertificate::findOrFail($request->get('certificate_id'));
        
        if ($request->get('status') == 'verify') {
            $certificate->update([
                'is_verified' => true,
                'verified_at' => Carbon::now(),
                'verified_by' => auth()->id(),
                'verification_notes' => $request->get('notes'),
                'rejected_at' => null,
                'rejection_reason' => null,
            ]);
            
            $message = 'شناسنامه با موفقیت تأیید شد.';
        } else {
            $certificate->update([
                'is_verified' => false,
                'verified_at' => null,
                'verified_by' => null,
                'verification_notes' => null,
                'rejected_at' => Carbon::now(),
                'rejection_reason' => $request->get('notes'),
            ]);
            
            $message = 'شناسنامه با موفقیت رد شد.';
        }
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'certificate' => $certificate->load('owner.user')
        ]);
    }
}