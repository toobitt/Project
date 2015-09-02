{template:head}
<style>
.menu li{width:100px;height:30px;border:1px solid #b2c8da;float:left;margin:15px 0 0 10px;line-height:30px;text-align:center;background:#eee;border-radius:2px;-webkit-box-shadow:0 0 3px #ccc;-moz-box-shadow:0 0 3px #ccc;-o-box-shadow:0 0 3px #ccc;box-shadow:0 0 3px #ccc;cursor:pointer;}
.menu li em{width: 20px;height: 20px;margin:7px 0 0 5px;background:url("{$RESOURCE_URL}icon.png") no-repeat -60px -183px;display:inline-block;float:left;}
.menu li a{color:#1a4c9a;height: 100%;width:100%;display:block;}
.select{border: 1px solid #6aa3ea!important;background:#e2eef8!important;}
.bg-pic{background:url("{$RESOURCE_URL}icon.png") no-repeat -78px -183px!important;}
</style>
<div class="heard_menu">
	<div class="clear top_omenu" id="_nav_menu">
		<ul class="menu">
			<li><a href="http://10.0.1.40/livworkbench/admin_template.php" target="content"><em></em>模板源商店</a></li>
			<li><a href="http://10.0.1.40/livworkbench/admin_datasource.php" target="content"><em></em>数据源商店</a></li>
			<li><a href="http://10.0.1.40/livworkbench/admin_layout.php" target="content"><em></em>布局商店</a></li>
			<li><a href="http://10.0.1.40/livworkbench/admin_mode.php" target="content"><em></em>样式商店</a></li>
			
		</ul>
	</div>
</div>
<iframe frameborder="no" scrolling="yes"  src="http://10.0.1.40/livworkbench/admin_template.php" name="content" style="width:100%;height:620px"></iframe>
<script>
(function($){
	$('.menu li').on('click' , function(event){
		var self = $(event.currentTarget);
			self.addClass('select').siblings().removeClass('select');
			self.find('em').addClass('bg-pic').end().siblings().find('em').removeClass('bg-pic');
			self.find('a').addClass('color').end().siblings().find('a').removeClass('color');
	});
	$('.menu li:first').click();			
})($);
</script>