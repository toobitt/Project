
{template:head}
{css:ad_style}
{css:vote_style}
{js:mms_default}
{js:input_file}
{js:message}
{js:vote}
{css:column_node}
{js:column_node}

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
		<form name="editform" action="./run.php?mid={$_INPUT['mid']}&a={$action}" method="post" enctype='multipart/form-data' class="ad_form h_l">
			<h2>{$optext}频道</h2>
			<ul class="form_ul">
				<li class="i">
					<div class="form_ul_div">
						<span class="title">频道名称：</span>
						<input type="text" onblur="input_content_color(2);" onfocus="input_content_color(2);" id="required_2" name="web_station_name" value="{$web_station_name}" style="width:440px;"/>
						<div style="float:right;">
						必填
						</div>
					</div>
					<div class="form_ul_div">
						<span class="title">标签：</span>
						<input type="text" onblur="input_content_color(2);" onfocus="input_content_color(2);" name="tags" value="{$tags}" style="width:440px;"/>
					</div>
					<div class="form_ul_div">	
						<span class="title">描述：</span>
						{template:form/textarea,brief,$brief}
					</div>
					<div class="form_ul_div clear">
						
						<span class="title">LOGO：</span>
						<div style="width:60px;height:60px;float:left;margin-left:12px;">
                			<img src="{$logo_url}" width="60" height="60" />
                		</div>
						<span class="file_input s" id="file_input" style="float:left;">选择文件</span>
						<span id="file_text" class="overflow file-text s"></span>
						
						<input onclick="hg_logo_value();" name="files" type="file"  value="" class="vote_file" id="f_file"  hidefocus>
					</div>
				</li>
				{if is_array($formdata['programme']) && count($formdata['programme'])>0}
				<li class="i">
					<div class="form_ul_div">	
						<span class="title">节目单：</span>
						<div style="width:400px;">
						<ul class="form_ul">
						
						{foreach $formdata['programme'] as $k=>$v }
							<li>
								<span>{$v['programe_name']}</span>&nbsp;&nbsp;<span>{$v['toff']}</span>
							</li>
						{/foreach}
						</ul>
						</div>
					</div>
				</li>
				{/if}
				<li class="i">
					<div class="form_ul_div">
						<span class="title">创建人：</span>
						<input type="text" onblur="input_content_color(2);" disabled="true" onfocus="input_content_color(2);" name="username" value="{$username}" style="width:240px;"/>
					</div>
					<div class="form_ul_div">
						<span class="title">创建时间：</span>
						<input  id="create_time" type="text" value="{$create_time}" autocomplete="off" size="20" style="width:240px;" onfocus="if(1){WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd HH:mm:ss'});}" name="create_time">
					</div>
					<div class="form_ul_div">
						<span class="title">关注：</span>
						<input type="text" name="collect_count" value="{$collect_count}" style="width:240px;"/>
					</div>
					<div class="form_ul_div">
						<span class="title">状态：</span>
						<input type="radio" name="state" value="0" {if !$state}checked="checked"{/if} />待审核
						<input type="radio" name="state" value="1" {if $state==1}checked="checked"{/if} />审核通过
						<input type="radio" name="state" value="2" {if $state==2}checked="checked"{/if} />审核不通过
					</div>
				</li>
				
			</ul>
		</br>
		<input type="submit" name="sub" value="{$optext}" id="sub" class="button_6_14"/>
		<input type="hidden" name="a" value="{$action}" id="action" />
		<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
		<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
		<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
		</form>
<!-- other_box -->
		<div id="livwindialog" class="lightbox" style="width: 780px; z-index: 1000; position: absolute; visibility: visible; margin-left: -300px; top: 30%; left: 50%; display: none; ">
			<div class="lightbox_top">
				<span class="lightbox_top_left"></span>
				<span class="lightbox_top_right"></span>
				<span class="lightbox_top_middle"></span>
			</div>
			<div class="lightbox_middle">
				<span style="position:absolute;right:25px;top:25px;z-index:1000;" onclick="hg_otherClose();">
					<img width="14" height="14" id="livwindialogClose" src="{$RESOURCE_URL}close.gif" style="cursor: pointer; " />
				</span>
				<div id="livwindialogbody" class="text" style="max-height:500px;"></div>
			</div>
			<div class="lightbox_bottom">
				<span class="lightbox_bottom_left"></span>
				<span class="lightbox_bottom_right"></span>
				<span class="lightbox_bottom_middle"></span>
			</div>
		</div>
<!-- other_box -->
		</div>
		<div class="right_version">
			<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
		</div>{template:foot}