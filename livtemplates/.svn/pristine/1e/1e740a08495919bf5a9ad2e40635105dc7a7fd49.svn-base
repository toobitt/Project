{template:head}
{css:ad_style}
{css:column_node}
{js:column_node}
{css:ad_style}
{css:hg_sort_box}
{js:common/auto_textarea}
{js:hg_sort_box}
{js:special/special_newform}
{css:common/common_form}
{css:special}
{code}
$info = $formdata[0];
//print_r($info);
$css_attr['style'] = 'style="width:100px"';
foreach($app_modules[0]['app'] as $k=>$v)
{
	$apps[$k] = $v['name'];
}
$columns[0][0]='全部栏目';
//print_r($app_modules);
{/code}
<script type="text/javascript">
function add_content(id)
{
	if(id == 'add')
	{
		$('#add').css('display','');
		$('#select').css('display','none');
		//alert($("#id").val());
		if($("#id").val())
		{	
			$("#sub").val('更新');
			$("#a").val('update');
		}
		else
		{
			$("#sub").val('添加');
			$("#a").val('create');
		}
		
	}
	else if(id == 'select')
	{
		$('#add').css('display','none');
		//$('#add').css('display','');
		$('#select').css('display','');
		$("#referto").val("./run.php?mid=292&a=query&speid={$_INPUT['speid']}&id=1&infrm=1");
		$("#sub").val('查询');
		$("#a").val('query');
		$('html').height($('.special-content-form').height());
		special_resizenodeFrame();
	}
}

function change_module()
{
	var url= './run.php?mid='+gMid+'&a=get_app&app_id='+$('#app_id').val();
	hg_ajax_post(url);
}

function app_back(json)
{	
	var data = $.parseJSON(json);
	$('#app').html(get_html('module_id',data));
}

function get_html(name,data)
{
	var html = '<select name='+name+' ><option>-请选择-</option>';
	for (var i in data)
	{
		
		html = html + '<option>' +data[i] + '</option>';
	}
	html = html + '</select>';
	return html;
}

</script>
<div id="channel_form" style="margin-left:60%;"></div>
		<div class="ad_middle special-form special-content-form">
			<form action="./run.php?mid={$_INPUT['mid']}" method="post"  class="ad_form h_l" enctype="multipart/form-data">
				<h2>{if $info['id']}编辑专题内容{else}添加专题内容{/if}</h2>
				<script>
				jQuery(function($){
				    $('.ext-tab').on('click', 'a', function(){
				        var cname = 'ext-current';
				        if($(this).hasClass(cname)){
				            return;
				        }
				        $(this).addClass(cname).siblings().removeClass(cname);
				    });
				});
				</script>
				{if $_INPUT['id']}
                {else}
				<div class="ext-tab">
                    <a href="javascript:void(0)" onclick="add_content('add')" class="ext-current">手动添加内容 </a>
                    <a href="javascript:void(0)" onclick="add_content('select')">选取内容添加</a>
				</div>
				{/if}
				<div id="add" >
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span class="title">专题内容标题：</span>
								<input type="text" value="{$info['title']}" name='title' class="content-input">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="title">专题内容描述：</span>
								<textarea rows="3" cols="80" name='brief' class="content-input">{$info['brief']}</textarea>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="title">外链：</span>
								<input type="text" value="{$info['outlink']}" name='outlink' class="content-input">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">索引图：</span>
								{code}
							       	 $picinfo = unserialize($info['indexpic']);
							       	 $url = $picinfo['host'].$picinfo['dir'].'120x80/'.$picinfo['filepath'].$picinfo['filename'];
		       	                {/code}	
								<div class="special-indexpic" style="{if $picinfo}background:none;border:0;{/if}">
								    <img class="viewPic" src="{$url}" style="{if $picinfo}display:block;{/if}"/> 
								    <span class="indexpic-suoyin {if $picinfo}indexpic-suoyin-current{/if}"></span>
								</div>
								<input type="file" name="Filedata" id="Filedata"  value="submit" style="display:none;">
							</div>
						</li>
					</ul>
				</div>
				<div id="select" style="display:none">
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span class="title">权重：</span>
								<input type="text"  name='weight'>
								<span class="second-title">偏移量：</span>
								<input type="text"  name='offset'>
								<span class="second-title">条数：</span>
								<input type="text"  name='num'>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="title">更新时间：</span>
								<input type="text"  name='update_time'>
								<span class="second-title">更新频率：</span>
								<input type="text"  name='update_freq'>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">应用标识：</span>
								{code}
									$attr_app = array(
										'class' => 'transcoding down_list',
										'show'  => 'select_ap',
										'width' => 180,/*列表宽度*/
										'state' => 0,/*0--正常数据选择列表，1--日期选择*/
										'onclick' => 'change_module();'
									);
									$apps['-1'] = "-请选择-";
									$info['app_id'] = $info['app_id'] ? $info['app_id'] : -1;
								{/code}
								
								{template:form/search_source,app_id,$info['app_id'],$apps,$attr_app}
								<span class="second-title" style="padding-left:5px;">模块标识：</span>
								<div id='app' style="display:inline-block;width:120px;margin-top:3px">
									{if $info['module_id']}
									<select name='module_id' id='module_id'><option>{$info['module_id']}</option></select>
									{else}
									<select name='module_id' id='module_id'><option>-请选择-</option></select>
									{/if}
								</div>
							</div>
						</li>
						<li class="i">
							<a class="common-publish-button overflow" href="javascript:;" _default="发布至" _prev="发布至：">发布至</a>
							
						</li>
					</ul>
				</div>
				<div>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div clear" style="line-height:28px;">
								<span class="title">专题栏目：</span>
								{template:form/select,special_column_id,$info['column_id'],$columns[0], $css_attr}
							</div>
						</li>
					</ul>
				</div>
				<input type="hidden" name="a" id="a" value="{$a}" />
				<input type="hidden" name="id" id= "id" value="{$_INPUT['id']}" />
				<input type="hidden" name="speid" id= "speid" value="{$_INPUT['speid']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<input type="hidden" name="referto" id= "referto" value="{$_INPUT['referto']}" />
				<br />
				<input type="submit" name="sub" id="sub" value="{if $_INPUT['id']}更新{else}添加{/if}" class="button_6_14"/>
				{template:unit/publish_for_form, 1, $formdata['column_id']}
			</form>
		</div>
