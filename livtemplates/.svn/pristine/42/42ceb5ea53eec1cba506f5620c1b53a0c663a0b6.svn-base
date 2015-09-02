{template:head}
{css:bootstrap/3.3.0/bootstrap.min}
{js:common/auto_textarea}
{js:hg_sort_box}
{js:common/common_form}
{js:app_plant/leancloud_form}
{if is_array($formdata) && $a == 'update'}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
{code}
$host = "https://leancloud.cn/1.1/open";
$url = $host."/clients/".$uid."/apps/".$app_id."/uploadiOSCertificate?access_token=".$access_token;
{/code}
<style>
input{height:auto;}
</style>
<div class="page-header">
	<h3 class="clearfix">
		<div class="col-sm-10">详情</div>
		<div class="col-sm-2"><a class="btn btn-info" href="{$_INPUT['referto']}"><span class="glyphicon glyphicon-circle-arrow-left"></span> 返回前一页</a></div>
	</h3>
</div>
<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" class="form-horizontal wrap-form">
							<div class="form-group">
								<div class="col-sm-2 control-label">用户名</div>
								<div class="col-sm-3">
									<input type="text" value="{$user_name}" class="form-control" readonly />
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-2 control-label">access_token</div>
								<div class="col-sm-3">
									<input type="text" value="{$access_token}" class="form-control" readonly />
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-2 control-label">expires_in</div>
								<div class="col-sm-3">
									<input type="text" value="{$expires_in}" class="form-control" readonly />
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-2 control-label">token_type</div>
								<div class="col-sm-3">
									<input type="text" value="{$token_type}" class="form-control" readonly />
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-2 control-label">uid</div>
								<div class="col-sm-3">
									<input type="text" value="{$uid}" class="form-control" readonly />
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-2 control-label">email</div>
								<div class="col-sm-3">
									<input type="text" value="{$email}" class="form-control" readonly />
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-2 control-label">app_name</div>
								<div class="col-sm-3">
									<input type="text" value="{$app_name}" class="form-control" readonly />
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-2 control-label">app_key</div>
								<div class="col-sm-3">
									<input type="text" value="{$app_key}" class="form-control" readonly />
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-2 control-label">app_id</div>
								<div class="col-sm-3">
									<input type="text" value="{$app_id}" class="form-control" readonly />
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-2 control-label">client_id</div>
								<div class="col-sm-3">
									<input type="text" value="{$client_id}" class="form-control" readonly />
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-2 control-label">created</div>
								<div class="col-sm-3">
									<input type="text" value="{$created_at}" class="form-control" readonly />
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-2 control-label">master_key</div>
								<div class="col-sm-3">
									<input type="text" name="master_key" value="{$master_key}" class="master_key form-control" readonly />
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-2 control-label">prod</div>
								<div class="col-sm-3">
									<input type="text" name="prod" value="{$prod}" class="prod form-control" readonly />
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-2 control-label">certfile_name</div>
								<div class="col-sm-3">
									<input type="text" name="certfile_name" value="{$certfile_name}" class="certfile_name form-control" readonly />
								</div>
							</div>
                            <div class="form-group">
                                <div class="col-sm-2 control-label">PROV ID</div>
                                <div class="col-sm-3">
                                    <input type="text" name="prov_id" value="{$prov_id}" class="prov_id form-control" style="width: 320px;"/>
                                    <a style="color: red">* PROV ID是苹果证书的描述文件标识符,见下图</a>
                                    <img src="http://img.dev.hogesoft.com:233/material/news/img/2015/06/20150626143455P6WQ.png" border=0/>
                                </div>
                                <div class="col-sm-2 control-label">应用的Bundle ID</div>
                                <div class="col-sm-3">
                                    <input type="text" name="bundle_id" value="{$bundle_id}" class="bundle_id form-control" readonly/>
                                </div>
                            </div>
				<input type="hidden" name="user_id" value="{$user_id}" />
				<input type="hidden" name="a" value="update" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
			</form>
			<form action="{$url}" method="post" enctype="multipart/form-data" class="form-horizontal myform">
				<div class="form-group">
					<div class="col-sm-2 control-label"></div>
					<div class="col-sm-10 row">
						<div class="col-sm-3">
							<input type='file' name="cert_file" id="cert_file" class="form-control"/>
						</div>
						<input type="hidden" name="prod" value='prod'/>
						<a class="btn btn-info submit-btn"><span class="glyphicon glyphicon-upload"></span> 上传</a>
					</div>
				</div>	
			</form>	
			<div class="row">
				<div class="col-sm-2 control-label"></div>
				<div class="col-sm-3">
					<a class="btn btn-info wrap-form-submit"><span class="glyphicon glyphicon-ok"></span> 提交</a>
					<input type="button" value="取消" class="btn btn-default" onclick="javascript:history.go(-1);"/>
				</div>
			</div><br/>
		</div>
</div>
{template:foot}