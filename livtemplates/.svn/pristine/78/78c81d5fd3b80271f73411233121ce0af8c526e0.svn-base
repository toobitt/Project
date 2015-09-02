{template:head}

{css:common/common_list}
{css:vod_style}
{css:edit_video_list}
{js:common/list}
<style type="text/css">
.common-list-i{top:12px;}
.edit_show .info.vider_s span{z-index:10000;}
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
.special-slt{width:60px}
.special-ztlj{width:320px}
.special-biaoti-overflow{max-width:400px;}
.special-biaoti a{font-size:14px;}
.module{width: 120px;}

.extend-buttons{position:absolute;right:0px;top:6px;z-index:1;}
</style>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
</div>

<div class="content clear">
 <div class="common-list-content" style="min-width:auto;">
          <div class="right v_list_show">
                <form method="post" action="" name="listform" style="position:relative;">
                   <!-- 标题 -->
                   <ul class="common-list" id="list_head">
                         <li class="common-list-head clear">
                            <div class="common-list-biaoti ">
						        <div class="common-list-item open-close special-biaoti">标题</div>
					     	</div>
                        </li>
                   
                    </ul>
	               
	                <ul class="common-list hg_sortable_list" id="logslist" data-order_name="orderid">
					    {if $logs_list}
		       			    {foreach $logs_list as $k => $v} 
							<li class="common-list-data">
		                         <div class="common-list-biaoti">
									<div class="common-list-item special-biaoti biaoti-transition">
										<div class="common-list-cell">
											{$v['title']} <!-- {$v['create_time']} -->
										</div>  
									</div>
								 </div>
							</li>
		                    {/foreach}
						{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有日志信息</p>
		  				{/if}
	                </ul>
		            
		            <ul class="common-list">
				     <li class="common-list-bottom clear">
		               {$pagelink}
		            </li>
		          </ul>	
		          <div class="edit_show">
					<span class="edit_m" id="arrow_show" style="position:absolute;"></span>
					<div id="edit_show"></div>
				  </div>
    			</form>
    			
           </div>
        </div>
</div></body>
{template:foot}