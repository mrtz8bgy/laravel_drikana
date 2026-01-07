<?php

namespace App\Http\Controllers\Jewelry;

use App\Http\Controllers\Controller;
use App\Models\JewelryCertificate;
use App\Models\RepairOrder;
use App\Models\JewelryTransfer;
use App\Models\JewelryOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * گزارش شناسنامه‌ها
     */
    public function certificateReport(Request $request)
    {
        $userId = Auth::id();
        
        $query = JewelryCertificate::whereHas('owner', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->with('owner');
        
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
        
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $certificates = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // آمار کلی
        $stats = [
            'total' => JewelryCertificate::whereHas('owner', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })->count(),
            'verified' => JewelryCertificate::whereHas('owner', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })->where('is_verified', true)->count(),
            'pending' => JewelryCertificate::whereHas('owner', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })->where('is_verified', false)->whereNull('rejected_at')->count(),
            'rejected' => JewelryCertificate::whereHas('owner', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })->whereNotNull('rejected_at')->count(),
        ];
        
        // توزیع نوع فلزات
        $metalDistribution = JewelryCertificate::whereHas('owner', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })
        ->select('metal_type', DB::raw('COUNT(*) as count'))
        ->groupBy('metal_type')
        ->get();
        
        $metalTypes = ['طلا', 'نقره', 'پلاتین', 'سایر'];
        
        return view('jewelry.reports.certificates', compact(
            'certificates', 
            'stats', 
            'metalDistribution',
            'metalTypes'
        ));
    }
    
    /**
     * گزارش سفارشات تعمیر
     */
    public function repairReport(Request $request)
    {
        $userId = Auth::id();
        
        $query = RepairOrder::where('user_id', $userId)
            ->with('certificate');
        
        // فیلترها
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $repairOrders = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // آمار کلی
        $stats = [
            'total' => RepairOrder::where('user_id', $userId)->count(),
            'pending' => RepairOrder::where('user_id', $userId)->where('status', 'در انتظار')->count(),
            'in_progress' => RepairOrder::where('user_id', $userId)->where('status', 'در حال تعمیر')->count(),
            'completed' => RepairOrder::where('user_id', $userId)->where('status', 'تکمیل شده')->count(),
            'cancelled' => RepairOrder::where('user_id', $userId)->where('status', 'لغو شده')->count(),
        ];
        
        // توزیع نوع تعمیرات
        $repairTypeDistribution = RepairOrder::where('user_id', $userId)
            ->select('repair_type', DB::raw('COUNT(*) as count'))
            ->groupBy('repair_type')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
        
        // آمار ماهانه
        $monthlyStats = RepairOrder::where('user_id', $userId)
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        $statuses = ['در انتظار', 'در حال تعمیر', 'تکمیل شده', 'لغو شده'];
        
        return view('jewelry.reports.repairs', compact(
            'repairOrders', 
            'stats', 
            'repairTypeDistribution',
            'monthlyStats',
            'statuses'
        ));
    }
    
    /**
     * گزارش انتقالات
     */
    public function transferReport(Request $request)
    {
        $userId = Auth::id();
        
        $query = JewelryTransfer::whereHas('certificate.owner', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })
        ->with(['certificate', 'fromOwner', 'toOwner']);
        
        // فیلترها
        if ($request->has('transfer_type')) {
            $query->where('transfer_type', $request->transfer_type);
        }
        
        if ($request->has('date_from')) {
            $query->whereDate('transfer_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->whereDate('transfer_date', '<=', $request->date_to);
        }
        
        $transfers = $query->orderBy('transfer_date', 'desc')->paginate(20);
        
        // آمار کلی
        $stats = [
            'total' => JewelryTransfer::whereHas('certificate.owner', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })->count(),
            'sale' => JewelryTransfer::whereHas('certificate.owner', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })->where('transfer_type', 'فروش')->count(),
            'gift' => JewelryTransfer::whereHas('certificate.owner', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })->where('transfer_type', 'هدیه')->count(),
            'inheritance' => JewelryTransfer::whereHas('certificate.owner', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })->where('transfer_type', 'ارث')->count(),
        ];
        
        // توزیع نوع انتقالات
        $transferTypeDistribution = JewelryTransfer::whereHas('certificate.owner', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })
        ->select('transfer_type', DB::raw('COUNT(*) as count'))
        ->groupBy('transfer_type')
        ->get();
        
        // آمار ماهانه
        $monthlyStats = JewelryTransfer::whereHas('certificate.owner', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })
        ->select(
            DB::raw('DATE_FORMAT(transfer_date, "%Y-%m") as month'),
            DB::raw('COUNT(*) as count')
        )
        ->where('transfer_date', '>=', Carbon::now()->subMonths(6))
        ->groupBy('month')
        ->orderBy('month')
        ->get();
        
        $transferTypes = ['فروش', 'هدیه', 'ارث', 'سایر'];
        
        return view('jewelry.reports.transfers', compact(
            'transfers', 
            'stats', 
            'transferTypeDistribution',
            'monthlyStats',
            'transferTypes'
        ));
    }
    
    /**
     * گزارش مالی (اگر قیمت انتقالات ثبت شده باشد)
     */
    public function financialReport(Request $request)
    {
        $userId = Auth::id();
        
        $query = JewelryTransfer::whereHas('certificate.owner', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })
        ->where('transfer_type', 'فروش')
        ->whereNotNull('price')
        ->with(['certificate', 'toOwner']);
        
        // فیلترها
        if ($request->has('date_from')) {
            $query->whereDate('transfer_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->whereDate('transfer_date', '<=', $request->date_to);
        }
        
        $sales = $query->orderBy('transfer_date', 'desc')->paginate(20);
        
        // آمار مالی
        $financialStats = [
            'total_sales' => $sales->total(),
            'total_revenue' => $sales->sum('price'),
            'average_price' => $sales->avg('price'),
            'max_price' => $sales->max('price'),
            'min_price' => $sales->min('price'),
        ];
        
        // درآمد ماهانه
        $monthlyRevenue = JewelryTransfer::whereHas('certificate.owner', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })
        ->where('transfer_type', 'فروش')
        ->whereNotNull('price')
        ->select(
            DB::raw('DATE_FORMAT(transfer_date, "%Y-%m") as month'),
            DB::raw('SUM(price) as revenue'),
            DB::raw('COUNT(*) as count')
        )
        ->where('transfer_date', '>=', Carbon::now()->subMonths(6))
        ->groupBy('month')
        ->orderBy('month')
        ->get();
        
        return view('jewelry.reports.financial', compact(
            'sales', 
            'financialStats', 
            'monthlyRevenue'
        ));
    }
    
    /**
     * خروجی اکسل از گزارشات
     */
    public function export(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:certificates,repairs,transfers',
            'format' => 'required|in:excel,csv',
        ]);
        
        $userId = Auth::id();
        $reportType = $request->get('report_type');
        $format = $request->get('format');
        
        // TODO: پیاده‌سازی خروجی اکسل/CSV
        // می‌توانید از کتابخانه Laravel Excel استفاده کنید
        
        return response()->json([
            'success' => true,
            'message' => 'گزارش آماده شده است.',
            'report_type' => $reportType,
        ]);
    }
}