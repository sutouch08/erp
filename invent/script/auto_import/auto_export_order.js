var i = 0;
var limit = 100;
var ds;
$(document).ready(function() {
  syncData();
});


function getData(){
  var from_date = '2020-01-01 00:00:00';
  var to_date = '2020-05-31 23:59:59';
  $.ajax({
    url:"controller/IXController.php?get_order_list",
    type:'GET',
    cache:false,
    data:{
      'from_date' : from_date,
      'to_date' : to_date,
      'limit' : limit
    },
    success:function(rs){
      if(isJson(rs)){
        ds = $.parseJSON(rs);
        limit = ds.length;
        export_to_ix();
      }else{
        window.close();
      }
    }
  })
}

function syncData(){
  var data = getData();
  if(data !== false){

  }
}


function export_to_ix(){
  if(i === limit){
    window.close();
  }else{
    $.ajax({
      url:'controller/IXController.php?export_ix_order',
      type:'POST',
      cache:false,
      data:{
        'id' : ds[i]
      },
      success:function(rs){
        i++;
        export_to_ix();
      }
    })
  }
}


function isJson(str){
	try{
		JSON.parse(str);
	}catch(e){
		return false;
	}
	return true;
}
