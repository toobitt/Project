{code}extract($list[0]);{/code}{template:head}{js:jqueryfn/jquery.tmpl.min}{js:2013/list}{js:albums_app/comment}{css:2013/list}{css:2013/form}{css:albums_list}{css:choice_area}<script>$(function(){	$(".nav-box:first-child").hide();	$(".serach-btn").click(function(){$(".key-search").toggleClass("key-search-open");});});</script><style>.m2o-item{padding:0;border:0;}.m2o-item:hover{background:none;}</style><body><div class="m2o-form">	<header class="m2o-header">		<div class="m2o-inner">			<div class="m2o-flex m2o-flex-center">				<h1 class="m2o-l">照片{$photo_name}评论</h1>	            <div class="m2o-m m2o-flex-one">	            <!-- 	                <input class="m2o-m-title" name="title" id="title" placeholder="填写名称" style="font-weight:normal;font-style:normal;color:undefined !important;border-bottom-color:undefined !important;" title="">	             -->	            </div>	            <div class="m2o-btn m2o-r">	                <span class="m2o-close option-iframe-back"></span>	            </div>			</div>		</div>	</header>	<div class="m2o-inner">		<div class="albums-comment albums-photos m2o-main m2o-flex">			<aside class="albums-info">				<div class="info-item head">					<a class="head-photo">					{if $user['avatar']}						<img src="{code}echo hg_bulid_img($user['avatar']);{/code}" />					{/if}					</a>					<p class="name">{$address}<br />{$photos_brief}</p>				</div>			</aside>			<section class="comment-list m2o-flex-one">				<div class="outer clear">					<div class="choice-area">						<span class="serach-btn"></span>					    <form name="searchform" id="searchform" action="run.php" method="get">						    <div class="key-search">						    	<input type="text" name="k" id="search_list_k" value="" speech="speech" x-webkit-speech="x-webkit-speech" x-webkit-grammar="builtin:translate">						    	<input type="submit" value="" name="hg_search" style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;">						    </div>							<div class="select-search">								{code}									$attr_status = array(										'class' => 'colonm down_list data_time',										'show' => 'status_show',										'width' => 104,										'state' => 0,									);									$attr_date = array(										'class' => 'colonm down_list data_time',										'show' => 'date_show',										'width' => 104,/*列表宽度*/										'state' => 1,/*0--正常数据选择列表，1--日期选择*/									);									if (!$_INPUT['status']) $_INPUT['status'] = 1;									if (!$_INPUT['date_search']) $_INPUT['date_search'] = 1;								{/code}								{template:form/search_source,status,$_INPUT['status'],$_configs['status'],$attr_status}								{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}								<input type="hidden" name="mid" value="530" />								<input type="hidden" name="photo_id" value="{$_INPUT['photo_id']}" />							  </div>			             </form>					</div>					</div>					<form method="post" action="" name="listform">					<div class="m2o-list">						<div class="m2o-title m2o-flex m2o-flex-center">							<div class="m2o-item m2o-paixu"></div>							<div class="m2o-item m2o-flex-one m2o-bt">评论内容</div>							<div class="m2o-item dx">评论对象</div>							<div class="m2o-item m2o-state">状态</div>							<div class="m2o-item m2o-time">评论人/时间</div>						</div>						<div class="m2o-each-list">							{if $comment}							{foreach $comment as $k => $v}								{template:unit/photo_comment}							{/foreach}							{else}							<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;margin:0 10px">没有您要找的内容！</p>							{/if}						</div>						<div class="m2o-bottom  m2o-flex m2o-flex-center">							<div class="m2o-paixu">			                   <input type="checkbox" title="全选" class="checkAll"/>			                </div>					       	<div class="m2o-batch batch-delete" data-method="delete">删除</div>					       	<div class="m2o-batch batch-audit" data-method="audit">审核</div>					       	<div class="m2o-flex-one">{$pagelink}</div>						</div>		            	</div>		              </form>				</section>		</div>	</div></div></body><script>(function($){	var data = $.globalListData = {code}echo $comment ? json_encode($comment) : '{}';{/code};    $.extend($.geach || ($.geach = {}), {        data : function(id){            var info;            $.each(data, function(i, n){               if(n['id'] == id){                   info = {                       id : n['id']                   }                   return false;               }            });            return info;        }    });})(jQuery);</script><script type="text/x-jquery-tmpl" id="m2o-option-tpl"><div class="m2o-option" data-id="{{= id}}">    <div class="m2o-option-inner m2o-flex">        <div class="m2o-btns m2o-flex">			<div class="m2o-btn-area m2o-flex">				<a class="m2o-delete" data-method="deletePhotoComment">删除</a>				<a></a>				<a></a>				<a></a>			</div>			<div class="m2o-option-line"></div>        </div>    </div>	<div class="m2o-option-confirm">			<p>确定要删除该内容吗？</p>			<div class="m2o-option-line"></div>			<div class="m2o-option-confim-btns">				<a class="confim-sure">确定</a>				<a class="confim-cancel cancel">取消</a>			</div>	</div>	<div class="m2o-option-close"></div></div></script>