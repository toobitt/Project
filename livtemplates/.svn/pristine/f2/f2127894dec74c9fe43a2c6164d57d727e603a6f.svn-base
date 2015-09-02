{template:head}
{css:ad_style}
{css:column_node}
{js:column_node}
<style>
.weather-img{float:left;margin-right:20px;width:76px;height:68px;position:relative;border:1px dashed #CCC;}
.weather-img span{position:absolute;right:0;cursor:pointer;display:none;}
.weather-img img, .weather-pic-item img{width:70px;height:65px;}
.weather-img-box{clear:left;margin-left:72px;}
.weather-img-box .weather-pic-item{float:left;margin:10px 10px 0 0;text-align:center;}
.weather-pic-item span{display:block;}
</style>
<script type="text/javascript">
	function show_form(name)
	{
		if (name =='edit')
		{
			$('#edit').css('display','block');
			$('#config').css('display','none');
			$("input[name='a']").val('update');
			$("input[name='sub']").val('更新');
			hg_resize_nodeFrame();
		}else if(name=='config'){
			$("input[name='a']").val('config');
			$("input[name='sub']").val('确认');
			$('#edit').css('display','none');
			$('#config').css('display','block');
			var url= './run.php?mid='+gMid+'&a=config_detail&id='+$("input[name='id']").val();
			hg_ajax_post(url);
			hg_resize_nodeFrame();
		}
	}
	function hg_config_detail_back(html)
	{
		$("#config").html(html);
	}

	$(function ($) {
		$(document).on( 'click', '#add', function () {
			var newUsr = $('.weather-usr:first').clone();
			$(this).before( newUsr );
		});
	});
	var Mid = '';
	var Flag = '';
	function changePic(id,flag)
	{
		Mid = id;
		Flag = flag;
		var url= './run.php?mid='+gMid+'&a=get_many_material';
		hg_ajax_post(url);
		if($("#pic_"+Mid).css('display')=="none")
		{
			$("#pic_"+Mid).slideDown();
			$("#delete_"+Mid+"_"+flag).show();
			for(var i=0;i<7;i++)
			{
				if(i!=Mid)
				{
					$("#pic_"+i).css('display','none');
					$("#delete_"+i+"_one").css('display','none');
					$("#delete_"+i+"_two").css('display','none');
				}
			}
		}else{
			$("#pic_"+Mid).css('display','none');
			$("#delete_"+Mid+"_one").css('display','none');
			$("#delete_"+Mid+"_two").css('display','none');
		}
		
		hg_resize_nodeFrame();
	}
	function hg_material_back(html)
	{
		$("#pic_"+Mid).html(html);
		hg_resize_nodeFrame();
	}
	function delete_img(id,flag)
	{
		$("#img_"+id+"_"+flag).attr('src', '');
		$("#user_img_"+id+"_"+flag).val('');
	}
	function selectImg(id)
	{
		var url='';
		url = $('#img_url_'+id).attr('src')
		$("#img_"+Mid+"_"+Flag).attr('src', url);
		$("#user_img_"+Mid+"_"+Flag).val(id);
		$("#pic_"+Mid).css('display','none');
		$("#delete_"+Mid+"_"+Flag).css('display','none');
	}
</script>
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
    <div class="ad_middle">
        <form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
        {if !$formdata['data']}
        	<h2>添加城市</h2>
            	<ul class="form_ul">
					<li class="i">
						<div class="form_ul_div">
							<span  class="title">名称：</span>
							<input  type="text" name="name" style="width:440px;"  class="info-title info-input-left t_c_b" value="{if $formdata[0]['name']}{$formdata[0]['name']}{/if}" />
						</div>
					</li>
					<li class="i">
					<div class="form_ul_div">
						<span class="title">描述：</span>
						<textarea rows="2" class="info-description info-input-left t_c_b" name="brief" >{if $formdata[0]['brief']}{$formdata[0]['brief']}{/if}</textarea>
					</div>
					</li>
					<li class="i">
						<div class="form_ul_div clear">
						<span class="title">父级分类：</span>
						{code}
							$hg_attr['node_en'] = 'weather_city_node';
						{/code}
						{template:unit/class,fid,$formdata[0]['fid'], $node_data}
						</div>
					</li>	
				</ul>
		 {else}
		 	<h2>{$formdata['data'][0]['name']}</h2>
		 	<!--  
		 	<div class="ext-tab">
                    <a href="javascript:void(0)" onclick="show_form('edit')" class="ext-current">编辑天气信息 </a>
                    <a href="javascript:void(0)" onclick="show_form('config')">配置天气信息 </a>
			</div>
			-->
		 	<ul class="form_ul" id='edit' style="display: block">
		 		{foreach $formdata['data'] as $key=>$val}
		 			
		 			<li class="i">
						<div class="form_ul_div">
							<span  class="title">日期：</span>
						 	<span>{$val['w_date']}</span>
					 	</div>
					 </li>
		 			{foreach $formdata['fields'] as $k=>$v}
		 				{code}
		 					$img_id = explode(',',$val['img']);
		 				{/code}
		 				{if $k=='img'}
		 				<li class="i clear">
					 			<div class="form_ul_div">
						 			<span  class="title">{$v['user_desc']}：</span>
						 			{code}
						 				$url1 = $val['icon'][0]['host'].$val['icon'][0]['dir'].$val['icon'][0]['filepath'].$val['icon'][0]['filename'];
						 				$url2 = $val['icon'][1]['host'].$val['icon'][1]['dir'].$val['icon'][1]['filepath'].$val['icon'][1]['filename'];
						 			{/code}
						 			<div class="weather-img" onclick="changePic({$key},'one')"><img alt="" src="{$url1}" id="img_{$key}_one"><span id="delete_{$key}_one" onclick="delete_img({$key},'one')">x</span></div>
						 			<div class="weather-img" onclick="changePic({$key},'two')"><img alt="" src="{$url2}" id="img_{$key}_two"><span id="delete_{$key}_two" onclick="delete_img({$key},'two')">x</span></div>
						 			<span style="cursor: pointer;position:absolute;display:none;" id="sive_pic_{$key}">保存图片</span>
									<input type="hidden" name="img_{$key}[]" id="user_img_{$key}_one" value="{$img_id[0]}" />
									<input type="hidden" name="img_{$key}[]" id="user_img_{$key}_two" value="{$img_id[1]}" />
						 			<div id = "pic_{$key}" class="weather-img-box clear" style="display: none"></div>
					 			</div>
					
					 		</li>
		 				{else}
		 				{if is_array($val[$v['user_field']])}
		 					<li class="i">
					 			<div class="form_ul_div">
						 			<span  class="title">{$v['user_desc']}：</span>
						 			{foreach $val[$v['user_field']] as $kk=>$vv}
						 			<input type="text" name="{$v['user_field']}_{$key}[{$kk}]"/ value="{$val[$v['user_field']][$kk]}">
						 			{/foreach}
					 			</div>
					 		</li>
					 		
					 	{else}
					 	<li class="i">
					 			<div class="form_ul_div">
						 			<span  class="title">{$v['user_desc']}：</span>
						 			<input type="text" name="{$v['user_field']}_{$key}"/ value="{$val[$v['user_field']]}">
					 			</div>
					 	</li>
						{/if}
						{/if}	 			
		 			{/foreach}
		 		{/foreach} 
		 	</ul>
		 	
		 	<div id ='config' style="display: none"></div>
		 		
         {/if}      
            <input type="hidden" name="a" value="{$a}" />
            <input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
            <input type="hidden" name="referto" value="{$_INPUT['referto']}" />
            <input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
            <br />
            <input type="submit" name="sub" value="{$optext}" class="button_6_14"/>
        </form>
    </div>
    <div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
</div>
{template:foot}