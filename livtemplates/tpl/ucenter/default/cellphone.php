<?php 
/* $Id: cellphone.php 396 2011-07-28 00:52:08Z zhoujiafei $ */
?>
{template:head}
<div class="content">
	<div class="content_top"></div>	
	<div class="content_middle lin_con clear"> 
		{template:unit/userset}			
		<div class="edit-passwd">
			<div class="bind_con clear">
			     <p class="notice">使用说明：</p>
			     <p>(1) 绑定手机号后，我们将不会记录您的手机号码！</p>
			     <p>(2) 绑定手机后，您可以将所要发布的点滴作为彩信发送到以下邮箱，若您已经绑定了新浪微博，那么此彩信将同时同步到新浪微博！</p>
			     		<div class="circle_outer">
							<div class="circle_inner">
								<strong>{$_settings['mms_email']}</strong>
							</div>
						</div>
			     <p>(3) 每个帐号只能绑定一个手机号！</p>		
			 <h3 style="width:850px;margin:0 auto;">在葫芦网上绑定手机</h3>

			
			{if $_user['cellphone']}
			
			<div class="cell_unbind">
				<div class="circle_out">
							<div class="circle_inner">
								<strong>{$_user['cellphone']}</strong>
							</div>
						</div>	
						<a class="unbind_bt" href="javascript:void(0);" onclick="unbind_phone();">解除绑定</a>
				<span class="error_tip" id="error_tip"></span>
			</div>		
			{else}
		     <table class="table-cell" style="float: left; margin-left: 88px;">
				<tr>
					<td class="td-ft">手机号：</td>
					<td class="td-sd">
						<div class="biankuang">
							<input type="text" id="cellphone"/>
						</div>	
					</td>
					<td id="info02" class="td-td"></td>
				</tr>
				<tr>
					<td></td>
					<td class="td-sd user_info_ok">
						<input type="button" value="" onclick="cell_phone();"><span class="error_tip" id="error_tip"></span>
					</td>
					<td></td>
				</tr>
			</table>
		   {/if}
			    <p class="notice clear">小贴士：</p>	
			   	<p> 1. 每条彩信可以包含多张照片，一次发多张更合算。</p>
				<p>2. 发大一点的照片更清楚，调整好你的手机拍照设置。</p>
				<p>3. 运营商提供了多种彩信套餐，选择一个合适的彩信套餐可以节省很多彩信费。</p>
				<p>4. 把Email地址保存在你的通讯录中，以后发彩信时就不用每次都输一遍了。</p>
			</div>
		</div>
	</div>
	<div class="content_bottom"></div>
</div>
{template:foot}
