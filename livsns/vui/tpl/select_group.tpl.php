<?php
/*$Id:$*/
?>
<div id="pub_to_g" class="lightbox" style="top: 40%; left: 30%;display:none;">
	<div class="lightbox_top"></div>
	<div class="lightbox_middle">
		<h3><span id="pub_to_g_btn" style="float:right;padding-right:8px;cursor:pointer;" onclick="cancle_choice();">X</span><?php echo $this->lang['select_pub_groups']?>(您最多能够选择 <strong><?php echo PUBLISH_TO_MULTI_GROUPS;?></strong>个讨论区)</h3>
		<div id="sel_results"><b id="results_first" style="font-size:12px;padding-left:5px;"><?php echo $this->lang['selected_groups']?>:&nbsp;&nbsp;</b><span id="sel_re_span"></span></div>
		<div class="text hide_groups" id="p_t_gs_div">
			<div id="g_loading" class="loading">
	        	<div>
	        		<center>
	        			<img src="./res/img/loading.gif"/>
	        			正在获取数据 ...
	        		</center>
	        	</div>
	        </div>
		</div> 
	</div>
	<div class="lightbox_bottom"></div>
</div>