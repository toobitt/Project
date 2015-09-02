<li class="common-list-data clear"  id="r_{$v['id']}" _id="{$v['id']}"    name="{$v['id']}"   orderid="{$v['id']}">
	<div class="common-list-left">
        <div class="common-list-item common-paixu">
            <div class="common-list-cell">
                <!--<a class="lb"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['program_id']}" title="{$v['program_id']}"  /></a>-->
            </div>
        </div>
    </div>
    <div class="common-list-right">
    	<div class="common-list-item">
            <div class="common-list-cell">
			    <span id="name_{$v['bundle_id']}">{$v['bundle_id']}</span>
            </div>
        </div> <div class="common-list-item module">
            <div class="common-list-cell">
			    <span id="name_{$v['moudle_id']}">{$v['moudle_id']}</span>
            </div>
        </div>
        <!-- 
        <div class="common-list-item">
            <div class="common-list-cell">
			   <a onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"><i class="hg_icons del_icon"></i></a>
            </div>
        </div>
         -->
        <div class="common-list-item">
            <div class="common-list-cell">
			    <span id="name_{$v['operation']}">{$v['operation']}</span>
            </div>
        </div>
        <div class="common-list-item">
            <div class="common-list-cell">
			    <span id="name_{$v['user_name']}">{$v['user_name']}</span>
            </div>
        </div>
        <div class="common-list-item">
            <div class="common-list-cell">
			    <span id="name_{$v['source']}">{$v['source']}</span>
            </div>
        </div>
        <div class="common-list-item">
            <div class="common-list-cell">
			    <span id="name_{$v['ip']}" title="{$v['ip_info']}">{$v['ip']}</span>
            </div>
        </div>
   </div>
   <div class="common-list-biaoti">
	    <div class="common-list-item special-biaoti biaoti-transition">
			<div class="common-list-cell">
				{if $v['title']}
	        	<a class="common-list-overflow special-biaoti-overflow" id="name_{$v['title']}" href="./run.php?mid={$_INPUT['mid']}&a=get_content&id={$v['id']}&infrm=1" target="mainwin">
	        	<Span class="m2o-common-title">{$v['title']}</Span></a>
            	{else}
            	<a class="common-list-overflow special-biaoti-overflow" id="name_{$v['id']}" href="./run.php?mid={$_INPUT['mid']}&a=get_content&id={$v['id']}&infrm=1" target="mainwin">
            	<Span class="m2o-common-title">{$v['id']}</Span></a>
            	{/if}
            	<input type="hidden" name="id" value="{$v['id']}" />
            </div>  
	    </div>
   </div>
</li>