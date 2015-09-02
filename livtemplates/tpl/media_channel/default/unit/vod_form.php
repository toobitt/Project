<div class="info clear">
	<input  type="text" name="title" id="title{$hg_name}"   class="info-title info-input-left t_c_b" value="在这里添加标题" onfocus="text_value_onfocus(this,'在这里添加标题');" onblur="text_value_onblur(this,'在这里添加标题');" />
	<textarea rows="2" class="info-description info-input-left t_c_b" name="comment"  id="comment{$hg_name}"  onfocus="text_value_onfocus(this,'这里输入描述');" onblur="text_value_onblur(this,'这里输入描述');">这里输入描述</textarea>
</div>

<div class="info clear">
	<div class="info-left">
		<div class="info-left-top">副题</div>
		<div class="info-left-bottom">
			<input type="text"  name="subtitle" id="subtitle{$hg_name}" class="subtitle info-input-left" style="width:393px;"/>
		</div>
	</div>
	
	<div class="info-right">
		<div class="info-right-top">来源</div>
		<div class="info-right-bottom">
			{code}
				$item_source = array(
					'class' => 'down_list',
					'show' => 'source_show{$hg_name}',
					'width' => 172,/*列表宽度*/		
					'state' => 0, /*0--正常数据选择列表，1--日期选择*/
					'is_sub'=>1,
				);
				$default = -1;
				$sources[$default] = '自动';
				foreach($source as $k =>$v)
				{
					$sources[$v['id']] = $v['name'];
				}
				$source_id = 'source_id{$hg_name}';
			{/code}
			{template:form/search_source,$source_id,$default,$sources,$item_source}
		</div>
	</div>
</div>

<div class="info clear">
	<div class="info-left">
		<div class="info-left-top">关键字</div>
		<div class="info-left-bottom">
			<input type="text"  name="keywords" id="keywords{$hg_name}"  class="subtitle info-input-left" style="width:393px;"/>
		</div>
	</div>
	<div class="info-right">
		<div class="info-right-top">
			<span>作者</span>
		</div>
		<div class="info-right-bottom">
			<input type="text" name="author" id="author{$hg_name}" class="subtitle info-input-left" style="width:164px;" />
		</div>
	</div>
</div>
{code}
  $columnid = 'columnid{$hg_name}';
{/code}

<div style="background:#F9F9F9;margin:10px 0;">
{template:unit/publish, 1, $formdata['column_id']}
<script>
jQuery(function($){
    var timeid = setInterval(function(){
        if($.fn.commonPublish){
            $('#publish-1').commonPublish({
                column : 2,
                maxcolumn : 2,
                height : 224,
                absolute : false
            });
            clearInterval(timeid);
        }
    }, 100);
});
</script>
</div>