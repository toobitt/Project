<li class="common-list-data clear">
	<div class="common-list-left">
        <div class="common-list-item access-paixu" style="width:30px;">
            <div class="common-list-cell">
                <a class="lb"  name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
            </div>
        </div>
    </div>
	<div class="common-list-right">
		<div class="common-list-item access-ssyy two"  align=center>
            <div class="common-list-cell">
                <span>{$v['title']}</span>
            </div>
        </div>
        <div class="common-list-item access-ssyy" align=center style="width:240px;">
            <div class="common-list-cell">
                <span>{$v['url']}</span>
            </div>
        </div>
        <div class="common-list-item access-ssyy" align=center>
            <div class="common-list-cell">
                <span>{$v['num']}</span>
            </div>
        </div>
        <div class="common-list-item access-ssyy" align=center>
            <div class="common-list-cell">
                <span>{$v['outernet']}/{$v['intranet']}</span>
            </div>
        </div>
        <div class="common-list-item access-fwcs two" align=center>
            <div class="common-list-cell">
                 <span>
                 {code}
                 	if($v['video_id'])
                 	{
                 		echo $v['video_num'];
                 	}
                 	else
                 	{
                 		echo "无";
                 	}
                 {/code}
                 </span>
            </div>
        </div>
        <div class="common-list-item access-fwcs two" align=center>
            <div class="common-list-cell">
                 <span><a href="#">详情</a></span>
            </div>
        </div>
        <div class="common-list-item access-fwcs two" align=center>
            <div class="common-list-cell">
                 <span><a href="#">详情</a></span>
            </div>
        </div>
        <div class="common-list-item access-fwcs two" align=center>
            <div class="common-list-cell">
                 <span><a href="#">详情</a></span>
            </div>
        </div>
        <div class="common-list-item access-fwcs two" align=center>
            <div class="common-list-cell">
                 <span>{$v['department']}</span>
            </div>
        </div>
        <div class="common-list-item access-fwcs two" align=center>
            <div class="common-list-cell">
                 <span>{$v['user_name']}</span>
            </div>
        </div>
        <div class="common-list-item access-fwsj two" align=center>
            <div class="common-list-cell">
              <span>2013-10-10</span>
            </div>
        </div>
   </div>
      
</li>