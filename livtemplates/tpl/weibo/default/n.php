<?php 
/* $Id: n.php 8320 2012-03-16 08:08:52Z repheal $ */
?>

{template:head}
<div class="content clear" id="equalize">
	<div class="content-left">
		{if $have_result}	
		
		<div class="my-business">
			
			<div class="search-box" style="float:right;">
				<form action="n.php" method="post">
				<input type="hidden" name="search" value="search">
				<input style="font-size:12px;color:gray;border:1px solid #CCCCCC;" class="search" id="search_content" onblur="showText(this);" onclick="clearText(this);" type="text" name="search_name" value="{code} echo $_input['search_name'] ? $_input['search_name'] : $_lang['input_screen_name']; {/code}" />
				<input type="submit" name="search_follow" value="{$_lang['search']}" style="padding:0px 10px;" />
				</form>
			</div>
			
			<span>共有{$total_nums}位用户</span>
		</div>		
		
		<div class="followers_list">
			<ul class="status-item">
			{foreach $search_friend as $k => $v}
				<li>
					
					<div class="blog-content">
						<div class="attention clear">
							<p class="name"><a href="{code} echo hg_build_link(SNS_UCENTER . 'user.php' , array('user_id' => $v['id'] )); {/code}" >{$v['username']}</a>：<span><a>{$v['followers_count']}</a>粉丝&nbsp;&nbsp;<a>{$v['attention_count']}</a>关注&nbsp;&nbsp;<a>{$v['status_count']}</a>点滴&nbsp;&nbsp;<a>{$v['video_count']}</a>视频</span></p>
						</div>
						
						<div class="close-concern">
					
						{if $_user['id'] == $v['id']} <!-- /*自己*/    -->       
						
						{else}
						
							{if $v['is_friend'] == 1}    <!-- /*已关注*/ -->
					
						<a class="been-concern"></a>					
						
							{else}
							
						<p id="add_{$v['id']}"><a class="follow-gz" href="javascript:void(0);" onclick="addFriends({$v['id']} , 4)"></a></p>
							{/if} 
						{/if} 															
						</div>
						
					
					</div>
					
					<a href="{code} echo hg_build_link(SNS_UCENTER . 'user.php' , array('user_id' => $v['id'])); {/code}"><img src="{$v['middle_avatar']}" title="{$v['username']}" /></a>
				    <div style="margin:5px;" >
						{code} echo hg_verify($v['text']);{/code}						
					</div>					
				</li>
			{/foreach}		
			</ul>
			<div style="clear:both;"></div>
			{$showpages}
		</div>
		{else}
			{code}
				$search_content = $screen_name;
			{/code}
			{template:unit/null_search}
		{/if}
			
	</div>
	
		{if $_user['id'] > 0}

    <div class="content-right">
	<div class="pad-all">
	<!-- load userInfo -->
	<div class="bk-top1">我的资料</div>
	<div class="wb-block1">
	<div class="user">
		<div class="user-set">
		<h5><a href="{code} echo hg_build_link(SNS_UCENTER.'user.php' , array('user_id' => $user_info['id'])); {/code}">{$user_info['username']}</a></h5>
		<div class="user-name">
		
			
			<div style="font-size:12px;color:gray;">性别：{code} echo hg_show_sex($user_info['sex']);{/code}</div>
				<div style="font-size:12px;color:gray;">所在地盘：<a style="color:#0164CC" href="{code} echo hg_build_link(SNS_UCENTER . 'geoinfo.php');{/code}">{$user_info['group_name']}</a></div>
					{code}
						$relation = array('birthday'=>'生日','email'=>'邮箱','qq'=>'QQ','msn'=>'MSN','mobile'=>'手机');
					{/code}
					{foreach $relation as $key =>$value}
						{code}
							$temp = $user_info[$key];
						{/code}
						{if $temp}
						
							{if strcmp($key,"birthday")==0 && is_numeric($temp)}
							
								<div style="font-size:12px;color:gray;"><span>{$value} ： <span>{$_lang['xingzuo'][$temp]}</div>
							
							{else}
							
								<div style="font-size:12px;color:gray;"><span style="font-size:12px;color:gray;">{$value} ： </span>{$temp}</div>
							{/if}
						{/if}				
					{/foreach}
		</div>
		</div>
	<a href="{code} echo hg_build_link(SNS_UCENTER.'avatar.php'); {/code}"><img src="{$user_info['middle_avatar']}" title="{$user_info['username']}" /></a>
	</div>
	
	{template:unit/userInfo}
	
		</div>

	    </div>
		
		{else}
	
			<div class="content-right login">

		
		<form action="<?php echo SNS_UCENTER;?>login.php" method="POST">
			<div class="login-menu" id="login">
				<a class="register" href="{code} echo hg_build_link(SNS_UCENTER.'register.php'); {/code}"></a>
				<div class="login-text">
					<input type="text" id="username" name="username" class="username_bg" onfocus="clearUser(this);" onblur="showUser(this);"/>
					<input type="password" id="password" name="password"/>
				</div>
				
				<div class="pwd-recovery" style=" visibility:hidden"><span>
				<input name="" type="checkbox" value="" checked />下次自动登录</span><a href="#">找回密码</a></div>
				<input id="login_bt" class="login-input" type="submit" value=" " name="submit"/>
			</div>
		<input type="hidden" value="dologin" name="a" />
		<input type="hidden" value="{$_INPUT['referto']}" name="referto" />
		</form>
		<div class="pad-all">
				
		<div class="bk-top1">热门话题</div>                                                                                                                                                                                              
		<div class="wb-block1">
		{if $topic}
		<ul class="topic clear">
			
			{foreach $topic as $value}
			<li>
				<a href="{code} echo hg_build_link('k.php' , array('q' => $value['title'])); {/code}">
				{$value['title']}</a><span>({$value['relate_count']})</span>
			</li>
			{/foreach}
		</ul>
		
		{/if}
		</div>
		</div>

	</div>
	{/if}
		</div>

{template:foot}