<li class="common-list-data clear" _id="{$v['id']}" id="r_{$v['id']}" name="{$v['id']}" >
    <div class="common-list-left">
        <div class="common-list-item paixu">
            <div class="common-list-cell">
                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
            </div>
        </div>
        <div class="vod-fengmian common-list-item wd60">
                <img _src="{if $v['avatar_url']}{$v['avatar_url']}{else}{$RESOURCE_URL}avatar.jpg{/if}" width="40" height="30" />
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
        <div class="common-list-item wd80 overflow">
                <span>{$v['company']}</span>
        </div>
        <div class="common-list-item wd80 overflow">
                <span>{$v['job']}</span>
        </div>
        <div class="common-list-item wd100 overflow">
                <span>{$v['telephone']}</span>
        </div>
        <div class="common-list-item wd100 overflow">
                <span>{$v['email']}</span>
        </div>
        
        <div class="common-list-item wd60">
                <span>{if $v['is_sign']}是{else}否{/if}</span>
        </div>
        
        <div class="common-list-item wd80">
                <span>{$v['guest_type_text']}</span>
        </div>
        
        <div class="common-list-item wd60">
                <span>{if $v['exchange_num']}<a href="run.php?mid={$_INPUT['mid']}&a=get_exchanged_members&id={$v['id']}&infrm=1"><font color="blue">{$v['exchange_num']}</font></a>{else}<font color="red">无</font>{/if}</span>
        </div>
    
        <div class="common-list-item wd120">
            <div class="common-list-cell">
                 <span class="common-user">{$v['user_name']}</span>
                 <span class="common-time">{$v['create_time']}</span>
            </div>
       </div>
    </div>
    <div class="common-list-biaoti" href="run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">
        <div class="common-list-item biaoti-transition">
                <a>
	                <span class="common-list-overflow max-wd fz14 m2o-common-title" style="display:inline-block;">{$v['name']}</span>
                </a>
        </div>
    </div>
</li>