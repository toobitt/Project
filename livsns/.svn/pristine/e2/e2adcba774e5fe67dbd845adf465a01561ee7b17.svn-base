<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: show.php 2093 2011-02-16 09:45:49Z yuna $
***************************************************************************/
require('../../conf/global.conf.php');
require('../../lib/func/functions.php');
$uid = intval($_GET['user_id']);
$type = intval($_GET['type']);
$t_index = array(
	0 => 'larger',	
	1 => 'middle',	
	2 => 'small',	
	3 => 'larger',	
);
$avatar_url = hg_avatar($uid, $t_index[$type], $uid . '.jpg');
header('Location:' . $avatar_url);
?>