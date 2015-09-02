{template:head}
{code}
if ($webvod_list[0]) { 
	$list = $webvod_list[0];
	foreach (array_keys($list) as $k) {
		$list[$k]['id'] = $list[$k]['program_id'];
	}
}
$attrs_for_edit = array('indexpic', 'maid');
{/code}
{template:list/common_list}
{css:list}

<script type="text/javascript">
   function hg_show_change(id)
   {
	   if($('#img_box_'+id).css('display') == 'none')
	   {
		   $('#img_box_'+id).slideDown();
	   }
	   else
	   {
		   $('#img_box_'+id).slideUp();
	   }
   }

   function change_indexpic(program_id,id, img)
   {
	   	var url= './run.php?mid='+gMid+'&a=change_indexpic&program_id='+ program_id + '&pic_id='+id;
	   	var src = img.src;
	   	$('#img_' + program_id).attr('src', src);
	   	hg_ajax_post(url);
   }
   function indexpic_back(json)
   {	
   }

  
	$(function () {
		var program_id, btn, uploading = false;
		var form = $('#uploadForm');
		var file = $('#uploadForm').find('input');

		
		file.change(function () {
			form.find('[name=program_id]').val(program_id);
			uploading = true;
			form.ajaxSubmit({
				semantic: true,
				success: function (data) {
					uploading = false;
					try {
						data = $.parseJSON(data);
						if (data.msg) {
							alert('文件格式不正确!');
						} else {
							btn.parent().prepend(
								'<div style="float:left;margin-left:8px;">' +
								'<img src="' + data[0] + '" style="width:120px;height:75px;"  onclick="change_indexpic(' + program_id + ',' + data[1] + ', this)" />  </div>'
				          );
						}
					} catch (e) {
					}
				},
				data: {
					program_id: program_id,
					ajax: 1	
				}
			});
		});
		$('.uploadBtn').on('click', function () {
			if (uploading) return;
			btn = $(this);
			program_id = btn.data('program_id');
			file.trigger('click');
		});
	});
</script>


<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
</div>
{template:unit/webvodsearch}
<div class="common-list-content">
{if !$webvod_list[0]}
	<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">
		没有视频信息
	</p>
	<script>
		hg_error_html('p', 1);
	</script>
{else}
	<form method="post" action="" name="listform" style="position:relative;">
		<!-- 标题 -->
		<ul class="common-list id="list_head">
			<li class="common-list-head public-list-head clear">
				<div class="common-list-left">
					<div class="common-paixu common-list-item">
						<a class="common-list-paixu" style="cursor:pointer;"  onclick="hg_switch_order('webvodlist');"  title="排序模式切换/ALT+R"></a>
					</div>
					<div class="special-slt common-list-item">
						缩略图
					</div>
				</div>
				<div class="common-list-right">
					<div class="common-list-item open-close common-list-pub-overflow">
						发布至
					</div>
					<div class="common-list-item open-close wd70">
						码流
					</div>
					<div class="common-list-item open-close wd70">
						分类
					</div>
					<div class="common-list-item open-close wd120">
						更新时间
					</div>
				</div>
				<div class="common-list-biaoti">
					<div class="common-list-item open-close special-biaoti">
						标题
					</div>
				</div>
			</li>
		</ul>
		<ul class="common-list hg_sortable_list public-list" id="webvodlist" data-order_name="orderid">
		{foreach $webvod_list[0] as $k => $v}
			{template:unit/webvodlist}
		{/foreach}
		</ul>
		<ul class="common-list public-list">
			<li class="common-list-bottom clear">
				<!-- <div class="common-list-left">
				<input type="checkbox"  name="checkall" value="infolist" title="全选" rowtag="LI" />
				<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id','', 'ajax');"    name="batdelete">删除</a>
				</div>-->
				{$pagelink}
			</li>
		</ul>
	</form>
{/if}
</div>

<div id="infotip"  class="ordertip"></div>
<div id="getimgtip"  class="ordertip"></div>
<from id="uploadForm" style="display:none;" method="post" action="run.php" enctype="multipart/form-data">
	<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
	<input type="hidden" name="a" value="upload_pic" />
	<input type="hidden" name="program_id" />
	<input type="file" name="Filedata" />
</from>
{template:unit/record_edit}
<script>
$(function () {
	hg_resize_nodeFrame(1);
	$([parent, parent.document, parent.document.documentElement]).scrollTop(0);
});
</script>
{template:foot}