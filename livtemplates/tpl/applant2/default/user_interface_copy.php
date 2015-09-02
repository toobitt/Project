{template:head}
{css:hg_sort_box}
{js:common/auto_textarea}
{js:hg_sort_box}
{js:common/common_form}
{css:common/common_form}
{css:ad_style}
{if is_array($formdata)}
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
					<h2>复制<font color="red">{$name}</font>的属性</h2>
					<ul class="form_ul">
						<li class="i select-attr-type-wrap">
							<div class="form_ul_div">
								<span  class="title">复制到</span>
								{code}
									$attr_ui = array(
                                		'class'  => 'attr_ui down_list',
                                		'show'   => 'attr_ui_show',
                                		'width'  => 200,
                                		'state'  => 0,
                                		'is_sub' => 1,
                                    );
                                    
                                    $attr_ui_arr = array();
                                    $_default = 0;
                                    $attr_ui_arr[$_default] = '请选择一个选择UI';
                                	foreach($ui_data AS $_k => $_v)
                                	{
                                		 if($id == $_v['id'])
                                		 {
                                		 	continue;
                                		 }
                                		 $attr_ui_arr[$_v['id']] = $_v['name'];
                                	}
								{/code}
								<div style="width:100%;height:30px;">
								{template:form/search_source,ui_id,$_default,$attr_ui_arr,$attr_ui}
								</div>
							</div>
						</li>
					</ul>
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="a" value="do_copy_attr" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br />
				<input type="submit" name="sub" value="复制" class="button_6_14"/>
				<input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
{template:foot}