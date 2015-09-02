<?php
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require(ROOT_PATH.'global.php');
require(CUR_CONF_PATH."lib/functions.php");
define('MOD_UNIQUEID','bulidXmlSetting');
class bulidXmlAuto extends cronBase
{
    function __construct()
    {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/xml_setting.class.php');
        $this->obj = new xmlSetting();        
    }
    
    function __destruct()
    {
        parent::__destruct();
    }
    
    public function initcron()
    {
        $array = array(
                'mod_uniqueid' => MOD_UNIQUEID,
                'name' => '创建XML文件',
                'brief' => '创建XML文件',
                'space' => '600', //运行时间间隔，单位秒
                'is_use' => 1,  //默认是否启用
        );
        $this->addItem($array);
        $this->output();
    }
    
    public function show()
    {
        $condition  = " AND file_offset>=0 AND state = 1";
    	if(trim($this->input['id']))
    	{
	    	$condition .= " AND id IN(" . $this->input['id'] . ")";
    	}
        $condition .= " ORDER BY last_time DESC ";
        $condition .= " LIMIT 0, 10";
        $field = '*';
        $sql = "SELECT * FROM " .DB_PREFIX. "xml_setting WHERE 1 " . $condition;
        $q = $this->db->query($sql);
        $list = array();
        $source_id = $space ='';
        while($row = $this->db->fetch_array($q))
        {	
        	$source_id .= $space . $row['source_id'];
        	$space = ',';
	        $list[] = $row;
        }
        
        $source = array();
        if($source_id)
        {
	        $sql = "SELECT * FROM " .DB_PREFIX. "source_setting WHERE 1 and id IN(" . $source_id . ")";
	        $q = $this->db->query($sql);
	        while($row = $this->db->fetch_array($q))
	        {
		        $source[$row['id']] = $row;
	        }
	              
	        $data = array();
	      //  hg_pre($list);exit;
	        foreach($list as $key => $value)
	        {
	       // 	hg_pre($source[$value['source_id']]);exit;
		        /**************获取数据源********************/
		        
		        include_once (ROOT_PATH . 'lib/class/curl.class.php');
				$next_offset = 0;
				if($source[$value['source_id']]['islocal'] )
				{
					$parameter_array = explode('&',$source[$value['source_id']]['parameter']);
					
					$key_relation = array(
						'action' => 'a',
						'data_appid' => 'appid',
						'data_appkey' => 'appkey',
					);
					$parameter_data = array();
					$key_relation = array_flip($key_relation);
					foreach($parameter_array as $kk => $vv)
					{
						$tmp = explode('=',$vv);
						if($key_relation[$tmp[0]])
						{
							$parameter_data[$key_relation[$tmp[0]]] = $tmp[1];
						}
						else
						{
							$parameter_data[$tmp[0]] = $tmp[1];
						}
					}
					$parameter_data['host'] = $source[$value['source_id']]['host'];
					$parameter_data['dir'] = $source[$value['source_id']]['dir'];
					$parameter_data['filename'] = $source[$value['source_id']]['filename'];
					$parameter_data['port'] = $source[$value['source_id']]['port'];
					$parameter_data['data_type'] = $source[$value['source_id']]['data_type'];
					$parameter_data['cid'] = $source[$value['source_id']]['cid'];
					$parameter_data['count'] = trim(strstr($value['count_num'],"="),"=");
					$parameter_data['offset'] =  trim(strstr($value['offset_num'],"="),"=");
					//hg_pre($this->settings['App_videoop']);exit;
					$curl_connect = new curl($this->settings['App_videoop']['host'], $this->settings['App_videoop']['dir']);
					$curl_connect->setSubmitType('post');
					$curl_connect->setReturnFormat('json');
					$curl_connect->initPostData();
			       	foreach($parameter_data as $kk => $vv)
			       	{
						$curl_connect->addRequestData($kk, $vv);
			       	}
			       	$ret = $curl_connect->request('data/api.php');
			       //	hg_pre($ret);exit;
			       if($source[$value['source_id']]['data_type'] == 'variety')
			       {
				       	foreach($ret as $kk => $vv)
				       	{
					       	$next_offset += count($vv)-1;
				       	}
			       }
			       else
			       {
				      // hg_pre($ret);exit;
			       }
				}
				else
				{
					$curl_connect = new curl($source[$value['source_id']]['host'] . ($source[$value['source_id']]['port'] ? ":" . $source[$value['source_id']]['port'] : ''), $source[$value['source_id']]['dir']);
					$curl_connect->setSubmitType('post');
					$curl_connect->setReturnFormat('json');
					$curl_connect->initPostData();
					$parameter_array = $source[$value['source_id']]['parameter'];
					if(!empty($parameter_array))
					{
						$parameter_array  .= '&' . $value['count_num'] . '&' . $value['offset_num'];
						$parameter_array  = explode('&',trim($parameter_array));
					}
					foreach($parameter_array as $k => $v)
					{
						$tmp = explode('=',$v);
						$curl_connect->addRequestData($tmp[0], $tmp[1]);
					}
					$ret = $curl_connect->request($source[$value['source_id']]['filename']);
				}
				$xml_relation = json_decode($value['relation'],1);
				$data_relation = array();
				$data[$value['id']] = array();
				if($ret)
				{
					/****上次生成时间判断****/
					if(intval(TIMENOW-$value['last_time']) < $value['space_time'])
					{
						continue;
					}
					/****解析xml规则****/
					
					preg_match_all("/{while}(.*){\/while}/is",$value['content'],$tmp_whiles);
					$whiles_xml = $tmp_whiles[0][0];//包含while循环字符的xml
					$whiles_xml = $this->data_xml($ret,$whiles_xml,$xml_relation);//数据，循环xml，替换对象
				//	hg_pre($whiles_xml);
				//	exit;
					$xml = str_replace(array($tmp_whiles[0][0],'#now_time#'),array($whiles_xml,date('Y-m-d')),$value['content']);//生成xml								
					//echo $xml;exit;
					/****文件大小判断****/
					$length_xml = strlen($xml);
					//$length_xml = 11.324*1024*1024;
					$tmp_count = explode("=",$value['count_num']);
					$tmp_offset = explode("=",$value['offset_num']);
				//	hg_pre($value);exit;
					$file_name = '';
					if($length_xml > $value['file_size']*1024*1024)
					{
				//	echo 1234;exit;
						$value['count_num'] = intval($value['file_size']*1024*1024/intval($length_xml/$tmp_count[1]));//单个内容的大小,新的内容
						$new_count = $tmp_count[0] . '=' . $value['count_num'];
						if(!$value['space_time'])
						{
							 $tmp_length = ceil($tmp_count[1]/$value['count_num']);
							 
							 $tmp_count_child = $value['count_num'];
							 $tmp_count_total = '';
							 for($i =0 ;$i<$tmp_length;$i++)
							 {
								$tmp_curl = new curl($source[$value['source_id']]['host'] . ($source[$value['source_id']]['port'] ? ":" . $source[$value['source_id']]['port'] : ''), $source[$value['source_id']]['dir']);
								$tmp_curl->setSubmitType('post');
								$tmp_curl->setReturnFormat('json');
								$tmp_curl->initPostData();
								$parameter_child_array = $source[$value['source_id']]['parameter'];
								if(!empty($parameter_child_array))
								{	
									$tmp_count_total += $value['count_num'];
									if($tmp_count_total >= $tmp_count[1])
									{
										$tmp_count_child = $tmp_count[1] - ($tmp_count_total - $value['count_num']);
									}
									//hg_pre($source[$value['source_id']]['parameter']);
									$parameter_child_array .= '&' . $tmp_count[0] . "=" . $tmp_count_child  . '&' . $tmp_offset[0] . "=" . $value['count_num']*$i;
									$parameter_child_array = explode('&',trim($parameter_child_array));
								}
							//	hg_pre($parameter_child_array);
								
								foreach($parameter_child_array as $k => $v)
								{
									$tmp = explode('=',$v);
									//hg_pre($tmp);
									$tmp_curl->addRequestData($tmp[0], $tmp[1]);
								}
								$child_ret = $tmp_curl->request($source[$value['source_id']]['filename']);
							//	echo count($child_ret) . '***';
							//	$tmp_count[1] - $value['count_num']*$i
								if($child_ret)
								{
									$whiles_child_xml = $this->data_xml($child_ret,$whiles,$xml_relation);
									$child_xml = str_replace($tmp_whiles[0][0],$whiles_child_xml,$value['content']);//生成xml
									$file_name = ($i?$value['file_name'] . '_' . $i:$value['file_name']).'.xml';
									file_put_contents(XML_DIR . $file_name,$child_xml);
									echo "《" . $value['title'] . "》的xml生成成功，文件名为-----------------“" . $file_name . "”". date("Y-m-d H:i:s",TIMENOW) . "<br/>";
								}
							
							}
							$sql = "update " .DB_PREFIX. "xml_setting SET file_offset=-1 WHERE id=" . $value['id'];
							$this->db->query($sql);					
							 
						}
						else
						{
							$sql = "update " .DB_PREFIX. "xml_setting SET count_num='" . $new_count .  "' WHERE id=" . $value['id'];
							$this->db->query($sql);
						}
						continue;//文件过大跳过下面操作
					}
				//	echo 321;exit;
					/****是否拆分****/
					$update_sql = array(
						'last_time' => TIMENOW
					);
					$file_name = $value['file_name'] . '.xml';
				//	echo $value['is_split'];
					if($value['is_split'])
					{
						$file_name =   $value['file_name'] . '_' . ($value['file_offset']+1) . '.xml';
						file_put_contents(XML_DIR . $file_name,$xml);
						echo "《" . $value['title'] . "》的xml生成成功，文件名为-----------------“" . $file_name . "”" . date("Y-m-d H:i:s",TIMENOW) . "<br/>";
						$value['file_offset'] = $value['file_offset']+1; //
						$value['offset_num'] = $tmp_offset[0] . '=' . (count($ret)+intval($tmp_offset[1]));//上一次的总数＋现有的offset
						if($value['valid_time'])//有有效期的话xml文件需要删除
						{
							$valid_sql = "SELECT * FROM " .DB_PREFIX. "bulid_xml WHERE state=1 AND file_time < " . (TIMENOW-$value['valid_time']*3600);
							$valid_q = $this->db->query($valid_sql);
							$out_id = $space = '';
							while($rv = $this->db->fetch_array($valid_q))
							{
								$out_id .= $space . $rv['id'];
								$space = ',';
								@unlink(XML_DIR . $rv['filename']);
							}
							if($out_id)
							{
								$this->db->query("UPDATE " .DB_PREFIX. "bulid_xml  SET state=0 WHERE id IN(" . $out_id . ")");
							}
						}
					}
					else
					{
						file_put_contents(XML_DIR . $file_name,$xml);
						echo "《" . $value['title'] . "》的xml生成成功，文件名为-----------------“" . $file_name . "”" . date("Y-m-d H:i:s",TIMENOW) . "<br/>";
						if($value['valid_time'])
						{
							/****因为不拆分，所以数据库索引不会变化，除非过期****/	
							if((TIMENOW - $value['valid_start']) >= $value['valid_time']*3600)//这边的过期只是改变数据库索引，不会对bulid_xml表的数据有影响
							{
								$value['offset_num'] = $tmp_offset[0] . '=' . (count($ret)+intval($tmp_offset[1]));//上一次的总数＋现有的offset
							}
							else
							{
								$value['offset_num'] = $value['offset_num'];//因为未过期，所以数据库索引不变，继续增量
							}
							$update_sql['valid_start'] = TIMENOW;
						}
						else//没有效期，数据就是最新的
						{
							$value['offset_num'] = $tmp_offset[0] . '=' . (count($ret)+intval($tmp_offset[1]));//上一次的总数＋现有的offset
						}
						if($next_offset)
						{
							$value['offset_num'] = $tmp_offset[0] . '=' .$next_offset;
						}
					}
					$update_sql['offset_num'] = $value['offset_num'];
					$update_sql['file_offset'] = $value['file_offset'];
					$sql = "update " .DB_PREFIX. "xml_setting SET ";
					$space = '';
					foreach($update_sql as $ks => $vs)
					{
						$sql .= $space . $ks ."='" . $vs . "'";
						$space = ',';
					}	
					$sql = $sql . " WHERE id=" . $value['id'];
					$this->db->query($sql);
					$tmp = explode('.',$file_name);
					$sql = "INSERT INTO " .DB_PREFIX. "bulid_xml(name,xml_setting_id,host,dir,filename,file_time,create_time,create_user) values('" . $tmp[0] . "'," . $value['id'] . ",'" . XML_URL . "','','" . $file_name . "'," . TIMENOW . "," . TIMENOW . ",'" . $this->user['user_name'] . "')";
					$this->db->query($sql);//记录每条生成的xml
					
					/****索引文件****/
					if($value['is_index'])
					{
						$index_xml = $while_index_xml = $index_words = '';
						preg_match_all("/\#(.*)\#/i",$value['index_content'],$tmp_words);
						//hg_pre($tmp_words);
						preg_match_all("/{while}(.*){\/while}/is",$value['index_content'],$tmp_indexs);
						$indexs =  $tmp_indexs[1][0];
						//hg_pre($tmp_indexs);
						$index_sql = "SELECT * FROM " .DB_PREFIX. "bulid_xml WHERE xml_setting_id=" . $value['id'];
						$index_q = $this->db->query($index_sql);
						while($r = $this->db->fetch_array($index_q))
						{
							//hg_pre($r);
							$index_data = array();
							
							foreach($tmp_words[1] as $kk => $vv)
							{
								if(strstr($vv,'++'))
								{
									$tmp_index_words = explode("++",$vv);
									//hg_pre($tmp_index_words);
									$index_data['#'.$vv.'#'] = '';
									foreach($tmp_index_words as $kd => $vd)
									{
										$index_data['#'.$vv.'#'] .= $r[$vd]; 
									}
								}
								else
								{	
									$index_data['#'.$vv.'#'] = $vv == 'file_time' ? date('Y-m-d H:i:s',$r[$vv]) : $r[$vv]; 
								}
							}
							$while_index_xml .= str_replace($tmp_words[0],$index_data,$indexs);
						}
						if($while_index_xml)
						{
							$index_xml = str_replace($tmp_indexs[0][0],$while_index_xml,$value['index_content']);
							file_put_contents(XML_DIR . $value['index_file'] . '.xml',$index_xml);
							echo "《" . $value['title'] . "》的xml索引文件生成成功，文件名为-----------------“" . $value['index_file'] . ".xml”" . date("Y-m-d H:i:s",TIMENOW) . "<br/>";
						}				
					}
				}
	        }
        }
    }
    
    private function data_xml($ret,$whiles,$xml_relation)
    {
    	preg_match_all("/{while}.*{child_while}(.*){\/child_while}.*{\/while}/is",$whiles,$tmp_child_whiles);
		$father_xml = $child_xml = '';
		$whiles = str_replace(array('{while}','{/while}'),array('',''),$whiles);//不包含循环字符的xml
		
	//	hg_pre($whiles);//没加while
	//	hg_pre($whiles_xml);//加了while
	//	hg_pre($tmp_child_whiles);
	//	exit;
		
		$replace_relation = array();
		$index_replace_relation = array();
		foreach($xml_relation as $kr => $vr)
		{
			if(strstr($vr,'index+'))
			{
				$index_replace_relation['#'.$vr.'#'] =  str_replace('index+','',$vr);
			}
			else
			{
				$replace_relation['#'.$vr.'#'] = $vr;
			}			
		}
	//	hg_pre($xml_relation);
	//	hg_pre($index_replace_relation);exit;
	//	hg_pre($replace_relation);
	//	hg_pre();
		if($tmp_child_whiles[1][0])//包含子集
		{
			$father_xml = str_replace($tmp_child_whiles[1][0],'',$tmp_child_whiles[0][0]);
			$child_xml = $tmp_child_whiles[1][0];
		//	hg_pre($ret);exit;
			foreach($ret as $key => $value)
			{
				$item_xml = '';
				$father_data_xml = $child_data_xml = '';
				if(count($value) <= 1)
				{
					continue;
				}
				$index_value = $value['index'];
				$index_replace_value = array();
				foreach($index_value as $k => $v)
				{
					foreach($index_replace_relation as $kk => $vv)
					{
						if($k == $vv)
						{
							$index_replace_value[$kk] = $v;
						}
					}
				}
			//	hg_pre($index_replace_value);exit;
			//	echo $father_xml;
				$father_data_xml = str_replace(array_keys($index_replace_value),array_values($index_replace_value),$father_xml);
				//	hg_pre($index_replace_relation);
				unset($value['index']);
				$replace_value = array();
				foreach($value as $k => $v)
				{
					foreach($replace_relation as $kk => $vv)
					{
						if(in_array($vv,array_keys($v)))
						{
							$replace_value[$k][$kk] = $v[$vv];
						}
					}
			//		hg_pre($replace_value[$k]);
					$child_data_xml .= str_replace(array_keys($replace_value[$k]),array_values($replace_value[$k]),$child_xml);
				}
				$item_xml = str_replace('{child_while}{/child_while}',$child_data_xml,$father_data_xml);
				//$item_xml = str_replace(array('{while}','{/while}'),array('',''),$item_xml);
				$item_xml = trim(trim($item_xml,'{while}'),'{/while}');
			//	echo $item_xml;exit;
				$whiles_xml .= $item_xml;
			//	hg_pre($replace_value);
			}
			//$whiles_xml = '{while}' . $whiles_xml . '{/while}';
		}
		else
		{
			$whiles_xml = '';
		    foreach($ret as $k => $v)
			{
				$tmp_ret = array();
				foreach($xml_relation as $kk => $vv)
				{
					if(!strstr($vv,'&&'))
					{
						$tmp_ret[$vv] =$v[$vv];
					}
					else
					{
						if(strstr($vv,'++'))
						{
							$tmp_words = explode('&&',$vv);
							//hg_pre($tmp_words);
							$tmp_child_words = explode('++',$tmp_words[1]);
							foreach($tmp_child_words as $ks => $vs)
							{
								$tmp_ret[$vv] .= $v[$tmp_words[0]][$vs];
							}
						}
						else
						{
							$tmp_words = explode('&&',$vv);
							if(count($tmp_words) == 2)
							{
								$tmp_ret[$vv] = $v[$tmp_words[0]][$tmp_words[1]];
							}
							else
							{
								//暂未定义规则
							}
						}
					}						
				}
			//	$data[$value['id']][] = $tmp_ret;
				//hg_pre($tmp_ret);
				$replace_value = array();
				foreach($replace_relation as $k => $v)
				{
					foreach($tmp_ret as $kk => $vv)
					{
						if($kk == $v)
						{
							$replace_value[$k] = $vv;
						}
					}
				}
				$whiles_xml .= str_replace(array_keys($replace_value),array_values($replace_value),$whiles);
			}
		}		
//		hg_pre($replace_relation);exit;
		return $whiles_xml;
    }
    
}
$out = new bulidXmlAuto();
$action = $_INPUT['a'];
if(!method_exists($out, $_INPUT['a']))
{
    $action = 'show';
}
$out->$action(); 
?>