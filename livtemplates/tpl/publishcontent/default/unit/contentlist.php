<li class="common-list-data clear"  id="r_{$v['rid']}" _id="{$v['rid']}" name="{$v['rid']}"   order_id="{$v['order_id']}">
	<div class="common-list-left">
		<div class="common-list-item paixu">
				 <a name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['rid']}" title="{$v['rid']}" /></a>
		</div>
	</div>
	<div class="common-list-right ">
		<!--  <div class="common-list-item content-delete">
			<span class="delete" data-rid="{$v['rid']}">删除<img class="watting-img" src="{$RESOURCE_URL}loading2.gif" /></span>
		</div>-->
		<div class="common-list-item content-neixing">
				<span class="typeslt">{$v['app_name']}</span>
		</div>
		<div class="common-list-item contentn-fabu">
				 <span class="shareslt common-list-overflow">{code}echo str_replace('/', '>', $content[0]['col_parent'][$v['column_id']]);{/code}</span>
			<div class="overflow"></div>	
		</div>
		{template:list/list_weight,asd,$v['weight']}
		<!-- <div class="common-list-item content-quanzhong">
			<div class="common-list-cell">
				<div class="common-quanzhong-box">
					<div class="common-quanzhong-box{$v['weight']}" _level="{$v['weight']}">
						<div class="common-quanzhong">{$_configs['levelLabel'][$v['weight']]}</div>
						<div class="common-quanzhong-option">
						    <div class="common-quanzhong-down"></div>
							<div class="common-quanzhong-up"></div>
						</div>
					</div>
				</div>
			</div>
		</div> -->
                {$_configs['is_need_audit']}
                {if $_configs['is_need_audit']}
		<div class="common-list-item content-status">
                    <div class="common-switch-status">
		     <span _id="{$v['id']}" _state="{$v['state']}" id="statusLabelOf{$v['id']}" style="color:{$_configs['status_color'][$v['state']]};">{$_configs['status_show'][$v['state']]}</span>
			</div>
		</div>
                {/if}
		<div class="common-list-item content-tianjia-time">
				<span class="user-name">{$v['create_user']}</span><span class="content-time">{$v['create_time']}</span>
		</div>
		<div class="common-list-item content-shenhe-time">
				<span class="user-name">{$v['publish_user']}</span>
				<span class="content-time publish-time-area">{$v['publish_time']}</span>
				<input class="publish-time-input" value="{$v['publish_time']}" _time="true" />
				<span class="update-time">更新</span>
		</div>
	</div>
	<div class="common-list-i" onclick="hg_show_opration_info({$v['rid']});"></div>
	<div class="common-list-biaoti">
		<div class="common-list-item biaoti-transition">
			{if $v['indexpic']}<img src="{$v['indexpic']}" class="biaoti-img" id="img_{$v['_id']}"  />{/if}
				<a target="formwin" class="common-list-overflow max-wd fz14" href="modify.php?app_uniqueid={$v['bundle_id']}&mod_uniqueid={$v['module_id']}&id={$v['content_fromid']}&outlink={$v['outlink']}">
				<span class="m2o-common-title">{$v['title']}</span></a>
				<a class="shareslt" {if $v['content_url']}href="{$v['content_url']}" target="_blank"{/if}  style="color:green;margin-left:10px;vertical-align:middle;">浏览</a> 
		</div>
	</div>
</li>