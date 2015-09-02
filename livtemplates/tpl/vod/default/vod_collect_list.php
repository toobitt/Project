{template:head}
{code}
	$list = $vod_collect_list[0];
	$image_resource = RESOURCE_URL;
{/code}

{code}
	$states = array(-1 => '全部', 2=> '待审核', 1 => '审核通过', 0 => '审核不过');
	if(!isset($_INPUT['k']))
	{
		$_INPUT['k'] = '关键字';
		$onclick = ' onfocus="if(this.value==\'关键字\') this.value=\'\'" onblur="if (this.value==\'\') this.value=\'关键字\'"';
	}
	if (!isset($_INPUT['state']))
	{
		$_INPUT['state'] = -1;
	}
{/code}

<style type="text/css">
  .row_title{font-size:14px;height:42px;border-top:1px solid #C8D4E0;}
  .row_content{font-size:14px;height:50px;cursor:pointer;}
  .row_foot{font-size:14px;height:55px;}
  td{border-bottom:1px solid #C8D4E0;}
  .bitrate{width:18px;height:11px;background:url('{$image_resource}400.jpg') no-repeat;font-size:10px;}
  a{font-size:14px;color:#515151;text-decoration:underline}
  .minfo{height:82px;border:1px solid red;background:#E6F3FC;display:none;}
  .bianji_info{width:20%;float:left;font-size:13px;color:gray;margin-top:5px;}
  .minfo_label{color:black;}
  .caozuo{width:246px;margin-top:10px;}
  .caozuo1{font-size:13px;color:#7F97BD;float:left;display:block;text-decoration:none;margin-top:16px;}
  .cz{cursor:pointer;}
  .collect_name{font-size:13px;text-decoration:none;}
  .head_box{width:100%;height:40px;background:#F1F2F4;border:1px solid #D8D8D8;}
  .text_style{margin-left:8px;margin-top:2px;float:left;font-size:14px;color:#737373;}
  .sou_box{width:95px;height:22px;border:1px solid #CFCFCF;margin-left:10px;margin-top:8px;float:left;}
  .xiala{width:10px;height:10px;float:right;background:url({$image_resource}xiala.png) no-repeat;margin-top:8px;margin-right:5px;}
  .button_op{float:right;margin-top:7px;margin-right:10px;cursor:pointer;}
  .select_box{width:110px;float:left;}
  .search_box{margin-left:1150px;width:200px;margin-top:-3px;}
  .sort{width:100px;height:21px;}
  .inner_button{float:left;}
</style>

<div class="wrap" id="video_list">

<div class="head_box">
  
   <div class="search_a" >
	<form name="searchform" action="" method="get" onsubmit="if(this.k.value=='关键字') this.k.value='';">
	 <div class="select_box">
		    <select name="sort_name" class="sort" >
		        <option value=0>全部</option>
			    {foreach $sort_name[0] as $v}
			    {if $list['sort'] == $v['id']}
			    	<option value="{$v['id']}" selected="selected">{$v['sort_name']}</option>
			    {else}
			    	<option value="{$v['id']}" >{$v['sort_name']}</option>
			    {/if}
			    {/foreach}
		    </select>
	 </div>
	<input type="hidden" name="a" value="show" />
	<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
	<div class="search_box">
		<input type="text" name="k"  id="search_list"  class="inner_button"  value="{$_INPUT['k']}"{$onclick}  />
		<input type="submit"  class="button_2  inner_button"  name="hg_search"  value="搜索" />
	</div>
	</form>
  </div>
  
</div>
<form method="post" action="" name="listform">
<table cellspacing="0" cellpadding="0" class="list">
	  <tr align="center" class="row_title" >
	    <td width="10%">&nbsp;&nbsp;<img src="{$image_resource}hg_logo.jpg" /></td>
	    <td width="50%">标题</td>
	    <td width="10%">发布</td>
	    <td width="10%">分类</td>
	    <td width="10%" align="left">最后添加人</td>
	    <td width="10%" align="left">时间</td>
	  </tr>
    <tbody id="collect_list"> 
     {if $list['collect_info']}
       {foreach $list['collect_info'] as $k => $v}
		  <tr id="r_{$v['id']}" align="center"   style="cursor:pointer;height:50px;"   status="0"     onclick="hg_row_interactive(this, 'click', 'cur');hg_fold(this);" onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');" >
		    <td width="49px">&nbsp;&nbsp;<img src="{$image_resource}hg_logo.jpg" /></td>
		    <td width="49px"><a href="./run.php?mid={$relate_module_id}&collect_id={$v['id']}{$_ext_link}"  class="collect_name"><font color="black">{$v['collect_name']}</font></a>&nbsp;&nbsp;{$v['create_time']}&nbsp;&nbsp;&nbsp;&nbsp;<font color="green">{$v['count']}</font></td>
		    <td><img src="{$image_resource}hg_fabu_green.jpg" /></td>
		    <td>{$v['vod_sort_id']}</td>
		    <td>{$v['admin_name']}</td>
		    <td>{$v['update_time']}</td>
		  </tr>
		  
		  <tr class="minfo" id="m_r_{$v['id']}">
		    <td></td>
		    <td colspan="3">
		       <div class="bianji_info" style="width:75%;">
		         <label class="minfo_label">来&nbsp;&nbsp;&nbsp;源：</label>{$v['source']}
		       </div>
		      
		       <div class="bianji_info" >
		         <label  class="minfo_label">分&nbsp;&nbsp;&nbsp;类：</label>{$v['vod_sort_id']}
		       </div>
		      
		       <div class="bianji_info" style="width:95%;">
		         <label  class="minfo_label">描&nbsp;&nbsp;&nbsp;述：</label>{$v['comment']}
		       </div>
		    </td>
		    <td colspan="2">
		       <div class="caozuo" style="margin-top:10px;">
			      <a  href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}{$_ext_link}">编辑</a>
			      <a onclick="return hg_ajax_post(this, '删除', 1);" title="" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}">删除</a>
		       </div>
		    </td>
		  </tr>
	    {/foreach}
	  {else}
	  <tr><td colspan="6" style="text-align:center;">&nbsp;&nbsp;暂无此类信息</td></tr>
	  {/if}
	  
	  <tr class="row_foot">
	    <td  align="center">&nbsp;&nbsp;<input type="checkbox" name="checkall" id="checkall" value="infolist" title="全选" /></td>
	    <td colspan="3">
	       <input type="button" class="button_2" onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');"  title=""  value="删除"   name="batdelete"> 
	    </td>
	   
	    <td colspan="2">{$pagelink}</td>
	  </tr>
  </tbody>
</table>
</form>
</div>
{template:foot}