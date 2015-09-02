<?php
/*$Id:$*/
?>
<link rel="STYLESHEET" type="text/css" href="<?php echo RESOURCE_DIR . 'colorselector/dhtmlxcolorpicker.css'?>" />
<script type="text/javascript" src="<?php echo RESOURCE_DIR . 'colorselector/dhtmlxcommon.js'?>"> </script>
<script type="text/javascript" src="<?php echo RESOURCE_DIR . 'colorselector/dhtmlxcolorpicker.js'?>"></script>

<style>
.set_wrap{background: none repeat scroll 0 0 #FFFFFF;color: #666666; font: 12px/28px Arial,Helvetica,sans-serif;}
.set_expand{height: 251px;padding-top: 6px;}
.set_interface{width: inherit;}
.set_zdybg{height: 203px;}
.set_expand .set_innerDiv .set_nav {
    float: left;
    height: 28px;
    margin-bottom: 5px;
    width: 600px;
	text-align:center;
}
#skin_display_main{text-align:center;}
.set_userDefined {height: 140px; padding-top: 10px;width: 805px;}
.set_userDefined .set_subMenu {border-right: 1px solid #BDC5D8;height: 140px;width: 101px;}
.set_settingPage {border-right: 1px dashed #CBCBCB; margin-left: 15px;} 
.lf {float: left;}
.set_innerDiv{ height: 246px;  margin: 0 auto;  position: relative;  width: 800px;}
.set_controlDiv{ height: 188px; width: 805px;}
.set_controlDiv ul.set_chooseStyle01{padding-left: 12px;}
.set_controlDiv ul .set_chooseStyle01 li { border: 2px solid #F4F8FC;height: 83px; width: 120px;}
.set_controlDiv ul.set_chooseStyle01 li a {  background: none repeat scroll 0 0 #FFFFFF;border: 1px solid #B4B4B4;display: block;padding: 2px;}
.set_expand .set_innerDiv .rt { height: 26px;text-align: right;width: 200px;}
.set_isUsing{ border: 1px solid #EFEFEF; cursor: auto; padding: 2px;text-align: center;}
.set_isUsing .imgBox { height: 59px; width: 106px}

.set_settingPage .set_nobg {border: 2px solid #EFEFEF; margin-top: 10px;}
.set_settingPage .set_notUse { background: none repeat scroll 0 0 #FFFFFF; border: 1px solid #B2B2B2; cursor: pointer; padding: 2px; text-align: center;}
.rt {float: right;}

.set_settingPage .set_border {
    border: 2px solid #62B856;
    text-align: center;
}
.set_pageBack {
    background: none repeat scroll 0 0 #FFFFFF;
    border: 1px solid #B2B2B2;
    cursor: pointer;
    text-align: center;
    width: 80px;
	height:50px;
}
.set_pageBack .set_backColor {
    background: none repeat scroll 0 0 #709ADE;
    cursor: pointer;
    height: 49px;
    margin: 2px;
    width: 86px;
}

a, a:link, a:visited {color: #0082CB;}
.activeMenu a:active {
    color: #6F7A92;
    font-size: 14px;
    text-decoration: none;}
img.set_templateTH {height: 59px; text-decoration: none;width: 114px;}
.set_controlDiv ul.set_chooseStyle01 li p.name {color: #999999; height: 18px;line-height: 18px;text-align: center;}
.activeSubMenu{ font-weight: bold; height: 27px; margin: 0 0 5px; padding: 0; width: 101px;}
.set_changeColor ul li{  background: none repeat scroll 0 0 #FFFFFF; border: 1px solid #B4B4B4;cursor: pointer;float: left;height: 70px;padding: 2px;text-align: center;width: 86px;}
.set_changeColor ul li .set_pageBack{margin-left:0;}
.MIB_bloga {background-image: none;background-position: center bottom; background-repeat: no-repeat;}
.MIB_blogb {background-position: center 0;background-repeat: no-repeat;clear: both;text-align:center;}
 
</style>
<script>

</script>
<div class="set_wrap">
	<div id="container" class="set_interface set_expand" style="position: static; top: 0px;">
		<div id="innerContainer" class="set_innerDiv">
			<div>
				<div class="set_nav">
					<ul>
					<!-- 循环分类 
					<?php //foreach($skin_sort as $key => $value){?>
						<li onclick="" id="" class="activeMenu"><span><a onclick="return false;" href="#">最新推荐</a></span></li>
						<?php // }?>-->
						<li onclick="showZdy();" id="zdyli" class="activeMenu"><span><a onclick="return false;" href="#">自定义</a></span></li>
					</ul>
				</div>
				<div class="rt">
					<input type="button" name="setSkin" value="保存" onclick="saveSkin()" /><input type="button" name="cancleSkin" value="取消" onClick="" />
				</div>
				<div class="clear"></div>
			</div>
			 <div id="skin_display_main">
			 <!-- 循环系统风格缩略图
			 <?php //foreach($style_list as $kk => $vv){?>
				<div id="" class="set_controlDiv" style=""> 
					<ul class="set_chooseStyle01">
						<?php //foreach($vv as $id => $info){?>													
						<li onclick="" onmouseout=" " onmouseover=" " class="">
						<a class="noborder" onclick="return false;" href="#">
								<img class="set_templateTH" src="">
								<p class="name"></p>
							</a>
						</li>
						<?php //}?>	
						</ul>
					<div class="clear"></div>
				</div>
				<?php // }?> -->
				<!-- 分页的设定   -->	 
				<div style="display: none;" id="tZdy" class="set_userDefined">
					<div class="set_subMenu lf">
						<ul>
						<!-- 此处为循环输出后台设置过的可供用户自定义的选项 -->
						<?php //foreach($user_zdy as $kk => $vv){?>
							<li class="activeSubMenu" onclick="setColor();">
								<a href="javascript:void(0);">页面背景</a>
							</li>
							<li class="" onclick="setColor();">
								<a href="javascript:void(0);">颜色</a>
							</li>
							<?php //}?>
						</ul>
					</div>
				<div class="set_settingPage set_uploadSuccess lf" id="zdy_l0">
					<div class="set_setBackground lf">
						<div id="use_bg" class="set_bg set_border" onclick="changeClass(this)">
							<div class="set_isUsing">
								<div class="imgBox">
									<img id="tips_img" height="60px" width="109px" src="<?php echo $save_file_path;?>" alt="背景图" onload="changeBG();" onreload="changeBG();">
								</div>
								<p>使用背景图</p>
							</div>
						</div>
						<div id="unuse_bg" class="set_nobg " onclick="changeClass(this);disableCh(this);">
							<div class="set_notUse" >
								<p>不使用背景图</p>
							</div>
						</div>
					</div>
					<div class="set_otherSettings lf">
						<div> 
						<span class="set_gray" id="upload_process" style="display:none;">上传中...<img src="<?php echo RESOURCE_DIR ?>img/spinner.gif" alt="上传中" /></span>
							<form target="Upfiler_iframe" name="upload_form" method="post" action="mytemplate.php?a=uploadImg" enctype="multipart/form-data" >
							 	<span id="result"></span>
								<input id="up_file_bg" type="file" class="MIB2_input" name="pic1" onchange="upload(this);" />
								<strong style="display: none;"><?php echo $this->lang['success'];?></strong> 
							</form>
							<p class="set_gray">
								<iframe height="1" frameborder="0" width="1" style="display: none;" src="about:blank" name="Upfiler_iframe" id="Upfiler_iframe" ></iframe>
								支持大小不超过5M的jpg、gif、png图片上传<br> 
							</p>
							<input type="hidden" value="<?php echo $result;?>" name="re" id="up_result" />
						</div>
						<div class="marginTop15">
							<strong>设置背景图：</strong>
							<span class="toInlineBlock" style="margin-right: 5px;">
								<input type="checkbox" id="lockbg" name="lockbg" value="fixed">锁定
							</span>
							<select id="bg_repeat">
								<option value="repeat">平铺</option>
								<option value="no-repeat">不平铺</option>
							</select>
							<span class="toInlineBlock marginLeft20">
								<strong>对齐方式：</strong>
								<select id="bg_alin">
									<option value="top left">左对齐</option>
									<option value="top center">居中</option>
									<option value="top right">右对齐</option>
								</select>
							</span>
						</div>
					</div>
				</div>
				<div class="set_pageBack lf" id="pageback" onclick="showCP()" style="padding:0;height:auto;width:90px;margin-left:15px;">
					<input disabled="true" type="text" class="set_backColor" id="mainSdiv" style="background-color: rgb(112, 154, 222);border:0;margin:0px;">
					<div id="cp"></div>
					<p>页面背景</p>
				</div>
				<div class="set_changeColor" style="display: none;" id="mainColor_div">
					<ul>
					<!-- 以下要循环输出配置的选项 -->
					<?php foreach($this->mSetting as $key => $val){ $vv = explode("_",$key);?>
						<li class=""  onclick="cpinit(<?php  echo $vv[1];?>)" id="cSli_<?php  echo $vv[1];?>">
							<input type="text" disabled="disabled" class="set_pageBack" id="mainD_<?php  echo $vv[1];?>" style="border:0;background-color:<?php echo $val['color'];?>" >
							<input type="hidden" id="mainF_<?php  echo $vv[1];?>" value="<?php echo $val['color'];?>" class="colorSet"/> 
							<div  id="mainCP<?php echo $vv[1];?>" ></div>
							<p><?php echo $val['name'];?></p>
						</li> 
						<?php }?>
					</ul>
					<input type="hidden" id="styleid" name="styleid" value="" /> 
					<!-- 
					<div class="defaultColor">
						<a href="javascript:void(0);">恢复到默认颜色</a>
					</div> -->
					<div class="clear" style="margin-bottom: -79px;"></div>
				</div>
			</div>
		</div>
	</div> 
  </div>
</div>
<div class="MIB_bloga">
	<div class="MIB_blogb"><?php include hg_load_template("index");?></div>
	
</div>