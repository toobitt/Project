{template:head}
{css:2013/form}
{js:pop/base_pop}
{js:pop/pop_list}
{js:mms_default}
{js:input_file}
{js:vote}
{css:column_node}
{css:2013/button}
{css:hg_sort_box}
{css:vote_from}
{css:jquery.lightbox-0.5}
{js:hg_sort_box}
{js:column_node}
{js:common/common_form}
{js:common/publish}
{js:jqueryfn/jquery.tmpl.min}
{js:vote/vote_add}
{js:ajax_upload}
{js:jquery.lightbox-0.5}
{js:2013/ajaxload_new}
{js:page/page}
<style>
.verify_type{display:none}
</style>
{if $a}
	{code}
		$action = $a;
	{/code}
{/if}

{if is_array($formdata)}
	{foreach $formdata AS $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}

{code}
$currentSort[$sort_id] = ($sort_id ? $sort_name : '选择分类');
$method = $id ? 'update' : 'save';
$verifytype = $verifytype[0];
{/code}
<form class="m2o-form" action="run.php?mid={$_INPUT['mid']}" method="post" data-id="{$id}" id="vote-form" enctype="multipart/form-data">
	
    <header class="m2o-header">
      <div class="m2o-inner">
        <div class="m2o-title m2o-flex m2o-flex-center">
            <h1 class="m2o-l">{$optext}投票</h1>
            <div class="m2o-m m2o-flex-one">
                <input class="m2o-m-title {if $title}input-hide{/if}" _value="{if $title}{$title}{else}添加文稿标题{/if}" name="title" id="title" placeholder="输入投票名称" value="{$title}"/>
            </div>
            <div class="m2o-btn m2o-r">
                <input type="submit" name="sub" value="保存" class="save-button"  >
				<input type="hidden" name="a" value="{$action}" id="action" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
                <span class="m2o-close option-iframe-back"></span>
            </div>
        </div>
      </div>
    </header>
    <div class="m2o-inner">
     <div class="m2o-main m2o-flex">
        <div class="m2o-l" style="height:auto">
        	<div class="m2o-item" style="position:relative">
        		<div class="indexpic">
        			{code}$index_img = hg_fetchimgurl($pictures_info,160,160){/code}
        			<img src="{$index_img}" />
                    <span class="indexpic-suoyin {if $formdata['index_img']}indexpic-suoyin-current{/if}"></span>
                 </div>
                 <input type="file" name="question_files" style="display:none;" id="photo-file" />
        	</div>
        	<div class="form-dioption-sort m2o-item"  id="sort-box">
                <label style="color:#9f9f9f;">分类： </label><p style="display:inline-block;" class="sort-label" _multi="vote_node"> {$formdata['sort_name']}<img class="common-head-drop" src="{$RESOURCE_URL}tuji/drop.png" style="position: relative;left:10px;bottom:2px;" /></p>
				<div class="sort-box-outer"><div class="sort-box-inner"></div></div>
                <input name="sort_id" type="hidden" value="{$sort_id}" id="sort_id" />
            </div>
			{template:unit/publish_for_form, 1, $formdata['column_id']}
        	<div class="m2o-item">
        		<div class="form-dioption-fabu form-dioption-item">
                    <a class="common-publish-button overflow title" href="javascript:;" _default="发布至" _prev="发布至：" _type="publish" >发布至</a>
                </div>
        	</div>
        	<div class="file-l"  style="display:block;">
        	
        	 	<div class="foot-hidden  column-list" >
         				<span class="file-input pic">图片</span>
         				<span class="file-input video uploadvod">视频</span>
         				<span class="file-input music uploadvod">音频</span>
         				<span class="file-input cite">引用</span>
         		</div>
        	  	<div class="file-show">
        	  	<input type="hidden" class="pic-hidden" name="picture_ids" value="{$formdata['pictures']}"/>
         		<input type="hidden" class="cite-hidden" name="publishcontent_id" value="{$formdata['publishcontent_id']}"/>
         		<input type="hidden" class="video-hidden" name="vod_id" value="{$formdata['vod_id']}"/>
        	  	<input type="file" class="Materialfile" name="Filedata" multiple style="display:none"/>
        	  	<input type="file" class="videofile" name="videofile" multiple style="display:none"/>
        	  	{foreach $formdata['pictures_other'] as $k => $v}
         		    	<div class="Preview {if $v['upload_type']=='音频'} prevod {/if} {if $v['upload_type']=='视频'} prevod {/if}{if $v['upload_type']=='引用'} pre {/if} {if $v['upload_type']=='图片'}prepic {/if}" style="width:197px" _id="{$v['id']}"{if $v['upload_type']=='音频'} data-url="{$v['url']}" {/if}  {if $v['upload_type']=='视频'} data-url="{$v['m3u8']}" {/if}>
         		    		<div class="contain">
         		    			{code}$index_img_info = hg_fetchimgurl($v['pic_arr'],100,75){/code}
         		    			<img src="{$index_img_info}"/>
         		    			<span class="Pre-file">{$v['upload_type']}</span>
         		    			<p class="del-pic" style="right: 92px;"></p>
         		    		</div>
         		    		<span class="Pre-brief" style="float: right;width: 83px;display: inline-block;margin-top: -80px;height:75px;overflow:hidden;text-overflow:ellipsis;">
         		    		{if $v['upload_type']=='引用'}
         		    		<div style="color: #9f9f9f;">{$v['module_name']}|</div>
         		    		{/if}
         		    		{$v['title']}</span>
         		    		
         		    		<span class="play {if $v['upload_type']=='视频'} play-button {/if} {if $v['upload_type']=='音频'} play-button {/if}"></span>
         		    		
         		    	</div>
         		 {/foreach}   	
         	    </div>
         	</div>
        </div>
         <section class="m2o-m m2o-flex-one contents-list">
         	<div class="m2o-m-head">
         		<span class="vote-choose">投票选项</span>
         		<a class=" advance-mode">高级模式</a>
         	</div>
         	
         	<div class="content content-list-add" style="z-index:-9px;">
         	{if $formdata['options']}
         		{foreach $formdata['options'] as $k => $v}
         		{code} $num = $k+1; {/code}
         		<div class="content-list"> 
         			<span class="content-index">{$num}</span>
         			<input class="content-title content-input" type="text" value="{$v['title']}">
         			<span class="init-vote">初始票数</span><input class="init-votes" type="text"  value="{$v['ini_num']}">
         			<span class="del content-del">一</span>
         			<!-- <input type="hidden" name="order[{$k}]" value="{$v['order_id']}"> -->
         		</div>
         		{/foreach}
         	{else}
				{code}
				for ($i = 0; $i < 2; $i++) {
				{/code}
					<div class="content-list"> 
         				<span class="content-index">{code}echo $i+1;{/code}</span>
         				<input class="content-title content-input" type="text" value="">
         				<input type="hidden" name="option_id[{$i}]" value="" />
         				<!-- <input type="hidden" name="order[{$i}]" value="{code}echo $i+1;{/code}" /> -->
         				<span class="init-vote">初始票数</span><input class="init-votes" type="text"  value="">
         				<span class="del content-del">一</span>
         			</div>
								
				{code}
				}
				{/code}
			{/if}
         	</div>
         	
       <!-- 高级模式 -->  
         <div class="content advanced-content-list-add" style="display:none;">
         	<input type="file" class="Materialfile" name="Filedata" multiple style="display:none"/>
         	<input type="file" class="videofile" name="videofile" multiple style="display:none"/>
         	{if $formdata['options']}
         	{foreach $formdata['options'] as $k => $v}
         	<div class="m2o-flex content-list" orderid="{$v[order_id]}" opid={$v['id']}> 
         	<!-- <input type="file" class="content-title-img" name="option_files_{$k}"  style="display:none"/> -->
         	<input type="hidden" class="content-title-img" name="option_index[{$k}]" value="" />
         	<!-- <input type="hidden" name="order[{$k}]" value="{$v['order_id']}"> -->
         	{code}
         		$num = $k+1;
         	{/code}
         		<div class="content-index" >{$num}</div>
         		<div class="content-img" >
         			{code}$option_index_img = hg_fetchimgurl($v['pictures_info'],100,75){/code}
         			<img src="{$option_index_img}" data-type="1"/>
         		</div>
         		<div class="m2o-flex-one">
         			<input class="content-title advanced-content-input" placeholder="增加选项" type="text" name="option_title[{$k}]" value="{$v['title']}">
         			<input type="hidden" name="option_id[{$k}]" value="{$v['id']}" />
         			<span class="del content-del"  title="删除此条选项">一</span>
         			{foreach $v['describes'] as $kk => $vv}	
         			<div class="add-input-brief">
         				<input class="advanced-content-input" type="text" style="margin-top: 9px;" name="option_describes[{$k}][]" value="{$vv}">
         				<span class="del input-del">一</span>
         			</div>
         			{/foreach}
         			<div class="vote-brief">
         		  		<input class="advanced-content-input add-input" placeholder="增加描述" type="text" style="margin-top: 9px;" name="option_describes[{$k}][]" >
         				<span class="advanced-content-add">+</span>
         			</div>	
         		    <div class="foot-hidden">
         				<span class="file-input pic">图片</span>
         				<span class="file-input video uploadvod">视频</span>
         				<span class="file-input music uploadvod">音频</span>
         				<span class="file-input cite">引用</span>
         		    	<span class="advanced-init-vote">初始票数</span><input class="init-votes" type="text" name="ini_num[{$k}]" value="{$v['ini_num']}">
          		    </div>
         		    <div class="file-show">
         		    	<input type="hidden" class="pic-info-hidden" name="option_files[{$k}]" value=""/>
         		    	<input type="hidden" class="pic-hidden" name="option_picture_ids[{$k}]" value="{$v['pictures']}"/>
         		    	<input type="hidden" class="cite-hidden" name="quote_ids[{$k}]" value="{$v['publishcontent_id']}"/>
         		    	<input type="hidden" class="video-hidden" name="option_vod_ids[{$k}]" value="{$v['vod_ids']}"/>
         		    	{foreach $v['pictures_other'] as $kk => $vv}
         		    	<div class="Preview {if $vv['upload_type']=='视频'} prevod {/if} {if $vv['upload_type']=='音频'} prevod {/if} {if $vv['upload_type']=='引用'} pre {/if} {if $vv['upload_type']=='图片'}prepic {/if}" _id="{$vv['id']}" {if $vv['upload_type']=='视频'} data-url="{$vv['m3u8']}" {/if} {if $vv['upload_type']=='音频'} data-url="{$vv['url']}" {/if}>
    						<div class="contain">
    							{code}$option_img_info = hg_fetchimgurl($vv['pic_arr'],100,75){/code}
        						<img src="{$option_img_info}">
         						<span class="Pre-file">{$vv['upload_type']}</span>
         						<p class="del-pic"></p>
         						{if $vv['upload_type']=='引用'}
         						<div class="Pre-brief"><label style="color: #9f9f9f;">{$vv['module_name']}|</label>{$vv['title']}</div>
         						{/if}
         						{if $vv['upload_type']=='视频'}
         		    				<span class="play-button"></span>
         		    			{/if}
         		    			{if $vv['upload_type']=='音频'}
         		    				<span class="play-button"></span>
         		    			{/if}
       						 </div>
   						 </div>
         		    	{/foreach}
         		    </div>
         		</div>
         	</div>
         	{/foreach}
         	{else}
				{code}
				for ($i = 0; $i < 2; $i++) {
				{/code}
				<div class="m2o-flex content-list"> 
         			<!--  <input type="file" class="content-title-img" name="option_files_{code}echo $i;{/code}"  style="display:none"/>-->
         			<input type="hidden" class="content-title-img" name="option_index[{code}echo $i;{/code}]" value="" />
         			<!--  <input type="hidden" name="order[{$i}]" value="{code}echo $i+1;{/code}" /> -->
         			<div class="content-index" >{code}echo $i+1;{/code}</div>
         			<div class="content-img" >
         				<img src="" data-type="1"/>
         			</div>
         			<div class="m2o-flex-one">
         				<input class="content-title advanced-content-input" placeholder="增加选项" type="text" name="option_title[{code}echo $i;{/code}]" value="">
         			    <input type="hidden" name="option_id[{code}echo $i;{/code}]" value="" />
         				<span class="del content-del"  title="删除此条选项">一</span>
         				<div class="vote-brief">
         					<input class="advanced-content-input add-input" placeholder="增加描述" type="text" style="margin-top: 9px;" name="option_describes[{code}echo $i;{/code}][]" >
         					<span class="advanced-content-add">+</span>
         				</div>	
         		   		<div class="foot-hidden">
         					<span class="file-input pic">图片</span>
         					<span class="file-input video uploadvod">视频</span>
         					<span class="file-input music uploadvod">音频</span>
         					<span class="file-input cite">引用</span>
         		    		<span class="advanced-init-vote">初始票数</span><input class="init-votes" type="text" name="ini_num[{$i}]" value="">
         		    	</div>
         		    	<div class="file-show">
         		    		<input type="hidden" class="pic-info-hidden" name="option_files[{code}echo $i;{/code}]" value=""/>
         		    		<input type="hidden" class="pic-hidden" name="option_picture_ids[{code}echo $i;{/code}]" value=""/>
         		    		<input type="hidden" class="cite-hidden" name="quote_ids[{code}echo $i;{/code}]" value=""/>
         		    		<input type="hidden" class="video-hidden" name="option_vod_ids[{code}echo $i;{/code}]" value=""/>
         		    	</div>
         			</div>
         		</div>
				{code}
				}
				{/code}
			{/if}
         </div>
         	<span class="content-add">+新增选项</span>
         </section>
         <div class="m2o-r">
         	<div class="m2o-item">
        		<div class="form-dioption-fabu form-dioption-item">
                    <textarea placeholder="添加投票描述" class="add-brief" name="describes" >{$formdata['describes']}</textarea>
                </div>
           </div>
        	<div class="m2o-item">
        		<div class="form-dioption-fabu form-dioption-item">
                    <a class="overflow times">关键词</a> <input class="time" name="keywords" value="{$formdata['keywords']}"></textarea>
                </div>
           </div>
        	<div class="m2o-item">
        		<div class="form-dioption-fabu form-dioption-item">
                    <a class=" overflow times">开始时间</a>
                     <input class="time start-time date-picker" _time="true" type="text" name="start_time" value="{if ($formdata['start_time'])}{$formdata['start_time']}{/if}">
                    <a class=" overflow times">结束时间</a>
                    <input class="time end-time date-picker" _time="true" type="text" name="end_time" value="{if ($formdata['end_time'])}{$formdata['end_time']}{/if}">
                </div>
        	</div>
        	<div class="m2o-item sort-box-with-show">
        		<div class="form-dioption-fabu form-dioption-item single-option">
                    {code}
                    	if($formdata['option_type'] == 1 || !$formdata['option_type'] ){
                    		$select = '单选';
                    	}else{
                    		$select = '多选';
                    	}
                    {/code}
                    <a class="title one-option">{$select}</a>
                    <input type= "hidden" name="option_type" value="{$formdata['option_type']}"/>
                    <img class="common-head-drop" src="{$RESOURCE_URL}tuji/drop.png" >
                    <ul class="option-ul sort-box-with-show">
                    	<li>单选</li>
                    	<li>多选</li>
                    </ul>
                </div>
                <div class="form-dioption-fabu form-dioption-item more-options" {if ($select == '多选')}style="display:block" {/if}/>
                    	<a class="option max-option">  最多
                    	<input type="text" name="max_option" value="{$formdata['max_option']}">&nbsp;项</a>
                    	<a class="option min-option">最少
                    	<input type="text" name="min_option" value="{$formdata['min_option']}">&nbsp;项</a>
                </div>
                
        	</div>
        	<div class="m2o-item">
        		<div class="form-dioption-fabu form-dioption-item">
        			<input type="checkbox" {if $formdata['is_other']}checked{/if}  name="is_other" value="1">
                    <a class="other-option">其他选项</a>
                </div>
        	</div>
        	<div class="m2o-item">
        		<div class="form-dioption-fabu form-dioption-item ip-limit">
                  <input type="checkbox" class="limit-ip-box limit-box" {if $formdata['is_ip']}checked{/if} name="is_ip" value="1" />
                    <a class="limit-ip" >IP限制</a>
                    <div class="ip-limit-hour limit-hour"  {if $formdata['is_ip']} style="display:block"{/if}>
                    <input type="text" class="num_count" name="ip_limit_time" value="{$formdata['ip_limit_time']}"><a>小时</a>
                    {code}$ip_limit_num = $formdata['ip_limit_num'] ? $formdata['ip_limit_num'] : 1;{/code}
                    <input type="text" class="num_count" name="ip_limit_num" value="{$ip_limit_num}"><a>票</a>
                    </div>
                </div>
        	</div>
        	{if $_configs['App_mobile']}
        	<div class="m2o-item">
        		<div class="form-dioption-fabu form-dioption-item device-limit">
                  <input type="checkbox" class="limit-device-box limit-box" {if $formdata['is_device']}checked{/if} name="is_device" value="1" />
                    <a class="limit-device" >设备限制</a>
                    <div class="device-limit-hour limit-hour"  {if $formdata['is_device']} style="display:block"{/if}>
                    <input type="text" class="num_count" name="device_limit_time" value="{$formdata['device_limit_time']}"><a>小时</a>
                    {code}$device_limit_num = $formdata['device_limit_num'] ? $formdata['device_limit_num'] : 1;{/code}
                    <input type="text" class="num_count" name="device_limit_num" value="{$device_limit_num}"><a>票</a>
                    </div>
                </div>
        	</div>
        	{/if}
        	<div class="m2o-item">
        		<div class="form-dioption-fabu form-dioption-item">
                   <input type="checkbox"  class="limit-ip-box limit-box" {if $formdata['is_userid']} checked{/if} name="is_userid" value="1">
                    <a class="" >登录后投票</a>
                    <div class="login-limit-hour limit-hour"  {if $formdata['is_userid']} style="display:block"{/if}>
                    <input type="text" class="num_count" name="userid_limit_time" value="{$formdata['userid_limit_time']}"><a>小时</a>
                    {code}$userid_limit_num = $formdata['userid_limit_num'] ? $formdata['userid_limit_num'] : 1;{/code}
                    <input type="text" class="num_count" name="userid_limit_num" value="{$userid_limit_num}"><a>票</a>
                     </div>
                </div>
        	</div>
        	{if $_configs['App_verifycode']}
        	<div class="m2o-item">
        		<div class="form-dioption-fabu form-dioption-item">
                  <input class="verify" type="checkbox"  {if $formdata['is_verify_code']}checked{/if} name="is_verify_code" value="1">
                    <a>开启验证码</a>{if $verifytype['false']}  <span> * {$verifytype['false']}</span>{/if}
                </div>
                <div class="verify_type" style="display:{if $formdata['verify_type']}block{else}none{/if}">
               		<!-- <div class="verify_code">
	                	<div class="form-dioption-item verify_title">
	                  		<input type="radio"  {if $is_msg_verify}checked{/if} name="verify_type" value="-1">
	                  		<a class="" >短信验证码</a>
	                	</div>
	                	<img style="width: 48px;height: 48px; margin-left:30px;" src="../../../.././../livtemplates/tpl/lib/images/menu2013/app/message_received.png"/>
                	</div> -->
                    {if !$verifytype['false']}
                	{foreach $verifytype as $k=>$v}
                	<div class="verify_code">
	                	<div class="form-dioption-item verify_title">
	                  		<input type="radio"  {if $formdata['verify_type'] == $v['id'] }checked{/if} name="verify_type" value="{$v['id']}">
	                  		<a class="" >{$v['name']}</a>
	                	</div>
	                	<img src="run.php?a=get_verify_code&type={$v['id']}"/>
                	</div>
                	{/foreach}
                	{/if}
                 </div>
        	</div>
        	{/if}
        	{if $_configs['App_feedback']}
        	 <!--   调查反馈  -->
        	<div class="m2o-item feedback-item">
             	<div class="form-dioption-item ">
                  <input class="verify" type="checkbox" _id="{$feedback_id}" {if $is_feedback}checked{/if} name="is_feedback" value="1">
                  <a class="" >使用反馈表单</a>
                  <span class="feed-title sort-label">{$feedback_title}</span>
                </div>
                <div class="feedback-list verify_type"  style="display:{if $is_feedback}block{else}none{/if}">
                	<div class="feedback-list-item"></div>
		        	<div class="page_size"></div>
        		</div>   
        	</div>
        	{/if}
        	 </div>
           </div>
         </div>
          <div class="video-box"></div>
      </form>

{template:foot}
<!-- 简单模式 -->
<script type="text/x-jquery-tmpl" id="content-input-tpl">
	<div class="content-list"> 
         <span class="content-index">${num}</span>
         <input class="content-title content-input" type="text">
         <input type="hidden" name="option_id[${reduce}]" value="" />
        <span class="init-vote">初始票数</span><input class="init-votes" type="text" >
         <span class="del content-del">一</span>
		<!-- <input type="hidden" name="order[${reduce}]" value="${num}" />-->
    </div>
</script>

<!-- 高级模式 -->
<script type="text/x-jquery-tmpl" id="advanced-content-input-tpl">
	<div class="m2o-flex content-list" > 
 			<!--<input type="file" class="content-title-img" name="option_files_${reduce}"  style="display:none"/>-->
				<input type="hidden" class="content-title-img" name="option_index[${reduce}]" value=""/>
				<!-- <input type="hidden" name="order[${reduce}]" value="${num}" />-->
         <div class="content-index" >${num}</div>
         <div class="content-img" >
			<img src="" data-type="1" />
		 </div>
         <div class="m2o-flex-one">
         	<input class="content-title advanced-content-input" placeholder="增加选项" type="text" name="option_title[${reduce}]">
            <input type="hidden" name="option_id[${reduce}]" value="" />
         	<span class="del content-del">一</span>
         	<div class="vote-brief"><input class="advanced-content-input add-input" placeholder="增加描述" type="text" style="margin-top: 9px;" name="option_describes[${reduce}][]">
         	<span class="advanced-content-add">+</span></div>
         	<div class="foot-hidden">
         		<span style="margin-left: 12px;" class="file-input pic">图片</span>
         		<span class="file-input video uploadvod">视频</span>
         	 	<span class="file-input music uploadvod">音频</span>
         		<span class="file-input cite">引用</span>
         		<span class="advanced-init-vote">初始票数</span><input class="init-votes" type="text" name="ini_num[${reduce}]">
         	</div>
 			<div class="file-show">
				<input type="hidden" class="pic-info-hidden" name="option_files[${reduce}]" value=""/>
				<input type="hidden" class="pic-hidden" name="option_picture_ids[${reduce}]" value=""/>
				<input type="hidden" class="cite-hidden" name="quote_ids[${reduce}]" value=""/>
				<input type="hidden" class="video-hidden" name="option_vod_ids[${reduce}]" value=""/>
			</div>
         </div>
   </div>
</script>
<!-- 图片 视频 音频 引用模板 -->
<script type="text/x-jquery-tmpl" id="file-show-tpl">
	<div class="Preview" _id="${id}" data-url="${url}">
    	<div class="contain">
        	<img src="${src}">
         	<span class="Pre-file">${type}</span>
         	<p class="del-pic"></p>
         	<div class="Pre-brief"><label style="color: #9f9f9f;">${name}|</label>${title}</div>
        </div>
		<input type="hidden" class="cite-hidden" name="quote_ids[${num}]" value=""/>
		<span class="play" style="left:50%"></span>
    </div>
</script>

<!-- 播放器 -->
<script type="text/x-jquery-tmpl" id="vedio-tpl">
<div style="width:240px;height:240px;">
  <object id="vodPlayer" type="application/x-shockwave-flash" data="{code}echo RESOURCE_URL{/code}swf/vodPlayer.swf?11122713" width="240" height="240">
	<param name="movie" value="{code}echo RESOURCE_URL{/code}swf/vodPlayer.swf?11122713">
	<param name="allowscriptaccess" value="always">
	<param name="allowFullScreen" value="true">
	<param name="wmode" value="transparent">
	<param name="flashvars" value="videoUrl=${video_url}&autoPlay=true&aspect=${aspect}">
  </object>
</div>
  <span class="vedio-back-close"></span>
</script>
<script type="text/x-jquery-tmpl" id="detail-tpl" _val="{$feedback_id}">
{{each options}}
	<div class="form-feedback-item verify_title" style="margin: 4px 2px;">
	<input type="radio" {{if $value['id'] == checkedid}}checked="checked"{{/if}} name="feedback_id" value="{{= $value['id']}}">
	<a class="" >{{= $value['title']}} </a>
	</div> 
{{/each}}      
</script>






