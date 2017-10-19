
<CENTER>
  <span>
    <img src="<?= base_url()."img/slide/line_thai.png"?>">
  </span>
</CENTER>
<div class="container main-container head-offset">
  <!-- Main component call to action -->
  <div id="imgShowcase" class="owl-carousel owl-theme globalPaddingBottom" >
    <div><img src="<?= base_url()."img/slide/thailand.jpg"?>"></div>
    <div><img src="<?= base_url()."img/slide/rama10.jpg"?>"></div>
    <div><img src="<?= base_url()."img/slide/rama9.png"?>"></div>
  </div>
  <!--/.featuredPostContainer-->
</div>    


<style>
#imgShowcase .owl-item div{
  padding:5px;
}
#imgShowcase .owl-item img{
  display: block;
  width: 100%;
  max-height:450px;
  max-width:1170px
  height: auto;
  -webkit-border-radius: 3px;
  -moz-border-radius: 3px;
  border-radius: 3px;
}
</style>
<script>
  $(document).ready(function() {
    $("#imgShowcase").owlCarousel({
      autoPlay : 3000,
      autoHeight:true,
      stopOnHover : true,
        navigation : true, // Show next and prev buttons
        slideSpeed : 300,
        navigation : false,
        singleItem:true,
        responsive: true
      });


  });
</script>