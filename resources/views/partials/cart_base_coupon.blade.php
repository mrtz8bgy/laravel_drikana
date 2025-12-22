<div class="panel-heading">
    <h3 class="panel-title">{{__('کد تخفیف بر روی مجموع خرید')}}</h3>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label" for="coupon_code">{{__('کد تخفیف')}}</label>
    <div class="col-lg-9">
        <input type="text" id="coupon_code" name="coupon_code" class="form-control" required>
    </div>
</div>
<div class="form-group">
   <label class="col-lg-3 control-label">{{__('حداقل خرید')}}</label>
   <div class="col-lg-9">
      <input type="number" min="0" name="min_buy" class="form-control" required>
   </div>
</div>
<div class="form-group">
   <label class="col-lg-3 control-label">{{__('تخفیف')}}</label>
   <div class="col-lg-8">
      <input type="number" min="0" name="discount" class="form-control" required>
   </div>
   <div class="col-lg-1">
      <select class="demo-select2" name="discount_type">
         <option value="amount">مبلغ</option>
         <option value="percent">درصد</option>
      </select>
   </div>
</div>
<div class="form-group">
   <label class="col-lg-3 control-label">{{__('حداکثر مبلغ تخفیف')}}</label>
   <div class="col-lg-9">
      <input type="number" min="0" name="max_discount" class="form-control" required>
   </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label" for="start_date">{{__('Date')}}</label>
    <div class="col-lg-9">
        <div id="demo-dp-range">
            <div class="input-daterange input-group" id="datepicker">
                <input type="text" class="form-control" name="start_date">
                <span class="input-group-addon">{{__('to')}}</span>
                <input type="text" class="form-control" name="end_date">
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function(){
        $('.demo-select2').select2();
    });

</script>
