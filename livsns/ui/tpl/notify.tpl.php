<?php 
/* $Id: notify.tpl.php 606 2010-12-15 07:54:17Z wang $ */
?>
<?php include hg_load_template('head');?>
<div class="content">
<div class="notify border">
<div class="head">
<div style="width:130px;float:left"><?php echo $this->lang['notify']?></div><div><?php echo $this->lang['unreadNum']?>:<span id="unreadNum"><?php echo $unreadNum[0][count];?></span></div>
</div>
<div class="notify-info" id="notifyInfo">
<?php
foreach($notifyInfo as $key => $value)
{
	$paixu[$key] = $value['is_read'];
}
array_multisort($paixu, SORT_ASC,$notifyInfo);

 foreach($notifyInfo as $key => $value)
 {
?>
<ul><li style="width:160px;"><?php echo $value['content']?></li><li style="width:30px;"><?php echo $value['is_read'];?></li></ul>
<?php 
 }
?>
</div>
</div>
</div>
<?php include hg_load_template('foot');?>