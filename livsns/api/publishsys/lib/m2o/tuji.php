<?php

/**
 * 根据视频id取图集内容；
 * */
define('M2O_ROOT_PATH','./');
require(M2O_ROOT_PATH . 'global.php');
$id = intval($_REQUEST['id']);
if ($id)
{

    $curl = new curl($gGlobalConfig['App_tuji']['host'], $gGlobalConfig['App_tuji']['dir']);

    $curl->setReturnFormat('json');

    $curl->initPostData();

    $curl->addRequestData('id', $id);

    $ret = $curl->request('tuji.php');

    $tuji = $ret[0];

    if ($tuji['id'])
    {

        $curl->initPostData();

        $curl->addRequestData('tuji_id', $id);

        $curl->addRequestData('a', 'get_tuji_pics');

        $ret = $curl->request('tuji.php');

        $pics = $ret;
    }

    $html = '<div class="news-pic-list"><ul>';

    $shtml = '<div class="news-pic-tab"><div class="list"><ul>';

    $pic   = $title = $spic  = $hover = $show  = '';

    if ($pics)
    {

        foreach ($pics as $k => $v)
        {

            if ($v['img_info'])
            {

                if ($k == 0)
                {

                    $hover = 'class="hover"';

                    $show = 'class="show"';
                }
                else
                {

                    $show  = $hover = '';
                }

                $pic = hg_fetchimgurl($v['img_info'], 530, 0);

                $spic = hg_fetchimgurl($v['img_info'], 104, 61);

                $title = $v['description'] ? $v['description'] : $tuji['title'];

                $title = web_cutchars($title, 26, '', true);

                $html .= '<li ' . $show . '><img src="' . $pic . '" /><span>' . trim($title) . '</span></li>';

                $shtml .= ' <li ' . $hover . '><a><img src="' . $spic . '" /></a></li>';
            }
        }
    }

    $html .= '</ul></div>';

    $html .= $shtml . '</ul></div><a class="news-prev"></a><a class="news-next"></a></div>';
}
?>document.write('<?php echo $html; ?>');
