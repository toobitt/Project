{template:head}
{code}
$list = $transcode_center_list;
//print_r($list);
{/code}
{css:vod_style}
{js:jquery.switchable-2.0.min}
{js:switch_widget}
{js:vod_opration}
{js:transcode_center/transcode}
{css:edit_video_list}
{css:common/common_list}
{js:common/common_list}
{css:2013/index}
{css:2013/iframe}
{css:transcode_list}
<script>
/*$(function($){
		var transcode_switch=$('.transcode-switch');
		transcode_switch.each(function(){
			$(this).switchButton();
		});
})*/
</script>
<script>
$(function(){
	(function($){
		var search = $('#transcode_info_list_search'),
		    box=$('.key-search');
		search.find('.serach-btn').click(function(){
			var btn = $(this), open;
			
			open = btn.data('open');
			btn.data('open', !open);
			box[open ? 'removeClass' : 'addClass']('key-search-open');
		});
	})($);
});
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
	<div class="choice-area" id="transcode_info_list_search" style="position:absolute;top:1px;left:1px;height:43px;">
		{template:unit/transcode_search}
	</div>

<div class="wrap clear">
      <div class="f">
 
 		<!-- 新增分类面板 开始-->
 		 <div id="add_auth"  class="trans-module">
 		 	<h2><span class="trans-module-close" onclick="hg_closeAuth();"></span><span id="auth_title">新增转码服务器</span></h2>
 		 	<div id="add_auth_tpl" class="add_collect_form trans-module-body">
 		 	   <div class="collect_form_top info  clear" id="auth_form"></div>
 		 	</div>
		 </div>
 		 <!-- 新增分类面板结束-->
 		 
          <div class="v_list_show">
                <form method="post" action="" name="vod_sort_listform">
	                <ul id="auth_form_list" class="transcode-list">
	                   {if $list}
		       			    {foreach  $list  as $k => $v}
		                      {template:unit/transcode_centerlist}
		                    {/foreach}
		                {/if}
	                </ul>
	                <div class="add-transcode" onclick="hg_showAddAuth(0);">新增转码服务器</div>
		            <ul class="common-list" style="display:none;">
				     <li class="common-list-bottom clear">
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
<div class="trans-module middle-module">
     <h2><span class="trans-module-close" id="trans-close"></span><span id="trans_title"></span></h2>
     <div class="middle-module-body">
          <iframe name="transFrame" id="transFrame" frameborder="no" scrolling="no" hidefocus="hidefocus" allowtransparency="true"></iframe>
          <img src="{$RESOURCE_URL}loading2.gif" id="top-loading" style="top: 160px; display: none;">
     </div>
</div>
</body>
{template:foot}