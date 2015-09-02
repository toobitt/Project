{template:head}
{css:hg_sort_box}
{js:common/auto_textarea}
{js:hg_sort_box}
{js:common/common_form}
{css:common/common_form}
{css:ad_style}
{css:column_node}
{js:column_node}
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
					<h2>{$optext}属性</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">名称：</span>
								<input type="text"  required="true" value="{$name}" name='name' style="width:200px;" />
							</div>
						</li>
					
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">基础属性</span>
								{code}
									$attr_source = array(
                                		'class' => 'attr_data down_list',
                                		'show'  => 'attr_data_show',
                                		'width' => 200,
                                		'state' => 0,
                                		'is_sub'=> 1,
                                    );
                                    
                                    if(!$attr_id)
                                    {
                                    	$attr_id = 0;
                                    }
                                    
                                    $attr_arr[0] = '选择属性';
                                    if($attr_data)
                                    {
                                    	foreach($attr_data AS $_k => $_v)
                                    	{
											 $attr_arr[$_v['id']] = $_v['name'];                                  		
                                    	}
                                    }
								{/code}
								<div style="width:100%;height:30px;">
								{template:form/search_source,attr_id,$attr_id,$attr_arr,$attr_source}
								</div>
							</div>
						</li>
					
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">所属角色</span>
								{code}
									$attr_role = array(
                                		'class' => 'attr_role down_list',
                                		'show'  => 'attr_role_show',
                                		'width' => 200,
                                		'state' => 0,
                                		'is_sub'=> 1,
                                    );
                                    
                                    if(!$role_type_id)
                                    {
                                    	$role_type_id = 0;
                                    }
                                    
								{/code}
								<div style="width:100%;height:30px;">
								{template:form/search_source,role_type_id,$role_type_id,$_configs['role_type'],$attr_role}
								</div>
							</div>
						</li>
						
						<li class="i">
                            <div class="form_ul_div clear">
                            <span class="title">分组：</span>
                            {code}
                                $hg_attr['node_en'] = 'attribute_group';
                            {/code}
                            {template:unit/class,group_id,$group_id, $node_data}
                           </div>
                        </li>
						
					</ul>
				<input type="hidden" name="a" value="{$a}" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<input type="hidden" name="ui_id" value="{$_INPUT['ui_id']}" />
				<br />
				<input type="submit" name="sub" value="{$optext}" class="button_6_14"/>
				<input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
<!-- 模板end -->
{template:foot}