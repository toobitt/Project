<li order_id="{$v['order_id']}" _id="{$v[$primary_key]}" class="common-list-data clear"  id="r_{$v[$primary_key]}" name="{$v['order_id']}" >
   <div class="common-list-left ">
	  <div class="common-list-item paixu">
	     <a class="lb" name="alist[]">
		   <input type="checkbox" name="infolist[]" value="{$v[$primary_key]}" title="{$v[$primary_key]}" />
	     </a>
	  </div>
   </div>
   <div class="common-list-right">
   		<div class="common-list-item wd100">
   			{code}
	      	    /*$href = urlencode("./run.php?mid=". $_INPUT['mid'] ."&a=search_cell&site_id=".$cell_list[0]['site_id']."&page_id=".$cell_list[0]['page_id']."&page_data_id=".$cell_list[0]['page_data_id']."&content_type=".$k);*/
	      	    $ext = urlencode("layout_id=" . $v['id']);
	      	    $gmid = $_INPUT['mid'];
		    {/code}
   			<a href="magic/main.php?gmid={$gmid}&ext={$ext}&bs=b" target="_blank">预设</a>
   			<a href= "magic/magic.php?a=preview&gmid={$_INPUT['mid']}&layout_id={$v['id']}&bs=b" target="_blank">预览</a>
   		</div>
		<div class="common-list-item wd60 news-zhuangtai open-close">
			<div class="common-switch-status">
		     <span _id="{$v['id']}" _state="{$v['status']}" id="statusLabelOf{$v['id']}" style="color:{$_configs['status_color'][$v['status']]};">{$v['state']}</span>
			</div>
		</div>
		<div class="common-list-item wd100 news-ren open-close">
		     <span class="news-name">{$v['user_name']}</span>
		     <span class="news-time">{$v['create_time_show']}</span>
		</div>
	</div>
   <div class="common-list-i" onclick="hg_show_opration_info({$v['id']});"></div>
   {code}
		$href = './run.php?mid='.$_INPUT['mid'].'&a=form&id='.$v['id'].'&infrm=1';
		$classname = '';
	{/code}
   <div class="common-list-biaoti min-wd">
	    <div class="common-list-item biaoti-transition">
	      <div class="common-list-overflow max-wd">
	      	<a href="{$href}"  target="nodeFrame">
			{code}
				$v['indexpic'] = $v['indexpic'] ? hg_fetchimgurl($v['indexpic'],0, 0) : '';
			{/code}
		    {if $v['indexpic']}
		        <img  _src="{$v['indexpic']}"  class="img_{$v['id']} biaoti-img"/> 
		    {/if}
		   		<span id="title_{$v['id']}" class="m2o-common-title {$classname}">{$v['title']}</span>
			</a>
		   </div>
		</div>
   </div>
</li>
