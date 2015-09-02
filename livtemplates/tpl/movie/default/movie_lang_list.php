{template:head}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{js:share}
{css:vod_style}
{css:edit_video_list}
{css:common/common_list}
{code}
$list = $movie_lang_list[0];
{/code}

<script>
gBatchAction['delete'] = "./run.php?mid=252&a=lang_delete";
</script>

<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
	<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
		<span type="button" class="button_6" >
			<a style="display:block" href="./run.php?mid={$_INPUT['mid']}&a=lang_detail&infrm=1&nav=1">
				<strong>新增语言</strong>
    		</a>
    	</span>	
	</div>
	<div class="content clear">
		<div class="f">
			<div class="right v_list_show">
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
							{template:form/search_input,keyword,$_INPUT['keyword']}                        
	                    </div>
					</form>
				</div>
			{if !$list}			
				<p style="color: #da2d2d; text-align: center; font-size: 20px; line-height: 50px; font-family: Microsoft YaHei;">没有您要找的内容！</p>
				<script>hg_error_html('p',1);</script>	
			{else}
				<form method="post" action="" name="listform">
					<ul class="common-list">
						<li class="common-list-head clear">
							<div class="common-list-left">
								<div class="common-list-item"></div>
								<div class="common-list-item name">语言名</div>
								<div class="common-list-item">操作</div>
							</div>
						</li>
					</ul>
					<ul class="common-list">
					{foreach $list as $k => $v}
						{template:unit/langlist}
					{/foreach}
					</ul>
					<div class="bottom clear">
		            	<div class="left">
	                   		<input type="checkbox"  name="checkall" id="checkall" value="infolist" title="全选" rowtag="LI" />
				       		<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id','', 'ajax');">删除</a>
				   		</div>
		               {$pagelink}
		            </div>
				</form>
			{/if}
			</div>
		</div>
	</div>
	<div id="infotip"  class="ordertip"></div>
	<div id="getimgtip"  class="ordertip"></div>
</body>
{template:foot}