{template:head}
{css:hg_sort_box}
{template:form/common_form}
{js:jqueryfn/jquery.tmpl.min}
{js:common/ajax_upload}
{js:common/auto_textarea}
{js:hg_sort_box}
{js:common/common_form}
{js:video/video_file}
{js:live_control/live_page}
{js:special/special_conlist}
{js:special/special_form}
{code}

$list = $formdata;
//print_r($list);
$id=$list[id];
if($id)
{
	$operation="update";
}
else
{
	$operation="create";
}
$clients=$clients[0];
$css_attr['style'] = 'style="width:100px"';
$re = $list['sort_id']?$list['sort_name']:'请选择分类';
//print_r($list['client_pic']);

	/*新增集合的状态控件样式*/
	$item_collect_status = array(
		'class' => 'down_list',
		'show' => 'collect_status_show',
		'width' => 80,	
		'state' => 0, 
		'is_sub'=>1,
		'onclick'=>'hg_search_k()'
	);
	
	$trans_status_default = -1;

/*新增集合的类型控件样式*/
	$item_collect_leixing = array(
		'class' => 'down_list',
		'show' => 'collect_leixing_show',
		'width' => 80,	
		'state' => 0, 
		'is_sub'=>1,
		'onclick'=>'hg_search_k()'
	);
	
	$leixing_default = 0;
	$collect_vod_leixing[$leixing_default] = '全部类型';
	foreach($_configs['video_upload_type'] as $k =>$v)
	{
		$collect_vod_leixing[$k] = $v;
	}
	
	
	
	/*集合面板日期控件的数据设定*/
	$attr_date_collect = array(
		'class' => 'colonm down_list data_time',
		'show' => 'collect_colonm_show',
		'width' => 104,
		'state' => 1,
	);
	
	$date_default = 1;
	
	$attr_date = array(
		'class' => 'colonm down_list data_time',
		'show' => 'colonm_show',
		'width' => 104,
		'state' => 1,
	);
	$_configs['video_upload_status'][-1] = '全部状态';
{/code}

{css:column_node}
{css:common/common_form}
{css:2013/iframe_form}
{css:common/common_category}
{css:special}
<style>
.m2o-item .title{display:inline-block;width:60px;text-align:left;}
.m2o-item input{padding:0;width:115px;border-color:transparent;background:transparent;margin-left:-3px;}
.m2o-item input:focus{box-shadow:none;border-color:#ccc;background:white;}
</style>
<form action="./run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" class="special-form" id="special-form" {if $id}_id="{$id}"{/if}>
<!-- form 头部 -->
<div class="common-form-head">
     <div class="common-form-title">
          <h2>
          	<a class="property-tab on">属性</a>
          	<a class="con-tab {if !$id}disabled{else}enabled{/if}" href="./run.php?&a=relate_module_show&app_uniq=special&mod_uniq=special_content&mod_a=show&infrm=1&speid={$_INPUT['id']}" target="formwin" need-back>内容</a>
          	<a class="con-tab {if !$id}disabled{else}enabled{/if}" href="./run.php?a=relate_module_show&app_uniq=special&mod_uniq=special&mod_a=built_template_form&id={$_INPUT['id']}">模板</a>
          </h2>
          <div class="form-dioption-title form-dioption-item">
                <input name="name" _value="{if $list['name']}{$list['name']}{else}添加标题{/if}" id="title" class="title need-word-count" placeholder="添加标题" value="{$list['name']}"/>
                <div class="color-selector clearfix">
                    <span class="form-title-color"></span>
                    <span class="form-title-weight"></span>
                    <span class="form-title-italic"></span>
                </div>
                       <input name="tcolor" type="hidden" value="{$list['tcolor']}" id="tcolor" />
                       <input name="isbold" type="hidden" value="{if $list['isbold']}1{else}0{/if}" id="isbold" />
                       <input name="isitalic" type="hidden" value="{if $list['isitalic']}1{else}0{/if}" id="isitalic" />
                       <input name="weight" value="{$list['weight']}" id="weight" type="hidden" />
          </div>
		  <div class="form-dioption-submit">
		      <input type="submit" value="保存" class="common-form-save" id="special-save"/>
		      <span class="option-iframe-back">关闭</span>
		  </div>
		  <div id="weightPicker">
	                 {template:list/list_weight,agd,$list['weight']}
          </div>  
    </div>
</div>
<!-- form 主体 -->
<div class="common-form-main special-form">
  <!-- form 左侧 -->
  <div class="form-left">
    {code}
    $formdata = $list;
    {/code}
	{template:unit/publish_for_form, 1, $list['column_id']}
	<div class="form-dioption">
            <div class="form-dioption-inner">
                  <div class="form-cioption-indexpic form-cioption-item">
                    <div class="indexpic-box">
                        <div class="indexpic">
                            {code}
						       	 $picinfo = ($list['pic']);
						       	 $url = $picinfo['host'].$picinfo['dir'].'160x120/'.$picinfo['filepath'].$picinfo['filename'];
	       	                {/code}
                            <img class="{if !$picinfo}hide{/if}" src="{$url}" style="max-width:160px;max-height:120px;" title="索引图" />
                        </div>
                        <span class="{if $picinfo}indexpic-suoyin-current{else}indexpic-suoyin{/if}"></span>
                    </div>
                    <input type="file" name="Filedata" id="Filedata"  value="submit" style="display:none;">
                </div>
                
                <div class="form-dioption-item client-box" style="display:none;">
                   <label class="client-index">获取客户端：</label>
				    <div class="client-list client-hide">
					     <div class="items">
					        {foreach $clients as $ck =>$cv}
								  <span class="client-item" id="client[{$ck}]"  data-id="{$ck}">{$cv}</span>
						    {/foreach}
					     </div>
					   <div class="client-file-area">
					       <input type=file style="display:none;" class="file-data" />
						   <div class="client-file">
						        <span class="name" style="display:inline-block;width:60px;height:18px;overflow:hidden;"></span>
						        <span class="file-input"  data-key="client_pic"  style="width:60px;">选取文件</span>
						        <span class="view"><img /></span>
						        <span class="file-delete"></span>
						   </div>
					    </div>
					 </div>
					    {code}
				            $client_pic=$list['client_pic'];
				      {/code}
				      {if $client_pic}
				       {foreach $client_pic as $sc => $sv}
				        {code}
					       	 $url = $sv['host'].$sv['dir'].'40x30/'.$sv['filepath'].$sv['filename'];
					       	 $value=serialize($sv);
	       	            {/code}
				       <div class="client-file-area">
				       	  <input type=file style="display:none;" class="file-data" />
						   <div class="client-file" style="display:block;">
						        <span class="name" style="display:inline-block;width:60px;height:18px;overflow:hidden;">{$clients[$sc]}</span>
						        <span class="file-input" name="{$sc}" data-key="client_pic" style="width:60px;">选取文件</span>
						        <span class="view"><img src="{$url}"/></span>
						        <span class="file-delete"></span>
						        <input type="hidden"  name="client_pic[{$sc}]" value='{$value}' />
						   </div>
					    </div>
					{/foreach}
				  {/if}		
               </div>
            <div class="form-dioption-sort form-dioption-item"  id="sort-box">
                <label style="color:#9f9f9f;{if !$list['sort_id']}display:none;{/if}">分类： </label><p class="sort-label" _multi="special_sort">{$re}<img class="common-head-drop" src="{$RESOURCE_URL}tuji/drop.png" style="position: relative;left:10px;bottom:2px;" /></p>
				<div class="sort-box-outer"><div class="sort-box-inner"></div></div>
                <input name="sort_id" type="hidden" value="{$list['sort_id']}" id="sort_id" />			
            </div>
			<div class="form-dioption-fabu form-dioption-item">
                    <a class="common-publish-button overflow" href="javascript:;" _default="发布至" _type="publish" _prev="发布至：">发布至</a>
            </div>
            <div class="form-dioption-keyword form-dioption-item clearfix" style="position:relative;">
                <span class="keywords-del"></span>
                <span class="form-item" _value="添加关键字" id="keywords-box">
                    <span class="keywords-start">添加关键字</span>
                    <span class="keywords-add">+</span>
                </span>
                <input name="keywords" value="{$list['keywords']}" id="keywords" style="display:none;"/>
            </div>
            <div class="form-dioption-item m2o-item clear">
            	<span class="title">生成方式：</span>
				<select name='maketype'  value="{$formdata['maketype']}">
					{foreach $_configs['maketype'] as $k=>$v}
					<option value="{$k}" {code}if($formdata['maketype']==$k) echo "selected";{/code}>
						{$v}
					</option>
					{/foreach}
				</select>
            </div>
            <div class="form-dioption-item m2o-item clear">
				<span  class="title">文件名：</span>
				<input type="text" value="{$formdata['custom_filename']}" name='custom_filename'>
			</div>
			<div class="form-dioption-item m2o-item clear">
				<span  class="title">目 录：</span>
				<input type="text" value="{$formdata['column_dir']}" name='column_dir' >
			</div>
			<div class="form-dioption-item m2o-item clear">
				<span  class="title">二级域名：</span>
				<input type="text" value="{$formdata['column_domain']}" name='column_domain'>
			</div>
           
       </div>
    </div>
  </div>
  <!-- form 右侧 -->
  <div class="form-middle">
       <div class="form-middle-left">
            {code}
			       	 $toppicinfo = ($list['top_pic']);
			       	 $topurl = $toppicinfo['host'].$toppicinfo['dir'].'650x106/'.$toppicinfo['filepath'].$toppicinfo['filename'];
	       	{/code}
            <div class="special-bigpic">
                 {if !$toppicinfo}<span class="bigpic-flag">添加专题题图</span>{/if}
                <img class="{if !$toppicinfo}hide{/if}" src="{$topurl}"  title="专题题图" />
                <p class="client_title {if $toppicinfo}show{/if}">默认题图</p>
            </div>
            <input type="file" name="bigFiledata" id="bigFiledata"  value="submit" style="display:none;">
            
            <div class="special-bigpic-client client-box">
                  <div class="title"><span class="client_logo_item_add client-index">添加更多终端题图</span></div>
		          <div class="client-list">
					   <span class="client_all_list_pointer"></span>
					    <div class="items">
				        {foreach $clients as $ck =>$cv}
							  <span class="client-item" id="client{$ck}" data-id="{$ck}" data-name="{$cv}">{$cv}</span>
					    {/foreach}
					     </div>
					     <input type=file style="display:none;" class="client-file-data" />
				   </div>
				   <div class="client_log_all">
	                  <!-- 多终端题图start -->
					  {code}
					      $client_top_pic=$list['client_top_pic'];
					  {/code}
					  {if $client_top_pic}
					    {foreach $client_top_pic as $tc => $tv}
					        {code}
						       	 $url = $tv['host'].$tv['dir'].'650x80/'.$tv['filepath'].$tv['filename'];
						       	 $value = serialize($tv);
		       	            {/code}
	                       <div class="client_logo_item" data-id="{$tc}" id="client_logo_item{$tc}">
	                            <p class="client_logo" data-name="{$clients[$tc]}" data-id="{$tc}"><img src="{$url}"/></p>
	                            <p class="client_title">{$clients[$tc]}</p>
	                            <span class="client_logo_delete">x</span>
	                            <input type="hidden"  name="client_top_pic[{$tc}]" value='{$value}'/>
	                       </div>
	                     {/foreach}
					  {/if}		                
	                  <!-- 多终端题图end -->
                  </div>
               </div>
            
            
            <div class="special-descr">
                 <div class="special-brief-title">
		                <p class="blue-line"></p>
		                <span class="miaoshu-title">专题描述</span>
		         </div>
                 <textarea name='brief' placeholder="请在这里输入专题描述" id="miaoshu-editor" class="brief-detail brief-editor">{$list['brief']}</textarea>
            </div>
            <div id="special-brief-area">
	            {if $list['summary']}
	              {foreach $list['summary'] as $k =>$v }
		            <div class="special-brief reduce open">
		               <div class="special-brief-title">
		                <p class="blue-line"></p>
		                <input class="brief" placeholder="填写专题概要" name="summary[{$v['id']}]" value="{$v['title']}" id="{$v['id']}"/>
		                <span class="brief-del">x</span>
		              </div>
		                <textarea class="brief-detail brief-editor" id="brief-editor{$v['id']}" name="detail[{$v['id']}]" placeholder="填写专题概要描述">{$v['content']}</textarea> 
		            </div>
	              {/foreach}
	            {/if}
            </div>
            <div class="brief-controll">
                 <p class="blue-line"></p>
                 <span class="add">添加专题概要</span>
            </div>
       </div>
       <div class="form-middle-right">
            <div id="edit-slide-attach1">
               <div class="edit-slide-title">附件管理</div>
               <!-- 附件管理start -->
               <div class="attach-main">
                     <!--  <div class="attach-title">
                          <div class="attach-tab pic-tab active" data-type='pic'>图片</div>
                          <div class="attach-tab vedio-tab" data-type='vedio'>视频</div>
                     </div>-->
                     <div class="attach-con">
                          <!-- 图片start -->
                          <div class="pic-con">
                                 <div class="edit-slide-button">
				                    <span class="edit-slide-button-item attach-upload" id="attach-upload">添加附件</span>
				                    <input type="file" multiple="" class="attach-upload-button" id="attachment-file" style="display:none;"> 
				               </div>
				               <div class="attachment-type">
				               {foreach $_configs['attachment'] as $k => $v}
				                  <span>{$v} </span>
				               {/foreach}
				               </div>
				               <div class="attachment-view">
				                    {if $list['material']}
				                    {foreach $list['material'] as $k => $v}
				                      <div class="attach-item typeicon{$v['type']}" attach-id="{$v['material_id']}">
								           <span class="attach-name">{$v['name']}</span>
								           <span class="attach-size">({$v['filesize']})</span>
								           <span class="attach-del"></span>
								      </div>
								      <input type="hidden" name=attach-id[] value="{$v['material_id']}" />
								      {/foreach}
								    {/if}
				               </div>
				               <div class="attachid-hidden">
				                {if $list['material']}
				                    {foreach $list['material'] as $k => $v}
				                         <input type="hidden" name=new-attach-id[] value="{$v['material_id']}" />
								    {/foreach}
								{/if}
                               </div>  
                          </div>
                          <!-- 图片end -->
                          <!--  <div class="vedio-con">
                               <div class="edit-slide-button">
				                    <span class="edit-slide-button-item" id="vedio-upload" disabled=true>添加视频</span>
				                    <span class="button-mask"></span>
				               </div>
				               <div class="vedio-view">
				                    <ul class="file-list clearfix vedio-view-list">
				                       {if $list['video']}
				                        {foreach $list['video'] as $k => $v}
				                          {code}
				                              $img=hg_fetchimgurl($v['img'], 80, 60);
				                          {/code}
				                             <li id={$k} class="file-list-li">
										        <a class="pic">
										             <img src="{$img}" />
										        </a>
										        <a class="name">{$v['title']}</a>
										        <span class="attach-del" data-id={$k}></span>
										    </li>
										     <input type="hidden" name=video-id[{$k}] value="{$k}" />
										     <input type="hidden" name=new-video-id[{$k}] value="{$k}" />
				                        {/foreach}
				                       {/if}
				                    </ul>
				               </div>
                          </div>-->
                     </div>
               </div>
               <!-- 附件管理end -->
       </div>
  </div>
</div>
<!-- 
<div class="vedio-file-box">
     <span class="file-close">X</span>
     <div class="file-content box-content">
                <div class="vedio-search" id="vedio-search-form">
                      <!-- 搜索部分开始 
							<div id="search_condition" class="search_condition_all info">
								 {template:form/search_source,sea_add_leixing_id,$leixing_default,$collect_vod_leixing,$item_collect_leixing}
								 {template:form/search_source,collect_trans_status,$trans_status_default,$_configs['video_upload_status'],$item_collect_status}
								 <div class="key-search key-search-open">
								    <input type="text" name="key" id="search_list_key" value="" speech="speech" x-webkit-speech="x-webkit-speech" x-webkit-grammar="builtin:translate">
								    <input type="button" value="" name="hg_search" id="key-search" style="padding: 0; border: 0; margin: 0; background: none; cursor: pointer; width: 22px;">
								 </div>
							</div>
							<!-- 搜索部分结束-->
                <!--</div>
                <ul class="file-list common-vod-area clearfix"></ul>
                <div class="common-page-link fr"></div>
     </div>
</div> -->

</div>
            <input type="hidden" name="a" value="{$operation}" />
            <input type="hidden" id="id"  name="id" value="{$id}" />
            <input type="hidden" id="ajax"  name="ajax" value="1" />
            <div id="top-loading"></div>
            <div id="top-loading2"></div>
            <span class="result-tip"></span>
</form>
<!-- client隐藏域 -->
<script type="text/x-jquery-tmpl" id="client-tpl">
      <div class="client_logo_item" data-id="${id}" id="client_logo_item${id}">
           <p class="client_logo" data-name="${client_name}" data-id="${id}"><img src="${url}"/></p>
           <p class="client_title">${client_name}</p>
           <span class="client_logo_delete">x</span>
      </div>
</script>
<!-- 附件预览模板 -->
<script type="text/x-jquery-tmpl" id="attach-tpl">
      <div class="attach-item typeicon${type}" attach-id="${attach_id}">
           <span class="attach-name">${name}</span>
           <span class="attach-size">(${size})</span>
           <span class="attach-del"></span>
      </div>
</script>
<!-- 附件隐藏域 -->
<script type="text/x-jquery-tmpl" id="attachhiddenid-tpl">
      <input type="hidden" name=new-attach-id[] value="${attach_id}" />
</script>
<script type="text/x-jquery-tmpl" id="brief-tpl">
		            <div class="special-brief new-special-brief reduce">
		                 <div class="special-brief-title">
		                    <p class="blue-line"></p>
			                <input class="brief" placeholder="填写专题概要" name=new-summary[] />
			                <span class="brief-del">x</span>
			             </div>
			                <textarea class="brief-detail" name="new-detail[]" placeholder="专题概要描述"></textarea>
			        </div>
</script>
<!--file模板-->
<script type="text/x-jquery-tmpl" id="file-list-li-tpl">
{{if list}}
    {{each list}}
    <li _id="{{= $value.id}}" class="file-list-li">
        <a class="pic">
             <img src="{{= $value.img}}" />
             <span class="time">{{= $value.duration}}</span>
        </a>
        <a class="name">{{= $value.title}}</a>
    </li>
    {{/each}}
{{else}}
    <li class="list-wu">无</li>
{{/if}}
</script>
<script type="text/x-jquery-tmpl" id="vedio-view-tpl">
     <li id=${id} class="file-list-li">
        <a class="pic">
             <img src="${img}" />
        </a>
        <a class="name">${title}</a>
        <span class="attach-del"></span>
        <input type="hidden" name=new-video-id[${id}] value="${id}" />
    </li>
</script>