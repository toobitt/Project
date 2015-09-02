{template:head}
{css:2013/button}
{css:gather_list}
{code}
$content = htmlspecialchars_decode($formdata);
{/code}
<div class="wrap common-list-content">
	<form action="" method="post" enctype="multipart/form-data" class="gather-form">
	<div class="gather-box">
		<div class="title"><input type="text" value="{$formdata['title']}" name="title" required="required"/></div>
		<ul class="info clear">
			{code}
				$item_source = array(
					'class' => 'down_list sort',
					'show' => 'item_shows',
					'width' => 113,/*列表宽度*/		
					'state' => 0, /*0--正常数据选择列表，1--日期选择*/
					'is_sub'=>1,
					'onclick'=>'',
				);
				$formdata['sort_id'] = $formdata['sort_id'] ? $formdata['sort_id'] : -1;
				$name[-1] = '选择分类';
				foreach ($sorts[0] as $k=>$v)
				{
					$name[$k] = $v;
				}
			{/code}
			<li><label>作者：</label><input type="text" name="author" value="{$formdata['author']}"></li>
			<li><label>来源：</label><input type="text" name="source" value="{$formdata['source']}"></li>
			<input type="hidden" title="内容" name="content" value="{$formdata['content']}" />
			<input type="hidden" title="副标题" name="subtitle" value="{$formdata['subtitle']}" />
			<input type="hidden" title="关键字" name="keywords" value="{$formdata['keywords']}" />
			<input type="hidden" title="描述" name="brief" value="{$formdata['brief']}" />
			<input type="hidden" title="副标题" name="subtitle" value="{$formdata['subtitle']}" />
			<input type="hidden" title="索引图" name="indexpic" value="{$formdata['indexpic']}" />
			<input type="hidden" title="图片" name="pic" value="{$formdata['pic']}" />
			<input type="hidden" title="视频" name="video" value="{$formdata['video']}" />
		</ul>
		<div class="content"><p>{$formdata['content']}
		{code} foreach ($formdata['othercontent'] as $K => $v) {
		 echo $v;
		} 
		{/code};</p></div>
		<div class="info">
			<label>链接地址：</label><a href="{$formdata['source_url']}" target="_blank">{$formdata['source_url']}</a>
		</div>
		<div class="gather-button">
			<input type="submit" name="sub" value="更新" class="save-button"/>
			<input type="hidden" name="a" value="{$a}" />
			<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
			<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
			<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
		</div>
	</div>
	</form>
</div>

{template:foot}

<script type="text/javascript">
$(function($){
	var tcontent = $('.content').html();
	var thtml = tcontent.replace(/&lt;!--[\w\W\r\n]*?--&gt;/gmi, '');
	$('.content').html(thtml);
	$('.gather-form').submit(function(){
		$(this).ajaxSubmit({
			beforeSubmit:function(){
				var sortId = $('#sort_id').val();
				if(sortId == -1){
					mytip('请先选择分类数据');
					return false;
				}
			},
			dataType : 'json',
			success:function(){
				mytip('更新成功');
			},
			error:function(){
				mytip('更新失败');
			}
		});
		return false;
	});
	var mytip = function ( string ){
		$('.gather-form').find('.save-button').myTip({
			string : string,
			delay: 2000,
			dtop : 5,
			dleft : 120,
		});
	}
});
</script>




