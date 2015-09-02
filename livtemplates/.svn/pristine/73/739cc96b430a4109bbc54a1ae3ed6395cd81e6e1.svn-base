<li class="common-list-data clear" id="r_{$v['id']}"  name="{$v['id']}"  orderid="{$v['id']}">
     <div class="common-list-left">
        <div class="common-list-item paixu">
            <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['id']}" title=""  /></a>
        </div>
    </div>
    <div class="common-list-right">
        <div class="common-list-item wd150">
        	<span class="common-user">{$v['mk_time']}秒</span>
        </div>
        <div class="common-list-item wd150">
                <span class="common-time">{code}echo date('Y-m-d H:i:s',$v['next_time']){/code}</span>
        </div>
        <div class="common-list-item wd150">
                 <span class="common-user">{if $v['is_open']}开启{else}关闭{/if}</span>  
        </div>
        <div class="common-list-item wd150">
        	<span class="common-user">
                    <a href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">编辑</a> 
                    <a href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}" onclick="return hg_ajax_post(this, '删除', 1);">删除</a>
                </span>
        </div>
        <div class="common-list-item wd150">
        	<span class="common-user">{$v['create_user']}</span>
        </div>
    </div>
    <div class="common-list-biaoti">
	     <div class="common-list-item biaoti-transition">
	          <span>{$v['title']}</span>
	     </div>
	</div>
</li>  