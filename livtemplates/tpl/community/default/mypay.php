{css:mail-box}
<script>
    jQuery(function(){
		
		 var more=$(".nav-li-more"),
		     secondnav=$(".guanzhu-second-nav");
	     more.mouseover(function(){
			  $(this).addClass("nav-li-more-hover");
			  secondnav.show();
	     });
		 more.mouseout(function(){
			  $(this).removeClass("nav-li-more-hover");
			  secondnav.hide();
	     });
	})
</script>
<div class="my-message-box">
     <div class="my-message-top">
         <span class="fans-title">我关注了<b class="fans-total">32</b>人</span>
         <span class="fans-search-form">
                <input name="textfield" type="text" class="fans-txt" id="fans-txt" value="输入昵称或备注"/>
                <a href="#" class="fans-search-btn"></a>
         </span>
     </div>
     <div class="guanzhu-nav">
             <ul class="guanzhu-nav-list">
                   <li class="nav-li"><a href="#" class="nav-item">全部</a></li>
                   <li class="nav-li"><a href="#" class="nav-item">相互关注</a></li>
                   <li class="nav-li"><a href="#" class="nav-item">未分组</a></li>
                   <li class="nav-li"><a href="#" class="nav-item">吃喝组</a></li>
                   <li class="nav-li"><a href="#" class="nav-item">团购组</a></li>
                   <li class="nav-li"><a href="#" class="nav-item">游天下</a></li>
                   <li class="nav-li-more"><a href="#" class="nav-item">更多<span class="more-j"><img src="{$RESOURCE_URL}qingao/more-j.png" width="6" height="4" /></span></a>
                        <ul class="guanzhu-second-nav">
                              <li><a href="#" class="second-nav-item">朋友组</a></li>
                              <li><a href="#" class="second-nav-item">同事组</a></li>
                              <li><a href="#" class="second-nav-item">车友俱乐部</a></li>
                        </ul>
                   </li>
             </ul>
             <div class="new-group"><a href="#"><!--新建分组--></a></div>
     </div>
<div class="my-message-con">
          <ul class="fans-list">
               <li class="clearfix">
                      <div class="fans-list-left">
                           <a href="#" class="fans-photo"><img src="images/tou.jpg" width="50" height="50" /></a>
                           <a href="#" class="person-message-btn"><!--私信按钮--></a>
                      </div>
                      <div class="fans-list-right">
                            <div class="fans-right-item clearfix">
                                 <span class="fans-name"><a href="#">三宅又一生</a></span>
                                 <span class="fans-place">江苏南京</span>
                                 <span class="fans-attention"></span>
                            </div>
                            <div class="fans-right-item clear">
                                 <span class="number-detail">关注：<a href="#" class="number-total">23</a><em>|</em></span>
                                 <span class="number-detail">粉丝:<a href="#" class="number-total">172</a><em>|</em></span>
                                 <span class="number-detail">行动:<a href="#" class="number-total">5</a></span>
                            </div>
                            <div class="fans-right-item">
                                 <span class="intro"><span class="intr0-title">简介：</span>国际东南亚委内瑞拉危地马拉混血范儿</span>
                            </div>
                            <div class="fans-right-item">
                                 <span class="cancle-btn">取消关注</span>
                            </div>
                      </div>
               </li> 
       </ul>
    </div>
</div>