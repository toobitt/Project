{template:head}
{js:vod_opration}
{js:share}
{css:common/common_list}
{js:common/common_list}
{css:vod_style}
{css:edit_video_list}
{css:special}
{code}
$list=$special_content_list[0];
$columns=$columns[0];
{/code}
<body>
    <div class="special-column－area">
              <form method="post" action="" name="listform">
	                    <div class="head-title">
			               <span class="name">{$columns[$k]}</span>
			               <span class="number">全部<a href="">{$v[0]['count']}</a>条</span>
			             </div>
		                <ul class="common-list public-list">
						    {if $v}
			       			    {foreach $v as $kk => $vv} 
			                      {template:unit/specialcontlist}
			                    {/foreach}
		  				   {/if}
	                </ul>
	                <ul class="common-list public-list" style="margin:0 10px;width:auto;">
				     <li class="common-list-bottom clear" style="padding-left:10px;border:0;">
					   <div class="common-list-left">
		                   <input type="checkbox"  name="checkall" value="infolist" title="全选" rowtag="LI" />
		                   <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id','', 'ajax');"    name="batdelete">删除</a>
						   </div>
			            </li>
			        </ul>
    			</form>
   </div>
</body>