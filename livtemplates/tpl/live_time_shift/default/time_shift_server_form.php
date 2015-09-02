{template:head}
{css:ad_style}
{js:column_node}
{css:column_node}
{if $a}
	{code}
		$action = $a;
	{/code}
{/if}

{if is_array($formdata)}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
	<div class="ad_middle">
	<form name="editform" action="run.php?mid={$_INPUT['mid']}" method="post" class="ad_form h_l" onsubmit="return true;">
		<h2>{$optext}服务器配置</h2>
		<ul class="form_ul">
			<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">服务名称：</span>
						<div class="input " style="width:110px;float: left;">
							<span class="input_left"></span>
							<span class="input_right"></span>
							<span class="input_middle">
								<input type="text" name="name" id="name" size="14" style="width:100px;height: 18px;line-height: 20px;font-size:12px;padding-left:5px;float: left;border:none;" value="{$name}"></span>
						</div>
						<span class="error" id="name_tips" style="display:none;"></span>						
					</div>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">服务IP：</span>
						<div class="input " style="width:200px;float: left;">
							<span class="input_left"></span>
							<span class="input_right"></span>
							<span class="input_middle">
								<input type="text" name="host" id="host" size="14" style="width:190px;height: 18px;line-height: 20px;font-size:12px;padding-left:5px;float: left;border:none;" value="{$host}"></span>
						</div>
						<span class="error" id="mark_tips" style="display:none;"></span>						
					</div>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">服务端口：</span>
						<div class="input " style="width:65px;float: left;">
							<span class="input_left"></span>
							<span class="input_right"></span>
							<span class="input_middle">
								<input type="text" name="port" id="port" size="14" style="width:50px;height: 18px;line-height: 20px;font-size:12px;padding-left:5px;float: left;border:none;" value="{$port}"></span>
						</div>
						<span class="error" id="port_tips" style="display:none;"></span>						
					</div>
				</div>
			</li>			
			<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">是否开启：</span>
						<div class="input " style="width:110px;float: left;">
							<input type="radio" name="is_open" value="1" {if $is_open}checked{/if}/>&nbsp;是
							&nbsp;&nbsp;<input type="radio" name="is_open" value="0" {if !$is_open}checked{/if}/>&nbsp;否
						</div>
						<span class="error" id="state_tips" style="display:none;"></span>
					</div>
				</div>
			</li>
		</ul>
	<input type="hidden" name="a" value="{$action}" />
	<input type="hidden" name="{$primary_key}" value="{$formdata['id']}" />
	<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
	<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	</br>
	<input type="submit" name="sub" value="{$optext}" id="sub" class="button_6_14" />
	</form>
	</div>
	<div class="right_version" style="width:290px;">
		<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
	</div>
{template:foot}