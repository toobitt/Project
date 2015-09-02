{template:head}
{css:hg_sort_box}
{js:common/auto_textarea}
{js:hg_sort_box}
{js:common/common_form}
{css:common/common_form}
{code}
$list = $formdata[0];
{/code}
{css:ad_style}
{css:column_node}
{js:column_node}
<style>
.form_ul_div.l input{float:left}
.form_ul .n-h{float:none}
.ad_middle table textarea{min-width:0;min-height: 0;}
.option_del_box{display:inline-block;width:16px;height:16px;cursor:pointer;float:right;position: relative;right: 50px;top: 4px;}
.option_del{display:none;width:16px;height:16px;cursor:pointer;float:right;background: url('{$RESOURCE_URL}close_plan.png') 0 0 no-repeat;}
.option_del_b{width:16px;height:16px;cursor:pointer;float:right;position: relative;right: 140px;top: 4px;background: url('{$RESOURCE_URL}close_plan.png') 0 0 no-repeat;}
</style>

<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="./run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" class="ad_form h_l">
			{if $_INPUT['id']}
				<h2>编辑线路信息</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">线路名：</span>
								<input type="text" value="{$list['name']}" name='name' style="width:440px;">
								<font class="important">线路名必填</font>
								<font class="important" style="color:red">*</font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">线路描述：</span>
								<textarea rows="3" cols="80" name='brief'>{$list['brief']}</textarea>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">运行时间：</span>
								<input type="text" value="{$list['time']}" name='time' style="width:440px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">价格：</span>
								<input type="text" value="{$list['price']}" name='price' style="width:440px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="title">公交公司：</span>
								<input type="text" value="{$list['gjgs']}" name='gjgs' style="width:440px;">        
							</div>
						</li>
							<li class="i">
							<div class="form_ul_div">
								<span class="title">线路种类：</span>
								<input type="text" value="{$list['kind']}" name='kind' style="width:440px;">        
							</div>
						</li>
					</ul>
					{else}
					<h2>新增线路信息</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">线路编号：</span>
								<input type="text" value="{$list['routeid']}" name='name' style="width:440px;">
								<font class="important">线路名必填</font>
								<font class="important" style="color:red">*</font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">线路名：</span>
								<input type="text" value="{$list['name']}" name='name' style="width:440px;">
								<font class="important">线路名必填</font>
								<font class="important" style="color:red">*</font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">线路描述：</span>
								<textarea rows="3" cols="80" name='brief'>{$list['brief']}</textarea>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">运行时间：</span>
								<input type="text" value="{$list['time']}" name='time' style="width:440px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">价格：</span>
								<input type="text" value="{$list['price']}" name='price' style="width:440px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="title">公交公司：</span>
								<input type="text" value="{$list['gjgs']}" name='gjgs' style="width:440px;">        
							</div>
						</li>
							<li class="i">
							<div class="form_ul_div">
								<span class="title">线路种类：</span>
								<input type="text" value="{$list['kind']}" name='kind' style="width:440px;">        
							</div>
						</li>
					</ul>
				{/if}
				{if $a}
				<input type="hidden" name="a" value="{$a}" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				{else}
				<input type="hidden" name="a" value="update" />
				<input type="hidden" name="id" value="{$list['id']}" />
				{/if}
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br />
				<input type="submit" name="sub" value="确定" class="button_6_14"/>
				<input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
{template:foot}