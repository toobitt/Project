<li class="common-list-data clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['orderid']}">
	<div class="common-list-left">
        <div class="common-list-item common-paixu">
            <div class="common-list-cell">
                <a class="lb"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}"  /></a>
            </div>
        </div>
        <div class="common-list-item special-slt">
            <div class="common-list-cell">
                 {code}
		       	 $picinfo = unserialize($v['indexpic']);
		       	 $url = $picinfo['host'].$picinfo['dir'].'40x30/'.$picinfo['filepath'].$picinfo['filename'];
		       	 {/code}	
				<img src="{$url}" id="img_{$v['id']}"  />
            </div>
        </div>
    </div>
	<div class="common-list-right">
	    <div class="common-list-item"    style="width:150px;">
            <div class="common-list-cell" >
                <a title="编辑" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&fid={$_INPUT['fid']}&infrm=1">编辑</a>
                {foreach $_relate_module AS $kkk => $vvv}
                 <a href="./run.php?mid={$kkk}&a=show&para={$v['id']}&infrm=1">{$vvv}</a>
				{/foreach}
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
                <span id="name_{$v['sort_name']}">{$v['sort_name']}</span>
            </div>
        </div>
        <div class="common-list-item">
            <div class="common-list-cell">
                <span id="name_{$v['cre_time']}">{$v['cre_time']}</span>
            </div>
        </div>
   </div>
   <div class="common-list-biaoti">
	    <div class="common-list-item special-biaoti biaoti-transition">
			<div class="common-list-cell">
	        	<a class="common-list-overflow special-biaoti-overflow" id="name_{$v['name']}" href="./run.php?mid={$_INPUT['mid']}&a=show&fid={$v['id']}&sort_id={$v['sort_id']}&infrm=1">
	        	<span class="m2o-common-title">{$v['name']}</span></a>
            	<input type="hidden" name="id" value="{$v['id']}" />
            </div>  
	    </div>
   </div>
</li>