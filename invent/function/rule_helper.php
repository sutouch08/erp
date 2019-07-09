<?php
function discount_label($disc = '', $unit = '', $sp = '')
{
  $unit = $unit === '' ? '' : ($unit == 'amount' ? '' : '%' );
  return $disc > 0 ? $sp.$disc.$unit : '';
}

 ?>
