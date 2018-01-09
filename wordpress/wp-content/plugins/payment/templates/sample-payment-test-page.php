<form class="form-horizontal" method="POST" action="">
<!--input type="hidden" name="action" value="ibhpp_call_sample_action"-->
<fieldset>
<!-- Form Name -->
<legend>Sample Payment test page</legend>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="orderId">Order Id</label>  
  <div class="col-md-4">
  <input id="orderId" name="orderId" placeholder="121324" class="form-control input-md" required="" type="text">
  <span class="help-block">Sample Order Id for testing</span>  
  </div>
</div>

<!-- Prepended text-->
<div class="form-group">
  <label class="col-md-4 control-label" for="prependedtext">Amount($)</label>
  <div class="col-md-4">
    <div class="input-group">
      <input id="prependedtext" name="totalAmount" class="form-control" placeholder="10.20" required="" type="text">
    </div>
    <p class="help-block">Enter total Amount</p>
  </div>
</div>

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="submit"></label>
  <div class="col-md-4">
      <button id="submit" name="submit" class="btn btn-success" value="Submit">Submit</button>
  </div>
</div>

</fieldset>
</form>
