
<script src="../library/js/jquery.min.js"></script>
<script src="script/template.js"></script>
<script>
$(document).ready(function() {
  getCurrentBudgetList();
});


function getCurrentBudgetList(){
  $.ajax({
    url:'controller/sponsorController.php?getAllCurrentBudget',
    type:'GET',
    cache:false,
    success:function(rs){
      var rs = $.trim(rs);
      if(isJson(rs)){
        data = $.parseJSON(rs);
        startRecal(data);
      }
    }
  });
}

function startRecal(ds){
  if(ds.length > 0){
    setTimeout(function(){
      recal(ds, 0);
    }, 100);
  }else{
    $('body').append('no budget found');
  }
}


function recal(step, index){
  var ds = step[index];

  $.ajax({
    url: 'controller/sponsorController.php?reCalSponsorBudget&id_budget='+ds.id_budget,
    type:'GET',
    cache:'false',
    success:function(rs){
      var rs = $.trim(rs);
      $('body').append('recal : '+ds.name+' => '+rs+'<br/>');
      if(index == (step.length)){
        window.close();
      }else{
        recal(step, index);
      }
    }
  });
index++;
}

</script>
