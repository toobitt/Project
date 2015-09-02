{template:head}
{css:common/common_form}
{css:hg_sort_box}
{css:teditor}
{js:common/common_form}
{js:2013/ajaxload_new}
{js:hg_sort_box}
{js:common/auto_textarea}
{js:teditor}
{js:xml/xml_form}
{code}
//hg_pre($formdata);
{/code}
<style type="text/css">
.material_log{float:left;position:relative;margin:0 5px 5px 0;}
.material_log span{position:absolute;top:0px;right:0px;background:black;width:15px;height:15px;color:#FFFFFF;text-align:center;display:none;cursor:pointer;}
.down_list ul, .down_list .ul{background:white;border-bottom: 0;display: block;width:100%;clear: both;top: 24px;left: 0;position: absolute;z-index:110;max-height: 200px;overflow-y:auto;}
.transcoding{z-index:1!important;}
.ad_form .form_ul li.i input{height:21px;}
.select-file .select-file-button{color: white;background: #5b5b5b;border-radius: 2px;height: 27px;line-height: 27px;display: block;margin-top: 10px;width: 70px;text-align: center;}
</style>
{css:ad_style}
{css:column_node}
{css:template_list}
<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear" style="padding-bottom:30px;position:relative;">
			<form action="run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" class="ad_form h_l {if $_INPUT['id']}form-compare{/if}" id="form-template">
				<h2 class="template-edit-title">{if $_INPUT['id']}编辑模板{else}新增模板信息{/if}</h2>
					<div class="template-edit">
						<div class="edit-area">
						<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span class="title">模板名</span>
								<div class="select-file">
									<div class="xml-box">
										<input type="file" name="Fileda" id="Filedata1"  value="submit" class="select" style="visibility:hidden;top:-25px;position:absolute;">
										<input class="file-long" name="sourse_title" value="{$formdata['title']}">
										<a class="select-file-button" style="cursor:pointer">选择文件</a>
									</div>
								</div>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="title">分组</span>
								<div class="sort-box clear">
									{code}
					                    $group_source = array(
					                        'class' 	=> 'down_list',
					                        'show' 		=> 'type_show',
					                        'state' 	=> 	0, /*0--正常数据选择列表，1--日期选择*/
					                        'is_sub'	=>	1,
					                        'width'     => 125,
					                    );
					                    
					                    if($formdata['group_id'])
					                    {
					                    	$type_default = $formdata['group_id'];
					                    }
					                    else
					                    {
					                     	$group_default = -1;
					                    }
					                   
					                    $group_sort[-1] = '-请选择分组-';
					                    $group_sort[other] = '新建分组';
					                {/code}
					                {template:form/search_source,group_id,$group_default,$group_sort,$group_source}
					                <input type="text" name="new_group" placeholder="输入分组名" style="display:none;margin: 10px 0px 0px 85px;text-indent:10px;"/>
								</div>
							</div>
						</li>
					</ul>
					</div>
					<div class="code-edit" style="background: #eee;">
						<div id="content_container" class="editor_container clearfix">
							<textarea id="content_line" class="editor_line" cols="5" rows="20" disabled="disabled"></textarea>
							<textarea id="content" name="content" class="editor" cols="60" rows="20">{$formdata['content']}</textarea>
							<input type="hidden" name="content_xml" value='{$formdata['content']}' /> 
						</div>
					</div>
					</div>
					{if $_INPUT['id']}
					<input type="hidden" name="a" value="update" />
					{else}
					<input type="hidden" id="a_action" name="a" value="{$a}" />
					{/if}
					<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
					<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
					<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
					<input type="hidden" name="html" value="true" />
					<br /><br />
					<div class="temp-edit-buttons">
						<div class="edit-button submit preview-xml">预览</div>
						<input type="submit" name="sub" value="保存模板" class="edit-button submit"/>
						<!--<a class="edit-button cancel" onclick="javascript:history.go(-1);">取消</a>-->
					</div>
			</form>
	</div>
<script type="text/javascript">
$(function(){
	var MC = $('.ad_form');
	MC.on('focus' , '#content' , function(){
		return editor.focus(this.id);
	});
	MC.on('click' , '.overflow' , function(event){
		var self = $(event.currentTarget),
			attrid = self.attr('attrid'),
			item = self.closest('.i').find('input[name="new_group"]');
		attrid == 'other' ? item.show() : item.hide() ;
	});
});
</script>
{template:foot}