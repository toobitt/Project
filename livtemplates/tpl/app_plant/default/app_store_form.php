{template:head}
{css:bootstrap/3.3.0/bootstrap.min}
<style>
.form-right{padding-top:7px;}
</style>
<form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
	<div class="form-group">
		<label class="col-sm-2 control-label">应用名</label>
		<div class="col-sm-10 form-right">{$formdata['app_name']}</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">版本</label>
		<div class="col-sm-10 form-right">{$formdata['version']}</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">申请人</label>
		<div class="col-sm-10 form-right">{$formdata['user_name']}</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">QQ号</label>
		<div class="col-sm-10 form-right">{$formdata['qq']}</div>
	</div>
	<div class="form-group">
                <label class="col-sm-2 control-label">Apple ID</label>
                <div class="col-sm-10 form-right">{$formdata['apple_id']}</div>
        </div>
	<div class="form-group">
		<label class="col-sm-2 control-label">申请时间</label>
		<div class="col-sm-10 form-right">{$formdata['create_time']}</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">案例地址</label>
		<div class="col-sm-10 form-right"><a onclick="window.open('{$formdata['case_url']}')">{$formdata['case_url']}</a></div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">安卓市场1</label>
		<div class="col-sm-10 form-right"><a onclick="window.open('{$formdata['android_market1']}')">{$formdata['android_market1']}</a></div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">安卓市场2</label>
		<div class="col-sm-10 form-right"><a onclick="window.open('{$formdata['android_market2']}')">{$formdata['android_market2']}</a></div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">分享截图</label>
		<div class="col-sm-10 form-right"><a  target="_blank"target="_blank" href="{code}echo hg_bulid_img($formdata['attach'][$formdata['share_snap']]){/code}"><img width="160" height="90" src="{code}echo hg_bulid_img($formdata['attach'][$formdata['share_snap']]){/code}"/></a></div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">百度口碑截图</label>
		<div class="col-sm-10 form-right"><a target="_blank" href="{code}echo hg_bulid_img($formdata['attach'][$formdata['baidu_koubei_snap']]){/code}"><img width="160" height="90" width="" height="" src="{code}echo hg_bulid_img($formdata['attach'][$formdata['baidu_koubei_snap']]){/code}"/></a></div>
	</div>
	{if !$formdata['itunes_connect']}
	<div class="form-group">
		<label class="col-sm-2 control-label">版权</label>
		<div class="col-sm-10 form-right">{$formdata['copy_right']}</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">描述</label>
		<div class="col-sm-10 form-right">{$formdata['brief']}</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">关键字</label>
		<div class="col-sm-10 form-right">{$formdata['keywords']}</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">技术网站</label>
		<div class="col-sm-10 form-right"><a target="_blank" href="{$formdata['tech_surpport_site']}">{$formdata['tech_surpport_site']}</a></div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">隐私政策</label>
		<div class="col-sm-10 form-right"><a target="_blank" href="{$formdata['privacy_policy']}">{$formdata['privacy_policy']}</a></div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-2 control-label">客户端前台管理员账号</label>
		<div class="col-sm-10 form-right">{$formdata['admin_user_name']}</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">客户端前台管理员密码</label>
		<div class="col-sm-10 form-right">{$formdata['admin_user_pwd']}</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">此版本的新增特性</label>
		<div class="col-sm-10 form-right">{$formdata['version_info']}</div>
	</div>
	
	
	{else}
	<div class="form-group">
		<label class="col-sm-2 control-label">Apple ID</label>
		<div class="col-sm-10 form-right">{$formdata['apple_id']}</div>
	</div>
	{/if}
	<div class="form-group">
		<label class="col-sm-2 control-label">更新时间</label>
		<div class="col-sm-10 form-right">{$formdata['update_time']}</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">附件</label>
		<div class="col-sm-10 form-right">
			{if $formdata['zip']}
				<a onclick="window.open('{$formdata['zip']}')">点击下载</a>
			{else}
				<a onclick="window.open('./run.php?mid={$_INPUT['mid']}&a=download&id={$formdata['id']}&infrm=1')">点击下载</a>
			{/if}
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">审核</label>
		<div class="col-sm-2">
			<select class="form-control" name="status">
				{foreach $_configs['app_store_status'] as $k=>$v}
				<option value="{$k}" {if $k==$formdata['status']}selected="selected"{/if}>{$v}</option>
				{/foreach}
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">意见</label>
		<div class="col-sm-5">
			<textarea class="form-control" name="message">{$formdata['message']}</textarea>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"></label>
		<div class="col-sm-10">
			<input type="hidden" name="a" value="update" />
			<input type="hidden" name="id" value="{$formdata['id']}" />
			<button class="btn btn-default" type="submit" name="submit">提交</button>
		</div>
	</div>
</form>
{template:foot}
