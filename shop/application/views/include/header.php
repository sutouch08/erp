<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="<?php echo base_url(); ?>shop/assets/ico/warrix.ico">
  <title><?php if( isset( $title )){ echo $title; }else{ echo 'Welcome'; } ?></title>
  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>library/css/jquery-ui-1.10.4.custom.min.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>library/css/sweet-alert.css">
  <link href="<?php echo base_url(); ?>shop/assets/bootstrap/css/bootstrap.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="<?php echo base_url(); ?>shop/assets/css/style.css" rel="stylesheet">
  <script src="<?php echo base_url(); ?>library/js/jquery.min.js"></script>
  <script src="<?php echo base_url(); ?>library/js/sweet-alert.js"></script>
  <!-- <script src="<?php echo ROOT_PATH; ?>library/js/jquery.cookie.js"></script> -->
  <!-- <script src="<?php echo base_url(); ?>library/js/handlebars-v3.js"></script>  -->
  <script src="<?php echo base_url(); ?>library/js/jquery-ui-1.10.4.custom.min.js"></script>
  
  
  <style>
   .ui-autocomplete { 	height: 400px; overflow-y: scroll; overflow-x: hidden; }
  </style>
 <meta property="og:url"	content="<?php echo current_url(); ?>" />
 <meta property="og:type" content="website" />
 <meta property="og:title" content="<?php echo @$title; ?>" />
 <meta property="og:description" content="" />
 <meta property="og:image" content="<?php echo base_url(); ?>img/company/logo.png" />
    <script>
      paceOptions = {
        elements: true
      };
    </script>

    <script src="<?php echo base_url(); ?>shop/assets/js/pace.min.js"></script>
  </head>

  <body>

      <?php $this->load->view("include/menu.php"); ?>
      
    
    <div class="gap"></div>
    <div class="gap hide visible-xs"></div>

<script src="<?php echo base_url(); ?>shop/assets/js/bootstrap-datepicker.min.js"></script>

<style>
  .navbar-brand:hover{
    opacity: 0.5;
    transition: 0.3s ease;
    cursor: pointer;
  } 
</style>

