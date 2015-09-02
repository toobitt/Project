{code}
$list = $formdata['newslist'];
if($formdata['validtime']=="0")
{
	$formdata['validtime'] = "";
}
else
{
	$formdata['validtime'] = date("Y-m-d H:i",$formdata['validtime']);
}
{/code}
<div class="editor-middle">
	   <form action="run.php?mid={$_INPUT['mid']}" method="post" id="editor-card-form">
		   	 <div class="card-middle-title">
		   	 	<input type="text" value="{$formdata['title']}" name="add_title"  class="card-name" />
		   	 	<span><input type="checkbox" name="fore-show" {if $formdata['is_title']==1} checked {/if} id="fore-show" value="1" /><label for="fore-show">标题前台显示</label></span>
		   	 </div>
		   	 <div class="title-url">标题链接地址:<input type="text" name="title_url" placeholder="请输入链接地址" value="{$formdata['more_link']}" /></div>
		   	 <div class="card-brief">描&nbsp;&nbsp;述:<textarea name="card_brief" placeholder="请输入卡片描述">{$formdata['card_brief']}</textarea></div>
		   	 <div class="valid-time">有效时间：<input type="text" name="valid-time-input" value="{$formdata['validtime']}" class="date-picker" _time="true" /></div>
		   	 <div class="html-editor">
			   	   			<input type="checkbox" name="html-editor" {if $formdata['is_html']==1} checked {/if} id="html-check" value="1" />
			   	   			<label for="card-html">html模式</label>
		   	  </div>
		   	  <div class="form-mode">
	   	   			<input type="checkbox" name="form_mode" {if $formdata['is_form']==1} checked {/if} class="form-mode-check" value="1" />
	   	   			<label>表单模式</label>
		   	  </div>
		   	  <div class="dynamic-setting">
		   	  		<input type="checkbox" name="is_dynamic" {if $formdata['is_dynamic']==1} checked {/if} class="dynamic-setting-check" value="1" />
	   	   			<label>动态配置模式</label>
		   	  </div>
		   	  <div class="card-extend-property clear">
		   	  	<span><input type="checkbox" name="default_show" {if $formdata['is_default_show']==1} checked {/if} value="1" /><label>默认展示卡片</label></span>
		   	  	<span><input type="checkbox" name="fix_show"  {if $formdata['is_fix_show']==1} checked {/if} value="1" /><label>设为固定卡片</label></span>
		   	  	<span><input type="checkbox" name="dingbian_outer_show" {if $formdata['dingbian_outer_show']==1} checked {/if} value="1" /><label>开启外顶边</label></span>
		   	  	<span><input type="checkbox" name="dingbian_inner_show" {if $formdata['dingbian_inner_show']==1} checked {/if} value="1" /><label>开启内顶边</label></span>
		   	  </div>
		   	  <div class="card-html-content {if $formdata['is_html']==1}card-html-show{/if}">
		   	  		<textarea name="html_con" placeholder="html编辑卡片内容">{$formdata['htmltext']}</textarea>
		   	  		<br><br>
		   	  		&nbsp;&nbsp;&nbsp;&nbsp;宽度：<input type="text" name="htmlwidth" value="290" readonly="readonly" style="width:50px; height:15px;" />/px
		   	  		高度：<input type="number" min="0" name="htmlheight" value="{$formdata['htmlheight']}" style="width:50px; height:15px;" />/px
		   	  </div>
		   	 <div class="card-middle-content {if $formdata['is_html']==1}card-middle-hide{/if}">
		   	 	<div class="card-show-img edit-content">
		   	 		{code}
		   	 		//print_R($list);
		   	 		{/code}
		   	 	{foreach $list as $kk=>$vv}
	   	 		{if $vv['cssid']==1}
	   	 		<div class="card-slideshow card-small card-struct" _id="{$vv['id']}">
				   <div class="card-img"><img src="{$vv['indexpic']}"></div>
				   <div class="card-block card-title">{$vv['title']}</div>
				   <input type="hidden" name="source_id[]" class="source-id" value="{$vv['content_id']}" />
				   <input type="hidden" name="source_type[]" class="source-type" value="{$vv['cssid']}" />
				   <input type="hidden" name="source_from[]" class="source-from" value="{$vv['source_from']}" />
				   <input type="hidden" name="title[]" class="source-title" value="{$vv['title']}" />
				   <input type="hidden" name="brief[]" class="source-brief" />
				</div>
	   	 		{/if}
	   	 		{if $vv['cssid']==2}
	   	 		<div class="card-leftsmall card-small border-bottom" _id="{$vv['id']}">
				   	<div class="card-title m2o-flex-one">{$vv['title']}<!--  <span class="card-special">模块类型</span>--></div>
				   	 <div class="card-img"><img src="{$vv['indexpic']}"></div>
				   	<input type="hidden" name="source_id[]" class="source-id" value="{$vv['content_id']}" />
				    <input type="hidden" name="source_type[]" class="source-type" value="{$vv['cssid']}" />
				    <input type="hidden" name="source_from[]" class="source-from" value="{$vv['source_from']}" />
				    <input type="hidden" name="title[]" class="source-title" value="{$vv['title']}" />
				    <input type="hidden" name="brief[]" class="source-brief" />
				</div>
	   	 		{/if}
	   	 		{if $vv['cssid']==3}
	   	 		<div class="card-rightsmall card-small border-bottom" _id="{$vv['id']}">
				   	 <div class="card-title m2o-flex-one">{$vv['title']}<!--  <span class="card-special">模块类型</span>--></div>
				   	 <div class="card-img"><img src="{$vv['indexpic']}"></div>
				   	 <input type="hidden" name="source_id[]" class="source-id" value="{$vv['content_id']}" />
				     <input type="hidden" name="source_type[]" class="source-type" value="{$vv['cssid']}" />
				     <input type="hidden" name="source_from[]" class="source-from" value="{$vv['source_from']}" />
				     <input type="hidden" name="title[]" class="source-title" value="{$vv['title']}" />
				     <input type="hidden" name="brief[]" class="source-brief" />
				</div>
	   	 		{/if}
	   	 		{if $vv['cssid']==4}
	   	 		<div class="card-bottomsmall card-small card-direction border-bottom" _id="{$vv['id']}">
				   	 <div class="card-title m2o-flex-one">{$vv['title']}</div>
				   	<div class="card-img">
				   		{foreach $vv['childs_data'] as $kkk=>$vvv}
				   		 {if $kkk < 3 }
						 <a><img src="{$vvv['host']}{$vvv['dir']}{$vvv['filepath']}{$vvv['filename']}"></a>
						 {/if}
						{/foreach}
				   	</div>
				   	<input type="hidden" name="source_id[]" class="source-id" value="{$vv['content_id']}" />
				    <input type="hidden" name="source_type[]" class="source-type" value="{$vv['cssid']}" />
				    <input type="hidden" name="source_from[]" class="source-from" value="{$vv['source_from']}" />
				    <input type="hidden" name="title[]" class="source-title" value="{$vv['title']}" />
				    <input type="hidden" name="brief[]" class="source-brief" />
				</div>
	   	 		{/if}
	   	 		{if $vv['cssid']==5}
	   	 		<div class="card-text-record card-small card-direction border-bottom" _id="{$vv['id']}">
				      <div class="card-title">{$vv['title']}</div>
				      <div class="card-describe">{$vv['brief']}</div>
				      <input type="hidden" name="source_id[]" class="source-id" value="{$vv['content_id']}" />
				      <input type="hidden" name="source_type[]" class="source-type" value="{$vv['cssid']}" />
				      <input type="hidden" name="source_from[]" class="source-from" value="{$vv['source_from']}" />
				      <input type="hidden" name="title[]" class="source-title" value="{$vv['title']}" />
				      <input type="hidden" name="brief[]" class="source-brief" />
				</div>
	   	 		{/if}
	   	 		{if $vv['cssid']==6}
	   	 		<div class="card-recommend card-small  card-struct" _id="{$vv['id']}">
				   <div class="card-img"><img src="{$vv['indexpic']}"></div>
				   <input type="hidden" name="source_id[]" class="source-id" value="{$vv['content_id']}" />
				   <input type="hidden" name="source_type[]" class="source-type" value="{$vv['cssid']}" />
				   <input type="hidden" name="source_from[]" class="source-from" value="{$vv['source_from']}" />
				   <input type="hidden" name="title[]" class="source-title" value="{$vv['title']}" />
				   <input type="hidden" name="brief[]" class="source-brief" />
				</div>
	   	 		{/if}
	   	 		{if $vv['cssid']==7}
	   	 		<div class="card-video-monitor card-small card-struct card-direction" _id="{$vv['id']}">
				   <div class="card-img card-imgbig"><img src="{$vv['indexpic']}"></div>
				   <div class="card-title">{$vv['title']}</div>
				   <div class="card-describe">{$vv['brief']}</div>
				   <input type="hidden" name="source_id[]" class="source-id" value="{$vv['content_id']}" />
				   <input type="hidden" name="source_type[]" class="source-type" value="{$vv['cssid']}" />
				   <input type="hidden" name="source_from[]" class="source-from" value="{$vv['source_from']}" />
				   <input type="hidden" name="title[]" class="source-title" value="{$vv['title']}" />
				   <input type="hidden" name="brief[]" class="source-brief" />
				</div>
	   	 		{/if}
	   	 		
	   	 		{if $vv['cssid']==8}
	   	 		<div class="card-small card-menu-type  border-bottom clear" title="选中菜单进行编辑">
	   	 			{code}
	   	 				$ids = array();
	   	 			{/code}
	   	 			<div class="module-item demo" style="display:none;">
				        <span class="module-item-img"><img src="" /></span>
				        <span class="module-item-name">菜单名称</span>
				    </div>
	   	 			{foreach $vv['childs_data'] as $kkk => $vvv }
		   	 			{code}
		   	 				$ids[] = $vvv['id'];
		   	 				$url = $vvv['host'].$vvv['dir'].$vvv['filepath'].$vvv['filename'];
		   	 			{/code}
				    <div class="module-item" data-id="{$vvv['id']}">
				        <span class="module-item-img"><img src="{$url}" /></span>
				        <span class="module-item-name">{$vvv['title']}</span>
				        <span class="module-item-del">x</span>
				    </div>
				    {/foreach}
				    {code}
				    $ids = implode(',', $ids);
				    {/code}
				    <input type="hidden" name="source_id[]"  class="source-id" value="{$ids}" >
				    <input type="hidden" name="source_type[]"  class="source-type" value="{$vv['cssid']}" / >
				    <input type="hidden" name="source_from[]"  class="source-from" value="0" />
				    <input type="hidden" name="title[]" class="source-title" />
				    <input type="hidden" name="brief[]" class="source-brief" />
				</div>
				{/if}
				
				{if $vv['cssid']==9}
				<div class="card-small card-leftsmall-descr border-bottom" _id="{$vv['id']}">
				   	 <div class="card-img"><img src="{$vv['indexpic']}"></div>
				   	 <div class="m2o-flex-one">
				   	 	<div class="card-title ">{$vv['title']}</div>
				   	 	<div class="card-describe">{$vv['brief']}</div>
				   	 </div>
				   	<input type="hidden" name="source_id[]" class="source-id" value="{$vv['content_id']}" />
				    <input type="hidden" name="source_type[]" class="source-type" value="{$vv['cssid']}" />
				    <input type="hidden" name="source_from[]" class="source-from" value="{$vv['source_from']}" />
				    <input type="hidden" name="title[]" class="source-title" value="{$vv['title']}" />
				    <input type="hidden" name="brief[]" class="source-brief" value="{$vv['brief']}" />
				</div>
				{/if}
				
				{if $vv['cssid']==10}
				<div class="card-small card-movie-type border-bottom" _id="{$vv['id']}">
					<div class="card-img"><img src="{$vv['indexpic']}"></div>
				   	 <div class="m2o-flex-one">
				   	 	<div class="card-title ">{$vv['title']}</div>
				   	 	<div class="card-describe">{$vv['brief']}</div>
				   	 </div>
				   	<input type="hidden" name="source_id[]" class="source-id" value="{$vv['content_id']}" />
				    <input type="hidden" name="source_type[]" class="source-type" value="{$vv['cssid']}" />
				    <input type="hidden" name="source_from[]" class="source-from" value="{$vv['source_from']}" />
				    <input type="hidden" name="title[]" class="source-title" value="{$vv['title']}" />
				    <input type="hidden" name="brief[]" class="source-brief" value="{$vv['brief']}" />
				</div>
				{/if}
				
				{if $vv['cssid']==11}
	   	 		<div class="card-small card-menu-type card-product-type clear" title="选中商品进行编辑">
	   	 			{code}
	   	 				$ids = array();
	   	 			{/code}
	   	 			<div class="module-item demo" style="display:none;">
				        <span class="module-item-img"><img src="" /></span>
				        <span class="module-item-name">商品名称</span>
				    </div>
	   	 			{foreach $vv['childs_data'] as $kkk => $vvv }
		   	 			{code}
		   	 				$ids[] = $vvv['id'];
		   	 				$url = $vvv['host'].$vvv['dir'].$vvv['filepath'].$vvv['filename'];
		   	 			{/code}
				    <div class="module-item" data-id="{$vvv['id']}">
				        <span class="module-item-img"><img src="{$url}" /></span>
				        <span class="module-item-name">{$vvv['title']}</span>
				        <span class="module-item-descr">{$vvv['brief']}</span>
				        <span class="module-item-del">x</span>
				    </div>
				    {/foreach}
				    {code}
				    $ids = implode(',', $ids);
				    {/code}
				    <input type="hidden" name="source_id[]"  class="source-id" value="{$ids}" >
				    <input type="hidden" name="source_type[]"  class="source-type" value="{$vv['cssid']}" / >
				    <input type="hidden" name="source_from[]"  class="source-from" value="0" />
				    <input type="hidden" name="title[]" class="source-title" />
				    <input type="hidden" name="brief[]" class="source-brief" />
				</div>
				{/if}
				
				{if $vv['cssid']==12}
				<div class="card-small card-struct card-weather-type">
					<div class="weather-head">
						<span class="city">南京</span>
						<span class="week">今天 星期三</span>
					</div>
					<div class="weather-info">
						<div class="temp">22℃~27℃</div>
						<div>阴转雷阵雨</div>
						<div>空气质量 <span class="air-quality">63 良好</span></div>
					</div>
					<input type="hidden" name="source_id[]"  class="source-id" value="query" >
				    <input type="hidden" name="source_type[]"  class="source-type" value="{$vv['cssid']}" / >
				    <input type="hidden" name="source_from[]"  class="source-from" />
				    <input type="hidden" name="title[]" class="source-title" />
				    <input type="hidden" name="brief[]" class="source-brief" />
				</div>
				{/if}
				
				{if $vv['cssid']==13}
				<div class="card-small card-struct card-formstyle-type">
					<div class="form-head">水费查询</div>
					<div class="form-info">
						<div class="m2o-flex"><div class="form-info-input m2o-flex-one">3200******89898</div><div class="form-info-btn">查水费</div></div>
						<div class="form-info-result m2o-flex">
							<div class="m2o-flex-one">
								<div class="m2o-flex"><div class="form-info-title wd60">户&nbsp;名</div><div class="form-info-value">王小二</div></div>
								<div class="m2o-flex"><div class="form-info-title wd60">水&nbsp;费</div><div class="form-info-value">97.84</div></div>
								<div class="m2o-flex"><div class="form-info-title wd60">垃圾费</div><div class="form-info-value">60.00</div></div>
								<div class="m2o-flex"><div class="form-info-title wd60">总收费</div><div class="form-info-value">159.84</div></div>
							</div>
							<div class="mingxi">明细</div>
						</div>
					</div>
					<input type="hidden" name="source_id[]"  class="source-id" value="query" >
				    <input type="hidden" name="source_type[]"  class="source-type" value="{$vv['cssid']}" / >
				    <input type="hidden" name="source_from[]"  class="source-from" />
				    <input type="hidden" name="title[]" class="source-title" />
				    <input type="hidden" name="brief[]" class="source-brief" />
				</div>
				{/if}
				
				{if $vv['cssid']==14}
				<div class="card-small card-struct card-formstyle-type">
					<div class="form-head">公积金查询</div>
					<div class="form-info">
						<div class="m2o-flex"><div class="form-info-title">身份证号</div><div class="form-info-input m2o-flex-one">3200******89898</div></div>
						<div class="m2o-flex"><div class="form-info-title">查询密码</div><div class="form-info-input m2o-flex-one">*******</div></div>
						<div class="m2o-flex"><div class="form-info-btn auto">查询</div></div>
						<div class="form-info-result m2o-flex">
							<div class="m2o-flex-one">
								<div class="m2o-flex"><div class="form-info-title wd90">姓名</div><div class="form-info-value">王小二</div></div>
								<div class="m2o-flex"><div class="form-info-title wd90">个人月存额度</div><div class="form-info-value">200.00</div></div>
								<div class="m2o-flex"><div class="form-info-title wd90">单位月存额度</div><div class="form-info-value">200.00</div></div>
							</div>
							<div class="mingxi">明细</div>
						</div>
					</div>
					<input type="hidden" name="source_id[]"  class="source-id" value="query" >
				    <input type="hidden" name="source_type[]"  class="source-type" value="{$vv['cssid']}" / >
				    <input type="hidden" name="source_from[]"  class="source-from" />
				    <input type="hidden" name="title[]" class="source-title" />
				    <input type="hidden" name="brief[]" class="source-brief" />
				</div>
				{/if}
				
				{if $vv['cssid']==15}
				<div class="card-small card-struct card-formstyle-type">
					<div class="form-head">违章查询</div>
					<div class="form-info">
						<div class="m2o-flex"><div class="form-info-title">车型</div><div class="form-info-input m2o-flex-one">小汽车</div></div>
						<div class="m2o-flex"><div class="form-info-title">车牌号</div><div class="form-info-input m2o-flex-one">苏A6608</div></div>
						<div class="m2o-flex"><div class="form-info-title">发动机号</div><div class="form-info-input m2o-flex-one">J988K</div></div>
						<div class="m2o-flex"><div class="form-info-btn auto">查违章</div></div>
					</div>
					<input type="hidden" name="source_id[]"  class="source-id" value="query" >
				    <input type="hidden" name="source_type[]"  class="source-type" value="{$vv['cssid']}" / >
				    <input type="hidden" name="source_from[]"  class="source-from" />
				    <input type="hidden" name="title[]" class="source-title" />
				    <input type="hidden" name="brief[]" class="source-brief" />
				</div>
				{/if}
				
				{if $vv['cssid']==16}
				<div class="card-small card-baoliao-type border-bottom" _id="{$vv['id']}">
				   	 <div class="card-img"><img src="{$vv['indexpic']}"></div>
				   	 <div class="m2o-flex-one">
				   	 	<div><span class="baoliao-username">{$vv['publish_user']}</span><span class="baoliao-time">{$vv['publish_time']}</span></div>
				   	 	<div class="card-title">{$vv['title']}</div>
				   	 </div>
				   	<input type="hidden" name="source_id[]" class="source-id" value="{$vv['content_id']}" />
				    <input type="hidden" name="source_type[]" class="source-type" value="{$vv['cssid']}" />
				    <input type="hidden" name="source_from[]" class="source-from" value="{$vv['source_from']}" />
				    <input type="hidden" name="title[]" class="source-title" value="{$vv['title']}" />
				    <input type="hidden" name="brief[]" class="source-brief" value="{$vv['brief']}" />
				</div>
				{/if}
				
				{if $vv['cssid']==17}
				<div class="card-rotate-type card-small card-struct" _id="{$vv['id']}">
					<span class="rotate-flag">轮转图数据项</span>
				   <div class="card-img"><img src="{$vv['indexpic']}"></div>
				   <div class="card-block card-title">{$vv['title']}</div>
				   <input type="hidden" name="source_id[]" class="source-id" value="{$vv['content_id']}" />
				   <input type="hidden" name="source_type[]" class="source-type" value="{$vv['cssid']}" />
				   <input type="hidden" name="source_from[]" class="source-from" value="{$vv['source_from']}" />
				   <input type="hidden" name="title[]" class="source-title" value="{$vv['title']}" />
				   <input type="hidden" name="brief[]" class="source-brief" />
				</div>
				{/if}
				
	   	 		{/foreach}
	   	 		
	   	 			{if $formdata['column_id']}
	   	 			<div class="dynamic-hidden-box" style="display:none;">
						<input type="hidden" name="column_id"  value="{$formdata['column_id']}" />
					</div>
					{/if}
	   	 		
		   	 	</div>
		   	 </div>
		   	 <!--  
		   	 <div class="card-html-content card-html-show">
		   	  		&nbsp;&nbsp;&nbsp;&nbsp;排序：<input type="text" name="card_order" value="{$formdata['order_id']}" style="width:50px; height:15px;" />
		   	 </div>-->
		   	 <div class="card-right-div news-edit-buttons">
				<input type="submit" name="sub" value="保存编辑" class="edit-button save" id="save-editor">
				<a class="edit-button cancel edit-cancel" href="#">取消</a>
			  </div>
		   	 <input type="hidden" name="a" value="update" />
		   	 <input type="hidden" name="order_ids" class="order-ids" />
		   	 <input type="hidden" name="id" value="{$formdata['id']}" />
		   	 <input type="hidden" name="status" value="{$formdata['status']}" />
	   	 </form>
</div>
<script>
$(function(){
	var data = {code} echo $list ? json_encode($list) : '{}'; {/code};
	var info = {};
		$.each(data,function(key,value){
			var id = value['id'],
				title = value['title'],
				brief = value['brief'],
				outlink = value['outlink'],
				index_url = value['indexpic'] || '',
				form = value['source_from'],
				listpic = [],
				childs_data = value['childs_data'],
				menu = value['menu'],
				username = value['publish_user'],
				content_createtime = value['create_time'];
			if( childs_data ){
				$.each(childs_data,function(kk,vv){
					var list_url = vv['host'] + vv['dir'] + vv['filepath'] + vv['filename'];
					listpic.push( list_url );
				});
			}
			info[id] = { title : title, brief : brief, index_url: index_url, listpic : listpic , form : form, outlink: outlink, username : username, content_createtime : content_createtime };
			if( $.isArray( childs_data ) && childs_data.length ){
				$.each( childs_data, function( kk, vv ){
					var id = vv['id'],
						title = vv['title'],
						brief = vv['brief'],
						outlink = vv['outlink'],
						index_url = vv['host'] + vv['dir'] + vv['filepath'] + vv['filename'],
						form = vv['source_from'];
					info[id] = { title : title, brief : brief, index_url: index_url, form : form, outlink: outlink };
				} );
			}
		});
	window.globaleditinfo = info;

	$('.date-picker').hg_datepicker();
})
</script>