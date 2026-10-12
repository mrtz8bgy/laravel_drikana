<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>فاکتور</title>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<?php header("Content-type:text/css;charset:UTF-8"); ?>
	<style>
		body{
			font-family: 'fa','en' !important;
			font-size: .875rem;
			direction: rtl;
		}
		.gry-color *,
		.gry-color{
			color:#878f9c;
		}
		table{
			width: 100%;
		}
		table th{
			font-weight: normal;
		}
		table.padding th{
			padding: .5rem .7rem;
		}
		table.padding td{
			padding: .7rem;
		}
		table.sm-padding td{
			padding: .2rem .7rem;
		}
		.border-bottom td,
		.border-bottom th{
			border-bottom:1px solid #eceff4;
		}
		.text-left{
			text-align:left;
		}
		.text-right{
			text-align:right;
		}
		.small{
			font-size: .85rem;
		}
		.currency{

		}
	</style>

{{-- کلاس‌های استخراج‌شده از استایل درون‌خطی (بازبینی L-04) --}}
{{-- این ویو با dompdf به PDF تبدیل می‌شود و dompdf با --}}
{{-- isRemoteEnabled=false استایل خارجی را بارگذاری نمی‌کند؛ --}}
{{-- پس کلاس‌ها به‌جای فایل سراسری، اینجا تعریف شده‌اند. --}}
<style>
.u-background-eceff4 { background: #eceff4 !important; }
.u-background-eceff4-padding-1-5rem { background: #eceff4 !important; padding: 1.5rem !important; }
.u-direction-rtl { direction: rtl !important; }
.u-display-inline-block { display:inline-block !important; }
.u-font-size-1-2rem { font-size: 1.2rem !important; }
.u-font-size-2-5rem { font-size: 2.5rem !important; }
.u-padding-0-1-5rem { padding:0 1.5rem !important; }
.u-padding-1-5rem { padding: 1.5rem !important; }
.u-padding-1-5rem-padding-bottom-0 { padding: 1.5rem !important; padding-bottom: 0 !important; }
.u-width-40-margin-right-auto { width: 40% !important; margin-right:auto !important; }
</style>
</head>
<body>
	<div>

		@php
			$generalsetting = \App\GeneralSetting::first();
		@endphp

		<div class="u-background-eceff4-padding-1-5rem">
			<table>
				<tr>
					<td>
						@if($generalsetting->logo != null)
							<img class="u-display-inline-block" loading="lazy" src="{{ asset($generalsetting->logo) }}" height="40">
						@else
							<img class="u-display-inline-block" loading="lazy" src="{{ asset('frontend/images/logo/logo.png') }}" height="40">
						@endif
					</td>
					<td class="text-right strong u-font-size-2-5rem">فاکتور خرید</td>
				</tr>
			</table>
			<table>
				<tr>
					<td class="strong u-font-size-1-2rem">{{ $generalsetting->site_name }}</td>
					<td class="text-right"></td>
				</tr>
				<tr>
					<td class="gry-color small">{{ $generalsetting->address }}</td>
					<td class="text-right"></td>
				</tr>
				<tr>
					<td class="gry-color small">ایمیل: {{ $generalsetting->email }}</td>
					<td class="text-right small"><span class="gry-color small">کد سفارش:</span> <span class="strong">{{ $order->code }}</span></td>
				</tr>
				<tr>
					<td class="gry-color small">تلفن: {{ $generalsetting->phone }}</td>
					<td class="text-right small"><span class="gry-color small">تاریخ سفارش:</span> <span class=" strong">{{ $order->date }}</span></td>
				</tr>
			</table>

		</div>

		<div class="u-padding-1-5rem-padding-bottom-0">
			<table>
				@php
					$shipping_address = json_decode($order->shipping_address);
				@endphp
				<tr><td class="strong small gry-color">ارسال به:</td></tr>
				<tr><td class="strong">{{ $shipping_address->name }}</td></tr>
				<tr><td class="gry-color small">{{ $shipping_address->city }}, {{ $shipping_address->address }}</td></tr>
				<tr><td class="gry-color small">ایمیل: {{ $shipping_address->email }}</td></tr>
				<tr><td class="gry-color small">تلفن: {{ $shipping_address->phone }}</td></tr>
			</table>
		</div>

	    <div class="u-padding-1-5rem">
			<table class="padding text-left small border-bottom u-direction-rtl">
				<thead>
	                <tr class="gry-color u-background-eceff4">
	                    <th width="35%">نام محصول</th>
						<th width="15%">نوع تحویل</th>
	                    <th width="10%">تعداد</th>
	                    <th width="15%">قیمت واحد</th>
	                    <th width="10%">مالیات</th>
	                    <th width="15%" class="text-right">مجموع</th>
	                </tr>
				</thead>
				<tbody class="strong">
	                @foreach ($order->orderDetails as $key => $orderDetail)
		                @if ($orderDetail->product != null)
							<tr class="">
								<td class="text-right">{{ $orderDetail->product->name }} ({{ $orderDetail->variation }})</td>
								<td>
									@if ($orderDetail->shipping_type != null && $orderDetail->shipping_type == 'home_delivery')
										{{ __('Home Delivery') }}
									@elseif ($orderDetail->shipping_type == 'pickup_point')
										@if ($orderDetail->pickup_point != null)
											{{ $orderDetail->pickup_point->name }} ({{ __('Pickip Point') }})
										@endif
									@endif
								</td>
								<td class="gry-color">{{ $orderDetail->quantity }}</td>
								<td class="gry-color currency">{{ single_price($orderDetail->price/$orderDetail->quantity) }}</td>
								<td class="gry-color currency">{{ single_price($orderDetail->tax/$orderDetail->quantity) }}</td>
			                    <td class="text-right currency">{{ single_price($orderDetail->price+$orderDetail->tax) }}</td>
							</tr>
		                @endif
					@endforeach
	            </tbody>
			</table>
		</div>

	    <div class="u-padding-0-1-5rem">
	        <table class="text-right sm-padding small strong u-width-40-margin-right-auto">
		        <tbody>
			        <tr>
			            <th class="gry-color text-left">مجموع</th>
			            <td class="currency">{{ single_price($order->orderDetails->sum('price')) }}</td>
			        </tr>
			        <tr>
			            <th class="gry-color text-left">هزینه ارسال</th>
			            <td class="currency">{{ single_price($order->orderDetails->sum('shipping_cost')) }}</td>
			        </tr>
			        <tr class="border-bottom">
			            <th class="gry-color text-left">مالیات</th>
			            <td class="currency">{{ single_price($order->orderDetails->sum('tax')) }}</td>
			        </tr>
			        <tr>
			            <th class="text-left strong">مجموع کلی</th>
			            <td class="currency">{{ single_price($order->grand_total) }}</td>
			        </tr>
		        </tbody>
		    </table>
	    </div>

	</div>
</body>
</html>