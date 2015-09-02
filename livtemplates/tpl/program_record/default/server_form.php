{template:head}
{css:ad_style}
{js:column_node}
{css:column_node}
<script type="text/javascript">


</script>
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
	<form name="editform" action="" method="post" class="ad_form h_l" onsubmit="return true;"><!--  hg_form_check()-->
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
			<!--
<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">服务标识：</span>
						<div class="input " style="width:120px;float: left;">
							<span class="input_left"></span>
							<span class="input_right"></span>
							<span class="input_middle">
								<input type="text" name="mark" id="mark" size="14" style="width:110px;height: 18px;line-height: 20px;font-size:12px;padding-left:5px;float: left;border:none;" value="{$mark}"></span>
						</div>
						<span class="error" id="mark_tips" style="display:none;"></span>						
					</div>
				</div>
			</li>

			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">服务协议：</span>
					{code}
						$item_source = array(
							'class' => 'down_list',
							'show' => 'protocol_show',
							'width' => 80,/*列表宽度*/		
							'state' => 0, /*0--正常数据选择列表，1--日期选择*/
							'is_sub'=>1,
						);
						$default = $protocol ? $protocol : 'http://';
						$protocol_array = array(
							'http://' => 'http://'
						);
					{/code}
					{template:form/search_source,protocol,$default,$protocol_array,$item_source}
					<span class="error" id="protocol_tips" style="display:none;"></span>
				</div>
			</li>-->
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
			<!--
<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">服务目录：</span>
						<div class="input " style="width:110px;float: left;">
							<span class="input_left"></span>
							<span class="input_right"></span>
							<span class="input_middle">
								<input type="text" name="dir" id="dir" size="14" style="width:100px;height: 18px;line-height: 20px;font-size:12px;padding-left:5px;float: left;border:none;" value="{$dir}"></span>
						</div>
						<span class="error" id="dir_tips" style="display:none;"></span>						
					</div>
				</div>
			</li>
-->
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
				<div class="form_ul_div" style="width: 350px;background-color: #ccc;height: 40px;opacity: 0.7;line-height: 40px;padding-left: 15px;">
					<span>录制存放：</span><span>{$config['default_record_file_path']}</span><!--
<br/>
					<span>时移存放：</span><span>{$config['default_timeshift_file_path']}</span>
-->
				</div>
			</li>			
			<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">状态：</span>
						<div class="input " style="width:110px;float: left;">
							<input type="radio" name="state" value="1" {if $state}checked{/if}/>&nbsp;是
							&nbsp;&nbsp;<input type="radio" name="state" value="0" {if !$state}checked{/if}/>&nbsp;否
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