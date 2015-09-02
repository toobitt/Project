		<div class="pop-add-title">
			<p>新增杂志</p>
			<div class="pop-menu">
				<input type="submit" class="pop-save-button" value="保存"/>
				<a class="pop-close-button2"></a>
			</div>
		</div>
		<div class="pop-add-content">
		    <div class="m2o-tent pop-title">
		   	  <label>名称: </label><input type="text" class="tname" id="required_2" name="title" value=""/>
		    </div>
		    <div class="m2o-tent">
		   	  <label>分类: </label>
		   	  {code}
					$item_source = array(
						'class' => 'down_list i',
						'show' => 'item_shows_',
						'width' => 100,/*列表宽度*/		
						'state' => 0, /*0--正常数据选择列表，1--日期选择*/
						'is_sub'=>1,
						'onclick'=>'',
					);
					$default = $group_id ? $group_id : -1;
					$gname = $appendSort[0];
					$gname[-1] = '选择分类';
					
				{/code}
				{template:form/search_source,group_id,$default,$gname,$item_source}
		    </div>
		    <div class="m2o-tent">
		   	  <label>描述: </label>
		   	  <!-- {template:form/textarea,brief,$list['brief']} -->
		   	   <textarea name="brief" cols="50" rows="5" placeholder="请输入杂志描述"></textarea>
		    </div>
		    <div class="m2o-tent">
		   	  <label>周期: </label>{template:form/select,release_cycle,$list['release_cycle'],$_configs['release_cycle'], $css_attr}
		    </div>
		    <div class="m2o-tent pop-secondary">
		   	  <label>主办单位: </label><input type="text" name="sponsor" value="" />
		    </div>
		     <div class="m2o-tent">
		   	  <label>总期数: </label><input type="text" class="mustdata" name="volume" value="" />
		    </div>
		    <div class="m2o-tent pop-secondary">
		   	  <label>当前期数: </label><input type="text" class="mustdata" name="current_nper" value="" />
		    </div>
		    <div class="m2o-tent">
		   	  <label>页数: </label><input type="text" class="mustdata" name="page_num" value="" />
		    </div>
		    <div class="m2o-tent pop-secondary">
		   	  <label>责任编辑: </label><input type="text" name="editor" value="" />
		    </div>
		    <div class="m2o-tent">
		   	  <label>语言: </label><input type="text" name="language" value="" />
		    </div>
		    <div class="m2o-tent pop-secondary">
		   	  <label>国内统一刊号: </label><input type="text" name="cssn" value="" />
		    </div>
		    <div class="m2o-tent">
		   	  <label>价格: </label><input type="text" name="price" value="" />
		    </div>
		    <div class="m2o-tent pop-secondary">
		   	  <label>国际标准刊号: </label><input type="text" name="issn" value="" />
		    </div>
		    <div class="m2o-tent pop-text">
		   	  <label>联系方式: </label><ul class="pop-box artical-sort">
			    <li><input type="text" name="contract_name[]" placeholder="联系方式" class="contract-name" />
			    	<input type="text" name="contract_value[]" placeholder="联系号码" class="contract-value" />
			    	<a class="text-set text-add"></a></li>
		      </ul>
		    </div>
		</div>
<script type="text/x-jquery-tmpl" id="sortadd-tpl">
	 <li><input type="text" name="contract_name[]" placeholder="联系方式" class="contract-name" />
		 <input type="text" name="contract_value[]" placeholder="联系号码" class="contract-value" />
		 <a class="text-set text-add"></a>
	 </li>
</script>