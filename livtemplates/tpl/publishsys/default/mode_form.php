{template:head}
{js:publishsys/mode_out_variable}
{js:jqueryfn/jquery.tmpl.min}
{js:common/ajax_upload}
{js:publishsys/mode_form}
{code}
$list = $formdata[0];
$sorts = $sorts[0];
$css_attr['style'] = 'style="width:100px"';
$out_arment  = $list['out_arment'];
{/code}
{css:column_form}
{css:common/common}
{css:2013/form}
{css:common}
{css:mode_form}
{js:2013/form}
<style>
.column-edit-button{display:none;}
</style>
{code}
    $aceDir = './res/ace/';
{/code}
<script src="{$aceDir}ace.js"></script>
{js:publishsys/parse}
<script>
</script>
<div id="mode-main">
<form name="editform" action="run.php?mid={$_INPUT['mid']}" enctype="multipart/form-data" method="post" class="m2o-form" id="mode-form">
	<header class="m2o-header">
	  <div class="m2o-inner">
        <div class="m2o-title m2o-flex m2o-flex-center">
            <h1 class="m2o-l">{if $_INPUT['id']}编辑样式{else}新增样式{/if}</h1>
            <div class="m2o-m m2o-flex-one">
                <input placeholder="添加样式标题" name="title"  class="m2o-m-title" value="{$list['title']}" />
            </div>
            <div class="m2o-btn m2o-r">
            	{if !$list['mode_default']}
                	<input type="submit" value="保存样式" class="m2o-save" name="sub" id="sub" />
                {/if}
                {if $a=='update'}
					<input type="button" name="lcw" class="m2o-savae-as submit" value="另存为"  />
				{/if}
                <span class="m2o-close option-iframe-back"></span>
            </div>
        </div>
       </div>
    </header>
   <div class="m2o-inner">
    <div class="m2o-main">
        <div class="choice-area m2o-flex" style="margin-bottom:0;border-bottom:1px dashed #ccc;">
        <!-- 
        	<div class="m2o-item">
				<div class="form-cioption-indexpic form-cioption-item">
                    <div class="pic-view">
                    {if $list['indexpic']}
                        <div class="indexpic">
                            {code}
						       	 $picinfo = unserialize($list['indexpic']);
						       	 $url = $picinfo['host'].$picinfo['dir'].'160x120/'.$picinfo['filepath'].$picinfo['filename'];
	       	                {/code}
                            <img class="{if !$picinfo}hide{/if}" src="{$url}" style="max-width:160px;max-height:120px;" title="索引图" />
                        </div>
                        <span class="{if $picinfo}indexpic-suoyin-current{else}indexpic-suoyin{/if}"></span>
                    {/if}
                    </div> 
                    <input type="file" name="Filepic" id="Filepic"  value="submit" style="display:none;">
                    <input type="hidden" name="indexpic" id= "indexpic" value='{$list['indexpic']}' />
                </div>
        	</div>
        	-->
        	
        	{code}
				$sorts_arr = array(
					'class' => 'transcoding down_list',
					'show' => 'sorts_show',
					'width' => 120,	
					'state' => 0,
				);
				$sorts[-1] = '全部类别';
				$sorts_default = $list['sort_id'] ? $list['sort_id'] : -1;	
				
				$attr_mode = array(
					'class' => 'transcoding down_list',
					'show'  => 'select_pro',
					'width' => 180,/*列表宽度*/
					'state' => 0,/*0--正常数据选择列表，1--日期选择*/
					'onclick' => 'get_mode_variable();'
				);
				$modes[0]['-1'] = "-请选择-";
				
				$m_types = array(
					'class' => 'transcoding down_list',
					'show'  => 'select_type',
					'width' => 180,/*列表宽度*/
					'state' => 0,/*0--正常数据选择列表，1--日期选择*/
				);
				$mode_default = $list['mode_type'] ? $list['mode_type'] : 0;	
						
				$da_types = array(
					'class' => 'transcoding down_list',
					'show'  => 'select_data',
					'width' => 180,/*列表宽度*/
					'state' => 0,/*0--正常数据选择列表，1--日期选择*/
				);
				$data_cates[0]['-1'] = "-请选择-";
				$data_cate = $list['data_cate'] ? $list['data_cate'] : -1;	
					
			{/code}
        	<div class="m2o-item">
        		<p class="title">分类：</p>
				{template:form/search_source,sort_id,$sorts_default,$sorts,$sorts_arr}
        	</div>
        	<div class="m2o-item">
        		<p class="title">类型：</p>
				{template:form/search_source,mode_type,$mode_default,$mode_types[0],$m_types}
        	</div>
        	<div class="m2o-item">
				<p class="title">分页：</p>
				<div class="common-switch {if $list['need_pages']}common-switch-on{/if}">
					<div class="switch-item switch-left" data-number="0"></div>
					<div class="switch-slide"></div>
					<div class="switch-item switch-right" data-number="100"></div>
				</div>
				<input type="radio" name="need_pages" value="1" {if $list['need_pages']}checked{/if} style="display:none;" class="pageopen"/>
				<input type="radio" name="need_pages" value="0" {if !$list['need_pages']}checked{/if} style="display:none;" class="pageoff" />
   			</div>
   			<!--  
        	<div class="m2o-item">
        		<textarea name="default_param" cols="60" rows="5">{code}var_export($list['default_param']);{/code}</textarea>
        	</div>
        	-->
        	<div class="m2o-item m2o-flex-one">
        		<p class="title">样式描述：</p>
				<textarea name="description" cols="60" rows="5" placeholder="样式描述">{$list['description']}</textarea>
        	</div>
        	<div class="m2o-item">
        		<p class="title">默认数据：</p>
				{template:form/search_source,data_cate,$data_cate,$data_cates[0],$da_types}
        	</div>
        	<div class="m2o-item m2o-flex-one">
        		<p class="title">条数</p>
				<textarea name="data_cate_num" cols="1" rows="1">{$list['data_cate_num']}</textarea>
        	</div>
        </div>
        <div class="choice-area m2o-flex">
        	<div class="m2o-item">
        		<p class="title" style="width:90px;">默认样式图：</p>
				<div class="mode-css">
					<input type="file" name="indexpic" class="css-preview-file" style="display:none;" />
					<div class="css-preview">
						{code}
				       	$indexpic = unserialize($list['indexpic']);
				       	$indexurl = $indexpic['host'].$indexpic['dir'].$indexpic['filepath'].$indexpic['filename'];
	                	{/code}
	                	{if $indexpic}
	               			<img  src="{$indexurl}"  title="默认样式图" />
	               		{/if}
					</div>
					
				</div>
        	</div>
        	<div class="m2o-item">
        		<p class="title">效果图：</p>
				<div class="mode-css">
					<input type="file" name="effectpic" class="css-preview-file" style="display:none;" />
					<div class="css-preview">
						{code}
				       	$effectpic = unserialize($list['effectpic']);
				       	$effecturl = $effectpic['host'].$effectpic['dir'].$effectpic['filepath'].$effectpic['filename'];
	                	{/code}
	                	{if $effectpic}
	               			<img  src="{$effecturl}"  title="默认样式图" />
	               		{/if}
					</div>
				</div>
        	</div>
        </div>
        <section class="m2o-m m2o-flex-one">
        <div class="mode-all">
        <!-- html -->
         <div class="m2o-o m2o-flex mode-area clear">
        		<div class="form-style-code">
        			<h2 class="form-tips">
        				<em class="section-name">Html</em>
        				<span class="section-tips"></span>
        			</h2>
					<div class="form-text">
						<textarea id='mode_html' name="mode_html" cols="60" rows="5" class="parse-textarea" data-flag="html"  style="display:none;">{$list['content']}</textarea>
						<div class="editor-box" id="html-editor" data-type="html">{$list['content']}</div>
					</div>
				</div>
        		<div class='form-para-list m2o-flex-one clear'> 
        			<div class="form-list m2o-flex m2o-flex-center">
						 <div class="form-item form-mark">标识</div>
					     <div class="form-item form-para">参数名</div>								     
						 <div class="form-item form-value">值</div>
						<div class="form-item form-type">类型</div>
						<div class="form-item form-drop">下拉框</div>	
						<div class="form-item form-handle">操作</div>													     
					</div>
					<div class="form-box">
						{if $list['argument']}
						{code}
						//print_r($list['argument']);
						{/code}
						{foreach $list['argument']['sign'] as $k=>$v}
							<div class="form-each m2o-flex m2o-flex-center {if $list['argument']['flag'][$k]}disable-flag{/if}">
								 <div class="form-item form-mark ">
								 	<input type='text' name='sign[]' value='{$list["argument"]["sign"][$k]}'  title='{$list["argument"]["sign"][$k]}' readonly="readonly" class="mark-title">
								 	<input type="hidden" name='flag[]'  value='{$list["argument"]["flag"][$k]}'  class="out_arment_flag" />
								 </div>
								 <div class="form-item form-para">
								 	{code}
								 		$list["argument"]["name"][$k] = $list["argument"]["name"][$k]?$list["argument"]["name"][$k]:$list['argument']['sign_name'][$v]['0'];
								 	{/code}
								 	<input type='text' name='name[]' value='{$list["argument"]["name"][$k]}'  title='{$list["argument"]["name"][$k]}'>
								 	 {if $list['argument']['sign_name'][$v]}
								 	 <span class="arrow"></span>
									 	<ul class="para-list">
									 		{foreach $list['argument']['sign_name'][$v] as $ke=>$va}
									 		<li class="paralist" _id="{$ke}" _biaoshi="{$list["argument"]["sign"][$k]}" _type="">{$va}</li>
									 		{/foreach}
									 	</ul>
									 {/if}
								 </div>								         
								 <div class="form-item form-value"><input type='text' name='default_value[]' value='{$list["argument"]["default_value"][$k]}'  title='{$list["argument"]["default_value"][$k]}' /></div>
								<div class="form-item form-type">
									<select name='type[]' class="mode-select">
										{foreach $_configs['mode_data_type'] as $kk=>$vv}
										<option value="{$kk}" {code}if($list['argument']['type'][$k]==$kk) echo "selected";{/code}>
											{$vv}
										</option>
										{/foreach}
									</select>
								</div>
								<div class="form-item form-drop">
									<div class="drop-select {if $list['argument']['type'][$k] != 'select'}drop-hide{/if}">
									 <div class="drop-slide-btn"></div>
						          	  <ul>
						          	   	{if $list['argument']['other_value'][$k]}
						          	  	{foreach $list['argument']['other_value'][$k] as $ke=>$va}
						          	  	<li class="drop-list-item">
						          	  		<input type="text" class="mode-select-key" name="{$v}_select_key[]" value='{$ke}' style="max-width: 50px;" />=<input type="text" name="{$v}_select_value[]"  value='{$va}' style="max-width: 50px;" class="mode-select-value" />
						          	  	</li>
						          	  	{/foreach}
						          	  	{else}
						          	  	<li class="drop-list-item">
						          	  		<input type="text" class="mode-select-key" name="{$v}_select_key[]"  style="max-width: 50px;" />=<input type="text" name="{$v}_select_value[]" style="max-width: 50px;" class="mode-select-value" />
						          	  	</li>
						          	  	{/if}
								      </ul>
								      <div class="mode-select-add">+</div>
								     </div>
								     <!--  <input type='text' name='other_value[]' value='{$list["argument"]["other_value"][$k]}' style="display:none;"/>-->
								 </div>	
								 <div class="form-item del-param"></div>					         
							</div>
						{/foreach}
						{/if}
					</div>
				</div>
        	 </div>
        	 
        	 <!-- css -->
        	 <div class="mode-css-box">
        	 <div class="mode-css-list">
	        	 {if($list['css'])}
				 {foreach $list['css'] as $key => $value}
	             <div class="m2o-o m2o-flex mode-area mode-css hased-mode-css clear  del-hide" data-id="{$key}">
	        		<div class="form-style-code">
	        			<h2 class="form-tips">
	        				<em class="section-name">css</em>
	        				<a class="css-preview">
	        					 {code}
							       	$picinfo = unserialize($value['css_indexpic']);
							       	$url = $picinfo['host'].$picinfo['dir'].'160x120/'.$picinfo['filepath'].$picinfo['filename'];
	       	                	 {/code}
	       	                	 {if $picinfo}
                           			<img  src="{$url}"  title="索引图" />
                           		 {else}
                           			<img>
                           		 {/if}
	        				</a>
	        				<input type="hidden" name="css{$key}_hidpic" value='{$value['css_indexpic']}' />
	        				<input type="file" name="css{$key}_pic" style="display:none;" class="css-preview-file" />
	        				<span class="code-name">
	        					<input type="text" placeholder="css名"  value="{$value['css_title']}" name='css{$key}_title' class="css-name" style="width:100px;">
	        				</span> 
	        				<span class="section-tips"></span>
	        			</h2>
	        			<div class="set-default-btn"><input type="checkbox" {if $value['default_css']==1} checked="checked"{/if}/><span>默认css</span></div>
	        			<div class="mode-css-buttons">
							<div class="mode-css-del mode-css-button">删除</div>
							<div class="mode-css-copy mode-css-button">复制</div>
						</div>
						<div class="form-text">
							<textarea  name="css{$key}_" cols="60" rows="5" class="parse-textarea css-parse-textarea" data-type="css{$key}_" data-flag="css" style="display:none;">{$value['code']}</textarea>
							<div class="editor-box" data-type="css{$key}_">{$value['code']}</div>
						</div>
					</div>
	        		<div class='form-para-list m2o-flex-one clear'> 
	        			<div class="form-list m2o-flex m2o-flex-center">
							 <div class="form-item form-mark">标识</div>
						     <div class="form-item form-para">参数名</div>							     
							 <div class="form-item form-value">值</div>
							<div class="form-item form-type">类型</div>
							<div class="form-item form-drop">下拉框</div>		
							<div class="form-item form-handle">操作</div>			     
						</div>
						<div class="form-box">
							{foreach $value['css_argument']['css_sign'] as $k=>$v}
								<div class="form-each m2o-flex m2o-flex-center {if $value['css_argument']['css_flag'][$k]}disable-flag{/if}">
									 <div class="form-item form-mark">
									 	<input type='text' name='css{$key}_sign[]' value='{$value["css_argument"]["css_sign"][$k]}' title='{$value["css_argument"]["css_sign"][$k]}' readonly="readonly" class="mark-title">
									 	<input type="hidden" name='css{$key}_flag[]'  value='{$value["css_argument"]["css_flag"][$k]}' class="out_arment_flag" />
									 </div>
									 <div class="form-item form-para">
									 	{code}
								 			$value["css_argument"]["css_name"][$k] = $value["css_argument"]["css_name"][$k]?$value["css_argument"]["css_name"][$k]:$value['css_argument']['css_sign_name'][$v]['0'];
								 	    {/code}
									 	<input type='text' name='css{$key}_name[]' value='{$value["css_argument"]["css_name"][$k]}' title='{$value["css_argument"]["css_name"][$k]}'>
									 	{code}
									 	//print_r($value['css_argument']['css_sign_name'][$v]);
									 	{/code}
									 	{if $value['css_argument']['css_sign_name'][$v]}
										 	<span class="arrow"></span>
											 	<ul class="para-list">
											 	{foreach $value['css_argument']['css_sign_name'][$v] as $ke=>$va}
											 		<li class="paralist" _id="{$ke}" _biaoshi="{$value["css_argument"]["css_sign"][$k]}" _type="css{$key}_">{$va}</li>
											 	{/foreach}
											 	</ul>
										{/if}
									 </div>								         
									 <div class="form-item form-value"><input type='text' name='css{$key}_default_value[]' value='{$value["css_argument"]["css_default_value"][$k]}' title='{$value["css_argument"]["css_default_value"][$k]}'/></div>
									<div class="form-item form-type">
										<select name='css{$key}_type[]' class="mode-select">
											{foreach $_configs['mode_data_type'] as $kk=>$vv}
											<option value="{$kk}" {code}if($value['css_argument']['css_type'][$k]==$kk) echo "selected";{/code}>
												{$vv}
											</option>
											{/foreach}
										</select>
									</div>
									<div class="form-item form-drop">
										<div class="drop-select {if $value['css_argument']['css_type'][$k] != 'select'}drop-hide{/if}">
										  <div class="drop-slide-btn"></div>
							          	  <ul>
							          	    {if $value['css_argument']['css_other_value'][$k]}
							          	    {foreach $value['css_argument']['css_other_value'][$k] as $ke=>$va}
							          	    {code}
							          	   // print_r($ke);
							          	    {/code}
							          	  	<li class="drop-list-item">
							          	  		<input type="text" class="mode-select-key" name="{$v}_css{$key}_select_key[]" value='{$ke}' style="max-width: 50px;" />=<input type="text" name="{$v}_css{$key}_select_value[]" value='{$va}' style="max-width: 50px;" class="mode-select-value" />
							          	  	</li>
							          	  	{/foreach}
							          	  	{else}
							          	  	<li class="drop-list-item">
							          	  		<input type="text" class="mode-select-key" name="{$v}_css{$key}_select_key[]" style="max-width: 50px;" />=<input type="text" name="{$v}_css{$key}_select_value[]"  style="max-width: 50px;" class="mode-select-value" />
							          	  	</li>
							          	  	{/if}
									      </ul>
									      <div class="mode-select-add">+</div>
									     </div>
									     <!-- <input type='text' name='css{$key}_other_value[]' value='{$value["css_argument"]["css_other_value"][$k]}' title='{$value["css_argument"]["css_other_value"][$k]}'/>-->
								   </div>
								   <div class="form-item del-param"></div>
								</div>
							{/foreach}
						</div>
					</div>
	        	 </div>
	        	 <input type="hidden" name="old_css_ids[]" id="old_css_ids" value="{$key}" />
	        	 {/foreach}
	        	 {else}
	        	  <div class="m2o-o m2o-flex mode-area mode-css  add-mode-css clear  del-hide" data-id="1">
	        		<div class="form-style-code">
	        			<h2 class="form-tips">
	        				<em class="section-name">css</em>
	        				<a class="css-preview">
	        					<img>
	        				</a>
	        				<input type="file" name="css1_pic" style="display:none;" class="css-preview-file" />
	        				<span class="code-name">
	        					<input type="text" placeholder="css名" value="" name='css1_title' class="css-name" style="width:100px;">
	        				</span>
	        				<span class="section-tips"></span>
	        			</h2>
	        			<div class="set-default-btn"><input type="checkbox" /><span>设为默认css</span></div>
	        			<div class="mode-css-buttons">
	        				<div class="mode-css-del mode-css-button">删除</div>
	        				<div class="mode-css-copy mode-css-button">复制</div>
	        			</div>
						<div class="form-text">
							<textarea  name="css1_" cols="60" rows="5" class="parse-textarea add-parse-textarea css-parse-textarea" data-flag="css" data-type="css1_" style="display:none;"></textarea>
							<div class="editor-box" data-type="css1_"></div>
						</div>
					</div>
	        		<div class='form-para-list m2o-flex-one clear'> 
	        			<div class="form-list m2o-flex m2o-flex-center">
							 <div class="form-item form-mark">标识</div>
						     <div class="form-item form-para">参数名</div>								     
							 <div class="form-item form-value">值</div>
							 <div class="form-item form-type">类型</div>	
							<div class="form-item form-drop">下拉框</div>	
							<div class="form-item form-handle">操作</div>						     
						</div>
						<div class="form-box">
						</div>
					</div>
	        	 </div>
				 {/if}
			 </div>
        	 </div>
			<div class="m2o-o">
				<div class="mode-css-add">添加css</div>
			</div>
        	 <!-- js -->
         	<div class="m2o-o m2o-flex mode-area clear">
        		<div class="form-style-code">
        			<h2 class="form-tips">
        				<em class="section-name">js</em>
        				<span class="code-name">
        					<input type="text" placeholder="请填写js名"  value="{$list['js']['js_title']}" name='js_title' style="width:100px;">
        				</span>
        				<span class="section-tips"></span>
        			</h2>
					<div class="form-text">
						<textarea id='mode_js' name="js" cols="60" rows="5" class="parse-textarea" data-flag="js" data-type="js_"  style="display:none;">{$list['js']['code']}</textarea>
						<div class="editor-box" id="js-editor" data-type="js">{$list['js']['code']}</div>
					</div>
				</div>
        		<div class='form-para-list m2o-flex-one clear'> 
        			<div class="form-list m2o-flex m2o-flex-center">
						 <div class="form-item form-mark">标识</div>
					     <div class="form-item form-para">参数名</div>								     
						 <div class="form-item form-value">值</div>
						 <div class="form-item form-type">类型</div>
						 <div class="form-item form-drop">下拉框</div>
						 <div class="form-item form-handle">操作</div>							     
					</div>
					<div class="form-box">
						{if $list['js']['js_argument']}
						{foreach $list['js']['js_argument']['js_sign'] as $k=>$v}
							<div class="form-each m2o-flex m2o-flex-center {if $list['js']['js_argument']['js_flag'][$k]}disable-flag{/if}">
								 <div class="form-item form-mark ">
								 	<input type='text' name='js_sign[]' value='{$list["js"]["js_argument"]["js_sign"][$k]}' title='{$list["js"]["js_argument"]["js_sign"][$k]}' readonly="readonly" class="mark-title">
								 	<input type="hidden" name="js_flag[]"  value='{$list["js"]["js_argument"]["js_flag"][$k]}' class="out_arment_flag" />
								 </div>
								 <div class="form-item form-para">
								 	{code}
								 		$list["js"]["js_argument"]["js_name"][$k] = $list["js"]["js_argument"]["js_name"][$k]?$list["js"]["js_argument"]["js_name"][$k]:$list['js']['js_argument']['js_sign_name'][$v]['0'];
								 	{/code}
								 	<input type='text' name='js_name[]' value='{$list["js"]["js_argument"]["js_name"][$k]}' title='{$list["js"]["js_argument"]["js_name"][$k]}' >
								 	{if $list['js']['js_argument']['js_sign_name'][$v]}
								 	 <span class="arrow"></span>
									 	<ul class="para-list">
									 		{foreach $list['js']['js_argument']['js_sign_name'][$v] as $ke=>$va}
									 		<li class="paralist" _id="{$ke}" _biaoshi="{$list["js"]["js_argument"]["js_sign"][$k]}" _type="js_" >{$va}</li>
									 		{/foreach}
									 	</ul>
									 {/if}
								 </div>								         
								 <div class="form-item form-value"><input type='text' name='js_default_value[]' value='{$list["js"]["js_argument"]["js_default_value"][$k]}' title='{$list["js"]["js_argument"]["js_default_value"][$k]}'/></div>
								 <div class="form-item form-type">
									<select name='js_type[]' class="mode-select">
										{foreach $_configs['mode_data_type'] as $kk=>$vv}
										<option value="{$kk}" {code}if($list["js"]['js_argument']['js_type'][$k]==$kk) echo "selected";{/code}>
											{$vv}
										</option>
										{/foreach}
									</select>
								 </div>
								 <div class="form-item form-drop">
										<div class="drop-select {if $list['js']['js_argument']['js_type'][$k] != 'select'}drop-hide{/if}">
										<div class="drop-slide-btn"></div>
							          	  <ul>
							          	    {if $list['js']['js_argument']['js_other_value'][$k]}
							          	  	{foreach $list['js']['js_argument']['js_other_value'][$k] as $ke=>$va}
							          	  	<li class="drop-list-item">
							          	  		<input type="text" class="mode-select-key" name="{$v}_js_select_key[]" value='{$ke}' style="max-width: 50px;" />=<input type="text" name="{$v}_js_select_value[]" value='{$va}' style="max-width: 50px;" class="mode-select-value" />
							          	  	</li>
							          	  	{/foreach}
							          	  	{else}
							          	  	<li class="drop-list-item">
							          	  		<input type="text" class="mode-select-key" name="{$v}_js_select_key[]" style="max-width: 50px;" />=<input type="text" name="{$v}_js_select_value[]"  style="max-width: 50px;" class="mode-select-value" />
							          	  	</li>
							          	  	{/if}
									      </ul>
									      <div class="mode-select-add">+</div>
									     </div>
									     <!-- <input type='text' name='js_other_value[]' value='{$list["js"]["js_argument"]["js_other_value"][$k]}' title='{$list["js"]["js_argument"]["js_other_value"][$k]}' />-->
								   </div>	
								   <div class="form-item del-param"></div>							         
							</div>
						{/foreach}
						{/if}
					</div>
				</div>
        	 </div>
        	 <div class="m2o-f m2o-output">
			  <div class="form_ul_div out-form-div form-output-para clear">
			    <div class="form-title">
			     <span> 输出参数</span>
			    </div>
			  <div class="form-list m2o-flex m2o-flex-center">
			     <div class="form-item m2o-flex-one form-para">参数名</div>
			  </div>
			  {code}
			 	//print_r($out_arment);
			  {/code}
				<div class="out_arment">
					{foreach $out_arment['name'] as $k=>$v}
					  <div class="form-each m2o-flex m2o-flex-center items {if $out_arment['flag'][$k]}disable-flag{/if}">
				          <div class="form-item m2o-flex-one form-para form-border">
				          		<input type='text' name='out_arname[]' value='{$v}'  class='out_arment-title'  readonly="readonly" />
				          		<input type="hidden" name="out_arment_flag[]"  value="{$out_arment['flag'][$k]}" class="out_arment_flag" />
				          </div>
				       	 <div class="del-param"></div>
				        </div>
					{/foreach}
				</div>
			 </div>
			 </div>
        	<!--<div class="m2o-f">{template:unit/mode_out_variable}</div>-->
        </div>
        </section>
    </div>
   </div>
    <input type="hidden" name="css_arrs" id="css_arrs" />
    <input type="hidden" name="css_ids" id="css_ids" />
    <input type="hidden" name="a" id= "aid" value="{$a}" />
    <input type="hidden" name="fid" value="{$list['fid']}" />
	<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
	<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
	<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	<!--<input type="hidden" name="site_id" value="{$_INPUT['site_id']}" />-->
	<input type="hidden" name="default_css"  />
</form>
</div>
{template:foot}
<script>
$(function(){
	$.configsType = {code} echo $_configs['mode_data_type'] ?  json_encode( $_configs['mode_data_type'] ) : '{}' {/code};
})
</script>
<script type="text/x-jquery-tmpl" id="param-tpl">
{{if biaoshi}}
 	<div class="form-each m2o-flex m2o-flex-center add-form-each">
		    <div class="form-item form-mark">
		    	<input type='text' name='${type}sign[]' value='${biaoshi}' title="${biaoshi}" readonly="readonly" class="mark-title" />
		    	<input type="hidden" name='${type}flag[]'  value="0"  class="out_arment_flag" />
		    </div>
			<div class="form-item form-para">
				<input type='text' name='${type}name[]' value="${default_mark}" _markType = "${defaultconfigType}" class="param-input" />
				{{if hasparam}}
				<span class="arrow"></span>
				{{/if}}
				<ul class="para-list">
					{{tmpl(marks_info) "#marks-tpl"}}
			 	</ul>
			</div>								         
			<div class="form-item form-value"><input type='text' name='${type}default_value[]' /></div>
			<div class="form-item form-type">
			   <select name='${type}type[]' class="mode-select">
			   		{{each configType}}
					<option value="{{= $index}}">
						{{= $value}}
					</option>
					{{/each}}
			   </select>
		     </div>
			<div class="form-item form-drop">			
			  <div class="drop-select drop-hide">
				<div class="drop-slide-btn"></div>
				 <ul>
					{{each default_drop}}
					<li class="drop-list-item">
						<input type="text" class="mode-select-key" name="${biaoshi}_${type}select_key[]" style="max-width: 50px;"  value="{{= $index}}" />=<input type="text" name="${biaoshi}_${type}select_value[]" value="{{= $value}}"  style="max-width: 50px;" class="mode-select-value" />
					</li>
					{{/each}}
					<li class="drop-list-item">
						<input type="text" class="mode-select-key" name="${biaoshi}_${type}select_key[]" style="max-width: 50px;"  />=<input type="text" name="${biaoshi}_${type}select_value[]"  style="max-width: 50px;" class="mode-select-value" />
					</li>
				 </ul>
				 <div class="mode-select-add">+</div>
			  </div>
			</div>
			<div class="form-item del-param"></div>
     </div>	
{{/if}}			
</script>

<script type="text/x-jquery-tmpl" id="marks-tpl">
<li class="paralist" _id="${id}" _biaoshi="${biaoshi}" _type="${type}">${mark_name}</li>
</script>

<script type="text/x-jquery-tmpl" id="css-tpl">
<div class="m2o-o m2o-flex mode-area mode-css add-mode-css clear" data-id="${index}">
        		<div class="form-style-code">
        			<h2 class="form-tips">
						<em class="section-name">css</em>
						<a class="css-preview">
	        					<img>
	        			</a>
	        			<input type="file" name="css${index}_pic" style="display:none;" class="css-preview-file" />
						<span class="code-name">
        					<input type="text"  value="" name='css${index}_title' class="css-name">
						</span>
						<span class="section-tips"></span>
        				<input type="hidden" name='css${index}_flag'  value="0"  class="out_arment_flag" />
        			</h2>
					<div class="set-default-btn"><input type="checkbox" /><span>默认css</span></div>
					<div class="mode-css-buttons">
							<div class="mode-css-del mode-css-button">删除</div>
							<div class="mode-css-copy mode-css-button">复制</div>
					</div>
					<div class="form-text">
						<textarea  name="css${index}_" cols="60" rows="5" class="parse-textarea css-parse-textarea add-parse-textarea" data-type="css${index}_" data-flag="css" style="display:none;"></textarea>
						<div class="editor-box"  data-type="css${index}_"></div>
					</div>
				</div>
        		<div class='form-para-list m2o-flex-one clear'> 
        			<div class="form-list m2o-flex m2o-flex-center">
						 <div class="form-item form-mark">标识</div>
					     <div class="form-item form-para">参数名</div>								     
						 <div class="form-item form-value">值</div>
						<div class="form-item form-type">类型</div>
						<div class="form-item form-drop">下拉框</div>						     
					</div>
					<div class="form-box">
					</div>
				</div>
</div>
</script>
<script type="text/x-jquery-tmpl" id="para-html-tpl">
{{if list}}
{{each list}}
<div class="form-each m2o-flex m2o-flex-center items">
	 <div class="form-item m2o-flex-one form-para form-border">
		<input type='text' name='out_arname[]' value='{{= $value}}'  class='out_arment-title' readonly="readonly" >
		<input type="hidden" name="out_arment_flag[]"  value="0"  class="out_arment_flag" />
	</div>
	 <div class="del-param"></div>
</div>
{{/each}}
{{/if}}
</script>
{code}
//print_r( $list['css']  );
{/code}
<script>
  $.htmlParaminfo = {code} echo $list['argument']['sign_info']  ?  json_encode( $list['argument']['sign_info'] ) : '{}'   {/code};
  $.cssParaminfo = {code} echo $list['css_sign']  ?  json_encode( $list['css_sign'] ) : '{}'   {/code};
  $.jsParaminfo = {code} echo $list['js']['js_argument']['js_sign_info']  ?  json_encode( $list['js']['js_argument']['js_sign_info'] ) : '{}'   {/code};
</script>