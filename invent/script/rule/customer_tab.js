
//--- เลือกลูกค้าทั้งหมด
function toggleAllCustomer(option){
  $('#all_customer').val(option);
  if(option == 'Y'){
    $('#btn-cust-all-yes').addClass('btn-primary');
    $('#btn-cust-all-no').removeClass('btn-primary');
    disActiveControl();
  }else if(option == 'N'){
    $('#btn-cust-all-no').addClass('btn-primary');
    $('#btn-cust-all-yes').removeClass('btn-primary');
    $('.not-all').removeAttr('disabled');
    activeControl();
  }
}



function disActiveControl(){
  toggleCustomerId('N');
  toggleCustomerGroup('N');
  toggleCustomerType('N');
  toggleCustomerKind('N');
  toggleCustomerArea('N');
  toggleCustomerClass('N');
  $('.not-all').attr('disabled', 'disabled');
}


function activeControl(){
  customer_id = $('#customer_id').val();
  if(customer_id == 'Y'){
    toggleCustomerGroup('N');
    toggleCustomerType('N');
    toggleCustomerKind('N');
    toggleCustomerArea('N');
    toggleCustomerClass('N');
    return;
  }

  toggleCustomerGroup($('#customer_group').val());
  toggleCustomerType($('#customer_type').val());
  toggleCustomerKind($('#customer_kind').val());
  toggleCustomerArea($('#customer_area').val());
  toggleCustomerClass($('#customer_class').val());
}






function toggleCustomerId(option){
  $('#customer_id').val(option);
  if(option == 'Y'){
    $('#btn-cust-id-yes').addClass('btn-primary');
    $('#btn-cust-id-no').removeClass('btn-primary');
    $('#txt-cust-id-box').removeAttr('disabled');
    $('#btn-cust-id-add').removeAttr('disabled');
  }else if(option == 'N'){
    $('#btn-cust-id-no').addClass('btn-primary');
    $('#btn-cust-id-yes').removeClass('btn-primary');
    $('#txt-cust-id-box').attr('disabled', 'disabled');
    $('#btn-cust-id-add').attr('disabled', 'disabled');
  }
  activeControl();
}


function toggleCustomerGroup(option){
  $('#customer_group').val(option);
  all = $('#all_customer').val();
  sc = $('#customer_id').val();
  if(option == 'Y' && sc == 'N' && all == 'N'){
    $('#btn-cust-group-no').removeClass('btn-primary');
    $('#btn-cust-group-yes').addClass('btn-primary');
    $('#btn-cust-group_no').removeAttr('disabled');
    $('#btn-cust-group-yes').removeAttr('disabled');
    $('#btn-select-cust-group').removeAttr('disabled');

    return;
  }

  if(option == 'N' && sc == 'N' && all == 'N'){
    $('#btn-cust-group-yes').removeClass('btn-primary');
    $('#btn-cust-group-no').addClass('btn-primary');
    $('#btn-cust-group-no').removeAttr('disabled');
    $('#btn-cust-group-yes').removeAttr('disabled');
    $('#btn-select-cust-group').attr('disabled', 'disabled');

    return;
  }

  if(all == 'Y' || sc == 'Y'){
    $('#customer_group').val('N');
    $('#btn-cust-group-yes').removeClass('btn-primary');
    $('#btn-cust-group-no').addClass('btn-primary');
    $('#btn-cust-group-yes').attr('disabled', 'disabled');
    $('#btn-cust-group-no').attr('disabled', 'disabled');
    $('#btn-select-cust-group').attr('disabled', 'disabled');
    return;
  }
}



function toggleCustomerType(option){
  $('#customer_type').val(option);
  sc = $('#customer_id').val();
  all = $('#all_customer').val();
  if(option == 'Y' && all == 'N' && sc == 'N'){
    $('#btn-cust-type-no').removeClass('btn-primary');
    $('#btn-cust-type-yes').addClass('btn-primary');
    $('#btn-cust-type-no').removeAttr('disabled');
    $('#btn-cust-type-yes').removeAttr('disabled');
    $('#btn-select-cust-type').removeAttr('disabled');
    return;
  }

  if(option == 'N' && sc == 'N' && all == 'N'){
    $('#btn-cust-type-yes').removeClass('btn-primary');
    $('#btn-cust-type-no').addClass('btn-primary');
    $('#btn-cust-type-yes').removeAttr('disabled');
    $('#btn-cust-type-no').removeAttr('disabled');
    $('#btn-select-cust-type').attr('disabled', 'disabled');

    return;
  }

  if(all == 'Y' || sc == 'Y'){
    $('#customer_type').val('N');
    $('#btn-cust-type-yes').removeClass('btn-primary');
    $('#btn-cust-type-no').addClass('btn-primary');
    $('#btn-cust-type-yes').attr('disabled', 'disabled');
    $('#btn-cust-type-no').attr('disabled', 'disabled');
    $('#btn-select-cust-type').attr('disabled', 'disabled');
  }
}



function toggleCustomerKind(option){
  $('#customer_kind').val(option);
  customer_id = $('#customer_id').val();
  all_customer = $('#all_customer').val();
  if(option == 'Y' && customer_id == 'N' && all_customer == 'N'){
    $('#btn-cust-kind-no').removeClass('btn-primary');
    $('#btn-cust-kind-yes').addClass('btn-primary');
    $('#btn-select-cust-kind').removeAttr('disabled');

  }else{
    $('#btn-cust-kind-yes').removeClass('btn-primary');
    $('#btn-cust-kind-no').addClass('btn-primary');
    $('#btn-select-cust-kind').attr('disabled', 'disabled');

  }
}



function toggleCustomerArea(option){
  $('#customer_area').val(option);
  customer_id = $('#customer_id').val();
  all_customer = $('#all_customer').val();
  if(option == 'Y' && customer_id == 'N' && all_customer == 'N'){
    $('#btn-cust-area-no').removeClass('btn-primary');
    $('#btn-cust-area-yes').addClass('btn-primary');
    $('#btn-select-cust-area').removeAttr('disabled');

  }else{
    $('#btn-cust-area-yes').removeClass('btn-primary');
    $('#btn-cust-area-no').addClass('btn-primary');
    $('#btn-select-cust-area').attr('disabled', 'disabled');

  }
}



function toggleCustomerClass(option){
  $('#customer_class').val(option);
  customer_id = $('#customer_id').val();
  all_customer = $('#all_customer').val();
  if(option == 'Y' && customer_id == 'N' && all_customer == 'N'){
    $('#btn-cust-class-no').removeClass('btn-primary');
    $('#btn-cust-class-yes').addClass('btn-primary');
    $('#btn-select-cust-class').removeAttr('disabled');

  }else{
    $('#btn-cust-class-yes').removeClass('btn-primary');
    $('#btn-cust-class-no').addClass('btn-primary');
    $('#btn-select-cust-class').attr('disabled', 'disabled');

  }
}
