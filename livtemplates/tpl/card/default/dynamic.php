{code}
$list = $formdata['content'];
$style = $formdata['style'];
{/code}
{foreach $list as $kk=>$vv}
	{code}
	$indexpic = '';
	if( $vv['indexpic'] ){
		$indexpic = $vv['indexpic']['host'] . $vv['indexpic']['dir'] . '80x50/' .$vv['indexpic']['filepath'] .$vv['indexpic']['filename'];
	}
	{/code}
	{if $style==1}
	<div class="card-slideshow card-small card-dynamic-style card-struct" _id="{$vv['id']}">
	   <div class="card-img"><img src="{$indexpic}" /></div>
	   <div class="card-block card-title">{$vv['title']}</div>
	   <input type="hidden" name="source_id[]" class="source-id" value="{$vv['id']}" />
	   <input type="hidden" name="source_type[]" class="source-type" value="{$style}" />
	   <input type="hidden" name="source_from[]" class="source-from" value="{$vv['source_from']}" />
	   <input type="hidden" name="title[]" class="source-title" value="{$vv['title']}" />
	   <input type="hidden" name="brief[]" class="source-brief" />
	</div>
	{/if}
	{if $style==2}
	<div class="card-leftsmall card-small card-dynamic-style border-bottom" _id="{$vv['id']}">
	   	<div class="card-title m2o-flex-one">{$vv['title']}<!--  <span class="card-special">模块类型</span>--></div>
	   	 <div class="card-img"><img src="{$indexpic}" /></div>
	   	<input type="hidden" name="source_id[]" class="source-id" value="{$vv['id']}" />
	    <input type="hidden" name="source_type[]" class="source-type" value="{$style}" />
	    <input type="hidden" name="source_from[]" class="source-from" value="{$vv['source_from']}" />
	    <input type="hidden" name="title[]" class="source-title" value="{$vv['title']}" />
	    <input type="hidden" name="brief[]" class="source-brief" />
	</div>
	{/if}
	{if $style==3}
	<div class="card-rightsmall card-small card-dynamic-style border-bottom" _id="{$vv['id']}">
	   	 <div class="card-title m2o-flex-one">{$vv['title']}<!--  <span class="card-special">模块类型</span>--></div>
	   	 <div class="card-img"><img src="{$indexpic}" /></div>
	   	 <input type="hidden" name="source_id[]" class="source-id" value="{$vv['id']}" />
	     <input type="hidden" name="source_type[]" class="source-type" value="{$style}" />
	     <input type="hidden" name="source_from[]" class="source-from" value="{$vv['source_from']}" />
	     <input type="hidden" name="title[]" class="source-title" value="{$vv['title']}" />
	     <input type="hidden" name="brief[]" class="source-brief" />
	</div>
	{/if}
	{if $style==4}
	<div class="card-bottomsmall card-small card-dynamic-style card-direction border-bottom" _id="{$vv['id']}">
	   	 <div class="card-title m2o-flex-one">{$vv['title']}</div>
	   	<div class="card-img">
	   		{foreach $vv['childs_data'] as $kkk=>$vvv}
	   		 {if $kkk < 3 }
			 <a><img src="{$vvv['host']}{$vvv['dir']}{$vvv['filepath']}{$vvv['filename']}"></a>
			 {/if}
			{/foreach}
	   	</div>
	   	<input type="hidden" name="source_id[]" class="source-id" value="{$vv['id']}" />
	    <input type="hidden" name="source_type[]" class="source-type" value="{$style}" />
	    <input type="hidden" name="source_from[]" class="source-from" value="{$vv['source_from']}" />
	    <input type="hidden" name="title[]" class="source-title" value="{$vv['title']}" />
	    <input type="hidden" name="brief[]" class="source-brief" />
	</div>
	{/if}
	{if $style==5}
	<div class="card-text-record card-small card-dynamic-style card-direction border-bottom" _id="{$vv['id']}">
	      <div class="card-title">{$vv['title']}</div>
	      <div class="card-describe">{$vv['brief']}</div>
	      <input type="hidden" name="source_id[]" class="source-id" value="{$vv['id']}" />
	      <input type="hidden" name="source_type[]" class="source-type" value="{$style}" />
	      <input type="hidden" name="source_from[]" class="source-from" value="{$vv['source_from']}" />
	      <input type="hidden" name="title[]" class="source-title" value="{$vv['title']}" />
	      <input type="hidden" name="brief[]" class="source-brief" />
	</div>
	{/if}
	{if $style==6}
	<div class="card-recommend card-small card-dynamic-style  card-struct" _id="{$vv['id']}">
	   <div class="card-img"><img src="{$indexpic}" /></div>
	   <input type="hidden" name="source_id[]" class="source-id" value="{$vv['id']}" />
	   <input type="hidden" name="source_type[]" class="source-type" value="{$style}" />
	   <input type="hidden" name="source_from[]" class="source-from" value="{$vv['source_from']}" />
	   <input type="hidden" name="title[]" class="source-title" value="{$vv['title']}" />
	   <input type="hidden" name="brief[]" class="source-brief" />
	</div>
	{/if}
	{if $style==7}
	<div class="card-video-monitor card-small card-dynamic-style card-struct card-direction" _id="{$vv['id']}">
	   <div class="card-img card-imgbig"><img src="{$indexpic}" /></div>
	   <div class="card-title">{$vv['title']}</div>
	   <div class="card-describe">{$vv['brief']}</div>
	   <input type="hidden" name="source_id[]" class="source-id" value="{$vv['id']}" />
	   <input type="hidden" name="source_type[]" class="source-type" value="{$style}" />
	   <input type="hidden" name="source_from[]" class="source-from" value="{$vv['source_from']}" />
	   <input type="hidden" name="title[]" class="source-title" value="{$vv['title']}" />
	   <input type="hidden" name="brief[]" class="source-brief" />
	</div>
	{/if}
	
	
	{if $style==9}
	<div class="card-small card-dynamic-style card-leftsmall-descr border-bottom" _id="{$vv['id']}">
	   	 <div class="card-img"><img src="{$indexpic}" /></div>
	   	 <div class="m2o-flex-one">
	   	 	<div class="card-title ">{$vv['title']}</div>
	   	 	<div class="card-describe">{$vv['brief']}</div>
	   	 </div>
	   	<input type="hidden" name="source_id[]" class="source-id" value="{$vv['id']}" />
	    <input type="hidden" name="source_type[]" class="source-type" value="{$style}" />
	    <input type="hidden" name="source_from[]" class="source-from" value="{$vv['source_from']}" />
	    <input type="hidden" name="title[]" class="source-title" value="{$vv['title']}" />
	    <input type="hidden" name="brief[]" class="source-brief" value="{$vv['brief']}" />
	</div>
	{/if}
	
	{if $style==10}
	<div class="card-small card-dynamic-style card-movie-type border-bottom" _id="{$vv['id']}">
		<div class="card-img"><img src="{$indexpic}" /></div>
	   	 <div class="m2o-flex-one">
	   	 	<div class="card-title ">{$vv['title']}</div>
	   	 	<div class="card-describe">{$vv['brief']}</div>
	   	 </div>
	   	<input type="hidden" name="source_id[]" class="source-id" value="{$vv['id']}" />
	    <input type="hidden" name="source_type[]" class="source-type" value="{$style}" />
	    <input type="hidden" name="source_from[]" class="source-from" value="{$vv['source_from']}" />
	    <input type="hidden" name="title[]" class="source-title" value="{$vv['title']}" />
	    <input type="hidden" name="brief[]" class="source-brief" value="{$vv['brief']}" />
	</div>
	{/if}
	
	{if $style==16}
	<div class="card-small card-dynamic-style card-baoliao-type border-bottom" _id="{$vv['id']}">
	   	 <div class="card-img"><img src="{$indexpic}" /></div>
	   	 <div class="m2o-flex-one">
	   	 	<div><span class="baoliao-username">{$vv['publish_user']}</span><span class="baoliao-time">{$vv['publish_time']}</span></div>
	   	 	<div class="card-title">{$vv['title']}</div>
	   	 </div>
	   	<input type="hidden" name="source_id[]" class="source-id" value="{$vv['id']}" />
	    <input type="hidden" name="source_type[]" class="source-type" value="{$style}" />
	    <input type="hidden" name="source_from[]" class="source-from" value="{$vv['source_from']}" />
	    <input type="hidden" name="title[]" class="source-title" value="{$vv['title']}" />
	    <input type="hidden" name="brief[]" class="source-brief" value="{$vv['brief']}" />
	</div>
	{/if}
	
	{if $style==17}
	<div class="card-rotate-type card-dynamic-style card-small card-struct" _id="{$vv['id']}">
		<span class="rotate-flag">轮转图数据项</span>
	   <div class="card-img"><img src="{$indexpic}"></div>
	   <div class="card-block card-title">{$vv['title']}</div>
	   <input type="hidden" name="source_id[]" class="source-id" value="{$vv['id']}" />
	   <input type="hidden" name="source_type[]" class="source-type" value="{$style}" />
	   <input type="hidden" name="source_from[]" class="source-from" value="{$vv['source_from']}" />
	   <input type="hidden" name="title[]" class="source-title" value="{$vv['title']}" />
	   <input type="hidden" name="brief[]" class="source-brief" />
	</div>
	{/if}

{/foreach}
<script>
$(function(){
	var data = {code} echo $list ? json_encode($list) : '{}'; {/code};
	var info = {};
		$.each(data,function(key,value){
			var id = value['id'],
				title = value['title'],
				brief = value['brief'],
				outlink = value['outlink'],
				index_url = value['indexpic'] ? $.createImgSrc( value['indexpic']) : '',
				form = value['source_from'],
				listpic = [],
				childs_data = value['childs_data'],
				menu = value['menu'],
				username = value['publish_user'],
				publish_time = value['publish_time'];
			if( childs_data ){
				$.each(childs_data,function(kk,vv){
					var list_url = vv['host'] + vv['dir'] + vv['filepath'] + vv['filename'];
					listpic.push( list_url );
				});
			}
			info[id] = { title : title, brief : brief, index_url: index_url, listpic : listpic , form : form, outlink: outlink, username : username, publish_time : publish_time };
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
	


})
</script>