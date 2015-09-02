<?php
require('global.php');
define('MOD_UNIQUEID', 'access');
class stats extends outerUpdateBase
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$id = intval( $this->input['id'] );
		if ( !$id )
		{
			$this->errorOutput(NOID);
		}
		$rec = intval( $this->input['rec'] );
		$appunid = addslashes( $this->input['app_uniqueid'] );
		$modunid = addslashes($this->input['mod_uniqueid']);
		$sql = 'SELECT id, access_nums, is_sync,url, last_sync_time, create_time, title 
				FROM ' . DB_PREFIX . "nums 
				WHERE app_bundle='{$appunid}' AND module_bundle='{$modunid}' AND cid='{$id}'";
		$row = $this->db->query_first($sql);
		$count = intval($row['access_nums']);
		if ( $rec )
		{
			$title = $this->input['title'];
            if (!$title && !$row['title']) {

                include_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
                $this->publishcontent = new publishcontent();
                $content_type = $this->publishcontent->get_all_content_type();
                $this->pub_content_bundle = $pub_content_bundle = array();
                foreach ((array)$content_type as $k => $v)
                {
                    $this->pub_content_bundle[] = $pub_content_bundle[] = $v['bundle_id'];
                }

                if ( $appunid && in_array($appunid, $pub_content_bundle) )
                {
                    //统计时如果没有传并且库里没有标题,去发布库查询标题,用户统计后台搜索
                    include_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
                    $this->publishtcontent = new publishcontent();
                    $content = $this->publishtcontent->get_content_by_cid($id);
                    $title = $content[$id]['title'];
                }
            }
            $title = $title ? addslashes($title) : addslashes($row['title']);
			$reffer = addslashes($this->input['reffer']);
			$type = intval($this->input['type']);
			$columnid = intval($this->input['column_id']);
			$count = $count + 1;
			$ip = hg_getip();
			$time = date('Ym',TIMENOW);
			if(!$row['id'])
			{
				$row['create_time'] = TIMENOW;
			}
			$tableName = "record_" . $time;
//			$sql = "SHOW TABLES LIKE '" . DB_PREFIX . $tableName. "'";
//			$this->db->query($sql);
//			if(!$this->db->affected_rows())
			### 读取已经存在的表验证新表是否已经存在
			include_once(CUR_CONF_PATH . 'lib/cache.class.php');
			$cache = new CacheFile();
			$oldTable = $cache->get_cache('access_table_name');			
			if(!in_array($tableName,$oldTable))
			{
				$sql = "CREATE TABLE " . DB_PREFIX . $tableName . " LIKE " . DB_PREFIX ."record";	
				$this->db->query($sql);
				###保存已建的表 关联时验证表是否存在
				$newTable = array($tableName);
				$allTable = array_merge($oldTable,$newTable);
				$allTable = array_unique($allTable);
				$cache->set_cache('access_table_name',$allTable);
				###保存结束				
			}
			$sql = 'INSERT INTO ' . DB_PREFIX . "record_" . $time . " (app_bundle, module_bundle, type, cid, column_id, refer_url, ip, access_time, appid, appname,user_id, user_name, title, create_time) VALUES
					('{$appunid}', '{$modunid}', '{$type}', '{$id}', '{$columnid}', '{$reffer}', '{$ip}', " . TIMENOW . ", '{$this->user['appid']}', '{$this->user['appname']}', '{$this->user['user_id']}', '{$this->user['user_name']}', '{$title}', '" . $row['create_time'] . "')";
			$this->db->query($sql);
			if (!$row['url'])
			{
				$row['url'] = $reffer;
			}

            /** begin zbb 2015-4-20 在表里增加了publish_time字段，存储发布库的内容发布时间 **/
            /*
            if(!$this->publishtcontent){
                include_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
                $this->publishtcontent = new publishcontent();
                $content = $this->publishtcontent->get_content_by_cid($id);
            }
            $publish_time = isset($content['publish_time_stamp']) && $content['publish_time_stamp'] ? $content['publish_time_stamp'] : TIMENOW;
            */
            /** end **/

			$sql = 'REPLACE INTO ' . DB_PREFIX . "nums (app_bundle, module_bundle, cid, title, access_nums, last_sync_time, is_sync, url, create_time, update_time) VALUES
					('{$appunid}', '{$modunid}', '{$id}', '{$title}', " . $count . ", '{$row['last_sync_time']}', '{$row['is_sync']}', '{$row['url']}', '{$row['create_time']}', " . TIMENOW . ")";
			$this->db->query($sql);
			$this->sync($id,$row['last_sync_time'],$row['create_time'],$count,$appunid);
		}
		if(isset($this->settings['default_number']) && $this->settings['default_number'])
		{
			@srand($id);
			$min = $max = 0;
			list($min, $max) = @split (',', $this->settings['default_number']);
			$count += @rand($min,$max);			
		}
		$this->addItem($count);
		$this->output();
	}
	
    //同步
	private function sync($id,$last_sync_time,$create_time,$count,$appunid)
	{
		if((TIMENOW - $last_sync_time) >= SYNC_SPACE * 3600)
		{
			$time = date('Ym',$create_time);
			include_once(ROOT_PATH . 'lib/class/curl.class.php');
			$settings = $this->settings['App_' . $appunid];
			if ( $settings )
			{
                if (empty($this->pub_content_bundle))
                {
                    include_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
                    $this->publishcontent = new publishcontent();
                    $content_type = $this->publishcontent->get_all_content_type();
                    $this->pub_content_bundle = array();
                    foreach ((array)$content_type as $k => $v)
                    {
                        $this->pub_content_bundle[] = $v['bundle_id'];
                    }
                }
				//取原始内容id
                if ( $appunid && in_array($appunid, (array)$this->pub_content_bundle) )
                {
                    include_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
                    $this->publishcontent = new publishcontent();
                    $ret = $this->publishcontent->get_content_by_cid($id);
                    $content_fromid = $ret[$id]['content_fromid'];
                    //去文稿、图集等应用同步访问数时 随便把统计库标题更新调  防止发布库统计库不一致(依然有时间差)
                    $title = $ret[$id]['title'];
                    $curl = new curl($settings['host'],$settings['dir'] . 'admin/');
                    $curl->setSubmitType('post');
                    $curl->setReturnFormat('json');
                    $curl->initPostData();
                    $curl->addRequestData('a', 'access_sync');
                    $curl->addRequestData('id', $content_fromid);
                    switch($appunid)
                    {
                        case 'livmedia':
                            $filename = 'vod';
                            break;
                        default:
                            $filename = $appunid;
                            break;
                    }
                    $curl->addRequestData('click_num',$count);
                    $q = $curl->request($filename . '_update.php');
                    //同步到发布库
                    $this->publishcontent = new publishcontent();   //get_content_by_cid把$this->curl更改  需要重新实例化
                    $data = array('click_num' => $count);
                    $this->publishcontent->update_content_by_cid($id, $data);
                }
			}
            $sql = "UPDATE ".DB_PREFIX ."nums SET last_sync_time = ".TIMENOW." WHERE cid = " . $id . " AND app_bundle='".$appunid."'";

            $this->db->query($sql);
		}
		return $id;
	}	
	
	/**
	 * 叮当内容统计add
	 */
	public function dingdoneAddNums()
	{
		$id = intval( $this->input['id'] );
		$content_fromid = intval($this->input['content_fromid']);
		if ( !$id)
		{
			$this->errorOutput(NOID);
		}
		$rec = intval( $this->input['rec'] );
		$appunid = addslashes( $this->input['app_uniqueid'] );
		$modunid = addslashes($this->input['mod_uniqueid']);
		
		//叮当原有数据兼容
		$old_sql = 'SELECT id, access_nums, is_sync,url, last_sync_time, create_time, title
				FROM ' . DB_PREFIX . "nums
			WHERE app_bundle='{$appunid}' AND module_bundle='{$modunid}' AND cid='{$id}' AND content_fromid = 0";
		
		$old_row = $this->db->query_first($old_sql);
		if($old_row && is_array($old_row))
		{
			$count = intval($old_row['access_nums']);
			//删除此数据
			$delete_old_sql = "delete from " . DB_PREFIX . "nums where cid =" . $id;
			$this->db->query($delete_old_sql);
		}
		else
		{
			$sql = 'SELECT id, access_nums, is_sync,url, last_sync_time, create_time, title
				FROM ' . DB_PREFIX . "nums
							WHERE app_bundle='{$appunid}' AND module_bundle='{$modunid}' AND content_fromid='{$content_fromid}'";
			$row = $this->db->query_first($sql);
			$count = intval($row['access_nums']);	
			$delete_new_sql = "delete from " . DB_PREFIX . "nums where content_fromid =" . $content_fromid ."";
			$this->db->query($delete_new_sql);
		}
		
		if ( $rec )
		{
			$title = $this->input['title'];
			if (!$title && !$row['title'])
			{
				include_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
				$this->publishcontent = new publishcontent();
				$content_type = $this->publishcontent->get_all_content_type();
				$this->pub_content_bundle = $pub_content_bundle = array();
				foreach ((array)$content_type as $k => $v)
				{
					$this->pub_content_bundle[] = $pub_content_bundle[] = $v['bundle_id'];
				}

				if ( $appunid && in_array($appunid, $pub_content_bundle) )
				{
					//统计时如果没有传并且库里没有标题,去发布库查询标题,用户统计后台搜索
					include_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
					$this->publishtcontent = new publishcontent();
					$content = $this->publishtcontent->get_content_by_cid($id);
					$title = $content[$id]['title'];
				}
			}
			$title = $title ? addslashes($title) : addslashes($row['title']);
			$reffer = addslashes($this->input['reffer']);
			$type = intval($this->input['type']);
			$columnid = intval($this->input['column_id']);
// 			if((TIMENOW - $row['last_sync_time']) >= SYNC_SPACE * 3600)
// 			{
				$count = $count + 1;
// 			}
			$ip = hg_getip();
			$time = date('Ym',TIMENOW);
			if(!$row['id'])
			{
				$row['create_time'] = TIMENOW;
			}
			$tableName = "record_" . $time;
			//			$sql = "SHOW TABLES LIKE '" . DB_PREFIX . $tableName. "'";
			//			$this->db->query($sql);
			//			if(!$this->db->affected_rows())
			### 读取已经存在的表验证新表是否已经存在
			include_once(CUR_CONF_PATH . 'lib/cache.class.php');
			$cache = new CacheFile();
			$oldTable = $cache->get_cache('access_table_name');
			if(!in_array($tableName,$oldTable))
			{
				$sql = "CREATE TABLE " . DB_PREFIX . $tableName . " LIKE " . DB_PREFIX ."record";
				$this->db->query($sql);
// 				###保存已建的表 关联时验证表是否存在
				$newTable = array($tableName);
				$allTable = array_merge($oldTable,$newTable);
				$allTable = array_unique($allTable);
				$cache->set_cache('access_table_name',$allTable);
// 				###保存结束
			}
			$sql = 'INSERT INTO ' . DB_PREFIX . "record_" . $time . " (app_bundle, module_bundle, type, content_fromid ,cid, column_id, refer_url, ip, access_time, appid, appname,user_id, user_name, title) VALUES
			('{$appunid}', '{$modunid}', '{$type}', {$content_fromid} ,'{$id}', '{$columnid}', '{$reffer}', '{$ip}', " . TIMENOW . ", '{$this->user['appid']}', '{$this->user['appname']}', '{$this->user['user_id']}', '{$this->user['user_name']}', '{$title}')";
			$this->db->query($sql);
			if (!$row['url'])
			{
				$row['url'] = $reffer;
			}
			$sql = 'INSERT INTO ' . DB_PREFIX . "nums (app_bundle, module_bundle, content_fromid,cid, title, access_nums, last_sync_time, is_sync, url, create_time, update_time) VALUES
			('{$appunid}', '{$modunid}', '{$content_fromid}', '{$id}' ,'{$title}', " . $count . ", '{$row['last_sync_time']}', '{$row['is_sync']}', '{$row['url']}', '{$row['create_time']}', " . TIMENOW . ")";
			$this->db->query($sql);
			$this->dingdoneSync($content_fromid,$row['last_sync_time'],$row['create_time'],$count,$appunid,$modunid);
		}
		if(isset($this->settings['default_number']) && $this->settings['default_number'])
		{
			@srand($id);
			$min = $max = 0;
			list($min, $max) = @split (',', $this->settings['default_number']);
			$count += @rand($min,$max);
		}
		$this->addItem($count);
		$this->output();
	}
	
	private function dingdoneSync($id,$last_sync_time,$create_time,$count,$appunid,$modunid)
	{
		if((TIMENOW - $last_sync_time) >= SYNC_SPACE * 3600)
		{
			$time = date('Ym',$create_time);
			include_once(ROOT_PATH . 'lib/class/curl.class.php');
			$settings = $this->settings['App_' . $appunid];
			if ( $settings )
			{
				if (empty($this->pub_content_bundle))
				{
					include_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
					$this->publishcontent = new publishcontent();
					$content_type = $this->publishcontent->get_all_content_type();
					$this->pub_content_bundle = array();
					foreach ((array)$content_type as $k => $v)
					{
						$this->pub_content_bundle[] = $v['bundle_id'];
					}
				}
				//取原始内容id
				if ( $appunid && in_array($appunid, (array)$this->pub_content_bundle) )
				{
					include_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
					$this->publishcontent = new publishcontent();
					$ret = $this->publishcontent->get_content_by_other($id,$appunid,$modunid);
					$content_fromid = $ret[0]['content_fromid'];
					//去文稿、图集等应用同步访问数时 随便把统计库标题更新调  防止发布库统计库不一致(依然有时间差)
					$title = $ret[0]['title'];
					$curl = new curl($settings['host'],$settings['dir'] . 'admin/');
					$curl->setSubmitType('post');
					$curl->setReturnFormat('json');
					$curl->initPostData();
					$curl->addRequestData('a', 'access_sync');
					$curl->addRequestData('id', $content_fromid);
					switch($appunid)
					{
						case 'livmedia':
							$filename = 'vod';
							break;
						default:
							$filename = $appunid;
							break;
					}
					$curl->addRequestData('click_num',$count);
					$q = $curl->request($filename . '_update.php');
					//同步到发布库
					$this->publishcontent = new publishcontent();   //get_content_by_cid把$this->curl更改  需要重新实例化
					$data = array('click_num' => $count);
					$this->publishcontent->update_content_by_cid($ret[0]['content_id'], $data);
				}
			}
			$sql = "UPDATE ".DB_PREFIX ."nums SET last_sync_time = ".TIMENOW." WHERE content_fromid = " . $id . " AND app_bundle='".$appunid."'";
			
			$this->db->query($sql);
		}
		return $id;
	}
	
	
	public function dingdoneSetClickNum()
	{
		$click_num = intval($this->input['click_num']);
		$module_bundle = trim($this->input['module_bundle']);
		$app_bundle = trim($this->input['app_bundle']);
		$source_id = intval($this->input['source_id']);
		$publish_id = intval($this->input['publish_id']);
		
		include_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
		$publishcontent_class = new publishcontent();
		$ret = $publishcontent_class->get_content_by_other($source_id,$app_bundle,$module_bundle);
		$content_fromid = $ret[0]['content_fromid'];
		$cid = $ret[0]['content_id'];
		//对叮当旧数据需要做兼容 content_fromid 为0
		$jianrong_sql = "select * from ".DB_PREFIX."nums where module_bundle = '".$module_bundle."' and cid = ".$cid." and content_fromid = 0";
		$temp_info = $this->db->query_first($jianrong_sql);
		if($temp_info)
		{
			$update_temp_sql = "update ".DB_PREFIX."nums set content_fromid = ".$source_id." where cid = ".$cid ;
			$this->db->query($update_temp_sql);
		}
		//验证消息是否正确
		//source source_id publish_id
		$select_sql = "select * from ".DB_PREFIX."nums where module_bundle = '".$module_bundle."' and content_fromid = ".$source_id;
		$now_nums = $this->db->query_first($select_sql);
		$content_info= $publishcontent_class->get_content_by_cid($cid);
		$title = $content_info[$cid]['title'];
		if(!$ret)
		{
			$this->errorOutput(FAILED);
		}	
		if($now_nums)
		{
			//更新nums表
			$update_sql = "update ".DB_PREFIX."nums set access_nums = ".$click_num." where module_bundle = '".$module_bundle."' and content_fromid = ".$source_id;
			$this->db->query($update_sql);
		}
		else
		{
			$insert_sql = 'INSERT INTO ' . DB_PREFIX . "nums (app_bundle, module_bundle, content_fromid,cid, title, access_nums, last_sync_time, is_sync, url, create_time, update_time) VALUES
			('{$app_bundle}', '{$module_bundle}', '{$content_fromid}', '{$cid}' ,'{$title}', " . $click_num . ", ".TIMENOW.", '0', '', ".TIMENOW.", " . TIMENOW . ")";
			$this->db->query($insert_sql);
		}
		
		//更新发布库和对应的source库
		include_once(ROOT_PATH . 'lib/class/curl.class.php');
		$settings = $this->settings['App_' . $app_bundle];
		if ( $settings )
		{
			if (empty($this->pub_content_bundle))
			{
				$this->publishcontent = new publishcontent();
				$content_type = $this->publishcontent->get_all_content_type();
				$this->pub_content_bundle = array();
				foreach ((array)$content_type as $k => $v)
				{
					$this->pub_content_bundle[] = $v['bundle_id'];
				}
			}
			//取原始内容id
			if ( $app_bundle && in_array($app_bundle, (array)$this->pub_content_bundle) )
			{
				//去文稿、图集等应用同步访问数时 随便把统计库标题更新调  防止发布库统计库不一致(依然有时间差)
				$title = $ret[0]['title'];
				$curl = new curl($settings['host'],$settings['dir'] . 'admin/');
				$curl->setSubmitType('post');
				$curl->setReturnFormat('json');
				$curl->initPostData();
				$curl->addRequestData('a', 'access_sync');
				$curl->addRequestData('id', $content_fromid);
				switch($app_bundle)
				{
					case 'livmedia':
						$filename = 'vod';
						break;
					default:
						$filename = $app_bundle;
						break;
				}
				$curl->addRequestData('click_num',$click_num);
				$q = $curl->request($filename . '_update.php');
				//同步到发布库
				$this->publishcontent = new publishcontent();   //get_content_by_cid把$this->curl更改  需要重新实例化
				$data = array('click_num' => $click_num);
				$this->publishcontent->update_content_by_cid($ret[0]['content_id'], $data);
			}
		}
		$this->addItem(array('return'=>1));
		$this->output();
	}
	
	public function create(){}
	public function update(){}
	public function delete(){}	
}

$out = new stats();
$action = $_INPUT['a'];
if(!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>