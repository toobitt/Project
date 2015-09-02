{template:head}
{css:2013/list}
{css:2013/iframe}
{css:2013/button}
{css:market}
{js:jqueryfn/jquery.tmpl.min}
{js:common/ajax_upload}
{js:supermarket/market_list}

<!-- 这一部分会被推进父层框架，成为检索条件和添加、配置按钮 -->
<div style="display:none">
	{template:unit/market_search}
	<div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
	</div>
</div>

<div class="wrap clear">
	<div class="market-wrap">
		<form method="post" action="" name="vod_sort_listform">
			<ul class="market-list clear">
				<li class="market-add">
					<div class="market-name">
						<input name="market_name" placeholder="添加商超名称" class="market-head add-head"/>
						<span class="mk-save mkt-save">保存</span>
					</div>
					<div class="market-default">
						<span>添加LOGO</span>
						<img src="" />
						<input type="hidden" name="logo_id" class="market-logoid" value="" />
					</div>
				</li>
				{foreach $list as $k=>$v}
				    {template:unit/marketlist}
				{/foreach}
				<input type="file" name="index_pic" accept="image/png,image/jpeg" class="image-file" style="display: none;" />
			</ul>
			<div class="m2o-bottom m2o-flex m2o-flex-center">
			  	 <div class="market-operate">
			  	 	<input type="checkbox" name="checkall" id="checkAll" />
			  	    <a name="state" data-method="audit" class="bataudit">审核</a>
			  	    <a name="back" data-method="back" class="batback">打回</a>
			  	    <a name="batdelete" data-method="delete" class="batdelete">删除</a>
			  	 </div>
			  	 <div class="m2o-flex-one">
			  	 {$pagelink}
			  	 </div>
  			</div>
		</form>
	</div>
</div>
{template:foot}
<script type="text/x-jquery-tmpl" id="add-market-tpl">
	<li class="market-each" id="r_${id}" name="${id}" _id="${id}"  orderid="${order_id}">
		<div class="market-name">
			<a href="./run.php?mid={$relate_module_id}&market_id=${id}" target="formwin">${market_name}</a>
			<input type="text" value="${market_name}" name='market_name' class="market-head"/>
			<span class="mk-save save">保存</span>
		</div>
		<div class="market-content">
			<div class="market-info m2o-flex">
			    <div class="market-img">
			    	{{if logo}}
					<img src="${logo}" class="mk-logo" width="55" height="55" id="img_${id}"/>
					{{else}}
					<img src="{$RESOURCE_URL}market/default_logo.png" class="mk-logo" width="55" height="55" id="img_${id}"/>
					{{/if}}
					<a class="cover-img"></a>
					<input type="hidden" name="logo_id" class="market-logoid" value="${logo_id}" />
			        <span _id="${id}"  _status="1" class="reaudit" style="color:#8ea8c8">待审核</span>
			    </div>
			    <div class="market-profile m2o-flex-one">
				    <div class="market-intro"><label>门店：</label><span>0</span></div>
				    <div class="market-intro"><label>会员：</label><span>0/0</span></div>
				    <div class="market-intro"><label>商品：</label><span>0/0</span></div>
			    </div>
		    </div>
			<div class="market-time">
					<span>${user_name}</span><span>${create_time}</span><em class="edit"></em><em class="del"></em>
			</div>
		</div>
	</li>		
</script>


