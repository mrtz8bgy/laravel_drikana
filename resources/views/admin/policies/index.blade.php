@extends('layouts.app')

@section('content')

@php
    $policyName = optional($policy)->name;
    $policyContent = optional($policy)->content;
@endphp

<div class="col-sm-12">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">
                @if($policyName == 'seller_policy')
                    سیاست فروشنده   
                @elseif($policyName == 'return_policy')     
                    سیاست بازگشت محصولات
                @elseif($policyName == 'support_policy')    
                    سیاست پشتیبانی
                @elseif($policyName == 'terms')        
                    شرایط و ضوابط
                @elseif($policyName == 'privacy_policy')
                    سیاست حریم خصوصی            
                @endif
                
            </h3>
        </div>
        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('policies.store') }}" method="POST" enctype="multipart/form-data">
        	@csrf
            <div class="panel-body">
                <div class="form-group">
                    <input type="hidden" name="name" value="{{ $policyName }}">
                    <label class="col-sm-2 control-label" for="name">{{__('Content')}}</label>
                    <div class="col-sm-10">
                        <textarea class="editor" name="content" placeholder="" required>{{ $policyContent }}</textarea>
                    </div>
                </div>
            </div>
            <div class="panel-footer text-right">
                <button class="btn btn-purple" type="submit">{{__('Save')}}</button>
            </div>
        </form>

        <!--===================================================-->
        <!--End Horizontal Form-->

    </div>
</div>

@endsection
