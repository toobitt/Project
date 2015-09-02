{template:head}
{css:2013/list}
{css:2013/iframe}
{css:2013/button}
{css:hospital}
{js:2013/ajaxload_new}
{js:2013/list}
{code}
	$module_name = 'hospital';
	//print_r( $list );
{/code}
<!-- 这一部分会被推进父层框架，成为检索条件和添加、配置按钮 -->
<div style="display:none">
	{template:unit/hospital_search}
	<div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
		<a href="run.php?mid={$_INPUT['mid']}&a=form&infrm=1" target="formwin" class="button_6">新增医院</a>
	</div>
</div>
<div class="wrap clear">
	<div class="hospital-wrap">
	  <ul class="hospital-list clear">
	  	{if $list}
		   {foreach $list as $k => $v}
			<li class="hospital-each" _id="{$v['id']}" data-id="{$v['id']}">
	  	     <div class="hospital-profile">
	  		  	{code}
					$pic = $v['pic'] && $v['pic']['filename']  ? hg_bulid_img($v['pic'], 261, 160) : '';
					$logo = $v['logo'] && $v['logo']['filename'] ? hg_bulid_img($v['logo'], 46, 46) : '';
				{/code}
			 <div class="img{if $pic} hasImg{/if}">
	  			{if $pic}<img src="{$pic}" alt="医院图片" />{/if}
	  		  </div> 		  
	  		  <div class="brief m2o-flex">
	  			<div class="info m2o-flex-one">
	  				<div class="item">
		  				<h4 class="m2o-overflow" title="{$v['name']}">{$v['name']}</h4>
		  				<!-- <a class="detail" href="run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1" target="formwin">编辑</a> -->
	  				</div>
	  				<div class="item item-grade">
	  					<span class="grade">{$v['level']}</span>   
	  					{if $v['yibao_point']}<span class="point">{$v['yibao_point']}</span>{/if}
	  				</div>
	  				<div class="item"><span class="m2o-audit" _status="{$v['status']}" style="color:{$_configs['status_color'][$v['status']]};" >{$v['status_name']}</span></div>
	  				<!-- <div class="item item-prof m2o-overflow"><span class="prof">{$v['brief']}</span></div> -->
	  			</div>
	  			<div class="logo">
	  				{if $logo}<img src="{$logo}" alt="医院logo">{/if}
	  			</div>
	  		  </div>
	  		  <!-- <a class="m2o-delete">删除</a> -->
	  		  <a href="run.php?mid={$_INPUT['mid']}&a=relate_module_show&app_uniq=hospital&mod_uniq=department&hospital_id={$v['id']}" target="formwin" class="linking" need-back>&nbsp;</a>
	  	     </div>
	     	</li>
		   {/foreach}
		{else}
		  <p style="color:#da2d2d;text-align:center;font-size:20px;line-height:20px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
		{/if}
	  </ul>
	  <div class="bottom m2o-flex m2o-flex-center">
	  	 <div class="operate">
	  	 	<!-- <input type="checkbox" name="checkall" class="checkAll" id="checkAll" />
	  	    <a class="batch-handle">审核</a>
			<a class="batch-handle">打回</a>
			<a class="batch-handle">删除</a> -->
	  	 </div>
	  	 <div class="m2o-flex-one">
	  	 {$pagelink}
	  	 </div>
	  </div>
	</div>
</div>
{template:foot}
<script type="text/javascript">
	$('.hospital-wrap').glist({
		each : '.hospital-each',
		selected : '.hospital-each.selected'
	});
	$.m2o.geach.prototype._click = function(){
		var target = this.element;
		target.toggleClass('selected');
		if( target.hasClass('selected') ){
			target.siblings().removeClass('selected');
		}
	}
	$('.hospital-each').geach();
</script>