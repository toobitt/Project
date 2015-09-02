<li class="common-list-data clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['id']}">
	<div class="common-list-left">
        <div class="common-list-item common-paixu">
            <div class="common-list-cell">
                <a class="lb"  name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['id']}" title=""  /></a>
            </div>
        </div>
        <div class="common-list-item">
            <div class="common-list-cell">
			    <span id="name_{$v['id']}">{$v['id']}</span>
            </div>
        </div>
    </div>
	<div class="common-list-right">
	    <div class="common-list-item template-cz">
            <div class="common-list-cell">
                <a title="编辑" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">编辑</a>
				<a onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}">删除</a>
            </div>
        </div>
        <div class="common-list-item">
            <div class="common-list-cell">
                <span id="name_{$v['type_name']}">{$v['type_name']}</span>
            </div>
        </div>
         <div class="common-list-item">
            <div class="common-list-cell">
                <span id="name_{$v['domain_name']}">{$v['domain_name']}</span>
            </div>
        </div>
   </div>
   <div class="common-list-biaoti">
	    <div class="common-list-item template-biaoti biaoti-transition">
			   <div class="common-list-cell">
		          <span class="common-list-overflow special-biaoti-overflow"> <a class="shareslt" href="./run.php?mid={$_INPUT['mid']}&a=show&fid={$v['id']}&infrm=1">{$v['name']}{if $v['is_last']==0} >>{/if}</a></span>
            </div>  
	    </div>
   </div>
</li>