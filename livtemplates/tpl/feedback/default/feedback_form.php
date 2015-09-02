{template:head}
{css:2013/form}
{css:column_node}
{css:2013/button}
{css:hg_sort_box}
{css:feedback_form}
{js:common/publish}
{js:common/common_form}
{js:hg_sort_box}
{js:jquery.lightbox-0.5}
{js:2013/ajaxload_new}
{js:feedback/feedback_form}
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
$extensions = $extensions[0];
//print_r($formdata);
{/code}
<script type="text/javascript">
$.globalData = {code}echo $formdata ? json_encode($formdata) : '{}';{/code};
$.globalId = {code}echo $id ? json_encode($id) : '{}';{/code};
</script>
<form class="m2o-form" action="run.php?mid={$_INPUT['mid']}" method="post" _id="{$id}" id="feedback-form" _publish="{$formdata['is_publish']}" enctype="multipart/form-data">	
   	<div class="cover"></div>
    <header class="m2o-header feedback-header">
      <div class="m2o-inner">
        <div class="m2o-title m2o-flex m2o-flex-center">
            <h1 class="m2o-l">{$optext}表单</h1>
            <div class="m2o-m m2o-flex-one">
                <input class="m2o-m-title {if $title}input-hide{/if} need-word-count" _value="{if $title}{$title}{else}添加表单标题{/if}" name="title" id="title" required placeholder="输入表单名称" value="{$title}"/>
            </div>
            <div class="m2o-btn m2o-r">
                <input type="button" value="另存为" class="save-button save_as"/>
                <input type="submit" name="sub" value="保存" class="save-button m2o-save"  >
				<input type="hidden" name="a" value="{$action}" id="action" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
                <span class="m2o-close option-iframe-back"></span>
                 <!-- 另存为 -->
			    <div class="notice-box">
			    	<p class="arrow"></p>
			    	<div class="msg"><p>此表单已发布使用，保存后会改变前端的显示，您是否继续保存？</p></div>
			    	<div class="save-box">
			    		<p class="save_as">另存为</p>
			    		<p class="other_save">保存</p>
			    		<p class="cancel">取消</p>
			    	</div>
			    </div>
    			<!-- end -->
            </div>
        </div>
      </div>
    </header>
    <div class="m2o-inner feedback-box">
     <div class="m2o-main m2o-flex">
        <div class="m2o-l" style="height:auto">
        	<div class="m2o-item img-info" style="position:relative">
			        <div class="indexpic">
			            {code}
			            $indexpic_url = $indexpic['host'] . $indexpic['dir'] . $indexpic['filepath'] . $indexpic['filename'];
			            {/code}
			            <img src="{$indexpic_url}" />
			            <span class="indexpic-suoyin {if $formdata['indexpic']}indexpic-suoyin-current{/if}"></span>
			            <input type="hidden" name="indexpic" value="{$indexpic}" />
			        </div>
			        <input type="file" name="indexpic" style="display:none;" class="upload-file" />
			</div>
        	
        	<div class="m2o-item">
        		<textarea class="feedback-brief" placeholder="添加表单描述" name="feedback_brief">{$formdata['brief']}</textarea>
        	</div>
        	
        	<div class="m2o-item">
        		<span style="color:#9f9f9f;">页面标题：</span>
        		<input type="text" name="page_title" value="{$formdata['page_title']}" placeholder="添加页面标题" />
        	</div>
        	
        	<div class="m2o-item">
        		<span style="color:#9f9f9f;">提交按钮：</span>
        		<input type="text" name="submit_text" value="{$submit_text}" placeholder="提交按钮文案" />
        	</div>
        	
        	<div class="m2o-item form-dioption-sort"  id="sort-box">
                <label style="color:#9f9f9f;">分类： </label><p style="display:inline-block;" class="sort-label" _multi="feedback_node"> {$formdata['sort_name']}<img class="common-head-drop" src="{$RESOURCE_URL}feedback/drop.png" style="position: relative;left:10px;bottom:2px;" /></p>
				<div class="sort-box-outer"><div class="sort-box-inner"></div></div>
                <input name="sort_id" type="hidden" value="{$sort_id}" id="sort_id" />
            </div>
            {template:unit/publish_for_form, 1, $formdata['column_id']}
        	<div class="m2o-item">
        		<div class="form-dioption-fabu form-dioption-item">
                    <a class="common-publish-button overflow" href="javascript:;" _default="发布至" _prev="发布至：" _type="publish" >发布至</a>
                </div>
        	</div>
        	<div class="m2o-item">
        		<div class="form-time">
                    <a class=" overflow times">开始时间</a>
                     <input class="time start-time date-picker" _time="true" type="text" name="start_time" value="{if ($formdata['start_time'])}{$formdata['start_time']}{/if}">
                    <a class=" overflow times">结束时间</a>
                    <input class="time end-time date-picker" _time="true" type="text" name="end_time" value="{if ($formdata['end_time'])}{$formdata['end_time']}{/if}">
                </div>
        	</div>
           	
         	<div class="m2o-item">
        		<div class="login-box user-limit m2o-limit">
                   <input type="checkbox"  class="limit-ip-box limit-box" {if $is_login} checked{/if} name="is_login" value="1">
                    <a class="" >登录后提交</a>
                    <div class="login-limit-hour limit-hour"  {if $is_login} style="display:block"{/if}>
                    <input type="text" class="is-number" name="userid_limit_time" value="{$userid_limit_time}"><a>小时</a>
                    {code}$userid_limit_num = $userid_limit_num ? $userid_limit_num : 1;{/code}
                    <input type="text" class="num_count is-number" name="userid_limit_num" value="{$userid_limit_num}"><a>次</a>
                     </div>
                </div>
        	</div>
        	<div class="m2o-item">
        		<div class="login-box ip-limit m2o-limit">
                  <input type="checkbox" class="limit-ip-box limit-box" {if $is_ip}checked{/if} name="is_ip" value="1" />
                    <a class="limit-ip" >IP限制</a>
                    <div class="ip-limit-hour limit-hour"  {if $is_ip} style="display:block"{/if}>
                    <input type="text" class="is-number" name="ip_limit_time" value="{$ip_limit_time}"><a>小时</a>
                    {code}$ip_limit_num = $ip_limit_num ? $ip_limit_num : 1;{/code}
                    <input type="text" class="num_count is-number" name="ip_limit_num" value="{$ip_limit_num}"><a>次</a>
                    </div>
                </div>
        	</div>
        	{if $_configs['App_mobile']}
        	<div class="m2o-item">
        		<div class="login-box device-limit m2o-limit">
                  <input type="checkbox" class="limit-device-box limit-box" {if $is_device}checked{/if} name="is_device" value="1" />
                    <a class="limit-device" >设备限制</a>
                    <div class="device-limit-hour limit-hour"  {if $is_device} style="display:block"{/if}>
                    <input type="text" class="is-number" name="device_limit_time" value="{$device_limit_time}"><a>小时</a>
                    {code}$device_limit_num = $device_limit_num ? $device_limit_num : 1;{/code}
                    <input type="text" class="num_count is-number" name="device_limit_num" value="{$device_limit_num}"><a>次</a>
                    </div>
                </div>
        	</div>
        	{/if}
        	
        	{if $_configs['App_verifycode']}
        	<div class="m2o-item openverify">
        		<div class="login-box">
                  <input class="verify hidden_control" type="checkbox" {if $formdata['is_verifycode']}checked{/if} name="is_verifycode" value="1">
                    <a>开启验证码</a>
                </div>
                <div class="verify_type hiddenbox" style="display:{if $formdata['is_verifycode']}block{else}none{/if}">
                    {code}$verify_type = $verify_type[0];{/code}
                    {if $verify_type && is_array($verify_type)}
                	{foreach $verify_type as $k=>$v}
                	<div class="verify_code">
	                	<div class="form-dioption-item verify_title">
	                  		<input type="radio"  {if $formdata['verifycode_type'] == $v['id'] }checked{/if} name="verifycode_type" value="{$v['id']}">
	                  		<a class="" >{$v['name']}</a>
	                	</div>
	                	<img src="run.php?a=get_verify_code&type={$v['id']}"/>
                	</div>
                	{/foreach}
                	{/if}
                </div>
        	</div>
        	{/if}
        	<div class="m2o-item">
        		<div class="login-box">
        			<input class="hidden_control" type="checkbox"  class="hidden_control"  name="is_credit" value="1" {if $formdata['is_credit']==1} checked {/if}/>
        			<a>会员使用积分</a>
         		</div>
         		<div class="hiddenbox" style="display:{if $formdata['is_credit']}block{else}none{/if};">
          		    {code}$credit_type = $credit_type[0];{/code}
          		    {if $credit_type && is_array($credit_type)}
         		    {foreach $credit_type as $credit}
         		    <a style="color:#9f9f9f;">{$credit['title']}</a>
         		    {code} $cd = $formdata[$credit['db_field']];{/code}
                    <input type="text" class="is-number" style="width: 50px;" name="{$credit['db_field']}" value="{$cd}">
                    {/foreach}
                    {/if}
         		</div>
        	</div>
            <div class="m2o-item">
        		<textarea class="feedback-brief" placeholder="添加备注" name="remark">{$remark}</textarea>
        	</div>
        	<div class="m2o-item">
        		<span style="color:#9f9f9f;">网页跳转：</span>
        		<input type="text" name="jump_to" value="{$formdata['jump_to']}" placeholder="网页版跳转地址" />
        	</div>
        	<!-- 功能未实现
        	<div class="m2o-item">
        		<span style="color:#9f9f9f;">套系：</span>
        		<input type="text" name="style" value="{$style}" placeholder="添加套系" />
        	</div>
        	<div class="m2o-item">
        		<span style="color:#9f9f9f;">模板：</span>
        		<input type="text" name="template" value="{$template}" placeholder="添加模板" />
        	</div>
        	 -->
         </div> 
         
         <section class="m2o-m feedback-info m2o-flex-one"></section>
         <!-- 组件管理 -->
         <div class="common-widget">
         	<div class="common-widget-title">
         		<div class="nav-title">常用组件管理</div>
         		<div class="common-widget-hide">x</div>
         	</div>
         	<div class="common-widget-show border">
         		<ul class="widget-show-list common">
         			{code}$common = $common[0];{/code}
         			{foreach $common as $k=>$v}
        			{if $v['is_display'] == 1}
         				<li class="li" title="{$v['name']}" _id="{$v['id']}" _type="{$v['type']}" _display="{$v['is_display']}">
         					<a>{$v['name']}</a>
         					<p class="delete-use">x</p>
         				</li>
         			{/if}
        			{/foreach}
         		</ul>
         		<div class="notice">
         			<span>注：点击此区域内的组件，显示到下方区域，即不再添加组件面板上的常用组件中显示</span>
         		</div>
         	</div>
         	<div class="other-widget">
	         	<div class="other-widget-title">
	         		<div class="other-title">其他常用组件</div>
	         		<div class="other-widget-delete">删除</div>
	         	</div>
	         	<div class="common-widget-show">
	         		<ul class="widget-show-list other">
        			{foreach $common as $k=>$v}
        			{if $v['is_display'] == 0}
         				<li class="li" title="{$v['name']}" _id="{$v['id']}" _type="{$v['type']}" _display="{$v['is_display']}">
         					<a>{$v['name']}</a>
         					<p class="delete-use">x</p>
         				</li>
         			{/if}
        			{/foreach}
	         		</ul>
         		</div>
         		<div class="notice">
         			<span>注：点击此区域内的组件，显示到上方区域，即可在添加组件面板上的常用组件中显示</span>
         		</div>
         	</div>
         </div>
         <!-- end -->
         <div class="m2o-r r-feedback">
         	<ul class="nav">
         		<li class="selected">添加组件</li>
         		<li>编辑组件</li>
         	</ul>
         	<div class="attachinfo">
         		<div class="info-item">
         			<span>标准组件</span>
         			<ul class="standard list">
					{foreach $_configs['form_type'] as $k=>$v}
         				<li _type="{$v['type']}" _tpl="{$v['tpl']}" _style="standard">{$v['title']}</li>
         			{/foreach}
         			</ul>
         		</div>
         		<div class="info-item">
         			<span>固定组件</span>
         			<ul class="fixed list">
					{foreach $_configs['fixed_type'] as $k => $v}
         				<li _type="{$k}"  _tpl="{$v['tpl']}" _style="fixed">{$v['title']}</li>
         			{/foreach}	
         			</ul>
         		</div>
         		<div class="info-item">
         			<span>常用组件</span>
         			<p class="manage">管理</p>
         			<ul class="common-use manage-list">
        			{foreach $common as $k=>$v}
        			{if $v['is_display'] == 1}
         				<li class="li" title="{$v['name']}" _id="{$v['id']}" _type="{$v['type']}"  _display="{$v['is_display']}">
         					<a>{$v['name']}</a>
         				</li>
         			{/if}
        			{/foreach}
         			</ul>
         		</div>
         	<div class="info-item">
         		<span class="attach">注：常用组件是由标准组件和固定组件编辑而成</span>
         	</div>
         	<div class="keyword-items" style="border-top: 1px solid #e7e7e7;">
         		<span class="admin-box">添加该表单的管理员</span>
                <div class="form-dioption-keyword form-dioption-item clearfix" style="position:relative;">
	                <span class="keywords-del"></span>
	                <span class="form-item" _value="添加管理员" id="keywords-box" data-title="点击添加该表单管理员">
	                    <span class="keywords-start">点此添加</span>
	                    <span class="keywords-add">+</span>
	                </span>
	                <input name="admin_user" value="{$admin_user}" id="keywords" style="display:none;"/>
                </div>
         	</div>
         </div>
         	<div class="attachinfo regular" style="display:none"></div>
         	
     </div>
  </div>
</div>
		   <!-- 排序 -->
		   <input type="hidden" name="order" value="" />
           <!-- 标准组件id -->
           <input type="hidden" name="standard[1][id]" value="" />
           <input type="hidden" name="standard[2][id]" value="" />
           <input type="hidden" name="standard[3][id]" value="" />
           <input type="hidden" name="standard[4][id]" value="" />
           <input type="hidden" name="standard[5][id]" value="" />
           <input type="hidden" name="standard[6][id]" value="" />
           <!-- 标准组件标题 -->
           <input type="hidden" name="standard[1][name]" value="" />
           <input type="hidden" name="standard[2][name]" value="" />
           <input type="hidden" name="standard[3][name]" value="" />
           <input type="hidden" name="standard[4][name]" value="" />
           <input type="hidden" name="standard[5][name]" value="" />
           <!-- 标准组件描述 -->
           <input type="hidden" name="standard[1][brief]" value="" />
           <input type="hidden" name="standard[2][brief]" value="" />
           <input type="hidden" name="standard[3][brief]" value="" />
           <input type="hidden" name="standard[4][brief]" value="" />
           <!-- 标准文本限制字符数 -->
           <input type="hidden" name="standard[1][char_num]" value="" />
           <input type="hidden" name="standard[2][char_num]" value="" />
           <!-- 标准组件文本框宽高 -->
           <input type="hidden" name="standard[1][width]" value="" />
           <input type="hidden" name="standard[1][height]" value="" />
           <input type="hidden" name="standard[2][width]" value="" />
           <input type="hidden" name="standard[2][height]" value="" />
           <!-- 标准组件是否设为必填项 -->
           <input type="hidden" name="standard[1][is_require]" value="" />
           <input type="hidden" name="standard[2][is_require]" value="" />
           <input type="hidden" name="standard[3][is_require]" value="" />
           <input type="hidden" name="standard[4][is_require]" value="" />
           <input type="hidden" name="standard[5][is_require]" value="" />
           <input type="hidden" name="standard[6][is_require]" value="" />
           <!-- 标准组件是否设为常用组件 -->
           <input type="hidden" name="standard[1][is_common]" value="" />
           <input type="hidden" name="standard[2][is_common]" value="" />
           <input type="hidden" name="standard[3][is_common]" value="" />
           <input type="hidden" name="standard[4][is_common]" value="" />
           <input type="hidden" name="standard[5][is_common]" value="" />
           <!-- 标准组件文本是否将答案设为表单回收名称 -->
           <input type="hidden" name="standard[1][is_name]" value="" />
           <input type="hidden" name="standard[2][is_name]" value="" />
           <!-- 标准组件选择题和下拉题选项 -->
           <input type="hidden" name="standard[3][option]" value="" />
           <input type="hidden" name="standard[4][option]" value="" />
           <!-- 标准组件选择题类型 单选或是多选 -->
           <input type="hidden" name="standard[3][cor]" value="" />
           <!-- 标准组件选择题多选 最小 最多 只选 -->
           <input type="hidden" name="standard[3][limit_type]" value="" />
           <!-- 标准组件选择题多选 最小 最多 只选项 -->
           <input type="hidden" name="standard[3][op_num]" value="" />
           <!-- 标准组件分割线仅显示分割线或者显示并分页  -->
           <input type="hidden" name="standard[6][spilter]" value="" />
           <!-- 标准组件是否保存到会员信息  -->
           <input type="hidden" name="standard[1][is_member]" value="" />
           <input type="hidden" name="standard[2][is_member]" value="" />
           <input type="hidden" name="standard[3][is_member]" value="" />
           <input type="hidden" name="standard[4][is_member]" value="" />
           <input type="hidden" name="standard[5][is_member]" value="" />
           <!-- 标准组件会员信息字段  -->
           <input type="hidden" name="standard[1][member_field]" value="" />
           <input type="hidden" name="standard[2][member_field]" value="" />
           <input type="hidden" name="standard[3][member_field]" value="" />
           <input type="hidden" name="standard[4][member_field]" value="" />
           <input type="hidden" name="standard[5][member_field]" value="" />
           <!-- 标准组件是否唯一  -->
           <input type="hidden" name="standard[1][is_unique]" value="" />
           <input type="hidden" name="standard[2][is_unique]" value="" />
           <!-- 固定组件id -->
           <input type="hidden" name="fixed[1][id]" value="" />
           <input type="hidden" name="fixed[2][id]" value="" />
           <input type="hidden" name="fixed[3][id]" value="" />
           <input type="hidden" name="fixed[4][id]" value="" />
           <input type="hidden" name="fixed[5][id]" value="" />
           <input type="hidden" name="fixed[6][id]" value="" />
           <!-- 固定组件标题 -->
           <input type="hidden" name="fixed[1][name]" value="" />
           <input type="hidden" name="fixed[2][name]" value="" />
           <input type="hidden" name="fixed[3][name]" value="" />
           <input type="hidden" name="fixed[4][name]" value="" />
           <input type="hidden" name="fixed[5][name]" value="" />
           <input type="hidden" name="fixed[6][name]" value="" />
           <!-- 固定组件描述 -->
           <input type="hidden" name="fixed[1][brief]" value="" />
           <input type="hidden" name="fixed[2][brief]" value="" />
           <input type="hidden" name="fixed[3][brief]" value="" />
           <input type="hidden" name="fixed[4][brief]" value="" />
           <!-- 固定文本限制字符数 -->
           <input type="hidden" name="fixed[1][char_num]" value="" />
           <input type="hidden" name="fixed[2][char_num]" value="" />
           <input type="hidden" name="fixed[3][char_num]" value="" />
           <!-- 固定组件文本框宽高 -->
           <input type="hidden" name="fixed[1][width]" value="" />
           <input type="hidden" name="fixed[1][height]" value="" />
           <input type="hidden" name="fixed[2][width]" value="" />
           <input type="hidden" name="fixed[2][height]" value="" />
           <input type="hidden" name="fixed[3][width]" value="" />
           <input type="hidden" name="fixed[3][height]" value="" />
           <!--固定组件是否设为必填项 -->
           <input type="hidden" name="fixed[1][is_require]" value="" />
           <input type="hidden" name="fixed[2][is_require]" value="" />
           <input type="hidden" name="fixed[3][is_require]" value="" />
           <input type="hidden" name="fixed[4][is_require]" value="" />
           <input type="hidden" name="fixed[5][is_require]" value="" />
           <input type="hidden" name="fixed[6][is_require]" value="" />
           <!-- 固定组件是否设为常用组件 -->
           <input type="hidden" name="fixed[1][is_common]" value="" />
           <input type="hidden" name="fixed[2][is_common]" value="" />
           <input type="hidden" name="fixed[3][is_common]" value="" />
           <input type="hidden" name="fixed[4][is_common]" value="" />
           <input type="hidden" name="fixed[5][is_common]" value="" />
           <input type="hidden" name="fixed[6][is_common]" value="" />
           <!-- 固定组件文本是否将答案设为表单回收名称 -->
           <input type="hidden" name="fixed[1][is_name]" value="" />
           <input type="hidden" name="fixed[2][is_name]" value="" />
           <input type="hidden" name="fixed[3][is_name]" value="" />
           <input type="hidden" name="fixed[4][is_name]" value="" />
           <!-- 固定组件地址 省 城市 县区 详细地址 -->
           <input type="hidden" name="fixed[4][province]" value="" />
           <input type="hidden" name="fixed[4][city]" value="" />
           <input type="hidden" name="fixed[4][county]" value="" />
           <input type="hidden" name="fixed[4][detail]" value="" />
           <!-- 固定组件日期 -->
           <input type="hidden" name="fixed[5][start_time]" value="" />
           <input type="hidden" name="fixed[5][end_time]" value="" />
           <!-- 固定组件时间 -->
           <input type="hidden" name="fixed[6][hour]" value="" />
           <input type="hidden" name="fixed[6][min]" value="" />
           <input type="hidden" name="fixed[6][second]" value="" />
           <!-- 固定组件是否保存到会员信息  -->
           <input type="hidden" name="fixed[1][is_member]" value="" />
           <input type="hidden" name="fixed[2][is_member]" value="" />
           <input type="hidden" name="fixed[3][is_member]" value="" />
           <input type="hidden" name="fixed[4][is_member]" value="" />
           <input type="hidden" name="fixed[5][is_member]" value="" />
           <input type="hidden" name="fixed[6][is_member]" value="" />
           <!-- 固定组件会员信息字段  -->
           <input type="hidden" name="fixed[1][member_field]" value="" />
           <input type="hidden" name="fixed[2][member_field]" value="" />
           <input type="hidden" name="fixed[3][member_field]" value="" />
           <input type="hidden" name="fixed[4][member_field]" value="" />
           <input type="hidden" name="fixed[5][member_field]" value="" />
           <input type="hidden" name="fixed[6][member_field]" value="" />
           <!-- 标准组件是否唯一  -->
           <input type="hidden" name="fixed[1][is_unique]" value="" />
           <input type="hidden" name="fixed[2][is_unique]" value="" />
           <input type="hidden" name="fixed[3][is_unique]" value="" />
            
</form>


<!-- 组件列表 -->     
<script type="text/x-jquery-tmpl" id="one-text-tpl">
<div class="detail one-text" title="可拖动排序" _id="{{= id}}" _sign="{{= type}}" _name="{{= name}}" _brief="{{= brief}}" _type="{{= form_type}}" _tpl="rows-tpl" _required="{{= is_required}}" _width="{{= width}}" _height="{{= height}}" _num="{{= char_num}}" _common="{{= is_common}}" _back="{{= is_name}}" _cor="{{= cor}}" _limit="{{= limit_type}}" _op="{{= op_num}}" _spilter="{{= spilter}}" _member="{{= is_member}}" _field="{{= member_field}}" _unique="{{= is_unique}}">
     <span class="delete">x</span>
     <div class="detail-text">
         <span class="title">{{= name}}</span><p class="symbol mark" style="{{if is_required == 1}} display:block{{/if}}">*</p><p class="symbol back" style="{{if is_name == 1}} display:block{{/if}}">(已设为表单回收名称)</p>
     </div>
     <div class="detail-text">
         <div class="one-text-input text" style="width:{{= width}}px;height:{{= height}}px"></div>
         <p class="character">(<a class="num">{{= char_num}}</a>个字符)</p>
     </div>
     <div class="detail-brief">{{= brief}}</div>
</div>
</script>     
<script type="text/x-jquery-tmpl" id="multiline-text-tpl"> 
<div class="detail multiline-text" title="可拖动排序" _id="{{= id}}" _sign="{{= type}}" _name="{{= name}}" _brief="{{= brief}}" _type="{{= form_type}}" _tpl="rows-tpl" _required="{{= is_required}}" _width="{{= width}}" _height="{{= height}}" _num="{{= char_num}}" _common="{{= is_common}}" _back="{{= is_name}}" _cor="{{= cor}}" _limit="{{= limit_type}}" _op="{{= op_num}}" _spilter="{{= spilter}}" _member="{{= is_member}}" _field="{{= member_field}}" _unique="{{= is_unique}}">
     <span class="delete">x</span>
 <div class="detail-text">
     <span class="title">{{= name}}</span><p class="symbol mark" style="{{if is_required == 1}} display:block{{/if}}">*</p><p class="symbol back" style="{{if is_name == 1}} display:block{{/if}}">(已设为表单回收名称)</p>
</div>
	 <div class="detail-text">
         <div class="multiline-text text" style="width:{{= width}}px;height:{{= height}}px"></div>
         <p class="character">(<a class="num">{{= char_num}}</a>个字符)</p>
     </div>
     <div class="detail-brief">{{= brief}}</div>
</div>   
</script> 
<script type="text/x-jquery-tmpl" id="more-choice-tpl"> 
<div class="detail" title="可拖动排序" _id="{{= id}}" _sign="{{= type}}" _options="{{each options}}{{= $value}},{{/each}}" _name="{{= name}}" _brief="{{= brief}}" _type="{{= form_type}}" _tpl="choice-question-tpl" _required="{{= is_required}}" _width="{{= width}}" _height="{{= height}}" _num="{{= char_num}}" _common="{{= is_common}}" _back="{{= is_name}}" _cor="{{= cor}}" _limit="{{= limit_type}}" _op="{{= op_num}}" _spilter="{{= spilter}}" _member="{{= is_member}}" _field="{{= member_field}}">
     <span class="delete">x</span>
     <div class="detail-text">
         <span class="title">{{= name}}</span><p class="symbol mark" style="{{if is_required == 1}} display:block{{/if}}">*</p><p class="choice">({{if cor == 1}}单选{{else}}多选:{{/if}} {{if cor !=1}}{{if limit_type==2}}最多选{{else limit_type == 1}}最少选{{else limit_type==3}}只选{{/if}}{{= op_num}}项{{/if}})</p>
     </div>
     <div class="detail-brief">{{= brief}}</div>
     <div class="option option-box">
		{{each options}}
         <div class="detail-text select">
         	<input type="checkbox" /> <p class="option">{{= $value}}</p>
         </div>
		{{/each}}
     </div>
</div>
</script>  
<script type="text/x-jquery-tmpl" id="choices-tpl">
<div class="detail-text select">
     <input type="checkbox" /> <p class="option">{{= option}}</p>
</div>
</script>    
<script type="text/x-jquery-tmpl" id="select-tpl"> 
<div class="detail" title="可拖动排序" _id="{{= id}}" _sign="{{= type}}" _options="{{each options}}{{= $value}},{{/each}}" _name="{{= name}}" _brief="{{= brief}}" _type="{{= form_type}}" _tpl="choice-question-tpl" _required="{{= is_required}}" _width="{{= width}}" _height="{{= height}}" _num="{{= char_num}}" _common="{{= is_common}}" _back="{{= is_name}}" _cor="{{= cor}}" _limit="{{= limit_type}}" _op="{{= op_num}}" _spilter="{{= spilter}}" _member="{{= is_member}}" _field="{{= member_field}}">
    <span class="delete">x</span>
    <div class="detail-text">
        <span class="title">{{= name}}</span><p class="symbol mark" style="{{if is_required == 1}} display:block{{/if}}">*</p>
    </div>
    <div class="detail-brief">{{= brief}}</div>
    <select class="pull-down select">
		{{each options}}
        <option class="option">{{= $value}}</option>
		{{/each}}
    </select>
</div>
</script>     
<script type="text/x-jquery-tmpl" id="upload-tpl">      
<div class="detail" title="可拖动排序" _id="{{= id}}" _sign="{{= type}}" _name="{{= name}}" _brief="{{= brief}}" _type="{{= form_type}}" _tpl="cattachment-tpl" _required="{{= is_required}}" _width="{{= width}}" _height="{{= height}}" _num="{{= char_num}}" _common="{{= is_common}}" _back="{{= is_name}}" _cor="{{= cor}}" _limit="{{= limit_type}}" _op="{{= op_num}}" _spilter="{{= spilter}}" _member="{{= is_member}}" _field="{{= member_field}}">
     <span class="delete">x</span>
     <div class="detail-text">
         <span class="title">{{= name}}</span><p class="symbol mark" style="{{if is_required == 1}} display:block{{/if}}">*</p>
     </div>
     <div class="upload-file">
        上传文件小于5M	
     </div>
     <p class="uploadfile">+</p>
</div>      
</script>      
<script type="text/x-jquery-tmpl" id="date-box-tpl"> 
<div class="detail" title="可拖动排序" _id="{{= id}}" _sign="{{= type}}" _start="{{= start_time}}" _end="{{= end_time}}" _name="{{= name}}" _brief="{{= brief}}" _type="{{= form_type}}" _tpl="date-tpl" _required="{{= is_required}}" _width="{{= width}}" _height="{{= height}}" _num="{{= char_num}}" _common="{{= is_common}}" _back="{{= is_name}}" _cor="{{= cor}}" _limit="{{= limit_type}}" _op="{{= op_num}}" _spilter="{{= spilter}}" _member="{{= is_member}}" _field="{{= member_field}}">
    <span class="delete">x</span>
    <div class="detail-text">
         <span class="title">{{= name}}</span><p class="symbol mark" style="{{if is_required == 1}} display:block{{/if}}">*</p>
    </div>
    <input type="text" class="date" />
</div>
</script> 
<script type="text/x-jquery-tmpl" id="address-box-tpl">
<div class="detail" title="可拖动排序" _id="{{= id}}" _province="{{each element}}{{if $value['id'] == 8}}{{= $value['id']}}{{/if}}{{/each}}" _city="{{each element}}{{if $value['id'] == 9}}{{= $value['id']}}{{/if}}{{/each}}" _county="{{each element}}{{if $value['id'] == 10}}{{= $value['id']}}{{/if}}{{/each}}" _detail="{{each element}}{{if $value['id'] == 11}}{{= $value['id']}}{{/if}}{{/each}}" _sign="{{= type}}" _name="{{= name}}" _brief="{{= brief}}" _type="{{= form_type}}" _tpl="address-tpl" _required="{{= is_required}}" _width="{{= width}}" _height="{{= height}}" _num="{{= char_num}}" _common="{{= is_common}}" _back="{{= is_name}}" _cor="{{= cor}}" _limit="{{= limit_type}}" _op="{{= op_num}}" _spilter="{{= spilter}}" _member="{{= is_member}}" _field="{{= member_field}}">
     <span class="delete">x</span>
 <div class="detail-text">
     <span class="title">{{= name}}</span><p class="symbol mark" style="{{if is_required == 1}} display:block{{/if}}">*</p><p class="symbol back" style="{{if is_name == 1}} display:block{{/if}}">(已设为表单回收名称)</p>
 </div>
	 <div class="detail-text detail-info">
		{{each element}}
		{{if $value['id'] != 11}}
         <select class="address">
         	<option>请选择</option>
         </select>
		{{/if}}
		{{/each}}
     </div>
     <div class="more-address" style="{{each element}}{{if $value['id'] == 11}}display:block{{/if}}{{/each}}">
    	 <input type="text" class="one-text-input" placeholder="请填写详细地址"/>
     </div> 
	
</div> 
</script>
<script type="text/x-jquery-tmpl" id="time-box-tpl">
<div class="detail" title="可拖动排序" _id="{{= id}}" _sign="{{= type}}" _hour="{{each element}}{{if $value['id'] == 1}}{{= $value['id']}}{{/if}}{{/each}}" _min="{{each element}}{{if $value['id'] == 2}}{{= $value['id']}}{{/if}}{{/each}}" _second="{{each element}}{{if $value['id'] == 3}}{{= $value['id']}}{{/if}}{{/each}}" _name="{{= name}}" _brief="{{= brief}}" _type="{{= form_type}}" _tpl="time-tpl" _required="{{= is_required}}" _width="{{= width}}" _height="{{= height}}" _num="{{= char_num}}" _common="{{= is_common}}" _back="{{= is_name}}" _cor="{{= cor}}" _limit="{{= limit_type}}" _op="{{= op_num}}" _spilter="{{= spilter}}" _member="{{= is_member}}" _field="{{= member_field}}">
     <span class="delete">x</span>
 <div class="detail-text">
     <span class="title">{{= name}}</span><p class="symbol mark" style="{{if is_required == 1}} display:block{{/if}}">*</p>
</div>
	 <div class="detail-text detail-info">
	{{each element}}
     <select class="address">
        <option>请选择</option>
     </select>
	{{/each}}
     </div>
</div> 
</script>
<script type="text/x-jquery-tmpl" id="divide-box-tpl">
<div class="detail spilter-line {{if  spilter == 1}}pagehide{{/if}}" title="可拖动排序" _id="{{= id}}" _sign="{{= type}}" _name="{{= name}}" _brief="{{= brief}}" _type="{{= form_type}}" _tpl="divide-line-tpl" _required="{{= is_required}}" _width="{{= width}}" _height="{{= height}}" _num="{{= char_num}}" _common="{{= is_common}}" _back="{{= is_name}}" _cor="{{= cor}}" _limit="{{= limit_type}}" _op="{{= op_num}}" _spilter="{{= spilter}}">
   <span class="delete">x</span>
   <div style="display:-webkit-box">
		<div class="line-divide" style="{{if  spilter == 2}}display: -webkit-box;{{/if}}">
       		<div style=" margin:10px;width:500px;height:1px;border-bottom:1px dashed #535353;"></div>
       		<span>第<span class="index">1</span>页</span>
       		<div style=" margin:10px;width:45px;height:1px;border-bottom:1px dashed #535353;"></div>
		</div>
		<div class="line-divide" style="{{if  spilter == 1}}display: -webkit-box;{{/if}}">
			<div style=" margin:10px;width:590px;height:1px;border-bottom:1px dashed #535353;"></div>
		</div>
   </div>
</div> 
</script>     
      
<!-- 编辑组件 -->     
<script type="text/x-jquery-tmpl" id="divide-line-tpl">
<div class="r-contain">
	<div class="question-info">
	   <div class="r-item">
	       <input type="radio" name="divideline" value="1" {{if spilter==1}}checked{{/if}}/>
	       <span class="r-detail">仅显示分割线</span>
	   </div>
	   <div class="r-item">
	       <input type="radio" name="divideline"" value="2" {{if spilter==2}}checked{{/if}}/>
	       <span class="r-detail">显示分割线，并分页</span>
	   </div>
	</div>
	   <!--<div class="r-item required">
	       <input type="checkbox" name="required" {{if required==1}}checked{{/if}}/>
	       <span class="r-detail">设为必填项</span>
	   </div>-->
</div>
<input type="button" class="save" value="保存" >
</script>
<script type="text/x-jquery-tmpl" id="rows-tpl">
<div class="r-contain">
     <div class="question-info">
         <div class="item">
         	<span class="title">组件标题</span>
         	<input type="text" name="widget-title" value="{{= name}}"/>
         </div>
     </div>
     <div class="question-info">
         <div class="item">
         	<span class="title">组件描述</span>
         	<textarea name="brief">{{= brief}}</textarea>
         </div>
     </div>
     <div class="question-info" style="display: -webkit-box;">
         <div class="item w70">
         	<span class="title">组件宽高</span>
         	<div class="length mt">
         		<span>W</span>
         		<input type="text" class="is-number" name="width"  value="{{= width}}"/>
         		<span>H</span>
         		<input type="text" class="is-number" name="height" value="{{= height}}"/ >
         	</div>
         </div>
         <div class="item limit">
         	<span class="title">限制字符数</span>
         	<input type="text" name="limit" class="mt is-number" value="{{= char_num}}"/>
         </div>
      </div>
      <div class="question-info">
	      <div class="r-item member">
		     <input type="checkbox" name="member" {{if member == 1}}checked{{/if}}/>
		     <span class="r-detail">保存到会员信息</span>
			  <span class="member_field"  {{if member == 0}}style="display:none"{{/if}}>
				<select name="member_field" >
 				{foreach $extensions as $k=>$v}
 	    	    <option 
				{{if '{$v['field']}' == field}}selected
				{{else sign =='fixed' && type==1 && '{$v['field']}' == 'realname'}}selected
				{{else sign =='fixed' && type==2 && '{$v['field']}' == 'email'}}selected
				{{else sign =='fixed' && type==3 && '{$v['field']}' == 'mobile'}}selected
				{{/if}} 
				value="{$v['field']}">{$v['field_name']}</option>
				{/foreach}
				</select>
			  </span>
 		  </div>
     </div>
      <div class="question-info">
	     <div class="r-item">
		     <input type="checkbox" name="required" {{if required == 1}}checked{{/if}}/>
		     <span class="r-detail">设为必填项</span>
		</div>
      </div>
      <div class="question-info">
	     <div class="r-item">
		     <input type="checkbox" name="unique" {{if unique == 1}}checked{{/if}}/>
		     <span class="r-detail">是否唯一</span>
		</div>
      </div>
      <div class="question-info">
	      <div class="r-item">
		     <input type="checkbox" name="common" {{if common == 1}}checked{{/if}}/>
		     <span class="r-detail">保存为常用组件</span>
		  </div>
      </div>
      <div class="question-info">
	      <div class="r-item">
		      <input type="checkbox" name="is_name" {{if back == 1}}checked{{/if}}/>
		      <span class="r-detail">将答案设置为表单回收名称</span>
		   </div>
      </div>
</div>
<input type="button" class="save" value="保存" >
</script>
<script type="text/x-jquery-tmpl" id="choice-question-tpl">
<div class="r-contain">
     <div class="question-info">
         <div class="item">
         	<span class="title">组件标题</span>
         	<input type="text" name="widget-title" value="{{= name}}"/>
         </div>
     </div>
     <div class="question-info">
         <div class="item">
         	<span class="title">组件描述</span>
         	<textarea name="brief">{{= brief}}</textarea>
         </div>
     </div>
     <div class="question-info">
     	 <span class="title">选项设置</span>
		{{each options}}
         <div class="option">
         	<input type="text" value="{{= $value}}"/>
         	<p class="add">+</p>
         	<p class="delete">x</p>
         </div>
		{{/each}}
      </div>
	{{if cor != 0}}
      <div class="question-info">
      	 <div class="r-item">
		     <input type="radio" name="options" {{if cor == 1}}checked{{/if}} value="1"/>
		     <span class="r-detail">单选</span>
		 </div>
		  <div class="r-item">
		     <input type="radio" name="options" {{if cor != 1}}checked{{/if}} value="2"/>
		     <span class="r-detail">多选</span>
		     <div class="select op-choose">
		     	<select>
		     		<option {{if limit == 1}}selected{{/if}} value="1">最少选</option>
		     		<option {{if limit == 2}}selected{{/if}} value="2">最多选</option>
		     		<option {{if limit == 3}}selected{{/if}} value="3">只选</option>
		     	</select>
		     	<input type="text" class="is-number" name="op_num" value="{{= op}}"/>
		     	<span class="unit">项</span>
		     </div>
		 </div>
      </div>
{{/if}}
      <div class="question-info">
	      <div class="r-item member">
		     <input type="checkbox" name="member" {{if member == 1}}checked{{/if}}/>
		     <span class="r-detail">保存到会员信息</span>
			  <span class="member_field" {{if member == 0}}style="display:none"{{/if}}>
				<select name="member_field" >
	 			{foreach $extensions as $k=>$v}
	  		    <option {{if '{$v['field']}' == field}}selected{{/if}} value="{$v['field']}">{$v['field_name']}</option>
				{/foreach}
				</select>
			  </span>
 		  </div>
     </div>
      <div class="question-info">
	     <div class="r-item">
		     <input type="checkbox" name="required" {{if required == 1}}checked{{/if}}/>
		     <span class="r-detail">设为必填项</span>
		</div>
      </div>
      <div class="question-info">
	      <div class="r-item">
		     <input type="checkbox" name="common" {{if common == 1}}checked{{/if}}/>
		     <span class="r-detail">保存为常用组件</span>
		  </div>
      </div>
</div>
<input type="button" class="save" value="保存" >
</script>
<script type="text/x-jquery-tmpl" id="cattachment-tpl">
<div class="r-contain">
     <div class="question-info">
         <div class="item">
         	<span class="title">组件标题</span>
         	<input type="text" name="widget-title" value="{{= name}}"/>
         </div>
     </div>
      <div class="question-info">
	      <div class="r-item member">
		     <input type="checkbox" name="member" {{if member == 1}}checked{{/if}}/>
		     <span class="r-detail">保存到会员信息</span>
			  <span class="member_field"  {{if member == 0}}style="display:none"{{/if}}>
				<select name="member_field" >
 				{foreach $extensions as $k=>$v}
     		    <option {{if '{$v['field']}' == field}}selected{{else '{$v['field']}' == 'avatar'}}selected{{/if}} value="{$v['field']}">{$v['field_name']}</option>
				{/foreach}
				</select>
			  </span>
		  </div>
      </div>
      <div class="question-info">
	     <div class="r-item">
		     <input type="checkbox" name="required" {{if required == 1}}checked{{/if}}/>
		     <span class="r-detail">设为必填项</span>
		</div>
      </div>
      <div class="question-info">
	      <div class="r-item">
		     <input type="checkbox" name="common" {{if common==1}}checked{{/if}}/>
		     <span class="r-detail">保存为常用组件</span>
		  </div>
      </div>
</div>
<input type="button" class="save" value="保存" >
</script>
<script type="text/x-jquery-tmpl" id="address-tpl">
 <div class="r-contain">
     <div class="question-info">
         <div class="item">
         	<span class="title">组件标题</span>
         	<input type="text" name="widget-title" value="{{= name}}"/>
         </div>
     </div>
     <div class="question-info">
	     <div class="r-item">
	     	<div class="r-item p20 r_label">
		     	<input type="checkbox" name="province" value="8" {{if province}} checked {{/if}}/>
		    	<span class="r-detail">省</span>
		    </div>
		    <div class="r-item p20 r_label">
		     	<input type="checkbox" name="city" value="9" {{if city}} checked {{/if}} />
		    	<span class="r-detail">城市</span>
		    </div>
		    <div class="r-item p20 r_label">
		     	<input type="checkbox" name="county" value="10" {{if county}} checked {{/if}}/>
		    	<span class="r-detail">区县</span>
		    </div>
		</div>
      </div>
    <div class="question-info">
	     <div class="r-item">
		     <input type="checkbox" name="detail" value="11" {{if detail}} checked {{/if}}/>
		     <span class="r-detail">填写详细地址</span>
		</div>
      </div>
      <div class="question-info">
	      <div class="r-item member">
		     <input type="checkbox" name="member" {{if member == 1}}checked{{/if}}/>
		     <span class="r-detail">保存到会员信息</span>
 				  <div class="member_field"  {{if member == 0}}style="display:none"{{/if}}>
       		   {code}$arr = array('8'=>'省','9'=>'城市','10'=>'区县','11'=>'详细地址','-1'=>'完整信息');{/code}
				{foreach $arr as $key=>$name}
				  <div class="addr">
    			    <input type="checkbox" {{if  field_addr['{$key}']}}checked{{/if}} name="is_field" value="{$key}"/>{$name}
					<select name="member_field" >
 					{foreach $extensions as $k=>$v}
	   			    <option 
					{{if  field_addr['{$key}'] == '{$v['field']}' }}selected
					{{else '{$key}' == '8' && '{$v['field']}' == 'prov'}}selected
					{{else '{$key}' == '9' && '{$v['field']}' == 'city'}}selected
					{{else '{$key}' == '10' && '{$v['field']}' == 'dist'}}selected
					{{else '{$key}' == '11' && '{$v['field']}' == 'address'}}selected
					{{else '{$key}' == '-1' && '{$v['field']}' == 'address'}}selected
					{{/if}} 
					value="{$v['field']}">{$v['field_name']}</option>
					{/foreach}
					</select>
		  </div>
		{/foreach}
</div>
		  </div>
      </div>
      <div class="question-info">
	     <div class="r-item">
		     <input type="checkbox" name="required" {{if required==1}}checked{{/if}}/>
		     <span class="r-detail">设为必填项</span>
		</div>
      </div>
      <div class="question-info">
	      <div class="r-item">
		     <input type="checkbox" name="common" {{if common==1}}checked{{/if}}/>
		     <span class="r-detail">保存为常用组件</span>
		  </div>
      </div>
      <div class="question-info">
	      <div class="r-item">
		      <input type="checkbox" name="is_name" {{if back==1}}checked{{/if}}/>
		      <span class="r-detail">将答案设置为表单回收名称</span>
		   </div>
      </div>
</div>
<input type="button" class="save" value="保存" >
</script>
<script type="text/x-jquery-tmpl" id="date-tpl">
  <div class="r-contain">
     <div class="question-info">
         <div class="item">
         	<span class="title">组件标题</span>
         	<input type="text" name="widget-title" value="{{= name}}"/>
         </div>
     </div>
     <div class="question-info">
         <div class="item">
         	<span class="title">数据年限</span>
         	<div class="r-item">
         	<div class="select limit-year">
         		<input type="text" class="is-number" name="start" value="{{= start}}"/>
         		<span class="year">年</span>
         	</div>
         	<span class="till">至</span>
         	<div class="select limit-year">
         		<input type="text" class="is-number" name="end" value="{{= end}}"/>
         		<span class="year">年</span>
         	</div>
         	</div>
         </div>
     </div>
      <div class="question-info">
	      <div class="r-item member">
		     <input type="checkbox" name="member" {{if member == 1}}checked{{/if}}/>
		     <span class="r-detail">保存到会员信息</span>
			  <span class="member_field"  {{if member == 0}}style="display:none"{{/if}}>
				<select name="member_field" >
 				{foreach $extensions as $k=>$v}
 	    	    <option {{if '{$v['field']}' == field}}selected{{else '{$v['field']}' == 'birthday'}}selected{{/if}} value="{$v['field']}">{$v['field_name']}</option>
				{/foreach}
				</select>
			  </span>
		  </div>
      </div>
      <div class="question-info">
	     <div class="r-item">
		     <input type="checkbox" name="required" {{if required==1}}checked{{/if}}/>
		     <span class="r-detail">设为必填项</span>
		</div>
      </div>
      <div class="question-info">
	      <div class="r-item">
		     <input type="checkbox" name="common" {{if common==1}}checked{{/if}}/>
		     <span class="r-detail">保存为常用组件</span>
		  </div>
      </div>
</div>
<input type="button" class="save" value="保存" >
</script>
<script type="text/x-jquery-tmpl" id="time-tpl">
 <div class="r-contain">
     <div class="question-info">
         <div class="item">
         	<span class="title">组件标题</span>
         	<input type="text" name="widget-title" value="{{= name}}"/>
         </div>
     </div>
     <div class="question-info">
	     <div class="r-item">
	     	<div class="r-item p20 r_label">
		     	<input type="checkbox" name="hour" value="1" {{if hour}} checked {{/if}}/>
		    	<span class="r-detail">时</span>
		    </div>
		    <div class="r-item p20 r_label">
		     	<input type="checkbox" name="min" value="2" {{if min}} checked {{/if}}/>
		    	<span class="r-detail">分</span>
		    </div>
		    <div class="r-item p20 r_label">
		     	<input type="checkbox" name="second" value="3" {{if second}} checked {{/if}}/>
		    	<span class="r-detail">秒</span>
		    </div>
		</div>
      </div>
      <div class="question-info">
	      <div class="r-item member">
		     <input type="checkbox" name="member" {{if member == 1}}checked{{/if}}/>
		     <span class="r-detail">保存到会员信息</span>
			  <span class="member_field"  {{if member == 0}}style="display:none"{{/if}}>
				<select name="member_field" >
				{if $extensions && is_array($extensions)}
 				{foreach $extensions as $k=>$v}
 	    	    <option {{if '{$v['field']}' == field}}selected{{/if}} value="{$v['field']}">{$v['field_name']}</option>
				{/foreach}
				{/if}
				</select>
			  </span>
		  </div>
      </div>
    
      <div class="question-info">
	     <div class="r-item">
		     <input type="checkbox" name="required" {{if required==1}}checked{{/if}}/>
		     <span class="r-detail">设为必填项</span>
		</div>
      </div>
      <div class="question-info">
	      <div class="r-item">
		     <input type="checkbox" name="common" {{if common==1}}checked{{/if}}/>
		     <span class="r-detail">保存为常用组件</span>
		  </div>
      </div>
     
</div>
<input type="button" class="save" value="保存" >
</script>
<script type="text/javascript">
        $(".hidden_control").click(function(){
		var ob = $(this).parent().parent();
		if('checked' == $(this).attr("checked"))
		{
			ob.find(".hiddenbox").css('display','block');
		}
		else
		{
			ob.find(".hiddenbox").css('display','none');
		}
		})
</script>

{template:foot}
