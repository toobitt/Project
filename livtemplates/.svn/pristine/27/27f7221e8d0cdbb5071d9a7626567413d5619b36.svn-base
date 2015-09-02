{template:head}
{css:ad_style}
{css:column_node}
{js:column_node}
{js:reporter_sort}
<style type="text/css">
.form_ul_div.l input{float:left}
.form_ul .n-h{float:none}
.ad_middle table textarea{min-width:0;min-height: 0;}
.option_del_box{display:inline-block;width:16px;height:16px;cursor:pointer;float:right;position: relative;right: 50px;top: 4px;}
.option_del {
display: none;
width: 16px;
height: 16px;
cursor: pointer;
float: right;
background: url('../../.././../livtemplates/tpl/lib/images/close_plan.png') 0 0 no-repeat;
}
.img-box{position:relative;display:inline-block;}
.del-btn{display:none;position:absolute;right:8px;top:0;cursor:pointer;}
</style>
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
	function hg_optionDel(obj)
	{
		if(confirm('确定删除该参数配置吗？'))
		{
			$(obj).parent().parent().remove();
		}
		hg_resize_columnFrame();
		
	}
	function hg_deleteConf(obj)
	{
		if(confirm('确定删除该配置吗？'))
		{
			$(obj).parent().parent().parent().remove();
		}
		hg_resize_columnFrame();
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
	function addconfig()
	{
		var num = ++gIndex;
		var url = "run.php?mid="+gMid+"&a=addconf&num="+num;
		hg_ajax_post(url);
	}
	function hg_addconfig_back(html)
	{
		$("#addSetting").before(html);
		hg_resize_columnFrame();
	}
	var con_sort_param = 0;
	function hg_addParam(num)
	{		
		con_sort_param = num;
		var url = "run.php?mid="+gMid+"&a=addparam&num="+num;
		hg_ajax_post(url);
	}
	function hg_addParam_back(html)
	{
		$("#pa_"+con_sort_param).before(html);
		hg_resize_columnFrame();
	}
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

	jQuery(function($){
		if (!$('.common-publish-button').size()) return;
		var pub = $("#form_publish");
		
	    $('.common-publish-button').on('click', function(event){
	        event.stopPropagation();
	        event.preventDefault();
	      
	        if ( $(this).data('show') ) {
	        	$(this).data('show', false);
	       		pub.css({top: -450})
	        } else {
	        	$(this).data('show', true);
	        	pub.css({top: 500});	
	        }
	    });
	    pub.on('click', '.publish-box-close', function () { $('.common-publish-button').trigger('click'); });
	    pub.find('.publish-box').hg_publish({
	    	change: function () {
	    		 $('.common-publish-button').html(function(){
	        		var hidden = $('.publish-name-hidden', pub).val();
	       			return hidden ? ($(this).attr('_prev') + '<span style="color:#000;">' + hidden + '</span>') : $(this).attr('_default');
	    		 });	
	    	},
	    	maxColumn: 3
	    });
	});
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
				$hg_attr['node_en'] = 'reporter_node';
			{/code}
			{template:unit/class,fid,$formdata['fid'], $node_data}
			</div>
		</li>
		<fieldset><legend>报料显示设置</legend>
		<!--  
		<li class="i">
			<div class="form_ul_div">
				<span class="title">自动填充：</span>
				<input type="checkbox" name="auto" id="auto" value="1" {if $formdata['is_auto']} checked="checked" {/if}/>
			</div>
		</li>
		-->
		<li class="i">
			<div class="form_ul_div">
				<span class="title">信息设置：</span>
				<span>自动填充</span>
				<input type="checkbox" name="auto" id="auto" value="1" {if $formdata['is_auto']} checked="checked" {/if}/>	
			</div>
		</li>
		<li class="i">
			<div class="form_ul_div">
				<span class="title">快捷输入：</span>
				<input type="checkbox" name="fastinput" id="fastinput" onclick="hg_open_fastinput(this)" value="1" {if $formdata['is_open']} checked="checked" {/if}/>
			</div>
		</li>
		
		<li class="i" id="showsort" {if !$formdata['is_open']}style="display: none" {/if}>
			<div class="form_ul_div">
				<span class="title">输入分类：</span>
				{foreach $fastInput_sort[0] AS $key=>$val}
					<input type="checkbox" name="sort[]" value="{$key}" {if in_array($key,$formdata['input_sort'])} checked="checked"{/if}/>{$val}
				{/foreach}
			</div>
		</li>
		<li class="i">
			<div class="form_ul_div">
				<span class="title">用户信息：</span>
				<input type="checkbox" name="userinfo" id="userinfo" onclick="hg_open_userinfo(this)" value="1" {if $formdata['is_userinfo']} checked="checked" {/if}/>
			</div>
		</li>
		<li class="i" id="showuserinfo" {if !$formdata['is_userinfo']}style="display: none" {/if}>
			<div class="form_ul_div">
				<span class="title">显示信息：</span>
				{foreach $_configs['userinfo'] AS $key=>$val}
					<input type="checkbox" name="user_info[{$key}]" value="1" {if $formdata['userinfo'][$key]} checked="checked"{/if}/>{$val}
				{/foreach}
			</div>
		</li>
		<li class="i">
			<div class="form_ul_div">
				<span class="title">图片上传：</span>
				{code}
					$img = '';
					if($formdata['image'])
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
		
		<li class="i">
			<a class="common-publish-button overflow" href="javascript:;" _default="发布至" _prev="发布至：">发布至</a>
			{template:unit/publish_for_form, 1, $formdata['column_id']}
		</li>
		</fieldset>
	</ul>
	<br />
	<fieldset><legend>报料转发配置</legend>
	{if $formdata['configs']}
		{foreach $formdata['configs'] as $ckey=>$conf}
		{code} $ckey = $ckey+1; {/code}
		{template:unit/addconfig}
		{/foreach}
	{/if}
	<p id="addSetting" style="text-align: center;color:green;font-size:20px;cursor:pointer;" onclick="addconfig()" >添加配置</p>
	</fieldset>
	<input type="hidden" name="a" value="{$a}" />
	<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
	<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
	<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
	<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	<br />
	<input type="submit" name="sub" value="{$optext}" class="button_6_14" />
</form>
{template:foot}