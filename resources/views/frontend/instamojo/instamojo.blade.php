<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Instamojo Payment Gateway Integrate</title>
    
    {{-- استفاده از فایل بوت‌استرپ محلی به جای CDN --}}
    <link href="{{ asset('frontend/css/bootstrap.min.css') }}" rel="stylesheet">
    
    <style>
        .mt40{
            margin-top: 40px;
        }
        /* استایل‌های کمکی برای فرم */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        .card-header {
            background: #0275D8;
            padding: 15px 20px;
            border-radius: 4px 4px 0 0;
        }
        .card-header h2 {
            color: white;
            margin: 0;
            font-size: 1.5rem;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .alert-danger {
            color: #a94442;
            background-color: #f2dede;
            border-color: #ebccd1;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group strong {
            display: block;
            margin-bottom: 5px;
        }
        .form-control {
            display: block;
            width: 100%;
            padding: 6px 12px;
            font-size: 14px;
            line-height: 1.42857143;
            color: #555;
            background-color: #fff;
            background-image: none;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .btn {
            display: inline-block;
            padding: 6px 12px;
            margin-bottom: 0;
            font-size: 14px;
            font-weight: normal;
            line-height: 1.42857143;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            cursor: pointer;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .btn-primary {
            color: #fff;
            background-color: #0275D8;
            border-color: #0275D8;
        }
        .btn-primary:hover {
            background-color: #025aa5;
            border-color: #01549b;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }
        .col-md-12 {
            flex: 0 0 100%;
            max-width: 100%;
            padding-right: 15px;
            padding-left: 15px;
        }
        .mt40 {
            margin-top: 40px;
        }
        .mb-3 {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="container">

<div class="row">
    <div class="col-lg-12 mt40">
        <div class="card-header">
            <h2>Confirm your Information For Security Reason</h2>
        </div>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Opps!</strong> Something went wrong<br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ url('pay') }}" method="POST" name="laravel_instamojo">
    {{ csrf_field() }}

     <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <strong>Name</strong>
                <input type="text" name="name" class="form-control" placeholder="Enter Name" value="{{$order->user->name}}" required>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <strong>Mobile Number</strong>
                <input type="text" name="mobile_number" class="form-control" placeholder="Enter Mobile Number" value="{{$order->user->phone}}" required>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <strong>Email Id</strong>
                <input type="text" name="email" class="form-control" placeholder="Enter Email id" value="{{$order->user->email}}" required>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <strong>Amount</strong>
                <input type="text" name="amount" class="form-control" placeholder="" value="{{round($order->grand_total)}}" readonly="">
            </div>
        </div>
        <div class="col-md-12">
                <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>

</form>
</div>

</body>
</html>