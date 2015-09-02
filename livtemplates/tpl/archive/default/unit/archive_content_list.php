<li class="common-list-data clear"  id="r_{$v['id']}" _id="{$v['id']}"  name="{$v['id']}"   order_id="{$v['order_id']}">
	<div class="common-list-left">
        <div class="common-list-item archive-paixu">
            <div class="common-list-cell">
                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
            </div>
        </div>
    </div>
    <div class="common-list-right">

        <div class="common-list-item archive-sc wd60">
            <div class="common-list-cell">
                 <a class="btn-box" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}&archive_id={$v['archive_id']}"><em class="b3"></em></a>
            </div>
       </div>
       <div class="common-list-item archive-hy wd60">
            <div class="common-list-cell">
                <a class="btn-box" onclick="return hg_ajax_post(this, '还原', 1);" href="./run.php?mid={$_INPUT['mid']}&a=recover_content&id={$v['id']}&archive_id={$v['archive_id']}">还原</a>
            </div>
       </div>
       <div class="common-list-item archive-ssyy wd100">
            <div class="common-list-cell">
                 <span class="overflow" id="archive_sort_{$v['id']}">{$v['name']}</span>
            </div>
       </div>
       <div class="common-list-item archive-gdr wd100">
            <div class="common-list-cell">
                 <span id="archive_audit_{$v['id']}">{$v['user_name']}</span>
            </div>
       </div>
       <div class="common-list-item archive-gdip wd100">
            <div class="common-list-cell">
                 <span id="archive_audit_{$v['id']}">{$v['ip']}</span>
            </div>
       </div>
       <div class="common-list-item archive-gdsj wd150">
            <div class="common-list-cell">
                 <span id="archive_audit_{$v['id']}">{$v['create_time']}</span>
            </div>
       </div>
   </div>
   <div class="common-list-biaoti ">
	    <div class="common-list-item archive-biaoti biaoti-transition">
		   <div class="common-list-cell">
			   <span class="archive-biaoti-overflow">{$v['title']}</span>
           </div>  
	    </div>
   </div>
</li> 