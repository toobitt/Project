{template:head}
{code}
{/code}
{if is_array($formdata) && $a == 'update'}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
{js:jqueryfn/jquery.tmpl.min}
{js:common/common_form}
{css:hg_sort_box}
{js:hg_sort_box}
{js:jquery.form}
{js:tv_play/tv_form}
{js:common/ajax_upload}
{css:common/common}
{css:2013/form}
{css:tv-play}
{css:2013/button}
{code}
$currentSort[$play_sort_id] = ($play_sort_id ? $play_sort_name : '选择分类');
{/code}
<script>
{code}
$arr['upload_url'] = $_configs['App_mediaserver']['protocol'] . $_configs['App_mediaserver']['host'] . '/' . $_configs['App_mediaserver']['dir'] . 'admin/create.php';
$arr['file_types'] = $video_types[0]['videoTypes'];
$arr['admin_name'] = $_user['user_name'];
$arr['admin_id'] = $_user['id'];
$arr['token'] = $_user['token'];
$arr['vod_sort_id'] = $_configs['liv_media_sort'];
$arr['app_uniqueid'] = $app_uniqueid ? $app_uniqueid :  'tv_play';
$arr['mod_uniqueid'] = $mod_uniqueid ? $mod_uniqueid :  'tv_play';
$arr['callback_url'] = $_configs['App_tv_play']['protocol'] . $_configs['App_tv_play']['host'] . '/' . $_configs['App_tv_play']['dir'] . 'admin/tv_episode_upload_callback.php';
$arr['callback_data'] = base64_encode(json_encode(array(
		'tv_play_id' => $id,
)));
$params = json_encode($arr);
{/code}
var tv_play_params = {$params};
</script>
{js:tv_play/tv_upload}
<form class="m2o-form" action="run.php?mid={$_INPUT['mid']}" method="post" data-id="{$id}" id="tv-form">
    <header class="m2o-header">
      <div class="m2o-inner">
        <div class="m2o-title m2o-flex m2o-flex-center">
            <h1 class="m2o-l">{$optext}电视剧</h1>
            <div class="m2o-m m2o-flex-one">
                <input class="m2o-m-title {if $title}input-hide{/if}" _value="{if $title}{$title}{else}添加文稿标题{/if}" name="title" id="title" placeholder="电视剧名称" value="{$title}"/>
            </div>
            <div class="m2o-btn m2o-r">
                <span class="m2o-close option-iframe-back"></span>
                <em class="prevent-do"></em>
            </div>
        </div>
      </div>
    </header>
    <div class="m2o-inner">
     <div class="m2o-main m2o-flex">
     	{template:unit/publish_for_form, 1, $formdata['column_id']}
        <aside class="m2o-l">
        	<div class="m2o-item">
        		<div class="indexpic">
        			<img style="width:176px; height:176px;" src="{$img}" />
                    <span class="{if $title}indexpic-suoyin-current{else}indexpic-suoyin{/if}"></span>
                 </div>
                 <input type="file" name="img" style="display:none;" id="photo-file" />
        	</div>
        	<div class="m2o-item">
        		<label class="title">集数: </label><span><a class="updata-num">{if $title}{$update_status}</a>/{$playcount}{/if}</span>
        	</div>
        	<div class="form-dioption-sort m2o-item"  id="sort-box">
                <label style="color:#9f9f9f;{if !$play_sort_id}display:none;{/if}">分类： </label><p style="display:inline-block;" class="sort-label" _multi="play_sort"> {$currentSort[$play_sort_id]}<img class="common-head-drop" src="{$RESOURCE_URL}tuji/drop.png" style="position: relative;left:10px;bottom:2px;" /></p>
				<div class="sort-box-outer"><div class="sort-box-inner"></div></div>
                <input name="play_sort_id" type="hidden" value="{$play_sort_id}" id="sort_id" />
            </div>
    		<div class="form-dioption-fabu m2o-item">
                <a class="common-publish-button overflow" href="javascript:;" _default="发布至" _prev="发布至：">发布至</a>
            </div>
            <div class="m2o-item">
            	<label class="title">逐集发布: </label>
            	<div class="common-switch {if $publish_auto}common-switch-on{/if}">
		           <div class="switch-item switch-left" data-number="0"></div>
		           <div class="switch-slide"></div>
		           <div class="switch-item switch-right" data-number="100"></div>
		        </div>
            	<input type="hidden" name="publish_auto" value="{$publish_auto}">
            </div>
            <div class="m2o-item">
            	<label class="title">每天发布: </label>
            	<input type="number" min="0" name="publish_num_day" value="{$publish_num_day}" style="width: 50px;">集
            </div>
        </aside>
         <section class="m2o-m m2o-flex-one">
	            <div class="m2o-item tv-info">
	            	<a class="tv-title {if !$id}active{/if}" _id="t_1">基本信息</a>
	            	<a class="tv-title {if $id}active{/if}" _id="t_2">剧集维护</a>
	            </div>
	            <div class="basic-info" _id="t_1" {if $id}style="display:none;"{/if}>
	            <div class="m2o-item">
	        		<label class="title">电视剧简介: </label><textarea name="brief" cols="120" rows="5" placeholder="电视剧简介"> {$brief}</textarea>
	        	</div>
	        	<div class="m2o-item">
	        		<label class="title">导演: </label><input type="text" name="director"  value="{$director}"/>
	        	</div>
	        	<div class="m2o-item">
	        		<label class="title">主演: </label><input type="text" name="main_performer" value="{$main_performer}" />
	        	</div>
	        	
	        	<div class="m2o-item tv-select">
	        		<label class="title">等级: </label>
	        		{code}
	                    $grade_item_source = array(
	                        'class' 	=> 'down_list',
	                        'show' 		=> 'grade_show',
	                        'state' 	=> 	0, /*0--正常数据选择列表，1--日期选择*/
	                        'is_sub'	=>	1,
	                    );
	                    
	                    if($play_grade)
	                    {
	                    	$grade_default = $play_grade;
	                    }
	                    else
	                    {
	                    	$grade_default = -1;
	                    }
	                    $_configs['play_grade'][-1] = '选择等级';
	                {/code}
	                {template:form/search_source,play_grade,$grade_default,$_configs['play_grade'],$grade_item_source}
	        	</div>
	        	
	        	<div class="m2o-item tv-select">
	        		<label class="title">类型: </label>
	        		{code}
	                    $type_item_source = array(
	                        'class' 	=> 'down_list',
	                        'show' 		=> 'type_show',
	                        'state' 	=> 	0, /*0--正常数据选择列表，1--日期选择*/
	                        'is_sub'	=>	1,
	                    );
	                    
	                    if($type)
	                    {
	                    	$type_default = $type;
	                    }
	                    else
	                    {
	                    	$type_default = -1;
	                    }
	                    $type_sort[-1] = '选择类型';
	                    foreach($tv_play_type[0] as $k =>$v)
	                    {
	                        $type_sort[$v['id']] = $v['name'];
	                    }
	                {/code}
	                {template:form/search_source,type,$type_default,$type_sort,$type_item_source}
	        	</div>
	            <div class="m2o-item tv-select">
	        		<label class="title">制片国家/地区: </label>
	                {code}
	                    $district_item_source = array(
	                        'class' 	=> 'down_list',
	                        'show' 		=> 'district_show',
	                        'state' 	=> 	0, /*0--正常数据选择列表，1--日期选择*/
	                        'is_sub'	=>	1,
	                    );
	                    
	                    if($district)
	                    {
	                    	$district_default = $district;
	                    }
	                    else
	                    {
	                    	$district_default = -1;
	                    }

	                    $district_sort[-1] = '选择制片国家/地区';
	                    foreach($tv_play_district[0] as $k =>$v)
	                    {
	                        $district_sort[$v['id']] = $v['name'];
	                    }
	                {/code}
	                {template:form/search_source,district,$district_default,$district_sort,$district_item_source}
	        	</div>
	            <div class="m2o-item tv-select">
	        		<label class="title">语言: </label>
	                {code}
	                    $lang_source = array(
	                        'class' 	=> 'down_list',
	                        'show' 		=> 'languag_show',
	                        'state' 	=> 	0, /*0--正常数据选择列表，1--日期选择*/
	                        'is_sub'	=>	1,
	                    );
	                    
	                    if($lang)
	                    {
	                    	 $lang_default = $lang;
	                    }
	                    else
	                    {
	                    	$lang_default = -1;
	                    }
	                    $lang_sort[-1] = '选择语言';
	                    foreach($tv_play_lang[0] as $k =>$v)
	                    {
	                        $lang_sort[$v['id']] = $v['name'];
	                    }
	                {/code}
	                {template:form/search_source,lang,$lang_default,$lang_sort,$lang_source}
	        	</div>
	        	<div class="m2o-item tv-select">
	        		<label class="title">首播时间: </label>
	                {code}
	                    $year_source = array(
	                        'class' 	=> 'down_list',
	                        'show' 		=> 'year_show',
	                        'state' 	=> 	0, /*0--正常数据选择列表，1--日期选择*/
	                        'is_sub'	=>	1,
	                    );
	                    
	                    if($year)
	                    {
	                    	$year_default = $year;
	                    }
	                    else
	                    {
	                     	$year_default = -1;
	                    }
	                   
	                    $year_sort[-1] = '选择首播时间';
	                    foreach($tv_play_year[0] as $k =>$v)
	                    {
	                        $year_sort[$v['id']] = $v['name'];
	                    }
	                {/code}
	                {template:form/search_source,year,$year_default,$year_sort,$year_source}
	        	</div>
	        	<div class="m2o-item">
	        		<label class="title">总集数: </label><input type="text" name="playcount" value="{$playcount}" />
	        	</div>
	        	<div class="m2o-item tv-select">
	        		<label class="title">版权商: </label>
                    {code}
	                    $publisher_item = array(
	                        'class' 	=> 'down_list',
	                        'show' 		=> 'publisher_show',
	                        'state' 	=> 	0, /*0--正常数据选择列表，1--日期选择*/
	                        'is_sub'	=>	1,
	                    );
	                    
	                    if($publisher)
	                    {
	                    	$publisher_default = $publisher;
	                    }
	                    else
	                    {
	                     	$publisher_default = -1;
	                    }
	                   
	                    $publisher_sort[-1] = '选择版权商';
	                    foreach($tv_play_publisher[0] as $k =>$v)
	                    {
	                        $publisher_sort[$v['id']] = $v['name'];
	                    }
	                {/code}
	                {template:form/search_source,publisher,$publisher_default,$publisher_sort,$publisher_item}
	        	</div>
	        	<div class="m2o-item">
	        		<label class="title">到期时间: </label><input type="text" name="copyright_limit" class="date-picker" value="{$copyright_limit}"/>
	        		<input type="checkbox" {if !$copyright_limit}checked="checked"{/if} id="permanent" value="0"><label for="permanent">永久有效</label> <span>*多选框选中表示永久有效</span>
	        	</div>
	        	<div class="m2o-item">
	        		<label class="title">&nbsp;</label>
	        		<input type="hidden" name="a" value="{$a}" />
	        		<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
					<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
					<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	        		<input type="submit" value="{$optext}" class="save-button" />
	        	</div>
            </div> 
            
            <div class="tv-maintain" _id="t_2" {if !$id}style="display: none;"{/if}>
            	<div class="transform-info"  {if !$id}style="display: none"{/if}>
	            	{code}
	                     $transcode_item = array(
	                        'class' 	=> 'down_list',
	                        'show' 		=> 'transcode_show',
	                        'state' 	=> 	0, /*0--正常数据选择列表，1--日期选择*/
	                        'is_sub'	=>	1,
	                    );
	                   
	                    $transcode_default = 0;
	                    $transcode_sort[0] = '选择转码服务器';
	                    foreach($transcode_servers[0] as $k =>$v)
	                    {
	                        $transcode_sort[$v['id']] = $v['name'];
	                    }
	                {/code}
	                {template:form/search_source,transcode_server,$transcode_default,$transcode_sort, $transcode_item}
	            </div>    
               <ul class="tele-total clear">
               	<li class="teleplay add-play">
               	   <div class="loading"><img src="{$RESOURCE_URL}loading2.gif" /></div>
               		<div id="tvPlayUploadPlace"></div>
               	</li>
               	 {if $episode}
                	{foreach $episode AS $_k => $_v}
	               	<li class="teleplay" _id="{$_v['id']}" _video_id="{$_v['video_id']}">
	               		<p><img src="{$_v['img_index']}"/>
	               			<span class="play"></span>
	               		</p>
	               		<span><em>{$_v['index_num']}</em><span>{$_v['title']}</span>
	               		{if $_v['expand_id']}
	               			<span class="transcode" style="color:#17b202">已发布</span>
               			{else}
               				<span class="transcode" style="color:#f8a6a6">未发布</span>
               			{/if}
	               		</span>
	               		<a class="del"></a>
	               	</li>
	               	{/foreach}
          		{/if}
               </ul>
            </div> 
         </section>
      </div>
     </div>
     <div class="media_box"></div>
</form>
<script type="text/x-jquery-tmpl" id="add-vod-tpl">
	<li class="teleplay"  _id="${id}" _video_id="${video_id}">
        <p><img src="${vod_src}"/><span class="play"></span></p>
        <span><em>${num}</em><span>${title}</span><span class="transcode" style="color:#f8a6a6">未发布</span></span>
        <a class="del"></a>
    </li>
</script>
<script type="text/x-jquery-tmpl" id="vedio-tpl">
<div style="width:400px;height:300px;">
  <object id="vodPlayer" type="application/x-shockwave-flash" data="{code}echo RESOURCE_URL{/code}swf/vodPlayer.swf?11122713" width="400" height="300">
	<param name="movie" value="{code}echo RESOURCE_URL{/code}swf/vodPlayer.swf?11122713">
	<param name="allowscriptaccess" value="always">
	<param name="allowFullScreen" value="true">
	<param name="wmode" value="transparent">
	<param name="flashvars" value="videoUrl=${video_url_m3u8}&autoPlay=true&aspect=${aspect}">
  </object>
</div>
  <span class="vedio-back-close"></span>
</script>
<script>
$(function($){
		$('#tv-form').tv_form();
});
</script>