<?php
function hg_rewrite($html)
{

	//return $html; 
	$html = preg_replace("/[?]m=picture(?:&amp;|&)picture_id=([0-9]+)(?:&amp;|&)albums_id=([0-9]+)(?:&amp;|&)v=([a-z]+)/i", "picture-\\1-\\2-\\3.html", $html); //相册的上下页
	$html = preg_replace("/[?]m=picture(?:&amp;|&)picture_id=([0-9]+)/i", "picture-\\1.html", $html);//具体的相册图片
 
	$html = preg_replace("/[?]m=albums(?:&amp;|&)albums_id=([0-9]+)(?:&amp;|&)a=albums_view(?:&amp;|&)view_mode=([0-9]+)((?:&amp;|&)pp=([0-9-]*)){0,1}/i", "albums-show-\\1-\\2-\\4.html", $html);//某个相册的内容页的大小图模式分页
	$html = preg_replace("/[?]m=albums(?:&amp;|&)albums_id=([0-9]+)(?:&amp;|&)a=albums_view(?:&amp;|&)view_mode=([0-9]+)/i", "albums-show-\\1-\\2.html", $html);//某个相册的内容页的大小图模式
	$html = preg_replace("/[?]m=albums(?:&amp;|&)albums_id=([0-9]+)(?:&amp;|&)a=albums_view/i", "albums-show-\\1.html", $html);//某个相册的内容页
	$html = preg_replace("/[?]m=albums(?:&amp;|&)albums_id=([0-9]+)(?:&amp;|&)a=albums_comments(?:&amp;|&)pp=([0-9-]+)/i", "albums-c-\\1-\\2.html", $html);//某个相册的评论的分页
	$html = preg_replace("/[?]m=albums(?:&amp;|&)albums_id=([0-9]+)(?:&amp;|&)a=albums_comments/i", "albums-c-\\1.html", $html);//某个相册的评论
	$html = preg_replace("/[?]m=albums(?:&amp;|&)albums_id=([0-9]+)(?:&amp;|&)a=picture_upload/i", "albums-pu-\\1.html", $html);//图片上传
	$html = preg_replace("/[?]m=albums(?:&amp;|&)albums_id=([0-9]+)(?:&amp;|&)a=albums_admin/i", "albums-ad-\\1.html", $html);//相册管理
	$html = preg_replace("/[?]m=albums(?:&amp;|&)a=show_user(?:&amp;|&)user_id=([0-9]+)/i", "albums-u-\\1.html", $html);//某个用户的相册
	$html = preg_replace('/href="(\w+)?[?]m=albums(?:&amp;|&)a=([a-z0-9]+)(?:&amp;|&)rc_category_id=([0-9]+)"/i', 'href="\\1albums-rc-\\3.html"', $html);//相册分类
	$html = preg_replace('/href="(\w+)?[?]m=albums(?:&amp;|&)a=([a-z0-9]+)"/i', 'href="\\1albums-s-\\2.html"', $html);//相册类型
	$html = preg_replace("/[?]m=albums/i", "albums.html", $html);//相册
	
    $html = preg_replace("/user.php[?]user_id=([0-9]+)(?:&amp;|&)pp=([0-9-]+)/i", "user-\\1-\\2.html", $html);//用户页-id
    $html = preg_replace("/user.php[?]user_id=([0-9]+)/i", "user-\\1.html", $html);//用户页-id
  //  $html = preg_replace('/href="(.*?)user.php[?]name=(.*?)"/i', 'href="\\1user-0-\\2.html"', $html);//用户页-name
    $html = preg_replace("/video_play.php[?]id=([0-9]+)/i", "video-\\1.html", $html);//视频播放
    $html = preg_replace("/station_play.php[?]sta_id=([0-9]+)(#{0,1}[0-9]+){0,1}/i", "station-\\1.html\\2", $html);//频道播放
    $html = preg_replace("/show.php[?]id=([0-9]+)/i", "status-\\1.html", $html);//微博内容页
	
    $html = preg_replace("/[?]m=group(?:&amp;|&)a=show_my_page[(?:&amp;|&)]*/i", "my_group.html", $html);//我的地盘
    $html = preg_replace("/[?]m=group(?:&amp;|&)a=g_list[(?:&amp;|&)]*/i", "groups.html", $html);//地盘列表
    $html = preg_replace("/[?]m=group(?:&amp;|&)a=show[(?:&amp;|&)]*/i", "/", $html);//某个地盘的显示

    $html = preg_replace("/[?]m=thread(?:&amp;|&)thread_id=([0-9]+)(?:&amp;|&)a=detail(?:&amp;|&)group_id=[0-9]+(?:&amp;|&)pp=([0-9-]*)/i", "thread-\\1-\\2.html", $html);
    $html = preg_replace("/[?]m=thread(?:&amp;|&)thread_id=([0-9]+)(?:&amp;|&)a=detail(?:&amp;|&)group_id=([0-9]+)(#{0,1}[0-9]+){0,1}/i", "thread-\\1.html\\3", $html);
    $html = preg_replace("/[?]m=thread(?:&amp;|&)thread_id=([0-9]+)(?:&amp;|&)a=detail(?:&amp;|&)/i", "thread-\\1.html", $html);
    $html = preg_replace("/[?]m=thread(?:&amp;|&)group_id=([0-9]+)(?:&amp;|&)type_id=([0-9]+)(?:&amp;|&)pp=([0-9-]*)/i", "group-\\1-\\2-\\3.html", $html);
    $html = preg_replace("/[?]m=thread(?:&amp;|&)group_id=([0-9]+)(?:&amp;|&)pp=([0-9-]*)/i", "group-\\1-1-\\2.html", $html);
    $html = preg_replace("/[?]m=thread(?:&amp;|&)type_id=([0-9]+)(?:&amp;|&)group_id=([0-9]+)[(?:&amp;|&)]*/i", "group-\\2-\\1.html", $html);
    
    $html = preg_replace("/[?]m=thread((?:&amp;|&)a=show){0,1}(?:&amp;|&)group_id=([0-9]+)(?:&amp;|&)gp=([0-9-]+)/i", "thread-\\2-gp-\\3.html", $html);
    $html = preg_replace("/[?]m=thread(?:&amp;|&)group_id=([a-z0-9]+)[(?:&amp;|&)]*/i", "group-\\1.html", $html);
    
    $html = preg_replace("/[?]m=thread(?:&amp;|&)a=(\w+)(?:&amp;|&)group_id=([0-9]+)/i", "thread-\\1-\\2.html", $html);
    $html = preg_replace("/[?]m=thread(?:&amp;|&)thread_id=([0-9]+)(?:&amp;|&)a=edit(?:&amp;|&)group_id=([0-9]+)/i", "thread-\\1-0-\\2.html", $html);

    $html = preg_replace("/[?]m=thread(?:&amp;|&)action_id=([0-9]+)(?:&amp;|&)a=detail_action(?:&amp;|&)group_id=([0-9]+)(?:&amp;|&)thread_id=([0-9]+)/i", "activitys-0-\\1-\\2.html", $html);

    $html = preg_replace("/[?]m=activitys(?:&amp;|&)a=show_create(?:&amp;|&)type=([0-9]+)(?:&amp;|&)group_id=([0-9]+)/i", "activitys-\\1-\\2.html", $html);
    
    
	$html = preg_replace("/[?]m=activitys(?:&amp;|&)a=my_activity(?:&amp;|&)thread_id=([0-9]+)(?:&amp;|&)action_id=([0-9]+)(?:&amp;|&)group_id=([0-9]+)/i", "activitys-m-\\1-\\2-\\3.html", $html);
	$html = preg_replace("/[?]m=activitys(?:&amp;|&)a=join_interest(?:&amp;|&)thread_id=([0-9]+)(?:&amp;|&)action_id=([0-9]+)(?:&amp;|&)type=([0-9]+)/i", "activitys-j-\\1-\\2-\\3.html", $html);
	$html = preg_replace("/[?]m=activitys(?:&amp;|&)a=join_interest(?:&amp;|&)thread_id=([0-9]+)(?:&amp;|&)action_id=([0-9]+)/i", "activitys-i-\\1-\\2.html", $html);
	$html = preg_replace("/[?]m=activitys(?:&amp;|&)a=show_create(?:&amp;|&)thread_id=([0-9]+)(?:&amp;|&)action_id=([0-9]+)(?:&amp;|&)group_id=([0-9]+)/i", "activitys-e-\\1-\\2-\\3.html", $html);
    
        
    $html = preg_replace("/[?]m=(\w+)(?:&amp;|&)group_id=([a-z0-9]+)[(?:&amp;|&)]uncheck=([0-9]+)[(?:&amp;|&)]*/i", "\\1-\\2-1.html", $html);
    $html = preg_replace("/[?]m=(\w+)(?:&amp;|&)group_id=([a-z0-9]+)[(?:&amp;|&)]uname=(\w+)[(?:&amp;|&)]*/i", "\\1-\\2-0-\\3.html", $html);
    $html = preg_replace("/[?]m=(\w+)(?:&amp;|&)group_id=([a-z0-9]+)[(?:&amp;|&)]*/i", "\\1-\\2.html", $html);
	return $html;
}
?>