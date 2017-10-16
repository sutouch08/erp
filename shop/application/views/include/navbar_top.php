

<div class="navbar-top">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 col-sm-6 col-xs-6 col-md-6">
        <div class="pull-left ">
          <ul class="userMenu ">
            <li><a href="#"> <span class="hidden-xs">CALL CENTER</span>
            <!-- <i class="glyphicon glyphicon-info-sign hide visible-xs "></i>  --></a></li>
              <li class="phone-number hidden-xs"><a href="#"> <span> <i class="fa fa-phone-square"></i></span> <span style="margin-left:5px"> 034-156-515 </span>
              </a>
            </li>
          </ul>
        </div>
      </div><!-- col-lg-6 col-sm-6 col-xs-6 col-md-6 -->

      <div class="col-lg-6 col-sm-6 col-xs-6 col-md-6 no-margin no-padding">
        <div class="pull-right">
          <ul class="userMenu">
            
            <?php if(empty($this->session->userdata('id_customer'))) : ?>
          
             <li>
               <a href="#" data-toggle="modal" data-target="#ModalLogin"> <span class="hidden-xs">Sign In </span>
                 <i class="fa fa-sign-in visible-xs"></i> 
               </a>
             </li>
             <li class="hidden-xs">
               <a href="<?php echo base_url(); ?>shop/Register"> Create Account </a>
               <i class="fa fa-sign-in visible-xs"></i> 
             </li>                                
           <?php else : ?>
            <li>
              <a href="<?php echo base_url(); ?>shop/Member">
                <span class="hidden-xs"> My Account</span> 
                <i class="glyphicon glyphicon-user hide visible-xs "></i>
              </a>
            </li>
            <li class="dropdown hasUserMenu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> 
                <i class="glyphicon glyphicon-log-in hide visible-xs"></i>
                <span class="hidden-xs">Hi,<?php print_r($this->session->userdata("first_name")); ?> </span>
              </a>
            </li>
            <li>
              <a href="<?php echo base_url(); ?>shop/member/logout"><i class="fa fa-power-off"></i> 
                LOGOUT</a>
              </li>
            <?php endif; ?>    
          </ul>
        </div>
      </div><!-- col-lg-6 col-sm-6 col-xs-6 col-md-6 no-margin no-padding -->
      </div> <!-- row -->
  </div><!-- container -->
</div><!--/.navbar-top-->

<?php $this->load->view("include/login.php"); ?>

