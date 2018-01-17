<div class="row margin-top-10">
  <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 margin-top-15 margin-bottom-15">
    <button type="button" class="btn btn-lg btn-success btn-block" onclick="goOrder()">
      ออเดอร์
    </button>
  </div>

  <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 margin-top-15 margin-bottom-15">
    <button type="button" class="btn btn-lg btn-info btn-block" onclick="goStock()">
      เช็คสต็อก
    </button>
  </div>

  <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 margin-top-15 margin-bottom-15">
    <button type="button" class="btn btn-lg btn-primary btn-block" onclick="goOrder()">
      รายงาน
    </button>
  </div>

  <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 margin-top-15 margin-bottom-15">
    <button type="button" class="btn btn-lg btn-warning btn-block" onclick="goOrder()">
      catalog
    </button>
  </div>
</div>

<script>
function goOrder(){
  window.location.href = 'index.php?content=order';
}

function goStock(){
  window.location.href = 'index.php?content=check_stock';
}
</script>
