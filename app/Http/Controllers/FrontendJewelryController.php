<?php

namespace App\Http\Controllers;

use App\Models\JewelryCertificate;
use App\Models\MissingReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontendJewelryController extends Controller
{
    public function __construct()
    {
        // برای گزارش سرقت نیاز به ورود است؛ بقیه صفحات عمومی هستند
        $this->middleware('auth')->only(['reportStolenStore']);
    }

    /**
     * فرم عمومی اعلام سرقت/مفقودی
     */
    public function reportStolenForm()
    {
        $jewelries = collect();
        if (Auth::check()) {
            $jewelries = JewelryCertificate::with('owner')
                ->whereHas('owner', function($q) {
                    $q->where('user_id', Auth::id());
                })
                ->get();
        }

        // آمار کلی
        $stats = [
            'total_certificates' => JewelryCertificate::count(),
            'recovered' => MissingReport::where('status', 'found')->count(),
            'pending' => MissingReport::where('status', 'pending')->count(),
        ];

        return view('frontend.jewelry.report_stolen', compact('jewelries', 'stats'));
    }

    /**
     * ذخیره گزارش سرقت/مفقودی
     */
    public function reportStolenStore(Request $request)
    {
        $request->validate([
            'jewelry_id' => 'required|exists:jewelry_certificates,id',
            'type' => 'required|in:theft,lost',
            'location' => 'nullable|string|max:255',
            'description' => 'required|string|min:10',
        ]);

        // چک مالکیت
        $jewelry = JewelryCertificate::with('owner')
            ->where('id', $request->jewelry_id)
            ->whereHas('owner', function($q) {
                $q->where('user_id', Auth::id());
            })
            ->first();

        if (!$jewelry) {
            return back()->withErrors(['jewelry_id' => 'شما مالک این قطعه نیستید.'])->withInput();
        }

        // بررسی گزارش تکراری
        $existing = MissingReport::where('jewelry_id', $request->jewelry_id)
            ->where('status', 'pending')
            ->first();
        if ($existing) {
            return back()->with('warning', 'قبلاً یک گزارش فعال برای این قطعه ثبت شده است. کد پیگیری: #' . $existing->id);
        }

        $report = MissingReport::create([
            'user_id' => Auth::id(),
            'jewelry_id' => $request->jewelry_id,
            'type' => $request->type,
            'location' => $request->location,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        return redirect()->route('jewelry.missing_reports.create_public')
            ->with('success', 'گزارش شما با کد پیگیری #' . $report->id . ' با موفقیت ثبت شد. کارشناسان پس از بررسی با شما تماس می‌گیرند.');
    }

    /**
     * رهگیری عمومی قطعه با سریال
     */
    public function track(Request $request, $serial = null)
    {
        $certificate = null;
        $missingReport = null;
        $message = null;

        $serial = $serial ?: $request->get('serial');

        if ($serial) {
            $certificate = JewelryCertificate::where('serial_number', trim($serial))
                ->with(['owner', 'transfers.fromOwner', 'transfers.toOwner'])
                ->first();

            if ($certificate) {
                $missingReport = MissingReport::where('jewelry_id', $certificate->id)
                    ->where('status', 'pending')
                    ->latest()
                    ->first();
            } else {
                $message = 'سریال وارد شده در سامانه یافت نشد. لطفاً از صحت شماره سریال مطمئن شوید.';
            }
        }

        return view('frontend.jewelry.track', compact('certificate', 'missingReport', 'message', 'serial'));
    }
}
