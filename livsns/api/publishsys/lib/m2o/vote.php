<?php

/**
 * 根据投票id取投票内容；
 * */
define('M2O_ROOT_PATH','./');
require(M2O_ROOT_PATH . 'global.php');
$id = intval($_REQUEST['id']);
$dReturn = intval($_REQUEST['d_return']);
if ($id) {
    include(M2O_ROOT_PATH . 'lib/class/vote.class.php');
    $obj = new vote();
    $vote = $obj->singleQuestionConti($id);
    // echo "<pre>";
    // print_r($vote);
    // echo "</pre>";exit;
    if ($dReturn) {
       echo json_encode($vote); 
       exit;  
    }
   
    $options = $vote['option_title'];
    //投票结果
    $html = '<ul class="vt_ul">';
    $i=0;
    if (is_array($options) && count($options) > 0 ) {
        foreach ($options AS $kk => $vv) {
            $i++;
            $precent = @round($vv['ini_single']/$vote['question_total_ini']*100,1);
            $rand = rand(1,10);
            $html .= '<li class="vote-item-'.$vv['id'].'"><div class="vote-option-li">'.$vv['title'].':</div> <div class="vote-option-outer-box"><div class="vote-option-inner-box vote-option-color'.$rand.'" style="width:' . $precent . '%;"></div></div> <div class="vote-option-text-box">(' . $precent . '%)<span>' . $vv['ini_single'] . ' 票</span></div></li>';
        }
    }

    $html .= "</ul>";
    $html .= '<div class="tpbtnbg"><input type="button" value="返回投票" class="vote-question-back"/></div>';
    //投票结果
    
    //投票
    $expired = false;
    if ($vote['end_time'] && strtotime($vote['end_time']) < TIMENOW) {
        $expired = true;
    }
    if ($expired) {
        $str = '<div class="vote-expired"><div style="display:none;" id="vote-list">';
    }
    else {
        $str = '<div class="m2o-vote"><div style="display:block;" id="vote-list">';
    }
    $str .= '<form class="m2o-vote-'.$vote['id'].'" name="voteQuestion" id="voteQuestion" method="post" enctype="multipart/form-data" action="http://'.$gGlobalConfig['v_site']['site_info']['url']. '/m2o/voting.php"><div><h3>投票：' . $vote['title'] . '</h3><ul>';
    $type = $vote['option_type'] == 1 ? 'radio' : 'checkbox';
    if (is_array($options) && count($options) > 0 ) {
        foreach ($options AS $i => $opt) {
            $str .= '<li id="vote-item-'.$opt['id'].'"><div class="v-option"> <input type="'.$type.'" value="' . $opt['id'] . '" id="single_total_' . $opt['id'] . '" name="single_total" />' . $opt['title'] . '</div>';
            $str .= '<div class="v-brief">'.$opt['describes'].'</div>';
            if($opt['other_info'] && $opt['other_info']['pictures'])
            {
                $str .= '<div class="v-img">';
                foreach($opt['other_info']['pictures'] as $pic)
                {
                    $img = hg_fetchimgurl($pic,400);
                    $str .= '<span class="s-img"><img src="'.$img.'"/></span>';
                }
                $str .= '</div>';
            }
            if($opt['other_info'] && $opt['other_info']['publishcontents'])
            {
                $str .= '<div class="v-img">';
                foreach($opt['other_info']['publishcontents'] as $pic)
                {
                    $img = hg_fetchimgurl($pic['pic_arr'],400);
                    $str .= '<span class="s-img"><a href="'.$pic['content_url'].'"><img src="'.$img.'"/></span></a>';
                }
                $str .= '</div>';
            }
        $str .= '</li>';
        }
    }
    $str .= '</ul>';
    $str .= '<div class="vote-question-action"><input type="submit" value="我来投票" class="vote-question-submit" /><input  type="button" value="投票结果" class="vote-question-result"/></div></div> <input type="hidden" name="a" value="submitQuestion" id="a" /> <input type="hidden" min="'.$vote['min_option'].'" max="'.$vote['max_option'].'" name="id" value="' . $vote['id']. '" /> <input type="hidden" name="ip" value="' . $_SERVER['REMOTE_ADDR'] . '" /></form><div class="vote-question-total">参与人：<span id="allvotes">' . $vote['person_total'] . '人</span></div></div>';
    $display = !$expired ? 'none' : 'block';
    $str .= '<div style="display:'.  $display . ';" id="vote_result">' . $html . '</div>';
    $str .= '</div>';
    //投票
        
}
?>
document.write('<?php echo $str; ?>');