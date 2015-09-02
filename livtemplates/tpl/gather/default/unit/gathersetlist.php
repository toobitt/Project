<li class="common-list-data clear" _id="{$v['id']}" id="r_{$v['id']}" name="{$v['id']}" order_id="{$v['order_id']}">
    <div class="common-list-left">
        <div class="common-list-item paixu">
            <div class="common-list-cell">
                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
            </div>
        </div>
    </div>
    <div class="common-list-right">
    	<div class="common-list-item wd60">
            <div class="common-list-cell">
                <a class="btn-box" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1"><em class="b2"></em></a>
            </div>
        </div>
        <div class="common-list-item wd60">
            <div class="common-list-cell">
                 <a class="btn-box" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"><em class="b3"></em></a>
            </div>
        </div>
        <div class="common-list-item wd100"> 
        	<div class="common-list-cell">
        		<span>{$v['sort_name']}</span>
        	</div>
        </div>
        <div class="common-list-item wd100"> 
        	<div class="common-list-cell">
        		<span  {if $v['is_relay']} style="color: green" {/if}>{if $v['is_relay']}是{else}否{/if}</span>
        	</div>
        </div>
        <div class="common-list-item wd100" style="cursor:pointer;">
                <span  onclick="change_status({$v['id']},{$v['is_open']});"   id="audit_{$v['id']}" {if $v['is_open']==1}style="color:green;"{/if}>{$v['open_status']}</span>
        </div>
        <div class="common-list-item wd200">
            <div class="common-list-cell">
                 <span class="common-user">{$v['user_name']}</span>
                 <span class="common-time">{$v['create_time']}</span>
            </div>
       </div>
    </div>
    <div class="common-list-biaoti" >
        <div class="common-list-item biaoti-transition">
        	<a href="run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1" target="nodeFrame">
	        	<span class="common-list-overflow max-wd fz14 m2o-common-title" style="display:inline-block;">{$v['app_name']}</span>
            </a>
        </div>
    </div>
</li>