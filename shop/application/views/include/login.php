

<div class="modal fade" id="ModalLogin" tabindex="-1" role="dialog" >
  <div class="modal-dialog" >
    <div class="modal-content">
      <div class="modal-header" style="background-color:#585858">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
        <h3 class="modal-title-site text-center"> Login to SHOP </h3>
      </div>
      <div class="modal-body">

        <label for="username" style="float:left;">Username : </label>
        <input class="form-control" id="username" placeholder="Username" required>

        <label for="password" style="float:left;">Password : </label>
        <input class="form-control" id="password" type="password" placeholder="Password" required>

      </div>
      <div class="modal-footer">
       
          <button style="margin-bottom:5px" type="submit" class="btn btn-primary btn-md btn-block" onclick="login()"><i class="fa fa-circle-o-notch" aria-hidden="true"></i> Login</button>
          <a href="<?php echo base_url(); ?>shop/rePassword"><button style="margin-bottom:5px" type="button" class="btn btn-warning btn-md btn-block"><i class="fa fa-info" aria-hidden="true"></i> Reset Password</button></a>
          <a href="<?php echo base_url(); ?>shop/Register" ><button style="margin-bottom:5px" type="button" class="btn btn-success btn-md btn-block">  <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Register</button></a>
          <button style="margin-bottom:5px" type="submit" class="btn btn-primary btn-md btn-block visible-xs" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
       
      </div>
    </div><!-- /.modal-content -->
  </div> <!-- /.modal-dialog -->
</div><!-- /.Modal Login -->
<script>
function login()
  {

    var user = $("#username").val();
    var pass = $("#password").val();

    load_in();

    $.ajax({
      url:"<?php echo base_url(); ?>shop/member/validation",
      type:"POST", 
      cache: "false", 
      data:{ 
        "username" : user, 
        "password" : pass
      },
      success: function(rs){
       load_out();
       // $('#ModalLogin').modal('toggle');
       // login_status(rs);	

       console.log(rs);

     },error:function(e) {

       console.log("error");
       load_out();

     }
   });
  }

  function login_status(msg)
  {	

    if(msg == "success"){
      swal({
        title: "SUCCESS !",
        text: "เข้าสู่ระบบสำเร็จ",
        type: "success",
        timer:2000,
      });
      setTimeout(function(){
        window.location.href = "<?php echo site_url('shop/'); ?>";
      },2100);

    }else if(msg == "online"){
      swal({
        title: "WARNING !",
        text: "บัญชีมีการเข้าสู่ระบบอยู่",
        type: "warning",
        timer:2500,
      });
    }else if(msg == "fail"){
      swal({
        title: "Error!",
        text: "ยังไม่ได้เป็นสมาชิก",
        type: "error",
        timer:2500,
      });
    }

  }

</script>