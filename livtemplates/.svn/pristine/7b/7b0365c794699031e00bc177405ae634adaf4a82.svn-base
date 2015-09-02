{code}
//hg_pre($v);exit; 
{/code}
<li order_id="{$v['order_id']}" _id="{$v[$primary_key]}" class="common-list-data clear"  id="r_{$v[$primary_key]}" name="{$v['order_id']}" >
   <div class="common-list-left ">
	  <div class="common-list-item paixu">
	     <a class="lb" name="alist[]">
		   <input type="checkbox" name="infolist[]" value="{$v[$primary_key]}" title="{$v[$primary_key]}" />
	     </a>
	  </div>
   </div>
   <div class="common-list-right">
		<div class="common-list-item common-list-pub-overflow news-fabu open-close" style="width:400px;">
            <span>{$v['url']}</span>
		</div>

	
		<div class="common-list-item wd60 news-zhuangtai open-close">
			<div class="common-switch-status">
		     <span _id="{$v['id']}" _state="{$v['state']}" id="statusLabelOf{$v['id']}" style="color:{$_configs['status_color'][$v['state']]};">{$v['is_publish']}</span>
			</div>
		</div>
		<!--  <div class="common-list-item wd60 news-pinglun open-close">
		     <span>{$v['comm_num']}/{$v['click_num']}</span>
		</div>-->
		<div class="common-list-item wd100 news-ren open-close">
		     <span class="news-name">{$v['create_user']}</span>
		     <span class="news-time">{$v['create_time']}</span>
		</div>
	</div>
   <div class="common-list-i" onclick="hg_show_opration_info({$v['id']});"></div>
   {code}
	if(!$v['outlink']) {
		$href = './run.php?mid='.$_INPUT['mid'].'&a=form&id='.$v['id'].'&infrm=1';
		$classname = '';
	}
	else {
		$href = './run.php?mid='.$_INPUT['mid'].'&a=form_outerlink&id='.$v['id'];
		/*$classname = 'out-color';*/
		$classanme = '';
 	}
	{/code}
   <div class="common-list-biaoti min-wd">
	    <div class="common-list-item biaoti-transition">
	      <div class="common-list-overflow max-wd">
	      	
		    {if $v['indexpic_url']}
		        <img  _src="{$v['indexpic_url']}"  class="img_{$v['id']} biaoti-img"/> 
		    {/if}
		   		<span id="title_{$v['id']}" class="m2o-common-title {$classname}">{$v['title']}</span>
		   		{if $v['outlink']}
		   		<a class="news-outer" title="外链"></a>
		   		{/if}
			
		   </div>
		</div>
   </div>
</li>
