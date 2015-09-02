<?php ?>
{template:head}
{css:2013/form}
{css:2013/button}
{css:hg_sort_box}
{css:member_form}
{js:hg_preview}
{js:hg_sort_box}
{js:ajax_upload}
{js:common/common_form}
{js:2013/ajaxload_new}
{js:page/page}
{js:members/member_form}
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
{/code}
{if $a}
	{code}
		$action = $a;
	{/code}
{/if}

{if is_array($formdata)}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
{code}
//hg_pre( $get_credits_type );
{/code}
<script src="http://libs.baidu.com/swfobject/2.2/swfobject.js" type="text/javascript"></script>
<body>
<form name="editform" action="./run.php?mid={$_INPUT['mid']}" method="post" enctype='multipart/form-data' class="members_form" data-id="{$id}" data-btn="{$RESOURCE_URL}" data-swf="{$SCRIPT_URL}">
   {template:unit/bg_picture}
    <header class="m2o-header">
    <div class="m2o-inner">
        <div class="m2o-title m2o-flex m2o-flex-center">
            <h1 class="m2o-l">{$optext}会员信息</h1>
            <div class="m2o-m m2o-flex-one">
                 <input placeholder="输入会员名称" name="member_name" class="m2o-m-title need-word-count" {if $member_name&&!$isNameUpdate}disabled="disabled"{/if} title="{$member_name}" required value="{$member_name}" />
            </div>
            <div class="m2o-btn m2o-r">
                <input type="submit" value="保存信息" class="m2o-save" name="sub" id="sub" data-target="run.php?mid={$_INPUT['mid']}&a={$ac}" data-method="{$ac}"/>
                <span class="m2o-close option-iframe-back"></span>
            </div>
        </div>
      </div>  
    </header>
    <div class="m2o-inner">
    <div class="m2o-main m2o-flex">
        <div class="m2o-l m2o-aside">
        	<div class="m2o-item img-info" style="position:relative">
        		<div class="indexpic icon">
        			{if $avatar}
						<img src="{$avatar}" ／>
					{/if}
					<span style="{if $avatar}display:none;{/if}">点击上传头像</span>
                 </div>
                 <input type="file" name="avatar" style="display:none;">
        	</div>
        	{if  $formdata['iusname']}
        	<div class="m2o-item">
        	 	<label>用户系统 :</label>
        	 	<span class="uc_status">{$formdata['iusname']}</span>
        	</div>
        	{else if !$id}
        	<div class="m2o-item">
        	<label>用户系统 :</label>
        	<input type="text" placeholder="请填写identifier,可选" name="identifier" value="{$identifier}" />
        	</div>
        	{/if}
        	<div class="m2o-item">
        	 	<label>会员类型 :</label>
        	 	<div class="member_type">
        	 	{if $id}
        	 		{$formdata['type_name']}
        	 	{else}
	        	 	{foreach $_configs['member_admin_type'] as $type_id=>$member_type}
			         	<input type="radio" class="type" name="member_type" value="{$member_type['type']}">
			         	<span>{$member_type['type_name']}</span>
			    	{/foreach}
        	 	{/if}
        	 	</div>
        	</div>
        	{if $id && ! $formdata['identifier']}
        	<div class="m2o-item">
        	 	<label>UC状态 :</label>
        	 	<span class="uc_status">{if $formdata['inuc'] >0 }已同步{else}未同步{/if}</span>
        	</div>
        	{/if}
        </div>
        <section class="m2o-m m2o-flex-one">
        	<div class="m2o-item nav">
        		<a class="member-title active">基本信息</a>
            	<a class="member-title">扩展信息</a>
            	<a class="member-title">颁发勋章</a>
            	{if $id}
            	<a class="member-title">绑定信息</a>
            	<a class="member-title">积分日志</a>
            	<a class="member-title">其他</a>
            	{/if}
            </div>
            <div class="member-info" style="display:block;">
            	<div class="m2o-item cut-off">
	        		<label class="title">密码: </label>
	        		<div class="info">
	        			<input type="password" name="password"  /><span class="tip">*以防密码泄漏,不显示密码</span>
	        		</div>
	        	</div>
	        	<div class="m2o-item cut-off">
	        		<label class="title">昵称: </label>
	        		<div class="info">
	        			<input type="text" placeholder="不填写则默认为用户名" name="nick_name" value="{$nick_name}" /><span class="tip">不填写则默认为用户名</span>
	        		</div>
	        	</div>
	        	<div class="m2o-item cut-off">
	        		<label class="title">手机号码: </label>
	        		<div class="info">
	        			<input type="text" name="mobile" value="{$mobile}" /><span class="tip"></span>
	        		</div>
	        	</div>
	        	<div class="m2o-item cut-off">
	        		<label class="title">邮箱: </label>
	        		<div class="info">
	        			<input type="email" name="email" value="{$email}" /><span class="tip"></span>
	        		</div>
	        	</div>
	        	{if $id}
            	<div class="m2o-item cut-off">
	        		<label class="title">总积分: </label>
	        		<div class="info">
	        			<input type="text" name="credits" value="{$credits}" disabled="disabled"/><span class="tip">*不可更改,系统自动计算.</span>
	        		</div>
	        	</div>
	        	{/if}
	        	{if $id&&$_configs['isFrozen']}
            	<div class="m2o-item cut-off">
	        		<label class="title">冻结积分: </label>
	        		<div class="info">
	        			<input type="text" name="frozenCredit" value="{$frozenCredit}" disabled="disabled"/><span class="tip">*不可更改(由用户购物支付成功后增加，订单完成或者取消减少).</span>
	        		</div>
	        	</div>
	        	{/if}
	        	{if $get_credits_type&&is_array($get_credits_type)}
				{foreach $get_credits_type as $k=>$v}
				<div class="m2o-item cut-off">
	        		<label class="title">{$v['title']}：</label>
	        		<div class="info">
	        			<input type="text" name="credit[{$v['db_field']}]" value="{$credit[$v['db_field']]}"/><span class="tip">{if $member_id}*此更改将影响总积分{if $v['is_update']},并且影响用户等级；注意：* 不允许为负数{/if}{else} {if $v['is_update']}注意：* 不允许为负数{/if} * 留空则按默认增加{/if}.</span>
	        		</div>
	        	</div>
				{/foreach}
				{/if}
				<div class="m2o-item cut-off">
	        		<label class="title">用户组: </label>
	        		<div class="info">
	        			{code}
	                    $group_source = array(
	                        'class' 	=> 'down_list i',
	                        'show' 		=> 'count_show',
	                        'state' 	=> 	0, /*0--正常数据选择列表，1--日期选择*/
	                        'is_sub'	=>	1,
	                        'width'		=> 204
	                    );
	                     $group_sort[0] = '默认分组';
	                     if($groupname)
	                     {
	                     	$group_sort[$gid] = $groupname;
	                     }
	                     if($gid&&$groupname)
	                   	 {
	                    	$group_default = $gid;
	                   	 }
	                   	 else
	                   	 {
	                     	$group_default = 0;
	                   	 }
	                    foreach( $member_group as $k =>$v)
	                    {
	                    	$groupid = $v['id'];
	                   	 	if($groupid!=$gid)
	                     	$group_sort[$groupid] = $v['name'];	                       
	                    }
	                {/code}
	                {template:form/search_source,groupid,$group_default,$group_sort,$group_source}
	                <span class="tip">*默认分组为按照会员总积分自动分配,自定义分组仅列出管理员授权类型组,此组不受积分影响.</span>
	        		</div>
	        	</div>
	        	<div class="m2o-item cut-off">
	        		<label class="title">组有效期:</label>
	        		<div class="info">
	        			<input type="text" name="groupexpiry" class="date-picker" value="{if $groupexpiry}{$groupexpiry}{/if}"/><span class="tip">*留空则不限制时间,此项仅授权组有效,非授权组设置也无效.</span>
	        		</div>
	        	</div>
	        	{if $id}
	        	<div class="m2o-item cut-off">
	        		<label class="title">用户等级:</label>
	        		<div class="info">
	        			<input type="text" name="graname" value="{$graname}" disabled="disabled"/><span class="tip">*不可修改,根据经验自动变更.</span>
	        		</div>
	        	</div>
	        	{/if}
	        	<div class="m2o-item cut-off">
	        		<label class="title">黑名单:</label>
	        		<div class="info">
	        			<input type="radio" class="blacklist" name="blacklist" {if $blacklist['isblack']} checked="checked"{/if}  value ="1"><span class="radio-choose">是</span>
						<input type="radio" class="blacklist" name="blacklist" {if !$blacklist['isblack']} checked="checked"{/if}  value ="0"><span class="radio-choose">否</span>
	        		</div>
	        	</div>
	        	<div class="m2o-item cut-off" style="{if !$blacklist['isblack']}display:none;{/if}">
	        		<label class="title">名单过期:</label>
	        		<div class="info">
	        			<input type="text" name="isblack" class="date-picker" value="{if $blacklist['deadline']>0}{$blacklist['deadline']}{/if}" /><span class="tip">*黑名单有效期,留空则永久有效.</span>
	        		</div>
	        	</div>
	        	<div class="m2o-item cut-off">
	        		<label class="title">个性签名:</label>
	        		<div class="info">
	        			<textarea name="signature" id="signature"  cols="80" rows="4" />{$signature}</textarea>
	        		</div>
	        	</div>
            </div>
            <div class="member-info">
            	{foreach $member_extension as $k => $v}
					{code}$value ='';{/code}
					{if $formdata['extension']}
					{foreach $formdata['extension'] as $val}
					{if($v['extension_field']==$val['field'])}
					{code}
					if($v['type'] == 'img'){
						$val['value'] = hg_fetchimgurl($val['value']);
					}
					$value = $val['value'];
					{/code}
					{/if}
					{/foreach}
					{/if}
					<div class="m2o-item cut-off">
						<label class="title">{$v['extension_field_name']}:</label>
						<div class="info">
						{if $v['type'] =='text'}
							<input type="text" name="member_info[{$v['extension_field']}]" value="{$value}" />
						{elseif $v['type'] == 'img'}
							<div class="img-box">
								<div class="img">
									{if $value}
										<img  src="{$value}">
									{/if}
								</div>
								<input type="file" class="id-upload" name="{$v['extension_field']}"  style="display: none;" />
								<div class="img-upload-btn">+</div>
							</div>
						{/if}	
						</div>
					</div>
					{/foreach}
            
            		<!--{code}
						$info = array($province_id, $city_id, $area_id);
						{/code}
						{template:form/address_search, '', '', $info, ''}-->
            </div>
            <div class="member-info medal-info">
				<ul class="medal-list">
				{code}$selected_medal_id = array();{/code}
				{foreach $medal_info as $k => $v}
					{code}
						$flag = 0;
						if(in_array($v[id],$medal_id))
						{
							$flag = 1;
							$selected_medal_id[] = $v[id];
						}
					{/code}
						<li class="{if $flag}selected{/if}" data-id="{$v['id']}" title="{$v['brief']}">
							<img src="{$v[image_url]}" />
							<span>{$v['name']}</span>
						</li>
				{/foreach}
				</ul>
				<input type="hidden" name="medal_id" value="{code}if($selected_medal_id) echo implode(',',$selected_medal_id);{/code}" />
			</div>
			{if $id}
			 <div class="member-info bind-info">
            	<ul>
            		{foreach $formdata['bind'] as $k => $v}
            		<li class="m2o-flex" data-memberid="{$v['member_id']}_{$v['platform_id']}_{$v['type']}">
            			<div class="per-info">
            				<img src="{if $v['avatar_url']}{$v['avatar_url']}{else}{$RESOURCE_URL}avatar.jpg{/if}" title="{$v['nick_name']}"/>
            				<p title="{$v['nick_name']}">{$v['nick_name']}</p>
            			</div>
            			<div class="other-info m2o-flex-one">
            				<div class="info-list">
            					<label>是否主帐号:</label> 
            					<p>{if $v['is_primary']}是{else}否{/if}</p>
            				</div>
            				<div class="info-list" title="{$v['platform_id']}">
            					<label>平台ID:</label> 
            					<p>{$v['platform_id']}</p>
            					{if $v['platform_id']}
            					<span class="clone">复制</span>
            					<span id="forLoadSwf{$k}1" class="forLoadSwf" data-index="{$k}1" _text="{$v['platform_id']}"></span>
            					{/if}
            				</div>
            				{if $v['type'] == 'uc' || $v['inuc']}
            				<div class="info-list" title="{$v['inuc']}">
            					<label>UC_ID:</label>
            					<p>{$v['inuc']}</p>
            					{if $v['inuc']}
            					<span class="clone">复制</span>
            					<span id="forLoadSwf{$k}2" class="forLoadSwf" data-index="{$k}2" _text="{$v['inuc']}"></span>
            					{/if}
            				</div>
            				{/if}
            				<div class="info-list">
            					<label>绑定类型:</label>
            					<p>{$v['type_name']}</p>
            				</div>
            				<div class="info-list" title="{$v['reg_device_token']}">
            					<label>绑定设备号:</label>
            					<p>{$v['reg_device_token']}</p>
            					{if $v['reg_device_token']}
            					<span class="clone">复制</span>
            					<span id="forLoadSwf{$k}3" class="forLoadSwf" data-index="{$k}3" _text="{$v['reg_device_token']}"></span>
            					{/if}
            				</div>
            				<div class="info-list">
            					<label>绑定IP:</label> 
            					<p>{$v['bind_ip']}</p>
            					{if $v['bind_ip']}
            					<span class="clone">复制</span>
            					<span id="forLoadSwf{$k}4" class="forLoadSwf" data-index="{$k}4" _text="{$v['bind_ip']}"></span>
            					{/if}
            				</div>
            				<div class="info-list">
            					<label>绑定时间:</label>
            					<p>{$v['bind_time']}</p>
            				</div>
            			</div>
            			<span class="unbind">解除绑定</span>
            		</li>
            		{/foreach}
            	</ul>
			</div>
			<div class="member-info score-info">
				<div class="score-box" data-id="{$id}">
					<div class="score-each score-title m2o-flex m2o-flex-center">
			            <div class="w100" title="索引图">索引图</div>
			            {if $get_credits_type['credit1']}
			            <div class="w100" title="{$get_credits_type['credit1']['title']}">{$get_credits_type['credit1']['title']}</div>
			            {/if}
			            {if $get_credits_type['credit2']}
			            <div class="w100" title="{$get_credits_type['credit2']['title']}">{$get_credits_type['credit2']['title']}</div>
			            {/if}
			            <div class="w200" title="记录时间">记录时间</div>
			            <div class="w200" title="操作原因">操作原因</div>
			            <div class="m2o-flex-one" title="变更描述">变更描述</div>
			        </div>
			        <div class="score-list">
			        </div>
			        <div class="score-each score-bottom">
			        	<div class="page_size"></div>
			        </div>
				</div>
			</div>
			<div class="member-info">
				<div class="m2o-item cut-off">
	        		<label class="title">邀请人:</label>
	        		<div class="info">
	        			<input type="text" name="inviteuser" value="{$inviteuser['member_name']}" disabled="disabled"/>
	        		</div>
	        	</div>
				<div class="m2o-item cut-off">
	        		<label class="title">注册设备号:</label>
	        		<div class="info">
	        			<input type="text" name="register_device" value="{$reg_device_token}" disabled="disabled"/>
	        		</div>
	        	</div>
	        	<div class="m2o-item cut-off">
	        		<label class="title">登录设备号:</label>
	        		<div class="info">
	        			<input type="text" name="login_device" value="{$last_login_device}" disabled="disabled"/>
	        		</div>
	        	</div>
			</div>
			{/if}
            </div>
        </section>
    </div>
	<input type="hidden" name="a" value="{$ac}" id="action" />
	<input type="hidden" name="member_id" value="{$member_id}" />
	<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
</form>
</body>
<script type="text/x-jquery-tmpl" id="list-tpl">
<div class="score-each m2o-flex m2o-flex-center" id="{{= id}}">
	<div class="w100" title="索引图">
		<img src="{{if icon[0]}}{{= icon}}{{else}}{{= RESOURCE_URL}}pic_detail.png{{/if}}" />
	</div>
	{{if credit1}}
	<div class="w100" title="{{= credit1}}">{{= credit1}}</div>
	{{/if}}
	{{if credit2}}
	<div class="w100" title="{{= credit2}}">{{= credit2}}</div>
	{{/if}}
	<div class="w200" title="{{= dateline}}">{{= dateline}}</div>
	<div class="w200" title="{{= title}}">{{= title}}</div>
	<div class="m2o-flex-one" title="{{= remark}}">{{= remark}}</div>
</div>
</script>
<script type="text/javascript">
function copySuccess(){
	//flash回调
	alert("已复制到粘贴板！");
}
</script>