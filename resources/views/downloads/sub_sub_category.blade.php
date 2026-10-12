
{{-- کلاس‌های استخراج‌شده از استایل درون‌خطی (بازبینی L-04) --}}
{{-- این ویو با dompdf به PDF تبدیل می‌شود و dompdf با --}}
{{-- isRemoteEnabled=false استایل خارجی را بارگذاری نمی‌کند؛ --}}
{{-- پس کلاس‌ها به‌جای فایل سراسری، اینجا تعریف شده‌اند. --}}
<style>
.u-background-eceff4 { background: #eceff4 !important; }
.u-background-eceff4-padding-1-5rem { background: #eceff4 !important; padding: 1.5rem !important; }
.u-border-bottom-1px-solid-eceff4-margin-0-1-5rem { border-bottom:1px solid #eceff4 !important; margin: 0 1.5rem !important; }
.u-display-inline-block { display:inline-block !important; }
.u-margin-left-auto-margin-right-auto { margin-left:auto !important; margin-right:auto !important; }
.u-padding-1-5rem { padding: 1.5rem !important; }
</style>
<div class="u-margin-left-auto-margin-right-auto">
<style media="all">
	/* فونت سیستمی جایگزین Dejavu Sans - بدون نیاز به اینترنت */
	*{
		margin: 0;
		padding: 0;
		line-height: 1.5;
		font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
		color: #333542;
	}
	div{
		font-size: 1rem;
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
	.strong{
		font-weight: bold;
	}
</style>

	@php
		$generalsetting = \App\GeneralSetting::first();
	@endphp

	<div class="u-background-eceff4-padding-1-5rem">
		<table>
			<tr>
				<td>
					@if($generalsetting->logo != null)
						{{-- لوگو از مسیر محلی خوانده می‌شود، نه از آدرس HTTP: dompdf برای
     آدرس‌های http درخواست شبکه‌ای می‌زند که در سرور تک‌رشته‌ای/بدون
     اینترنت باعث توقف طولانی (۶۰ ثانیه تایم‌اوت) و مصرف بی‌جای منابع
     می‌شد. اگر فایل محلی نبود، همان آدرس وب درج می‌شود. --}}
                                                @php
                                                    $logoDisk = public_path($generalsetting->logo);
                                                @endphp
                                                <img class="u-display-inline-block" src="{{ file_exists($logoDisk) ? $logoDisk : asset($generalsetting->logo) }}" height="40">
					@else
						@php
                                                    $fallbackLogo = public_path('frontend/images/logo/logo.png');
                                                @endphp
                                                <img class="u-display-inline-block" src="{{ file_exists($fallbackLogo) ? $fallbackLogo : asset('frontend/images/logo/logo.png') }}" height="40">
					@endif
				</td>
			</tr>
		</table>
	</div>

	<div class="u-border-bottom-1px-solid-eceff4-margin-0-1-5rem"></div>

    <div class="u-padding-1-5rem">
		<table class="padding text-left small border-bottom">
			<thead>
                <tr class="gry-color u-background-eceff4">
                    <th width="50%">Sub-Sub-Category Name</th>
                    <th width="30%">Sub-Sub-Category ID</th>
					<th width="20%">Sub-Category ID</th>
                </tr>
			</thead>
			<tbody class="strong">
                @foreach ($sub_sub_categories as $key => $sub_sub_category)
	                <tr class="">
						<td>{{ $sub_sub_category->name }}</td>
						<td>{{ $sub_sub_category->id }}</td>
						<td>{{ $sub_sub_category->sub_category_id }}</td>
					</tr>
				@endforeach
            </tbody>
		</table>
	</div>

</div>