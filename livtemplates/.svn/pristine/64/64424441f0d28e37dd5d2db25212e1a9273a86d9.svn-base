{template:head}
{css:2013/iframe}
{css:2013/button}
{css:2013/m2o}
{css:magazine_less}
{css:issue_less}
{js:2013/ajaxload_new}
{js:2013/list}
{js:magazine/magazine-add}
{js:magazine/magazine-list}
{code}
$bottomData = array(
	'audit' => '审核',
	'back' => '打回',
	'delete' => '删除'
);
$maga_info = $list[0];
unset($list[0]);
//print_r($maga_info);
//print_r($list)
{/code}

<!-- 这一部分会被推进父层框架，成为检索条件和添加、配置按钮 -->
<div style="display:none">
	{template:unit/issue_search}
	<div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
		<!-- <a type="button" class="button_6" href="run.php?mid={$_INPUT['mid']}&a=form&maga_id={$_INPUT['maga_id']}&cur_nper={$_INPUT['cur_nper']}&infrm=1">新增期刊</a> -->
	</div>
</div>	
<div class="common-list-content wrap">
	  <div class="m2o-main m2o-flex">
		  <aside class="m2o-l">
		  	<form class="common-list" action="run.php?mid={$_INPUT['mid']}&a=update_magazine" method="post">
				<div class="m2o-cont">
					<label>名称: </label><input type="text" name="title" value="{$maga_info['name']}" {if !$maga_info['update_tag']}readonly="readonly"{/if}/>
				</div>
				<div class="m2o-cont">
					<label>描述: </label><input type="text" name="brief" value="{$maga_info['brief']}"/>
				</div>
				<div class="m2o-cont">
					{code}
							$group_id = $maga_info['sort_id'];
							$default = $group_id ? $group_id : -1;
							$gname = $appendSort[0];
							$gname[-1] = '选择分类';
							
					{/code}
					<label>分类: </label>{template:form/select,group_id,$default,$gname, $css_attr}
				</div>
				<div class="m2o-cont">
					<label>周期: </label>{template:form/select,release_cycle,$maga_info['release_cycle'],$_configs['release_cycle'], $css_attr}
				</div>
					
				<div class="m2o-cont">
					<label>主办单位: </label><input type="text" name="sponsor" value="{$maga_info['sponsor']}"/>
				</div>
				<div class="m2o-cont">
					<label>责任编辑: </label><input type="text" name="editor" value="{$maga_info['editor']}"/>
				</div>
				<div class="m2o-cont">
					<label>国内刊号: </label><input type="text" name="cssn" value="{$maga_info['cssn']}"/>
				</div>
				<div class="m2o-cont">
					<label>国际刊号: </label><input type="text" name="issn" value="{$maga_info['issn']}"/>
				</div>
				<div class="m2o-cont">
					<label>页数: </label><input type="text" name="page_num" value="{$maga_info['page_num']}"/>
				</div>
				<div class="m2o-cont">
					<label>语言: </label><input type="text" name="language" value="{$maga_info['language']}"/>
				</div>
				<div class="m2o-cont">
					<label>价格: </label><input type="text" name="price" value="{$maga_info['price']}"/>
				</div>
				<div class="m2o-cont fenge-dotted">
				</div>
				<ul class="cont-area artical-sort">
				{foreach $maga_info['contract_way'] as $k => $contract_way}
				{if $contract_way['contract_name'] && $contract_way['contract_value']}
			    <li class="m2o-cont"><input type="text" name="contract_name[]" class="num" placeholder="联系方式" value="{$contract_way['contract_name']}" />
			    	<input type="text" name="contract_value[]" class="text contract-value" placeholder="联系号码" value="{$contract_way['contract_value']}" />
			    	<a class="text-set text-del"></a></li>
		    	{/if}
			    {/foreach}
				<li class="m2o-cont"><input type="text" name="contract_name[]" placeholder="联系方式" class="num" value="" />
					<input type="text" name="contract_value[]" class="text contract-value" placeholder="联系号码" value="" />
					<a class="text-set text-add"></a></li>
				</ul>
				<div class="m2o-btn">
					<input type="submit" name="sub" value="保存" class="save-button"/>
					<input type="hidden" name="id" value="{$maga_info['id']}" />
				</div>
				<span class="result-tip"></span>
				<img src="{$RESOURCE_URL}loading2.gif" class="loading">
			</form>
		</aside>
		<section class="m2o-m m2o-flex-one">
			<ul class="magazine-list clear">
				{foreach $list as $k => $v}
					{template:unit/issuelist}
				{/foreach}
				<li class="magazine-add">
		      		<div class="mag-img pop-add" data-type="issue">新增期刊</div>
		      		<input type="hidden" name="volume" value="{$maga_info['volume']}">
		      		<input type="hidden" name="current_nper" value="{$maga_info['current_nper']}">
		      	</li>
			</ul>
			<div class="record-bottom m2o-flex clear">
			  	 <div class="record-operate">
			  	    <input type="checkbox" name="checkall" class="checkAll" title="全选" />
			  	    <a name="state" data-method="audit" class="batch-audit">审核</a>
			  	    <a name="back" data-method="back" class="batch-back">打回</a>
	  	    		<a name="delete" data-method="delete" class="batch-delete">删除</a>
			  	 </div>
			  	 <div class="m2o-flex-one">
			  	 {$pagelink}
			  	 </div>
			 </div>
		</section>
	 </div>
	 <div class="pop-add-mag pop-hide" id="add-issue">
		<form class="common-list-form" action="./run.php?mid={$_INPUT['mid']}&a=create&ajax=1" method="post" >
		 {template:unit/add-issue}
		 </form>
	  </div>
</div>
<script type="text/x-jquery-tmpl" id="leftcontadd-tpl">
	 <li class="m2o-cont"><input type="text" name="contract_name[]" placeholder="联系方式" class="num" value="" />
	<input type="text" name="contract_value[]" class="text contract-value" placeholder="联系号码" value="" />
	<a class="text-set text-add"></a></li>
</script>
<script type="text/x-jquery-tmpl" id="issueadd-tpl">
	<li class="magazine-each" data-id="${id}" data-magid="${magazine_id}" >
		<input type="checkbox" name="infolist[]" value="${id}" class="m2o-check" />
		<div class="mag-img">
			<img src="${url}" />
			<p><em class="m2o-state audit" data-method="audit" _id="${id}" _status="0" style="color:#8ea8c8;" >待审核</em></p>
			<a class="newest-href" href="./run.php?a=relate_module_show&app_uniq=magazine&mod_uniq=maga_article&mod_a=show&maga_id=${magazine_id}&issue_id=${id}&maga_name=${maga_name}&infrm=1"></a>
		</div>
		<h4>${year}第${issue}期 总${total_issue}期</h4>
		<p><span>${user_name}</span>${create_time}</p>
		<a class="del" data-method="delete" ></a>
	</li>
</script>
{template:foot}