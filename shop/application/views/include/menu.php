
<div class="navbar navbar-tshop navbar-fixed-top" role="navigation">
 <?php $this->load->view("include/navbar_top.php"); ?>
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand " href="<?php echo base_url() ?>shop"> <img src="<?php echo base_url(); ?>shop/images/logo.png" alt="WARRIX"> </a>

            <button type="button" class="navbar-toggle" data-toggle="collapse" onclick="viewCart(<?= @$this->id_cart; ?>)">
                <i class="fa fa-shopping-basket fa-lg colorWhite"> </i> 
                <span id="cartMobileLabel" class="label labelRounded label-danger" style="position: relative; margin-left:-10px; top:-10px;"><?php echo $this->cart_qty ?> </span>
            </button>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-right hidden-xs">
                <li><a onclick="viewCart(<?= @$this->id_cart; ?>)">
                    <i class="fa fa-shopping-basket fa-lg colorWhite"> </i> 
                    <span id="cartMobileLabel" class="label labelRounded label-danger" style="position: relative; margin-left:-10px; top:-10px;"><?php echo $this->cart_qty ?> </span>
                    </a>
                </li>
            </ul>
            <ul class="nav navbar-nav">
                <?php foreach ($menus as $menu): ?>
                <li>
                    <?php if(empty($menu->child)): ?>
                        <a href="<?= base_url()?>shop/product/item/<?= $menu->parent_id ?>" ><i class="fa <?= $menu->icon ?>" aria-hidden="true"></i> 
                        <?= $menu->name ?></a>
                    <?php else: ?>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa <?= $menu->icon ?>" aria-hidden="true"></i>
                        <?= $menu->name ?><span class="caret"></a>
                        <ul class="dropdown-menu multi-level">
                            <?php foreach ($menu->child as $child): ?>
                                <?php if ($child->sub_child!==''): ?>
                                <li class="dropdown-submenu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa <?= $child->icon ?>" aria-hidden="true"></i><?= $child->name ?><b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <?php foreach ($child->sub_child as $sub): ?>
                                            <li><a href="<?= base_url()?>shop/product/item/<?= $sub->parent_id ?>/<?= $sub->child_id ?>/<?= $sub->id_subchild ?>"><i class="fa <?= $sub->icon ?>" aria-hidden="true"></i><?= $sub->name ?></a></li>
                                        <?php endforeach ?>
                                    </ul>
                                </li>
                                <?php else: ?>
                                <li><a href="<?= base_url()?>shop/product/item/<?= $child->parent_id ?>/<?= $child->id_child ?>"><i class="fa <?= $child->icon ?>" aria-hidden="true"></i><?= $child->name ?></a></li>
                                <?php endif ?>
                            <?php endforeach ?>
                        </ul>
                    <?php endif ?>
                </li>
                <?php endforeach ?>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>
<style>

    .dropdown-submenu {
        position: relative;
    }

    .dropdown-submenu>.dropdown-menu {
        top: 0;
        left: 100%;
        margin-top: -6px;
        margin-left: -1px;
        -webkit-border-radius: 0 6px 6px 6px;
        -moz-border-radius: 0 6px 6px;
        border-radius: 0 6px 6px 6px;
    }

    .dropdown-submenu:hover>.dropdown-menu {
        display: block;
    }

    .dropdown-submenu>a:after {
        display: block;
        content: " ";
        float: right;
        width: 0;
        height: 0;
        border-color: transparent;
        border-style: solid;
        border-width: 5px 0 5px 5px;
        border-left-color: #ccc;
        margin-top: 5px;
        margin-right: -10px;
    }

    .dropdown-submenu:hover>a:after {
        border-left-color: #fff;
    }

    .dropdown-submenu.pull-left {
        float: none;
    }

    .dropdown-submenu.pull-left>.dropdown-menu {
        left: -100%;
        margin-left: 10px;
        -webkit-border-radius: 6px 0 6px 6px;
        -moz-border-radius: 6px 0 6px 6px;
        border-radius: 6px 0 6px 6px;
    }

    @media (min-width: 767px) {
        .navbar-nav .dropdown-menu .caret {
            transform: rotate(-90deg);
        }
    }
    .cartMenu:hover { font-size:16px}

</style>

<script>
    $(document).ready(function() {
        $('.navbar a.dropdown-toggle').on('click', function(e) {
            var $el = $(this);

            var $parent = $(this).offsetParent(".dropdown-menu");
            $(this).parent("li").toggleClass('open');


            if(!$parent.parent().hasClass('nav')) {
                $el.next().css({"top": $el[0].offsetTop, "left": $parent.outerWidth() - 3});
            }
            $('.nav li.open').not($(this).parents("li")).removeClass("open");
            return false;
        });


    });

    function viewCart(id_cart){
        var l = window.location;
        var base_url = l.protocol + "//" + l.host + "/" + l.pathname.split('/')[1];
        // alert(base_url+"/shop/cart/cart/"+id_cart);

        document.location.href = base_url+"/shop/cart/cart/"+id_cart;
    }
</script>







