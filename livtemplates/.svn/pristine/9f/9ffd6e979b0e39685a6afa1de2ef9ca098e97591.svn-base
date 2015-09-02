{template:head}
{css:interview}
{js:interview_user}
{code}
$total = $formdata['page']['total']['total'];
$count = $formdata['page']['perpage'];
$curpage = intval($_INPUT['offset']);
$data['totalpages'] = $total;
$data['perpage'] = $count;
$data['curpage'] = $curpage;
$extralink = '';
foreach ($_INPUT AS $k => $v)
{
	if ($k != 'mid' && $k != 'hg_search')
	{
		$extralink .= '&amp;' . $k . '=' . $v;
	}
}
$data['pagelink'] = '?mid='. $_INPUT['mid'] . $extralink;
$pagelink = hg_build_pagelinks($data);
$pagelink =  preg_replace('/pp=/', 'offset=', $pagelink);
{/code}
<style>

.biaoz .content .f{min-width:auto;min-height:auto;}
.biaoz .right_1{min-width:auto;}
#pic_list li, #list_head{width:560px;}
#pic_list li .right, #list_head .right{width:auto;}
</style>
<div class="biaoz" style="position:relative;z-index:1;"  id="body_content">	
	<div id="hg_page_menu"   >
	</div>
	
	<div class="content clear"  style="width:600px">
 		<div style="min-width:auto;min-height:auto;">
			<div class="right v_list_show">	<form>		
				<div class="search_a" id="info_list_search">
	                    <div class="right_1">
							<input type="hidden" name="a" value="showUser" />
							<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
							<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
							<input type="hidden" name="_id" value="{$_INPUT['_id']}" />
							<input type="hidden" name="_type" value="{$_INPUT['_type']}" />
	                    </div>
	                    <div class="right_2">
	                    	<div class="button_search">
								<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
	                        </div>
	                        {template:form/search_input,k,$_INPUT['k']}
	                        <!-- 
							<div class="search input clear" id="search">
								<span class="input_left"></span>
								<span class="input_right"></span>
								
								<span class="input_middle">
									<input type="text" name="search_list" id="search_list" />
								</span>
							</div>
							 -->                       
	                    </div>	                    
	                </div></form>
	
	                
	                
	                <div class="list_first clear"  id="list_head" >
	                    <span class="left" style="width:120px">
	                    	<a class="lb" style="cursor:pointer;">
	                    		<em></em>
	                    		<a class="fl">头像</a>
	                    	</a>
	                    </span>
                        <span class="right"> 
                        	<a class="fl">加入访谈</a>
                       </span>
                        <span class="title overflow">
                        	 <a>用户名</a>
                        </span>                        	                                 
	                </div>	                
	                <form method="post" action="" name="pos_listform">
		                <ul class="list" id="pic_list">
							{if $formdata['userinfo']}
			       			   	    {foreach $formdata['userinfo'] as $k => $v}		       			    
			                      {template:unit/interview_manage_user_list}
			                    {/foreach}
			  				{/if}
							
							<li style="height:0px;padding:0;" class="clear"></li>
		                </ul>
			            <div class="bottom clear">
			               <div class="left">
			                   <input type="checkbox"  name="checkalluser" id="checkall" value="infolist" title="全选" rowtag="LI" onclick="hg_alluser(this)" />
			                   <a style="cursor:pointer;" onclick="hg_add_more(interview_id={$_INPUT['id']})">加入访谈</a>
						   </div>
			           	   {$pagelink}
			            </div>	
	    		    </form>
				</div>
			</div>
		</div>
</div>
{template:foot}