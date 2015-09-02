<?php 
/* $Id: head_register_login.tpl.php 1872 2011-01-26 07:16:40Z develop_tong $ */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->page_title . '_' . $this->settings['sitename'];?></title>
<?php echo $extra_header;?>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<!--  
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_DIR?>css/base.css?<?php echo $this->settings['version']; ?>"/>
-->
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_DIR?>css/style.css?<?php echo $this->settings['version']; ?>"/>
<?php 
echo hg_add_head_element('echo'); 
?>
</head>
<body<?php echo $html_body_attr;?> class="img_h">
<div class="ucenter-con">
<div id = "notify" style="position:relative;clear:both;width:175px; float:right; z-index:9999"><div id='flownotify'></div></div>
<div class="head">
	<div class="head_top">
		<div class="logo"><a href="<?php echo hg_build_link('index.php');?>"><img src="<?php echo RESOURCE_DIR;?>img/logo.jpg"/></a></div>
	    <div class="hright">
	    	<div class="hlink">
	        	<ul class="link_txt">
	                <li><a href="#">帮助</a></li>
	                <li class="lpd">|</li>
	                <li><a href="#">收藏本站</a></li>
	            </ul>
	            <ul class="css_chang">
	            	<li class="cs1"></li>
	                <li class="cs2"></li>
	                <li class="cs3"></li>
	                <li class="cs4"></li>
	            </ul>
	        </div>
	        <div class="logins">
	        	<div class="login_form">
	        	<!--
	        	     	<div class="ruzhu">
		       			<span class="span-red">
		       			
		       			
		       			</span>
		       			 入 入驻
		       		</div>
		       		<div class="fabu">
		       			<span class="span-green">
		       
		       			
		       			</span>
		       			条 信息发布
		       		</div>
		       		-->
	            </div>             	
	        </div>
	    </div>
	</div>
</div>
    <div class="nav_bg clear">
	<div class="nav_menu">
    	<div class="tab_menu">
        	<ul class="tab_btn">
        	<?php 
        		if(is_array($this->nav['main']))
        		{
        			$j = 1;
        			$last = count($this->nav['main']);
        			foreach($this->nav['main'] as $key =>$value)
        			{
        				if($j == $last)
        				{?>
        					<li class="last"><a href="<?php echo $value;?>"><?php echo $this->nav['lang'][$key];?></a></li>
        				<?php	
        				}
        				else 
        				{
        				?>
        					<li><a href="<?php echo $value;?>"><?php echo $this->nav['lang'][$key];?></a></li>
        				<?php 
        				}
        				$j++;
        			}
        		}
        	?>
        	</ul>
        </div>
        <div class="sea_form">
        	<form action="" method="post">
        	  <input class="sea_name" name="sname" type="text" />
        	  <input class="sea_sub" name="sub" type="button" value="搜索" />
          </form>
        </div>
    </div>
</div>