<?php

namespace App\Http\Controllers\Jewelry;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\JewelryOwner;
use App\Models\JewelryCertificate;
use App\Models\RepairOrder;
use App\Models\JewelryTransfer;

class DashboardController extends Controller
{
    /**
     * نمایش داشبورد مدیریت جواهرات کاربر (صفحه ادمین)
     */
    public function index()
    {
        $userId = Auth::id();
        
        // آمار کلی
        $stats = [
            'owners_count' => JewelryOwner::where('user_id', $userId)->count(),
            'certificates_count' => JewelryCertificate::whereHas('owner', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })->count(),
            'verified_certificates' => JewelryCertificate::whereHas('owner', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })->where('is_verified', true)->count(),
            'repair_orders_count' => RepairOrder::where('user_id', $userId)->count(),
            'active_repairs' => RepairOrder::where('user_id', $userId)
                ->whereIn('status', ['در حال تعمیر', 'در انتظار'])
                ->count(),
            'completed_repairs' => RepairOrder::where('user_id', $userId)
                ->where('status', 'تکمیل شده')
                ->count(),
        ];
        
        // آخرین شناسنامه‌ها
        $recentCertificates = JewelryCertificate::whereHas('owner', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })
        ->with('owner')
        ->latest()
        ->take(5)
        ->get();
        
        // آخرین سفارشات تعمیر
        $recentRepairs = RepairOrder::where('user_id', $userId)
            ->with('certificate')
            ->latest()
            ->take(5)
            ->get();
        
        // آخرین انتقالات
        $recentTransfers = JewelryTransfer::whereHas('certificate.owner', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })
        ->with(['certificate', 'fromOwner', 'toOwner'])
        ->latest()
        ->take(5)
        ->get();
        
        // شناسنامه‌های نیازمند تأیید
        $pendingVerification = JewelryCertificate::whereHas('owner', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })
        ->where('is_verified', false)
        ->whereNull('rejected_at')
        ->count();
        
        // مسیر view تغییر کرد به admin.jewelry.dashboard.index
        return view('admin.jewelry.dashboard.index', compact(
            'stats', 
            'recentCertificates', 
            'recentRepairs', 
            'recentTransfers',
            'pendingVerification'
        ));
    }
    
    /**
     * نمایش خلاصه وضعیت (برای AJAX)
     */
    public function summary()
    {
        $userId = Auth::id();
        
        $summary = [
            'total_certificates' => JewelryCertificate::whereHas('owner', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })->count(),
            'pending_repairs' => RepairOrder::where('user_id', $userId)
                ->where('status', 'در انتظار')
                ->count(),
            'in_progress_repairs' => RepairOrder::where('user_id', $userId)
                ->where('status', 'در حال تعمیر')
                ->count(),
            'recent_activity' => $this->getRecentActivity($userId),
        ];
        
        return response()->json($summary);
    }
    
    /**
     * دریافت فعالیت‌های اخیر
     */
    private function getRecentActivity($userId)
    {
        $activities = [];
        
        // شناسنامه‌های اخیر
        $certificates = JewelryCertificate::whereHas('owner', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })
        ->latest()
        ->take(3)
        ->get();
        
        foreach ($certificates as $certificate) {
            $activities[] = [
                'type' => 'certificate',
                'title' => 'شناسنامه جدید: ' . $certificate->product_name,
                'description' => 'شماره سریال: ' . $certificate->serial_number,
                'date' => $certificate->created_at->diffForHumans(),
                'icon' => 'fa-certificate',
                'color' => 'success',
            ];
        }
        
        // سفارشات تعمیر اخیر
        $repairs = RepairOrder::where('user_id', $userId)
            ->latest()
            ->take(3)
            ->get();
        
        foreach ($repairs as $repair) {
            $activities[] = [
                'type' => 'repair',
                'title' => 'سفارش تعمیر جدید',
                'description' => 'نوع تعمیر: ' . $repair->repair_type,
                'date' => $repair->created_at->diffForHumans(),
                'icon' => 'fa-tools',
                'color' => 'info',
            ];
        }
        
        // انتقالات اخیر
        $transfers = JewelryTransfer::whereHas('certificate.owner', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })
        ->latest()
        ->take(2)
        ->get();
        
        foreach ($transfers as $transfer) {
            $activities[] = [
                'type' => 'transfer',
                'title' => 'انتقال مالکیت',
                'description' => 'نوع انتقال: ' . $transfer->transfer_type,
                'date' => $transfer->created_at->diffForHumans(),
                'icon' => 'fa-exchange-alt',
                'color' => 'warning',
            ];
        }
        
        // مرتب‌سازی بر اساس تاریخ
        usort($activities, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        
        return array_slice($activities, 0, 5);
    }
}
