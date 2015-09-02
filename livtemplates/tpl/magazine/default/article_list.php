{template:head}
{css:2013/iframe}
{css:2013/button}
{css:2013/list}
{css:issue_less}
{js:2013/ajaxload_new}
{js:box_model/list_sort}
{js:2013/list}
{js:magazine/magazine-add}
{js:magazine/article-list}
{code}
$issue_info = $list[0];
unset($list[0]);
//print_r($issue_info);
{/code}

<!-- 这一部分会被推进父层框架，成为检索条件和添加、配置按钮 -->
<div {if $_INPUT['infrm']}style="display:none"{/if}>
	{template:unit/article_search}
	<div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
		<a class="add-button news mr10" href="run.php?mid={$_INPUT['mid']}&a=form&maga_id={$_INPUT['maga_id']}&issue_id={$_INPUT['issue_id']}&infrm=1" target="formwin">添加文章</a>
	</div>
</div>	
<div class="common-list-content wrap">
  <div class="m2o-main m2o-flex">
	  <aside class="m2o-l">
	  		<form class="common-list issue-edit" action="run.php?mid=241&a=update" method="post">
			  	<div class="m2o-cont">
			  		{code}
	               		$img = $issue_info['host'].$issue_info['dir'].'120x156/'.$issue_info['file_path'].$issue_info['file_name'];
	               	{/code}
					<label>封面: </label>
					<p class="period-cover"><img class="cover-img" src="{$img}"></p>
					<input type="file" name="files" style="display:none;" id="cover-file" />
				</div>
				<div class="m2o-cont">
					<label>杂志: </label>
					<input name="maga_name" value="{$issue_info['maga_name']}" readonly="readonly"/>
				</div>
				<div class="m2o-cont">
					<label>期号: </label>
					<input name="issue" value="{$issue_info['issue']}"/>
				</div>
				<div class="m2o-cont">
					<label>总期号: </label>
					<input name="total_issue" value="{$issue_info['total_issue']}"/>
				</div>
				<div class="m2o-cont">
					<label>出版日期: </label>
					<input name="pub_date" class="date-picker" value="{$issue_info['pub_date']}"/>
				</div>
				<div class="m2o-cont fenge-dotted">
				</div>
				<ul class="cont-area artical-sort" data-issueid="{$issue_info['id']}">
					{foreach $issue_info['sort_info'] as $k => $sort_info}
					<li class="m2o-cont" _id="{$sort_info['id']}" >
						<input type="text" class="text" name="article_sort_name[]" placeholder="新分类" value="{$sort_info['name']}">-<input type="text" class="num" name="article_num[]" placeholder="新分类" value="{$sort_info['article_num']}">
						<em>{$sort_info['cur_article_num']}</em>
						<a class="text-set text-del"></a>
						<input type="hidden" name="article_sort_id[]" value="{$sort_info['id']}">
					</li>
					{/foreach}
					<li class="m2o-cont">
						<input type="text" class="text" name="article_sort_name_add[]" placeholder="新分类" />-<input type="text" name="article_num_add[]" class="num" placeholder="篇数" />
						<em>&nbsp;</em>
						<a class="text-set text-add"></a>
					</li>
				</ul>
				<div class="m2o-btn">
					<input type="hidden" name="a" value="update" id="action" />
					<input type="hidden" name="id" value="{$issue_info['id']}" />
					<input type="hidden" name="magazine_id" value="{$issue_info['magazine_id']}" />					
					<input type="submit" name="sub" value="保存" class="save-button"/>
				</div>
				<span class="result-tip"></span>
				<img src="{$RESOURCE_URL}loading2.gif" class="loading">
			</form>
		</aside>
		<section class="m2o-m m2o-flex-one">
			<div class="m2o-list">
				<!--排序模式打开后显示排序状态-->
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
				{if $list}
					{foreach $list as $k => $v} 
						{template:unit/articlelist}
					{/foreach}
				{else}
					<p class="common-list-empty">没有你要找的内容！</p>
				{/if}
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
		    		<div id="page_size">{$pagelink}</div>
		    	</div>
		     </div>
		</section>
	</div>
</div>
<script>
(function($){
	var data = $.globalListData = {code}echo $list ? json_encode($list) : '{}';{/code};
    $.extend($.geach || ($.geach = {}), {
        data : function(id){
            var info;
            $.each(data, function(i, n){
               if(n['id'] == id){
                   info = {
                       id : n['id']
                   }
                   return false;
               }
            });
            return info;
        }
    });
})(jQuery);
</script>
<script type="text/x-jquery-tmpl" id="leftcontadd-tpl">
	 <li class="m2o-cont">
		<input type="text" class="text" name="article_sort_name_add[]" placeholder="新分类" value="">-<input type="text" class="num" name="article_num_add[]" placeholder="篇数" value="">
		<em>&nbsp;</em>
		<a class="text-set text-add"></a>
	</li>
</script>
<script  type="text/x-jquery-tmpl" id="nodata-tpl">
<p class="common-list-empty">没有你要找的内容！</p>
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