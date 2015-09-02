{template:head}
{css:2013/iframe}
{css:2013/list}
{css:2013/button}
{css:magazine_less}
{css:issue_less}
{js:box_model/list_sort}
{js:2013/ajaxload_new}
{js:page/page}
{js:2013/list}
{js:magazine/lastest-list}
{code}
//print_r($formdata);
//print_r($appendMagazine);
{/code}
<!-- 这一部分会被推进父层框架，成为检索条件和添加、配置按钮 -->
<div {if $_INPUT['infrm']}style="display:none"{/if}>
	{template:unit/lastest_search}
	<div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
		<!-- <a class="add-button pop-add" data-type="magazine">新增杂志</a> -->
	</div>
</div>	
<div class="common-list-content wrap">
  <!-- <a class="add-button pop-add add-lastest" data-type="magazine">新增杂志</a> -->
  <div class="m2o-main m2o-flex">
	  <aside class="m2o-lastest">
		<ul class="magazine-list lastest-list clear">
		{foreach $formdata as $k => $v}
			<li class="magazine-each" data-id="{$v['id']}" data-issueid="{$v['issue_id']}">
				<div class="mag-img">
					<img src="{$v['url']}">
					<p>{$v['sort_name']}/{$v['release_cycle']}</p>
					<a class="period-href" title="往期列表" href="./run.php?a=relate_module_show&app_uniq=magazine&mod_uniq=issue&maga_id={$v['id']}&cur_nper={$v['current_nper']}&infrm=1" target="mainwin"></a>
				</div>
	      		<h4>{$v['year']}第{$v['current_nper']}期 总{$v['volume']}期</h4>
	      		<p><span>{$v['user_name']}</span>{$v['update_time']}</p>
	      		<a class="del" data-method="del_maga"></a>
	      		<input type="hidden" name="magname" value="{$v['name']}" />
	      		<input type="hidden" name="current_nper" value="{$v['current_nper']}" />
  				<input type="hidden" name="volume" value="{$v['volume']}" />
			</li>
		{/foreach}
		</ul>
		<div class="load-more">加载更多</div>
	</aside>
	<section class="m2o-m m2o-flex-one">
		<div class="magazine-profile">
		</div>
		<div class="m2o-list">
			<div id="infotip" class="ordertip">排序模式已关闭</div>
	        <div class="m2o-title m2o-flex m2o-flex-center">
	        	<div class="m2o-item m2o-paixu" title="排序">
	        		<a title="排序模式切换/ALT+R" class="common-list-paixu"></a>
	        	</div>
	            <div class="m2o-item m2o-flex-one m2o-bt" title="标题">标题</div>
	            <div class="m2o-item m2o-classify" title="分类">分类</div>
	            <div class="m2o-item m2o-author" title="作者">作者</div>
	            <div class="m2o-item m2o-editor" title="主编">主编</div>
	            <div class="m2o-item m2o-state" title="状态">状态</div>
	            <div class="m2o-item m2o-time" title="时移节目名称">添加人/时间</div>
	        </div>
			 <div class="m2o-each-list">
		   	 </div>
		    <div class="m2o-bottom m2o-flex m2o-flex-center">
		        <div class="m2o-item m2o-paixu">
		    		<input type="checkbox" name="checkall" class="checkAll" rowtag="m2o-item" title="全选"/>
				</div>
				<div class="m2o-item m2o-flex-one list-config">
				   <a data-method="audit" class="batch-audit">审核</a>
				   <a data-method="back" class="batch-back">打回</a>
				   <a data-method="delete" class="batch-delete">删除</a>
				</div>
				<div id="page_size"></div>
			</div>
	  	</div>
    </section>
  </div>
</div>
<script type="text/x-jquery-tmpl" id="getprofile-tpl">
	<h2>${magname}</h2>
  	<div class="magazine-menu">
  		<a class="save-button sub-add" href="run.php?mid={$_INPUT['mid']}&a=form&maga_id=${maga_id}&issue_id=${issue_id}&infrm=1" target="formwin">增加文章</a>
  		<input type="hidden" name="volume" value="${volume}">
  		<input type="hidden" name="current_nper" value="${current_nper}">
  	</div>
  	<ul class="clear">
  		{{if sortlist}}{{tmpl($data['sortlist']) "#getsort-tpl"}}{{/if}}
  	</ul>
</script>

<script type="text/x-jquery-tmpl" id="getsort-tpl">
	<li data-id="${id}"><label>${name}: </label><span>${cur_article_num}/${article_num}</span></li>
</script>

<script type="text/x-jquery-tmpl" id="nodata-tpl">
	<p class="common-list-empty">没有你要找的数据！</p>
</script>

<script type="text/x-jquery-tmpl" id="articallist-tpl">
	<div class="m2o-each m2o-flex m2o-flex-center" data-id="${id}" orderid="${order_id}">
        <div class="m2o-item m2o-paixu">
		   <input type="checkbox"  value="${id}" title="${id}"  name="infolist[]" class="m2o-check" />
    	</div>
        <div class="m2o-item m2o-flex-one m2o-bt">
           <div class="m2o-title-transition m2o-title-overflow">
        	 <a href="./run.php?mid={$_INPUT['mid']}&a=form&id=${id}&infrm=1" target="formwin">
        	 	{{if indexpic}}
        	 	<img style="margin-right: 10px;" src="${indexpic}" />
        	 	{{/if}}
        	 	<span>${articletitle}</span>
	 		</a>
           </div>
        </div>
        <div class="m2o-item m2o-classify">${sort_name}</div>
        <div class="m2o-item m2o-author">${article_author}</div>
        <div class="m2o-item m2o-editor">${redactor}</div>
        <div class="m2o-item m2o-state" data-method="${op}" _id="${id}" style="color:${status_color};">${audit}</div>
        <div class="m2o-item m2o-time">
            <span class="name">${user_name}</span>
            <span class="time">${create_time}</span>
        </div>
        <div class="m2o-item m2o-ibtn"></div>
    </div>
</script>	

<script type="text/x-jquery-tmpl" id="magadd-tpl">
	<li class="magazine-each" data-id="${id}" data-issueid="${issue_id}">
		<div class="mag-img">
			<img src="${url}" />
			<p>${sort_name}/${release_cycle}</p>
			<a class="period-href" title="往期列表" href="./run.php?a=relate_module_show&app_uniq=magazine&mod_uniq=issue&maga_id=${id}&cur_nper=0&infrm=1" target="mainwin"></a>
		</div>
  		<h4>${year}{{if current_nper}}第${current_nper}期{{/if}} {{if volume}}总${volume}期{{/if}}</h4>
  		<p><span>${editor}</span>${create_time}</p>
  		<a class="del" data-method="del_maga"></a>
  		<input type="hidden" name="magname" value="${tname}" />
  		<input type="hidden" name="current_nper" value="${current_nper}" />
  		<input type="hidden" name="volume" value="${volume}" />
	</li>
</script>

<script type="text/x-jquery-tmpl" id="issueadd-tpl">
	<li class="magazine-each" data-id="${id}" data-issueid="${issue_id}">
		<div class="mag-img">
			<img src="${url}" />
			<p>${sort_name}/${release_cycle}</p>
			<a class="period-href" title="往期列表" href="./run.php?a=relate_module_show&app_uniq=magazine&mod_uniq=issue&maga_id=${id}&cur_nper=0&infrm=1" target="mainwin"></a>
		</div>
  		<h4>${year}{{if current_nper}}第${current_nper}期{{/if}} {{if volume}}总${volume}期{{/if}}</h4>
  		<p><span>${editor}</span>${create_time}</p>
  		<a class="del" data-method="del_maga"></a>
  		<input type="hidden" name="magname" value="${tname}" />
  		<input type="hidden" name="current_nper" value="${current_nper}" />
  		<input type="hidden" name="volume" value="${volume}" />
	</li>
</script>
<script type="text/x-jquery-tmpl" id="m2o-option-tpl">
<div class="m2o-option" data-id="{{= id}}">
    <div class="m2o-option-inner m2o-flex">
        <div class="m2o-btns m2o-flex">
			<div class="m2o-btn-area m2o-flex">
				<a href="./run.php?mid={$_INPUT['mid']}&a=form&id={{= id}}&infrm=1" target="formwin">编辑</a>
				<a class="option-delete" data-method="delete">删除</a>
				<a></a>
				<a></a>
			</div>
			<div class="m2o-option-line"></div>
        </div>
    </div>
	<div class="m2o-option-confirm">
			<p>确定要删除该内容吗？</p>
			<div class="m2o-option-line"></div>
			<div class="m2o-option-confim-btns">
				<a class="confim-sure">确定</a>
				<a class="confim-cancel cancel">取消</a>
			</div>
	</div>
	<div class="m2o-option-close"></div>
</div>
</script>
{template:foot}