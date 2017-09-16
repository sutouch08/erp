<div class="row">
	<div class="col-sm-6">
    	<table class="table border-1">
        <?php if( $add OR $edit OR $delete ) : ?>
        	<tr>
            	<td class="width-30 middle text-right">สถานะ : </td>
                <td class="width-40">
                	<select class="form-control input-sm" id="stateList">
                    	<option value="0">เลือกสถานะ</option>
                 <?php if( $order->state <3 OR $edit) : ?>
                        <option value="1">รอการชำระเงิน</option>
                        <option value="2">แจ้งชำระเงิน</option>
                        <option value="3">รอจัดสินค้า</option>
                 <?php endif; ?>
                 <?php if( $delete ) : ?>
                        <option value="11">ยกเลิก</option>
                  <?php endif; ?>      
                    </select>
                </td>
                <td class="width-30">
                <?php if( $order->status == 1 ) : ?>
                	<button class="btn btn-sm btn-primary btn-block" onclick="changeState()">เปลี่ยนสถานะ</button>
                <?php endif; ?>
                </td>
            </tr>
       <?php else : ?>
       <tr>
            	<td class="width-30 text-center">สถานะ</td>
                <td class="width-40 text-center">พนักงาน</td>
                <td class="width-30 text-center">เวลา</td>
            </tr>
       <?php endif; ?>
<?php $state = new state(); ?>
<?php $qs = $state->getOrderStateList($order->id); ?>
<?php if( dbNumRows($qs) > 0 ) : ?>
<?php 	while( $rs = dbFetchObject($qs) ) : ?>
			<tr class="font-size-10" style="color:white; background-color:<?php echo $rs->color; ?>;">
            	<td class="middle text-center"><?php echo $rs->name; ?></td>
                <td class="middle text-center"><?php echo employee_name($rs->id_employee); ?></td>
                <td class="middle text-center"><?php echo thaiDateTime($rs->date_upd); ?></td>
            </tr>
<?php	endwhile; ?>
<?php else : ?>
		<tr><td colspan="3" class="middle text-center font-size-18">ไม่พบรายการ</td></tr>
<?php endif; ?>          
        </table>
    </div>
</div>