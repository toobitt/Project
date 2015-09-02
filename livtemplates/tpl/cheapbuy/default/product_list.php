{template:head}
{css:product_list}
{js:2013/ajaxload_new}
{js:2013/list}
{js:cheapbuy/product_list}
{code}
	//hg_pre($list);
{/code}

<div style="display:none">
	{template:unit/product_search}
	<div class="controll-area fr mt5" id="hg_page_menu" style="display:block">
		<a class="add-button news mr10" href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" target="formwin">新增商品</a>
	</div>
</div>
<div class="product-wrap">
	<div class="handler-btns">
		<span class="order-btn">开启排序</span>
	</div>
	<ul class="product-list">
	{if $list}
		{foreach $list as $k => $v}
			{template:unit/product_item}
		{/foreach}
	{else}
	  <p style="color:#da2d2d;text-align:center;font-size:16px;line-height:20px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
	{/if}
	</ul>
	<div class="m2o-flex product-bottom">
		<input type="checkbox" name="checkall" class="checkAll" title="全选">
		<a name="delete" data-method="delete" class="batch-handle">删除</a>
		{$pagelink}

	</div>
</div>
{template:foot}