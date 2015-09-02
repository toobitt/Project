<li class="common-list-data clear"  id="r_{$v['id']}">
	<div class="common-list-left">
        <div class="common-list-item email-paixu">
            <div class="common-list-cell">
                <a class="lb" name="alist[]"><input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}" /></a>	
            </div>
        </div>
    </div>
	<div class="common-list-right">
	    <div class="common-list-item email-yjbt">
            <div class="common-list-cell">
                <span>{$v['subject']}</span>
            </div>
        </div>
        <div class="common-list-item email-fsyx">
            <div class="common-list-cell">
                <span>{$v['emailsend']}</span>
            </div>
        </div>
        <div class="common-list-item email-cz">
            <div class="common-list-cell">
                    <div title="操作" class="btn-box-cz">
                        <div class="btn-box-cz-menu">
				           <a onclick="window.location.href = './run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1'"  style="margin:4px 2px 0 0;" class="button_2">编辑</s>
				           <a onclick="hg_emailSettingsDel({$v['id']});"  style="margin:4px 2px 0 0;" class="button_2">删除</s>
				           <a onclick="hg_emailSettingsAudit({$v['id']},{$v['status']},'status', 'audit_');" id="emailSettingsAudit_{$v['id']}" style="margin:4px 2px 0 0;" class="button_2">审核</a>
			            </div>
			       </div> 
           </div>
        </div>
        <div class="common-list-item email-zt">
            <div class="common-list-cell">
              <span id="audit_{$v['id']}">{if !$v['status']}待审核{else}已审核{/if}</span>
            </div>
        </div>
        <div class="common-list-item email-tjr">
            <div class="common-list-cell">
			    <span>{$v['user_name']}</span>
			   <span class="create-time">{$v['create_time']}</span>
            </div>
        </div>
   </div>
   <div class="common-list-i" onclick="hg_getEmailSettingsById({$v['id']});"></div>
   <div class="common-list-biaoti ">
	    <div class="common-list-item email-biaoti biaoti-transition">
			   <div class="common-list-cell">
                <span id="sort_name_86" class="common-list-overflow email-biaoti-overflow" onclick=""><a href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">
                <span class="m2o-common-title">{$v['name']}</span></a></span>
            </div>  
	    </div>
   </div>
</li>