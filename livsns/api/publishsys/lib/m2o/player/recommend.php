<?php

header('Content-Type: text/xml; charset=UTF-8');
define('M2O_ROOT_PATH', '../');
@include_once(M2O_ROOT_PATH . 'global.php');
//include(M2O_ROOT_PATH . 'lib/class/curl.class.php');
include(M2O_ROOT_PATH . 'lib/class/publishcontent.class.php');
$id = intval($_INPUT['extend']);
if ($id)
{
    $limits = 10;

    $con = array(
        'liv_cel_id' => $liv_cel_id,
        'sort_field' => $sort_field,
        'sort_type' => $sort_type ? $sort_type : 'ASC',
        'bundle_id' => 'livmedia',
        'module_id' => 'vod',
        'need_video' => 'vod',
    );

    $con['is_have_indexpic'] = 1;
    $obj                     = new publishcontent();

    $content           = $obj->get_content($id);
    $con['exclude_id'] = $content['cid'];
    if($content['column_id'])
    {
        $con['column_id'] = $content['column_id'];
    }
    if ($content['keywords'])
    {
        $con['keywords'] = $content['keywords'];
        $liv_data        = $obj->get_content_condition($columnid, $weight, $offset, $limits, $con);
        if ($liv_data && is_array($liv_data))
        {
            echo '<recommend imgBaseUrl="">';
            foreach ($liv_data AS $v)
            {
                $img        = web_get_pic_url($v['indexpic'], '80', '60');
                $link       = $v['content_url'];
                $rep        = array('"', '<', '>');
                $exp        = array("'", '《', '》');
                $v['title'] = str_replace($rep, $exp, $v['title']);
                echo '<item id="' . $link . '" title="' . $v['title'] . '" img="' . $img . '" count="' . $v['click_num'] . '" duration="' . $v['video']['duration'] . '"/>';
            }
            echo '</recommend>';
        }
    }
}
?>