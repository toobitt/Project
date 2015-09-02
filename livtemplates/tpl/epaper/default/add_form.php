<!--新增一期-->
{template:head}
{css:2013/button}
{css:2013/iframe}
{css:2013/list}
{css:add_form}
{js:2013/ajaxload_new}
{js:jqueryfn/jquery.tmpl.min}
{js:common/ajax_upload}
{js:epaper/ajax_uploadWithUrl}
{js:epaper/epaper}
{js:epaper/epaper_common}
{code}
$action = $a;
if($epaperInfo)
{
	$epaperInfo = $epaperInfo[0];
}
//print_r($epaperInfo);
if($action == 'create')
{
	$cur_date = $epaperInfo['cur_time'];
	$cur_stage = $epaperInfo['cur_stage'];
	$epaper_id = $epaperInfo['id'];
	$epaper_name = $epaperInfo['name'];
}
else if($action == 'update')
{
	$stack = $formdata['stack'];
	unset($formdata['stack']);
		
	foreach($formdata as $k => $v)
	{
		$$k = $v; 
	}
	$cur_date = $period_date;
	$cur_stage = $period_num;
	$epaper_id = $epaper_id;
	$period_id = $id;
}

{/code}
{code}
if(!isset($_INPUT['date_search']))
{
    $_INPUT['date_search'] = 1;
}
$attr_date = array(
	'class' => 'colonm down_list data_time',
	'show' => 'colonm_show',
	'width' => 104,/*列表宽度*/
	'state' => 1,/*0--正常数据选择列表，1--日期选择*/
	'is_sub'=> 0,
);
if(!$_INPUT['status_show'])
{
	$_INPUT['status_show']= -1;
}
{/code}

<body class="epaper-wrap">
<a class="prevent"></a>
<!-- 这一部分会被推进父层框架，成为检索条件和添加、配置按钮 -->
<div style="display:none">
	<!-- {template:unit/epaper_search}  -->
	<div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
	</div>
</div>	
<div class="tips"></div>
<span id = loading></span>	
	<form class="addEpaper" action="./run.php?mid={$_INPUT['mid']}&a={$action}" method="post">
	<div class="epaper">
		<div class="paper-info">
			<h3 class="name">{$epaper_name}</h3>
			<input type="hidden" class="needsave" value="false">
			<input type="hidden" name="period_id" value="{$period_id}">
			<input type="text" class="date-picker" name="period_date" value="{$cur_date}">
			<input type="number" min="1" class="num"  name="period_num" value="{$cur_stage}">期
		</div>
		<div class="epaper-btn">
			<!--  <input type="submit"  class="save-button" value="保存期刊" />-->
			<input type="button"  class="save-button" value="保存期刊" />
		</div>
		<div class="list-area">
			<div class="clear control">
				<div class="uplodeBtn">
					<span class="sortBtn">开启排序</span>
					<a class="jpg batch-jpg" _type="jpg">批量上传JPG</a>
					<a class="pdf batch-pdf" _type="pdf">批量上传PDF</a>
				</div>
				<ul class="tab-control">
				{if $stack}
					{foreach $stack as $k => $v}
					<li class="control-item" _flag="{$v['zm']}" _id="{$k}" _needAjax='true'>
						<div class="stack-flag">{$v['name']}</div>
						<div class="edit">
							<input class="edit-stack" value="{$v['name']}"/>
						</div>
						<input type="hidden" name="stack_id[]" class="stack_id_hid" value="{$k}"/>
					</li>
					{/foreach}
					{else}
					<!-- 
					<li class="control-item" _flag='A' _id='1' _needAjax='true'>
						<div class="stack-flag">A叠</div>
						<div class="edit">
							<input class="edit-stack" value="A叠"/>
						</div>
						<input type="hidden" name="stack_id[]" class="stack_id_hid" value='1'/>
					</li>
					-->
				{/if}
					<li class="add add-stack">新增一叠</li>
				</ul>
				<!-- 
				<a class="edit-btn" _command='edit'>修改</a>
				 -->
			</div>
			<div class="tab-area clear">
				<input type="file" style="display:none;" class="add-epaper-file" accept="image/jpg">
				<input type="file" style="display:none;" class="pdf-file" multiple="multiple" accept="application/pdf">
				<div class="prev-lists-area" _needsave='false'>
				{if $stack}
					{foreach $stack as $k => $v}
					<ul class="each-list" id="" _id="{$k}" _flag="{$v['zm']}">
					</ul>
					{/foreach}
					{else}
					<!-- 
					<ul class="each-list" id="" _id='1' _flag='A'>
					</ul>
					-->
				{/if}
					<div class='add-epaper' _flag="">
						<div class="prev upload-file">
							<a class="upload-jpg" _type="jpg">上传JPG</a>
							<a class="upload-pdf" _type="pdf">上传PDF</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="pdf-box m2o-flex">
		<div class="save-box">
			<a class="save-pdf">保存PDF</a>
		</div>
		<ul class="num-list">
		</ul>
		<ul class="pdf-list m2o-flex-one">
		</ul>
	</div>
	{if $a == 'update'}
	<input type="hidden" name="id" value="{$id}" />
	{/if}
	<input type="hidden" name="epaper_id" value="{$epaper_id}" />
	<input type="hidden" name="referto" value="./run.php?a=relate_module_show&app_uniq=epaper&mod_uniq=period&epaper_id={$epaper_id}&epaper_name={$epaper_name}&cur_date={$cur_date}&cur_stage={$cur_stage}&infrm=1" />

	<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	</form>
	
	<div class="m2o-bottom  m2o-flex m2o-flex-center">
		<div class="m2o-paixu">
			<input type="checkbox" title="全选" name="checkall" class="checkAll"/>
		</div>
		<div class="m2o-batch batch-delete" data-method="delete">
       		<a name="batdelete" data-method="delete" class="batdelete">删除</a>
       	</div>
	</div>
</body>
<script type="text/x-jquery-tmpl" id="new-epaper-tpl">
<li class="item" _flag="${page_num}" _belong="${flag}" _id="${page_id}" _pagenum="${page_num}" _orderid="${order_id}">
	<div class="prev" >
		<img class="jpg show" src="${jpgSrc}" _id="${img_id}" _type='jpg'>
		<img class="pdf" src="${pdfSrc}" _id="${pdf_id}" _type='pdf'>
		<span class="pageNum">${flag}${page_num}</span>
		<input class="edit-page" value="${flag}${page_num}"/>
	</div>
	<div class="file-format">
		<a class="update-jpg target-update" _type="jpg">编辑JPG</a>
		<a class="update-pdf target-update" _type="pdf">编辑PDF</a>
		<span class="pdf-icon ${hasPdf}">pdf</span>
	</div>
	<a class="del"></a>
	<input type="text" class="item-title"  value="${title}" placeholder='填写标题'/>
	<input type="hidden" name="jpg_id[]" class="jpg_id_hid" value="${img_id}"/>
	<input type="hidden" name="pdf_id[]" class="pdf_id_hid" value="${pdf_id}"/>
	<input type="hidden" name="page_id[]" class="page_id_hid" value="${page_id}"/>
</li>
</script>
<script type="text/x-jquery-tmpl" id="new-stack-tpl">
<ul class="each-list" _id="${stackId}" _flag="${flag}">	
</ul>
</script>
<script type="text/x-jquery-tmpl" id="control-item-tpl">
<li class="control-item" _flag="${flag}" _id="${id}" _needajax="false">
	<div class="stack-flag">${flag}${chin}</div>
	<div class="edit">
		<input class="edit-stack" value="${flag}${chin}"/>
	</div>
	<input type="hidden" name="stack_id[]" class="stack_id_hid" value="${id}"/>
</li>
</script>
{template:foot}
