<?php
/* $Id: userprivacy.tpl.php 1681 2011-01-10 08:47:19Z develop_tong $ */

?>
<?php include hg_load_template('head_register_login');?>

<div class="registering">
	<div class="content clear">
	<div class="content_top"></div>	
		<div class="content_middle clear"> 
		<!-- 导航按钮  -->
		
		<?php include hg_load_template('userset'); ?>
				
		<div class="content_ys">
		<h3>
			个人页面访问
		</h3>
		
		<?php
		foreach($this->settings['authority']['visit_user_info'] as $k => $v)
		{
			if($authority[14] == $k)
			{
		?>
		<p>
			<input type="radio" name="visit_user_info" checked="checked" value="<?php echo $k; ?>"/>			
			<label><?php echo $v; ?></label>
		</p>
		<?php 
			}
			else
			{ 		
		?>
		<p>
			<input type="radio" name="visit_user_info"  value="<?php echo $k; ?>"/>			
			<label><?php echo $v; ?></label>
		</p>		
		<?php
			}
		}	
		?>
		</div>		
		<div class="content_ys">
		<h3>
			<span><strong>添加关注</strong></span>
		</h3>
		<?php
		foreach($this->settings['authority']['follow'] as $k => $v)
		{
			if($authority[19] == $k)
			{
		?>
		<p>
			<input type="radio" name="follow" checked="checked" value="<?php echo $k; ?>"/>			
			<label><?php echo $v; ?></label>
		</p>
		<?php 
			}
			else
			{ 		
		?>
		<p>
			<input type="radio" name="follow"  value="<?php echo $k; ?>"/>			
			<label><?php echo $v; ?></label>
		</p>		
		<?php
			}
		}	
		?>
		
		</div>
		<div class="content_ys">	 		
		<h3>
			<span><strong>评论</strong></span>
		</h3>		
		<?php
		foreach($this->settings['authority']['comment'] as $k => $v)
		{
			if($authority[18] == $k)
			{
		?>
		<p>
			<input type="radio" name="comment" checked="checked" value="<?php echo $k; ?>"/>			
			<label><?php echo $v; ?></label>
		</p>
		<?php 
			}
			else
			{ 		
		?>
		<p>
			<input type="radio" name="comment"  value="<?php echo $k; ?>"/>			
			<label><?php echo $v; ?></label>
		</p>		
		<?php
			}
		}	
		?>
		
		</div>	
		<div class="content_ys"> 		
		<h3>
			真实姓名
		</h3>		
		<?php
		foreach($this->settings['authority']['search_true_name'] as $k => $v)
		{
			if($authority[17] == $k)
			{
		?>
		<p>
			<input type="radio" name="search_true_name" checked="checked" value="<?php echo $k; ?>"/>			
			<label><?php echo $v; ?></label>
		</p>
		<?php 
			}
			else
			{ 		
		?>
		<p>
			<input type="radio" name="search_true_name"  value="<?php echo $k; ?>"/>			
			<label><?php echo $v; ?></label>
		</p>		
		<?php
			}
		}	
		?>
			
		</div>
		
		<div class="content_ys">	
		<input type="button" name="sub" onclick="setPrivacy();" value=""  class="ok"/>
		<span id="show_notice" ></span>	
		</div>
	</div>
<div class="content_bottom"></div>
	</div>

</div>

<?php include hg_load_template('foot');?>
