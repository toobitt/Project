<li class="common-list-data clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['id']}">
	<div class="common-list-left">
        <div class="common-list-item common-paixu">
            <div class="common-list-cell">
                <a class="lb"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}"  /></a>
            </div>
        </div>
    </div>
	<div class="common-list-right">
	    <div class="common-list-item wd60">
            <div class="common-list-cell" >
                <a title="编辑" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">编辑</a>
                <!--<a title="删除" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}&routeid={$v['routeid']}&goon=1&infrm=1">删除</a> -->
           		<a title="删除" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}&routeid={$v['routeid']}"  onclick="return hg_ajax_post(this, '删除', 1);">删除</a>
            </div>
        </div>
        <div class="common-list-item wd60">
            <div class="common-list-cell">
                <span id="audit_{$v['id']}">{$v['status_name']}</span>
            </div>
        </div>
        <div class="common-list-item wd80">
            <div class="common-list-cell">
                <span id="name_{$v['city_name']}">{$v['city_name']}</span>
            </div>
        </div>
        <div class="common-list-item wd300">
            <div class="common-list-cell" >
                <span id="name_{$v['time']}">{$v['time']}</span>
            </div>
        </div>
   </div>
   <div class="common-list-biaoti">
	    <div class="common-list-item special-biaoti biaoti-transition">
			<div class="common-list-cell">
	        	<a class="common-list-overflow special-biaoti-overflow" id="name_{$v['name']}" href="./run.php?mid={$_INPUT['mid']}&a=show_stand&id={$v['id']}&routeid={$v['routeid']}&infrm=1">
	        	<span class="m2o-common-title">{$v['name']}</span></a>
            	<input type="hidden" name="id" value="{$v['id']}" />
            </div>  
	    </div>
   </div>
</li>