<li class="common-list-data clear" _id="{$v['id']}" id="r_{$v['id']}" order_id="{$v['order_id']}"  name="{$v['id']}" >
    <div class="common-list-left">
        <div class="common-list-item paixu">
            <div class="common-list-cell">
                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
            </div>
        </div>
    </div>
    <div class="common-list-right">
    	<div class="common-list-item wd50">
            <div class="common-list-cell">
                <a class="btn-box" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1&ui_id={$_INPUT['ui_id']}"><em class="b2"></em></a>
            </div>
        </div>
        <div class="common-list-item wd50">
            <div class="common-list-cell">
                 <a class="btn-box" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"><em class="b3"></em></a>
            </div>
        </div>
       
        <div class="common-list-item wd50 overflow">
                <span>{$v['value_id']}</span>
        </div>
       
        <div class="common-list-item wd50 overflow">
                <span>{$v['relate_id']}</span>
        </div>
        
        <div class="common-list-item wd100 overflow">
                <span>{$v['uniqueid']}</span>
        </div>
        
        <div class="common-list-item wd100 overflow">
                <span>{$v['group_name']}</span>
        </div>
        
        <div class="common-list-item wd100 overflow">
                <span>{$v['attr_name']}</span>
        </div>
        
        <div class="common-list-item wd100 overflow">
                <span>{$v['attr_type_name']}</span>
        </div>
        
        <div class="common-list-item wd100 overflow">
                <span>{$v['ui_name']}</span>
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