<li class="common-list-data clear" orderid="{$v['order_id']}"  order_id="{$v['order_id']}" _id="{$v['id']}" id="r_{$v['id']}" class="h"   name="{$v['id']}">
	<div class="common-list-left">
        <div class="common-list-item common-paixu">
            <div class="common-list-cell">
                <a class="lb"  name="alist[]" ><input type="checkbox"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  name="infolist[]"></a>
            </div>
        </div>
    </div>
    <div class="common-list-right">
      {if $v['pubinfo'][1]}
        <div class="common-list-item wd60">
            <div class="common-list-cell">
			    <span style="cursor:pointer;width:16px;height:16px;display:inline-block;{if $v['status']}background:url('{$RESOURCE_URL}bg-all.png') -21px -187px no-repeat;{else}background:url('{$RESOURCE_URL}bg-all.png') -2px -187px no-repeat;{/if}"></span>
            </div>
        </div>
        {/if}  
        <div class="common-list-item fbz overflow common-list-pub-overflow">
        	{if $v['column_id']}
				{code}
					$column_id = implode(',',$v['column_id']);
				{/code}
			<div title="发布至:{$column_id}" style="color:{if $v['expand_id']}#1FB34E{else}#888{/if};" class="common-list-pub-overflow">
				{$column_id}
			</div>
			{/if}
		</div>
        <!--
<div class="common-list-item wd60" style="overflow: visible;">
            <div class="common-list-cell">
	            <div title="操作" class="btn-box-cz">
	                <div class="btn-box-cz-menu">
				        <span onclick="hg_voteForm({$v['id']});" style="margin:4px 2px 0 0;" class="button_2">编辑</span>
						<span onclick="hg_delQuestionOption({$v['id']})" style="margin:4px 2px 0 0;" class="button_2">删除</span>
						<span onclick="hg_statePublish({$v['id']});" id="statePublish_{$v['id']}" style="margin:4px 2px 0 0;" class="button_2"><a href="./run.php?mid={$_INPUT['mid']}&a=recommend&id=${id}" onclick="return hg_ajax_post(this, '推荐', 0);">签发</a></span>
		            </div>
		       </div> 
           </div>
        </div>
-->
     <!--      <div class="common-list-item wd60">
            <div class="common-list-cell">
			    <span>{$v['option_num']}</span>
            </div>
        </div>
        
       <div class="common-list-item vote-xxs wd100">
            <div class="common-list-cell">
			    <span>{$v['min_option']}/{$v['max_option']}</span>
            </div>
        </div>-->
       
        <div class="common-list-item  ">
			<div class="common-switch-status audit" _status="{$v['status']}">
				{code}
					if($v['status']== 2){
						$audit = '已打回';
						$color = '#f8a6a6';
					}else if($v['status']== 1){
						$audit = '已审核';
						$color = '#17b202';
					}else{
						$audit = '待审核';
						$color = '#8ea8c8';
					}
				{/code}
		     <span _id="{$v['id']}" id="statusLabelOf{$v['id']}" _state="{$v['status']}" style="color:{$color};" data-noclick="true">{$audit}</span>
			</div>
		</div>
		
	 <!--  	<div class="common-list-item zt wd70">
						<div align="center" style="padding-top:4px;margin-left:-30px;">
							<div class="need-switch" title="{if !$v['is_open']}未启动{else}已启动{/if}" state="{$v['is_open']}" style="cursor:pointer;" vid="{$v['id']}"></div>
						</div>
						<p style="{if $v['status'] == 1} display:none;{/if} width: 40px;height: 16px;overflow: hidden;background: rgba(209,217,222,0.6);border-radius: 8px;position: absolute;top: 14px;"></p>
		</div>-->
		
		<div class="common-list-item m2o-switch" _status="{$v['is_open']}" style="position:relative;">
    		<div class="common-switch {if $v['is_open']}common-switch-on{/if}" style="bottom:0px;">
           		<div class="switch-item switch-left" data-number="0"></div>
           		<div class="switch-slide"></div>
           		<div class="switch-item switch-right" data-number="100"></div>
        	</div>
        	<span class="default-switch" style="{if $v['status'] == 1} display:none;{/if} width: 55px;height: 25px;overflow: hidden;background: rgba(209,217,222,0.8);border-radius: 15px;position: absolute;top: 7px;left:-10px;z-index: 9999" {if $v['status'] != 1} title="请先审核"{/if}></span>
    	</div>
    	
    	 <div class="common-list-item ">
            <div class="common-list-cell">
			    <span>{$v['total']}</span>
            </div>
        </div>
        
        <div class="common-list-item">
            <div class="common-list-cell">
			    <span>{$v['sort_name']}</span>
            </div>
        </div>
        
        {template:list/list_weight,asd,$v['weight']}
        <div class="common-list-item vote-jssj wd80">
            <div class="common-list-cell">
			    <a style="color:#7B7B7B;" {if $v['end_time']}title="{$v['end_time']}" {/if}><span>{if !$v['end_time_flag']} {if !$v['end_time']}<span style="color:#17b202;">永久有效</span>{else}<font style="color:red;">已过期</font>{/if}{else}{$v['end_time']}{/if}</span></a>
            </div>
        </div>
      <!--  <div class="common-list-item wd60">
            <div class="common-list-cell">
			    <div class="need-switch" title="{if $v['status']}已审核{else}待审核{/if}" state="{if $v['status']}1{else}0{/if}" style="cursor:pointer;" vid="{$v['id']}"></div>
            </div>
        </div> --> 
        <div class="common-list-item vote-tjr wd120">
            <div class="common-list-cell">
			    <span class="common-user">{$v['user_name']}</span>
			    <span class="common-time">{$v['create_time']}</span>
            </div>
        </div>
   </div>
   <div class="common-list-biaoti">
	    <div class="common-list-item vote-biaoti biaoti-transition">
			   <div class="common-list-cell">
			   <a class="common-title" href="javascript:void(0);" _href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1" onclick="hg_getQestionOption({$v['id']});">
			   	<img class="biaoti-img" src="{$v['pictures_info_url']}">
                <span style="max-width: 200px;" class="common-list-overflow vote-biaoti-overflow m2o-common-title" title="{$v['title']}" _onclick="hg_getQestionOption({$v['id']});">{$v['title']}</span>
               </a>
            </div>  
	    </div>
   </div>
   <div class="common-list-i" onclick="hg_show_opration_info({$v['id']});"></div>
</li>
