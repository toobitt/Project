{template:head}
{css:hg_sort_box}
{js:common/auto_textarea}
{js:hg_sort_box}
{js:common/common_form}
{css:common/common_form}
{css:ad_style}
{if is_array($formdata) && $a == 'update'}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" class="ad_form h_l">
					<h2>{$optext}推送账号</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">名称</span>
								<input type="text" value="{$name}" name="name" style="width:257px;" />
								{code}
										$item_source = array(
											'class' 	=> 'down_list',
											'show' 		=> 'account_show',
											'state' 	=> 	0, /*0--正常数据选择列表，1--日期选择*/
											'is_sub'	=>	1,
										);
										
										if($plant_type)
										{
											$default = $plant_type;
										}
										else
										{
											$default = 0;
										}
									{/code}
									<div style="float:right;margin-right:212px;">
										{template:form/search_source,plant_type,$default,$_configs['push_plant'],$item_source}
									</div>
							</div>
						</li>
						
						<li class="i">	
								<div class="form_ul_div">
									<span  class="title">账号</span>
									<input type="text" value="{$account}"  name="account"  style="width:257px;" />
								</div>
						</li>

						<li class="i">	
								<div class="form_ul_div">
									<span  class="title">密码</span>
									<input type="text" value="{$password}"  name="password"  style="width:257px;" />
								</div>
						</li>
						
						<li class="i">
								<div class="form_ul_div">
									<span  class="title">描述</span>
									<textarea style="width:400px;height:200px;" name="brief">{$brief}</textarea>
								</div>
						</li>
						
					</ul>
				<input type="hidden" name="a" value="{$a}" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br />
				<input type="submit" value="{$optext}" class="button_6_14" style="margin-left:28px;"/>
				<input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
</div>
{template:foot}