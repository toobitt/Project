<li class="common-list-data clear" id="r_{$v['id']}" name="{$v['id']}" orderid="{$v['id']}">
     <div class="common-list-left">
        <div class="common-list-item paixu">
            <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['id']}" title=""  /></a>
        </div>
    </div>
    <div class="common-list-right">
        <div class="common-list-item wd80">
            <span>{$_configs['action_type'][$v['action_type']]}</span>
        </div>
        <div class="common-list-item wd60">
            <span>待发布</span>
        </div>
        <div class="common-list-item wd150">
        	<span class="common-user">{$v['publish_user']}</span>
            <span class="common-time">{code}echo date('Y-m-d H:i:s',$v['publish_time']){/code}</span>
        </div>
    </div>
    <div class="common-list-biaoti">
	     <div class="common-list-item biaoti-transition">
	          <span class="m2o-common-title">{$v['title']}</span>
	     </div>
	</div>
</li>  