<li order_id="{$v['order_id']}" _id="{$v[$primary_key]}" class="common-list-data clear"  
	id="r_{$v[$primary_key]}" name="{$v['order_id']}" >
   <div class="common-list-left ">
	  <div class="common-list-item paixu">
	     <a class="lb" name="alist[]">
		   <input type="checkbox" name="infolist[]" value="{$v[$primary_key]}" title="{$v[$primary_key]}" />
	     </a>
	  </div>
   </div>
   <div class="common-list-right">
		<div class="common-list-item common-list-pub-overflow news-fabu open-close">
		  <div class="common-list-pub-overflow">
		    		<span class="common-list-pre-pub">{$cu}</span>
          </div>
		</div>
		<div class="common-list-item wd100 overflow news-zhuanfa open-close">
			<span>  {if $v['set_id']}
						{foreach $v['set_id'] as $key=>$val}
							{if $v['set_url']}
								{code} $set_url_keys = array_keys($v['set_url']); {/code}
								{if in_array($key, $set_url_keys)}
								<span class = "common-list-pub">{$val}</span>
								{else}
								<span class="common-list-pre-pub">{$val}</span>
								{/if}
							{else}
								<span class="common-list-pre-pub">{$val}</span>
							{/if}
						{/foreach}
					{/if}
			</span>
		</div>
		<div class="common-list-item wd80 overflow news-fenlei open-close">
		     <span>{$v['sort_name']}</span>
		</div>
		
		<div class="common-list-item wd80 news-zhuangtai open-close">
		    	<span  id="audit_{$v['id']}" {if $v['status']==1}style="color:green;"{elseif $v['status']==2}style="color:red"{/if}  onclick="change_status({$v['id']},{$v['status']});">{$v['status_name']}</span>
		</div>

		<div class="common-list-item wd200 news-ren open-close">
		     <span class="news-name">{$v['user_name']}</span>
		     <span class="news-time">{$v['create_time']}</span>
		</div>
	</div>
   <div class="common-list-i" onclick="hg_show_opration_info({$v['id']});"></div>
   <div class="common-list-biaoti min-wd">
	    <div class="common-list-item biaoti-transition">
	      <div class="common-list-overflow max-wd">
	      	<a href="run.php?mid={$_INPUT['mid']}&a=form&infrm=1&id={$v['id']}"  target="nodeFrame">
		    {if $v['indexpic_url']}
		        <img  _src="{$v['indexpic_url']}"  class="img_{$v['id']} biaoti-img"/> 
		    {/if}
		   		<span id="title_{$v['id']}" class="m2o-common-title {$classname}">{$v['title']}</span>
			</a>
		   </div>
		</div>
   </div>
</li>
