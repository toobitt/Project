<?php 
/* $Id:list.php 9870 2012-06-29 04:58:13Z wangleyuan $ */
?>
{template:unit/head}
{code}
if(!$_INPUT['date_search'])
{
	$_INPUT['date_search'] = 1;
}
{/code}
{css:twitter}
<body class="user"  style="position:relative;z-index:1"  id="body_content">
<div class="_left">{template:unit/user_menu}</div>
<div class="_mid"><iframe frameborder="no" scrolling="no" hidefocus="hidefocus" allowtransparency="true" _src="./user.php?a=my_index" autocomplete="off" name="nodeFrame" id="nodeFrame" class="winframe" style="width:100%;"></iframe></div>
<div class="_right twitter-right">{template:unit/twitter_right}</div>
</body>
<script type="text/javascript">
/*
jQuery(function($){
   $(".menu li").click(function(){
	   $(".menu li").each(function(){
		   $(this).removeClass('on');
	   });
	   $(this).addClass('on');
   });
});
jQuery(function($){
    var viewHeight=$(window).height();
        _midHeight=viewHeight-100;
    $('._mid').css({height:_midHeight+'px'});
});
*/
</script>
{template:foot}