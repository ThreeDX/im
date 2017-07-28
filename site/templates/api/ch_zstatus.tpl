<?php if ($this->error == 0) { ?>
<div id='order_id'><?=$this->order_id;?></div>
<div id='status_id'><?=$this->status_id;?></div>
<div id='status_text'><?=$this->status_text;?></div>
<div id='error'><?=$this->error;?></div>
<?php } else { ?>
<div id='error'><?=$this->error;?></div>
<?php } ?>