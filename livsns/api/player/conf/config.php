<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/

define('VOD_AD_GROUP', 'vod_player');//点播广告分组标志
define('LIVE_AD_GROUP', 'liv_player');//点播广告分组标志
define('ANTILEECH', ''); //防盗链http://www.xxx.com/m2o/player/drm.php
define('CURDOMAIN', '');
define('APPID', '55');
define('APPKEY', 'GLtPX7N7ijwb83wupXuIrEl1YvIeBbm7');
define('CACHE_TIME', 0);
define('MAINFEST_F4M','manifest.m3u8');//标注视频文件
$gGlobalConfig['default_preview_img'] = '';
$gGlobalConfig['App_cloudvideo'] = array(
'host'=>'localhost',
'dir'=>'cloudvideo/index.php/api/play/',
);
?>