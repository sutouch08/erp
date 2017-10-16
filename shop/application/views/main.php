
<style>
.owl-carousel {
    display: none;
    position: relative;
    width: 100%;
    -ms-touch-action: pan-y;
}
.owl-carousel .owl-item {
    -webkit-backface-visibility: hidden;
    -moz-backface-visibility: hidden;
    -ms-backface-visibility: hidden;
    -webkit-transform: translate3d(0, 0, 0);
    -moz-transform: translate3d(0, 0, 0);
    -ms-transform: translate3d(0, 0, 0);
}
.owl-carousel .owl-wrapper-outer {
    overflow: hidden;
    position: relative;
    width: 100%;
}
#productslider .item {
    margin: 0 15px 5px;
}

.item {
    display: block;
    height: auto;
    transition: all 0.3s ease 0s;
    -moz-transition: all 0.3s ease 0s;
    -webkit-transition: all 0.3s ease 0s;
    -o-transition: all 0.3s ease 0s;
    -ms-transition: all 0.3s ease 0s;
    margin-bottom: 15px;
    height: 450px;
}
.product {
    display: block;
    height: auto;
    transition: all 0.3s ease 0s;
    -moz-transition: all 0.3s ease 0s;
    -webkit-transition: all 0.3s ease 0s;
    -o-transition: all 0.3s ease 0s;
    -ms-transition: all 0.3s ease 0s;
    border: 1px solid #DDDDDD;
    border-bottom: 1px solid #DDDDDD;
    text-align: center;
}

th,td  {
    max-width: 100px;
    word-wrap: break-word;
}

.promo_field{
    position: absolute;
    bottom:50px;
    font-size: 18px;
    width:100%;
    height:100px;
    opacity:0.4;
    background-color:#2E2E2E;
}
</style>

<?php $this->load->view('module/new_arrivals'); ?>
<?php $this->load->view('module/features'); ?>


