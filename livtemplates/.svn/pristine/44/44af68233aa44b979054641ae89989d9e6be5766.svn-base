<?php ?>
{template:head}
{css:2013/form}
{css:2013/button}
{css:hg_sort_box}
{css:lbs_form}

{js:hg_sort_box}
{js:ajax_upload}
{js:live/my-ohms}
{js:common/common_form}
{js:2013/ajaxload_new}
{js:lbs/lbs_form}
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
$currentSort[$sort_id] = ($sort_id ? $sort_name : '选择分类');
$markswf_url = RESOURCE_URL.'swf/';
//var_dump($_configs['bicycle_sort_id']);
//print_r($formdata);
{/code}
<script>
var bicycle_sort_id = {$_configs['bicycle_sort_id']};
</script>
<body>
<style>
.content .img-batch {float: left;position: relative;margin: 0px 5px 5px;}
.content li{float:left;width:50px;height:50px;margin-right:10px;margin-bottom: 5px;border: 1px solid #9f9f9f;position:relative}
.content img{width:50px;height: 50px;vertical-align: middle;}
.content .del{width: 15px;height: 15px;background: #5394e4;color: #fff;font-size: 25px;line-height: 12px;text-align: center;border-radius: 50%;position: absolute;top: -8px;right: -8px;cursor:pointer;display: none;}
.content .img-batch:hover .del{display:block}
.content .img-batch:last-child .del{display:none;}
</style>
<form class="m2o-form" name="editform" action="" method="post" enctype="multipart/form-data" id="seek_form" data-id="{$id}">
    <div id="ohms-instance" style="position:absolute;display:none;"></div>
    <header class="m2o-header">
    <div class="m2o-inner">
        <div class="m2o-title m2o-flex m2o-flex-center">
            <h1 class="m2o-l">{$optext}信息点</h1>
            <div class="m2o-m m2o-flex-one">
                 <input placeholder="填写信息点名称" name="title" class="m2o-m-title" title="{$title}"  value="{$title}"/>
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
						{code}
							$account_avatar = $img_info['host'].$img_info['dir'].'176x176/'.$img_info['filepath'].$img_info['filename'];
						{/code}
        			<img src="{$account_avatar}" style=" width: 176px; height: 176px; "/>
        			{/if}
                    <span class="{if $img_info}indexpic-suoyin-current{else}indexpic-suoyin{/if}"></span>
                 </div>
                 <input type="hidden" class="indexid" name="indexpic" value="{$img_info['id']}" />
        	</div>
    		<div class="form-dioption-sort m2o-item"  id="sort-box">
	            <label style="color:#9f9f9f;">分类： </label>
	            <p style="display:inline-block;" class="sort-label" _multi="lbs_node"> {$currentSort[$sort_id]}<img class="common-head-drop" src="{$RESOURCE_URL}tuji/drop.png" style="position: relative;left:10px;bottom:2px;" /></p>
				<div class="sort-box-outer"><div class="sort-box-inner"></div></div>
	            <input name="sort_id" type="hidden" value="{$sort_id}" id="sort_id" />
	            <input name="fieldcontentdel" type="hidden" value="{$sort_id}" />
        	</div>
			<!-- <div class="m2o-item">
				<input type="checkbox" name="grade" /><span>开启评分</span>
			</div>
			<div class="m2o-item">
				<input type="checkbox" name="comment" /><span>开启评论</span>
			</div> -->
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
            	<a class="lbs-title" data-type="extend">扩展信息</a>
            	<input type="hidden" name="is_expand" value="0" />
            </div>
            <div class="basic-info">
            	<div class="m2o-item">
	        		<label class="title">描述简介: </label>
	        		<textarea name="content" cols="120" rows="5" placeholder="描述简介">{$content}</textarea>
	        	</div>
	        	
				<div class="m2o-item operating-unit" {if $sort_id != $_configs['bicycle_sort_id']}style="display:none;"{/if}>
					<label  class="title">运营单位：</label>
					{code}
						$item_css = array(
							'class' => 'transcoding down_list',
							'show' => 'sort_item',
							'width' => 150,
							'state' => 0,
							'is_sub' => 1
						);
						$company = $company[0];
						$default_contri_sort = 0;
						$company[$default_contri_sort] = '未选择';
						
						$formdata['company_id'] = $formdata['company_id'] ? $formdata['company_id'] : 0;
					{/code}
					<div class="address-area">{template:form/search_source,company_id,$formdata['company_id'],$company,$item_css}</div>
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
	        	<div class="m2o-item">
	        		<label class="title">营业时间: </label>
	        		<span class="business-time"><input type="text" placeholder="开始时间" class="btime stime" name="stime" value="{$stime}"><em>-</em><input type="text" placeholder="结束时间" class="btime etime" name="etime" value="{$etime}"></span>
	        	</div>
	        	<div class="m2o-item m2o-dotted"></div>
	        	<div class="m2o-item">
	        		<label class="title">图片信息: </label>
	        		<div class="pic-area">
	        			<div class="explain-button">上传图片</div>
		        		<div class="pic-info">
		        			<!-- <div class="pic-item pic-default">
		        				<span>添加图片</span>
		        			</div> -->
		        		{foreach $images as $pinfor}
						{code}
							$pic = $pinfor['host'].$pinfor['dir'].'115x115/'.$pinfor['filepath'].$pinfor['filename'];
						{/code}
						<div class="pic-item" _id="{$pinfor['id']}">
	        				<img src="{$pic}"/>
	        				<span class="set-index">设为索引</span>
	        				<em class="pic-del"></em>
	        				<input type="hidden" name="materials[]" value="{$pinfor['id']}" />
	        			</div>
						{/foreach}
						<input type="hidden" class="pic_allid" name="all_materials" value="" />
		        		</div>
		        		<input type="file" name="photo" style="display:none;" class="photo-file" multiple/>
	        		</div>
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
<script type="text/x-jquery-tmpl" id="add-tel-tpl">
	<li class="tel-item"><input type="text" name="tel_name[]" placeholder="电话名称(用途)" value="" /> : <input type="tel" name="tel[]" placeholder="电话号码(含手机)" value="" /><em class="add" data-type="add" title="新增电话"></em></li>
</script>
<script type="text/x-jquery-tmpl" id="add-pic-tpl">
	<div class="pic-item" _id="${id}">
		<img src="${pic}"/>
		<span class="set-index">设为索引</span>
		<em class="pic-del" title="删除图片信息"></em>
		<input type="hidden" name="materials[]" value="${id}" />
	</div>
</script>
<script type="text/x-jquery-tmpl" id="noInfo-tpl">
	<div class="m2o-item no-data"><label class="title">&nbsp;</label>没有该分类下的扩展信息</div>
</script>
<script type="text/x-jquery-tmpl" id="additionInfo-tpl">
	{{if type=='text'}}
	<div class="m2o-item m2o-flex" data-id="${id}">
		<label class="title">${zh_name}:</label>
		<div class="addition-content m2o-flex-one"><input type="text" name="${field}" value="${selected}" /></div>
		<!-- <em class="del-extend" title="${title}" ></em> -->
	</div>
	{{/if}}
	{{if type=='textarea'}}
	<div class="m2o-item m2o-flex" data-id="${id}">
		<label class="title">${zh_name}:</label>
		<div class="addition-content m2o-flex-one">
			<textarea name="${field}" cols="120" rows="5" placeholder="${zh_name}">${selected}</textarea>
		</div>
		<!-- <em class="del-extend" title="${title}" ></em> -->
	</div>
	{{/if}}
	{{if type=='phone'}}
	<div class="m2o-item m2o-flex" data-id="${id}">
		<label class="title">${zh_name}:</label>
		<div class="addition-content m2o-flex-one"><input type="text" name="${field}" value="${selected}" /></div>
		<!-- <em class="del-extend" title="${title}" ></em> -->
	</div>
	{{/if}}
	{{if type=='img'}}
	<div class="m2o-item m2o-flex" data-id="${id}">
		<label class="title">${zh_name}:</label>
		<div class="content addition-content m2o-flex-one" data-batch="${batch}">
			{{each selected}}
			<div class="img-batch" data-id="${id}">
				<p class="upload-pic">
					<img src="{{= $value['host']}}{{= $value['dir']}}{{= $value['filepath']}}{{= $value['filename']}}">
				</p>
				<input type="file" name="${field}" class="catalog_avatar" style="display:none">
				<p class="del">-</p>
			</div>
			{{/each}}
			<div class="img-batch">
				<p class="upload-pic"></p>
				<input type="file" name="${field}" class="catalog_avatar" style="display:none">
				<p class="del">-</p>
			</div>
			
		</div>
		<!-- <em class="del-extend" title="${title}" ></em> -->
	</div>
	{{/if}}
	{{if type== 'radio'}}
	<div class="m2o-item m2o-flex" data-id="${id}">
		<label class="title">${zh_name}:</label>
		<div class="addition-content m2o-flex-one"></div>
		<!-- <em class="del-extend" title="${title}" ></em> -->
	</div>
	{{/if}}
	{{if type== 'checkbox'}}
	<div class="m2o-item m2o-flex" data-id="${id}">
		<label class="title">${zh_name}:</label>
		<div class="addition-content addition-checkbox m2o-flex-one"></div>
		<!-- <em class="del-extend" title="${title}" ></em> -->
	</div>
	{{/if}}
	{{if type== 'option'}}
	<div class="m2o-item m2o-flex" data-id="${id}">
		<label class="title">${zh_name}:</label>
		<div class="addition-content m2o-flex-one"></div>
		<!-- <em class="del-extend" title="${title}" ></em> -->
	</div>
	{{/if}}
</script>



