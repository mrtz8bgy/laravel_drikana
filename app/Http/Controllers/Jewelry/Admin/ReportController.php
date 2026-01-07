<?php

namespace App\Http\Controllers\Jewelry\Admin;

use App\Http\Controllers\Controller;
use App\Models\JewelryCertificate;
use App\Models\RepairOrder;
use App\Models\JewelryTransfer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * نمایش داشبورد گزارشات ادمین
     */
    public function summary(Request $request)
    {
        // آمار کلی
        $stats = [
            'total_certificates' => JewelryCertificate::count(),
            'verified_certificates' => JewelryCertificate::where('is_verified', true)->count(),
            'pending_certificates' => JewelryCertificate::where('is_verified', false)->whereNull('rejected_at')->count(),
            'total_repair_orders' => RepairOrder::count(),
            'active_repairs' => RepairOrder::whereIn('status', ['در انتظار', 'در حال تعمیر'])->count(),
            'completed_repairs' => RepairOrder::where('status', 'تکمیل شده')->count(),
            'total_transfers' => JewelryTransfer::count(),
            'total_users_with_jewelry' => User::whereHas('jewelryOwners.certificates')->count(),
        ];
        
        // آمار ماهانه شناسنامه‌ها
        $monthlyCertificates = JewelryCertificate::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', Carbon::now()->subMonths(6))
        ->groupBy('month')
        ->orderBy('month')
        ->get();
        
        // آمار ماهانه سفارشات تعمیر
        $monthlyRepairs = RepairOrder::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', Carbon::now()->subMonths(6))
        ->groupBy('month')
        ->orderBy('month')
        ->get();
        
        // توزیع نوع فلزات
        $metalDistribution = JewelryCertificate::select(
            'metal_type',
            DB::raw('COUNT(*) as count')
        )
        ->groupBy('metal_type')
        ->get();
        
        // توزیع وضعیت سفارشات تعمیر
        $repairStatusDistribution = RepairOrder::select(
            'status',
            DB::raw('COUNT(*) as count')
        )
        ->groupBy('status')
        ->get();
        
        // آخرین فعالیت‌ها
        $recentCertificates = JewelryCertificate::with('owner.user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
            
        $recentRepairs = RepairOrder::with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        return view('admin.jewelry.reports.summary', compact(
            'stats',
            'monthlyCertificates',
            'monthlyRepairs',
            'metalDistribution',
            'repairStatusDistribution',
            'recentCertificates',
            'recentRepairs'
        ));
    }
    
    /**
     * گزارش انتقالات
     */
    public function transfers(Request $request)
    {
        $query = JewelryTransfer::with([
            'certificate',
            'fromOwner.user',
            'toOwner.user'
        ]);
        
        // فیلتر بر اساس نوع انتقال
        if ($request->has('transfer_type')) {
            $query->where('transfer_type', $request->get('transfer_type'));
        }
        
        // فیلتر بر اساس تاریخ
        if ($request->has('date_from')) {
            $query->whereDate('transfer_date', '>=', $request->get('date_from'));
        }
        if ($request->has('date_to')) {
            $query->whereDate('transfer_date', '<=', $request->get('date_to'));
        }
        
        // جستجو
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('certificate', function($q2) use ($search) {
                    $q2->where('serial_number', 'LIKE', "%{$search}%")
                       ->orWhere('product_name', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('fromOwner', function($q2) use ($search) {
                    $q2->where('full_name', 'LIKE', "%{$search}%")
                       ->orWhere('national_id', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('toOwner', function($q2) use ($search) {
                    $q2->where('full_name', 'LIKE', "%{$search}%")
                       ->orWhere('national_id', 'LIKE', "%{$search}%");
                });
            });
        }
        
        $transfers = $query->orderBy('transfer_date', 'desc')->paginate(20);
        
        $transferTypes = ['فروش', 'هدیه', 'ارث', 'سایر'];
        
        return view('admin.jewelry.reports.transfers', compact('transfers', 'transferTypes'));
    }
    
    /**
     * گزارش سفارشات تعمیر
     */
    public function repairs(Request $request)
    {
        $query = RepairOrder::with(['user', 'certificate.owner.user', 'assignedTo']);
        
        // فیلترها
        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }
        
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }
        
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }
        
        if ($request->has('technician_id')) {
            $query->where('assigned_to', $request->get('technician_id'));
        }
        
        // جستجو
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('description', 'LIKE', "%{$search}%")
                  ->orWhere('id', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('email', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('certificate', function($q2) use ($search) {
                      $q2->where('serial_number', 'LIKE', "%{$search}%")
                         ->orWhere('product_name', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        $repairs = $query->orderBy('created_at', 'desc')->paginate(20);
        
        $statuses = ['در انتظار', 'در حال تعمیر', 'تکمیل شده', 'لغو شده'];
        $technicians = User::whereIn('user_type', ['staff', 'admin'])->get(['id', 'name', 'email']);
        
        return view('admin.jewelry.reports.repairs', compact('repairs', 'statuses', 'technicians'));
    }
    
    /**
     * گزارش شناسنامه‌ها
     */
    public function certificates(Request $request)
    {
        $query = JewelryCertificate::with(['owner.user']);
        
        // فیلترها
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
        
        if ($request->has('metal_type')) {
            $query->where('metal_type', $request->get('metal_type'));
        }
        
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }
        
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }
        
        // جستجو
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('serial_number', 'LIKE', "%{$search}%")
                  ->orWhere('product_name', 'LIKE', "%{$search}%")
                  ->orWhereHas('owner', function($q2) use ($search) {
                      $q2->where('full_name', 'LIKE', "%{$search}%")
                         ->orWhere('national_id', 'LIKE', "%{$search}%")
                         ->orWhere('phone', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        $certificates = $query->orderBy('created_at', 'desc')->paginate(20);
        
        $metalTypes = ['طلا', 'نقره', 'پلاتین', 'سایر'];
        
        return view('admin.jewelry.reports.certificates', compact('certificates', 'metalTypes'));
    }
    
    /**
     * خروجی اکسل از گزارشات
     */
    public function export(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:certificates,repairs,transfers',
            'format' => 'required|in:excel,csv,pdf',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);
        
        $reportType = $request->get('report_type');
        $format = $request->get('format');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        
        // ایجاد نام فایل
        $filename = 'jewelry_' . $reportType . '_report_' . date('Y-m-d_H-i') . '.' . $format;
        
        // TODO: پیاده‌سازی خروجی اکسل/CSV/PDF
        // در اینجا باید از کتابخانه‌هایی مانند Maatwebsite/Laravel-Excel یا barryvdh/laravel-dompdf استفاده شود
        
        // به عنوان نمونه یک پاسخ JSON برمی‌گردانیم
        return response()->json([
            'success' => true,
            'message' => 'گزارش با موفقیت تولید شد.',
            'filename' => $filename,
            'download_url' => route('admin.jewelry.reports.download', ['filename' => $filename]),
        ]);
    }
    
    /**
     * آمار لحظه‌ای (برای داشبورد ادمین)
     */
    public function liveStats()
    {
        // آمار امروز
        $today = Carbon::today();
        
        $todayStats = [
            'new_certificates' => JewelryCertificate::whereDate('created_at', $today)->count(),
            'new_repairs' => RepairOrder::whereDate('created_at', $today)->count(),
            'completed_repairs' => RepairOrder::whereDate('completed_at', $today)->count(),
            'pending_verifications' => JewelryCertificate::where('is_verified', false)
                ->whereNull('rejected_at')
                ->whereDate('created_at', $today)
                ->count(),
        ];
        
        // آمار هفته جاری
        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd = Carbon::now()->endOfWeek();
        
        $weeklyStats = [
            'certificates' => JewelryCertificate::whereBetween('created_at', [$weekStart, $weekEnd])->count(),
            'repairs' => RepairOrder::whereBetween('created_at', [$weekStart, $weekEnd])->count(),
            'transfers' => JewelryTransfer::whereBetween('transfer_date', [$weekStart, $weekEnd])->count(),
        ];
        
        return response()->json([
            'today' => $todayStats,
            'week' => $weeklyStats,
            'timestamp' => Carbon::now()->toDateTimeString(),
        ]);
    }
    
    /**
     * نمودارهای آماری (برای AJAX)
     */
    public function charts(Request $request)
    {
        $request->validate([
            'chart_type' => 'required|in:monthly_certificates,monthly_repairs,metal_distribution,repair_status',
            'period' => 'nullable|in:week,month,quarter,year',
        ]);
        
        $chartType = $request->get('chart_type');
        $period = $request->get('period', 'month');
        
        $data = [];
        
        switch ($chartType) {
            case 'monthly_certificates':
                $data = $this->getMonthlyCertificatesData($period);
                break;
                
            case 'monthly_repairs':
                $data = $this->getMonthlyRepairsData($period);
                break;
                
            case 'metal_distribution':
                $data = $this->getMetalDistributionData();
                break;
                
            case 'repair_status':
                $data = $this->getRepairStatusData();
                break;
        }
        
        return response()->json([
            'success' => true,
            'data' => $data,
            'labels' => array_keys($data),
            'values' => array_values($data),
        ]);
    }
    
    /**
     * داده‌های ماهانه شناسنامه‌ها
     */
    private function getMonthlyCertificatesData($period = 'month')
    {
        $dateFormat = $this->getDateFormat($period);
        $startDate = $this->getStartDate($period);
        
        return JewelryCertificate::select(
            DB::raw("DATE_FORMAT(created_at, '{$dateFormat}') as period"),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', $startDate)
        ->groupBy('period')
        ->orderBy('period')
        ->pluck('count', 'period')
        ->toArray();
    }
    
    /**
     * داده‌های ماهانه سفارشات تعمیر
     */
    private function getMonthlyRepairsData($period = 'month')
    {
        $dateFormat = $this->getDateFormat($period);
        $startDate = $this->getStartDate($period);
        
        return RepairOrder::select(
            DB::raw("DATE_FORMAT(created_at, '{$dateFormat}') as period"),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', $startDate)
        ->groupBy('period')
        ->orderBy('period')
        ->pluck('count', 'period')
        ->toArray();
    }
    
    /**
     * توزیع نوع فلزات
     */
    private function getMetalDistributionData()
    {
        return JewelryCertificate::select(
            'metal_type',
            DB::raw('COUNT(*) as count')
        )
        ->groupBy('metal_type')
        ->pluck('count', 'metal_type')
        ->toArray();
    }
    
    /**
     * توزیع وضعیت سفارشات تعمیر
     */
    private function getRepairStatusData()
    {
        return RepairOrder::select(
            'status',
            DB::raw('COUNT(*) as count')
        )
        ->groupBy('status')
        ->pluck('count', 'status')
        ->toArray();
    }
    
    /**
     * فرمت تاریخ بر اساس دوره
     */
    private function getDateFormat($period)
    {
        switch ($period) {
            case 'week': return '%Y-%U';
            case 'month': return '%Y-%m';
            case 'quarter': return '%Y-%m';
            case 'year': return '%Y';
            default: return '%Y-%m';
        }
    }
    
    /**
     * تاریخ شروع بر اساس دوره
     */
    private function getStartDate($period)
    {
        switch ($period) {
            case 'week': return Carbon::now()->subWeek();
            case 'month': return Carbon::now()->subMonth();
            case 'quarter': return Carbon::now()->subMonths(3);
            case 'year': return Carbon::now()->subYear();
            default: return Carbon::now()->subMonth();
        }
    }
}