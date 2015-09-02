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
	
	$article_sort = $article_sort[0];
	$sort_id  = $article_sort['sort_id'];
	unset($article_sort['sort_id']);
	
	if(!$group_id && !$id)
	{
		$group_id = $sort_id;
	}
	if(is_array($article_sort) && count($article_sort))
	{
		foreach($article_sort as $k=>$v)
		{
			$sort_arr[$v['id']] = $v['name'];
			if($group_id == $v['id'])
			{
				$sort_name = $v['name'];
			}
		}
	}
	if(!$sort_arr[$group_id])
	{
		$group_id = -1;
	}
{/code}
{css:news_add}
{css:bigcolorpicker}
{css:2013/form}
{js:bigcolorpicker}
{js:ajax_upload}
{js:2013/ajaxload_new}
{js:column_node}
{css:column_node}
{js:ad}
{js:hg_news}
{js:hg_water}
{template:form/common_form}
{css:hg_sort_box}
{js:common/common_form}
{js:common/auto_textarea}
{js:common/editor_common}
{js:magazine/mag_editor}


{css:common/common_form}
{css:news_form}
{css:2013/iframe_form}
<style>
#weight_box{display:none; }
.edui-default .edui-editor{border:none;}
#edui1_bottombar{display:none;}
.common-form-main .editor-statistics{position:relative;}
.edui-default .edui-editor-toolbarboxouter{border-bottom: 1px solid #ececec;background-color: #f9f9f9;background-image:none;}
.editor-slide-fixed{position:fixed;top:0!important;}
.pic-edit-btn{z-index:10000;}
</style>

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


<form method="post" id="content_form" class="ueditor-outer-wrap">
<div class="common-form-head">
     <div class="common-form-title">
          <h2>{$optext}文章</h2>
          <div class="form-dioption-title form-dioption-item">
                <input name="title" _value="{if $title}{$title}{else}添加文章标题{/if}" id="title" class="title {if $title}input-hide{/if} need-word-count" placeholder="添加文稿标题" value="{$title}" />
                <div class="color-selector clearfix">
                    <span class="form-title-color"></span>
                    <span class="form-title-weight"></span>
                    <span class="form-title-italic"></span>
                </div>
               <input name="tcolor" type="hidden" value="{$tcolor}" id="tcolor" />
               <input name="isbold" type="hidden" value="{if $isbold}1{else}0{/if}" id="isbold" />
               <input name="isitalic" type="hidden" value="{if $isitalic}1{else}0{/if}" id="isitalic" />
          </div>
          <input type="hidden" name="submit_type" id="submit_type"/>
		  <div class="form-dioption-submit">
		      <input type="submit" id="submit_ok" name="sub" value="保存文章" class="common-form-save" _submit_type="2" />
		      <span class="option-iframe-back">关闭</span>
		  </div>   
    </div>
</div>
<div class="common-form-main">
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
                                $indexpicsrc = $indexpic_url;
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
                    {if $indexpicsrc}<input name="material_id[]" type="hidden" class="from-indexpic-material" value="{$indexpic}" />{/if}
                </div>
            <!-- 
            <div class="form-dioption-sort form-dioption-item"  id="sort-box">
                <label style="color:#9f9f9f;">分类： </label>
				<input type="text" name="sort" style="width: 120px" value="{$sort_name}" id="sort-input" />
            </div>
             -->
            
            <div class="form-dioption-sort form-dioption-item">
				 <label style="color:#9f9f9f;">分类：</label>
				 <select name="sort_id">
				 	<option value="-1">选择分类</option>
				 	{if $sort_arr}
				 	{foreach $sort_arr as $k => $v}
				 	<option value="{$k}" {if $k == $group_id}selected{/if}>{$v}</option>
				 	{/foreach}
				 	{/if}
				 </select>
			</div>
            <!-- 
			<div class="form-dioption-fabu form-dioption-item">
                    <a class="common-publish-button overflow" href="javascript:;" _default="发布至" _prev="发布至：">发布至</a>
            </div>
             -->
            <div class="form-dioption-keyword form-dioption-item clearfix" style="position:relative;">
                <span class="keywords-del"></span>
                <span class="form-item" _value="添加关键字" id="keywords-box">
                    <span class="keywords-start">添加关键字</span>
                    <span class="keywords-add">+</span>
                </span>
                <input name="keywords" value="{$keywords}" id="keywords" style="display:none;"/>
            </div>
       </div>
       
            <div class="form-dioption-inner form-dioption-inner-second">
            </div>
            <div class="editor-detail"></div>
		</div>
		
		<div id="editor-count"></div>
		
    </div>
    
  </div>
  
  <!-- form-left end -->
	
	<!-- 编辑器 start -->
	<textarea name="content" name="content" class="hide-textarea" id="magazine_editor">{code}echo htmlspecialchars_decode($content);{/code}</textarea>
	
	
	<!-- form-middle start -->
    <div class="form-middle m2o-flex-one" style="position:relative;left:0;">
       <div class="right-fix-box">
             <div class="form-cioption form-right" style="min-height:500px;">
             
                <div class="form-dioption-brief form-dioption-item">
	                <div style="display:none;">
	                <textarea name="brief" id="brief" class="brief {if $brief}input-hide{/if}" placeholder="添加文稿摘要" _value="{if $brief}{$brief}{else}添加文稿摘要{/if}">{$brief}</textarea>
	                </div>
	                <div class="need-word-count" contenteditable="true" data-left="-40px" data-top="-19px" id="brief-clone" target="brief" placeholder="添加文稿摘要">{$brief}</div>
                </div>
                <div class="form-dioption-subtitle form-dioption-item">
                    <input name="subtitle" id="subtitle" type="text" value="{$subhead}" style="display:none;"/>
                    <div contenteditable="true" id="subtitle-clone" class="my-placeholder" target="subtitle" placeholder="设置副题" preval="副题："></div>
                </div>
                <div class="form-dioption-author form-dioption-item">
                    <input name="author" id="author" type="text" value="{$article_author}" style="display:none;"/>
                    <div contenteditable="true" id="author-clone" class="my-placeholder" target="author" placeholder="作者" preval="作者："></div>
                </div>
                <div class="form-dioption-source form-dioption-item">
                    <input name="source" id="source" type="text" value="{$redactor}" style="display:none;"/>
                    <div contenteditable="true" id="source-clone" class="my-placeholder" target="source" placeholder="来源" preval="来源："></div>
                </div>
           </div>
         </div>
           <!-- <div id="iframe-mask-loading" style="background:transparent;border:none;display:none;"><img src="{$RESOURCE_URL}loading2.gif"/></div> -->

            <input type="hidden" name="a" value="{$ac}" />
            <input type="hidden" id="id"  name="id" value="{$formdata['id']}" />
            <input type="hidden" name="referto" value="{$_INPUT['referto']}" id="referto" />
            <input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
            <input type="hidden" name="mid" value="{$_INPUT['mid']}" />
            <input type="hidden" name="app" value="{$_INPUT['app']}" />
            <input type="hidden" name="mod" value="{$_INPUT['mod']}" />
            <input type="hidden" name="issue_id" id="maga_id" value="{$maga_id}" />
            <input type="hidden" name="issue_id" id="issue_id" value="{$issue_id}" />
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
                    <input type="hidden" name="material_id[]" _id="{$v['material_id']}" value="{$v['material_id']}" />
                    <input type="hidden" name="material_name[]" value="{$v['filename']}"/>
                </div>
            {/foreach}
            {/if}
            </div>

            <input type="hidden" name="pagetitles" id="pagetitles" value="{$v['pagetitles']}"/>
            <input type="hidden" name="pizhus" id="pizhus" value="{$v['pizhus']}"/>
    </div>
    <!-- form-middle end -->
   </div>
    
</form>
</div>
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
    
    /*添加文章完成后刷新当前期的杂志列表页*/
    
    var callback = function(){
		var mainwin = top.$('body').find('#mainwin'),
			magazine_current = mainwin[0].contentWindow.$('.magazine-list').find('.magazine-each.selected');
			if( magazine_current.length ){
				setTimeout( function(){
					magazine_current.removeClass('selected');
					magazine_current.trigger('click')
				},0 );
			}else{
				setTimeout( function(){
					mainwin[0].contentWindow.location.reload();
				},0 );
			}
	
	};
    
    $('#content_form').on( 'submit', function(){
    	hg_news_submit( callback );
    } );
    
});
</script>
{template:foot}