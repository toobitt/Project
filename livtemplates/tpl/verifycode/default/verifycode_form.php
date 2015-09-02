<?php ?>
{template:head}
{css:2013/form}
{css:2013/button}
{css:hg_sort_box}
{css:verify_code_form}
{css:colorpicker}
{js:hg_preview}
{js:hg_sort_box}
{js:ajax_upload}
{js:common/common_form}
{js:2013/ajaxload_new}
{js:jqueryfn/colorpicker.min}
{js:2013/hg_colorpicker}
{js:verify_code/verify_code_form}
{code}
if ( is_array($formdata ) )
{  
	foreach ( $formdata as $k => $v ) 
	{
		$$k = $v;
	}
}		
if($id)
{
	$optext="更新";
	$ac="update";
}
else
{
	$optext="添加";
	$ac="create";
}
$currentSort[$sort_id] = ($sort_id ? $sort_name : '选择分类');
$markswf_url = RESOURCE_URL.'swf/';
//print_r($formdata);
{/code}
<script type="text/javascript">
$.globalData = {code}echo $formdata ? json_encode($formdata) : '{}';{/code};
</script>
<body>
<form class="m2o-form" name="editform" action="run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" id="verifycode_form" data-id="{$id}">
   {template:unit/bg_picture}
    <header class="m2o-header">
    <div class="m2o-inner">
        <div class="m2o-title m2o-flex m2o-flex-center">
            <h1 class="m2o-l">{$optext}验证码</h1>
            <div class="m2o-m m2o-flex-one">
                 <input placeholder="输入验证码名称" name="name" class="m2o-m-title need-word-count" title="{$name}" required value="{$name}" />
                 <input type="hidden" name="old_name" value="{$name}" />
            </div>
            <div class="m2o-btn m2o-r">
                <input type="submit" value="保存信息" class="m2o-save" name="sub" id="sub" data-target="run.php?mid={$_INPUT['mid']}&a={$ac}" data-method="{$ac}"/>
                <span class="m2o-close option-iframe-back"></span>
                <input type="hidden" name="a" value="{$ac}" />
                <input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
                <input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
            </div>
        </div>
      </div>  
    </header>
    <div class="m2o-inner">
    <div class="m2o-main m2o-flex">
        <aside class="m2o-l m2o-aside">
        	<div class="m2o-item" style="position:relative">
        		<img src="{$RESOURCE_URL}loading2.gif" id="top-loading">
        		<span class="item-display">显示效果</span>
        		<span class="preview" title="点击预览" data-publish="preview">预览</span>
        		<!--<input type="submit" class="preview" value="预览" data-target="run.php?mid={$_INPUT['mid']}&a=preview" data-method="preview">  -->
        		<div class="item-show">
        			<img {if $formdata['id']} src="run.php?a=get_verify_code&type={$id}"{/if} />
        		</div>
        	</div>
        	{foreach $_configs['verify_type'] as $k => $v}
        	<div class="m2o-item type">
				<input type="radio" name="type_id" {if $formdata['type_id']==$k || $k ==1}checked{/if} value="{$k}"/><span>{$v}</span>
				<!--{if $k==2 || $k==3 }
				<div class="m2o-right" style="{if $formdata['type_id']==$k}display:block{/if}">
					<span>是否区分大小写:  </span>
					<input type="radio" name="is_dipartite" style="margin-left: 10px;" {if $formdata['is_dipartite']==1}checked{/if} value="1" /><span>是</span>
					<input type="radio" name="is_dipartite" {if $formdata['is_dipartite']==0 || !$formdata['is_dipartite']}checked{/if} value="0" /><span>否</span>
				</div>
				{/if}  -->
			</div>
        	{/foreach}
        </aside>
        <section class="m2o-m m2o-flex-one">
        	<ul class="code-info"> 
        		<li class="code-list m2o-flex">
        			<div class="list-title m10">
        				<span class="title">字体类型:</span>
        			</div>
        			<div class="select-box code-type">
        			 {code}
	                    $type_source = array(
	                        'class' 	=> 'down_list',
	                        'show' 		=> 'type_show',
	                        'state' 	=> 	0, /*0--正常数据选择列表，1--日期选择*/
	                        'is_sub'	=>	1,
	                        'width'     => 130,
	                    );
	                    
	                    if($formdata['fontface_id'])
	                    {
	                    	$type_default = $formdata['fontface_id'];
	                    }
	                    else
	                    {
	                     	$type_default = -1;
	                    }
	                   
	                    $type_sort[-1] = '-请选择字体-';
	                    foreach($font as $k =>$v)
	                    {
	                        $type_sort[$v['id']] = $v['name'].'.'.$v['houzhui'];
	                    }
	                {/code}
	                {template:form/search_source,fontface_id,$type_default,$type_sort,$type_source}
	                </div>
	                <input type="hidden" name="character" value="{$formdata['font_name']}"/>
        		</li>
        		<li class="code-list m2o-flex" style="position:relative;">
        			<div class="count-prevent operate－mode" style="{if $formdata['type_id']==5}display:none;{/if}"></div>
        			<div class="list-title m10">
        				<span class="title">运算方式:</span>
        			</div>
        			<div class="select-box">
        			 {code}
	                    $count_type_source = array(
	                        'class' 	=> 'down_list i',
	                        'show' 		=> 'count_show',
	                        'state' 	=> 	0, /*0--正常数据选择列表，1--日期选择*/
	                        'is_sub'	=>	1,
	                    );
	                    
	                    if($formdata['operation'])
	                    {
	                    	$count_default = $formdata['operation'];
	                    }
	                    else
	                    {
	                     	$count_default = 5;
	                    }
	                    
	                     foreach($_configs['operation'] as $k =>$v)
	                    {
	                        $count_sort[$k] = $v;
	                    }
	                {/code}
	                {template:form/search_source,operation,$count_default,$count_sort,$count_type_source}
	                </div>
        		</li>
        		<li class="code-list m2o-flex" style="position:relative;">
        			<div class="count-prevent count-num" style="{if $formdata['type_id']==5}display:block;{/if}""></div>
        			<div class="list-title m10">
        				<span class="title">字符数:</span>
        			</div>
        			<div class="select-box">
        			{code}
	                    $font_length_source = array(
	                        'class' 	=> 'down_list i',
	                        'show' 		=> 'length_show',
	                        'state' 	=> 	0, /*0--正常数据选择列表，1--日期选择*/
	                        'is_sub'	=>	1,
	                    );
	                    
	                    if($formdata['fontsize'])
	                    {
	                    	$length_default = $formdata['length'];
	                    }
	                    else
	                    {
	                     	$length_default = 4;
	                    }
	                  	for($i=1;$i<11;$i++){
	                  		$length_sort[$i] = $i;
	                  	}
	                {/code}
	                {template:form/search_source,length,$length_default,$length_sort,$font_length_source}
	                </div>
        			
        		</li>
        		<li class="code-list m2o-flex" style="position:relative;">
        			<div class="count-prevent dipartite-cover" style="{if $formdata['type_id']==2 || $formdata['type_id']==3}display:none;{/if}"></div>
        			<div class="list-title m10">
        				<span class="title">区分大小写:</span>
        			</div>
        			<div class="default">
        			 	<input type="radio" name="is_dipartite" {if $formdata['is_dipartite']==1}checked{/if} value="1"/>
        			 	<span>是</span>
        			</div>
        			<div class="default">
        				<input type="radio" name="is_dipartite" {if !$formdata['is_dipartite'] || $formdata['is_dipartite']==0}checked{/if} value="0"/>
        				<span>否</span> 
        			</div>
        		</li>
        		<li class="code-list m2o-flex">
        			<div class="list-title m10">
        				<span class="title">字符大小:</span>
        			</div>
        			<div class="default">
        			<input type="radio" name="is_size" {if $formdata['is_size']==0}checked{/if} value="0"/>
        			<span>自定义大小</span>
        			 {code}
	                    $font_size_source = array(
	                        'class' 	=> 'down_list i',
	                        'show' 		=> 'size_show',
	                        'state' 	=> 	0, /*0--正常数据选择列表，1--日期选择*/
	                        'is_sub'	=>	1,
	                    );
	                    
	                    if($formdata['fontsize'] && !is_array($formdata['fontsize']))
	                    {
	                    	$size_default = $formdata['fontsize'];
	                    }
	                    else
	                    {
	                     	$size_default = 9;
	                    }
	                  	for($i=9;$i<31;$i++){
	                  		$size_sort[$i] = $i;
	                  	}
	                {/code}
	                {template:form/search_source,fontsize,$size_default,$size_sort,$font_size_source}
	               </div>
	                <div class="list-info">
        				<input type="radio" name="is_size" value="1" {if $formdata['is_size']==1}checked{/if} style="vertical-align: sub;"/>
        				{code}
        				 	if(is_array($formdata['fontsize'])){
        				 	$min_size = $formdata['fontsize'][0];
        				 	$max_size = $formdata['fontsize'][1];
        				 	}
        				{/code}
        				<span>在</span><input type="text" data-min='9' data-max='30' class="text-input blur" name="font_size[]" value="{$min_size}" data-sign="1"/><span>至</span><input type="text" data-min='9' data-max='30' class="text-input blur" name="font_size[]" value="{$max_size}" data-sign="1"/> <span>之间随机显示每个字符的大小</span> 
        			</div>
        		</li>
        		<li class="code-list m2o-flex">
        			<div class="list-title m10">
        				<span class="title">字符间距:</span>
        			</div>
        				<div class="select-box">
        			 {code}
	                    $font_space_source = array(
	                        'class' 	=> 'down_list i',
	                        'show' 		=> 'space_show',
	                        'state' 	=> 	0, /*0--正常数据选择列表，1--日期选择*/
	                        'is_sub'	=>	1,
	                    );
	                    
	                    if($formdata['font_space'])
	                    {
	                    	$space_default = $formdata['font_space'];
	                    }
	                    else
	                    {
	                     	$space_default = 0;
	                    }
	                  	for($i=0;$i<31;$i++){
	                  		$space_sort[$i] = $i;
	                  	}
	                {/code}
	                {template:form/search_source,font_space,$space_default,$space_sort,$font_space_source}
	                </div>
        		</li>
        		<li class="code-list m2o-flex">
        			<div class="list-title m10">
        				<span class="title">平移量:</span>
        			</div>
        			<input class="input-info blur" type="text" data-min='0' data-max='200' name="translation" value="{$formdata['translation']}"/>
        		</li>
        		<li class="code-list m2o-flex">
        			<div class="list-title m10">
        				<span class="title">字符旋转角度变化范围:</span>
        			</div>
        			<input type="radio" name="is_angle" value="0" style="margin: 7px 0 0 20px;vertical-align: sub;" {if $formdata['is_angle']==0}checked {/if}/><span> 不旋转</span>
        			<div class="list-info" style="margin-left: 20px;">
        				<input type="radio" name="is_angle" value="1" {if $formdata['is_angle']==1}checked {/if} style="vertical-align: sub;"/>
        				{code}
        				 	if(is_array($formdata['angle'])){
        				 		if($formdata['angle'][0]==0 && $formdata['angle'][1]==0 ){
        				 			$min_angle = '';
        				 			$max_angle = '';
        				 		}else{
        				 			$min_angle = $formdata['angle'][0];
        				 			$max_angle = $formdata['angle'][1];
        				 		}
        				 	}
        				{/code}
        				<span>在</span><input type="text" data-min='-45' data-max='45' class="text-input blur" name="angle[]" value="{$min_angle}" data-sign="2"/><span>至</span><input type="text" data-min='-45' data-max='45' class="text-input blur" name="angle[]" value="{$max_angle}" data-sign="2"/> <span>之间随机显示每个字符的旋转角度</span> 
        			</div>
        		</li>
        		<li class="code-list m2o-flex">
        			<div class="list-title m10">
        				<span class="title">字符颜色:</span>
        			</div>
        			<div class="default">
        			 	<input type="radio" name="is_color" {if $formdata['is_color']==0}checked{/if} value="0"/>
        			 	<span>自定义颜色</span>
        			 	<input class="color-picker" data-color="{$formdata['fontcolor']}" type="text" name="fontcolor[]" value="{$formdata['fontcolor']}"/>
        			</div>
        			<div class="list-info">
        				<input type="radio" name="is_color" {if $formdata['is_color']==1}checked{/if} value="1"/>
        				<span>随机显示每个字符的颜色</span> 
        			</div>
        		</li>
        		<li class="code-list m2o-flex">
        			<div class="list-title m10">
        				<span class="title">背景显示方式:</span>
        			</div>
        			<div class="default">
        			 	<input type="radio" name="is_bgcolor" {if $formdata['is_bgcolor']==0}checked{/if} value="0"/>
        			 	<span>自定义背景</span>
        				<input class="color-picker" data-color="{$formdata['bg_color']}" type="text" name="bg_color[]" value="{$formdata['bg_color']}"/>
        			</div>
        			<div class="list-info">
        				<input type="radio" class="use-pic" name="is_bgcolor" {if $formdata['is_bgcolor']==1}checked{/if} value="1"/>
        				<span>使用图片</span> 
        			</div>
        			<div class="img-info" style="position: relative;{if $formdata['is_bgcolor']==1}display:block{/if}" >
        				<p class="upload-file store-upload" title="点击添加背景图片" style="position: absolute;{if $formdata['is_bgcolor']==1}display:none{/if}">背景图片库</p>
        				<img src="{$formdata['src']}" class="store-upload" style="width:120px;height:30px;margin:5px;"/>
        				<input type="hidden" name="picture_id" class="pic-hidden" value="{$formdata['bgpicture_id']}">
        				<input type="hidden" name="bg_pic" class="name-hidden" value="{$formdata['bg_pic']}">
        				<input type="hidden" name="pic_type" class="type-hidden" value="{$formdata['pic_type']}">
        			</div>
        			<div class="upload-img"></div>
        		</li>
        		<li class="code-list m2o-flex" style="position:relative">
        			<div class="count-prevent bg-display" style="{if $formdata['is_bgcolor']==1}display:block;{else if !$formdata['id']}display:none;{/if}"></div>
        			<div class="list-title m10">
        				<span class="title">背景宽高:</span>
        			</div>
        			<div class="default">
        			 	<input type="radio" name="is_wid_hei" {if $formdata['is_wid_hei']==0}checked{/if} value="0"/>
        			 	<span>自定义宽高</span>
        			 	{code}
        			 		if($formdata['width']==0 && $formdata['height']==0 ){
        				 			$width = '';
        				 			$height = '';
        				 		}else{
        				 			$width = $formdata['width'];
        				 			$height = $formdata['height'];
        				 		}
        			 	{/code}
        			<div class="list-set">
        				<span>宽</span>
        				<input  type="text" class="text-input blur" data-min='0' data-max='255' name="width" value="{$width}"/>
        				<span>高</span>
        				<input  type="text" class="text-input blur" data-min='0' data-max='60' name="height" value="{$height}"/>
        			</div>
        			</div>
        			<div class="list-info">
        				<input type="radio" name="is_wid_hei" {if $formdata['is_wid_hei']==1}checked{/if}  value="1"/>
        				<span>根据字符自适应宽高</span> 
        			</div>
        		</li>
        		<li class="code-list m2o-flex">
        			<div class="list-title m10">
        				<span class="title">杂点数量:</span>
        			</div>
        			<input class="input-info blur" type="text" data-min='0' data-max='3000' name="point_num" value="{$formdata['point_num']}"/>
        		</li>
        		<li class="code-list m2o-flex">
        			<div class="list-title m10">
        				<span class="title">干扰线数量:</span>
        			</div>
        			<input class="input-info blur" type="text" data-min='0' data-max='60' name="line_num" value="{$formdata['line_num']}"/>
        		</li>
        	</ul>
        </section>
    </div>
</form>
</body>
