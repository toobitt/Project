{code}
extract($list[0]);
//print_r($list);
{/code}
{template:head}
{css:pic_list}
{css:albums_list}
{css:choice_area}
{css:2013/form}
{js:2013/ajaxload_new}
{js:2013/list}
{js:vod_opration}
{js:common/common_list}
{js:albums_app/albums_list}
<body>
<script>
jQuery(function(){
	$(".nav-box:first-child").hide();
	$(".serach-btn").click(function(){$(".key-search").toggleClass("key-search-open");});
});
</script>
		
<div class="m2o-form">
	<header class="m2o-header">
		<div class="m2o-inner">
			<div class="m2o-title m2o-flex m2o-flex-center">
				<h1 class="m2o-l">{$albums_name}</h1>
	            <div class="m2o-m m2o-flex-one">
	            <!-- 
	                <input class="m2o-m-title" name="title" id="title" placeholder="填写名称" style="font-weight:normal;font-style:normal;color:undefined !important;border-bottom-color:undefined !important;" title="">
	             -->
	            </div>
	            <div class="m2o-btn m2o-r">
	                <span class="m2o-close option-iframe-back"></span>
	            </div>
			</div>
		</div>
	</header>
	<div class="m2o-inner">
		<div class="albums-comment albums-photos m2o-main m2o-flex" style="background:#f0f0f0">
			<aside class="albums-info">
				<div style="margin-bottom:20px;">
				<a class="head-photo">
					{code}
					$src = $user['avatar']['host'].$user['avatar']['dir'].$user['avatar']['filepath'].$user['filename']['host'];
					{/code}	
					<img src="{$src}" />
				</a>
				</div>
				<!--
				<a class="all-pic" href="./run.php?mid={$_INPUT['mid']}&a=viewAlbumsComment&id={$id}&comment=1">所有评论({$comment_total})</a>
				-->
				<a class="all-pic" href="./run.php?mid=529&albums_id={$id}">所有评论({$comment_total})</a>
			</aside>
			<section class="m2o-flex-one albums-list" style="border-left:1px solid #ccc">
			<div class="outer clear">
			<div class="choice-area">
				<span class="serach-btn"></span>
			    <form name="searchform" id="searchform" action="run.php" method="get">
				    <div class="key-search">
				    	<input type="text" name="k" id="search_list_k" value="" speech="speech" x-webkit-speech="x-webkit-speech" x-webkit-grammar="builtin:translate">
				    	<input type="submit" value="" name="hg_search" style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;">
				    </div>
					<div class="select-search">
						{code}
							$attr_status = array(
								'class' => 'colonm down_list data_time',
								'show' => 'status_show',
								'width' => 104,
								'state' => 0,
							);
							$attr_date = array(
								'class' => 'colonm down_list data_time',
								'show' => 'date_show',
								'width' => 104,/*列表宽度*/
								'state' => 1,/*0--正常数据选择列表，1--日期选择*/
							);
							if (!$_INPUT['status']) $_INPUT['status'] = 1;
							if (!$_INPUT['date_search']) $_INPUT['date_search'] = 1;
						{/code}
						{template:form/search_source,status,$_INPUT['status'],$_configs['status'],$attr_status}
						{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
						<input type="hidden" name="mid" value="528" />
						<input type="hidden" name="albums_id" value="{$_INPUT['albums_id']}" />
					  </div>
	             </form>
			</div>
			</div>
				<ul class="list_img photo-list clearfix handle-list" id="pictures_list">
				{if $photos}
					{foreach $photos as $k => $v}
						{template:unit/albumspiclist}
					{/foreach}
				{else}
					<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">该相册没有图片！</p>
					<script>hg_error_html(pictures_list,1);</script>
				{/if}
				</ul>
				<div class="bottom clear" style="padding-left:20px;">
	            	<div class="left" style="width:400px;">
                		<input type="checkbox"  name="checkall" id="checkall" class="checkAll" value="infolist" title="全选" rowtag="LI" style="vertical-align: middle;"/>
                		<a name="delete" data-method="audit" class="batch-handle">审核</a>
						<a name="delete" data-method="delete" class="batch-handle">删除</a>
					</div>
					{$pagelink}
            	</div>
			</section>
		</div>
	</div>
</div>
</body>
{template:foot}

