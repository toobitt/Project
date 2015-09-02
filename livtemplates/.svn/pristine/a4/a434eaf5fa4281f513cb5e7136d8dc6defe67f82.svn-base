{template:head}
{js:2013/ajaxload_new}
{js:2013/list}
{css:2013/list}
<script>
function special_form(id)
{
	window.location.href="./run.php?mid={$_INPUT['mid']}&a=form&infrm=1";
}
</script>
<style type="text/css">
.tuji_pics_show{width:398px;height:300px;background:#000 url({$image_resource}loading7.gif) no-repeat center;border:1px solid gray;position:relative;}
.tip_box{width:200px;height:100px;position:absolute;left:25%;top:-33%;background:none repeat scroll 0 0 #000000;opacity:0.7;display:none;z-index:20;}
.close_tip{position:absolute;left:89%;top:6%;z-index:20;width:15px;height:15px;background: url({$image_resource}hoge_icon.png) no-repeat -185px -18px;overflow:hidden;}
.pic_info{width:95%;height:15%;cursor:pointer;}
.arrL{position:absolute;width:50%;height:100%;cursor:pointer;left:0;top:0;z-index:10;}
.arrR{position:absolute;width:50%;height:100%;cursor:pointer;left:50%;top:0;z-index:10;}
.btnPrev{position:absolute;top:37%;left:12px;width:39px;z-index:15;height:80px;cursor:pointer;background:url({$image_resource}btnL_1.png)}
.btnNext{position:absolute;top:37%;right:12px;width:39px;z-index:15;height:80px;cursor:pointer;background:url({$image_resource}btnR_1.png)}
.btn_l{background:url({$image_resource}btnL_2.png) no-repeat;}
.btn_r{background:url({$image_resource}btnR_2.png) no-repeat;}
.special-slt{width:100px}
.special-ztlj{width:320px}
</style>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
<form action="" method="POST" name="add_special" id="add_special">
	<a class="blue mr10" onclick="special_form()">
		<span class="left"></span>
		<span class="middle"><em class="add">新增热词</em></span>
		<span class="right"></span>
	</a>
</form>
</div>
<div class="content clear">
 <div class="common-list-content">
                {template:unit/hotwordssearch}
                <form method="post" action="" name="listform">
                    <div class="m2o-list">
						<!--排序模式打开后显示排序状态-->
						<div class="m2o-title m2o-flex m2o-flex-center">
					 	   <div id="infotip" class="ordertip">排序模式已关闭</div>
					       <div class="m2o-item m2o-paixu" title="排序">
					        	<a title="排序模式切换/ALT+R" class="common-list-paixu"></a>
					       </div>
			            <div class="m2o-item m2o-flex-one m2o-bt" title="热词名称">热词名称</div>
			            <div class="m2o-item m2o-state" title="状态">状态</div>
			            <div class="m2o-item m2o-time" title="添加人/时间">添加人/时间</div>
			        </div>
	                <div class="m2o-each-list">
					    {if $hotwords_list[0]}
		       			    {foreach $hotwords_list[0] as $k => $v} 
		                      {template:unit/hotwordslist}
		                    {/foreach}
						{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有热词</p>
						<script>hg_error_html(hotwordslist,1);</script>
		  				{/if}
	                </div>
		           <!-- foot，全选、批处理、分页 -->
					<div class="m2o-bottom m2o-flex m2o-flex-center">
					  	 <div class="m2o-item m2o-paixu">
			        		<input type="checkbox" name="checkall" class="checkAll" rowtag="m2o-item" title="全选"/>
			    		</div>
			    		<div class="m2o-item m2o-flex-one list-config">
			    		   <a class="batch-handle">审核</a>
			    		   <a class="batch-handle">打回</a>
			    		   <a class="batch-handle">删除</a>
			    		</div>
			    		<div id="page_size">{$pagelink}</div>
					</div>    	
    			</form>
    			<div class="edit_show">
				<span class="edit_m" id="arrow_show"></span>
				<div id="edit_show"></div>
				</div>
        </div>
</div>
   <div id="infotip"  class="ordertip"></div>
   <div id="getimgtip"  class="ordertip"></div>
<script>
	var data = $.globalListData = {code}echo $hotwords_list[0] ? json_encode($hotwords_list[0]) : '{}';{/code};
    $.extend($.geach || ($.geach = {}), {
        data : function(id){
            var info;
            $.each(data, function(i, n){
               if(n['id'] == id){
                   info = {
                       id : n['id']
                   }
                   return false;
               }
            });
            return info;
        }
    });
    $('.m2o-each').geach();
	$('.m2o-list').glist();
</script>

<script type="text/x-jquery-tmpl" id="m2o-option-tpl">
<div class="m2o-option" data-id="{{= id}}">
    <div class="m2o-option-inner m2o-flex">
        <div class="m2o-btns m2o-flex">
			<div class="m2o-btn-area m2o-flex">
				<a href="./run.php?mid={$_INPUT['mid']}&a=form&id={{= id}}&infrm=1" target="nodewin">编辑</a>
				<a class="option-delete">删除</a>
				<a></a>
				<a></a>
			</div>
			<div class="m2o-option-line"></div>
        </div>
    </div>
	<div class="m2o-option-confirm">
			<p>确定要删除该内容吗？</p>
			<div class="m2o-option-line"></div>
			<div class="m2o-option-confim-btns">
				<a class="confim-sure">确定</a>
				<a class="confim-cancel cancel">取消</a>
			</div>
	</div>
	<div class="m2o-option-close"></div>
</div>
</script>
</body>
{template:foot}