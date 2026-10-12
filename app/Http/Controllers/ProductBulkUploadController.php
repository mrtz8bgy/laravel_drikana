<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Category;
use App\SubCategory;
use App\SubSubCategory;
use App\Brand;
use App\User;
use Auth;
use App\ProductsImport;
use App\ProductsExport;
// نکته: الیاس سراسری `PDF` توسط پکیج niklasravnsborg/laravel-pdf (از طریق
// bootstrap/cache/packages.php) روی mPDF بازنویسی می‌شود و متد
// setOptions ندارد. بنابراین اینجا کلاس dompdf را صریح import می‌کنیم.
use Barryvdh\DomPDF\Facade as PDF;
use Excel;
use App\Http\Controllers\Concerns\BuildsPdfDocuments;
use App\Http\Controllers\Concerns\HandlesCsvDownload;

class ProductBulkUploadController extends Controller
{
    use BuildsPdfDocuments;
    use HandlesCsvDownload;

    public function index()
    {
        if (Auth::user()->user_type == 'seller') {
            return view('frontend.seller.product_bulk_upload.index');
        }
        elseif (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff') {
            return view('admin.bulk_upload.index');
        }
    }

    public function export(){
        return Excel::download(new ProductsExport, 'products.xlsx');
    }

    public function pdf_download_category()
    {
        $categories = Category::all();
        return $this->pdfDownload('downloads.category', compact('categories'), 'category.pdf');
    }

    public function pdf_download_sub_category()
    {
        $sub_categories = Subcategory::all();
        return $this->pdfDownload('downloads.sub_category', compact('sub_categories'), 'sub_category.pdf');
    }

    public function pdf_download_sub_sub_category()
    {
        $sub_sub_categories = SubSubCategory::all();
        return $this->pdfDownload('downloads.sub_sub_category', compact('sub_sub_categories'), 'sub_sub_category.pdf');
    }

    public function pdf_download_brand()
    {
        $brands = Brand::all();
        return $this->pdfDownload('downloads.brand', compact('brands'), 'brands.pdf');
    }

    public function pdf_download_seller()
    {
        $users = User::where('user_type','seller')->get();
        return $this->pdfDownload('downloads.user', compact('users'), 'user.pdf');
    }

    public function bulk_upload(Request $request)
    {
        if($request->hasFile('bulk_file')){
            Excel::import(new ProductsImport, request()->file('bulk_file'));
        }
        flash('Products exported successfully')->success();
        return back();
    }


    /**
     * دانلود فایل الگوی CSV برای بارگذاری گروهی محصولات.
     *
     * [رفع باگ — Stage 2]
     * روت `GET /product-csv-download/{type}` (نام: product_csv.download)
     * به متد `import_product` اشاره می‌کرد اما این متد وجود نداشت و
     * کاربر با خطای 500 مواجه می‌شد.
     *
     * نکته امنیتی:
     *   پارامتر `type` از یک فهرست سفید (allowlist) تأیید می‌شود، نه با
     *   ساختن مستقیم مسیر فایل از ورودی کاربر. بنابراین امکان Path
     *   Traversal یا دسترسی به فایل دلخواه وجود ندارد.
     *
     * @param  string $type نوع الگو
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\RedirectResponse
     */
    public function import_product($type)
    {
        // فهرست سفید انواع مجاز — هر مقدار دیگری رد می‌شود
        $allowed = ['category', 'subcategory', 'subsubcategory', 'brand', 'seller'];

        if (!in_array($type, $allowed, true)) {
            flash(__('نوع الگوی درخواستی معتبر نیست.'))->error();
            return redirect()->back();
        }

        $columns = $this->csvTemplateColumns();
        $rows    = $this->csvTemplateRows($type);

        return $this->streamCsv('product_template_' . $type . '.csv', $columns, $rows);
    }

    /**
     * دانلود فایل الگوی CSV برای محصولات یک فروشنده مشخص.
     *
     * [رفع باگ — Stage 2]
     * روت `GET /vendor-product-csv-download/{id}` به متد
     * `import_vendor_product` اشاره می‌کرد که وجود نداشت.
     *
     * @param  int $id شناسه فروشنده
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function import_vendor_product($id)
    {
        $seller = User::findOrFail($id);

        $rows = $this->csvTemplateRows('seller');

        // درج نام فروشنده تنها در سطر نمونه
        if (!empty($rows) && isset($rows[0][0])) {
            $rows[0][0] = $seller->name;
        }

        return $this->streamCsv('vendor_product_template_' . (int) $seller->id . '.csv',
            $this->csvTemplateColumns(), $rows);
    }

    /**
     * سرستون‌های استاندارد فایل CSV محصولات.
     *
     * @return array
     */
    protected function csvTemplateColumns()
    {
        return [
            'name', 'category', 'subcategory', 'subsubcategory', 'brand',
            'unit', 'unit_price', 'purchase_price', 'current_stock',
            'description',
        ];
    }

    /**
     * سطرهای نمونه فایل CSV بر اساس نوع الگو.
     *
     * @param  string $type
     * @return array
     */
    protected function csvTemplateRows($type)
    {
        $sample = [
            'دستبند طلا ۱۸ عیار', 'طلا و جواهرات', 'دستبند', 'دستبند طلا',
            'دریکانا', 'عدد', '25000000', '20000000', '10',
            'دستبند طلا ۱۸ عیار با قفل ایمنی',
        ];

        return [$sample];
    }
}


