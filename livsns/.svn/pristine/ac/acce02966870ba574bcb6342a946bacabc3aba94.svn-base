<?php 
/**
 * 电视互动对接抽奖,移动app中转代码
 */

if($data['ErrorCode'])
{
	
	echo json_encode($data);exit();
}
$data = $data[0];

if($data['score_limit'] && $data['need_score'] != 0)
{
$data['scores'] = $data['need_score']  < 0 ? abs($data['need_score']) : '-' . $data['need_score'];
}
$data['sense_icon'] = $data['prize_indexpic'] ? $data['prize_indexpic'] : array();
$data['sense_num'] = '';
$data['win_info'] = array();
$data['win_text'] = '我的中奖记录';
$data['win_url'] = 'http://lottery.huaihai.tv/tv_interact/index.html';
if(!$data['id'])
{
     $data['un_start_tip'] = $data['tip'];
     //$data['un_start_desc'] = $data['tip'];
     if($data['indexpic'] && $data['lottery_id'] == 9)
     {
     	$data['un_start_icon'] = $data['indexpic'];
     }
     else 
     {
     	$data['un_start_icon'] = array(
             'host' => 'http://img1.huaihai.tv/',
             'dir'   => 'material/lottery/img/',
             'filepath'   => '2015/03/',
             'filename'  => '201503201531371YnP.jpg',
     	);
     }
     
     $data['start_flag'] = false;
}
else
{
     $data['sense_tip'] = $data['tip'];
     //$data['sense_desc'] = $data['tip'];
     $data['score'] = $data['name'] . $data['prize'];
     $data['start_flag'] = true;
     $data['exchange_text'] = '兑奖';
     $data['exchange_url'] = 'http://lottery.huaihai.tv/tv_interact/index.html';
}
unset($data['prize_indexpic'],$data['sendno']);
$data1 = $data;
unset($data);
$data[0] = $data1;
?>