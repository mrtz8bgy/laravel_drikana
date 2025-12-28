<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use PDF;
use PDFNEW;
use Auth;

class InvoiceController extends Controller
{
    //downloads customer invoice
    public function customer_invoice_download($id)
    {
        $order = Order::findOrFail($id);
        $odate = date('d-m-Y', $order->date);
        $order->date = \Morilog\Jalali\CalendarUtils::strftime('d-m-Y', strtotime($odate));
        $pdf = PDFNEW::loadView('invoices.customer_invoice', compact('order'),[],['mode' => 'utf-8']);            
        return $pdf->download('order-'.$order->code.'.pdf');
    }

    //downloads seller invoice
    public function seller_invoice_download($id)
    {
        $order = Order::findOrFail($id);
        $odate = date('Y-m-d', $order->date);
        $order->date = \Morilog\Jalali\CalendarUtils::strftime('Y/m/d', strtotime($odate));
        $pdf = PDFNEW::loadView('invoices.customer_invoice', compact('order'),[],['mode' => 'utf-8']);            
        return $pdf->download('order-'.$order->code.'.pdf');
    }

    //downloads admin invoice
    public function admin_invoice_download($id)
    {
        $order = Order::findOrFail($id);
        $odate = date('Y-m-d', $order->date);
        $order->date = \Morilog\Jalali\CalendarUtils::strftime('Y/m/d', strtotime($odate));
        $pdf = PDFNEW::loadView('invoices.customer_invoice', compact('order'),[],['mode' => 'utf-8']);            
        return $pdf->download('order-'.$order->code.'.pdf');
    }
}


