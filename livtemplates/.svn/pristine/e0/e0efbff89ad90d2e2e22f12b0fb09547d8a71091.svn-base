<?php 
?>
{template:head}
{code}
if(!$_INPUT['date_search'])
{
	$_INPUT['date_search'] = 1;
}
{/code}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{css:vod_style}
{css:edit_video_list}
{css:mark_style}

{js:tree/animate}
{code}
	$_type_ = $access_list['_type_'];
	unset($access_list['_type_']);
{/code}
<script type="text/javascript">

    var id = '{$id}';
    var frame_type = "{$_INPUT['_type']}";
    var frame_sort = "{$_INPUT['_id']}";
    $(function(){
	   if ($('#search_list_key').val())
	   {
		   var key = $('#search_list_key').val();
		   var url = './run.php?mid=' + gMid + '&infrm=1&key=' + key + '&_type={$_type_}';
		   $('#access_nums_asc').attr('href', url+'&access_nums=1');
		   $('#access_nums_desc').attr('href', url+'&access_nums=0');
	   }
    });
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op_program" {if $_INPUT['infrm']}style="display:none"{/if}>
</div>
<div class="content clear">
	<div class="f">			
          <div class="right v_list_show">
		  	<div id="infotip"  class="ordertip"></div>
	        <div id="getimgtip"  class="ordertip"></div>
			<div class="search_a" id="info_list_search">
				<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
					<div class="right_1">
						
						<input type="hidden" name="a" value="show" />
						<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
						<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
						<input type="hidden" name="_id" value="{$_INPUT['_id']}" />
						<input type="hidden" name="_type" value="{$_INPUT['_type']}" />
					  </div>
					  <div class="right_2">
						<div class="button_search">
							<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
						</div>
						{template:form/search_input,key,$_INPUT['key']}                        
					  </div>
	             </form>
			</div>

			<div class="list_first clear"  id="list_head">
                  <span class="left">
                    </span>
                    <span class="right" style="width:650px;">
	                     <a class="fl" style="width:120px;">所属应用</a>
	                     <a class="fl" style="width:120px;">访问类型</a>
						 <a class="fl" style="width:120px;">访问客户端</a>
						 <a class="fl" style="width:120px;">访问IP</a>
						 <a class="fl" style="width:120px;">访问人/访问时间</a>
                   </span>
                   <a style="margin-top: 10px;" class="title">访问地址</a>  
           </div>

              <form method="post" action="" name="listform">
               		 <ul class="list" id="vodlist">
					  	{if is_array($record_list) && count($record_list)>0}
							{foreach $record_list as $k => $v}		
		                      {template:unit/recordlist}
		                    {/foreach}
						{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;border-top:1px solid #c8d4e0;margin:0 10px">没有您要找的内容！</p>
						<script>hg_error_html(vodlist,1);</script>
		  				{/if}
						<li style="height:0px;padding:0;" class="clear"></li>
                	</ul>
	            <div class="bottom clear">
	               <!--<div class="left" style="width:400px;">
	                   <input type="checkbox"  name="checkall" id="checkall" value="infolist" title="全选" rowtag="LI" />
				   </div>-->
                   {$pagelink}
				</div>
              </form>

			  <div class="edit_show" style="">
			  <span class="edit_m" id="arrow_show"></span>
			  <div id="edit_show"></div>
			  </div>
		</div>
</div>
</div>
</body>
<script type="text/javascript">
function hg_call_access_del(id)
{
	 var ids=id.split(",");
	 for(var i=0;i<ids.length;i++)
	 {
		$("#r_"+ids[i]).remove();
	 }
}
</script>
{template:foot}