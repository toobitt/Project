<style type="text/css">
  .row_title{font-size:14px;height:42px;border:1px solid #C8D4E0;}
  .row_content{font-size:14px;height:50px;}
  td{border-bottom:1px solid #C8D4E0;}
</style>


<table cellspacing="0" cellpadding="0" width="845px">
   <tbody id="{$hg_name}">
	  <tr align="center" class="row_title" >
	    <td width="49px"><img src="http://localhost/livtemplates/tpl/lib/images/hg_logo.jpg" /></td>
	    <td width="49px">缩略图</td>
	    <td width="40px">播放</td>
	    <td width="380px">标题</td>
	    <td width="45px">发布</td>
	    <td width="50px" align="left">码流</td>
	    <td width="70px" align="left">分类</td>
	    <td width="70px" align="left">状态</td>
	    <td width="110px" align="left">添加人/时间</td>
	  </tr>
     
     {if $list}
       {foreach $list as $k => $v}
		  <tr align="center" class="list" >
		    <td><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}" /></td>
		    <td><img src="http://localhost/livtemplates/tpl/lib/images/hg_pic.jpg" /></td>
		    <td><img src="{$image_resource}hg_play.jpg"></td>
		    <td>{$v['title']}&nbsp;&nbsp;<font color="gray">{$v['duration']}</font></td>
		    <td><img src="{$image_resource}hg_fabu_green.jpg" /></td>
		    <td align="left"><div style="width:18;height:11px;background:url('400.jpg');font-size:10px;"></div></div></td>
		    <td  align="left"><font color="green">{$v['vod_sort_id']}</font></td>
		    <td align="left"><font color="#7F97BD">{$v['status']}</font></td>
		    <td  align="left"><font color="#7F97BD">{$v['author']}</font><br/><label style="font-size:12px;color:gray;">{$v['update_time']}</label></td>
		  </tr>
	    {/foreach}
	  {else}
	  <tr><td colspan="{$colspan}"  style="text-align:center;">暂无此类信息</td></tr>
	  {/if}
		
		
		
	    <tr align="center" class="row_content">
	    <td><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}" /></td>
	    <td><img src="{$image_resource}hg_pic.jpg" /></td>
	    <td><img src="{$image_resource}hg_play.jpg"></td>
	    <td>高三女生被困电梯  不幸身亡&nbsp;&nbsp; <font color="gray">3'21”</font></label></td>
	    <td><img src="{$image_resource}hg_fabu_gray.jpg" /></td>
	    <td align="left"><div style="width:18;height:11px;background:url('300.jpg');font-size:10px;"></div></td>
	    <td  align="left"><font color="#BD8344">直播归档</font></td>
	    <td  align="left"><font color="#7F97BD">待审核</font></td>
	    <td  align="left"><font color="#7F97BD">周星星</font><br/><label style="font-size:12px;color:gray;">2011-5-12 12:35</label></td>
	  </tr>
	  
	  <tr align="center" class="row_content">
	    <td><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}" /></td>
	    <td><img src="{$image_resource}hg_pic.jpg" /></td>
	    <td><img src="{$image_resource}hg_play.jpg"></td>
	    <td>王菲自爆女儿私房照  为李焉提前庆生&nbsp;&nbsp;<font color="gray">4'26”</font></td>
	    <td><img src="{$image_resource}hg_fabu_gray.jpg" /></td>
	    <td align="left"><div style="width:18;height:11px;background:url('400.jpg');font-size:10px;"></div></td>
	    <td  align="left"><font color="#7F4CCB">标注归档</font></td>
	    <td  align="left"><font color="#7F97BD">待审核</font></td>
	    <td  align="left"><font color="#7F97BD">周星星</font><br/><label style="font-size:12px;color:gray;">2011-5-12 12:35</label></td>
	  </tr>
	  
	   <tr align="center" class="row_content" >
	    <td><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}" /></td>
	    <td><img src="{$image_resource}hg_pic.jpg" /></td>
	    <td><img src="{$image_resource}hg_play.jpg"></td>
	    <td>动物间的肉欲大战&nbsp;&nbsp;<font color="gray">3'21”</font></td>
	    <td><img src="{$image_resource}hg_fabu_green.jpg" /></td>
	    <td align="left"><div style="width:18;height:11px;background:url('400.jpg');font-size:10px;"></div></td>
	    <td  align="left"><font color="#1298BD">标注归档</font></td>
	    <td  align="left"><font color="#7F97BD">待审核</font></td>
	    <td  align="left"><font color="#7F97BD">周星星</font><br/><label style="font-size:12px;color:gray;">2011-5-12 12:35</label></td>
	  </tr>
	  
	  <tr align="center" class="row_content">
	    <td><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}" /></td>
	    <td><img src="{$image_resource}hg_pic.jpg" /></td>
	    <td><img src="{$image_resource}hg_play.jpg"></td>
	    <td>动物间的肉欲大战&nbsp;&nbsp;<font color="gray">3'21”</font></td>
	    <td><img src="{$image_resource}hg_fabu_gray.jpg" /></td>
	    <td align="left"><div style="width:18;height:11px;background:url('300.jpg');font-size:10px;"></div></td>
	    <td  align="left"><font color="#1298BD">万家灯火</font></td>
	    <td  align="left">
	       <label><font color="#7F97BD">转码中</font></label>
	       <div style="width:38px;height:3px;border:1px solid #DBDBDB;">
	          <div style="width:19px;height:3px;background:#7F97BD"></div>
	       </div>
	    </td>
	    <td  align="left"><font color="#7F97BD">周星星</font><br/><label style="font-size:12px;color:gray;">2011-5-12 12:35</label></td>
	  </tr>
  </tbody>
</table>










