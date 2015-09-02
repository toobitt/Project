//function hg_showAddAuth(appid,title,bundle)
//{
//	if(gDragMode)
//    {
//	   return  false;
//    }
//
////	if(appid)
////	{
////		$('#auth_title').html('编辑auth');
////	}
////	else
////	{	
//	$('#auth_title').html('推送---'+title);
////	}
//
//	if($('#add_auth').css('display')=='none')
//	{
//	   var url = "run.php?mid="+gMid+"&a=recommond&id="+appid+"&title="+title+"&source="+bundle;
//	   hg_ajax_post(url);
//	   $('#add_auth').css({'display':'block'});
//	   $('#add_auth').animate({'right':'50%','margin-right':'-300px'},'normal',function(){
//		 hg_resize_nodeFrame();
//	   });
//	}
//	else
//	{
//		hg_closeAuth();
//	}
//}

//关闭面板
function hg_closeAuth()
{
	$('#add_auth').animate({'right':'120%'},'normal',function(){$('#add_auth').css({'display':'none','right':'0'});hg_resize_nodeFrame();});
}
////放入模板
//function hg_putAuthTpl(html)
//{
//	$('#auth_form').html(html);
//}