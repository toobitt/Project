{template:./header}
{css:register}
<body class="login">
	<header>							
			<h1><a href="/" title="青网">青网</a></h1>			
	</header>
	<section class="account">
		<h1 class="start">欢迎登录青网，开始你的行动</h1>
		<article>
			<div class="clearfix"><h2>登录</h2><div class="get_account">
				<span>没有青网帐号</span><a href="register.php">注册</a></div></div>
			<div class="error_message"></div>
			<div class="login_form">
				<form action="login.php" method="post"> 
					<div class="username_field"><input type="text" value="帐号"  name="usernames" autocomplete="off" /></div>
					<div class="password_field"><input type="password" name="password" autocomplete="off" /><div class="label">密码</div></div>
					<div class="clearfix other_field ckecked"><label><input type="checkbox" checked name="savestatus"  />保存登录状态</label><!--<a href="#" class="forget_password" >忘记密码</a>--></div>
					<input type="hidden" value="dologin" name="a" />
					<input type="hidden" value="{$referto}" name="referto" />
					<div class="account_btn"><input type="submit" value="登录" /></div>
				</form>
			</div>
		</article>
		<aside>
			<h3>使用合作网站帐号直接登录</h3>
			<ul class="login_with_other">
				<li><a href="#" class="login_with_qq" title="用QQ帐号登录"></a></li>
				<li><a href="#" class="login_with_sina" title="用微博帐号登录"></a></li>
				<li><a href="#" class="login_with_renren" title="用人人帐号登录"></a></li>
				<li><a href="#" class="login_with_douban" title="用豆瓣帐号登录"></a></li>
			</ul>
			<p>未注册过青网也可以直接登录哦</p>
		</aside>		
	</section>	
</body>
</html>