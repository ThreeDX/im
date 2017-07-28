<?php if ($this->error == 0) { ?>
<div id='item_deleted_id'><?=$this->item_deleted_id;?></div>
<div id='order_sum'><?=$this->order_sum;?></div>
<div id='error'><?=$this->error;?></div>
<?php } else { ?>
<div id='error'><?=$this->error;?></div>
<?php } ?>