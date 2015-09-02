{template:head}

{if is_array($formdata)}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}

{code}
	$a = $id ? 'update' : 'create';
	$indexImage = $indexpic && $indexpic['filename'] ? hg_bulid_img($indexpic, 260, 176) : '';
	$logo = $logo && $logo['filename'] ? hg_bulid_img($logo, 196, 196) : '';
	$a = $id ? 'update_hospital': 'create';
	$optext = $id ? '更新' : '新增';
	//print_r( $formdata );
{/code}

{css:common/common}
{css:2013/form}
{css:2013/button}
{css:hospital}
{js:2013/ajaxload_new}
{js:hospital/hospital_form}

<form class="m2o-form" action="run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" data-id="{$id}" id="hospital-form">
    <header class="m2o-header">
      <div class="m2o-inner">
        <div class="m2o-title m2o-flex m2o-flex-center">
            <h1 class="m2o-l">{$optext}医院</h1>
            <div class="m2o-m m2o-flex-one">
                <input class="m2o-m-title {if $name}input-hide{/if}" _value="{if $name}{$name}{else}添加医院名称{/if}" name="name" id="title" placeholder="医院名称" value="{$name}" required="required" />
            </div>
            <div class="m2o-btn m2o-r">
                <span class="m2o-close"></span>
                <em class="prevent-do"></em>
            </div>
        </div>
      </div>
    </header>
    <div class="m2o-inner">
     <div class="m2o-main m2o-flex">
        <aside class="m2o-l">
        	<div class="m2o-item">
        		<div class="indexpic{if $indexImage} has-images{/if}">
        			<div class="img-box img-add"><img title="{$name}" src="{$indexImage}" /></div>
                    <input type="file" name="indexpic" style="display:none;" class="images-file"/>
                    <input type="hidden" name="indexpic_id" value="{$indexpic_id}" />
                    <span class="{if $name}indexpic-suoyin-current{else}indexpic-suoyin{/if}"></span>
                 </div>
        	</div>
        	<div class="m2o-item">
        		<label class="title">等级: </label>
        		{code}
                    $grade_item_source = array(
                        'class' 	=> 'down_list',
                        'show' 		=> 'grade_show',
                        'state' 	=> 	0, /*0--正常数据选择列表，1--日期选择*/
                        'is_sub'	=>	1,
                        'width'		=>	227
                    );
                    
                    if($level)
                    {
                    	$grade_default = $level;
                    }
                    else
                    {
                    	$grade_default = -1;
                    }
                    $_configs['hospital_level'][-1] = '选择等级';
                {/code}
                {template:form/search_source,level,$grade_default,$_configs['hospital_level'],$grade_item_source}
        	</div>
        	<div class="m2o-item">
            	<label class="title">医保定点: </label>
            	<div class="common-switch {if $yibao_point}common-switch-on{/if}">
		           <div class="switch-item switch-left" data-number="0"></div>
		           <div class="switch-slide"></div>
		           <div class="switch-item switch-right" data-number="100"></div>
		        </div>
            	<input type="hidden" name="yibao_point" value="{$yibao_point}">
            </div>
            
            <div class="m2o-item">
            	<label class="title">官网: </label>
            	<input type="text" name="website" placeholder="网址" value="{$website}" />
            </div>
            
            <div class="m2o-item hospital-id">
            	<label class="title">医院id: </label>
            	<input type="text" name="hospital_id" placeholder="医院id" value="{$hospital_id}" />
            </div>
            
            <div class="m2o-item tel-item">
            	<label class="title">电话: </label>
            	<ul class="item tel-li" attr="tel">
            		{foreach $telephone as $v}
        			<li>
        				<input type="text" class="name" name="tel_name[]" placeholder="联系方式" value="{$v['telname']}" />
        				<input type="tel" class="tel" name="tel[]" placeholder="联系号码" value="{$v['tel']}" />
        				<span class="set del">删除</span>
        			</li>
        			{/foreach}
            		<li>
            			<input type="text" class="name" name="tel_name[]" placeholder="联系方式" />
            			<input type="tel" class="tel" name="tel[]"  placeholder="联系号码" />
            			<span class="set add">增加</span>
        			</li>
            	</ul>
            </div>
            
            <div class="m2o-item">
            	<label class="title">交通: </label>
            	<textarea name="traffic" cols="30" rows="5" placeholder="交通">{$traffic}</textarea>
            </div>
        </aside>
         <section class="m2o-m m2o-flex-one">
            <div class="basic-info">
            	<div class="m2o-item logo-item">
	        		<label class="title">logo: </label>
	        		<ul class="item pic-list">
	        			<li class="img-box">
	        				<p class="img-add{if $logo} has-images{/if}">
	        					添加LOGO
	        					<img src="{$logo}" />
	        				</p>
	        				<input type="file" name="logo" style="display:none;" class="images-file" />
	        			</li>
	        		</ul>
	        	</div>
	            <div class="m2o-item brief-item">
	        		<label class="title">简介: </label>
	        		<textarea name="content" cols="120" rows="5" placeholder="医院简介">{$content}</textarea>
	        	</div>
	        	<div class="m2o-item brief-item">
	        		<label class="title">预约规则: </label>
	        		<textarea name="yuyue_rule" cols="120" rows="5" placeholder="预约规则">{$yuyue_rule}</textarea>
	        	</div>
	        	<div class="m2o-item">
	        		<label class="title">特色科室: </label>
	        		<input type="text" name="special_depart" placeholder="特色科室" value="{$special_depart}"/>
	        	</div>
	        	<div class="m2o-item">
	        		<label class="title">重点学科: </label>
	        		<input type="text" name="important_depart" placeholder="重点学科" value="{$important_depart}" />
	        	</div>
	        	
	        	<div class="m2o-item envir-item">
	        		<label class="title">环境: </label>
	        		<ul class="item pic-list" attr="envir">
	        			{if $pic_info}
	        				<input type="hidden" name="del_img" class="del_img" value="" />
							{foreach $pic_info as $mk=>$mv}
								{code}
									$url = $mv['host'].$mv['dir'].'120x120/'.$mv['filepath'].$mv['filename'];
									$ori_url = $mv['host'].$mv['dir'].$mv['filepath'].$mv['filename'];
								{/code}								
								<li class="pic clear" data-id="{$mv['id']}">
			        				<div class="img-box img-add{if $url} has-images{/if}">
			        					<img src="{$url}">
			        				</div>
			        				<input type="file" name="photos[]" style="display:none;" class="images-file" value="{$url}"/>
			        				<textarea placeholder="图片描述" name="des[]">{$mv['brief']}</textarea>
			        				<input type="hidden" name="material_id[]" value="{$mv['id']}" />
			        				<span class="set del">增加</span>
			        			</li>
							{/foreach}
						{/if}
						<li class="pic clear">
	        				<div class="img-box img-add">
	        					添加图片
	        					<img >
	        				</div>
	        				<input type="file" name="photos[]" style="display:none;" class="images-file" value=""/>
	        				<textarea placeholder="图片描述" name="des[]"></textarea>
	        				<input type="hidden" name="material_id[]" value="" />
	        				<span class="set add">增加</span>
	        			</li>
	        		</ul>
	        	</div>
	        	
	        	<div class="m2o-item address-item">
	        		<label class="title">地址: </label>
	        		<div class="item">
						<div class="address-select">
							{code}
								$info = array($province_id, $city_id, $area_id);
							{/code}
							{template:form/address_search, '', '', $info, ''}
		        		</div>
		        		<input id="detailed_address" type="text" name="address" value="{$address}" placeholder="详细地址" />
	        		</div>
	        	</div>
	        	
	            <div class="m2o-item tv-select">
	        		<label class="title">地图: </label>
	        		<div class="item">
						{code}
							$hg_bmap = array(
								'height' => 362,
								'width'  => 666,
								'longitude' => $baidu_longitude ? $baidu_longitude : 0, 
								'latitude'  => $baidu_latitude ? $baidu_latitude : 0,
								'zoomsize'  => 13,
								'areaname'  => $_configs['areaname'] ? $_configs['areaname'] : '南京',
								'is_drag'   => 1,
							);
						{/code}
						{template:form/baidu_map,baidu_longitude,baidu_latitude,$hg_bmap}
	        		</div>
	        	</div>
	        	
	        	<div class="m2o-item btn-item">
	        		<label class="title">&nbsp;</label>
	        		<input type="hidden" name="a" value="{$a}" />
	        		<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
					<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
					<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	        		<input type="button" value="{$optext}" class="save-button" />
	        	</div>
            </div> 
         </section>
      </div>
     </div>
</form>

<script type="text/x-jquery-tmpl" id="tel-tpl">
	<li>
		<input type="text" class="name" name="tel_name[]" placeholder="联系方式" />
		<input type="tel" class="tel" name="tel[]" placeholder="联系号码" />
		<span class="set add">增加</span>
	</li>
</script>

<script type="text/x-jquery-tmpl" id="envir-tpl">
	<li class="pic clear">
		<div class="img-box img-add">
			添加图片
			<img >
		</div>
		<input type="file" name="photos[]" style="display:none;" class="images-file" value=""/>
		<textarea placeholder="图片描述" name="des[]"></textarea>
		<input type="hidden" name="material_id[]" value="" />
		<span class="set add">增加</span>
	</li>
</script>
<script type="text/javascript">
	$('#hospital-form').form({
		teltpl : $('#tel-tpl').html(),
		envirtpl : $('#envir-tpl').html()
	});
</script>