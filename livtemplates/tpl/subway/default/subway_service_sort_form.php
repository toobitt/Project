{template:head}
{code}
$formdata = $formdata[0];
$re = $formdata['sort_id']?$formdata['sort_name']:'请选择分类';
//print_r( $formdata );
{/code}
{css:column_node}
{css:colorpicker}
{css:ad_style}
{css:ad_style}
{css:subway}

{js:ajax_upload}
{js:2013/ajaxload_new}
{js:jqueryfn/colorpicker.min}
{js:2013/hg_colorpicker}
<style type="text/css">
.colorpicker-wrap{display:inline-block; }
</style>

<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear" style="padding-bottom: 30px;">
		<div class="ad_middle"  style="width:900px">
			<form name="editform" action="run.php?mid={$_INPUT['mid']}" method="post" class="ad_form h_l">
			<h2>{if $_INPUT['id']}编辑{else}新增{/if}服务信息分类</h2>
				<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">类别名称:</span>
								<input type="text" value="{$formdata['title']}" name='title' style="width:200px;">
								<input class="select-input color-picker" data-color="{$formdata['color']}" type="text" name="color" value="{$formdata['color']}" style="vertical-align: middle; "/>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">标识:</span>
								<input type="text" value="{$formdata['sign']}" name='sign' style="width:200px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">显示类型:</span>
								{code}
									$status_css = array(
										'class' => 'transcoding down_list',
										'show' => 'sort_audit',
										'width' => 124,
										'state' => 0,
									);
									$type_default = $formdata['type'] ? $formdata['type'] : -1;
									$_configs['type'][-1] = '所有分类';
								{/code}
								{template:form/search_source,type,$type_default,$_configs['type'],$status_css}
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">描述备注：</span>
								<textarea rows="3" cols="80" name='brief'>{$formdata['brief']}</textarea>
							</div>
						</li>
		        		<li class="i">
							<div class="form_ul_div clear">
								<span class="title">示意图：</span>
								<div id="circle_upload" class="way-content" style="display:block; ">
									<ul class="img-list flat-list">
										{if is_array($formdata['indexpic']) && count($formdata['indexpic']) > 0}
											{foreach $formdata['indexpic'] as $k => $v}
												{code}
													$img='';
													if($v)
														$img = $v['host'] . $v['dir'] . '100x75/' . $v['filepath'] . $v['filename'];
												{/code}	
												{if $img}
													<li>
														<img src="{$img}">
														<input type="hidden" name="indexpic[]" value="{$v['id']}" />
														<em class="del-image"></em>
													</li>
												{/if}
											{/foreach}
										{/if}
										<li class="add-img"></li>
									</ul>
									<input type="file" name="img" style="display:none;" class="images-file" multiple/>
								</div>
							</div>
						</li>
					</ul>
				<input type="hidden" name="a" value="{$a}" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="site_id" value="{$_INPUT['site_id']}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br/>
					<div class="temp-edit-buttons">
					   <input type="submit" name="sub" value="确定" class="edit-button submit"/>
					   <input type="button" value="取消" class="edit-button cancel" onclick="javascript:history.go(-1);"/>
				    </div>
			</form>
		</div>
	</div>
{template:foot}
<script type="text/javascript">
	$(function(){
		$('.color-picker').hg_colorpicker();
		var url = './run.php?mid=' + gMid + '&a=upload';
		$('.images-file').ajaxUpload({
			url : url,
			phpkey : 'Filedata',
			after : function( json ){
				var data = json['data'];
				data && UploadAfterData( data );
			}
		}); 
		$('.add-img').click(function(){
			$('.images-file').click();
		});
		
		var UploadAfterData = function( data ){
			var box = $('.add-img');
			data.src = $.globalImgUrl(data, '141x104');
			$('#add-pic-tpl').tmpl( data ).insertBefore( box );
		};
		$('.flat-list').on('click', '.del-image', function(){
			var $this = $(this),
				box = $this.closest('li');
			var url = './run.php?mid=' + gMid + '&a=delete_img',
				imgid = box.find('input[type="hidden"]').val();
			jConfirm( '您确定删除该图片吗？', '删除提醒' , function(result){
				if( result ){
					$.globalAjax( box, function(){
						return $.getJSON(url, {id : imgid}, function( data ){
							data && $this.closest('li').remove();
						});
					});
				}
			}).position( box );
		});
	});
</script>

<script type="text/x-jquery-tmpl" id="add-pic-tpl">
<li>
	<img src="${src}">
	<input type="hidden" name="indexpic[]" value="${id}" />
	<em class="del-image"></em>
</li>
</script>