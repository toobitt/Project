function hg_OverTechnicalReview(html)
{
	if($('#technical_review').css('display')=='none')
	{
	   $('#technical_info').html(html);
	   $('#technical_review').css({'display':'block'});
	   $('#technical_review').animate({'right':'50%','margin-right':'-300px'},'normal',function(){
		 hg_resize_nodeFrame();
	   });
	}
}

function hg_closeTechnicalReviewTpl()
{
	 $('#technical_review').animate({'right':'120%'},'normal',function(){$('#technical_review').css({'display':'none','right':'0'});hg_resize_nodeFrame();});
}