<li order_id="{$v['order_id']}" _id="{$v[$primary_key]}" class="common-list-data clear"  id="r_{$v[$primary_key]}" name="{$v['order_id']}" >
   <div class="common-list-left ">
	  <div class="common-list-item paixu">
	     <a class="lb" name="alist[]">
		   <input type="checkbox" name="infolist[]" value="{$v[$primary_key]}" title="{$v[$primary_key]}" />
	     </a>
	  </div>
   </div>
   <div class="common-list-right">

		<div class="common-list-item wd100 ">
		     <a class="download-record" target="formwin"  href="./run.php?a=relate_module_show&app_uniq=template_store&mod_uniq=consumption&mod_a=show&template_id={$v['id']}&infrm=1
">{$v['record']}</a>
		</div>
		<div class="common-list-item wd100 overflow news-fenlei open-close">
		     <span>{$v['sort_name']}</span>
		</div>
		<!-- <div class="common-list-item wd70">
			<span>{$v['price']}</span>
		</div> -->
		{template:list/list_weight,asd,$v['weight']}
	
		<div class="common-list-item wd60 news-zhuangtai open-close">
			<div class="common-switch-status">
		     <span _id="{$v['id']}" _state="{$v['status']}" id="statusLabelOf{$v['id']}" style="color:{$_configs['status_color'][$v['status']]};">{$_configs['template_status'][$v['status']]}</span>
			</div>
		</div>
		<!--  <div class="common-list-item wd60 news-pinglun open-close">
		     <span>{$v['comm_num']}/{$v['click_num']}</span>
		</div>-->
		<div class="common-list-item wd100 news-ren open-close">
		     <span class="news-name">{$v['user_name']}</span>
		     <span class="news-time">{code}echo date('Y-m-d h:i',$v['create_time']){/code}</span>
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
	      	<a href="{$href}"  target="formwin">
		    {if $v['index_pic']}
		        <img  _src="{code} echo hg_bulid_img($v['index_pic'], '40', '30');{/code}"  class="img_{$v['id']} biaoti-img"/> 
		    {/if}
		   		<span id="title_{$v['id']}" class="m2o-common-title {$classname}">{$v['title']}</span>
			</a>
		   </div>
		</div>
   </div>
</li>