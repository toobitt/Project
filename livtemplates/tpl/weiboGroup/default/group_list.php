<?php 
/* $Id:group_list.php 9870 2012-06-29 04:58:13Z wangleyuan $ */
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
{css:common/common_list}
{css:circle_list}

<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div class="content clear">
	<div class="f">	
		<div class="right v_list_show">
           <form method="post" action="" name="listform">
                    <ul class="common-list">
                        <li class="common-list-head clear">
                            <div class="common-list-left">
                             	<div class="common-list-item"></div>
                            </div>
                            <div class="common-list-right">
                                <div class="circle-ms common-list-item open-close">授权信息</div>
                            </div>
                            <div class="circle-title">类型名称</div>
                        </li>
                     </ul>
               		 <ul class="list" id="vodlist">
					  	{if is_array($group_list) && count($group_list)>0}
							{foreach $group_list as $k => $v}		
								<li class="common-list-data clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}" >
								    <div class="common-list-left">
								        <div class="common-list-item">
								            <div class="common-list-cell">
								                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"/></a>
								            </div>
								        </div>
								    </div>
								    <div class="common-list-right">
									    <div class="common-list-item circle-ms">
								            <div class="common-list-cell">
								                    <span id="contribute_sort_desc_{$v['id']}">{if $v['can_access']}授权未过期{else}<a href="#">授权已过期,点击此处重新授权</a>{/if}</span>
								            </div>
								        </div>
								    </div>
								    <div class="circle-title overflow" style="cursor:pointer;">
								        <div class="common-list-cell">
								        	 {code}
								        	 	$log = '';
								        		if($v['picurl'])
								        		{
								        			$log = $v['picurl']['host'] . $v['picurl']['dir'] .'80x60/'. $v['picurl']['filepath'] . $v['picurl']['filename'];
								        		}   		
								        	 {/code}
								        	 {if $log}
								        	 	<img src="{$log}" width="40" height="30" style="vertical-align:middle;width:40px;height:30px;margin-right:10px;" />
								        	 {else}
								        	 {/if}
								             <span id="title_{$v['id']}">{$v['name']}</span>
								        </div>          
								   </div>
								</li>
		                    {/foreach}
						{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;border-top:1px solid #c8d4e0;margin:0 10px">没有您要找的内容！</p>
						<script>hg_error_html(vodlist,1);</script>
		  				{/if}
						<li style="height:0px;padding:0;" class="clear"></li>
                	</ul>
             </form>
		</div>
</div>
</div>
</body>
{template:foot}