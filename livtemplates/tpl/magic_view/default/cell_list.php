{code}$list = $cell_list[0]['set_type'];{/code}{template:head}{css:common/common_list}{js:publishsys/init_create_page}{code}$res_path = './res/magic_view/images/';{/code}<div class="common-list-content" style="min-height:auto;min-width:auto;"><form method="post" action="" name="listform" class="common-list-form"><ul class="common-list news-list">	<li class="common-list-head public-list-head clear">		<div class="common-list-left">			<div class="common-list-item paixu open-close">			</div>		</div>                            <div class="common-list-right">                                                            </div>                            <div class="common-list-biaoti">						        <div class="common-list-item">页面</div>					        </div>	</li></ul><ul class="page-list common-list public-list">	{if !$list}    	<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>		<script>hg_error_html('p',1);</script>    {else}		{foreach $list as $k => $v} 		<li class="common-list-data clear"> 			<div class="common-list-left ">			  <div class="common-list-item paixu">			     <a class="lb" name="alist[]">				  			     </a>			  </div>		   </div>		   <div class="common-list-biaoti min-wd">	    	<div class="common-list-item biaoti-transition">		      <div class="common-list-overflow max-wd">		      	<!-- <a href="magic_view.php" target="_blank" _href="./run.php?mid={$_INPUT['mid']}&a=search_cell&site_id={$cell_list[0]['site_id']}&page_id={$cell_list[0]['page_id']}&page_data_id={$cell_list[0]['page_data_id']}&content_type={$k}"> -->		      	{code}		      	    /*$href = urlencode("./run.php?mid=". $_INPUT['mid'] ."&a=search_cell&site_id=".$cell_list[0]['site_id']."&page_id=".$cell_list[0]['page_id']."&page_data_id=".$cell_list[0]['page_data_id']."&content_type=".$k);*/		      	    $bs = $cell_list[0]['template_id'] ? 'k' : 'm';		      	    $ext = urlencode("site_id=".$cell_list[0]['site_id']."&page_id=".$cell_list[0]['page_id']."&page_data_id=".$cell_list[0]['page_data_id']."&content_type=".$k."&template_id=" . $cell_list[0]['template_id']);		      	    $gmid = $_INPUT['mid'];		      	{/code}		      	<a href="magic/main.php?gmid={$gmid}&ext={$ext}&bs={$bs}" target="_blank">     			{$v}     			</a>			   </div>			</div>	   	</div>     				</li>		{/foreach} 	{/if}</ul><ul class="common-list public-list">	<li class="common-list-bottom clear">		<div class="common-list-left">		</div>		{$pagelink}	</li></ul></form></div></body></html>        	