<style>
.u_notice {}
.u_notice ul li{border-bottom: 1px dotted #C7C7C7;
    height: 22px;
    line-height: 22px;
    padding-left: 15px;}
.history_no{background: none repeat scroll 0 0 #F7F7F7;font-size: 13px;height: 25px;line-height: 25px;padding-left: 5px;display: block;}
</style>
<div class="u_notice">
<ul>
<!--<li style="border:none;padding:0;"><span class="history_no">新通知</span></li>-->
<?php 
$typeArr = array(0,1,2,3);  
$stmp = $gtmp = array();
//unread notice
if(is_array($un_notice))
{   
	foreach ($un_notice as $type => $notices)
	{    
		if(in_array($type,$typeArr)){ 
			$idstr = array_shift($notices);
			$str = explode(',',$idstr);
			$j = 0;
			foreach($str as $ids)
			{
				if($ids)
				{
					$j++;
				}
			}
			if($type == 0)
			{
				$title = '系统通知'; 
				$link = 'javascript:void(0)';
			}  
			else
			{
				
				$title = $notices['content']['title'];
				$link = $notices['content']['page_link'];
			}
			?>
			<li style="font-weight:bold;"><?php echo $j;?>条<a  href="<?php echo $link;?>" title="点击查看" onclick="insertReadSMS('<?php echo $idstr;?>',<?php echo $type;?>);" ><?php echo $title;?></a></li>
		<?php 
		}
		else
		{ 
			if (is_array($notices))
			{
				foreach($notices as $id => $cons)
				{ 
					$title = $cons['content']['title'];
					$link = $cons['content']['page_link'];
					?>
					<li><?php echo $cons['content']['notify_time']?><a href="<?php echo $link?>" title="点击查看" onclick="insertReadSMS('<?php echo $id;?>',<?php echo $type;?>);" ><?php echo $title?></a></li>
				<?php 	 
				} 
			}
		} 
	}
	?>
	<li style="text-align:right;padding: 0;"><a href="javascript:void(0);" onclick="markAllSMS()" title="标记全部">全部标记为已读</a></li>  
	<?php
	
}
?>
<!--<li>-->
<!--暂无新通知...-->
<!--</li>-->
<!--<li style="border:none;padding:0;margin-top:5px;"><span class="history_no">历史通知</span></li>-->
<?php 
//read notices
if(is_array($notice))
{ 
	foreach($notice as $id => $cons)
	{ 
		foreach($cons as $k_id => $k_info)
		{ 	
			 
			$title = $k_info['content']['title'];
			$link = $k_info['content']['page_link']; 
			
		?>
		<li><span style="color:#9E9E9E;margin-right:6px;display:inline-block;"><?php echo $k_info['content']['notify_time']?></span><?php echo $title;?></li>
	<?php 	 
		}
	}
}

if(!$notice && !$un_notice){
?>
<li>暂无通知信息......</li>
<?php }?>
</ul>
</div>
 <?php echo $showpages;?>
