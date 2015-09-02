<li class="common-list-data public-list clear"  id="r_{$v['id']}" _id="{$v['id']}"    name="{$v['id']}"   orderid="{$v['id']}">
	<div class="common-list-left">
        <div class="common-list-item paixu">
            <div class="common-list-cell">
                <a class="lb"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}"  /></a>
            </div>
        </div>
    </div>
    <div class="common-list-right">
    	<div class="common-list-item wd80">
            <div class="common-list-cell">
                <span id="name_{$v['site_name']}">{$v['site_name']}</span>
            </div>
        </div>
        <div class="common-list-item wd80">
            <div class="common-list-cell">
            	{if $v['title']!='专题'&&$v['title']!='栏目'}
			   <a onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"><i class="hg_icons del_icon"></i></a>
            	{/if}
            </div>
        </div>
        <div class="common-list-item wd80">
            <div class="common-list-cell">
                <span id="name_{$v['has_child']}">{$v['has_child']}</span>
            </div>
        </div>
        <div class="common-list-item wd80">
            <div class="common-list-cell">
                <span id="name_{$v['has_content']}">{$v['has_content']}</span>
            </div>
        </div>
        <div class="common-list-item wd120">
            <div class="common-list-cell">
			   <span class="common-user">{$v['user_name']}</span>
			   <span class="common-time">{$v['create_time']}</span>
            </div>
        </div>
   </div>
   <div class="common-list-biaoti">
	    <div class="common-list-item special-biaoti biaoti-transition">
			<div class="common-list-cell">
	        	<a class="common-list-overflow special-biaoti-overflow" id="name_{$v['title']}" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">{$v['title']}</a>
            	<input type="hidden" name="id" value="{$v['id']}" />
            </div>  
	    </div>
   </div>
</li>