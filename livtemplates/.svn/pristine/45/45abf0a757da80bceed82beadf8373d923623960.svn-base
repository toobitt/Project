<li class="common-list-data clear"  id="r_{$v['cid']}"    name="{$v['cid']}"   orderid="{$v['order_id']}" >
    <div class="common-list-left">
        <div class="common-list-item" style="width:35px">
            <div class="common-list-cell">
                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"/></a>
            </div>
        </div>
    </div>
    <div class="common-list-right">
        <div class="common-list-item circle-tjr">
            <div class="common-list-cell">
                    <span>{$v['bundle_name']}</span>
            </div>
        </div>
        <div class="common-list-item circle-tjr" style="width:190px;">
            <div class="common-list-cell">
                <span>{$v['publish_time']}</span>
            </div>
        </div>
        <div class="common-list-item circle-tjr">
            <div class="common-list-cell">
                    <span>{$v['count']}</span>
            </div>
        </div>
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
				{code}
				    $content_url = '';
				    $content_url = $v['content_url'] ? $v['content_url'] : $v['column_url'];
				{/code}
				<span class="common-list-overflow max-wd" style="max-width:350px;" id="title_{$v['id']}"><a href="{$content_url}" target="_blank">{$v['title']}</a></span>
           </div>
	    </div>
   </div>   
</li>