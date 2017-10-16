  <!--- this part will be hidden for mobile version -->
<div class="navbar-right hidden-xs">
    <div class="dropdown cartMenu">
       <a href="<?php echo base_url(); ?>shop/cart/cart/<?php print_r(@$this->id_cart); ?>" class="dropdown-toggle"> 
        <i class="fa fa-shopping-basket fa-lg"> </i>
        <span id="cartLabel" class="label labelRounded label-danger" style="position: relative; margin-left:-10px;top:-10px;"><?php print_r(@$cart_qty); ?></span>
        </a>
    </div>
</div>
<!--/.cartMenu-->
<!-- <div class="minicart">
        <ul>
            <?php foreach ($cart_items as $item): ?>
                <li>
                    <img src="<?php echo get_image_path(get_id_cover_image($item->id_pa), 4); ?>" alt="img" class="img-responsive" width="50">
                <legend></legend>
                </li> 
            <?php endforeach ?>
        </ul>
</div> -->
<!-- <div class="search-box">
    <div class="input-group">
        <button class="btn btn-nobg getFullSearch" type="button"><i class="fa fa-search"> </i></button>
    </div>
</div> -->
<!--/.search-box -->
</div>
<!--/.navbar-nav hidden-xs-->

<style>
    .minicart {
        width:20%;
        overflow-y:scroll;
        height:350px;
        padding:5px;
        border-top: 2px solid #DA631D;
        box-shadow: -2px 2px 2px #888888;
        background-color:#fff;
        display:none;
        position:absolute;
        z-index:9999;
        right:5px;
        top:50px;
    }
    .minicart ul{
        padding:0px;
    }   
    .minicart ul li{
        padding:1px;
    }
    .minicart span{
        margin-left:10px;
        font-size: 18px;
    }
    
</style>
<script>
  $(document).ready(function () {
    $(".cartMenu,.minicart").hover(
        function () {
            $(".minicart").stop().slideDown(100);
        }, function () {
            $(".minicart").stop().slideUp(100);
        });
});
</script>











