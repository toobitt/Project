<li class="common-list-data clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['id']}">
	<div class="common-list-left">
        <div class="common-list-item common-paixu">
            <div class="common-list-cell">
                <a class="lb"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}"  /></a>
            </div>
        </div>
        <div class="common-list-item special-slt">
            <div class="common-list-cell">
                 {code}
		       	 $url = $v['indexpic_url'];
		       	 {/code}	
				<img src="{$url}" id="img_{$v['id']}"  />
            </div>
        </div>
    </div>
	<div class="common-list-right">
	    <div class="common-list-item">
            <div class="common-list-cell">
                <a onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}">删除</a>
            </div>
        </div>
        <div class="common-list-item">
            <div class="common-list-cell">
			    <span id="name_{$v['id']}">{$v['id']}</span>
            </div>
        </div>
        <div class="common-list-item">
            <div class="common-list-cell">
                <span id="name_{$v['status']}">{if $v['state']}已审核{else}待审核{/if}</span>
            </div>
        </div>
        <div class="common-list-item">
            <div class="common-list-cell">
                <span id="name_{$v['update_time_show']}">{$v['update_time_show']}</span>
            </div>
        </div>
   </div>
   <div class="common-list-biaoti">
	    <div class="common-list-item special-biaoti biaoti-transition">
			<div class="common-list-cell">
	        	<a class="common-list-overflow special-biaoti-overflow" id="name_{$v['title']}" href="modify.php?app_uniqueid={$_configs['appmod']['news']['app']}&mod_uniqueid={$_configs['appmod']['news']['mod']}&id={$v['id']}&app={$scenic_survey_list[0]['app']}&mod={$scenic_survey_list[0]['mod']}&para={$_INPUT['para']}">
	        	<span class="m2o-common-title">{$v['title']}</span></a>
            	<input type="hidden" name="id" value="{$v['id']}" />
            </div>  
	    </div>
   </div>
</li>