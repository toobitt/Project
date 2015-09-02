<li class="common-list-data clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}"  cname="{$v['cid']}"    corderid="{$v['order_id']}">
	<div class="common-list-left">
        <div class="common-list-item access-paixu">
            <div class="common-list-cell">
                <a class="lb"  name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
            </div>
        </div>
    </div>
	
	
	<div class="common-list-right">
	    <div class="common-list-item access-cz">
            <div class="common-list-cell">
                <a href="./run.php?mid={$relate_module_id}&a=show&app_bundle={$v['bundle_id']}&mod_bundle={$v['module_id']}&cid={$v['cid']}&infrm=1">查看详情</a>
            </div>
        </div>
        <div class="common-list-item access-ssyy">
            <div class="common-list-cell">
                <span>{$v['bundle_name']}</span>
            </div>
        </div>
        <div class="common-list-item access-fwcs">
            <div class="common-list-cell">
                 <span>{$v['access_nums']}</span>
            </div>
        </div>
<!--        <div class="common-list-item access-fwsj">-->
<!--            <div class="common-list-cell">-->
<!--              <span>{$v['update_time']}</span>-->
<!--            </div>-->
<!--        </div>-->
   </div>
   <div class="common-list-biaoti min-wd" style="cursor:pointer;">
	    <div class="common-list-item biaoti-transition">
		   <div class="common-list-cell">
	            {code}
				 	$pic = '';
				  	if($v['indexpic'])
				  	{
				  		$pic = $v['indexpic']['host'] . $v['indexpic']['dir'] . "40x30/" . $v['indexpic']['filepath'] . $v['indexpic']['filename'];
				  	}
				{/code}
				{if $pic}<img src="{$pic}" style="width:40px;height:30px;margin-right:10px;" />{else}{/if}
				<span class="common-list-overflow max-wd" style="max-width:350px;" id="title_{$v['id']}"><a href="{$v['content_url']}" target="_blank" >
				<span class="m2o-common-title">{$v['title']}</span></a></span>
           </div>
	    </div>
   </div>    
</li>