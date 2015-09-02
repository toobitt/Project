{template:head}
{css:2013/form}
{css:2013/button}
{css:hg_sort_box}
{css:cite_form}
{js:ajax_upload}
{js:common/common_form}
{js:2013/ajaxload_new}
{js:pop/base_pop}
{js:pop/pop_list}
{js:page/page}
{js:survey/add_survey_form}
{js:survey/cite_survey}
{code}
if(!$id)
{
	$optext="引用已有问卷";
	$ac="create";
}
//print_r($sort);
{/code}
<script>
$.globalData = {code}echo $formdata ? json_encode($formdata) : '{}';{/code};
$.globalTags = {code}echo $tags ? json_encode($tags) : '{}';{/code};
</script>
<style>
.hoge_page{margin-right:0}
.hoge_page select{display:none;}
.hoge_page .page_all{margin-right:0;float:none}
</style>
<form class="m2o-cite-form m2o-survey" name="editform" action="run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" id="verifycode_form" data-id="{$id}">
	<header class="m2o-header">
    <div class="m2o-inner">
        <div class="m2o-title m2o-flex m2o-flex-center">
            <h1 class="m2o-l">{$optext}</h1>
            <div class="m2o-m m2o-flex-one">
                 <input placeholder="输入问卷名称" name="title" class="m2o-m-title need-word-count" required title="{$formdata['title']}"  value="{$formdata['title']}" />
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
        <section class="cite-info m2o-flex-one">
        	<div class="search-cite-survey">
        		<span>搜索您想要引用问卷</span>
        		<input type="text" class="search-input" />
        		<p class="survey-search">搜索</p>
        	</div>
        	<div class="cite-info-box">
        		<div class="preview">
        			<p>预览</p>
        			<span>搜索您想要引用问卷</span>
        		</div>
        		<div class="info"></div>
        	</div>
        </section>
        <aside class="m2o-l m2o-aside">
        	<div class="cite-survey">
        		<p>已有调查问卷</p>
        		<div class="cite-tag" _id ="0">
        		<span>选择分类</span>
        		<ul class="cite-list">
        			<li id="0" _id="0">全部分类</li>
        			{foreach $sort as $k => $v}
        			<li id="{$v['id']}" _id="{$v['id']}">{$v['name']}</li>
        			{/foreach}
        		</ul>
        		</div>
        	</div>
        	<ul class="cite-survey-box">
        	<!-- {foreach $formdata as $k => $v}
        		<li id="{$v['id']}" _id="{$v['id']}" title="{$v['brief']}">{$v['title']}</li>
        	{/foreach}-->
        	</ul>
       	  	<div class="page_size"></div>
       	  	<div class="create-new">
       	  		<p>没有合适的问卷?</p>
       	  		<a href="./run.php?mid={$_INPUT['mid']}&a=form&id={$_INPUT['id']}&infrm=1" need-back>创建空白问卷</a>
       	  	</div>
        </aside>
    </div>
    </div>
    		<input type="hidden" name="type" value="" />
        	<input type="hidden" name="type_id" value="" />
        	<input type="hidden" name="1_title" value="" />
        	<input type="hidden" name="2_title" value="" />
        	<input type="hidden" name="3_title" value="" />
        	<input type="hidden" name="4_title" value="" />
        	<input type="hidden" name="1_option" value="" />
        	<input type="hidden" name="2_option" value="" />
        	<input type="hidden" name="1_initnum" value="" />
        	<input type="hidden" name="2_initnum" value="" />
        	<input type="hidden" name="1_brief" value="" />
        	<input type="hidden" name="2_brief" value="" />
        	<input type="hidden" name="1_more" value="" />
        	<input type="hidden" name="2_more" value="" />
        	<input type="hidden" name="4_tip" value="" />
        	<input type="hidden" name="1_required" value="" />
        	<input type="hidden" name="2_required" value="" />
        	<input type="hidden" name="3_required" value="" />
        	<input type="hidden" name="4_required" value="" />
        	<input type="hidden" name="4_max" value="" />
        	<input type="hidden" name="4_min" value="" />
        	<input type="hidden" name="2_max" value="" />
        	<input type="hidden" name="2_min" value="" />
        	<input type="hidden" name="3_num" value="" />
        	<input type="hidden" name="1_other" value="" />
        	<input type="hidden" name="2_other" value="" />
        	<input type="hidden" name="edit_proid" value="" />
        	<input type="hidden" name="delete_proid" value="" />
</form>
<!-- 预览模板 -->
<!-- 填空题 -->
<script type="text/x-jquery-tmpl" id="prepack-tpl">
<div class="cite-question" data-type="${type}" data-id="${id}" data-title="${type_name}" data-select="${select}">
	<div class="personal-info">
		<span>
			<a class="index"></a>
			<p>.</p>
		</span>
		<ul>
	    {{each option}}
	    <li class="fill-blank" style="display: -webkit-box;float: left;" data-num="{{if num}} {{= num[$index]}} {{else}} {{= $value['char_num']}} {{/if}}">
	    <p class="fill-name" style="font-size:14px;">{{if sign}} {{= $value }} {{else}} {{= $value['name']}} {{/if}}</p>
		<p>:</p>
	    <div style="width:125px;height:27px;border-bottom:1px solid #bababa;margin-top: -8px;"></div>
		</li>
	    {{/each}}
		</ul>
	</div>
</div>
</script>
<!-- 单选/多选题 -->
<script type="text/x-jquery-tmpl" id="presel-tpl">
<div class="cite-question" data-type="${type}" data-id="${id}" data-title="${type_name}" data-select="${select}" data-max="${max}" data-min="${min}" data-other="${other}">
	<span><a class="index"></a><em>.</em><p class="type-question">${title}</p></span>
	<div class="item-brief">
		<p class="brief">${brief}</p>
		{{if more}}
			<p class="item-more">【查看更多:<a class="more"> {{= more}}</a>】</p>
		{{/if}}
	</div>
	{{each option}}
	<div class="check"><input type="checkbox" name="" />
		<p class="sign">{{if sign}} {{= $value }} {{else}} {{= $value['name']}} {{/if}}</p>
		<span class="ininum" _initnum="{{if $value['initnum']}}{{= $value['initnum']}}{{else}}0{{/if}}">【{{if $value['initnum']}}{{= $value['initnum']}}{{else}}0{{/if}}】</span>
	</div>
	{{/each}}
	{{if other==1}}
		<div style="display: -webkit-box;display: -moz-box;margin-left: 25px;"><input type="checkbox" name="" />
		<p class="sign">其他</p>
		<div style="width: 200px;border-bottom: 1px solid #bababa;"></div>
	</div>
	{{/if}}
</div>
</script>
<!-- 文本题 -->
<script type="text/x-jquery-tmpl" id="pretext-tpl">
<div class="cite-question comment" data-type="${type}" data-id="${id}" data-title="${type_name}" data-select="${select}" data-max="${max}" data-min="${min}">
	<span><a class="index"></a><em>.</em><p class="type-question">${title}</p></span>
	<div style="width:576px;height:91px;border:1px solid #bababa;margin: 10px 0 5px 20px;">
		<p class="text-tip" style="margin: 5px 0 0 10px;color: #999;">${tips}</p>
	</div>
	<p class="tip">最少输入${min}个字,最多超过${max}个字</p>
</div>
</script>
<!-- 已有问卷 -->
<script type="text/x-jquery-tmpl" id="cite-tpl">
{{each option}}
<li id="{{= $value['id']}}" _id="{{= $value['id']}}" title="{{= $value['brief']}}">{{= $value['title']}}</li>
{{/each}}
</script>