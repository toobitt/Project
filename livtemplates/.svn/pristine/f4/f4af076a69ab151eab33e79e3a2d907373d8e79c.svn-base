 {css:common/common_list}
 {js:common/common_list}
 {code}
 	$cell = $formdata['cell'];
 	$cell_mode = $formdata['cell_mode'];
 	$data_source = $formdata['data_source'];
 	$site_id = $formdata['site_id'];
 	$page_id = $formdata['page_id'];
 	$page_data_id = $formdata['page_data_id'];
 	$content_type = $formdata['content_type'];
 {/code}
 <form method="post" action="" name="listform">
       <ul class="common-list">
          <li class="common-list-head clear">
          		<div class="common-list-left">
          			<div class="common-list-item" style="width:35px;"></div>
             	</div>
             	<div class="common-list-right">
             		<div class="common-list-item open-close">操作</div>
					<div class="common-list-item open-close" style="width:120px;">添加人/添加时间</div>
				</div> 
                <div class="common-list-biaoti">
                    <div class="common-list-item open-close">单元名称</div>
                </div>                
           </li>
       </ul>
	   <ul class="common-list">
	   		{if $cell}
		       	{foreach $cell as $k => $v} 
		           {template:unit/celllist}
		        {/foreach}
			{else}
				<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
				<script>hg_error_html(vodlist,1);</script>
	  		{/if}
       </ul>
	   <ul class="common-list">
			<li class="common-list-bottom clear">
				<div class="common-list-left">
					<input type="checkbox"  name="checkall" value="infolist" title="全选" rowtag="LI" />
					<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this,'delete','删除',1,'id','','ajax','hg_remove_row');"    name="batdelete">删除</a>
				</div>
	           {$pagelink}
	        </li>
	   </ul>	
</form> 
