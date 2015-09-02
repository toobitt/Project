{template:head}
{code}
	if ( is_array($formdata ) ) {
		foreach ( $formdata as $k => $v ) {
			$$k = $v;
		}
	}		
	if($id) {
		$optext = $state == -1 ? "添加": "更新";
		$ac="update";
	}
	else {
		$optext="添加";
		$ac="create";
	}
	if(empty($water_angle)) {
		$water_angle = 1;
	}
	if(empty($water_font)) {
		$water_font = 1;
	}
	$currentSort[$sort_id] = ($sort_id ? $sort_name : '选择分类');
{/code}
{css:news_add}
{css:bigcolorpicker}
{css:catalog}
{css:2013/form}
{js:catalog}
{js:underscore}
{js:Backbone}
{js:bigcolorpicker}
{js:column_node}
{css:column_node}
{js:ad}
{js:hg_news}
{js:hg_water}
{template:form/common_form}
{css:hg_sort_box}
{js:hg_sort_box}
{js:common/common_form}
{js:common/auto_textarea}
{js:news/news_form}
{js:ajax_upload}
{js:2013/ajaxload_new}
{css:common/common_form}
{css:news_form}
{css:2013/iframe_form}
<script>
$.officeconvert = {code}echo isset($_settings['officeconvert']) ? 1 : 0;{/code};
$.maxpicsize = {code}echo $_configs['maxpicsize'] ?  intval($_configs['maxpicsize']) : 640;{/code};
</script>

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
			var code = '{$v["code"]}'; 
			attachList.push({ "src": "{$v['url']}", "path": "{$v['path']}", "dir": "{$v['dir']}", "id": "{$v['material_id']}", "name": "{$v['name']}", "filesize": "{$v['filesize']}", "type": "{$v['type']}", "code": code});
		{/if}
	{/foreach}
	{foreach $sort as $k => $v}
		sortList.push( { sort_id: "{$k}", sort_name: "{$v}" } );
	{/foreach}
	var currentSort = { sort_id: "{$sort_id}", sort_name: "{$currentSort[$sort_id]}" };
	attach_support = '{$attach_support}';
	img_support = '{$img_support}';
</script>
<style>
.edui-default .edui-editor{border:none;}
#edui1_bottombar{display:none;}
.common-form-main .editor-statistics{position:relative;}
.edui-default .edui-editor-toolbarboxouter{border-bottom: 1px solid #ececec;background-color: #f9f9f9;background-image:none;}
.editor-slide-fixed{position:fixed;top:0!important;}
.pic-edit-btn{z-index:10000;}

.draft-overflow {overflow-y: auto;overflow-x: hidden;float: left;width:265px;}
.draft-content {height: 477px;}
.draft-item {border-bottom: 1px solid #ebe7e7;overflow: hidden;margin: 0 10px;cursor: pointer;position: relative;}
.draft-item:hover{background:#f0eff5;}
.draft-title {display: table-cell;height: 30px;padding-left: 5px;vertical-align:middle;white-space:nowrap;max-width:200px;text-overflow:ellipsis;overflow:hidden;margin-left:10px;padding-top:8px;}
.draft-time {float: right;padding-bottom: 5px;margin-right: 30px;}
.draft-option {width: 24px;height: 24px;position: absolute;right: 0;bottom: 0;display: none}
.draft-item:hover .draft-option{display: block;}
.draft-option-del{cursor: pointer;height: 100%; display:block;background:url({$RESOURCE_URL}news/del.png) #ccc no-repeat center;}
.draft-option-del:hover{background-image: url({$RESOURCE_URL}news/del_hover.png);}
</style>
<form method="post" enctype="multipart/form-data" id="content_form" onsubmit="return hg_news_submit();" class="ueditor-outer-wrap">
<div class="common-form-head">
	<div class="common-form-title">
		<h2>{$optext}文稿</h2>
        {code}
        //print_r($draft_list);
        {/code}
		<div class="form-dioption-title form-dioption-item">
			<!-- <textarea name="title" _value="{if $title}{$title}{else}添加文稿标题{/if}" id="title" class="title {if $title}input-hide{/if}" placeholder="添加文稿标题">{$title}</textarea> -->
			<input name="title" _value="{if $title}{$title}{else}添加文稿标题{/if}" id="title" class="title {if $title}input-hide{/if}  need-word-count" placeholder="添加文稿标题" value="{$title}" title="{$title}"/>
			<div class="color-selector clearfix">
				<span class="form-title-color"></span>
				<span class="form-title-weight"></span>
				<span class="form-title-italic"></span>
			</div>
			<div id="word-count">
			</div>
			<input name="tcolor" type="hidden" value="{$tcolor}" id="tcolor" />
			<input name="isbold" type="hidden" value="{if $isbold}1{else}0{/if}" id="isbold" />
			<input name="isitalic" type="hidden" value="{if $isitalic}1{else}0{/if}" id="isitalic" />
			<input name="weight" value="{$weight}" id="weight" type="hidden" />
		</div>
		<input type="hidden" name="submit_type" id="submit_type"/>
		<div class="form-dioption-submit" style="width: 270px;">
			<!--  <input type="submit" id="submit_ok" name="sub" value="确定并继续添加" class="button_6_14" _submit_type="2"/>
			<input type="submit" id="submit" value="确定" class="button_2_14" style="margin-left:5px;" _submit_type="1"/>-->
            {if !$id}
            <!--<a class="common-form-save" href="#" id="submit_draft" style="right:145px;line-height: 34px;">保存草稿</a>-->
            {/if}
			<input type="submit" id="submit_ok" name="sub" value="保存" class="common-form-save" _submit_type="2" />
			<span class="option-iframe-back">关闭</span>
		</div>
		<div id="weightPicker">
			{template:list/list_weight,agd,$weight}
		</div>  
    </div>
</div>
<div class="common-form-main">
	{template:unit/publish_for_form, 1, $formdata['column_id']}
	{template:unit/special_for_form, 1, $formdata['id']}
<div class="m2o-flex">
	<!-- form-left start -->
	<div class="form-left" style="z-index:1;">
		<div class="left-fix-box">
        	<div class="form-dioption" style="overflow:visible;">
	            <div class="form-dioption-inner">
	                  <div class="form-cioption-indexpic form-cioption-item">
	                    <div class="indexpic-box">
	                        <div class="indexpic" style="font-size:0;">
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
	                            <img style="max-width:160px;max-height:160px;" _src="{if !$indexpicsrc}{$default_indexpic_url}{else}{$indexpicsrc}{/if}" title="索引图" id="indexpic_url" _state="{if $indexpicsrc}1{else}0{/if}" _default="{$default_indexpic_url}"/>
	                        </div>
	                        <span class="indexpic-suoyin {if $indexpicsrc}indexpic-suoyin-current{/if}"></span>
	                    </div>
	                    <input name="indexpic" type="hidden"  id="indexpic" value="{$indexpic}" />
	                </div>
	            <div class="form-dioption-sort form-dioption-item"  id="sort-box">
	                <label style="color:#9f9f9f;{if !$sort_id}display:none;{/if}">分类： </label><p style="display:inline-block;" class="sort-label" _multi="news_node"> {$currentSort["$sort_id"]}<img class="common-head-drop" src="{$RESOURCE_URL}tuji/drop.png" style="position: relative;left:10px;bottom:2px;" /></p>
					<div class="sort-box-outer"><div class="sort-box-inner"></div></div>
	                <input name="sort_id" type="hidden" value="{$sort_id}" id="sort_id" />
	            </div>
				<div class="form-dioption-fabu form-dioption-item">
					<a class="common-publish-button overflow" href="javascript:;" _default="发布至" _type="publish" _prev="发布至：">发布至</a>
	            </div>
	            <div class="form-dioption-fabu form-dioption-item">
					<a class="common-publish-button overflow" href="javascript:;" _default="发布至专题" _type="special" _prev="发布至专题：">发布至专题</a>
	            </div>
	            <div class="form-dioption-keyword form-dioption-item clearfix" style="position:relative;">
	                <span class="keywords-del"></span>
	                <span class="form-item" _value="添加关键字" id="keywords-box" data-title="提取文章内容与标题为关键字">
	                    <span class="keywords-start">添加关键字</span>
	                    <span class="keywords-add">+</span>
	                </span>
	                <input name="keywords" value="{$keywords}" id="keywords" style="display:none;"/>
	            </div>
	        	<div id="lumin">
	        	</div>
			</div>
			<div class="form-dioption-inner form-dioption-inner-second">
                <!-- 
                <div class="form-dioption-quanzhong form-dioption-item">
                    <div>权重设置</div>
                    <div id="quanzhong" class="form-quanzhong">
                        <div class="quanzhong-option">
                            <div class="down"></div>
                            <div class="up"></div>
                        </div>
                        <div class="quanzhong-box{$weight}">
                            <div class="quanzhong">{$_configs['weight_search'][$weight]}</div>
                            <img src="{$RESOURCE_URL}news/quanzhong-masking.png" usemap="#quanzhong-map" class="quanzhong-masking" />
                            <map name="quanzhong-map" id="quanzhong-map">
                                <area shape="poly" coords="32,0,32,9,42,12,47,4" title="{$levelLabel[1]}" />
                                <area shape="poly" coords="49,6,43,12,50,19,58,14,49,6,49,6" title="{$levelLabel[2]}" />
                                <area shape="poly" coords="59,16,51,21,53,30,62,30" title="{$levelLabel[3]}" />
                                <area shape="poly" coords="54,31,62,31,60,45,59,45,52,41,54,32" title="{$levelLabel[4]}" />
                                <area shape="poly" coords="43,51,51,43,59,47,49,58" title="{$levelLabel[5]}" />
                                <area shape="poly" coords="33,54,32,63,47,59,42,52" title="{$levelLabel[6]}" />
                                <area shape="poly" coords="20,51,16,59,31,63,31,54" title="{$levelLabel[7]}" />
                                <area shape="poly" coords="4,47,12,43,19,50,15,57" title="{$levelLabel[8]}" />
                                <area shape="poly" coords="0,32,3,46,11,42,9,33" title="{$levelLabel[9]}" />
                                <area shape="poly" coords="9,32,0,31,4,16,12,21" title="{$levelLabel[10]}" />
                                <area shape="poly" coords="12,19,19,12,14,5,5,15" title="{$levelLabel[11]}" />
                                <area shape="poly" coords="21,12,30,9,30,0,16,4,20,11" title="{$levelLabel[12]}" />
                            </map>
                        </div>
                    </div>
                    <input name="weight" value="{$weight}" id="weight" type="hidden" />
                </div> -->
            </div>
            <div class="editor-detail"></div>
		</div>
		<div id="editor-count"></div>
    </div>
	</div>
	<!-- form-left end -->
	
	<!-- 编辑器 start -->
	<textarea name="content" name="content" class="hide-textarea" id="form-edit-box">{code}echo htmlspecialchars_decode($content);{/code}</textarea>
            <script>
           /*$('#form-edit-box').show();
            setTimeout(function(){
                jQuery('#iframe-mask-loading').click(function(){
                    $(this).hide();
                }).hide();
            }, 1000);*/
            </script>
	<!-- 编辑器 end -->
	<!-- form-middle start -->
    <div class="form-middle m2o-flex-one" style="position:relative;left:0;">
		<!-- 右侧属性 strat -->
		<div class="right-fix-box">
			<div class="form-cioption form-right" style="min-height:500px;">
				<div class="form-dioption-brief form-dioption-item">
	                <div style="display:none;">
	                	<textarea name="brief" id="brief" class="brief {if $brief}input-hide{/if}" placeholder="添加文稿摘要" _value="{if $brief}{$brief}{else}添加文稿摘要{/if}">{$brief}</textarea>
	                </div>
	                <div contenteditable="true" id="brief-clone" data-left="-35px" data-top="-19px" class="need-word-count" target="brief" placeholder="添加文稿摘要">{$brief}</div>
                </div>
                <div class="form-dioption-subtitle form-dioption-item">
                    <input name="subtitle" id="subtitle" type="text" value="{$subtitle}" style="display:none;"/>
                    <div contenteditable="true" id="subtitle-clone" class="my-placeholder" target="subtitle" placeholder="设置副题" preval="副题："></div>
                </div>
                <div class="form-dioption-author form-dioption-item">
                    <input name="author" id="author" type="text" value="{$author}" style="display:none;"/>
                    <div contenteditable="true" id="author-clone" class="my-placeholder" target="author" placeholder="作者" preval="作者："></div>
                </div>
                <div class="form-dioption-source form-dioption-item">
                    <input name="source" id="source" type="text" value="{$source}" style="display:none;"/>
                    <div contenteditable="true" id="source-clone" class="my-placeholder" target="source" placeholder="来源" preval="来源："></div>
                </div>
                <div class="form-dioption-source form-dioption-item">
                    <input name="ori_url" id="ori_url" type="text" value="{$ori_url}" style="display:none;"/>
                    <div contenteditable="true" id="ori_url-clone" class="my-placeholder" target="ori_url" placeholder="原始链接" preval="原始链接："></div>
                </div>
                <div class="form-dioption-item ext-laiyuan">
                	<label><input name="other_settings[closecomm]" type="checkbox" value="1" {if $other_settings['closecomm']}checked="checked"{/if} style="width:auto;vertical-align:middle;margin-right:6px;"/>关闭本文评论</label>
                </div>
                {if !$id}
                   <!-- <div class="form-dioption-item">
                        <span class="draft-button">使用草稿</span>
                    </div>  -->
                {/if}
                <ul class="form-cioption-ext" data-tip="暂时隐藏" style="display:none;">
                    <li><label><input name="istop" type="checkbox" value="1" {if $istop}checked="checked"{/if} id="istop" />本文置顶</label></li>
                    <li><label><input name="istpl" type="checkbox" value="1" {if $istpl}checked="checked"{/if} id="istpl" />独立模板</label></li>
                    <li><label><input name="isssss" type="checkbox" value="1" {if $isssss}checked="checked"{/if} id="istop" />指定文件名</label></li>
                </ul>
			</div>
		</div>
		<!-- 右侧属性end -->
		<div id="iframe-mask-loading" style="background:transparent;border:none;display:none;">
			<img src="{$RESOURCE_URL}loading2.gif"/>
		</div>
            <input type="hidden" name="a" value="{$ac}" />
            <input type="hidden" name="auto_draft" value="{$_configs['autoSaveDraft']}" />
            <input type="hidden" id="id"  name="id" value="{$formdata['id']}" />
            <input type="hidden" name="referto" value="{$_INPUT['referto']}" id="referto" />
            <input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
            <input type="hidden" name="mmid" value="{$_INPUT['mid']}" />
            <input type="hidden" name="app_uniqueid" value="{$_INPUT['app']}" />
            <input type="hidden" name="module_uniqueid" value="{$_INPUT['mod']}" />
            <input type="hidden" name="history_id" id="history_id" value="0" />
			<input type="hidden" name="material_history" id="material_history" value="" />
			<input type="hidden" name="water_config_id" value="{$water_id}" id="water_config_id" />
			<input type="hidden" name="water_config_name" value="{$water_name}"  id="water_config_name"/>
			{foreach $material as $k => $v}
				<input name="material_id[]"  type="hidden" _id="{$v['material_id']}" value="{$v['material_id']}" />
			{/foreach}
			<div class="history-info" style="display:none;">
			{if !empty($history_info)}
				{foreach $history_info as $k => $v}
					<a href="javascript:void(0);" onclick="hg_article_history({$v['id']});" style="margin:2px 0px;margin-left:8px ;float: left;">{$v['create_time']}</a>
					{/foreach}
				{/if}
			</div>
            <div class="material-box" style="display:none;">
            </div>
            <input type="hidden" name="pagetitles" id="pagetitles" value="{$v['pagetitles']}"/>
            <input type="hidden" name="pizhus" id="pizhus" value="{$v['pizhus']}"/>
            <input type="hidden" name="is_first_hand_save" id="is_first_hand_save" value="{$formdata['is_first_hand_save']}" />
    	</div>
    <!-- form-middle end -->
</div>
</div>

<div class="editor-slide-box draft-outer" style="top: 90px; left: 972.5px; height: 520px;display:none;">
    <div class="editor-slide-wrap">
        <div class="editor-slide-inner">
            <div class="editor-slide-head">
                <div class="editor-slide-title">草稿</div>
                <div class="editor-slide-option"><span class="editor-slide-no draft-slide-no"></span></div>
            </div>
            <div class="editor-slide-body">
                   <div class="draft-overflow">
                       <ul class="draft-content">

                           <li class="draft-item">
                               <a class="draft-title" href="./run.php?mid=58&a=detail&draft_id=-1&infrm=1" title="空白草稿" style="height:50px;">空白草稿</a>
                           </li>
                           {code}
                           $draft_list = $draft_list[0];
                           {/code}
                           {if is_array($draft_list) && count($draft_list) > 0 }
                           {foreach $draft_list as $k => $v}
                           <li class="draft-item draft-item-{$v['id']}">
                               {code}
                                    $v['title'] = $v['title'] ? $v['title'] : '无标题';
                                    if ($v['isauto']) {
                                        $v['title'] = '(自动草稿)' . $v['title'];
                                    }
                               {/code}
                               <a href="./run.php?mid=58&a=detail&draft_id={$v['id']}&infrm=1"">
                               <span class="draft-title" title="{$v['title']}">{$v['title']}</span>
                               <span class="draft-time">{$v['create_time']}</span>
                               </a>
                               <div class="draft-option">
                                <span class="draft-option-del" _draft_id="{$v['id']}"></span>
                               </div>
                           </li>
                           {/foreach}
                           {else}
                           {/if}
                       </ul>
                   </div>
            </div>
        </div>
    </div>
</div>

</form>
<script>
$(function(){
    var headHeight = $('.common-form-head').outerHeight(true);
    $(window).scroll(function(){
        var scrollTop = $(this).scrollTop(),
       		doClass = ( scrollTop > headHeight + 10 ) ? 'addClass' : 'removeClass';
        $('.left-fix-box')[doClass]('news-fix-left');
        $('.right-fix-box')[doClass]('news-fix-right');
        $('.edit-slide')[doClass]('edit-slide-fixed');
        $('.editor-slide-box')[doClass]('editor-slide-fixed');
        $('.editor-slide-box').css('left',$('.form-right').offset().left + 'px');
        if( doClass == 'removeClass' ){
        	$('.editor-slide-box').css('top','90px');
        }
    });
});
</script>
{template:foot}