{template:head}
{css:2013/list}
{css:2013/iframe}
{css:cinema_list}
{js:ajax_upload}
{js:2013/ajaxload_new}
{js:cinema/cinema_list}
{code}
//print_r( $list );
{/code}
<style>
.import-excel{position:relative;}
.import-excel.loading{background-image:url({$RESOURCE_URL}loading2.gif);background-position:center center;background-repeat:no-repeat;background-size:25px;}
.excel{width: 112px;height: 29px;position: absolute;top: 0px;left: 0px;cursor:pointer;opacity:0.00000001}
</style>
<!-- 这一部分会被推进父层框架，成为检索条件和添加、配置按钮 -->
<div style="display:none">
	{template:unit/movie_search}
	<div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
		<span class="button_6 import-excel">Excel导入
			<input type="file" class="excel" name="excel" accept=".xls" />
		</span>
		<a href="run.php?mid={$_INPUT['mid']}&a=form&infrm=1" target="formwin" class="button_6">新增影院</a>
	</div>
</div>
<div class="wrap clear">
	<div class="cinema-wrap">
		<form method="post" action="" name="vod_sort_listform">
			<ul class="cinema-list play-list clear cinema-list">
  	{if $list}
	   {foreach $list as $k => $v}
	     	{code}
  		  	   $full = $v['update_status'] == $v['playcount'] ? true : false;
  		  	{/code}
		<!-- <li _id="{$v['id']}" id="r_{$v['id']}" name="{$v['id']}" class="cinema-each {if $full}num-equal{/if}">
  	     <div class="cinema-profile m2o-flex">
  		  <div class="cinema-img">
  		  		<a href="run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1" target="formwin">
  					<img _src="{if $v['img_info']}{$v['img_info']}{else}{$RESOURCE_URL}cinema/pic.png{/if}" alt="影院简介" />
  				</a>
  		  </div> 		  
  		  <div class="cinema-brief m2o-flex-one">
  			<h4 title="{$v['title']}"><span class="m2o-common-title">{$v['title']}</span><span class="num"><em class="updata-num">{$v['update_status']}</em></span></h4>
  			<div class="cinema-status"><label>状态: </label><span _id="{$v['id']}"  _status="{$v['status']}" class="reaudit" style="color:{$_configs['status_color'][$v['status']]};">{$v['audit']}</span></div>
			<div class="cinema-sort"><label>分类: </label><span>{if $v['play_sort_name']}{$v['play_sort_name']}{else}暂无分类{/if}</span></div>
  			<div class="cinema-adduser"><label>添加人: </label><span>{$v['user_name']}</span></div>
  			<div class="cinema-addtime"><label>添加时间: </label><span>{$v['create_time']}</span></div>
  		  </div>
  	     </div>
  	     <a class="del"></a>
  	     <a class="arrange-film" href="run.php?mid={$relate_module_id}&a=frame&cinema_id={$v['id']}&cinema_name={$v['title']}" target="">排片表</a>
  	     <span class="select"></span>
     	</li> -->
     	
     	<li class="cinema-each" id="r_{$v['id']}" name="{$v['id']}" _id="{$v['id']}"  orderid="{$v['order_id']}">
			<div class="cinema-name">
				<a href="run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1" target="formwin">
					<span class="m2o-common-title">{$v['title']}</span>
				</a>
			</div>
			<div class="cinema-content">
				<div class="cinema-info m2o-flex">
					<span class="select"></span>
				    <div class="cinema-img">
						{if $v['img_info']}
							<img src="{if $v['img_info']}{$v['img_info']}{else}{$RESOURCE_URL}cinema/pic.png{/if}" class="mk-logo" width="55" height="55" id="img_{$v['id']}"/>
						{else}
							<img src="{$RESOURCE_URL}cinema/pic_detail.png" class="mk-logo" width="55" height="55" id="img_{$v['id']}"/>
						{/if}
						<a class="cover-img"></a>
						<input type="hidden" name="logo_id" class="cinema-logoid" value="{$v['logo_id']}" />
				       <span _id="{$v['id']}"  _status="{$v['status']}" class="reaudit" style="color:{$_configs['status_color'][$v['status']]};">{$v['audit']}</span>
				    </div>
				    <div class="cinema-profile m2o-flex-one">
				    <!-- 
					    <div class="cinema-intro"><label>分类：</label><span>{if $v['play_sort_name']}{$v['play_sort_name']}{else}暂无分类{/if}</span></div>
					 -->
					    <div class="cinema-intro"><label>电话：</label><span>{if $v['tel'][0]['tel']}{$v['tel'][0]['tel']}{else}无{/if}</span></div>
					    <div class="cinema-intro"><label>地址：</label><span title="{$v['address']}">{$v['address']}</span></div>
				    </div>
			    </div>
				<div class="cinema-time">
						<span>{$v['user_name']}</span><span>{$v['create_time']}</span>
						<a href="run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1" target="formwin">
							<em class="edit"></em>
						</a>
						<em class="del"></em>
						<a class="arrange-film" href="run.php?mid={$relate_module_id}&a=frame&cinema_id={$v['id']}&cinema_name={$v['title']}">排片</a>
				</div>
			</div>
		</li>	
		


	   {/foreach}
	{else}
	  <p style="color:#da2d2d;text-align:center;font-size:20px;line-height:20px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
	{/if}
  </ul>
			 <div class="cinema-bottom m2o-flex m2o-flex-center">
  	 <div class="cinema-operate">
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
<script type="text/javascript">
$(function(){
	$('input[name="excel"]').ajaxUpload({
		url :  'run.php?a=relate_module_show&app_uniq=cinema&mod_uniq=project_list&mod_a=excel_update&ajax=1',
		type : 'excel' ,
		phpkey : 'excel',
		before : function(){
		},
		after : function( json ){
			if( json.data.callback ){
				eval( json.data.callback );
			}else{
				$('.nav-box .import-excel').myTip({
	            	string : '导入成功',
	            	delay: 1000,
	            	width : 100,
	             	dtop : 20,
	             	dleft : -120,
	         	});
				var src= top.$("#mainwin").attr('src');
				top.$("#mainwin").attr('src' , src );
			}
		},
	})
})
</script>



