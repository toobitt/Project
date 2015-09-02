<?php ?>
{template:head}
{css:2013/form}
{css:2013/button}
{css:hg_sort_box}
{css:form}
{js:hg_preview}
{js:hg_sort_box}
{js:ajax_upload}
{js:common/common_form}
{js:pop/base_pop}
{js:pop/pop_list}
{js:live_interactive_new/live_interactive_new_form}
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
$channel_name = ($channel_id ? $channel_name : '选择频道');
$markswf_url = RESOURCE_URL.'swf/';
{/code}
<body>
	<form class="m2o-form" name="editform" action="run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" id="tv_interact_form" data-id="{$id}">
			<div id="ohms-instance" style="position:absolute;display:none;"></div>
			{template:unit/bg_picture}
			<header class="m2o-header">
			    <div class="m2o-inner">
			        <div class="m2o-title m2o-flex m2o-flex-center">
			            <h1 class="m2o-l">{$optext}话题</h1>
			            <div class="m2o-m m2o-flex-one">
			                <input placeholder="输入话题名称" name="title" class="m2o-m-title need-word-count" title="{$title}" required value="{$title}" />
			                <input type="hidden" name="old_name" value="{$name}" />
			            </div>
			            <div class="m2o-btn m2o-r">
			                <input type="submit" value="保存信息" class="m2o-save" name="sub" id="sub" data-target="run.php?mid={$_INPUT['mid']}&a={$ac}" data-method="{$ac}"/>
			                <span class="m2o-close option-iframe-back"></span>
			                <input type="hidden" name="a" value="{$ac}" />
			                <input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
			                <input type="hidden" name="referto" value="{$_INPUT['referto']}" />
			                <input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
			            </div>
			        </div>
			    </div>
			</header>
			<div class="m2o-inner">
			<div class="m2o-main m2o-flex">
			<aside class="m2o-l m2o-aside">
			    <div class="m2o-item img-info" style="position:relative">
			        <div class="indexpic">
			            {code}
			            $indexpic_url = $indexpic_url['host'] . $indexpic_url['dir'] . $indexpic_url['filepath'] . $indexpic_url['filename'];
			            {/code}
			            <img src="{$indexpic_url}" />
			            <span class="indexpic-suoyin {if $formdata['indexpic']}indexpic-suoyin-current{/if}"></span>
			            <input type="hidden" name="indexpic" value="{$indexpic}" />
			        </div>
			        <input type="file" name="upload-file" style="display:none;" class="upload-file" />
			    </div>
			    <div class="form-dioption-sort m2o-item"  id="sort-box">
			        <label style="color:#9f9f9f;">频道： </label>
			        <p style="display:inline-block;" class="sort-label" _multi="node"> {$channel_name}<img class="common-head-drop" src="{$RESOURCE_URL}tuji/drop.png" style="position: relative;left:10px;bottom:2px;" /></p>
			        <div class="sort-box-outer"><div class="sort-box-inner"></div></div>
			        <input name="channel_id" type="hidden" value="{$channel_id}" id="sort_id" />
			        <input name="fieldcontentdel" type="hidden" value="{$channel_id}" />
			    </div>
			</aside>
			<section class="m2o-m m2o-flex-one">
			    <div class="basic-info">
			        <div class="m2o-item tv-info">
			            <a class="tv-title active" data-type="basic">基本信息</a>
			        </div>
			        <div class="m2o-item cut-off">
			            <label class="title">描述简介: </label>
			            <textarea class="brief" name="brief" cols="120" rows="5" placeholder="描述简介">{$brief}</textarea>
			        </div>
			        <div class="m2o-item cut-off">
			            <label class="title">话题时间段: </label>
			            <div class="info">
			                <input type="text" name="start_time" class="time date-picker" _time="true" value="{if $start_time_show && $start_time_show !==0 } {$start_time_show} {/if}"/>
			                <span>至</span>
			                <input type="text" name="end_time" class="time date-picker"  _time="true" value="{if $end_time_show && $end_time_show!==0 } {$end_time_show} {/if}"/>
			            </div>
			        </div>
			        <div class="m2o-item cut-off">
                        <label class="title">图片: </label>
                        <div class="info" id="img-list" style="max-width: 800px;">
                        	{if is_array($material) && count($material) > 0}
                        	{foreach $material as $k => $v}
                        	{code}
                        		$bigsrc = $v['pic']['host'] . $v['pic']['dir'] . $v['pic']['filepath'] . $v['pic']['filename'];
                        		$src = $v['pic']['host'] . $v['pic']['dir']  . '80x/' . $v['pic']['filepath'] . $v['pic']['filename']
                        	{/code}
		                     <div class="item-box" style="margin-bottom: 10px;">
		                            <span class="del"></span>
		                            <div class="item-inner-box">
		                                <a class="suoyin set-suoyin {if $v['id'] == $indexpic}suoyin-current{/if}"></a>
		                                <img class="image" imageid="{$v['id']}" bigsrc="{$bigsrc}" src="{$src}">
		                            </div>
		                            <div class="nooption-mask"></div>
		                            <div class="image-option-box">
                            		<span class="image-option-del image-option-item"></span>
                            		</div>             
		                            <input type="hidden" value="{$v['id']}" name="material_id[]" />
		                        </div>                       	
                        	{/foreach}
                        	{/if}
		        			<div class="img-info">
		        				<div class="icon"><img src="{$un_start_icon}" /></div>
		        				<input type="file" name="un_start_file" class="upload-file" style="display:none" accept="image/*"/>
		        			</div>                        
                        </div>
                    </div> 
			         <div class="m2o-item cut-off">
			            <label class="title">选择嘉宾: </label>
			            <div class="show-guests">
			            	<ul>
			            		{foreach $guests_info as $k => $v}
			            		<li _id="{$v['id']}">
			            			<span class="guests">{$v['title']}</span>
			            			<span class="del-selected">x</span>
			            		</li>
			            		{/foreach}
			            	</ul>
			            </div>
			            <p class="add-guest" title="点击选择嘉宾">+</p>	
			            <input type="hidden" name="guests_id" value="{$formdata['guest_ids']}"/>
			        </div>
			        <div class="m2o-item cut-off">
			            <label class="title">引用: </label>
			             <div class="info" id="img-list" style="max-width: 800px;">
                             {foreach $refer as $k => $v}
                             <div class="img-info cite-info" title="{$v['title']}">
                                 <div class="icon pic">
                                     {code}
                                        $v['src']='';
                                        $v['src']= $v['indexpic']['host'] . $v['indexpic']['dir'] . '80x80/' . $v['indexpic']['filepath'] . $v['indexpic']['filename'];
                                     {/code}
                                     <img src="{$v['src']}" />
                                 </div>
                                 <p class="cite-title">{$v['title']}</p>
                                 <span class="del-pic"></span>
                                 <input type="hidden" name="refer[title][]" value="{$v['title']}">
                                 <input type="hidden" name="refer[bundle_id][]" value="{$v['app_uniqueid']}">
                                 <input type="hidden" name="refer[module_id][]" value="{$v['mod_uniqueid']}">
                                 <input type="hidden" name="refer[id][]" value="{$v['rid']}">
                                 <input type="hidden" name="refer[content_url][]" value="{$v['link']}"/>
                                 <input type="hidden" name="refer[indexpic][]" value="{$v['indexpic_json']}"/>
                                 <input type="hidden" name="refer[type][]" value="{$v['type']}"/>
                             </div>
                             {/foreach}
			                <div class="img-info">
			                    <div class="icon cite"><img src="{$un_start_icon}" /></div>
			                </div>
			            </div>
			        </div>
			    </div>
			</section>
		</div>
		<!-- 选择嘉宾框 -->
            <div class="guest-box">
            	<div class="guest-title">选择嘉宾
            		<span class="save-guest">确定</span>
            	</div>
            	<div class="selected-guest"></div>
            	<div class="guest-list">
            		<ul>
            		{foreach $guest_list as $k => $v}
            			<li _id="{$v['id']}">{$v['title']}</li>
            		{/foreach}
            		</ul>
            	
            	</div>
            </div>
            <!-- end -->
	</form>
</body>
<script type="text/x-jquery-tmpl" id="pic-tpl">
<div class="img-info cite-info" title="{{= title}}">
	<div class="icon pic">
		<img src="{{= src}}" />
	</div>
	<p class="cite-title">{{= title}}</p>
	<span class="del-pic"></span>
	<input type="hidden" name="refer[title][]" value="{{= title}}">
	<input type="hidden" name="refer[bundle_id][]" value="{{= bundle_id}}">
	<input type="hidden" name="refer[module_id][]" value="{{= module_id}}">
	<input type="hidden" name="refer[id][]" value="{{= id}}">
	<input type="hidden" name="refer[content_url][]" value="{{= content_url}}"/>
	<input type="hidden" name="refer[indexpic][]" value="{{= indexpic}}"/>
	<input type="hidden" name="refer[type][]" value="{{= type}}"/>
</div>
</script>