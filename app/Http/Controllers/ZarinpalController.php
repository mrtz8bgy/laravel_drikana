<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
use App\Order;
use App\BusinessSetting;
use Session;
use App\CustomerPackage;
use App\Http\Controllers\CustomerPackageController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WalletController;

class ZarinpalController extends Controller
{

    public function __construct()
    {
        
    }

    public function getCheckout()
    {        
        $order = Order::findOrFail(Session::get('order_id'));
        $MerchantID 	= env('ZARINPAL_MERCHANT_CODE');
        $Amount 		= $order->grand_total;
        $Description 	= "ØªØ±Ø§Ú©Ù†Ø´ Ø²Ø±ÛŒÙ† Ù¾Ø§Ù„";
        $Email 			= "";
        $Mobile 		= "";
        $CallbackURL 	= env('APP_URL')."zarinpal/back_url";
        $ZarinGate 		= false;
        $SandBox 		= false;

        if(Session::has('payment_type')){
            if(Session::get('payment_type') == 'cart_payment'){
                
                $result = $this->request($MerchantID, $Amount, $Description, $Email, $Mobile, $CallbackURL, $SandBox, $ZarinGate);
                if (isset($result["Status"]) && $result["Status"] == 100)
                {  
                    // Success and redirect to pay
                    $order->payment_details = $result["Authority"];
                    $order->save();
                    $this->redirect($result["StartPay"]);
                } else {
                    // error
                    flash("Ø®Ø·Ø§ Ø¯Ø± Ø§ÛŒØ¬Ø§Ø¯ ØªØ±Ø§Ú©Ù†Ø´")->error();
                }
            }
        }
    }

    public function back_url(Request $request){
        
        $order = Order::where('payment_details', $_GET['Authority'])->first();
        Session::put('order_id', $order->id);
        $MerchantID 	= env('ZARINPAL_MERCHANT_CODE');
        $Amount 		= $order->grand_total;
        $ZarinGate 		= false;
        $SandBox 		= false;
        
        $result = $this->verify($MerchantID, $Amount, $SandBox, $ZarinGate);
        
        if (isset($result["Status"]) && $result["Status"] == 100)
        {
            $payment = json_encode($result);     
            $request->session()->put('cart', collect([]));
            $request->session()->forget('delivery_info');
            $request->session()->forget('coupon_id');
            $request->session()->forget('coupon_discount');
            $checkoutController = new CheckoutController;
            return $checkoutController->checkout_done($order->id, $payment);
        }elseif (isset($result["Status"]) && $result["Status"] != 100){
            $request->session()->forget('order_id');
            $request->session()->forget('payment_data');
            flash(__('Payment cancelled'))->success();
            return redirect()->route('home');
        }else {
            // error
            $request->session()->forget('order_id');
            $request->session()->forget('payment_data');
            flash(__('Payment cancelled'))->success();
            return redirect()->route('home');
        }
    }

    
    /*--------------------------------------------------------------------------------------------------------*/
    private function curl_check()
	{
		return (function_exists('curl_version')) ? true : false;
	}
	
	private function soap_check()
	{
		return (extension_loaded('soap')) ? true : false;
	}
	
	private function error_message($code, $desc, $cb, $request=false)
	{
		if (empty($cb) && $request === true)
		{
			return "Ù„ÛŒÙ†Ú© Ø¨Ø§Ø²Ú¯Ø´Øª ( CallbackURL ) Ù†Ø¨Ø§ÛŒØ¯ Ø®Ø§Ù„ÛŒ Ø¨Ø§Ø´Ø¯";
		}

		if (empty($desc) && $request === true)
		{
			return "ØªÙˆØ¶ÛŒØ­Ø§Øª ØªØ±Ø§Ú©Ù†Ø´ ( Description ) Ù†Ø¨Ø§ÛŒØ¯ Ø®Ø§Ù„ÛŒ Ø¨Ø§Ø´Ø¯";
		}
		
		
		$error = array(
			"-1" 	=> "Ø§Ø·Ù„Ø§Ø¹Ø§Øª Ø§Ø±Ø³Ø§Ù„ Ø´Ø¯Ù‡ Ù†Ø§Ù‚Øµ Ø§Ø³Øª.",
			"-2" 	=> "IP Ùˆ ÙŠØ§ Ù…Ø±Ú†Ù†Øª ÙƒØ¯ Ù¾Ø°ÙŠØ±Ù†Ø¯Ù‡ ØµØ­ÙŠØ­ Ù†ÙŠØ³Øª",
			"-3" 	=> "Ø¨Ø§ ØªÙˆØ¬Ù‡ Ø¨Ù‡ Ù…Ø­Ø¯ÙˆØ¯ÙŠØª Ù‡Ø§ÙŠ Ø´Ø§Ù¾Ø±Ùƒ Ø§Ù…ÙƒØ§Ù† Ù¾Ø±Ø¯Ø§Ø®Øª Ø¨Ø§ Ø±Ù‚Ù… Ø¯Ø±Ø®ÙˆØ§Ø³Øª Ø´Ø¯Ù‡ Ù…ÙŠØ³Ø± Ù†Ù…ÙŠ Ø¨Ø§Ø´Ø¯",
			"-4" 	=> "Ø³Ø·Ø­ ØªØ§ÙŠÙŠØ¯ Ù¾Ø°ÙŠØ±Ù†Ø¯Ù‡ Ù¾Ø§ÙŠÙŠÙ† ØªØ± Ø§Ø² Ø³Ø·Ø­ Ù†Ù‚Ø±Ù‡ Ø§ÙŠ Ø§Ø³Øª.",
			"-11" 	=> "Ø¯Ø±Ø®ÙˆØ§Ø³Øª Ù…ÙˆØ±Ø¯ Ù†Ø¸Ø± ÙŠØ§ÙØª Ù†Ø´Ø¯.",
			"-12" 	=> "Ø§Ù…ÙƒØ§Ù† ÙˆÙŠØ±Ø§ÙŠØ´ Ø¯Ø±Ø®ÙˆØ§Ø³Øª Ù…ÙŠØ³Ø± Ù†Ù…ÙŠ Ø¨Ø§Ø´Ø¯.",
			"-21" 	=> "Ù‡ÙŠÚ† Ù†ÙˆØ¹ Ø¹Ù…Ù„ÙŠØ§Øª Ù…Ø§Ù„ÙŠ Ø¨Ø±Ø§ÙŠ Ø§ÙŠÙ† ØªØ±Ø§ÙƒÙ†Ø´ ÙŠØ§ÙØª Ù†Ø´Ø¯",
			"-22" 	=> "ØªØ±Ø§ÙƒÙ†Ø´ Ù†Ø§ Ù…ÙˆÙÙ‚ Ù…ÙŠØ¨Ø§Ø´Ø¯",
			"-33" 	=> "Ø±Ù‚Ù… ØªØ±Ø§ÙƒÙ†Ø´ Ø¨Ø§ Ø±Ù‚Ù… Ù¾Ø±Ø¯Ø§Ø®Øª Ø´Ø¯Ù‡ Ù…Ø·Ø§Ø¨Ù‚Øª Ù†Ø¯Ø§Ø±Ø¯",
			"-34" 	=> "Ø³Ù‚Ù ØªÙ‚Ø³ÙŠÙ… ØªØ±Ø§ÙƒÙ†Ø´ Ø§Ø² Ù„Ø­Ø§Ø¸ ØªØ¹Ø¯Ø§Ø¯ ÙŠØ§ Ø±Ù‚Ù… Ø¹Ø¨ÙˆØ± Ù†Ù…ÙˆØ¯Ù‡ Ø§Ø³Øª",
			"-40" 	=> "Ø§Ø¬Ø§Ø²Ù‡ Ø¯Ø³ØªØ±Ø³ÙŠ Ø¨Ù‡ Ù…ØªØ¯ Ù…Ø±Ø¨ÙˆØ·Ù‡ ÙˆØ¬ÙˆØ¯ Ù†Ø¯Ø§Ø±Ø¯.",
			"-41" 	=> "Ø§Ø·Ù„Ø§Ø¹Ø§Øª Ø§Ø±Ø³Ø§Ù„ Ø´Ø¯Ù‡ Ù…Ø±Ø¨ÙˆØ· Ø¨Ù‡ AdditionalData ØºÙŠØ±Ù…Ø¹ØªØ¨Ø± Ù…ÙŠØ¨Ø§Ø´Ø¯.",
			"-42" 	=> "Ù…Ø¯Øª Ø²Ù…Ø§Ù† Ù…Ø¹ØªØ¨Ø± Ø·ÙˆÙ„ Ø¹Ù…Ø± Ø´Ù†Ø§Ø³Ù‡ Ù¾Ø±Ø¯Ø§Ø®Øª Ø¨Ø§ÙŠØ¯ Ø¨ÙŠÙ† 30 Ø¯Ù‚ÙŠÙ‡ ØªØ§ 45 Ø±ÙˆØ² Ù…ÙŠ Ø¨Ø§Ø´Ø¯.",
			"-54" 	=> "Ø¯Ø±Ø®ÙˆØ§Ø³Øª Ù…ÙˆØ±Ø¯ Ù†Ø¸Ø± Ø¢Ø±Ø´ÙŠÙˆ Ø´Ø¯Ù‡ Ø§Ø³Øª",
			"100" 	=> "Ø¹Ù…Ù„ÙŠØ§Øª Ø¨Ø§ Ù…ÙˆÙÙ‚ÙŠØª Ø§Ù†Ø¬Ø§Ù… Ú¯Ø±Ø¯ÙŠØ¯Ù‡ Ø§Ø³Øª.",
			"101" 	=> "Ø¹Ù…Ù„ÙŠØ§Øª Ù¾Ø±Ø¯Ø§Ø®Øª Ù…ÙˆÙÙ‚ Ø¨ÙˆØ¯Ù‡ Ùˆ Ù‚Ø¨Ù„Ø§ PaymentVerification ØªØ±Ø§ÙƒÙ†Ø´ Ø§Ù†Ø¬Ø§Ù… Ø´Ø¯Ù‡ Ø§Ø³Øª.",
		);

		if (array_key_exists("{$code}", $error))
		{
			return $error["{$code}"];
		} else {
			return "Ø®Ø·Ø§ÛŒ Ù†Ø§Ù…Ø´Ø®Øµ Ù‡Ù†Ú¯Ø§Ù… Ø§ØªØµØ§Ù„ Ø¨Ù‡ Ø¯Ø±Ú¯Ø§Ù‡ Ø²Ø±ÛŒÙ† Ù¾Ø§Ù„";
		}
	}

	private function zarinpal_node()
	{
		if ($this->curl_check() === true)
		{
			$ir_ch = curl_init("https://www.zarinpal.com/pg/services/WebGate/wsdl");
			curl_setopt($ir_ch, CURLOPT_TIMEOUT, 1);
			curl_setopt($ir_ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ir_ch, CURLOPT_RETURNTRANSFER, true);
			curl_exec($ir_ch);
			$ir_info = curl_getinfo($ir_ch);
			curl_close($ir_ch);

			$de_ch = curl_init("https://de.zarinpal.com/pg/services/WebGate/wsdl");
			curl_setopt($de_ch, CURLOPT_TIMEOUT, 1);
			curl_setopt($de_ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($de_ch, CURLOPT_RETURNTRANSFER, true);
			curl_exec($de_ch);
			$de_info = curl_getinfo($de_ch);
			curl_close($de_ch);

			$ir_total_time = (isset($ir_info['total_time']) && $ir_info['total_time'] > 0) ? $ir_info['total_time'] : false;
			$de_total_time = (isset($de_info['total_time']) && $de_info['total_time'] > 0) ? $de_info['total_time'] : false;

			return ($ir_total_time === false || $ir_total_time > $de_total_time) ? "de" : "ir";
		} else {
			if (function_exists('fsockopen'))
			{
				$de_ping 	= $this->zarinpal_ping("de.zarinpal.com", 80, 1);
				$ir_ping 	= $this->zarinpal_ping("www.zarinpal.com", 80, 1);

				$de_domain 	= "https://de.zarinpal.com/pg/services/WebGate/wsdl";
				$ir_domain 	= "https://www.zarinpal.com/pg/services/WebGate/wsdl";

				$ir_total_time = (isset($ir_ping) && $ir_ping > 0) ? $ir_ping : false;
				$de_total_time = (isset($de_ping) && $de_ping > 0) ? $de_ping : false;

				return ($ir_total_time === false || $ir_total_time > $de_total_time) ? "de" : "ir";
			} else {
				$webservice = "https://www.zarinpal.com/pg/services/WebGate/wsd";
				$headers 	= @get_headers($webservice);

				return (strpos($headers[0], '200') === false) ? "de" : "ir";
			}
		}
	}
	
	private function zarinpal_ping($host, $port, $timeout)
	{
		$time_b 	= microtime(true);
		$fsockopen 	= @fsockopen($host, $port, $errno, $errstr, $timeout);

		if (!$fsockopen)
		{
			return false;
		}  else {
			$time_a = microtime(true); 
			return round((($time_a - $time_b) * 1000), 0); 
		}
	}

	public function redirect($url)
	{
		@header('Location: '. $url);
		echo "<meta http-equiv='refresh' content='0; url={$url}' />";
		echo "<script>window.location.href = '{$url}';</script>";
		exit;
	}

	public function request($MerchantID, $Amount, $Description="", $Email="", $Mobile="", $CallbackURL, $SandBox=false, $ZarinGate=false)
	{
		$ZarinGate = ($SandBox == true) ? false : $ZarinGate;

		if ($this->soap_check() === true)
		{
			$node 	= ($SandBox == true) ? "sandbox" : $this->zarinpal_node();
			$upay 	= ($SandBox == true) ? "sandbox" : "www";

			$client = new \SoapClient("https://{$node}.zarinpal.com/pg/services/WebGate/wsdl", ['encoding' => 'UTF-8']);

			$result = $client->PaymentRequest([
				'MerchantID'     => $MerchantID,
				'Amount'         => $Amount,
				'Description'    => $Description,
				'Email'          => $Email,
				'Mobile'         => $Mobile,
				'CallbackURL'    => $CallbackURL,
			]);

			$Status 		= (isset($result->Status) 		&& $result->Status != "") 		? $result->Status : 0;
			$Authority 		= (isset($result->Authority) 	&& $result->Authority != "") 	? $result->Authority : "";
			$StartPay 		= (isset($result->Authority) 	&& $result->Authority != "") 	? "https://{$upay}.zarinpal.com/pg/StartPay/". $Authority : "";
			$StartPayUrl 	= (isset($ZarinGate) 			&& $ZarinGate == true) 			? "{$StartPay}/ZarinGate" : $StartPay;

			return array(
				"Node" 		=> "{$node}",
				"Method" 	=> "SOAP",
				"Status" 	=> $Status,
				"Message" 	=> $this->error_message($Status, $Description, $CallbackURL, true),
				"StartPay" 	=> $StartPayUrl,
				"Authority" => $Authority
			);
		} else {
			$node 	= ($SandBox == true) ? "sandbox" : "ir";
			$upay 	= ($SandBox == true) ? "sandbox" : "www";

			$data = array(
				'MerchantID'     => $MerchantID,
				'Amount'         => $Amount,
				'Description'    => $Description,
				'CallbackURL'    => $CallbackURL,
			);

			$jsonData = json_encode($data);
			$ch = curl_init("https://{$upay}.zarinpal.com/pg/rest/WebGate/PaymentRequest.json");
			curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($jsonData)));

			$result = curl_exec($ch);
			$err 	= curl_error($ch);
			curl_close($ch);

			$result = json_decode($result, true);

			if ($err)
			{
				$Status 		= 0;
				$Message 		= "cURL Error #:" . $err;
				$Authority 		= "";
				$StartPay 		= "";
				$StartPayUrl 	= "";
			} else {
				$Status 		= (isset($result["Status"]) 	&& $result["Status"] != "") 	? $result["Status"] : 0;
				$Message 		= $this->error_message($Status, $Description, $CallbackURL, true);
				$Authority 		= (isset($result["Authority"]) 	&& $result["Authority"] != "") 	? $result["Authority"] : "";
				$StartPay 		= (isset($result["Authority"]) 	&& $result["Authority"] != "") 	? "https://{$upay}.zarinpal.com/pg/StartPay/". $Authority : "";
				$StartPayUrl 	= (isset($ZarinGate) 			&& $ZarinGate == true) 			? "{$StartPay}/ZarinGate" : $StartPay;
			}

			return array(
				"Node" 		=> "{$node}",
				"Method" 	=> "CURL",
				"Status" 	=> $Status,
				"Message" 	=> $Message,
				"StartPay" 	=> $StartPayUrl,
				"Authority" => $Authority
			);
		}
	}

	public function verify($MerchantID, $Amount, $SandBox=false, $ZarinGate=false)
	{
		$ZarinGate = ($SandBox == true) ? false : $ZarinGate;

		if ($this->soap_check() === true)
		{
			$au 	= (isset($_GET['Authority']) && $_GET['Authority'] != "") ? $_GET['Authority'] : "";
			$node 	= ($SandBox == true) ? "sandbox" : $this->zarinpal_node();

			$client = new \SoapClient("https://{$node}.zarinpal.com/pg/services/WebGate/wsdl", ['encoding' => 'UTF-8']);

			$result = $client->PaymentVerification([
				'MerchantID'     => $MerchantID,
				'Authority'      => $au,
				'Amount'         => $Amount,
			]);

			$Status 		= (isset($result->Status) 		&& $result->Status != "") 		? $result->Status 	: 0;
			$RefID 			= (isset($result->RefID) 		&& $result->RefID != "") 		? $result->RefID 	: "";
			$Message 		= $this->error_message($Status, "", "", false);

			return array(
				"Node" 		=> "{$node}",
				"Method" 	=> "SOAP",
				"Status" 	=> $Status,
				"Message" 	=> $Message,
				"Amount" 	=> $Amount,
				"RefID" 	=> $RefID,
				"Authority" => $au
			);	
		} else {
			$au 	= (isset($_GET['Authority']) && $_GET['Authority'] != "") ? $_GET['Authority'] : "";
			$node 	= ($SandBox == true) ? "sandbox" : "ir";
			$upay 	= ($SandBox == true) ? "sandbox" : "www";
			
			$data = array('MerchantID' => $MerchantID, 'Authority' => $au, 'Amount' => $Amount);
			$jsonData = json_encode($data);
			$ch = curl_init("https://{$upay}.zarinpal.com/pg/rest/WebGate/PaymentVerification.json");
			curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($jsonData)));

			$result = curl_exec($ch);
			$err 	= curl_error($ch);
			curl_close($ch);

			$result = json_decode($result, true);

			if ($err)
			{
				$Status 		= 0;
				$Message 		= "cURL Error #:" . $err;
				$Status 		= "";
				$RefID 			= "";
			} else {
				$Status 		= (isset($result["Status"]) && $result["Status"] != "") ? $result["Status"] : 0;
				$RefID 			= (isset($result['RefID']) 	&& $result['RefID'] != "") 	? $result['RefID'] 	: "";
				$Message 		= $this->error_message($Status, "", "", false);
			}

			return array(
				"Node" 		=> "{$node}",
				"Method" 	=> "CURL",
				"Status" 	=> $Status,
				"Message" 	=> $Message,
				"Amount" 	=> $Amount,
				"RefID" 	=> $RefID,
				"Authority" => $au
			);	
		}
	}
}


