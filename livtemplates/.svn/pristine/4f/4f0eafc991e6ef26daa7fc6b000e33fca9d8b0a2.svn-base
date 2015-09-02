<li class="common-list-data clear" orderid="{$v['order_id']}" id="r_{$v['id']}" name="{$v['id']}">
     <div class="common-list-left">
        <div class="common-list-item paixu">
             <a class="lb" name="alist[]"><input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}" /></a>
        </div>
    </div>
    <div class="common-list-right">
        <div class="common-list-item">
            <span>{$v['type_name']}</span>
        </div>
        <div class="common-list-item">
        	<span>{$v['sort_name']}</span>
        </div>
        <div class="common-list-item wd150">
            <span>{$v['url']}</span>
        </div>
        <div class="common-list-item">
            <div title="操作" class="btn-box-cz">
                <div class="btn-box-cz-menu" id="rr_2_{$v['id']}">
		            <a class="button_4" class="mt4" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1" target="formwin">编辑</a>
					<a class="button_4" class="mt4" onclick="hg_Delete({$v['id']});">删除</a>
					<a class="button_4" class="mt4" onclick="hg_Audit({$v['id']},{$v['status']},'status', 'audit_');">审核</a>
               </div>
			</div>
        </div> 
        <div class="common-list-item">
            <span id="audit_{$v['id']}">{if !$v['status']}待审核{else}已审核{/if}</span>
        </div>
        <div class="common-list-item wd150">
            <span class="common-user">{$v['user_name']}</span>
			<span class="common-time">{$v['create_time']}</span>
        </div>
    </div>
    <div class="common-list-biaoti">
	     <div class="common-list-item biaoti-transition">
				<a href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1" target="formwin">
				<span id="sort_name_86">
				     <img width="40" height="30" src="{$v['icon1']}" class="pub-img"/>
				     {$v['name']}
				</span>
			   </a>
	    </div>
	</div>
</li>