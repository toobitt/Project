{template:head}
{css:ad_style}
{code}
function getArray($arr)
{
	return $arr ? $arr : array('');
}
{/code}
<style>
.form_ul{margin-top:10px;margin-bottom:50px; width:100%;}
.fl{float:left;width:350px;}
.optBtn{cursor:pointer;}
.optBtn:hover{text-decoration: underline;}
</style>
<script type="text/javascript">
	function update_linfo()
	{
		window.location.href="./run.php?mid={$_INPUT['mid']}&a=update_linfo&id={$_INPUT['id']}&routeid={$_INPUT['routeid']}&infrm=1";
	}

	$(function ($) {
		var resort = function (el) {
			$(el).find('.indexLabel').each(function (i, n) {
				$(this).text( i + 1 );
		    });
		};
		var form = $("form");
		form.
			on("click", ".optBtn.del", function () {
				var btn = $(this), div = $(this).closest(".form_ul_div"), el = div.closest('.sortableList');
				if (div.find(".saveFlag").val()) {
					jConfirm("你确定要删除吗？", "删除提醒", function (yes) {
						yes && ( div.remove(), resort( el ) );
					}).position(btn);
				} else {
					div.remove();
					resort( el );
				}
			})
			.on("click", ".optBtn.add", function () {
				var div = $(this).closest(".form_ul_div");
				var tpl = div.clone();
				tpl.find("input").val("");
				div.after(tpl);
				resort( div.closest('.sortableList') );
			});
		$(".sortableList").sortable({
			items: '.form_ul_div',
			handle: '.drag',
			revert: true,
	        cursor: 'move',
	        containment: 'document',
	        scrollSpeed: 100,
	        tolerance: 'intersect',
	        stop: function () {
		    	resort(this);
	        }
		});
	});
</script>
<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="./run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" class="ad_form h_l">
			{if $_INPUT['id']}
				<h2>编辑站点信息 &nbsp&nbsp&nbsp&nbsp&nbsp
					<span type="button" class="button_6"  onclick="update_linfo()">获取线路信息</span>
				</h2>
					<ul class="form_ul clear">
					   <li class="fl"><span class="title"><h4>8684：上行</h4></span></li>
					   <li class="fl"><span class="title"><h4>公交总站：上行</h4></span></li>
						<li class="fl sortableList">
						   {code}$formdata['stands'][1] = getArray($formdata['stands'][1]);{/code}
						   {foreach $formdata['stands'][1] as $k=>$v}
						  
						   {template:unit/stand_edit,newsup[],oldsup[]}
						   {/foreach}
						</li>
						
						<li class="fl sortableList">
							{code}$formdata['busstands'][1] = getArray($formdata['busstands'][1]);{/code}
							{foreach $formdata['busstands'][1] as $k=>$v}
							{template:unit/stand_edit,newbussup[],oldbusup[], true}
							
							{/foreach} 
						</li>
				  </ul>
					<ul class="form_ul clear">
						<li class="fl"><span class="title"><h4>8684：下行</h4></span></li>
						<li class="fl"><span class="title" ><h4>公交总站：下行</h4></span></li>
						<li class="fl sortableList">
						   {code}$formdata['stands'][2] = getArray($formdata['stands'][2]);{/code}
						   {foreach $formdata['stands'][2] as $k=>$v}
							<div class="form_ul_div">	
								{template:unit/stand_edit,newsdo[],oldsdo[]}     
							</div>
							{/foreach}
						</li>
						
						<li class="fl sortableList">
						   {code}$formdata['busstands'][2] = getArray($formdata['busstands'][2]);{/code}
						   {foreach $formdata['busstands'][2] as $k=>$v}
					
						   {template:unit/stand_edit,newbusdo[],oldbusdo[], true}
							
						   {/foreach}
						</li>
					</ul>
					{else}
				{/if}
				<input type="hidden" name="a" value="update_stand" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<input type="hidden" name="linfo" value='{$formdata['linfo']}' />
				<input type="hidden" name="html" value="ture" />
				<div class="clearfix">
				<input type="submit" name="sub" value="确定" class="button_6_14"/>
				<input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
			 </div>  
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
{template:foot}