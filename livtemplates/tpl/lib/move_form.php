{code}
	foreach ($formdata as $k => $v) {
		$$k = $v;
	}
{/code}
{template:head}
{css:hg_sort_box}

<form id="move-form" method="post" action="run.php?mid={$_INPUT['mid']}&a=move">
	<div class="move-frame">
		<div class="title">
			<span>正在移动</span>
			<span class="title-label">{$content_id}:</span>
			<a class="close">X</a>
		</div>
        <div class="form-dioption-sort form-dioption-item"  id="sort-box">
                <label style="color:#9f9f9f;">分类： </label><p style="display:inline-block;" class="sort-label" _multi="{$nodevar}"> {$currentSort["$sort_id"]}<img class="common-head-drop" src="{$RESOURCE_URL}tuji/drop.png" style="position: relative;left:10px;bottom:2px;" /></p>
				<div class="sort-box-outer"><div class="sort-box-inner"></div></div>
                <input name="node_id" type="hidden" value="" id="node_id" />
        </div>		
        <input type="hidden" name="content_id" value="{$content_id}" />
		<div class="move-area clear">
			<a class="move-button">立即移动</a>
		</div>
	</div>
</form>
<div id="overlay" style="display: none;position: fixed;width: 100%;height: 100%;left: 0;top: 0;">
	<p style="margin-top: 100px;text-align: center;font-size: 28px;color: black;"></p>
</div>
<script>

$(function() {
	$('.sort-box-outer').hgSortPicker({
		change: function(id, name) {
			$('input[name=node_id]').val(id)
			$('.sort-label').text(name);
		},
		nodevar:'{$nodevar}',
	});
	
	$('.close').on('click','',function(){
		$('#move_box').fadeOut();
	});

	$('.move-button').on('click','',function(){
		$('#overlay').show().find('p').text('移动中...');
		$('#move-form').ajaxSubmit({
			success:function(data){
				var msg = '移动失败';
				data = JSON.parse(data); 
				if(data[0].success)
				{
					msg = '移动成功';
				}
				$('#overlay').fadeOut(function(){
					$('#move_box').hide();
				}).find('p').text(msg);
			}
		});
	});	
});
</script>
</body>
</html>