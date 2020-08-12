var i = 0;
var limit = 100;
var ds;
$(document).ready(function() {
  getData();
});

function addlog(text){
  var el = document.createElement('P');
  el.innerHTML = (text);
  document.body.prepend(el);
}


function getData(){
  var from_date = '2020-01-01 00:00:00';
  var to_date = '2020-06-30 23:59:59';
  addlog("Get WM Between " + from_date + " AND " + to_date);

  $.ajax({
    url:"controller/IXController.php?get_error_wm_list",
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
        addlog("พบ " + limit + " ออเดอร์");
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
      url:'controller/IXController.php?export_ix_wm',
      type:'POST',
      cache:false,
      data:{
        'id' : ds[i]
      },
      success:function(rs){
        addlog(i + " : ID "+ ds[i] + " : " + rs);
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
