<?php 
/* $Id: head.tpl.php 4161 2011-07-11 01:33:12Z repheal $ */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->page_title . '_' . $this->settings['sitename'];?></title>
<?php echo $extra_header;?>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_DIR?>css/style.css?<?php echo $this->settings['version']; ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_DIR?>css/site.css?<?php echo $this->settings['version']; ?>"/>
<?php
hg_add_head_element("js-c", "var sns_ui_url='" . SNS_MBLOG . "';" . "\r\t\n" . ' var now_uid = ' . $this->user['id']); 
hg_add_head_element("js-c", "\r\t\n".'window.onload=function(){if(parseInt(now_uid,10)>0){setTimeout("getnotify()",3000);}}'); 
hg_add_head_element('js',RESOURCE_DIR . 'scripts/pull_down.js');
hg_add_head_element('js',SNS_MBLOG . 'res/scripts/chat_message.js');
echo hg_add_head_element('echo'); 
?> 
</head>
<body <?php echo $load;?>>
<script type="text/javascript" src="<?php echo SNS_MBLOG?>top.php"></script>
<div class="site_head_top">
	<div class="head_menu">
		<div class="menu_left">
			<a class="head_logo" href="<?php echo $this->nav['main']['index']?>"></a>
			<span class="menu_left_tab"></span>
		</div>
		<div class="menu_mid">
			<ul class="menu_tab">
			<?php 
        		if(is_array($this->nav['main']))
        		{
        			$j = 1;
        			$last = count($this->nav['main']);
        			foreach($this->nav['main'] as $key =>$value)
        			{
        				if($j == $last)
        				{?>
							<li class="default"><a href="<?php echo $value;?>"><?php echo $this->nav['lang'][$key];?></a></li>
        				<?php	
        				}
        				else 
        				{
        				?>
        					<li class="default"><a href="<?php echo $value;?>"><?php echo $this->nav['lang'][$key];?></a></li>
        					<li class="line-1"></li>
        				<?php 
        				}
        				$j++;
        			}
        		}
        	?>
			</ul>
		</div>
		<div class="menu_mids">
			<form action="search.php" method="post" id='form'>
        	  <input id="head_search" type="text" name="k" onkeyup="show_head_search(this.value)" onfocus="add_search()" class="sea_name" value="输入关键字搜索..." onclick="javascript:if(this.value == '输入关键字搜索...'){this.value = '';}" onblur="javascript:if(!this.value){this.value='输入关键字搜索...';}">
        	  <input type="submit" value="" name="sub" class="sea_sub">
			</form>
		</div>
		<div class="menu_right">
		<?php 
			if(!$this->user['id'])
			{?>
				<a class="register" href="<?php echo SNS_UCENTER;?>register.php"></a>
				<a class="login" href="<?php echo SNS_UCENTER;?>login.php"></a>
			<?php 
			}
			else 
			{?>
				<div class="login_user">
					<span>欢迎您：</span><a href="<?php echo hg_build_link(SNS_UCENTER.'user.php');?>" class="username"><?php echo $this->user['username'];?></a>
					<ul id="nav">
                	   <!-- <li id="head_n"><a href="<?php echo SNS_UCENTER?>user.php?a=show_notice&n=1">通知</a></li> --><li class="iwant"><a href="javascript:void(0);" style="margin-left:0px">我想</a>
                	   <ul class="lists"> 
                       <?php 
						$i = 0;
						$len = count($this->nav['user']) - 2;
						foreach ($this->nav['user'] AS $k => $link)
						{
							if ($i % 2 == 0)
							{
								$split = '<li class="line-2">|</li>';
							}
							else
							{
								$split = '';
							}
							if ($i > $len||$i > $len-1)
							{
								$style = ' style="background:none;"';
							}
							else
							{
								$style = '';
							}?>
							<li <?php echo $style;?>><a href="<?php echo $link;?>"><?php echo $this->nav['lang'][$k];?></a></li><?php echo $split;?>
						<?php 	
						$i++;
						}
                       ?>
                       </ul></li><!-- <li class="user_setting"><a href="<?php echo hg_build_link(SNS_UCENTER."userprofile.php");?>">设置</a></li> --><li class="user_logout"><a href="<?php echo SNS_UCENTER;?>login.php?a=logout">退出</a></li>  
					</ul> 
				</div>
			<?php
			}
		?>
		</div>
	</div>
	<div class="tab_menu clear">
	<?php if(SECOND_NAV_URL){?> 
	 <script src="<?php echo SECOND_NAV_URL;?>" type="text/javascript"></script>
	<?php }?>
	</div>
</div>