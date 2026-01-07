<?php

namespace App\Http\Controllers\Jewelry;

use App\Http\Controllers\Controller;
use App\Models\JewelryCertificate;
use App\Models\JewelryOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class JewelryCertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = JewelryCertificate::whereHas('owner', function($q) {
            $q->where('user_id', Auth::id());
        })->with(['owner', 'transfers']);
        
        // فیلترها
        if ($request->has('metal_type')) {
            $query->where('metal_type', $request->metal_type);
        }
        
        if ($request->has('verification_status')) {
            if ($request->verification_status == 'verified') {
                $query->where('is_verified', true);
            } elseif ($request->verification_status == 'pending') {
                $query->where('is_verified', false)->whereNull('rejected_at');
            } elseif ($request->verification_status == 'rejected') {
                $query->whereNotNull('rejected_at');
            }
        }
        
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('serial_number', 'LIKE', "%{$request->search}%")
                  ->orWhere('product_name', 'LIKE', "%{$request->search}%")
                  ->orWhereHas('owner', function($q2) use ($request) {
                      $q2->where('full_name', 'LIKE', "%{$request->search}%");
                  });
            });
        }
        
        $certificates = $query->orderBy('created_at', 'desc')->paginate(15);
        
        $metalTypes = ['طلا', 'نقره', 'پلاتین', 'سایر'];
        $verificationStatuses = [
            'all' => 'همه',
            'verified' => 'تأیید شده',
            'pending' => 'در انتظار تأیید',
            'rejected' => 'رد شده'
        ];
        
        return view('jewelry.certificates.index', compact('certificates', 'metalTypes', 'verificationStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $owners = JewelryOwner::where('user_id', Auth::id())->get();
        
        return view('jewelry.certificates.create', compact('owners'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'owner_id' => 'required|exists:jewelry_owners,id',
            'product_name' => 'required|string|max:255',
            'metal_type' => 'required|in:طلا,نقره,پلاتین,سایر',
            'purity' => 'nullable|string|max:10',
            'weight' => 'nullable|numeric|min:0',
            'stone_type' => 'nullable|string|max:255',
            'serial_number' => 'required|string|unique:jewelry_certificates',
            'issued_at' => 'required|date',
            'certificate_file' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'description' => 'nullable|string|max:1000',
        ]);
        
        // بررسی مالکیت مالک
        $owner = JewelryOwner::findOrFail($validated['owner_id']);
        if ($owner->user_id !== Auth::id()) {
            abort(403, 'دسترسی غیرمجاز');
        }
        
        // آپلود فایل شناسنامه
        if ($request->hasFile('certificate_file')) {
            $path = $request->file('certificate_file')->store('jewelry-certificates', 'public');
            $validated['certificate_file'] = $path;
        }
        
        // اضافه کردن وضعیت پیش‌فرض
        $validated['is_verified'] = false;
        
        $certificate = JewelryCertificate::create($validated);
        
        return redirect()->route('my-jewelry.certificates.show', $certificate)
            ->with('success', 'شناسنامه جدید با موفقیت ثبت شد.');
    }

    /**
     * Display the specified resource.
     */
    public function show(JewelryCertificate $certificate)
    {
        // بررسی دسترسی
        $this->authorize('view', $certificate);
        
        $transfers = $certificate->transfers()
            ->with(['fromOwner', 'toOwner'])
            ->orderBy('transfer_date', 'desc')
            ->get();
        
        $repairOrders = $certificate->repairOrders()
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('jewelry.certificates.show', compact('certificate', 'transfers', 'repairOrders'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JewelryCertificate $certificate)
    {
        $this->authorize('update', $certificate);
        
        $owners = JewelryOwner::where('user_id', Auth::id())->get();
        
        return view('jewelry.certificates.edit', compact('certificate', 'owners'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JewelryCertificate $certificate)
    {
        $this->authorize('update', $certificate);
        
        $validated = $request->validate([
            'owner_id' => 'required|exists:jewelry_owners,id',
            'product_name' => 'required|string|max:255',
            'metal_type' => 'required|in:طلا,نقره,پلاتین,سایر',
            'purity' => 'nullable|string|max:10',
            'weight' => 'nullable|numeric|min:0',
            'stone_type' => 'nullable|string|max:255',
            'serial_number' => 'required|string|unique:jewelry_certificates,serial_number,' . $certificate->id,
            'issued_at' => 'required|date',
            'certificate_file' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'description' => 'nullable|string|max:1000',
        ]);
        
        // بررسی مالکیت مالک جدید
        $newOwner = JewelryOwner::findOrFail($validated['owner_id']);
        if ($newOwner->user_id !== Auth::id()) {
            abort(403, 'دسترسی غیرمجاز');
        }
        
        // آپلود فایل جدید
        if ($request->hasFile('certificate_file')) {
            // حذف فایل قبلی
            if ($certificate->certificate_file) {
                Storage::disk('public')->delete($certificate->certificate_file);
            }
            
            $path = $request->file('certificate_file')->store('jewelry-certificates', 'public');
            $validated['certificate_file'] = $path;
        } else {
            // حفظ فایل قبلی
            $validated['certificate_file'] = $certificate->certificate_file;
        }
        
        $certificate->update($validated);
        
        return redirect()->route('my-jewelry.certificates.show', $certificate)
            ->with('success', 'شناسنامه با موفقیت بروزرسانی شد.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JewelryCertificate $certificate)
    {
        $this->authorize('delete', $certificate);
        
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
        
        // حذف فایل مرتبط
        if ($certificate->certificate_file) {
            Storage::disk('public')->delete($certificate->certificate_file);
        }
        
        $certificate->delete();
        
        return redirect()->route('my-jewelry.certificates.index')
            ->with('success', 'شناسنامه با موفقیت حذف شد.');
    }
    
    /**
     * مشاهده تاریخچه انتقالات
     */
    public function transfers(JewelryCertificate $certificate)
    {
        $this->authorize('view', $certificate);
        
        $transfers = $certificate->transfers()
            ->with(['fromOwner', 'toOwner'])
            ->orderBy('transfer_date', 'desc')
            ->paginate(15);
            
        return view('jewelry.certificates.transfers', compact('certificate', 'transfers'));
    }
    
    /**
     * چاپ شناسنامه
     */
    public function print(JewelryCertificate $certificate)
    {
        $this->authorize('view', $certificate);
        
        return view('jewelry.certificates.print', compact('certificate'));
    }
    
    /**
     * جستجوی شناسنامه
     */
    public function search(Request $request)
    {
        $request->validate([
            'serial_number' => 'required|string',
        ]);
        
        $certificate = JewelryCertificate::where('serial_number', $request->serial_number)
            ->whereHas('owner', function($q) {
                $q->where('user_id', Auth::id());
            })
            ->first();
        
        if ($certificate) {
            return redirect()->route('my-jewelry.certificates.show', $certificate);
        }
        
        return redirect()->route('my-jewelry.certificates.index')
            ->with('error', 'شناسنامه با شماره سریال وارد شده یافت نشد.');
    }
    
    /**
     * تأیید عمومی شناسنامه (بدون نیاز به لاگین)
     */
    public function publicVerify($serial_number)
    {
        $certificate = JewelryCertificate::where('serial_number', $serial_number)
            ->with(['owner'])
            ->first();
        
        if (!$certificate) {
            abort(404, 'شناسنامه یافت نشد');
        }
        
        return view('jewelry.certificates.public-verify', compact('certificate'));
    }
    
    /**
     * دانلود فایل شناسنامه
     */
    public function downloadFile(JewelryCertificate $certificate)
    {
        $this->authorize('view', $certificate);
        
        if (!$certificate->certificate_file || !Storage::disk('public')->exists($certificate->certificate_file)) {
            abort(404, 'فایل یافت نشد');
        }
        
        return Storage::disk('public')->download($certificate->certificate_file);
    }
}