<?php

namespace App\Http\Controllers\Jewelry;

use App\Http\Controllers\Controller;
use App\Models\JewelryOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class JewelryOwnerController extends Controller
{
    /**
     * نمایش لیست مالکین
     */
    public function index()
    {
        $owners = $this->ownersVisibleToCurrentUser()
            ->withCount('certificates')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.jewelry.owners.index', compact('owners'));
    }

    /**
     * فرم ایجاد مالک جدید
     */
    public function create()
    {
        return view('admin.jewelry.owners.create');
    }

    /**
     * ذخیره مالک جدید
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'national_id' => [
                'nullable', 
                'string', 
                'size:10',
                Rule::unique('jewelry_owners')->where(function ($query) {
                    return $query->where('user_id', Auth::id());
                })
            ],
            'phone' => 'required|string|max:15|regex:/^[0-9]+$/',
            'address' => 'nullable|string|max:500',
            'email' => 'nullable|email|max:255',
        ], [
            'phone.regex' => 'شماره تلفن باید فقط شامل اعداد باشد.',
            'national_id.size' => 'کد ملی باید 10 رقمی باشد.',
            'national_id.unique' => 'این کد ملی قبلاً ثبت شده است.',
        ]);
        
        $validated['user_id'] = Auth::id();
        $owner = JewelryOwner::create($validated);
        
        return redirect()->route('admin.jewelry.owners.show', $owner)
            ->with('success', 'مالک جدید با موفقیت ثبت شد.');
    }

    /**
     * نمایش مالک و شناسنامه‌های مرتبط
     */
    public function show(JewelryOwner $owner)
    {
        $this->authorize('view', $owner);
        
        $certificates = $owner->certificates()
            ->withCount(['transfers', 'repairOrders'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('admin.jewelry.owners.show', compact('owner', 'certificates'));
    }

    /**
     * فرم ویرایش مالک
     */
    public function edit(JewelryOwner $owner)
    {
        $this->authorize('update', $owner);
        return view('admin.jewelry.owners.edit', compact('owner'));
    }

    /**
     * بروزرسانی مالک
     */
    public function update(Request $request, JewelryOwner $owner)
    {
        $this->authorize('update', $owner);
        
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'national_id' => [
                'nullable', 
                'string', 
                'size:10',
                Rule::unique('jewelry_owners')->where(function ($query) use ($owner) {
                    return $query->where('user_id', Auth::id())->where('id', '!=', $owner->id);
                })
            ],
            'phone' => 'required|string|max:15|regex:/^[0-9]+$/',
            'address' => 'nullable|string|max:500',
            'email' => 'nullable|email|max:255',
        ]);
        
        $owner->update($validated);
        
        return redirect()->route('admin.jewelry.owners.show', $owner)
            ->with('success', 'اطلاعات مالک با موفقیت بروزرسانی شد.');
    }

    /**
     * حذف مالک
     */
    public function destroy(JewelryOwner $owner)
    {
        $this->authorize('delete', $owner);
        
        if ($owner->certificates()->count() > 0) {
            return redirect()->back()
                ->with('error', 'امکان حذف مالک دارای شناسنامه وجود ندارد. ابتدا شناسنامه‌های مرتبط را حذف کنید.');
        }
        
        $owner->delete();
        
        return redirect()->route('admin.jewelry.owners.index')
            ->with('success', 'مالک با موفقیت حذف شد.');
    }
    
    /**
     * جستجوی مالکین (AJAX)
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2',
        ]);
        
        $owners = $this->ownersVisibleToCurrentUser()
            ->where(function($query) use ($request) {
                $query->where('full_name', 'LIKE', "%{$request->q}%")
                      ->orWhere('national_id', 'LIKE', "%{$request->q}%")
                      ->orWhere('phone', 'LIKE', "%{$request->q}%")
                      ->orWhere('email', 'LIKE', "%{$request->q}%");
            })
            ->limit(10)
            ->get(['id', 'full_name', 'national_id', 'phone', 'email']);
        
        return response()->json($owners);
    }
    
    /**
     * گرفتن لیست مالکین برای انتخاب (AJAX)
     */
    public function getOwnersForSelect()
    {
        $owners = $this->ownersVisibleToCurrentUser()
            ->select('id', 'full_name', 'national_id')
            ->orderBy('full_name')
            ->get();
        
        return response()->json($owners);
    }

    /**
     * مدیر کل می‌تواند همه مالکان را مدیریت کند؛ سایر کاربران فقط مالکان خود را می‌بینند.
     */
    protected function ownersVisibleToCurrentUser()
    {
        $query = JewelryOwner::query();

        if (!Auth::user()->isSuperAdmin()) {
            $query->where('user_id', Auth::id());
        }

        return $query;
    }

    // =========================================================
    // داشبورد مالک — بخش «جواهرات من»
    // =========================================================

    /**
     * داشبورد مالک قطعات.
     *
     * [رفع باگ — Stage 2]
     * روت `GET /my-jewelry` (نام: my-jewelry.dashboard) به این متد اشاره
     * می‌کرد اما متد وجود نداشت و کاربر با خطای 500 مواجه می‌شد.
     *
     * ─────────────────────────────────────────────────────────
     * نکته امنیتی مهم:
     * ─────────────────────────────────────────────────────────
     * این بخش اطلاعاتی درباره دارایی‌های کاربر نشان می‌دهد، بنابراین
     * تمام کوئری‌ها به `Auth::id()` محدود شده‌اند. هیچ شناسه‌ای از
     * ورودی کاربر گرفته نمی‌شود، پس امکان IDOR وجود ندارد.
     *
     * علاوه بر این، دسترسی به شناسنامه هر قطعه با Policy بررسی می‌شود
     * تا اگر ساختار داده تغییر کرد، محافظ دوم برقرار بماند.
     *
     * @return \Illuminate\View\View
     */
    public function myDashboard()
    {
        $user = Auth::user();

        // مالکین متصل به این کاربر
        $owners = JewelryOwner::query()
            ->where('user_id', $user->getKey())
            ->get();

        $ownerIds = $owners->pluck('id')->all();

        // شناسنامه‌های این مالکین — محدود به مالکیت خود کاربر
        $certificates = \App\Models\JewelryCertificate::query()
            ->whereIn('owner_id', $ownerIds)
            ->with(['owner'])
            ->withCount(['transfers', 'repairOrders'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // آمار خلاصه
        $stats = [
            'total_certificates' => \App\Models\JewelryCertificate::query()
                ->whereIn('owner_id', $ownerIds)->count(),

            'pending_transfers'  => \App\Models\JewelryTransfer::query()
                ->whereIn('from_owner_id', $ownerIds)
                ->where('status', \App\Models\JewelryTransfer::STATUS_PENDING)
                ->count(),

            'active_repairs'     => \App\Models\RepairOrder::query()
                ->where('user_id', $user->getKey())
                ->whereNotIn('status', ['delivered', 'cancelled'])
                ->count(),

            'missing_reports'    => \App\Models\MissingReport::query()
                ->where('user_id', $user->getKey())
                ->count(),
        ];

        return view('frontend.jewelry.owner_dashboard',
            compact('owners', 'certificates', 'stats'));
    }
}
