{template:head}
{css:hg_sort_box}
{js:common/auto_textarea}
{js:hg_sort_box}
{js:common/common_form}
{css:common/common_form}
{js:2013/ajaxload_new}
{if is_array($formdata) && $a == 'update'}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
	<div class="wrap clear">
		<div class="ad_middle">
			<div class="alert alert-warning" style="margin-top:10px;">
				<p>运营人员操作事项</p>
				<p>1.必须<strong class="text-danger">先确认付款</strong>后，才可以对用户的申请进行审核</p>
				<p>2.若打回申请，必须<strong class="text-danger">详细填写打回原因</strong>，如“注册地址填写不完整”，切勿简单填写“请完善资料”</p>
				<p>3.确认付款，必须<strong class="text-danger">确认最新的付款订单</strong>，也就是按照时间排序“最上面”的付款订单</p>
			</div>
			<form action="run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" class="form-horizontal">
				<h2 class="page-header">商业授权申请详情</h2>
				<div class="form-group">
					<label  class="col-sm-2 control-label">姓名/单位</label>
					<div class="col-sm-3">
						<input type="text" value="{$name}" class="form-control" readonly />
					</div>
				</div>
				<div class="form-group">
					<label  class="col-sm-2 control-label">类型</label>
					<div class="col-sm-3">
						<input type="text" value="{$type_text}" class="form-control" readonly />
					</div>
				</div>
				<div class="form-group">
					<label  class="col-sm-2 control-label">叮当账号</label>
					<div class="col-sm-3">
						<input type="text" value="{$user_name}" class="form-control" readonly />
					</div>
				</div>
				<div class="form-group">
					<label  class="col-sm-2 control-label">地址</label>
					<div class="col-sm-3">
						<input type="text" value="{$address}" class="form-control" readonly />
					</div>
				</div>
				<div class="form-group">
					<label  class="col-sm-2 control-label">联系人</label>
					<div class="col-sm-3">
						<input type="text" value="{$link_man}" class="form-control" readonly />
					</div>
				</div>
				<div class="form-group">
					<label  class="col-sm-2 control-label">手机号</label>
					<div class="col-sm-3">
						<input type="text" value="{$telephone}" class="form-control" readonly />
					</div>
				</div>
				<div class="form-group">
					<label  class="col-sm-2 control-label">当前状态</label>
					<div class="col-sm-3">
						<input type="text" value="{$status_text}" class="form-control business-current-status" readonly />
					</div>
				</div>
				<div class="form-group">
					<label  class="col-sm-2 control-label"></label>
					<div class="col-sm-3">
						<input type="hidden" value="{$$primary_key}" id='_business_auth_id' />
						<div class="business-btns {if $status!=1}hide{/if}" _status="1">
							<input type="button" value="开通授权" _status="2" class="btn btn-info business-auth-btn" style="margin-right:20px;"/>
							<input type="button" value="打回授权" _status="3" class="btn btn-info business-auth-btn" style="margin-right:20px;"/>
						</div>
						<div class="business-btns {if $status!=2}hide{/if}" _status="2">
							<input type="button" value="打回授权" _status="3" class="btn btn-info business-auth-btn" style="margin-right:20px;"/>
						</div>
						<div class="business-btns {if $status!=3}hide{/if}" _status="3">
							<input type="button" value="开通授权" _status="2" class="btn btn-info business-auth-btn" style="margin-right:20px;"/>
						</div>
					</div>
				</div>
			</form>
			{if $invoice_apply}
			<form action="run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" class="form-horizontal">
				<h2 class="page-header">票据申请详情</h2>
				<div class="form-group">
					<label  class="col-sm-2 control-label">票据类型</label>
					<div class="col-sm-3">
						<input type="text" value="{$invoice_apply['invoice_type_text']}" class="form-control" readonly />
					</div>
				</div>
				<div class="form-group">
					<label  class="col-sm-2 control-label">金额</label>
					<div class="col-sm-3">
						<input type="text" value="{$invoice_apply['money']}" class="form-control" readonly />
					</div>
				</div>
				<div class="form-group">
					<label  class="col-sm-2 control-label">发票抬头</label>
					<div class="col-sm-3">
						<input type="text" value="{$invoice_apply['invoice_title']}" class="form-control" readonly />
					</div>
				</div>
				<div class="form-group">
					<label  class="col-sm-2 control-label">纳税人标识</label>
					<div class="col-sm-3">
						<input type="text" value="{$invoice_apply['taxpayer_id']}" class="form-control" readonly />
					</div>
				</div>
				<div class="form-group">
					<label  class="col-sm-2 control-label">开户银行</label>
					<div class="col-sm-3">
						<input type="text" value="{$invoice_apply['deposit_bank']}" class="form-control" readonly />
					</div>
				</div>
				<div class="form-group">
					<label  class="col-sm-2 control-label">开户账号</label>
					<div class="col-sm-3">
						<input type="text" value="{$invoice_apply['bank_account']}" class="form-control" readonly />
					</div>
				</div>
				<div class="form-group">
					<label  class="col-sm-2 control-label">注册地址</label>
					<div class="col-sm-3">
						<input type="text" value="{$invoice_apply['register_address']}" class="form-control" readonly />
					</div>
				</div>
				
				<div class="form-group">
					<label  class="col-sm-2 control-label">公司注册电</label>
					<div class="col-sm-3">
						<input type="text" value="{$invoice_apply['register_phone']}" class="form-control" readonly />
					</div>
				</div>
				
				<div class="form-group">
					<label  class="col-sm-2 control-label">纳税人证明</label>
					<div class="col-sm-3">
						{code}
							$taxpayer_cert_url = '';
							if($invoice_apply['taxpayer_cert'] && is_array($invoice_apply['taxpayer_cert']))
							{
								$_taxpayer_cert = $invoice_apply['taxpayer_cert'];
								$taxpayer_cert_url = $_taxpayer_cert['host'] . $_taxpayer_cert['dir'] . $_taxpayer_cert['filepath'] . $_taxpayer_cert['filename'];
							}
						{/code}
						<img class="img-thumbnail" src="{$taxpayer_cert_url}" style="max-height:100px;"/>
					</div>
				</div>
				<div class="form-group">
					<label  class="col-sm-2 control-label">税务登记证</label>
					<div class="col-sm-3">
						{code}
							$tax_register_cert_url = '';
							if($invoice_apply['tax_register_cert'] && is_array($invoice_apply['tax_register_cert']))
							{
								$_tax_register_cert = $invoice_apply['tax_register_cert'];
								$tax_register_cert_url = $_tax_register_cert['host'] . $_tax_register_cert['dir'] . $_tax_register_cert['filepath'] . $_tax_register_cert['filename'];
							}
						{/code}
						<img class="img-thumbnail" src="{$tax_register_cert_url}" style="max-height:100px;"/>
					</div>
				</div>
				<div class="form-group">
					<label  class="col-sm-2 control-label">收件人</label>
					<div class="col-sm-3">
						<input type="text" value="{$invoice_apply['recipient']}" class="form-control" readonly />
					</div>
				</div>
				<div class="form-group">
					<label  class="col-sm-2 control-label">收件人地址</label>
					<div class="col-sm-3">
						<input type="text" value="{$invoice_apply['recipient_address']}" class="form-control" readonly />
					</div>
				</div>
				<div class="form-group">
					<label  class="col-sm-2 control-label">收件人电话</label>
					<div class="col-sm-3">
						<input type="text" value="{$invoice_apply['recipient_phone']}" class="form-control" readonly />
					</div>
				</div>
				<div class="form-group">
					<label  class="col-sm-2 control-label">当前状态</label>
					<div class="col-sm-3">
						<input type="text" value="{$invoice_apply['status_text']}" class="form-control current-invoice-status" readonly />
					</div>
				</div>
				<div class="form-group">
					<label  class="col-sm-2 control-label"></label>
					<div class="col-sm-3">
						<input type="hidden" name="id" value="{$invoice_apply['id']}" class="invoice-apply-id"/>
						<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
						<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
						<div class="invoice-btns {if $invoice_apply['status'] != 1}hide{/if}" _status="1">
							<input type="button" value="审核通过" _status="2" class="btn btn-info fapiao-btn" style="margin-right:20px;"/>
							<input type="button" value="打回申请" _status="3" class="btn btn-info fapiao-btn" style="margin-right:20px;"/>
						</div>
						<div class="invoice-btns {if $invoice_apply['status'] != 2}hide{/if}" _status="2">
							<input type="button" value="打回申请" _status="3" class="btn btn-info fapiao-btn" style="margin-right:20px;"/>
							<input type="button" value="确认邮寄" _status="4" class="btn btn-info fapiao-btn" style="margin-right:20px;"/>
						</div>
						<div class="invoice-btns {if $invoice_apply['status'] != 3}hide{/if}" _status="3">
							<input type="button" value="审核通过" _status="2" class="btn btn-info fapiao-btn" style="margin-right:20px;"/>
						</div>
					</div>
				</div>
			</form>
			{/if}

			<!-- 购买记录 -->
			{css:bootstrap/3.3.0/bootstrap.min}
			{js:app_plant/business}
			<style>
			html{background:#fff;margin:0 10px;}
			body, input{height:auto;}
			.confirm-pay{cursor:pointer;}
			#popup_container *{box-sizing: content-box;}
			#popup_content{height:100px!important;}
			</style>
			<div class="record-list-wrap">
				<table class="table table-hover">
					<thead>
						<tr>
							<th>订单号</th>
							<th>类型</th>
							<th>事由</th>
							<th>金额</th>
							<th>银行</th>
							<th>状态</th>
							<th>付款时间</th>
							<th>开通时间</th>
							<th>授权时长</th>
							<th>下单时间</th>
							<th>操作</th>
						</tr>
					</thead>
					{code}
					//hg_pre( $pay_log );
					{/code}
					<tbody>
						{foreach $pay_log as $k => $v}
        				<tr>
							<th>{$v['order_num']}</th>
							<th>{$v['type_text']}</th>
							<th>{$v['pay_reason_text']}</th>
							<th>{$v['money']}</th>
							<th>{$v['bank']}</th>
							<th>{$v['status_text']}</th>
							<th>{$v['pay_time_text']}</th>
							<th>{$v['open_time_text']}</th>
							<th>{$v['auth_duration']}</th>
							<th>{$v['create_time']}</th>
							<th>
								{if $v['status'] != 1}
								<a class="confirm-pay btn btn-info btn-xs" _id="{$v['id']}">确认付款</a>
								{/if}
							</th>
						</tr>
        				{/foreach}
					</tbody>
				</table>
			</div>
			<!-- 购买记录end -->
			
		</div>
		<!-- 
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	 -->
</div>
{template:foot}