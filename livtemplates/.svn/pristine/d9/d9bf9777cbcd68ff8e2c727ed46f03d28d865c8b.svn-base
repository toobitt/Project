{template:head}
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
	if(empty($water_angle))
	{
		$water_angle = 1;
	}
	if(empty($water_font))
	{
		$water_font = 1;
	}
	foreach($sort_form as $k =>$v)
	{
		$sort[$v['id']] = $v['name'];
	}
	$levelLabel = array(0, 1, 2, 3, 10, 20, 30, 40, 50, 60, 70, 80, 90);
	$currentSort[$sort_id] = ($sort_id ? $sort[$sort_id] : '选择分类');
{/code}
{css:news_add}
{css:bigcolorpicker}
{js:bigcolorpicker}
{js:column_node}
{css:column_node}
{js:ad}
{js:hg_news}
{js:hg_water}
{css:hg_sort_box}
{js:hg_sort_box}

<script type="text/javascript">
	(function(w) {
		var base = './run.php?mid=' + gMid + '&a=';
		w.gUrl = {
			referInfo: base + 'get_material_info',
			waterList: base + 'water_config_list',
			createWater: base + 'create_water_config',
			upload: base + 'upload',
			transform: base + 'revolveImg',
			referNode: base + 'get_material_node',
			referSketch: base + 'get_sketch_map',
			imgLocal: base + 'img_local'
		};
	})(window);
	var imgList = [], attachList = [], sortList = [];
	{foreach $material as $k => $v}
		{if $v['mark'] == 'img' } 
			imgList.push({ "src": "{$v['url']}", "path": "{$v['path']}", "dir": "{$v['dir']}", "material_id": "{$v['material_id']}", "filename": "{$v['filename']}" });
		{else}
			attachList.push({ "src": "{$v['url']}", "path": "{$v['path']}", "dir": "{$v['dir']}", "material_id": "{$v['material_id']}", "filename": "{$v['name']}", "size": "{$v['filesize']}", "type": "{$v['type']}" });
		{/if}
	{/foreach}
	{foreach $sort as $k => $v}
		sortList.push( { sort_id: "{$k}", sort_name: "{$v}" } );
	{/foreach}
	var currentSort = { sort_id: "{$sort_id}", sort_name: "{$currentSort[$sort_id]}" };
</script>


{js:common/common_form}
{js:common/auto_textarea}
{js:news/news_form}

{css:common/common_form}
{css:news_form}
<form method="post"   id="content_form" onsubmit="return hg_news_submit();">
    <span class="vod_fb" id="vod_fb"></span>
    <div id="vodpub" class="vodpub lightbox" style="z-index: 100000;">
        <div class="lightbox_top">
            <span class="lightbox_top_left"></span>
            <span class="lightbox_top_right"></span>
            <span class="lightbox_top_middle"></span>
        </div>
        <div class="lightbox_middle">
            <span onclick="hg_vodpub_hide();" style="position:absolute;right:25px;top:25px;z-index:1000;background:url('{$RESOURCE_URL}close.gif') no-repeat;width:14px;height:14px;cursor:pointer;display:block;"></span>
            <div id="vodpub_body" class="text" style="max-height:500px;padding:10px 10px 0;">
            {code}
                $hg_attr['multiple'] = 1;
                $hg_attr['multiple_site'] = 1;
                $hg_attr['slidedown'] = 1;
                $default = $column_id;
            {/code}
            {template:unit/column_node,columnid,$default}
            </div>
        </div>
        <div class="lightbox_bottom">
            <span class="lightbox_bottom_left"></span>
            <span class="lightbox_bottom_right"></span>
            <span class="lightbox_bottom_middle"></span>
        </div>
    </div>

    {template:unit/publish, 1, $formdata['column_id']}
		
    <div class="form-left" style="z-index:1;">
        <div class="option-iframe-back-box"><span class="option-iframe-back">返回概况</span></div>
        <div class="form-dioption" style="overflow:visible;">
            <h2>{$optext}概况</h2>
            <div class="form-dioption-title form-dioption-item">
                <!-- <input name="title" type="text" value="{if $title}{$title}{else}添加文稿标题{/if}" _value="{if $title}{$title}{else}添加文稿标题{/if}" id="title" class="title {if $title}input-hide{/if}" _default="添加文稿标题"/> -->
                <textarea name="title" _value="{if $title}{$title}{else}添加概况标题{/if}" id="title" class="title {if $title}input-hide{/if}" _default="添加概况标题" style="height:22px;line-height:22px;">{if $title}{$title}{else}添加概况标题{/if}</textarea>
                <div class="form-title-option clearfix" style="display:none;">
                    <span class="form-title-color"></span>
                    <span class="form-title-weight"></span>
                    <span class="form-title-italic"></span>
                </div>

                <input name="tcolor" type="hidden" value="{$tcolor}" id="tcolor" />
                <input name="isbold" type="hidden" value="{if $isbold}1{else}0{/if}" id="isbold" />
                <input name="isitalic" type="hidden" value="{if $isitalic}1{else}0{/if}" id="isitalic" />
            </div>
            <div class="form-dioption-brief form-dioption-item">
                <div style="overflow:hidden;">
                <textarea name="brief" id="brief" class="brief {if $brief}input-hide{/if}" _default="添加概况摘要" style="height:22px;line-height:22px;" _value="{if $brief}{$brief}{else}添加概况摘要{/if}">{if $brief}{$brief}{else}添加概况摘要{/if}</textarea>
                </div>
            </div>

            <div class="form-dioption-keyword form-dioption-item clearfix" style="position:relative;">
                <span class="keywords-del"></span>
                <span class="form-item" _value="添加关键字" id="keywords-box">
                    <span class="keywords-start">添加关键字</span>
                    <span class="keywords-add">+</span>
                </span>
                <input name="keywords" value="{$keywords}" id="keywords" style="display:none;"/>
                {if !empty($_configs['is_open_xs'])}
                <div id="keywords-tiqu" _title="提取文章内容关键字"><span class="keywords-tiqutip"><span class="keywords-tiqujiantou"></span>提取文章内容关键字</span></div>

                <div id="keywords-ajax">
                    <input type="button" class="button_2_14" id="keywords-submit" value="确定" style="display:none;">
                    <span id="keywords-close">x</span>
                    <span class="keywords-tiqujiantou"></span>
                    <ul class="clearfix"></ul>
                </div>
                <textarea id="keywords-tpl" style="display:none;"><li><span>{{name}}</span></li></textarea>
                {/if}
            </div>
            <div class="form-dioption-time form-dioption-item" style="display:none;"><span class="form-item">{$create_time_show}</span><input name="create_time" value="{$create_time_show}" id="create_time" style="display:none;" autocomplete="on" style="background:url({$RESOURCE_URL}hg_date.jpg) no-repeat 136px center;"/></div>




		</div>
		<input type="hidden" name="submit_type" id="submit_type"/>
		<div class="form-dioption-submit"><input type="submit" id="submit_ok" name="sub" value="确定并继续添加" class="button_6_14" _submit_type="2"/><input type="submit" id="submit" value="确定" class="button_2_14" style="margin-left:5px;" _submit_type="1"/></div>
    </div>
    <div class="form-middle">

            <div class="form-cioption">
                <div class="form-cioption-indexpic form-cioption-item">
                    <div class="indexpic-box">
                        <div class="indexpic">
                            {code}
                            $default_indexpic_url = RESOURCE_URL.'news/suoyin-default.png';

                            if($indexpic_url){
                                $indexpicsrc = $indexpic_url['host'].$indexpic_url['dir'].$indexpic_url['filepath'].$indexpic_url['filename'];
                            }else{
                                $indexpicsrc = '';
                            }
                            {/code}
                            <script>
                            $(function(){
                                if($.pixelRatio > 1){
                                    var index = $('#indexpic_url');
                                    if(index.attr('_state') < 1){
                                        index.attr('src', index.attr('_default').replace('.png', '-2x.png')).css('width', '49px');
                                    }
                                }
                            });
                            </script>
                            <img style="max-width:160px;max-height:120px;" _src="{if !$indexpicsrc}{$default_indexpic_url}{else}{$indexpicsrc}{/if}" title="索引图" id="indexpic_url" _state="{if $indexpicsrc}1{else}0{/if}" _default="{$default_indexpic_url}"/>
                        </div>
                        <span class="{if $indexpicsrc}indexpic-suoyin-current{else}indexpic-suoyin{/if}"></span>
                    </div>
                    <input name="indexpic" type="hidden"  id="indexpic" value="{$indexpic}" />
                </div>
                <div class="form-cioption-item ext-futi"><span class="form-prev">副题：</span><input name="subtitle" type="text" value="{$subtitle}" _default="设置副题" id="subtitle" style="width:120px;"/></div>
                <div class="form-cioption-item ext-zuozhe"><span class="form-prev">作者：</span><input name="author" type="text" value="{$author}" _default="作者" id="author" style="width:120px;"/></div>
                <div class="form-cioption-item ext-laiyuan"><span class="form-prev">来源：</span><input name="source" type="text" value="{$source}" _default="来源" id="source" style="width:120px;"/></div>
                <ul class="form-cioption-ext" data-tip="暂时隐藏" style="display:none;">
                    <li><label><input name="istop" type="checkbox" value="1" {if $istop}checked="checked"{/if} id="istop" />本文置顶</label></li>
                    <li><label><input name="iscomm" type="checkbox" value="1" {if !$iscomm}checked="checked"{/if} id="iscomm" />关闭本文评论</label></li>
                    <li><label><input name="istpl" type="checkbox" value="1" {if $istpl}checked="checked"{/if} id="istpl" />独立模板</label></li>
                    <li><label><input name="isssss" type="checkbox" value="1" {if $isssss}checked="checked"{/if} id="istop" />指定文件名</label></li>
                </ul>
            </div>

            <div id="form-edit-box">
            {code}
                echo hg_editor('content',$allpages);
            {/code}
            </div>

            <input type="hidden" name="a" value="{$ac}" />
            <input type="hidden" id="id"  name="id" value="{$formdata['id']}" />
            <input type="hidden" name="referto" value="{$_INPUT['referto']}" id="referto" />
            <input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
            <input type="hidden" name="mmid" value="{$_INPUT['mid']}" />
            <input type="hidden" name="history_id" id="history_id" value="0" />
			<input type="hidden" name="material_history" id="material_history" value="" />
			<input type="hidden" name="water_config_id" value="{$water_id}" id="water_config_id" />
			<input type="hidden" name="water_config_name" value="{$water_name}"  id="water_config_name"/>

			<div class="history-info" style="display:none;">
				{if !empty($history_info)}
					{foreach $history_info as $k => $v}
						<a href="javascript:void(0);" onclick="hg_article_history({$v['id']});" style="margin:2px 0px;margin-left:8px ;float: left;">{$v['create_time']}</a>
					{/foreach}
				{/if}
			</div>
            <div class="material-box" style="display:none;">
            {if !empty($material)}
            {foreach $material as $k => $v}
                <div id="material_{$v['material_id']}">
                    <input type="hidden" name="material_id[]" value="{$v['material_id']}" />
                    <input type="hidden" name="material_name[]" value="{$v['filename']}"/>
                </div>
            {/foreach}
            {/if}
            </div>

            <input type="hidden" name="pagetitles" id="pagetitles" value="{$v['pagetitles']}"/>
            <input type="hidden" name="pizhus" id="pizhus" value="{$v['pizhus']}"/>
    </div>
</form>

{template:foot}