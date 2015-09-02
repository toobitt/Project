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
        <div class="common-list-item wd280 common-list-overflow">
                <span>{$v['seekhelp_title']}</span>
        </div>
        <div class="common-list-item wd60" style="cursor:pointer;">
                <span  onclick="change_status({$v['id']},{$v['status']});"   id="audit_{$v['id']}" {if $v['status']==1}style="color:green;"{elseif $v['status']==2}style="color:red"{/if}>{$v['status_name']}</span>
        </div>
        <div class="common-list-item wd120">
            <div class="common-list-cell">
                 <span class="common-user">{$v['member_name']}</span>
                 <span class="common-time">{$v['format_create_time']}</span>
            </div>
       </div>
    </div>
    <div class="common-list-biaoti" href="run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">
        <div class="common-list-item biaoti-transition">
                <a>
	                <span class="common-list-overflow max-wd fz14" style="display:inline-block;">{if $v['banword']}<font color="red">{$v['content']}</font>{else} {$v['content']} {/if}</span>
                </a>
        </div>
    </div>
</li>