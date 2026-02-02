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
        $owners = JewelryOwner::where('user_id', Auth::id())
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
        
        $owners = JewelryOwner::where('user_id', Auth::id())
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
        $owners = JewelryOwner::where('user_id', Auth::id())
            ->select('id', 'full_name', 'national_id')
            ->orderBy('full_name')
            ->get();
        
        return response()->json($owners);
    }
}
