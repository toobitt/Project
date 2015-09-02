<?php 
/* $Id: affix_list.php 30877 2014-03-06 06:16:22Z wangleyuan $ */
?>
{template:head}
{code}
if(!$_INPUT['pic_type'])
{
	$_INPUT['pic_type']=1;
}
if(!$_INPUT['date_search'])
{
	$_INPUT['date_search'] = 1;
}
{/code}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{js:affix}
{css:vod_style}
{css:edit_video_list}
{css:mark_style}
{css:common/common_list}
{css:affix_list}
{js:common/common_list}
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op_program" {if $_INPUT['infrm']}style="display:none"{/if}>
</div>
<div class="content clear">
	<div class="f">			
			 <!-- 编辑图片模板开始 -->
		 	<div id="add_tuji"  class="single_upload">
				<h2><span class="b" onclick="hg_close_affix_tpl();"></span><span id="tuji_title">编辑图片附件</span></h2>
				<div id="tuji_contents_form"  class="upload_form" style="height:808px;margin-top:10px;overflow:auto;">
				</div>
			</div>
			<!-- 编辑图片模板结束 -->
          <div class="right v_list_show">
		  	<div id="infotip"  class="ordertip"></div>
	        <div id="getimgtip"  class="ordertip"></div>
			<div class="search_a" id="info_list_search">
				<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
					<div class="right_1">
						{code}
							$attr_status=array(
								'class' => 'colonm down_list data_time',
								'show' => 'status_show',
								'width' =>104,
								'state' =>0,
							);

							$attr_date = array(
								'class' => 'colonm down_list data_time',
								'show' => 'colonm_show',
								'width' => 104,/*列表宽度*/
								'state' => 1,/*0--正常数据选择列表，1--日期选择*/
							);
						{/code}
						{template:form/search_source,pic_type,$_INPUT['pic_type'],$_configs['pic_type'],$attr_status}
						{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
						<input type="hidden" name="a" value="show" />
						<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
						<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
						<input type="hidden" name="_id" value="{$_INPUT['_id']}" />
						<input type="hidden" name="_type" value="{$_INPUT['_type']}" />
					  </div>
					  <div class="right_2">
					  <div class="button_search">
						<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;"/>
						</div>
						{template:form/search_input,key,$_INPUT['key']}                        
					  </div>
	             </form>
			</div>
              <form method="post" action="" name="listform">
                     <!-- 标题 -->
              <ul class="common-list" id="list_head">
                        <li class="common-list-head clear">
                            <div class="common-list-left">
                                <div class="affix-paixu common-list-item nomargin"><a class="common-list-paixu"></a></div>
                                <div class="affix-slt common-list-item nomargin">缩略图</div>
                            </div>
                            <div class="common-list-right">
                                <div class="affix-bj common-list-item open-close nomargin">编辑</div>
                                <div class="affix-sc common-list-item open-close nomargin">删除</div>
                                <div class="affix-ssyy common-list-item open-close nomargin">所属应用</div>
                                <div class="affix-wjdx common-list-item open-close nomargin">文件大小</div>
                                <div class="affix-scip common-list-item open-close nomargin">上传IP</div>
                                <div class="affix-scr common-list-item open-close nomargin">上传人/上传时间</div>
                            </div>
                            <div class="common-list-biaoti ">
						        <div class="common-list-item open-close affix-biaoti nomargin">图片名称</div>
					        </div>
                        </li>
                </ul>
               	<ul class="common-list" id="vodlist">
					  	{if is_array($affix_list) && count($affix_list)>0}
							{foreach $affix_list as $k => $v}		
		                      {template:unit/affixlist}
		                    {/foreach}
						{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;border-top:1px solid #c8d4e0;margin:0 10px">没有您要找的内容！</p>
						<script>hg_error_html(vodlist,1);</script>
		  				{/if}
                </ul>
	            <ul class="common-list">
				     <li class="common-list-bottom clear">
					   <div class="common-list-left">
	                   <input type="checkbox"  name="checkall" value="infolist" title="全选" rowtag="LI" />
				       <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this,'delete','删除',1,'id','','ajax');"    name="batdelete">删除</a>
				    </div>
				   </li>
				</ul>
                   {$pagelink}
				</div>
              </form>
		</div>
</div>
</div>
</body>
<script type="text/javascript">

function hg_call_affix_del(id)
{
	 var ids=id.split(",");
	 for(var i=0;i<ids.length;i++)
	{
		$("#r_"+ids[i]).remove();
	}
}

function hg_delete_thumb_size(path,size_label,affix_id)
{
	var url='./run.php?mid=' + gMid + '&a=delete_thumb_size&affix_id=' + affix_id + '&path='+ path + '&size_label='+ size_label;
	hg_request_to(url)
}

function hg_delete_thumb_size_back(data)
{
	var obj = eval('(' + data + ')');
	$("#thumb_" + obj.affix_id + "_" +obj.thumb_id).remove();
}
</script>
{template:foot}