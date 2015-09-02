<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: geoinfo.php 396 2011-07-28 00:52:08Z zhoujiafei $
***************************************************************************/
?>
{template:head}
<style>
	.top_table{ font-size: 12px; text-align: left;  vertical-align: top;padding: 3px;color: #333333;margin-left:30px;}
	.top_table td{font-size:12px;text-align:left;padding:2px;}
	.table_right,.table_right tr td{height:auto;width:100%;vertical-align: top;}
	#map_canvas{height:400px;width:800px;margin-left:5px;text-align:left;} 
	.maker_normal{background:url(<?php echo RESOURCE_DIR;?>img/map/marker_n.png) no-repeat scroll 1px 0 transparent; color: white; cursor: pointer;position: absolute; font-size: 12px; height: 34px; line-height: 23px; padding: 0 1px;text-align: center;}
	.maker_normal a {color:#fff;}
	.maker_this{background:url(<?php echo RESOURCE_DIR;?>img/map/marker_this.png) no-repeat scroll 1px 0 transparent; color: white; cursor: pointer; font-size: 12px; height: 34px; line-height: 23px; padding: 0 1px;text-align: center;}
	.maker_this a {color:#fff;}
 
</style>
<div class="content clear">
  <div class="content_top"></div>	
	<div class="content_middle lin_con clear">  
	
<!-- 导航按钮  --> 
{template:unit/userset}
      <p>选择下列位置所属的讨论区，让同一讨论区的朋友关注到你的最新动态。 </p> 
      <table width="695" height="100" class="top_table" style="margin-left:110px;margin-top:15px;margin-bottom:5px;" align="center">
      	<tr style="height:40px;border: 1px solid #ccc;background:none repeat scroll 0 0 #FFFCE9;">
      		<td colspan="2" style="font-size:12px;text-align:center;">点击地图中的讨论区图标，可以将该讨论区所在的地点设置为您当前的位置</td>
      	</tr>
      	<tr height="40">	
      		<td id="liveheretd"> 
      			<div id="livehere_gid_div">
      				我当前位置<input name="livehere_gid_n" type="text" class="text" readonly=true value="{$default_gname}" id="livehere_gid_n"/>
      			 	<input type="hidden" name="livehere_gid" id="livehere_gid" value="{$default_gid}" />
      				<input type="hidden" name="livehere_gid_lat" id="livehere_gid_lat" value="{$default_lat}" />
      				<input type="hidden" name="livehere_gid_lng" id="livehere_gid_lng" value="{$default_lng}" /> 
      			</div>	
      		</td> 
      		<td><input type="button" value="保存设置" onclick="savaData()" /></td>
      	</tr>
      </table> 
      <table width="695" height="420" style="margin:10px auto;">
      	<tr> 
      		<td class="table_right">
      			<div id="map_canvas" ></div>
      		</td>
      	</tr> 
      </table>
      <input type="hidden" name="minPointLat" value="" id="" />
       
 	</div>
	<div class="content_bottom"></div>

  </div>

 {template:foot}