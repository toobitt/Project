{template:head}
{css:common/common_list}
{js:common/common_list}
{css:vod_style}
{js:constellation/station}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{code}
	if(!isset($_INPUT['date_search']))
	{
		$_INPUT['date_search'] = 1;
	}
{/code}
<script type="text/javascript">
$(function(){
	tablesort('vodlist','company','order_id');
	$("#vodlist").sortable('disable');
});
</script>

<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
	<a class="blue mr10" href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" target="nodeFrame">
		<span class="left"></span>
		<!--<span class="middle"><em class="add">新增星座</em></span>-->
		<span class="right"></span>
	</a>
</div>

<div class="content clear">
 <div class="f">
          <div class="right v_list_show">
               
                  <span class="serach-btn"></span>
         
         
				
                <form method="post" action="" name="listform">
                <!-- 标题 -->
                   <ul class="common-list public-list-head" id="list_head">
                        <li class="common-list-head clear">
                            <div class="common-list-left">
                                <div class="common-paixu common-list-item"><a class="common-list-paixu" {if !$list['colname']}onclick="hg_switch_order('vodlist');"{/if}  title="排序模式切换/ALT+R"></a></div>
                            	<div class="common-list-item">LOGO</div>
                            </div>
                            <div class="common-list-right">
                           
                                <div class="common-list-item open-close wd60">操作</div>
                            	<!--<div class="common-list-item open-close wd80">站点数量</div>-->
                                
                                <div class="common-list-item open-close vote-tjr wd120">有效时间</div>
                            </div>
                            <div class="common-list-biaoti ">
						        <div class="common-list-item open-close vote-biaoti">星座名称</div>
					        </div>
                        </li>
                    </ul>
                <ul class="common-list public-list" id="vodlist">
				    {if $list}
	       			    {foreach $list as $k => $v}
	                      {template:unit/astrolist}
	                    {/foreach}
					{else}
					<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
					<script>hg_error_html(vodlist,1);</script>
	  				{/if}
				</ul>
	           <ul class="common-list">
				     <li class="common-list-bottom clear">
					   <div class="common-list-left">
	                  <!-- <input type="checkbox"  name="checkall"  value="infolist" title="全选" rowtag="LI" />
	                    <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'audit',  '审核', 1, 'id', '&audit=1', 'ajax','hg_change_status');"    name="bataudit" >审核</a>
				       <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'audit',  '打回', 1, 'id', '&audit=0', 'ajax','hg_change_status');"   name="batgoback" >打回</a> 
				       <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');"    name="batdelete">删除</a>-->
				   </div>
	               {$pagelink}
	            </li>
	          </ul>	
    		</form>
			<div class="edit_show">
			<span class="edit_m" id="arrow_show"></span>
			<div id="edit_show"></div>
			</div>
            </div>
        </div>
</div>
	<div id="infotip"  class="ordertip"></div>
	<div id="getimgtip"  class="ordertip"></div>
</body>
{template:foot}