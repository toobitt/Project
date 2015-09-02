<li class="common-list-data clear"  id="r_{$v['id']}" orderid="{$v['order_id']}" name="{$v['id']}">
	<div class="common-list-left">
        <div class="common-list-item mem-paixu">
            <div class="common-list-cell">
                <a class="lb" name="alist[]"><input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}" /></a>	
            </div>
        </div>
    </div>
	<div class="common-list-right">
	    <div class="common-list-item mem-huiyuan">
            <div class="common-list-cell">
                <span title="{$v['platform']}" style="white-space: nowrap;
display: inline-block;overflow: hidden;text-overflow: ellipsis;width:120px;">{$v['platform']}</span>
            </div>
        </div>
        <div class="common-list-item mem-email">
            <div class="common-list-cell">
                <span title="{$v['callback']}" style="white-space: nowrap;
display: inline-block;overflow: hidden;text-overflow: ellipsis;width:160px;">{$v['callback']}</span>
            </div>
        </div>
        <div class="common-list-item mem-cz">
            <div class="common-list-cell">
              <a id="cz" style="width:25px;position:relative;" title="操作" class="cz"> <em class="b4" style="margin-top: 0px;width: 20px;"></em>
			     <span style="display: none;z-index:999;width: 140px;margin-top:-15px;" class="show_opration_button">
				   <span onclick="window.location.href = './run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1'"  style="margin:4px 2px 0 0;" class="button_2">编辑</span>
				   <span onclick="hg_memberDel({$v['id']});"  style="margin:4px 2px 0 0;" class="button_2">删除</span>
				   <span onclick="hg_memberAudit({$v['id']},{$v['status']},'status', 'audit_');" id="memberAudit_{$v['id']}"  style="margin:4px 2px 0 0;" class="button_2">审核</span>
				 </span>
			   </a>
            </div>
        </div>
        <div class="common-list-item mem-zt">
            <div class="common-list-cell">
			       <span id="audit_{$v['id']}">{if $v['status']}已审核{else}待审核{/if}</span>
            </div>
        </div>
        <div class="common-list-item mem-sj">
            <div class="common-list-cell">
                <span style="color:#819AB9;font-size:9px;float: left;">{$v['user_name']}</span>
                <span style="color:#7B7B7B;font-size:9px;float: left;">{$v['create_time']}</span>
            </div>
        </div>
	</div>
	<!-- <div class="common-list-i" onclick="hg_getMemberInfoById({$v['id']});"></div> -->
	<div class="mem-title" style="margin-top:10px;"  onclick="hg_getMemberInfoById({$v['id']});">		
			<span id="sort_name_86" style="color:#333;padding-right:10px;margin-top:10px;">{$v['name']}</span>
		</a>
	</div>
</li>