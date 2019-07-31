<div class="container">
  <div class="row top-row">
    <div class="col-sm-6 top-col">
      <h4 class="title"><i class="fa fa-bar-chart"></i> <?php echo $pageTitle; ?></h4>
    </div>
    <div class="col-sm-6">
      <p class="pull-right top-p">
        <button type="button" class="btn btn-sm btn-info" onclick="doExport()"><i class="fa fa-file-excel-o"></i>  ส่งออก</button>
      </p>
    </div>
  </div>
  <hr />
  <div class="row">
    <div class="col-sm-12" id="result">

    </div>
  </div>

</div><!-- container -->

<script>

function doExport(){
  var token = new Date().getTime();
  var target = 'controller/stockReportController.php?transformBacklogs&export&token='+token;

  get_download(token);
  window.location.href = target;
}

</script>
