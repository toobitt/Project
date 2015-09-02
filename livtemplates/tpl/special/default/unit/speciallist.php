<li order_id="{$v['order_id']}" _id="{$v['id']}" class="common-list-data clear"  id="r_{$v['id']}" name="{$v['id']}">
   <div class="common-list-left ">
	  <div class="common-list-item paixu">
	     <a class="lb" name="alist[]">
		   <input type="checkbox" name="infolist[]" value="{$v[$primary_key]}" title="{$v[$primary_key]}" />
	     </a>
	  </div>
   </div>
   <div class="common-list-right">
		<div class="common-list-item common-list-pub-overflow">
		  <div class="common-list-pub-overflow">
		   {if ($v['pub'])}
		    {foreach $v['pub'] as $kk => $vv} 
		    	{code}$cu = $vv;{/code}
		    	{if ($v['pub_url'][$kk])}
		    		{if (is_numeric($v['pub_url'][$kk]))}
		    		<a href="./redirect.php?id={$v['pub_url'][$kk]}" target="_blank"><span class="common-list-pub">{$cu}</span></a>
		    		{else}
						<a href="{$v['pub_url'][$kk]}" target="_blank"><span class="common-list-pub">{$cu}</span></a>   			
		    		{/if}			
		    	{else}
		    		<span class="common-list-pre-pub">{$cu}</span>
		    	 {/if}  	
			{/foreach}
          {/if}
          </div>
		</div>
	<!-- 	<div class="common-list-item wd100" style="width:160px;max-width:none;">
			<span><a href="./run.php?mid={$_INPUT['mid']}&a=form&id=${id}&infrm=1" target="formwin" need-back>属性</a></span>
			<span><a href="./run.php?&a=relate_module_show&app_uniq=special&mod_uniq=special_content&mod_a=show&speid={$v['id']}&infrm=1" target="formwin" need-back>内容</a></span>
			<span><a href="./run.php?mid={$_INPUT['mid']}&a=built_template_form&id={$v['id']}" target="formwin" need-back>模板</a></span>
		</div> -->
		<div class="common-list-item wd80 overflow">
		     <span>{$v['sort_name']}</span>
		</div>
		{template:list/list_weight,asd,$v['weight']}
	
		<div class="common-list-item wd60">
			<div class="common-switch-status">
		     <span _id="{$v['id']}" _state="{$v['state']}" id="statusLabelOf{$v['id']}" style="color:{$list_setting['status_color'][$v['state']]};">{$v['status']}</span>
			</div>
		</div>
		<div class="common-list-item wd100">
		     <span class="common-name">{$v['user_name']}</span>
		     <span class="common-time">{$v['create_time_show']}</span>
		</div>
	</div>
   <div class="common-list-i" onclick="hg_show_opration_info({$v['id']});"></div>
   <div class="common-list-biaoti min-wd" style="padding-top: 2px;">
	    <div class="common-list-item biaoti-transition">
	      <div class="min-wd">
	      	<a title="{$v['name']}" href="./run.php?&a=relate_module_show&app_uniq=special&mod_uniq=special_content&mod_a=show&speid={$v['id']}&infrm=1" target="formwin">
		         {code}
		       	    $picinfo =$v['pic'];
		       	    $url = $picinfo['host'].$picinfo['dir'].'40x30/'.$picinfo['filepath'].$picinfo['filename'];
		       	 {/code}	
		       	 {if $picinfo}
					<img src="{$url}" id="img_{$v['id']}" class="biaoti-img" />
				 {/if}
			</a>
			<div class="info-box">
		   
		    	<div id="title_{$v['id']}">
		    	 <a class="m2o-common-title overflow" title="{$v['name']}" href="./run.php?&a=relate_module_show&app_uniq=special&mod_uniq=special_content&mod_a=show&speid={$v['id']}&infrm=1" target="formwin">
		    	
		    	{$v['name']}
		    	 </a>
		    	</div>
		   <div>
				 <span><a class="fast-nav" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1" target="formwin" need-back>属性</a></span>
				<span><a class="fast-nav"  href="./run.php?&a=relate_module_show&app_uniq=special&mod_uniq=special_content&mod_a=show&speid={$v['id']}&infrm=1" target="formwin" need-back>内容</a></span>
				<span><a class="fast-nav"  href="./run.php?mid={$_INPUT['mid']}&a=built_template_form&id={$v['id']}" target="formwin" need-back>模板</a></span>
			</div>
			</div>
		   </div>
		    
		</div>
   </div>
</li>