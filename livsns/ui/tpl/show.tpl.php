<?php
/*
 * $Id: show.tpl.php 4060 2011-06-08 09:34:32Z repheal $
 */
?>
<?php include hg_load_template('head');?> 
<style type="text/css">
.comment .middle{width:493px;}
</style>
<script type="text/javascript">
setFous = function(sid,uid)
{
	if(!uid)
	{
		location.href = "login.php";
	}
	$("#comm_text_"+sid). focus();
}
</script>
<?php 
	$text = hg_verify($statusline['text']);
	$text_show = '：'.($statusline['text']?$statusline['text']:$this->lang['forward_null']);
	if($statusline['reply_status_id'])
	{
		$forward_show = '//@'.$statusline['user']['username'].' '.$text_show;
		$title = $this->lang['forward_one'].$statusline['retweeted_status']['text'];
		$uid = $statusline['reply_user_id'];
	}
	else
	{
		$forward_show = '';
		$title = $this->lang['forward_one'].$statusline['text'];
		$uid = $statusline['member_id'];
	}
?>
<div class="content clear" id="mid_<?php echo $statusline['id'];?>">
	
	<div class="content-left">
		<p class="weibo_bor1"></p>
		<div style="background:#fff;height:auto;border-left:1px solid #CCCCCC;border-right:1px solid #CCCCCC;" >
			<ul>
				<li class="clear liv_commemt" > 	
					<div style="float:right;width:565px;">	
						<p class="subject">
							<?php echo  hg_verify($statusline['text']);?>
						</p>
						<?php  
							$value = array();
							$value = $statusline; 
							$transmit_info = array();
							$transmit_info = $statusline['retweeted_status'];
							?>
							<?php include hg_load_template("statusline_content");?>
						<div class="speak clear"> 
							<div class="hidden" id="t_<?php echo $statusline['id'];?>"><?php echo hg_verify($title);?></div>
							<div class="hidden" id="f_<?php echo $statusline['id'];?>"><?php echo $forward_show;?></div>
							<span style="position: relative;">
								<a onclick="OpenForward('<?php echo $statusline['id']?>','<?php echo $uid;?>')" href="javascript:void(0);">转发(<?php echo $statusline['transmit_count'] + $statusline['reply_count']; ?>)</a> 
					            <a id="fa<?php echo $statusline['id'];?>" onclick="favorites('<?php echo $statusline['id']?>','<?php echo $this->user['id'];?>')" href="javascript:void(0);">收藏</a>
					            <a onclick="setFous(<?php echo $status_id;?>,<?php echo $this->user['id'];?>)" href="javascript:void(0);">评论(<span id="coms_<?php echo $statusline['id']?>"><?php echo $statusline['comment_count']?></span>)</a>
						    </span>
						    <strong><?php echo hg_get_date($statusline['create_at']);?></strong>
							<strong>来自 <?php echo $statusline['source'];?></strong> 
							<?php if($this->user['id']){?><strong><a href="javascript:void(0);" onclick="report_play(<?php echo $statusline['id'];?>,<?php echo $statusline['user']['id'];?>);"><?php echo $this->lang['report'];?></a></strong><?php }?>
							
							<div style="display:none;" id="cons_<?php echo $statusline['id'];?>_<?php echo $statusline['user']['id'];?>">
							<?php echo  hg_verify($statusline['text']);?>
							<?php include hg_load_template("statusline_content");?></div>
							<div style="display:none;" id="ava_<?php echo $statusline['id'];?>_<?php echo $statusline['user']['id'];?>"><?php echo $statusline['user']['small_avatar'];?></div>
							<div style="display:none;" id="user_<?php echo $statusline['id'];?>_<?php echo $statusline['user']['id'];?>"><?php echo $statusline['user']['username'];?></div>
							<div style="display:none;" id="url_<?php echo $statusline['id'];?>_<?php echo $statusline['user']['id'];?>"><?php echo SNS_MBLOG.'show.php?id='.$statusline['id'];?></div>
							<div style="display:none;" id="type_<?php echo $statusline['id'];?>_<?php echo $statusline['user']['id'];?>">3</div>
						
						</div>
					</div>
						<input type="hidden" value="0" id="cnt_comm_<?php echo $statusline['id']?>" name="count_comm" /> 
					<div style="width: 580px;" class="comment_list " id="comment_list_<?php echo $statusline['id'];?>">				
					</div>
				</li>
				<li class="clear liv_commemt" >
				<?php include hg_load_template('show_comment_detail');?>
				</li> 
			</ul>
		</div> 
		
		<?php echo $showpages;?>	
	</div>

	<div class="content-right">
		<div class="pad-all">
		<?php $user_info = array();$user_info = $statusline['user'];$topic = $status->getTopic();?>
		<?php include hg_load_template("userImage");?>
		<?php include hg_load_template('userInfo')?>
		</div>
	</div>
	</div>
 
<?php include hg_load_template('forward');?>
<div class="pop" id="pop">
<span style="font-size:12px;color:#0082CB;width:auto;" onclick="closevideo()">关闭</span>
<div id="pop_s"></div>
</div>	
<?php include hg_load_template("foot")?>