var exported = 0;
var i = 0;
var limit = 0;
var ds;
$(document).ready(function() {
  getData();
});

function addlog(text){
  var el = document.createElement('P');
  el.innerHTML = (text);
  $('#result').append(el);
}

function clearlog(){
  $('#result').html('');
}


function getData(){
  var from_date = '2020-01-01 00:00:00';
  var to_date = '2020-06-30 23:59:59';
  addlog("Get WM Between " + from_date + " AND " + to_date);
  i = 0;
  limit = 100;
  $.ajax({
    url:"controller/IXController.php?get_wm_list",
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
        addlog("พบ 0 ออเดอร์");
        window.close();
      }
    }
  })
}



function export_to_ix(){
  if(i === limit){
    //clearlog();
    //getData();
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
        update_stat();
        i++;
        export_to_ix();
      }
    })
  }
}


function update_stat(){
  exported++;
  $('#stat-qty').text(exported);
}


function isJson(str){
	try{
		JSON.parse(str);
	}catch(e){
		return false;
	}
	return true;
}
