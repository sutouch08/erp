
function importBrand(el){
  changeStatus(el, 1);
  $.ajax({
    url:'controller/interfaceController.php?syncMaster&brand',
    type:'GET',
    cache:'false',
    success:function(rs){
      var rs = $.trim(rs);
      if(rs == 'success'){
        changeStatus(el, 2);
      }else{
        changeStatus(el, rs);
      }

      importUnit('pdUnit');
    }
  });
}


function importUnit(el){
  changeStatus(el, 1);
  $.ajax({
    url:'controller/interfaceController.php?syncMaster&unit',
    type:'GET',
    cache:'false',
    success:function(rs){
      var rs = $.trim(rs);
      if(rs == 'success'){
        changeStatus(el, 2);
      }else{
        changeStatus(el, rs);
      }

      importProductGroup('pdGroup');
    }
  });
}


function importProductGroup(el){
  changeStatus(el, 1);
  $.ajax({
    url:'controller/interfaceController.php?syncMaster&product_group',
    type:'GET',
    cache:'false',
    success:function(rs){
      var rs = $.trim(rs);
      if(rs == 'success'){
        changeStatus(el, 2);
      }else{
        changeStatus(el, rs);
      }

      importColor('pdColor');
    }
  });
}


function importColor(el){
  changeStatus(el, 1);
  $.ajax({
    url:'controller/interfaceController.php?syncMaster&color',
    type:'GET',
    cache:'false',
    success:function(rs){
      var rs = $.trim(rs);
      if(rs == 'success'){
        changeStatus(el, 2);
      }else{
        changeStatus(el, rs);
      }

      importSize('pdSize');
    }
  });
}


function importSize(el){
  changeStatus(el, 1);
  $.ajax({
    url:'controller/interfaceController.php?syncMaster&size',
    type:'GET',
    cache:'false',
    success:function(rs){
      var rs = $.trim(rs);
      if(rs == 'success'){
        changeStatus(el, 2);
      }else{
        changeStatus(el, rs);
      }

      importStyle('pdStyle');
    }
  });
}


function importStyle(el){
  changeStatus(el, 1);
  $.ajax({
    url:'controller/interfaceController.php?syncMaster&style',
    type:'GET',
    cache:'false',
    success:function(rs){
      var rs = $.trim(rs);
      if(rs == 'success'){
        changeStatus(el, 2);
      }else{
        changeStatus(el, rs);
      }

      importProduct('product');
    }
  });
}



function importProduct(el){
  changeStatus(el, 1);
  $.ajax({
    url:'controller/interfaceController.php?syncMaster&product',
    type:'GET',
    cache:'false',
    success:function(rs){
      var rs = $.trim(rs);
      if(rs == 'success'){
        changeStatus(el, 2);
      }else{
        changeStatus(el, rs);
      }

      importBarcode('pdBarcode');
    }
  });
}



function importProduct(el){
  changeStatus(el, 1);
  $.ajax({
    url:'controller/interfaceController.php?syncMaster&product',
    type:'GET',
    cache:'false',
    success:function(rs){
      var rs = $.trim(rs);
      if(rs == 'success'){
        changeStatus(el, 2);
      }else{
        changeStatus(el, rs);
      }

      importBarcode('pdBarcode');
    }
  });
}


function importBarcode(el){
  changeStatus(el, 1);
  $.ajax({
    url:'controller/interfaceController.php?syncMaster&barcode',
    type:'GET',
    cache:'false',
    success:function(rs){
      var rs = $.trim(rs);
      if(rs == 'success'){
        changeStatus(el, 2);
      }else{
        changeStatus(el, rs);
      }

      importSaleGroup('saleGroup');
    }
  });
}






function importPO(el){
  changeStatus(el, 1);
  $.ajax({
    url:'controller/interfaceController.php?syncDocument&po',
    type:'GET',
    cache:'false',
    success:function(rs){
      var rs = $.trim(rs);
      if(rs == 'success'){
        changeStatus(el, 2);
      }else{
        changeStatus(el, rs);
      }

      importBM('bm');
    }
  });
}




function importBM(el){
  changeStatus(el, 1);
  $.ajax({
    url:'controller/interfaceController.php?syncDocument&BM',
    type:'GET',
    cache:'false',
    success:function(rs){
      var rs = $.trim(rs);
      if(rs == 'success'){
        changeStatus(el, 2);
      }else{
        changeStatus(el, rs);
      }

      importSM('sm');
    }
  });
}




function importSM(el){
  changeStatus(el, 1);
  $.ajax({
    url:'controller/interfaceController.php?syncDocument&SM',
    type:'GET',
    cache:'false',
    success:function(rs){
      var rs = $.trim(rs);
      if(rs == 'success'){
        changeStatus(el, 2);
      }else{
        changeStatus(el, rs);
      }
      addlog();
    }
  });
}






function importSupplier(el){
  changeStatus(el, 1);
  $.ajax({
    url:'controller/interfaceController.php?syncMaster&supplier',
    type:'GET',
    cache:'false',
    success:function(rs){
      var rs = $.trim(rs);
      if(rs == 'success'){
        changeStatus(el, 2);
      }else{
        changeStatus(el, rs);
      }

      importWarehouse('warehouse');
    }
  });
}


function importCustomerCredit(el){
  changeStatus(el, 1);
  $.ajax({
    url:'controller/interfaceController.php?syncMaster&customerCredit',
    type:'GET',
    cache:'false',
    success:function(rs){
      var rs = $.trim(rs);
      if(rs == 'success'){
        changeStatus(el, 2);
      }else{
        changeStatus(el, rs);
      }

      importSupGroup('supGroup');
    }
  });
}


function importSupGroup(el){
  changeStatus(el, 1);
  $.ajax({
    url:'controller/interfaceController.php?syncMaster&supplierGroup',
    type:'GET',
    cache:'false',
    success:function(rs){
      var rs = $.trim(rs);
      if(rs == 'success'){
        changeStatus(el, 2);
      }else{
        changeStatus(el, rs);
      }

      importSupplier('supplier');
    }
  });
}




function importWarehouse(el){
  changeStatus(el, 1);
  $.ajax({
    url:'controller/interfaceController.php?syncMaster&warehouse',
    type:'GET',
    cache:'false',
    success:function(rs){
      var rs = $.trim(rs);
      if(rs == 'success'){
        changeStatus(el, 2);
      }else{
        changeStatus(el, rs);
      }

      importPO('po');
    }
  });
}






function importCustomer(el){
  changeStatus(el, 1);
  $.ajax({
    url:'controller/interfaceController.php?syncMaster&customer',
    type:'GET',
    cache:'false',
    success:function(rs){
      var rs = $.trim(rs);
      if(rs == 'success'){
        changeStatus(el, 2);
      }else{
        changeStatus(el, rs);
      }

      importCustomerCredit('cusCredit');
    }
  });
}



function importCustomerGroup(el){
  changeStatus(el, 1);
  $.ajax({
    url:'controller/interfaceController.php?syncMaster&customerGroup',
    type:'GET',
    cache:'false',
    success:function(rs){
      var rs = $.trim(rs);
      if(rs == 'success'){
        changeStatus(el, 2);
      }else{
        changeStatus(el, rs);
      }

      importCustomer('customer');
    }
  });
}



function importCustomerArea(el){
  changeStatus(el, 1);
  $.ajax({
    url:'controller/interfaceController.php?syncMaster&customerArea',
    type:'GET',
    cache:'false',
    success:function(rs){
      var rs = $.trim(rs);
      if(rs == 'success'){
        changeStatus(el, 2);
      }else{
        changeStatus(el, rs);
      }

      importCustomerGroup('cusGroup');
    }
  });
}




function importSaleMan(el){
  changeStatus(el, 1);
  $.ajax({
    url:'controller/interfaceController.php?syncMaster&sale',
    type:'GET',
    cache:'false',
    success:function(rs){
      var rs = $.trim(rs);
      if(rs == 'success'){
        changeStatus(el, 2);
      }else{
        changeStatus(el, rs);
      }

      importCustomerArea('cusArea');
    }
  });
}



function importSaleMan(el){
  changeStatus(el, 1);
  $.ajax({
    url:'controller/interfaceController.php?syncMaster&sale',
    type:'GET',
    cache:'false',
    success:function(rs){
      var rs = $.trim(rs);
      if(rs == 'success'){
        changeStatus(el, 2);
      }else{
        changeStatus(el, rs);
      }

      importCustomerArea('cusArea');
    }
  });
}



function importSaleGroup(el){
  changeStatus(el, 1);
  $.ajax({
    url:'controller/interfaceController.php?syncMaster&saleGroup',
    type:'GET',
    cache:'false',
    success:function(rs){
      var rs = $.trim(rs);
      if(rs == 'success'){
        changeStatus(el, 2);
      }else{
        changeStatus(el, rs);
      }

      importSaleMan('saleMan');
    }
  });
}
