<?php
$notice = array();
$notice = $notice_arr;
$typeArr = array(0,1,2,3);  
$stmp = $gtmp = array();
if(!empty($notice))
{   
	$i=1;
	foreach ($notice as $type => $notices)
	{  
		if($i<=5){
			 
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
				<li><?php echo $j;?>条<a  href="<?php echo $link;?>" title="点击查看" onclick="insertReadSMS('<?php echo $idstr;?>',<?php echo $type;?>);" ><?php echo $title;?></a></li>
			<?php 
			}
			else
			{ 
				foreach($notices as $id => $cons)
				{ 
					$title = $cons['content']['title'];
					$link = $cons['content']['page_link'];
					?>
					<li ><a  href="<?php echo $link?>" title="点击查看" onclick="insertReadSMS('<?php echo $id;?>',<?php echo $type;?>);" ><?php echo $title?></a></li>
				<?php 	 
				} 
			}
		}
		$i++;  
	}
	?>
	<li style="text-align:right;padding: 0;"><a href="javascript:void(0);" onclick="markAllSMS()" title="标记全部">全部标记为已读</a></li>  
	<?php
	
}

?>