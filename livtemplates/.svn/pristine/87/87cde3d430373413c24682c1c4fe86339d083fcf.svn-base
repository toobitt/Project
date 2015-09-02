{template:head}
{code}
if(!isset($_INPUT['date_search']))
{
    $_INPUT['date_search'] = 1;
}
{/code}
{css:vod_style}
{js:vod_opration}
{js:jquery-ui-1.8.16.custom.min}

{css:ad_style}
{js:ad}
{css:common/common_list}
{js:common/common_list}
<script type="text/javascript">
	var id = '{$id}';
	var frame_type = "{$_INPUT['_type']}";
	var frame_sort = "{$_INPUT['_id']}";
	function hg_road_delete(id)
	{
		if(confirm('您确定要删除此条记录?'))
		{
			var url = './run.php?mid=' + gMid + '&a=delete&id=' + id + '&infrm=1&ajax=1';
			hg_request_to(url);	
		}
	}
</script>

<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op_program" {if $_INPUT['infrm']}style="display:none"{/if}>
   <a class="blue mr10" href="run.php?mid={$_INPUT['mid']}&a=form{$_ext_link}&token_id={$_INPUT[token_id]}" target="nodeFrame">
		<span class="left"></span>
		<span class="middle"><em class="add">新增消息</em></span>
		<span class="right"></span>
	</a>
</div>


<div class="content clear">
 <div class="f">
          <div class="right v_list_show">
                <form method="post" action="" name="listform">
                    <ul class="common-list">
                        <li class="common-list-head clear">
                            <div class="common-list">
                            	
                            	<div class="common-list-item">发送者</div>
                            	<div class="common-list-item">接受者</div>
                            	<div class="common-list-item"  style="width:400px">内容</div>
                                <div class="common-list-item">创建时间</div>
                            </div>                       
                        </li>
                    </ul>
                    
	                <ul class="common-list" id="contri_sortlist">
		       			{if !empty($formdata)}
		       				{foreach $formdata as $k => $v} 
		                      {template:unit/detail_mail_mail}
		                    {/foreach}
		       			   
						{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
		  				{/if}
	                </ul>
	                
		        	<ul class="common-list">
				    	<li class="common-list-bottom clear">
					 		<div class="common-list-left"></div>
		               		{$pagelink}
		            	</li>
		            </ul>	
    			</form>
           </div>
        </div>
</div>


   <div id="infotip"  class="ordertip"></div>
   <div id="getimgtip"  class="ordertip"></div>
</body>

<script type="text/javascript">
function hg_call_del(id)
{
	 var ids=id.split(",");
	 for(var i=0;i<ids.length;i++)
	 {
		$("#r_"+ids[i]).remove();
	 }
}
</script>
{template:foot}