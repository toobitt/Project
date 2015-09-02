{template:head}
{template:list/common_list}
{template:form/common_form}
{js:2013/ajaxload_new}
{js:common/ajax_upload}
{js:jqueryfn/jquery.tmpl.min}
{js:special/special_conlist}
{css:2013/button}
{css:common/common_form}
{css:2013/m2o}
{css:common/common}
{css:2013/iframe_form}
{css:2013/iframe}
{css:special}
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
<div class="common-form-head special-form special-con-head">
     <div class="common-form-title">
          <h2>
          	<a class="property-tab" href="./run.php?a=relate_module_show&app_uniq=special&mod_uniq=special&mod_a=form&id={$speid}" target="formwin" need-back>属性</a>
          	<a class="con-tab on">内容</a>
          	<a class="con-tab" href="./run.php?a=relate_module_show&app_uniq=special&mod_uniq=special&mod_a=built_template_form&id={$speid}" target="formwin" need-back>模板</a>
          </h2>
          <div class="form-dioption-title form-dioption-item">
                <div class="special-title">{$title}</div>
          </div>
		  <div class="form-dioption-submit">
		      <span class="option-iframe-back">关闭</span>
		  </div>
    </div>
</div>
<div class="common-form-main special-con-area" data-id="{$speid}">
      <div class="special-left">
	     <div class="comuln-area">
	          <div class="column-all on">全部栏目<span class="column-sort-btn">开启排序</span></div>
	          <div class="column-controll">
	               <input type="text" value="" class="column_input"/>
	               <span class="add-column"><em>+</em>增加栏目</span>
	          </div>
	          <ul class="comuln-list">
		         {foreach $columns as $clk => $clv}
                 <li id="{$clk}" order_id="{$clv['order_id']}">
                     <span class="item column_item" data-id="{$clk}">{$clv['column_name']}</span>
                     <a href="./run.php?mid={$_INPUT['mid']}&a=column_form&column_id={$clk}" class="column-editor">编辑</a>
                     <span class="del-column"></span>
                 </li>		           
		         {/foreach}
	          </ul>
	     </div>
       </div>
      <div class="conlist">
        <div class="special-top">
           {template:unit/special_search,$true,true}
           <div class="controll-area fr mt5" id="hg_parent_page_menu">
		     <a class="add-button">添加内容</a>
           </div>
        </div>
        <form method="post" action="" name="listform" class="common-list-form">
        <!-- 头部，记录的列属性名字 -->
		<ul class="common-list special-list" id="special-list-head">
			<li class="common-list-head public-list-head clear">
				<div class="common-list-left">
	                <div class="common-list-item paixu open-close">
 	                   <a title="排序模式切换/ALT+R" onclick="hg_switch_order('speciallist');"  class="common-list-paixu"></a>
                    </div>
                </div>
				<div class="common-list-right">
				    <div class="common-list-item wd120">发布栏目</div>
				    <div class="common-list-item wd120">专题栏目</div>
                    <div class="common-list-item wd80">类型</div>
                    <div class="common-list-item wd60">权重</div>
                    <div class="common-list-item wd60">状态</div>
                    <div class="common-list-item wd150">添加人/时间</div>
                </div>
                <div class="common-list-biaoti">
					<div class="common-list-item">标题</div>
				</div>
			</li>
		</ul>
		<!-- 主题，记录的每一行 -->
		<ul class="special-list common-list public-list hg_sortable_list" id="speciallist" data-table_name="article" data-order_name="order_id">
		</ul>
		<!-- foot，全选、批处理、分页 -->
		<ul class="common-list public-list" id="special-list-bottom">
			<li class="common-list-bottom clear" style="padding-bottom:10px;">
				<div class="common-list-left">
					<input type="checkbox" name="checkall" value="infolist" title="全选" rowtag="LI" />
					<a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'audit', '审核', 1, 'id', '&audit=1', 'ajax', 'hg_change_status');" name="audit">审核</a>
					<a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'audit', '打回', 1, 'id', '&audit=0', 'ajax', 'hg_change_status');" name="back">打回</a>
					<a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');" name="delete">删除</a>
					<a style="cursor:pointer;" class="batch-move">移动</a>
					<!-- <a style="cursor:pointer;" onclick="return hg_batchmove(this);" name="move" >移动</a> -->
				</div>
				<div class="page_area"></div>
			</li>
		</ul> 
	</form> 
     </div>
     <!-- 栏目编辑浮窗 -->
     <div id="edit-column-pop">
     	 <div class="column-pop-box">
			<div class="arrow"></div>
			<form action="./run.php?mid={$_INPUT['mid']}" method="post">
				<div class="head">
					<span class="title">编辑栏目</span>
					<span class="save-area"><input type="submit"  value="保存" class="pop-save-button" /></span>
					<span class="pop-close-button2 close-area"></span>
				</div>
				<div class="column-pop-content content"></div>
			</form>
		</div>
     </div>
     <!-- 栏目编辑浮窗 -->
     <!-- 排序模式打开后显示，排序状态的 -->
	<div id="infotip"  class="ordertip"></div>
</div>
<div class="mask"></div>
<div id="top-loading"></div>


<!-- 关于记录的操作和信息 -->
{template:unit/con_record_edit}
<!-- 添加内容弹窗 -->
{template:unit/special_modules}

<!-- 栏目移动弹窗 -->
{template:unit/special_move}

<!-- 栏目模板 -->
<script type="text/x-jquery-tmpl" id="column_list">
	   <li id="${id}" order_id="${id}">
	     <span class="item  column_item" contenteditable="true" data-id="${id}">${name}</span>
		<a href="./run.php?mid={$_INPUT['mid']}&a=column_form&column_id=${id}" class="column-editor">编辑</a>
	     <span class="del-column"></span>
	   </li>
</script>
<!-- 栏目编辑模板 -->
<script type="text/x-jquery-tmpl" id="column_edit_tpl">
	<ul class="form_ul">
		<li>
			<div class="form_item">
				<span class="title">栏目名称：</span>
				<input type="text" value="${column_name}" name='column_name' />
			</div>
		</li>
		<li>
			<div class="form_item">
				<span  class="title">栏目外链：</span>
				<input type="text" value="${outlink}" name='outlink' />
			</div>
		</li>
		<li>
			<div class="form_item make-type">
				<span class="title">生成方式:</span>
			{code}
			$attr_column = array(
				'class' => 'custom-select down_list',
				'show' => 'column_show',
				'width' =>104,
				'state' =>0,
				'is_sub' =>1
			);
			$attr_index = array(
				'class' => 'custom-select down_list',
				'show' => 'index_show',
				'width' =>104,
				'state' =>0,
				'is_sub' =>1
			);
			{/code}
			{template:form/search_source,maketype,$_INPUT['maketype'],$_configs['make_style'],$attr_column}
			</div>
		</li>
		<li>
			<div class="form_item index-type">
				<span class="title">首页：</span>
				{template:form/search_source,column_file,$_INPUT['column_file'],$_configs['index_type'],$attr_index}
			</div>
		</li>
		<li>
			<div class="form_item">
				<span  class="title">首页名称:</span>
				<input type="text" value="${colindex}" name='colindex' />
			</div>
		</li>
	</ul>
				
	<input type="hidden" name="a" value="update_special_column" />
	<input type="hidden" name="id" value="${id}" />
	<input type="hidden" name="special_id" value="${special_id}" />
	<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
</script>
<!-- 专题内容列表模板 -->
<script type="text/x-jquery-tmpl" id="specialcon_list">
       {template:unit/specialcontlist}
</script>
<!-- 专题内容列表模板 -->
<script type="text/x-jquery-tmpl" id="selectcon_list">
       {template:unit/selectcontlist}
</script>

