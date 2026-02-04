<?php

namespace App\Http\Controllers\Jewelry;

use App\Http\Controllers\Controller;
use App\Models\JewelryCertificate;
use App\Models\JewelryOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class JewelryCertificateController extends Controller
{
    protected $metalTypes = ['طلا', 'نقره', 'پلاتین', 'سایر'];
    protected $stoneTypes = ['الماس', 'یاقوت', 'زمررد', 'یاقوت کبود', 'مروارید', 'فیروزه', 'عقیق', 'سایر'];
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * نمایش لیست شناسنامه‌ها
     */
    public function index(Request $request)
    {
        $query = JewelryCertificate::with(['owner']);
        
        // فیلتر بر اساس مالک
        if ($request->has('owner_id') && $request->owner_id) {
            $query->where('owner_id', $request->owner_id);
        }
        
        // فیلتر نوع فلز
        if ($request->has('metal_type') && $request->metal_type != 'all') {
            $query->where('metal_type', $request->metal_type);
        }
        
        // جستجو
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('serial_number', 'LIKE', "%{$search}%")
                  ->orWhere('product_name', 'LIKE', "%{$search}%")
                  ->orWhere('stone_type', 'LIKE', "%{$search}%")
                  ->orWhereHas('owner', function($q2) use ($search) {
                      $q2->where('full_name', 'LIKE', "%{$search}%")
                         ->orWhere('phone', 'LIKE', "%{$search}%")
                         ->orWhere('national_id', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        // مرتب‌سازی
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $certificates = $query->paginate($request->get('per_page', 15));
        
        $owners = JewelryOwner::orderBy('full_name')->get();
        
        return view('admin.jewelry.certificates.index', compact(
            'certificates', 'owners'
        ));
    }

    /**
     * فرم ایجاد شناسنامه جدید
     */
    public function create()
    {
        $owners = JewelryOwner::orderBy('full_name')->get();
        
        return view('admin.jewelry.certificates.create', compact('owners'))->with([
            'metalTypes' => $this->metalTypes,
            'stoneTypes' => $this->stoneTypes,
        ]);
    }

    /**
     * ذخیره شناسنامه جدید
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'owner_id' => 'required|exists:jewelry_owners,id',
            'product_name' => 'required|string|max:255',
            'metal_type' => 'required|in:طلا,نقره,پلاتین,سایر',
            'purity' => 'nullable|integer|min:0|max:1000',
            'weight' => 'nullable|numeric|min:0',
            'stone_type' => 'nullable|string|max:255',
            'serial_number' => [
                'required',
                'string',
                'max:100',
                'unique:jewelry_certificates,serial_number'
            ],
            'issued_at' => 'required|date',
            'certificate_file' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'description' => 'nullable|string|max:1000',
        ]);
        
        // آپلود فایل
        if ($request->hasFile('certificate_file')) {
            $path = $request->file('certificate_file')->store('jewelry-certificates', 'public');
            $validated['certificate_file'] = $path;
        }
        
        // ایجاد شناسنامه
        $certificate = JewelryCertificate::create($validated);
        
        return redirect()->route('admin.jewelry.certificates.show', $certificate)
            ->with('success', 'شناسنامه جدید با موفقیت ثبت شد.');
    }

    /**
     * نمایش شناسنامه
     */
    public function show(JewelryCertificate $certificate)
    {
        // بارگذاری روابط
        $certificate->load(['owner', 'transfers.fromOwner', 'transfers.toOwner', 'repairOrders']);
        
        $transfers = $certificate->transfers()
            ->with(['fromOwner', 'toOwner'])
            ->orderBy('transfer_date', 'desc')
            ->get();
        
        $repairOrders = $certificate->repairOrders()
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.jewelry.certificates.show', compact(
            'certificate', 'transfers', 'repairOrders'
        ));
    }

    /**
     * فرم ویرایش شناسنامه
     */
    public function edit(JewelryCertificate $certificate)
    {
        $owners = JewelryOwner::orderBy('full_name')->get();
        
        return view('admin.jewelry.certificates.edit', compact(
            'certificate', 'owners'
        ))->with([
            'metalTypes' => $this->metalTypes,
            'stoneTypes' => $this->stoneTypes,
        ]);
    }

    /**
     * بروزرسانی شناسنامه
     */
    public function update(Request $request, JewelryCertificate $certificate)
    {
        $validated = $request->validate([
            'owner_id' => 'required|exists:jewelry_owners,id',
            'product_name' => 'required|string|max:255',
            'metal_type' => 'required|in:طلا,نقره,پلاتین,سایر',
            'purity' => 'nullable|integer|min:0|max:1000',
            'weight' => 'nullable|numeric|min:0',
            'stone_type' => 'nullable|string|max:255',
            'serial_number' => [
                'required',
                'string',
                'max:100',
                'unique:jewelry_certificates,serial_number,' . $certificate->id,
            ],
            'issued_at' => 'required|date',
            'certificate_file' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'description' => 'nullable|string|max:1000',
        ]);
        
        // آپلود فایل جدید (اگر وجود دارد)
        if ($request->hasFile('certificate_file')) {
            // حذف فایل قدیمی
            if ($certificate->certificate_file) {
                Storage::disk('public')->delete($certificate->certificate_file);
            }
            
            $path = $request->file('certificate_file')->store('jewelry-certificates', 'public');
            $validated['certificate_file'] = $path;
        } else {
            // نگه داشتن فایل قبلی
            $validated['certificate_file'] = $certificate->certificate_file;
        }
        
        $certificate->update($validated);
        
        return redirect()->route('admin.jewelry.certificates.show', $certificate)
            ->with('success', 'شناسنامه با موفقیت بروزرسانی شد.');
    }

    /**
     * حذف شناسنامه
     */
    public function destroy(JewelryCertificate $certificate)
    {
        // بررسی وجود انتقالات
        if ($certificate->transfers()->count() > 0) {
            return redirect()->back()
                ->with('error', 'امکان حذف شناسنامه دارای تاریخچه انتقال وجود ندارد.');
        }
        
        // بررسی سفارشات تعمیر فعال
        $activeRepairs = $certificate->repairOrders()
            ->whereIn('status', ['در انتظار', 'در حال تعمیر'])
            ->count();
            
        if ($activeRepairs > 0) {
            return redirect()->back()
                ->with('error', 'امکان حذف شناسنامه با سفارش تعمیر فعال وجود ندارد.');
        }
        
        // حذف فایل ضمیمه
        if ($certificate->certificate_file) {
            Storage::disk('public')->delete($certificate->certificate_file);
        }
        
        $certificate->delete();
        
        return redirect()->route('admin.jewelry.certificates.index')
            ->with('success', 'شناسنامه با موفقیت حذف شد.');
    }

    /**
     * چاپ شناسنامه
     */
    public function print(JewelryCertificate $certificate)
    {
        $certificate->load('owner');
        return view('admin.jewelry.certificates.print', compact('certificate'));
    }

    /**
     * دانلود فایل شناسنامه
     */
    public function downloadFile(JewelryCertificate $certificate)
    {
        if (!$certificate->certificate_file || 
            !Storage::disk('public')->exists($certificate->certificate_file)) {
            abort(404, 'فایل یافت نشد');
        }
        
        return Storage::disk('public')->download($certificate->certificate_file);
    }

    /**
     * تأیید عمومی شناسنامه
     */
    public function publicVerify($serial_number)
    {
        $certificate = JewelryCertificate::where('serial_number', $serial_number)
            ->with(['owner'])
            ->firstOrFail();
        
        return view('admin.jewelry.certificates.public-verify', compact('certificate'));
    }
}