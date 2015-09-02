{template:head}
{template:list/common_list}
{template:form/common_form}
{js:2013/ajaxload_new}
{js:common/ajax_upload}
{js:jqueryfn/jquery.tmpl.min}
{js:special/special_tmpl}
{css:2013/button}
{css:common/common_form}
{css:2013/m2o}
{css:common/common}
{css:2013/iframe_form}
{css:2013/iframe}
{css:special}
{css:2013/form}
{code}
$list=$special_content_list[0];
$title=$list['special_name'];
unset($list['special_name']);
$columns=$columns[0];
$consorts = $special_content_list[0]['sorts'];
unset($special_content_list[0]['sorts']);
$info=$list['info'];
$speid=$_INPUT['speid'];
$true=true;
{/code}
<script>
  $(function(){
	  $('.colonm.down_list').deferHover();
	})

</script>
<style>
body{background:#e5e5e5!important;}
</style>
<div class="m2o-form" data-id="{$_INPUT['id']}">
<header class="common-form-head special-form special-con-head">
     <div class="common-form-title">
          <h2>
          	<a class="property-tab" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$_INPUT['id']}&infrm=1" target="formwin" need-back>属性</a>
          	<a href="./run.php?&a=relate_module_show&app_uniq=special&mod_uniq=special_content&mod_a=show&speid={$_INPUT['id']}&infrm=1" target="formwin" need-back class="con-tab">内容</a>
          	<a class="con-tab on">模板</a>
          </h2>
          <div class="form-dioption-title form-dioption-item special-tpl-title">
                <p class="special-title"></p>
          </div>
		  <div class="form-dioption-submit">
		      <span class="option-iframe-back">关闭</span>
		  </div>
    </div>
</header>

<div class="m2o-inner" data-id="{$_INPUT['id']}">
    <div class="m2o-main m2o-flex">
        <aside class="m2o-left m2o-aside">
        	<div class="show-tmpl">
        		<div class="tmpl-selected">
        			<img src="" class="tpl-img"/>
        		</div>
				<a class="into-special" target="_blank" go-blank>进入快速专题</a>
        		<p class="default-tmpl">已选模板</p>
        	</div>	
        	<div class="m2o-record">
        		<span class="history-record">历史记录</span>
        		<ul class="record-list"></ul>
        	</div> 
        </aside>
        <section class="m2o-m m2o-flex-one">
        	<ul class="tmpl-nav">
        	<li id="0" _id="0">全部</li>
        	</ul>
        	<div class="search-tpl-box">
        		<input type="text" name="search-tpl" placeholder="输入搜索条件"/>
        		<p class="tpl-search-btn"></p>
        	</div>
        	<div class="tmpl-box">
        		<ul class="tmpl-list"></ul>
        	</div>
        </section>
    </div>
</div>
</div>
<!-- 历史记录 -->
<script type="text/x-jquery-tmpl" id="record-tpl">
<li class="list-record m2o-flex">
      <div class="list-tmpl">{{= title}}</div>
      <div class="list-name">{{= user_name}}</div>
      <div class="list-time">{{= create_time}}</div>
</li>
</script>

<!-- 模板 -->
<script type="text/x-jquery-tmpl" id="moban-tpl">
<li class="f-item" data-id="{{= id}}" data-sign="{{= sign}}" data-bigimg="{{= realBigPic}}" _id="{{= id}}">
	<div class="f-m m2o-flex m2o-flex-center tmpl-img">
    	{{if pic && pic.length}}
    	<img class="img-tpl" src="{{= realPic}}" alt="{{= title}}"/>
    	{{/if}}
	</div>
	<span class="f-t">{{= title}}</span>
	<p class="choose-tpl" title="点击选择此模板">选择</p>
	<p class="sel-sign"></p>
</li>
</script>

<!-- 标签 -->
<script type="text/x-jquery-tmpl" id="tag-tpl">
<li id="${id}" _id="${id}">${name}</li>
</script>

<!-- 预览框 -->
<script type="text/x-jquery-tmpl" id="show-big-pic-tpl">
<div class="big-pic-box">
	<p></p>
	<img src="${src}" />
</div>
</script>