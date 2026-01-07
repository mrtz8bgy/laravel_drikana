<?php

namespace App\Http\Controllers\Jewelry;

use App\Http\Controllers\Controller;
use App\Models\JewelryTransfer;
use App\Models\JewelryCertificate;
use App\Models\JewelryOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class JewelryTransferController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // اگر از طریق شناسنامه ایجاد شود
        if ($request->has('certificate_id')) {
            $certificate = JewelryCertificate::findOrFail($request->certificate_id);
            $this->authorize('view', $certificate);
            
            $owners = JewelryOwner::where('user_id', Auth::id())
                ->where('id', '!=', $certificate->owner_id)
                ->get();
            
            return view('jewelry.transfers.create', compact('certificate', 'owners'));
        }
        
        // ایجاد انتقال جدید بدون شناسنامه مشخص
        $certificates = JewelryCertificate::whereHas('owner', function($q) {
            $q->where('user_id', Auth::id());
        })->get();
        
        $owners = JewelryOwner::where('user_id', Auth::id())->get();
        
        return view('jewelry.transfers.create-general', compact('certificates', 'owners'));
    }
    
    /**
     * ایجاد انتقال برای شناسنامه خاص
     */
    public function createForCertificate(JewelryCertificate $certificate)
    {
        $this->authorize('view', $certificate);
        
        $owners = JewelryOwner::where('user_id', Auth::id())
            ->where('id', '!=', $certificate->owner_id)
            ->get();
        
        return view('jewelry.transfers.create', compact('certificate', 'owners'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'certificate_id' => 'required|exists:jewelry_certificates,id',
            'to_owner_id' => 'required|exists:jewelry_owners,id',
            'transfer_date' => 'required|date',
            'transfer_type' => 'required|in:فروش,هدیه,ارث,سایر',
            'notes' => 'nullable|string|max:1000',
            'price' => 'nullable|numeric|min:0',
            'contract_file' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ]);
        
        // بررسی دسترسی به شناسنامه
        $certificate = JewelryCertificate::findOrFail($validated['certificate_id']);
        $this->authorize('view', $certificate);
        
        // بررسی مالکیت مالک مقصد
        $toOwner = JewelryOwner::findOrFail($validated['to_owner_id']);
        if ($toOwner->user_id !== Auth::id()) {
            abort(403, 'دسترسی غیرمجاز به مالک مقصد');
        }
        
        // مالک فعلی باید مالک فعلی شناسنامه باشد
        $fromOwnerId = $certificate->owner_id;
        
        // آپلود فایل قرارداد
        if ($request->hasFile('contract_file')) {
            $path = $request->file('contract_file')->store('transfer-contracts', 'public');
            $validated['contract_file'] = $path;
        }
        
        // ایجاد انتقال
        $transfer = JewelryTransfer::create([
            'certificate_id' => $certificate->id,
            'from_owner_id' => $fromOwnerId,
            'to_owner_id' => $validated['to_owner_id'],
            'transfer_date' => $validated['transfer_date'],
            'transfer_type' => $validated['transfer_type'],
            'notes' => $validated['notes'],
            'price' => $validated['price'],
            'contract_file' => $validated['contract_file'] ?? null,
        ]);
        
        // بروزرسانی مالک فعلی شناسنامه
        $certificate->update(['owner_id' => $validated['to_owner_id']]);
        
        return redirect()->route('my-jewelry.transfers.show', $transfer)
            ->with('success', 'انتقال مالکیت با موفقیت ثبت شد.');
    }

    /**
     * Display the specified resource.
     */
    public function show(JewelryTransfer $transfer)
    {
        // بررسی دسترسی
        $this->authorize('view', $transfer);
        
        return view('jewelry.transfers.show', compact('transfer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JewelryTransfer $transfer)
    {
        $this->authorize('update', $transfer);
        
        $certificate = $transfer->certificate;
        $owners = JewelryOwner::where('user_id', Auth::id())->get();
        
        return view('jewelry.transfers.edit', compact('transfer', 'certificate', 'owners'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JewelryTransfer $transfer)
    {
        $this->authorize('update', $transfer);
        
        $validated = $request->validate([
            'transfer_date' => 'required|date',
            'transfer_type' => 'required|in:فروش,هدیه,ارث,سایر',
            'notes' => 'nullable|string|max:1000',
            'price' => 'nullable|numeric|min:0',
            'contract_file' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ]);
        
        // آپلود فایل قرارداد جدید
        if ($request->hasFile('contract_file')) {
            // حذف فایل قبلی
            if ($transfer->contract_file) {
                \Storage::disk('public')->delete($transfer->contract_file);
            }
            
            $path = $request->file('contract_file')->store('transfer-contracts', 'public');
            $validated['contract_file'] = $path;
        } else {
            // حفظ فایل قبلی
            $validated['contract_file'] = $transfer->contract_file;
        }
        
        $transfer->update($validated);
        
        return redirect()->route('my-jewelry.transfers.show', $transfer)
            ->with('success', 'اطلاعات انتقال با موفقیت بروزرسانی شد.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JewelryTransfer $transfer)
    {
        $this->authorize('delete', $transfer);
        
        // بررسی آیا این آخرین انتقال است؟
        $certificate = $transfer->certificate;
        $latestTransfer = $certificate->transfers()->latest()->first();
        
        if ($transfer->id === $latestTransfer->id) {
            // بازگشت مالکیت به مالک قبلی
            $certificate->update(['owner_id' => $transfer->from_owner_id]);
        }
        
        // حذف فایل قرارداد
        if ($transfer->contract_file) {
            \Storage::disk('public')->delete($transfer->contract_file);
        }
        
        $transfer->delete();
        
        return redirect()->route('my-jewelry.certificates.show', $certificate)
            ->with('success', 'رکورد انتقال با موفقیت حذف شد.');
    }
    
    /**
     * دریافت لیست انتقالات یک شناسنامه (برای AJAX)
     */
    public function getCertificateTransfers($certificateId)
    {
        $certificate = JewelryCertificate::findOrFail($certificateId);
        $this->authorize('view', $certificate);
        
        $transfers = $certificate->transfers()
            ->with(['fromOwner', 'toOwner'])
            ->orderBy('transfer_date', 'desc')
            ->get();
        
        return response()->json($transfers);
    }
    
    /**
     * دانلود فایل قرارداد
     */
    public function downloadContract(JewelryTransfer $transfer)
    {
        $this->authorize('view', $transfer);
        
        if (!$transfer->contract_file || !\Storage::disk('public')->exists($transfer->contract_file)) {
            abort(404, 'فایل قرارداد یافت نشد');
        }
        
        return \Storage::disk('public')->download($transfer->contract_file);
    }
}