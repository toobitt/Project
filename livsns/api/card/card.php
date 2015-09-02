<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* $Id: card.php 17960 2013-03-21 14:28:00 jeffrey $
***************************************************************************/
require_once './global.php';
require_once CUR_CONF_PATH . 'lib/card.class.php';
define('MOD_UNIQUEID', 'card'); //模块标识

class cardApi extends outerReadBase
{
	private $card;
	
	public function __construct()
	{
		parent::__construct();
		$this->card = new cardClass();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->card);
	}
		
	
	public function show()
	{
		$card_info = array();
        $offset = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
        $count = $this->input['count'] ? intval(urldecode($this->input['count'])) : 20;
		if(file_exists(CACHE_DIR . $offset.'_'.$count.'card.json'))
		{
			$cache = file_get_contents(CACHE_DIR . $offset.'_'.$count. 'card.json');
			$card_info = json_decode($cache,1);
		}
		else
		{

            $limit = ' LIMIT '.$offset.','.$count;
			$card_info = $this->card->show_card($limit);
			$cache = json_encode($card_info);
			file_put_contents(CACHE_DIR . $offset.'_'.$count .'card.json', $cache);
		}
		if ($card_info)
		{
			foreach ($card_info as $key => $value)
            {
                if ($value['content'] && is_array($value['content'])) {
                    foreach ($value['content'] as $kk => $vv) {
                        if (is_array($vv['childs_data']) && $vv['childs_data']) {
                            foreach ($vv['childs_data'] as $kkk => $vvv) {
                                if ($vvv['outlink']) {
                                    $value['content'][$kk]['childs_data'][$kkk]['outlink'] = str_replace('&#33;', '!', $vvv['outlink']);
                                }
                            }
                        }
                    }
                }

				/******************* 为轮转图样式特殊处理START *********************/
				foreach((array)$value['content'] as $k => $v)
				{
					if($v['cssid'] == '17')
					{
						$tmp[] = array(
							'id' 		=> '',
							'title'		=> $v['title'],
							'brief' 	=> $v['brief'],
							'outlink' 	=> $v['outlink'] ? $v['outlink'] : $v['module_id'].'#'.$v['content_id'],
							'host' 		=> $v['indexpic']['host'],
							'dir' 		=> $v['indexpic']['dir'],
							'filepath'	=> $v['indexpic']['filepath'],
							'filename' 	=> $v['indexpic']['filename'],
						);
						//排序处理,将轮转图合并后的内容合到一个order_id较小的数组中
						if(!isset($order_id))
						{
							$order_id = $v['order_id'];
						}
						if(!isset($kk))
						{
							$kk = $k;
						}
						if($v['order_id'] <= $order_id)
						{
							$order_id = $v['order_id'];
							$kk = $k;
						}
						//unset($card_info[$key]['content'][$k]);
						unset($value['content'][$k]);
					}
				}
				if(isset($kk))
				{
					$value['content'][$kk] = array(
						'id'				=> '',
						'content_id'		=> '',
						'cardid'			=> '',
						'module_id'		=> '',
						'module_name'	=> '',
						'title'			=> '',
						'brief'			=> '',
						'outlink'		=> '',
						'indexpic'		=> array(),
						'childs_data'	=> $tmp,
						'cssid'			=> "17",
						'source_from'	=> '',
						'order_id'		=> $order_id,
						'active'			=> '',
					);
					ksort($value['content']);
					$value['content'] = array_values($value['content']);
				}
				//hg_pre($value['content']);exit;
				unset($tmp,$kk);
				/******************* 为轮转图样式特殊处理END **********************/
				$this->addItem($value);
			}
		}
		$this->output();
	}
	
	public function count()
	{
		
	}
	
	public function detail()
	{
		
	}
	
}



$out = new cardApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'show';
}
$out->$action();

?>