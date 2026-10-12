<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UsersImport;
use App\CustomersImport;
use App\User;
use Excel;
use App\Http\Controllers\Concerns\BuildsPdfDocuments;
// نکته: الیاس سراسری `PDF` توسط پکیج niklasravnsborg/laravel-pdf (از طریق
// bootstrap/cache/packages.php) روی mPDF بازنویسی می‌شود و متد
// setOptions ندارد. بنابراین اینجا کلاس dompdf را صریح import می‌کنیم.
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;

class CustomerBulkUploadController extends Controller
{
    use BuildsPdfDocuments;

    public function index()
    {
        return view('admin.bulk_upload.customer_upload');
    }

    public function user_bulk_upload(Request $request)
    {
        if($request->hasFile('user_bulk_file')){
            Excel::import(new UsersImport, request()->file('user_bulk_file'));
        }
        flash('User exported successfully')->success();
        return back();
    }

    public function pdf_download_user()
    {
        $users = User::where('created_at','LIKE', '%'. Carbon::today()->toDateString().'%')->get();
        return $this->pdfDownload('downloads.user', compact('users'), 'user.pdf');
    }

    public function customer_bulk_file(Request $request)
    {
        if($request->hasFile('customer_bulk_file')){
            Excel::import(new CustomersImport, request()->file('customer_bulk_file'));
        }
        flash('Customers exported successfully')->success();
        return back();
    }
}


