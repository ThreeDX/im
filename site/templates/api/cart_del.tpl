<?php if ($this->error == 0) { ?>
<div id='count'><?=$this->cart_count;?></div>
<div id='sum'><?=$this->cart_sum;?></div>
<div id='id'><?=$this->cart_id;?></div>
<div id='error'><?=$this->error;?></div>
<?php } else { ?>
<div id='error'><?=$this->error;?></div>
<?php } ?>