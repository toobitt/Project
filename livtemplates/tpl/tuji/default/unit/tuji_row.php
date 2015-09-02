<li order_id="{$v['order_id']}" class="common-list-data clear" _id="{$v['id']}"  id="r_{$v['id']}" name="{$v['id']}" >
   <div class="common-list-left ">
	  <div class="common-list-item paixu">
	     <a class="lb" name="alist[]">
		   <input type="checkbox" name="infolist[]" value="{$v[$primary_key]}" title="{$v[$primary_key]}" />
	     </a>
	  </div>
	  <!--  <div class="common-list-item open-close wd150">
	       <div class="rotate-box rotate-transform-{code}echo rand(1, 4);{/code}">
		     <div class="rotate-item rotate-item-1">
			   <div class="rotate-inner">{if ($v['img_src'][0])}<img class="rotate-img" {if $v['fetch_one_li_model']}src{else}_src{/if}="{$v['img_src'][0]}" />{/if}</div>
		    </div>
		   <div class="rotate-item rotate-item-2">
			  <div class="rotate-inner">{if ($v['img_src'][1])}<img class="rotate-img" {if $v['fetch_one_li_model']}src{else}_src{/if}="{$v['img_src'][1]}" />{/if}</div>
		   </div>
		  <div class="rotate-item rotate-item-3"></div>
	</div>
	  </div>-->
   </div>
   <div class="common-list-right">
		<div class="common-list-item common-list-pub-overflow tuji-fabu open-close">
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
		<div class="common-list-item wd80 overflow tuji-fenlei open-close">
		     <span>{$v['sort_name']}</span>
		</div>
		{template:list/list_weight,tuji-quanzhong,$v['weight']}
		<div class="common-list-item wd60 tuji-zhuangtai open-close">
			<div class="common-switch-status">
		     <span _id="{$v['id']}" _state="{$v['status_display']}" id="statusLabelOf{$v['id']}" style="color:{$list_setting['status_color'][$v['status_display']]};">{$v['status']}</span>
			</div>
		</div>
		<div class="common-list-item wd60 tuji-tuisong open-close">
        	<span class="tuji-push">{$v['user_name']}</span>
        </div>
		<div class="common-list-item wd100 tuji-ren open-close">
		     <span class="tuji-name">{$v['user_name']}</span>
		     <span class="tuji-time">{$v['create_time']}</span>
		</div>
	</div>
   <div class="common-list-i" onclick="hg_show_opration_info({$v['id']});"></div>
   <div class="common-list-biaoti">
	    <div class="common-list-item biaoti-transition">
	        <a  class="common-list-overflow max-wd"  href="run.php?mid={$_INPUT['mid']}&a=tuji_form&infrm=1&id={$v['id']}" target="formwin">
	        {if $v['cover_img']}<img  _src="{$v['cover_img']}"  class="biaoti-img"/> {/if}
	        <span class="m2o-common-title">{$v['title']}</span>
	        </a>
	        {if ($v['img_count'] > 0)}<span class="tuji-total">({$v['img_count']})</span>{/if}
		</div>
   </div>
</li>