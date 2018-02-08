<?php
$id = isset($_GET['id_consign']) ? $_GET['id_consign'] : FALSE;
$cs = new consign($id);
$disabled = $id === FALSE ? '' : 'disabled';
$zone = new zone($cs->id_zone);
$shop = new shop($cs->id_shop);
$event = new event($cs->id_event);
$customer = new customer();
$allowUnderZero = $zone->allowUnderZero === TRUE ? 1 : 0;
 ?>

<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-check-square-o"></i> <?php echo $pageTitle; ?></h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
<?php echo goBackButton(); ?>
<?php if( ($add OR $edit) && $id !== FALSE && $cs->isSaved == 0 && $cs->isCancle == 0 ) : ?>
  <?php if($cs->id_consign_check == 0) : ?>
      <button type="button" class="btn btn-sm btn-info" onclick="getActiveCheckList()">นำเข้ายอดต่าง</button>
  <?php endif; ?>
      <button type="button" class="btn btn-sm btn-success" onclick="saveConsign()"><i class="fa fa-save"></i> บันทึก</button>
<?php endif; ?>

    </p>
  </div>
</div>

<hr/>
<?php
include 'include/consign/consign_add_header.php';

//--- ต้องเพิ่มเอกสารแล้ว และ ต้องยังไม่บันทึกหรือยกเลิกเอกสาร
if( $id !== FALSE && $cs->isSaved == 0 && $cs->isCancle == 0)
{
  include 'include/consign/consign_add_control.php';
  include 'include/consign/consign_add_detail.php';
}


 ?>

 <div class="modal fade" id="check-list-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
 	<div class="modal-dialog" style="width:400px;">
 		<div class="modal-content">
   			<div class="modal-header">
 				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
 			 </div>
 			 <div class="modal-body" id="check-list-body">

        </div>
 			 <div class="modal-footer">
 				<button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
 			 </div>
 		</div>
 	</div>
 </div>


<script id="check-list-template" type="text/x-handlebarsTemplate">
 <div class="row">
   <div class="col-sm-12">
     <table class="table table-striped">
       <thead>
         <tr>
           <th class="width-30 text-center">วันที่</th>
           <th class="width-40 text-center">เอกสาร</th>
           <th></th>
         </tr>
       </thead>
       <tbody id="check-list-table">
      {{#each this}}
        {{#if nodata}}
          <tr>
            <td colspan="3" class="text-center"><h4>ไม่พบรายการ</h4></td>
          </tr>
        {{else}}
           <tr>
             <td class="middle text-center">{{date_add}}</td>
             <td class="middle text-center">{{reference}}</td>
             <td class="middle text-center">
               <button type="button" class="btn btn-xs btn-info btn-block" onclick="loadCheckDiff({{id}},'{{reference}}')">นำเข้ายอดต่าง</button>
             </td>
           </tr>
         {{/if}}
      {{/each}}
       </tbody>
     </table>
   </div>
 </div>
</script>


<script src="script/consign/consign_add.js"></script>
<script src="script/consign/consign_edit.js"></script>
<script src="script/consign/consign_control.js"></script>
<script src="script/consign/consign_detail.js"></script>
