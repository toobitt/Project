<?php ?>
{template:head}
{css:2013/form}
{css:2013/button}
{css:hg_sort_box}
{css:cinema_form}
{js:ajax_upload}
{js:live/my-ohms}
{js:common/common_form}
{js:2013/ajaxload_new}
{js:cinema/cinema_form}
{code}
if ( is_array($formdata ) )
{  
	foreach ( $formdata as $k => $v ) 
	{
		$$k = $v;
	}
}		
if($id)
{
	$optext="更新";
	$ac="update";
}
else
{
	$optext="添加";
	$ac="create";
}
//print_r($formdata);
{/code}
<body>
<style>
.content .img-batch {float: left;position: relative;margin: 0px 5px 5px;}
.content li{float:left;width:50px;height:50px;margin-right:10px;margin-bottom: 5px;border: 1px solid #9f9f9f;position:relative}
.content img{width:50px;height: 50px;vertical-align: middle;}
.content .del{width: 15px;height: 15px;background: #5394e4;color: #fff;font-size: 25px;line-height: 12px;text-align: center;border-radius: 50%;position: absolute;top: -8px;right: -8px;cursor:pointer;display: none;}
.content .img-batch:hover .del{display:block}
.content .img-batch:last-child .del{display:none;}
#form-edit-box{display:inline-block;}

/*编辑器图片管理功能暂时不用 css隐藏*/
.editor-current-img .img-indexpic{display:none;}
.pic-edit-btn{opacity:0;}
.editor-current-img:hover .img-option{display:none;}
.edit-slide-sort{display:none;}
</style>
<form class="m2o-form ueditor-outer-wrap" name="editform" action="run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" id="seek_form" data-id="{$id}">
    <div id="ohms-instance" style="position:absolute;display:none;"></div>
    <header class="m2o-header">
    <div class="m2o-inner">
        <div class="m2o-title m2o-flex m2o-flex-center">
            <h1 class="m2o-l">{$optext}影院</h1>
            <div class="m2o-m m2o-flex-one">
                 <input placeholder="填写影院名称" name="title" class="m2o-m-title" title="{$title}"  value="{$title}"/>
            </div>
            <div class="m2o-btn m2o-r">
                <input type="submit" value="保存信息" class="m2o-save" name="sub" id="sub" />
                <span class="m2o-close option-iframe-back"></span>
                <input type="hidden" name="a" value="{$ac}" />
				<input type="hidden" name="{$primary_key}" value="{$id}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
            </div>
        </div>
      </div>  
    </header>
    <div class="m2o-inner">
    <div class="m2o-main m2o-flex">
        <aside class="m2o-l">
        	<div class="m2o-item">
        		<div class="indexpic">
        			{if $img_info}
        			<img src="{$img_info}" style=" width: 176px; height: 176px; "/>
        			{/if}
                    <span class="{if $img_info}indexpic-suoyin-current{else}indexpic-suoyin{/if}"></span>
                 </div>
                 <input type="file" name="indexpic" style="display:none;"/>
                 <input type="hidden" class="indexid" name="indexpic_id" value="{$indexpic}" />
        	</div>
			{if $video}
			<div class="m2o-item">
        		<div id="hoge_edit_play" style="">					
					<object id="video" type="application/x-shockwave-flash" data="{$markswf_url}vodPlayer.swf?{$video['time']}" width="190" height="150">
						<param name="movie" value="{$markswf_url}vodPlayer.swf?{$formdata['time']}">
						<param name="allowscriptaccess" value="always">
						<param name="wmode" value="transparent">
						<param name="allowFullScreen" value="true">
						<param name="flashvars" value="jsNameSpace=adminDemandPlayer&startTime={$video['start']}&duration={$video['duration']}&videoUrl={$video['url']}&videoId={$video['vodid']}&snap=true&autoPlay=false&snapUrl={$video['snapUrl']}">
					</object>
					<span></span>
				</div>
			</div>
			<div class="m2o-item">
                <label>手机端语音转化识别的文字:</label>
                <textarea name="word" cols="35" rows="5" ></textarea>
			</div>
			{/if}
        </aside>
        <section class="m2o-m m2o-flex-one">
        	<div class="m2o-item lbs-info">
            	<a class="lbs-title active" data-type="basic">基本信息</a>
            	<input type="hidden" name="is_expand" value="0" />
            </div>
            	<div class="m2o-item">
	        		<label class="title">电话: </label>
	        		<ul class="tel-info">
	        			{foreach $tel as $v}
	        			<li class="tel-item"><input type="text" name="tel_name[]" placeholder="电话名称(用途)" value="{$v['telname']}" /> : <input type="tel" name="tel[]" placeholder="电话号码(含手机)" value="{$v['tel']}" /><em class="del" data-type="del" title="删除电话"></em></li>
	        			{/foreach}
	        			<li class="tel-item"><input type="text" name="tel_name[]" placeholder="电话名称(用途)" value="" /> : <input type="tel" name="tel[]" placeholder="电话号码(含手机)" value="" /><em class="add" data-type="add" title="新增电话"></em></li>
	        		</ul>
	        	</div>
            <div class="basic-info">
            	<div class="m2o-item">
	        		<label class="title">描述简介: </label>
	        	<textarea name="content" class="hide-textarea" id="form-edit-box">{code}echo htmlspecialchars_decode($content);{/code}</textarea>
	        	</div>
	        	<div class="m2o-item m2o-dotted"></div>
	        	<div class="m2o-item">
	        		<label class="title">地址: </label>
					<div class="address-area">
						{code}
						$info = array($province_id, $city_id, $area_id);
						{/code}
						{template:form/address_search, '', '', $info, ''}
	        		</div>
	        	</div>
	        	<div class="m2o-item" style="padding-top:0; ">
	        		<label class="title"></label>
					<input type="text"  id="detailed_address"  value="{$address}" name='address' style="width:465px;" {if !$address}id="detailed_address"{/if}/>
	        	</div>
	        	<div class="m2o-item">
	        		<label class="title"></label>
					{code}
						$hg_bmap = array(
							'height' => 400,
							'width'  => 480,
							'longitude' => isset($baidu_longitude) ? $baidu_longitude : '0', 
							'latitude'  => isset($baidu_latitude) ? $baidu_latitude : '0',
							'zoomsize'  => 13,
							'areaname'  => $_configs['areaname'] ? $_configs['areaname'] : '南京',
							'is_drag'   => 1,
						);
					{/code}
					{template:map/baidu_map,baidu_longitude,baidu_latitude,$hg_bmap}
	        	</div>
        	</div>
            <div class="extend-info"></div>
        </section>
    </div>
    </div>
    
</form>
</body>

<script>
$(function(){
	var	init = function(){
		$.myueditor = $.m2oEditor.get( 'form-edit-box', {
			initialFrameWidth : 590,
			initialFrameHeight : 290,
			editorContentName : 'content',	//编辑器内容的name名
			slide : false,					//风格
			relyDom : '.m2o-inner',		//slide风格依赖dom（用于计算定位和高度）
			needCount : true,				//字数统计
			countDom : '#editor-count',		//字数统计dom
		} );
	};

	$.includeUEditor( init, {
		plugins : ['imgmanage']
	});
})
</script>
<script type="text/x-jquery-tmpl" id="add-tel-tpl">
	<li class="tel-item"><input type="text" name="tel_name[]" placeholder="电话名称(用途)" value="" /> : <input type="tel" name="tel[]" placeholder="电话号码(含手机)" value="" /><em class="add" data-type="add" title="新增电话"></em></li>
</script>
