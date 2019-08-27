
<div class="container">
  <div class="row top-row">
  	<div class="col-sm-6 top-col">
      	<h4 class="title"><i class="fa fa-exclamation-triangle"></i>&nbsp;<?php echo $pageTitle; ?></h4>
      </div>
      <div class="col-sm-6">
        <p class="pull-right top-p">
          <button type="button" class="btn btn-sm btn-success" onclick="goToStock()">Stock</button>
          <button type="button" class="btn btn-sm btn-info" onclick="goToMovement()">Movement</button>
          <button type="button" class="btn btn-sm btn-warning" onclick="goToBuffer()">Buffer</button>
          <button type="button" class="btn btn-sm btn-danger" onclick="goToCancle()">Cancle</button>
        </p>
      </div>
  </div><!-- / row -->

  <hr style="margin-bottom:15px;" />

<?php
  include 'function/discount_helper.php';

  $disc = '0';
  $price = 100;

  $discountText = $disc;//discountPercentToLabel($disc);
  echo $discountText.'<br/>';
  $discount = parseDiscount($discountText, $price);
  echo '<pre>';
  print_r($discount);
  echo '</pre>';

?>

</div><!-- container -->
<script type="text/javascript">
  $('.search-box').keyup(function(e){
    if(e.keyCode == 13){
      getSearch();
    }
  });

  function getSearch(){
    $('#stockForm').submit();
  }

  function clearFilter(){
    $.get('controller/testController.php?clearFilter', function(){ window.location.reload(); });
  }
</script>
<script>

function goToStock(){
  window.location.href = "index.php?content=test_run&stock";
}

function goToMovement(){
  window.location.href = "index.php?content=test_run&movement";
}

function goToBuffer(){
  window.location.href = "index.php?content=test_run&buffer";
}

function goToCancle(){
  window.location.href = "index.php?content=test_run&cancle";
}
</script>
