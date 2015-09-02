{template:head}
{css:ad_style}
{css:column_node}
{js:column_node}
{js:lbs_sort}
<script type="text/javascript">
$(function($){
	$('.img-box').hover(function(){
    	$('.del-btn').show();
        },function(){
        	$('.del-btn').hide();
        });
});

	$.format = function (str, param) {
		if ( arguments.length === 1 ) {
			return function () {
				var args = $.makeArray( arguments );
				return $.format.apply( null, args.unshift(str) );
			};
		}
		if ( arguments.length > 2 ) {
			param = $.makeArray( arguments ).slice(1);
		}
		if ( !$.isArray( param ) ) {
			param = [ param ];
		}
		$.each(param, function (i, n) {
			str = str.replace( new RegExp('\\{' + i + '\\}', 'g'), n );
		});
		return str;
	}
	function hg_resize_columnFrame()
	{
		var height = $(document).height();
		parent.$('#column-iframe-box').height(height).find('iframe').css("height", "100%");
		parent.hg_resize_nodeFrame();
	}
	$(function () {
		hg_resize_columnFrame();
		setTimeout(hg_resize_columnFrame, 1000);
	});
	$(function ($){
		window.gIndex = $(".settingField").length;
	});
	
	function hg_open_userinfo(obj)
	{
		if($(obj).attr('checked'))
		{
			$("#showuserinfo").show();
			hg_resize_columnFrame();
		}
		else
		{
		$("#showuserinfo").hide();
		}
	} 
	
	function del_image()
	{
		$(".img-box").hide();
		$("#bgimage").val('1');
	}


</script>
<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l" id="contribute_sort" name="contribute_sort">
	<ul class="form_ul">
		<li class="i">
			<div class="form_ul_div">
				<span  class="title">名称：</span>
				<input  type="text" name="name" style="width:440px;"  class="info-title info-input-left t_c_b" value="{if $formdata['name']}{$formdata['name']}{/if}" />
			</div>
		</li>
		<li class="i">
		<div class="form_ul_div">
			<span class="title">分类描述：</span>
			<textarea rows="2" class="info-description info-input-left t_c_b" name="brief" >{if $formdata['brief']}{$formdata['brief']}{/if}</textarea>
		</div>
		</li>
		<li class="i">
			<div class="form_ul_div clear">
			<span class="title">父级分类：</span>
			{code}
				$hg_attr['node_en'] = 'lbs_node';
			{/code}
			{template:unit/class,fid,$formdata['fid'], $node_data}
			</div>
		</li>
		<li class="i">
			<div class="form_ul_div">
				<span class="title">图片上传：</span>
				{code}
					$img = '';
					if($formdata['image'] && $formdata['image']['host'])
					{	
						$img = $formdata['image'];
						$image = $img['host'] . $img['dir'] .'100x75/'. $img['filepath'] . $img['filename'];
					}
				{/code}
				{if $image}
				  <span class="img-box">
                    <a class="del-btn" onclick="del_image()">X</a>
					<img src="{$image}" alt="背景图" />
			     </span>
			     <input type="hidden" name="bgimage" value="" id="bgimage"/>
				{/if}
				<input type="file" name ='Filedata' >
			</div>
		</li>
	</ul>
	<input type="hidden" name="a" value="{$a}" />
	<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
	<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
	<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
	<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	<br />
	<input type="submit" name="sub" value="{$optext}" class="button_6_14" />
</form>
{template:foot}