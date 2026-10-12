<?php

namespace App\Http\Controllers\Jewelry;

use App\Http\Controllers\Controller;
use App\Models\JewelryCertificate;
use App\Models\JewelryOwner;
use App\Models\JewelryTransfer;
use App\Models\OwnershipHistory;
use App\Services\Ownership\OwnershipTransferService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * کنترلر انتقال مالکیت طلا و جواهر.
 *
 * ── چه چیزی نسبت به قبل اصلاح شد؟ ──────────────────────────────────
 * کنترلر قبلی (`Jewelry/JewelryTransferController`) مشکلات زیر را داشت:
 *
 *   • بدون Database Transaction → امکان باقی‌ماندن رکورد یتیم
 *   • بدون lockForUpdate       → امکان دریافت دو درخواست هم‌زمان (Race Condition)
 *   • بدون گردش تأیید          → انتقال بی‌درنگ و بدون بررسی نهایی می‌شد
 *   • بدون Audit Log
 *   • فقط مجوز `view` بررسی می‌شد (نه مجوز ایجاد انتقال)
 *   • فیلدهای price و contract_file در مدل fillable نبودند → دور ریخته می‌شدند
 *   • روت‌های مقصد (route('my-jewelry.transfers.show')) وجود نداشتند → 500
 *
 * این کنترلر منطق را به `OwnershipTransferService` واگذار می‌کند و خودش
 * فقط مسئول اعتبارسنجی ورودی، بررسی مجوز (Policy) و پاسخ HTTP است
 * — مطابق اصل Single Responsibility.
 */
class OwnershipTransferController extends Controller
{
    /**
     * @var \App\Services\Ownership\OwnershipTransferService
     */
    protected $service;

    public function __construct(OwnershipTransferService $service)
    {
        $this->middleware('auth');
        $this->service = $service;
    }

    // =========================================================
    // پنل مدیریت
    // =========================================================

    /**
     * فهرست درخواست‌های انتقال با فیلتر و جست‌وجو.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', JewelryTransfer::class);

        $query = JewelryTransfer::query()
            ->with(['certificate', 'fromOwner', 'toOwner', 'requester', 'approver']);

        // فیلتر وضعیت — فقط مقادیر مجاز پذیرفته می‌شوند (جلوگیری از تزریق)
        if ($request->filled('status')) {
            $status = $request->get('status');
            if (in_array($status, JewelryTransfer::statuses(), true)) {
                $query->where('status', $status);
            }
        }

        // جست‌وجو بر اساس شماره سریال شناسنامه یا شناسه درخواست
        if ($request->filled('search')) {
            $search = trim((string) $request->get('search'));
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                  ->orWhereHas('certificate', function ($q2) use ($search) {
                      $q2->where('serial_number', 'like', '%' . $search . '%')
                         ->orWhere('product_name', 'like', '%' . $search . '%');
                  });
            });
        }

        // فیلتر بازه تاریخی
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        $transfers = $query->orderByDesc('created_at')->paginate(20);

        $stats = [
            'pending'   => JewelryTransfer::pending()->count(),
            'approved'  => JewelryTransfer::where('status', JewelryTransfer::STATUS_APPROVED)->count(),
            'rejected'  => JewelryTransfer::where('status', JewelryTransfer::STATUS_REJECTED)->count(),
            'cancelled' => JewelryTransfer::where('status', JewelryTransfer::STATUS_CANCELLED)->count(),
        ];

        return view('admin.jewelry.transfers.index', compact('transfers', 'stats'));
    }

    /**
     * فرم ثبت درخواست انتقال (انتخاب آزاد قطعه).
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('create', JewelryTransfer::class);

        // فقط قطعاتی که درخواست در انتظار ندارند
        $certificates = JewelryCertificate::query()
            ->whereDoesntHave('pendingTransfers')
            ->with('owner')
            ->orderBy('serial_number')
            ->get();

        $owners = JewelryOwner::query()->orderBy('full_name')->get();

        return view('admin.jewelry.transfers.create', compact('certificates', 'owners'));
    }

    /**
     * فرم ثبت درخواست انتقال برای یک قطعه مشخص.
     *
     * @param  int $certificate
     * @return \Illuminate\View\View
     */
    public function createForCertificate($certificate)
    {
        $this->authorize('create', JewelryTransfer::class);

        $certificate = JewelryCertificate::with('owner')->findOrFail($certificate);

        // مالک مقصد نمی‌تواند خود مالک فعلی باشد
        $owners = JewelryOwner::query()
            ->where('id', '!=', $certificate->owner_id)
            ->orderBy('full_name')
            ->get();

        return view('admin.jewelry.transfers.create', compact('certificate', 'owners'));
    }

    /**
     * ثبت درخواست انتقال.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->authorize('create', JewelryTransfer::class);

        $validated = $request->validate([
            'certificate_id' => 'required|integer|exists:jewelry_certificates,id',
            'to_owner_id'    => 'required|integer|exists:jewelry_owners,id',
            'transfer_date'  => 'required|date',
            'transfer_type'  => 'required|in:فروش,هدیه,ارث,سایر',
            'notes'          => 'nullable|string|max:2000',
            'price'          => 'nullable|numeric|min:0|max:999999999999',
            'contract_file'  => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'certificate_id.exists' => 'قطعه انتخاب‌شده معتبر نیست.',
            'to_owner_id.exists'    => 'مالک مقصد انتخاب‌شده معتبر نیست.',
            'transfer_type.in'      => 'نوع انتقال نامعتبر است.',
            'contract_file.mimes'   => 'فقط فایل PDF، JPG یا PNG مجاز است.',
            'contract_file.max'     => 'حجم فایل مدرک نباید بیشتر از ۵ مگابایت باشد.',
        ]);

        // آپلود امن مدرک قرارداد
        if ($request->hasFile('contract_file')) {
            $validated['contract_file'] = $this->storeContractFile($request->file('contract_file'));
        }

        try {
            $transfer = $this->service->requestTransfer($validated, Auth::user());

            return redirect()
                ->route('admin.jewelry.transfers.show', $transfer)
                ->with('success', 'درخواست انتقال مالکیت ثبت شد و در انتظار تأیید است.');
        } catch (\RuntimeException $e) {
            // خطاهای قاعده کسب‌وکار — پیام قابل نمایش به کاربر
            return back()->withInput()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'خطای غیرمنتظره در ثبت درخواست انتقال.');
        }
    }

    /**
     * نمایش جزئیات یک درخواست انتقال.
     *
     * @param  int $transfer
     * @return \Illuminate\View\View
     */
    public function show($transfer)
    {
        $transfer = JewelryTransfer::with([
            'certificate.owner',
            'fromOwner',
            'toOwner',
            'requester',
            'approver',
            'ownershipHistory',
        ])->findOrFail($transfer);

        $this->authorize('view', $transfer);

        // تاریخچه کامل این قطعه برای نمایش
        $history = $this->service->historyFor($transfer->certificate_id);

        // بررسی هم‌خوانی مالکیت با دفتر کل
        $consistency = $this->service->checkConsistency($transfer->certificate);

        return view('admin.jewelry.transfers.show', compact('transfer', 'history', 'consistency'));
    }

    // =========================================================
    // تأیید / رد / لغو
    // =========================================================

    /**
     * تأیید و نهایی‌سازی انتقال.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $transfer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(Request $request, $transfer)
    {
        $model = JewelryTransfer::findOrFail($transfer);

        // Policy: بررسی مجوز + وضعیت pending + تفکیک وظایف
        $this->authorize('approve', $model);

        $request->validate([
            'approval_note' => 'nullable|string|max:500',
        ]);

        try {
            $this->service->approve($model->getKey(), Auth::user(), $request->get('approval_note'));

            return redirect()
                ->route('admin.jewelry.transfers.show', $model)
                ->with('success', 'انتقال مالکیت تأیید و نهایی شد. سابقه در دفتر کل ثبت گردید.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            report($e);

            return back()->with('error', 'خطای غیرمنتظره در تأیید انتقال.');
        }
    }

    /**
     * رد درخواست انتقال.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $transfer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Request $request, $transfer)
    {
        $model = JewelryTransfer::findOrFail($transfer);

        $this->authorize('reject', $model);

        $request->validate([
            'approval_note' => 'required|string|max:500',
        ], [
            'approval_note.required' => 'ثبت دلیل رد درخواست الزامی است.',
        ]);

        try {
            $this->service->reject($model->getKey(), Auth::user(), $request->get('approval_note'));

            return redirect()
                ->route('admin.jewelry.transfers.show', $model)
                ->with('success', 'درخواست انتقال رد شد.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            report($e);

            return back()->with('error', 'خطای غیرمنتظره در رد انتقال.');
        }
    }

    /**
     * لغو درخواست انتقال.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $transfer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(Request $request, $transfer)
    {
        $model = JewelryTransfer::findOrFail($transfer);

        $this->authorize('cancel', $model);

        $request->validate([
            'approval_note' => 'nullable|string|max:500',
        ]);

        $redirectRoute = Auth::user()->hasPermissionTo('jewelry.transfers.view')
            ? 'admin.jewelry.transfers.show'
            : 'my-jewelry.transfers.show';

        try {
            $this->service->cancel($model->getKey(), Auth::user(), $request->get('approval_note'));

            return redirect()
                ->route($redirectRoute, $model)
                ->with('success', 'درخواست انتقال لغو شد.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            report($e);

            return back()->with('error', 'خطای غیرمنتظره در لغو انتقال.');
        }
    }

    // =========================================================
    // پنل کاربری مالک (my-jewelry)
    // =========================================================

    /**
     * فهرست درخواست‌های انتقال مرتبط با کاربر جاری.
     *
     * @return \Illuminate\View\View
     */
    public function myIndex()
    {
        $userId = Auth::id();

        $transfers = JewelryTransfer::query()
            ->with(['certificate', 'fromOwner', 'toOwner', 'requester'])
            ->where(function ($q) use ($userId) {
                $q->where('requested_by', $userId)
                  ->orWhereHas('fromOwner', function ($q2) use ($userId) {
                      $q2->where('user_id', $userId);
                  })
                  ->orWhereHas('toOwner', function ($q2) use ($userId) {
                      $q2->where('user_id', $userId);
                  });
            })
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('frontend.jewelry.transfers.index', compact('transfers'));
    }

    /**
     * فرم ثبت درخواست انتقال توسط مالک فعلی.
     *
     * ── محافظت از IDOR ─────────────────────────────────────────────
     * مالکیت قطعه پیش از نمایش فرم بررسی می‌شود. کاربر نمی‌تواند با
     * تغییر شناسه در URL، قطعه دیگری را ببیند.
     *
     * @param  int $certificate
     * @return \Illuminate\View\View
     */
    public function myCreate($certificate)
    {
        $certificate = JewelryCertificate::with('owner')->findOrFail($certificate);

        // بررسی مالکیت واقعی (نه فقط مجوز سراسری)
        $this->authorize('view', $certificate);

        if ((int) $certificate->owner->user_id !== (int) Auth::id()) {
            abort(403, 'شما مالک فعلی این قطعه نیستید.');
        }

        if ($certificate->pendingTransfers()->exists()) {
            return redirect()
                ->route('my-jewelry.transfers.index')
                ->with('error', 'برای این قطعه درخواست انتقال در انتظار تأیید وجود دارد.');
        }

        $owners = JewelryOwner::query()
            ->where('id', '!=', $certificate->owner_id)
            ->orderBy('full_name')
            ->get();

        return view('frontend.jewelry.transfers.create', compact('certificate', 'owners'));
    }

    /**
     * ثبت درخواست انتقال توسط مالک.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function myStore(Request $request)
    {
        $validated = $request->validate([
            'certificate_id' => 'required|integer|exists:jewelry_certificates,id',
            'to_owner_id'    => 'required|integer|exists:jewelry_owners,id',
            'transfer_date'  => 'required|date',
            'transfer_type'  => 'required|in:فروش,هدیه,ارث,سایر',
            'notes'          => 'nullable|string|max:2000',
            'price'          => 'nullable|numeric|min:0|max:999999999999',
            'contract_file'  => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $certificate = JewelryCertificate::with('owner')->findOrFail($validated['certificate_id']);

        // بررسی مالکیت — جلوگیری از IDOR و انتقال قطعه دیگران
        if (!$certificate->owner || (int) $certificate->owner->user_id !== (int) Auth::id()) {
            abort(403, 'شما مالک فعلی این قطعه نیستید.');
        }

        // مالک مقصد باید متعلق به همین کاربر باشد (او مالک جدید را ثبت کرده)
        $toOwner = JewelryOwner::findOrFail($validated['to_owner_id']);
        if ((int) $toOwner->user_id !== (int) Auth::id()) {
            abort(403, 'مالک مقصد انتخابی متعلق به حساب شما نیست.');
        }

        if ($request->hasFile('contract_file')) {
            $validated['contract_file'] = $this->storeContractFile($request->file('contract_file'));
        }

        try {
            $transfer = $this->service->requestTransfer($validated, Auth::user());

            return redirect()
                ->route('my-jewelry.transfers.show', $transfer)
                ->with('success', 'درخواست انتقال ثبت شد و پس از بررسی و تأیید، نهایی خواهد شد.');
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'خطای غیرمنتظره در ثبت درخواست.');
        }
    }

    /**
     * نمایش جزئیات درخواست در پنل کاربری.
     *
     * @param  int $transfer
     * @return \Illuminate\View\View
     */
    public function myShow($transfer)
    {
        $transfer = JewelryTransfer::with([
            'certificate.owner',
            'fromOwner',
            'toOwner',
            'requester',
            'approver',
        ])->findOrFail($transfer);

        // Policy بررسی می‌کند که کاربر یکی از طرفین درخواست باشد
        $this->authorize('view', $transfer);

        $history = $this->service->historyFor($transfer->certificate_id);

        return view('frontend.jewelry.transfers.show', compact('transfer', 'history'));
    }

    /**
     * نمایش تاریخچه مالکیت یک قطعه.
     *
     * @param  int $certificate
     * @return \Illuminate\View\View
     */
    public function history($certificate)
    {
        $certificate = JewelryCertificate::with('owner')->findOrFail($certificate);

        $this->authorize('viewHistory', $certificate);

        $history = $this->service->historyFor($certificate->getKey());
        $consistency = $this->service->checkConsistency($certificate);

        return view('frontend.jewelry.transfers.history', compact('certificate', 'history', 'consistency'));
    }

    // =========================================================
    // آپلود امن فایل
    // =========================================================

    /**
     * ذخیره امن مدرک قرارداد انتقال.
     *
     * ── کنترل‌های امنیتی ────────────────────────────────────────────
     *  ۱) بررسی «نوع واقعی فایل» با MIME sniffing — نه فقط پسوند
     *  ۲) محدودیت حجم ۵ مگابایت
     *  ۳) تولید نام فایل تصادفی (جلوگیری از Path Traversal و بازنویسی)
     *  ۴) ذخیره در دیسک محلی خارج از دسترس اجرا
     *  ۵) بازگشت مسیر نسبی — نه URL کامل
     *
     * @param  \Illuminate\Http\UploadedFile $file
     * @return string مسیر نسبی فایل
     *
     * @throws \RuntimeException
     */
    protected function storeContractFile($file)
    {
        if (!$file || !$file->isValid()) {
            throw new \RuntimeException('فایل مدرک نامعتبر است.');
        }

        // نوع واقعی MIME بر اساس محتوای فایل، نه پسوند
        $realMime = $file->getMimeType();

        $allowedMimes = [
            'application/pdf',
            'image/jpeg',
            'image/png',
        ];

        if (!in_array($realMime, $allowedMimes, true)) {
            throw new \RuntimeException('نوع فایل مجاز نیست. تنها PDF، JPG و PNG پذیرفته می‌شود.');
        }

        // محدودیت حجم — سمت سرور (اعتبارسنجی فرم کافی نیست)
        if ($file->getSize() > 5 * 1024 * 1024) {
            throw new \RuntimeException('حجم فایل نباید بیشتر از ۵ مگابایت باشد.');
        }

        // نام امن و تصادفی — هیچ بخشی از نام اصلی کاربر استفاده نمی‌شود
        $extension = $this->safeExtension($realMime);
        $filename = 'contract_' . date('Ymd_His') . '_' . bin2hex(random_bytes(8)) . '.' . $extension;

        // مسیر کنترل‌شده — بدون امکان خروج از پوشه مقصد
        $directory = 'transfer-contracts/' . date('Y/m');

        return $file->storeAs($directory, $filename, 'local');
    }

    /**
     * نگاشت MIME به پسوند امن.
     *
     * @param  string $mime
     * @return string
     */
    protected function safeExtension($mime)
    {
        $map = [
            'application/pdf' => 'pdf',
            'image/jpeg'      => 'jpg',
            'image/png'       => 'png',
        ];

        return isset($map[$mime]) ? $map[$mime] : 'bin';
    }
}
