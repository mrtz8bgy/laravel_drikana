<?php

namespace App\Http\Controllers\Jewelry;

use App\Http\Controllers\Controller;
use App\Models\JewelryCertificate;
use App\Models\JewelryOwner;
use App\Models\JewelryTransfer;
use App\Models\MissingReport;
use App\Models\RepairOrder;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * نمایش داشبورد سامانه جواهرات.
     */
    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;
        $isSuperAdmin = $user->isSuperAdmin();

        $owners = $this->ownersQuery($userId, $isSuperAdmin);
        $certificates = $this->certificatesQuery($userId, $isSuperAdmin);
        $repairs = $this->repairsQuery($userId, $isSuperAdmin);
        $transfers = $this->transfersQuery($userId, $isSuperAdmin);
        $missingReports = $this->missingReportsQuery($userId, $isSuperAdmin);

        $stats = [
            'owners_count' => (clone $owners)->count(),
            'certificates_count' => (clone $certificates)->count(),
            'verified_certificates' => (clone $certificates)
                ->where('is_verified', true)
                ->whereNull('rejected_at')
                ->count(),
            'pending_verification' => (clone $certificates)
                ->where('is_verified', false)
                ->whereNull('rejected_at')
                ->count(),
            'rejected_certificates' => (clone $certificates)
                ->whereNotNull('rejected_at')
                ->count(),
            'repair_orders_count' => (clone $repairs)->count(),
            'pending_repairs' => (clone $repairs)->where('status', 'pending')->count(),
            'in_progress_repairs' => (clone $repairs)->where('status', 'in_progress')->count(),
            'active_repairs' => (clone $repairs)->whereIn('status', ['pending', 'in_progress'])->count(),
            'completed_repairs' => (clone $repairs)->whereIn('status', ['completed', 'delivered'])->count(),
            'urgent_repairs' => (clone $repairs)
                ->where('priority', 'urgent')
                ->whereIn('status', ['pending', 'in_progress'])
                ->count(),
            'transfers_count' => (clone $transfers)->count(),
            'pending_transfers' => (clone $transfers)->where('status', JewelryTransfer::STATUS_PENDING)->count(),
            'missing_reports_count' => (clone $missingReports)->count(),
            'pending_missing_reports' => (clone $missingReports)->where('status', 'pending')->count(),
        ];

        $recentCertificates = $certificates->with('owner')
            ->latest('created_at')
            ->take(6)
            ->get();
        $recentRepairs = $repairs->with(['certificate.owner', 'technician'])
            ->latest('created_at')
            ->take(6)
            ->get();
        $recentTransfers = $transfers->with(['certificate', 'fromOwner', 'toOwner'])
            ->latest('created_at')
            ->take(6)
            ->get();
        $recentMissingReports = $missingReports->with('jewelryCertificate')
            ->latest('created_at')
            ->take(5)
            ->get();

        $metalDistribution = (clone $certificates)
            ->select('metal_type')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('metal_type')
            ->orderByDesc('total')
            ->get();

        return view('admin.jewelry.dashboard.index', compact(
            'stats',
            'recentCertificates',
            'recentRepairs',
            'recentTransfers',
            'recentMissingReports',
            'metalDistribution',
            'isSuperAdmin'
        ));
    }

    /**
     * نمایش خلاصه وضعیت برای درخواست‌های AJAX.
     */
    public function summary()
    {
        $user = Auth::user();
        $userId = $user->id;
        $isSuperAdmin = $user->isSuperAdmin();
        $certificates = $this->certificatesQuery($userId, $isSuperAdmin);
        $repairs = $this->repairsQuery($userId, $isSuperAdmin);

        return response()->json([
            'total_certificates' => (clone $certificates)->count(),
            'pending_repairs' => (clone $repairs)->where('status', 'pending')->count(),
            'in_progress_repairs' => (clone $repairs)->where('status', 'in_progress')->count(),
            'recent_activity' => $this->getRecentActivity($userId, $isSuperAdmin),
        ]);
    }

    protected function ownersQuery($userId, $isSuperAdmin)
    {
        $query = JewelryOwner::query();

        if (!$isSuperAdmin) {
            $query->where('user_id', $userId);
        }

        return $query;
    }

    protected function certificatesQuery($userId, $isSuperAdmin)
    {
        $query = JewelryCertificate::query();

        if (!$isSuperAdmin) {
            $query->whereHas('owner', function ($ownerQuery) use ($userId) {
                $ownerQuery->where('user_id', $userId);
            });
        }

        return $query;
    }

    protected function repairsQuery($userId, $isSuperAdmin)
    {
        $query = RepairOrder::query();

        if (!$isSuperAdmin) {
            $query->where('user_id', $userId);
        }

        return $query;
    }

    protected function transfersQuery($userId, $isSuperAdmin)
    {
        $query = JewelryTransfer::query();

        if (!$isSuperAdmin) {
            $query->whereHas('certificate.owner', function ($ownerQuery) use ($userId) {
                $ownerQuery->where('user_id', $userId);
            });
        }

        return $query;
    }

    protected function missingReportsQuery($userId, $isSuperAdmin)
    {
        $query = MissingReport::query();

        if (!$isSuperAdmin) {
            $query->where('user_id', $userId);
        }

        return $query;
    }

    /**
     * دریافت فعالیت‌های اخیر با مرتب‌سازی بر اساس زمان واقعی ثبت.
     */
    private function getRecentActivity($userId, $isSuperAdmin)
    {
        $activities = collect();

        foreach ($this->certificatesQuery($userId, $isSuperAdmin)
            ->latest('created_at')->take(3)->get() as $certificate) {
            $activities->push([
                'title' => 'شناسنامه جدید: ' . $certificate->product_name,
                'description' => 'شماره سریال: ' . $certificate->serial_number,
                'date' => optional($certificate->created_at)->diffForHumans(),
                'timestamp' => optional($certificate->created_at)->timestamp ?: 0,
                'icon' => 'fa-certificate',
                'color' => 'success',
            ]);
        }

        foreach ($this->repairsQuery($userId, $isSuperAdmin)
            ->latest('created_at')->take(3)->get() as $repair) {
            $activities->push([
                'title' => 'سفارش تعمیر: ' . ($repair->order_number ?: '#' . $repair->id),
                'description' => 'وضعیت: ' . $repair->status_label,
                'date' => optional($repair->created_at)->diffForHumans(),
                'timestamp' => optional($repair->created_at)->timestamp ?: 0,
                'icon' => 'fa-tools',
                'color' => 'info',
            ]);
        }

        foreach ($this->transfersQuery($userId, $isSuperAdmin)
            ->latest('created_at')->take(2)->get() as $transfer) {
            $activities->push([
                'title' => 'درخواست انتقال مالکیت',
                'description' => 'نوع انتقال: ' . $transfer->transfer_type,
                'date' => optional($transfer->created_at)->diffForHumans(),
                'timestamp' => optional($transfer->created_at)->timestamp ?: 0,
                'icon' => 'fa-exchange-alt',
                'color' => 'warning',
            ]);
        }

        return $activities->sortByDesc('timestamp')
            ->take(5)
            ->map(function ($activity) {
                unset($activity['timestamp']);
                return $activity;
            })
            ->values();
    }
}
