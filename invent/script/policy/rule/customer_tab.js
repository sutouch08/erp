
//--- เลือกลูกค้าทั้งหมด
function toggleAllCustomer(option){
  if(option == 'Y'){
    $('#btn-cust-all-yes').addClass('btn-primary');
    $('#btn-cust-all-no').removeClass('btn-primary');
    $('#all_customer').val(1);
    $('.not-all').attr('disabled', 'disabled');
    $('.option').attr('disabled', 'disabled');
  }else if(option == 'N'){
    $('#btn-cust-all-no').addClass('btn-primary');
    $('#btn-cust-all-yes').removeClass('btn-primary');
    $('#all_customer').val(0);
    $('.not-all').removeAttr('disabled');
  }
}


function toggleCustomerId(option){
  if(option == 'Y'){
    $('#btn-cust-id-yes').addClass('btn-primary');
    $('#btn-cust-id-no').removeClass('btn-primary');
    $('#txt-cust-id-box').removeAttr('disabled');
    $('#btn-cust-id-add').removeAttr('disabled');
    $('#customer_id').val(1);
  }else if(option == 'N'){
    $('#btn-cust-id-no').addClass('btn-primary');
    $('#btn-cust-id-yes').removeClass('btn-primary');
    $('#txt-cust-id-box').attr('disabled', 'disabled');
    $('#btn-cust-id-add').attr('disabled', 'disabled');
    $('#customer_id').val(0);
  }
}


function toggleCustomerGroup(option){
  if(option == 'Y'){
    $('#btn-cust-group-no').removeClass('btn-primary');
    $('#btn-cust-group-yes').addClass('btn-primary');
    $('#btn-select-cust-group').removeAttr('disabled');
    $('#customer_group').val(1);
  }else if(option == 'N'){
    $('#btn-cust-group-yes').removeClass('btn-primary');
    $('#btn-cust-group-no').addClass('btn-primary');
    $('#btn-select-cust-group').attr('disabled', 'disabled');
    $('#customer_group').val(0);
  }
}



function toggleCustomerType(option){
  if(option == 'Y'){
    $('#btn-cust-type-no').removeClass('btn-primary');
    $('#btn-cust-type-yes').addClass('btn-primary');
    $('#btn-select-cust-type').removeAttr('disabled');
    $('#customer_type').val(1);
  }else if(option == 'N'){
    $('#btn-cust-type-yes').removeClass('btn-primary');
    $('#btn-cust-type-no').addClass('btn-primary');
    $('#btn-select-cust-type').attr('disabled', 'disabled');
    $('#customer_type').val(0);
  }
}



function toggleCustomerKind(option){
  if(option == 'Y'){
    $('#btn-cust-kind-no').removeClass('btn-primary');
    $('#btn-cust-kind-yes').addClass('btn-primary');
    $('#btn-select-cust-kind').removeAttr('disabled');
    $('#customer_kind').val(1);
  }else if(option == 'N'){
    $('#btn-cust-kind-yes').removeClass('btn-primary');
    $('#btn-cust-kind-no').addClass('btn-primary');
    $('#btn-select-cust-kind').attr('disabled', 'disabled');
    $('#customer_kind').val(0);
  }
}



function toggleCustomerArea(option){
  if(option == 'Y'){
    $('#btn-cust-area-no').removeClass('btn-primary');
    $('#btn-cust-area-yes').addClass('btn-primary');
    $('#btn-select-cust-area').removeAttr('disabled');
    $('#customer_area').val(1);
  }else if(option == 'N'){
    $('#btn-cust-area-yes').removeClass('btn-primary');
    $('#btn-cust-area-no').addClass('btn-primary');
    $('#btn-select-cust-area').attr('disabled', 'disabled');
    $('#customer_area').val(0);
  }
}



function toggleCustomerClass(option){
  if(option == 'Y'){
    $('#btn-cust-class-no').removeClass('btn-primary');
    $('#btn-cust-class-yes').addClass('btn-primary');
    $('#btn-select-cust-class').removeAttr('disabled');
    $('#customer_class').val(1);
  }else if(option == 'N'){
    $('#btn-cust-class-yes').removeClass('btn-primary');
    $('#btn-cust-class-no').addClass('btn-primary');
    $('#btn-select-cust-class').attr('disabled', 'disabled');
    $('#customer_class').val(0);
  }
}
