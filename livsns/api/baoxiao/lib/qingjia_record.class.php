<?php
class qingjia_record extends initFrm
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function __destruct()
    {
        parent::__destruct();
    }
    
    public function show($condition='')
    {
        $sql = "SELECT * FROM " .DB_PREFIX. "qingjia_record WHERE 1 ". $condition;
        $q = $this->db->query($sql);
        $data = array();
        $user_id = $space = $sort_id = '';
        while($row = $this->db->fetch_array($q))
        {
        	$user_id .= $space . $row['user_id'];
        	$sort_id[] =  $row['sort_id'];
        	$record_id[] = $row['id'];
	        $space = ',';
	        $data[] = $row;
        }  
        if($user_id)
        {
	        include_once(ROOT_PATH . 'lib/class/auth.class.php');
			$auth = new auth();
		    $tmp = $auth->getMemberById($user_id);
		    $user_info = array();
		    foreach($tmp as $k => $v)
		    {
		   		$user_info[$v['id']] = $v['user_name'];
		    }
        }  	    
	    if($sort_id)
        {
	        include_once(CUR_CONF_PATH . 'lib/qingjia_sort.class.php');
        	$sort = new qingjia_sort();
        	$sort_tmp = $sort_info = array();
        	$sort_tmp = $sort->get_sort_name($sort_id);  
        	foreach($sort_tmp as $k => $v)
		    {
		   		$sort_info[$v['id']] = $v['name'];
		    }
        }
        if($record_id)
        {
	        include_once(CUR_CONF_PATH . 'lib/qingjia_record.class.php');
        	$record = new qingjia_record();
        	$record_tmp = $xiaojia_info = array();
        	$record_tmp = $record->show_xiaojia();  
        	foreach($data as $k => $v)
    		{
                foreach($record_tmp as $key => $vo)
        		{ 
        		    if($v['id'] == $vo['record_id'])
        		    {
	   	               $xiaojia_info[$v['id']] = array(
		                   'start_time' => $vo['start_time'], 
		   	               'end_time' => $vo['end_time'], 
		    	           );
     	            }
     	        }
 	        }
        }
        foreach($data as $k => $v)
	    {
	    	if($user_info)
		    	$data[$k]['user_name'] = $user_info[$v['user_id']];
		    if($sort_info)
		    	$data[$k]['sort_name'] = $sort_info[$v['sort_id']];
		    if($xiaojia_info)
		    	$data[$k]['xiaojia'] = $xiaojia_info[$v['id']];
	    }
        return $data;    
    }
     
    public function create($data)
    {
	    if(!empty($data))
	    {
	    	$extra = $space = '';
		    foreach($data as $k => $v)
		    {
			    $extra .= $space . $k . "='" . $v . "'";
			    $space = ',';
		    }
		    $sql = "INSERT INTO " .DB_PREFIX. "qingjia_record SET " . $extra;
		    $this->db->query($sql);
		    $data['id'] = $this->db->insert_id(); 
		    //从权限表中取得数据
		    $sql2 = "SELECT * FROM " .DB_PREFIX. "auditor  WHERE id=" . $data['auditor_id'] ; 
		    $q = $this->db->query($sql2); 
		    $row = $this->db->fetch_array($q);
		    $info = unserialize($row['info']);  
		    //将数据插入审核列表
		    foreach($info as $k => $v)
	        {
		        $sql3 =   'INSERT INTO '.DB_PREFIX. 'audit_record  SET  auditor_id = "'.$data['user_id'].
						'",type = \'qingjia\' ,state = 0 ,bill_id = "'.$data['id'].
						'",user_name = "'.$v['user_name'].
					    '",user_id = "'.$v['user_id'].
						'",audit_level = "'.$v['audit_level']. 
						'",create_time = "'.$data['create_time'].
						'",update_time = "'.$data['update_time'].
						'",ip = "'.$data['ip'].'"';  
		        $this->db->query($sql3); 
		    }
		    return $data;
	    }
    }
    
    public function reset_data($data)
    {
	    if(!empty($data))
	    {
		    $sql = "SELECT * FROM " .DB_PREFIX. "qingjia_record WHERE user_id=" . $data['user_id'];
		    $q1 = $this->db->query($sql);  
		    $extra = $space = $auditor_id = '';
		    while($row1 = $this->db->fetch_array($q1))
		    {   
		        $extra .= $space . $row1['id'];
			    $space  = ',';
		        $auditor_id = $row1['auditor_id'];
		    }
		    //从权限表中取得数据
			$sql2 = "SELECT * FROM " .DB_PREFIX. "auditor  WHERE id=" . $auditor_id; //唯一的
			$q = $this->db->query($sql2); 
			$row = $this->db->fetch_array($q);
			$info = unserialize($row['info']); 
		   
			//选择审核纪录
			$sql2 = "SELECT * FROM " .DB_PREFIX. "audit_record  WHERE type = 'qingjia' and bill_id IN(" . $extra . ")"; //多个纪录
			$qq = $this->db->query($sql2); 
            $users = $new_users  = '';
            while($row2 = $this->db->fetch_array($qq))
		    {
		       $users[] = $row2['user_id'];//原来的审核人id
			       
		    }
  
		    $sql = "SELECT * FROM " .DB_PREFIX. "qingjia_record WHERE state != 0  and  user_id=" . $data['user_id'];
		    $q1 = $this->db->query($sql); 
		    while($row1 = $this->db->fetch_array($q1))
		    {   
	             foreach($info as $k => $v) 
			     {
				      if(!in_array($v['user_id'],$users)) //新的审核人，就把以前的记录倒入
				      { 
						   $sql =  'INSERT INTO '.DB_PREFIX. 'audit_record  SET  auditor_id = "'.$data['user_id'].
										'",type = \'qingjia\' ,bill_id = "'.$row1['id'].
										'",user_name = "'.$v['user_name'].
									    '",user_id = "'.$v['user_id'].
									     '",state = "'.$row1['is_approve'].
										'",audit_level = "'.$v['audit_level']. 
										'",create_time = "'.$row1['create_time'].
										'",update_time = "'.$row1['update_time'].
										'",ip = "'.$row1['ip'].'"';  
						    $this->db->query($sql); 
				       }
				       $new_users[] = $v['user_id'];
				  }  
			 }  
			 
			 //已经不在审核列表的人，删除／锁定审核纪录     
		     $sql2 = "SELECT * FROM " .DB_PREFIX. "audit_record  WHERE type = 'qingjia' and bill_id IN(" . $extra . ")"; 
		     $qq = $this->db->query($sql2); 
		     $space = $extra = '';
             while($row2 = $this->db->fetch_array($qq))
			 {
			     if(!in_array($row2['user_id'],$new_users) && $row2['reason'] == '' )
			     { 					  
				      $extra .= $space . $row2['id'];
		              $space = ',';
			      }
		     }
		     if(!empty($extra))
		     {
	              $sql4 = "DELETE FROM " .DB_PREFIX. "audit_record  WHERE id IN(" . $extra . ")";
				  $this->db->query($sql4);  
			 }
		     return   $data ;
	     }
    }
    
    public function update($data,$id)
    {
	    if(!empty($data) && $id)
	    {
	    	$extra = $space = '';
		    foreach($data as $k => $v)
		    {
			    $extra .= $space . $k . "='" . $v . "'";
			    $space = ',';
		    }
		    $sql = "update " .DB_PREFIX. "qingjia_record SET " . $extra . " WHERE id=" . $id;
		    $this->db->query($sql);
		    $data['id'] = $id;
		    
		    //将数据插入审核列表
		     $sql3 =   'update '.DB_PREFIX. 'audit_record  SET update_time = "'.$data['update_time'].
					   '"  WHERE bill_id= '. $data['id'].' and type = \'qingjia\' '; 
		     $this->db->query($sql3); 		    
		     return $data;
	    }
    }
    
    public function xiaojia($data)
    {
	    if(!empty($data))
	    {
	    	$extra = $space = '';
		    foreach($data as $k => $v)
		    {
			    $extra .= $space . $k . "='" . $v . "'";
			    $space = ',';
		    }
		    //数据插入销假表
		    $sql = "INSERT INTO " .DB_PREFIX. "xiaojia_record SET " . $extra;
		    $this->db->query($sql);
		    $sql = "UPDATE " .DB_PREFIX. "audit_record  SET state= 1  WHERE type ='qingjia' and bill_id=" . $data['record_id'] ; 
            $q = $this->db->query($sql); 
		    $sql = "UPDATE " .DB_PREFIX. "qingjia_record  SET is_approve= 1  WHERE id=" . $data['record_id'] ; 
            $q = $this->db->query($sql); 
		    return $data;
	    }
    }
    
    public function show_xiaojia()
    {
   	     $sql = "SELECT * FROM " .DB_PREFIX. "xiaojia_record ";
         $q = $this->db->query($sql);
     	 while($row = $this->db->fetch_array($q)){
     	       $data[] = $row;
	     }
	     return $data;
	  
	}
	public function show_qingjia_message()
    {
   	     $sql = "SELECT * FROM " .DB_PREFIX. "qingjia_message order by create_time ";
         $q = $this->db->query($sql);
         $data = array();
         $user_id = $space = $sort_id = '';
     	 while($row = $this->db->fetch_array($q))
     	 {
     	       $user_id .= $space . $row['user_id'];
	           $space = ',';
     	       $data[] = $row;
	     }

	     if($user_id)
         {
	        include_once(ROOT_PATH . 'lib/class/auth.class.php');
			$auth = new auth();
		    $tmp = $auth->getMemberById($user_id);
		    $user_info = array();
		    foreach($tmp as $k => $v)
		    {
		   		$user_info[$v['id']] = $v['user_name'];
		    }
         }  	   
	       
	     foreach($data as $k => $v)
	     {
	    	if($user_info)
		    	$data[$k]['user_name'] = $user_info[$v['user_id']];
		 }
	     return $data; 
	}
     	  
    public function audit($ids,$state)
    {
    	 $sql = "SELECT * FROM " .DB_PREFIX. "qingjia_record WHERE id IN(" . $ids . ")";
    	 $f = $this->db->query_first($sql);
    	 if($f['qingjia_id'])//目前审核，只是批量某个订单的全部审核
    	 {
	    	include_once(CUR_CONF_PATH . 'lib/qingjia.class.php');
	    	$this->qingjia = new qingjia();
	    	$checkbool = $this->qingjia->checkLocked($f['qingjia_id']);
	    	if($checkbool)
	    	{
		    	return array('error' => THIS_IS_LOCKED);
	    	}
    	 }
    	 
	    $sql = "UPDATE " .DB_PREFIX. "qingjia_record SET state=" . $state . " WHERE id IN(" . $ids . ")";
	    $this->db->query($sql);
	    $sql = "SELECT * FROM " .DB_PREFIX. "qingjia_record WHERE id IN(" . $ids . ")";
	    $qingjia_id = 0;//目前审核，只是批量某个订单的全部审核
	    $q = $this->db->query($sql);
	    while($row = $this->db->fetch_array($q))
	    {
	    	$qingjia_id = $row['qingjia_id'];
		    if($state == 1)//审核通过 +1
		    {
			    $this->updateSortCount($row['sort_id'],1);
		    }
		    if($state == 2)//打回 -1
		    {
			    $this->updateSortCount($row['sort_id'],-1);
		    }		    
	    }	    	    
	    return array('id' => $ids,'status' => $state,'qingjia_id' => $qingjia_id); 
    }
    
    public function updateSortCount($sort_id,$action)
    {
    	if($sort_id)
    	{
    		$con = '';
    		$sql = "SELECT * FROM " .DB_PREFIX. "qingjia_sort WHERE id=" . $sort_id;
    		$f = $this->db->query_first($sql);
    		if($f['cost_count'] < 0)
    		{
	    		$con = '0';
    		}
    		else
    		{
	    		$con = "cost_count+" . $action;
    		}
		    $sql = "UPDATE " . DB_PREFIX . "qingjia_sort SET cost_count=" . $con . " WHERE id=" . $sort_id;
		    $this->db->query($sql);
		    return true;
    	}
    	return false;
     }
    
    public function count($condition='')
    {
	    $sql = "SELECT COUNT(*) AS total FROM " .DB_PREFIX. "qingjia_record WHERE 1 " . $condition;
	    $f = $this->db->query_first($sql);
	    return $f; 
    }
    
    public function qingjia_count($condition,$start_time = '',$end_time = '')
    {
	    $sql = "SELECT * FROM " .DB_PREFIX. "qingjia_record WHERE 1 " ;
        $q = $this->db->query($sql);
        while($row = $this->db->fetch_array($q))
        {
	        $data[] = $row;
        }
	    $data2 = array();
        $sql2 = "SELECT * FROM " .DB_PREFIX. "xiaojia_record where 1 ".$condition;
        $q = $this->db->query($sql2);
     	while($row = $this->db->fetch_array($q))
     	{
     	     foreach($data as $key=>$vo)
     	     {
     	          if($row['record_id'] == $vo['id'])
     	          {
	     	           $data2[$vo['user_id']][] = $row;
     	          }
     	     }   
	     }
         $user_id = $space = '';
         foreach($data2 as $key=>$vo)
         {
             foreach($vo as $k=>$v)
             {
                  if($start_time == '' && $end_time == '')
                  {
			          $data2[$key]['time'] +=  $v['end_time'] - $v['start_time'] ;
			      }
                  elseif($v['end_time'] >= $end_time &&  $v['start_time'] <= $start_time )
                  {
			          $data2[$key]['time'] +=  $end_time - $start_time  + 60*60*24 ;
		          }
		          elseif($v['end_time'] <= $end_time && $v['start_time'] >= $start_time )
		          {
			          $data2[$key]['time'] +=  $v['end_time'] - $v['start_time'] ;
		          }
		          elseif($v['end_time'] <= $end_time && $v['start_time'] <= $start_time && $v['end_time'] >= $start_time)
		          {
		              $data2[$key]['time'] +=  $v['end_time'] - $start_time ;
		          }
		          elseif($v['end_time'] >= $end_time && $v['start_time'] >= $start_time  && $v['start_time'] <= $end_time )
		          {
			          $data2[$key]['time'] +=  $end_time - $v['start_time'] ;
		          }
		          $sort_id[] = $v['sort_id'];

	         }
	         $user_id .= $space . $key;
	         $space = ',';   
	     }
	    
	    if($user_id)
        {
	        include_once(ROOT_PATH . 'lib/class/auth.class.php');
			$auth = new auth();
		    $tmp = $auth->getMemberById($user_id);
		    $user_info = array();
		    foreach($tmp as $k => $v)
		    {
		   		$user_info[$v['id']] = $v['user_name'];
		    }
        }
        
        if($sort_id)
        {
	        include_once(CUR_CONF_PATH . 'lib/qingjia_sort.class.php');
        	$sort = new qingjia_sort();
        	$sort_tmp = $sort_info = array();
        	$sort_tmp = $sort->get_sort_name($sort_id);  
        	foreach($sort_tmp as $k => $v)
		    {
		   		$sort_info[$v['id']] = $v['name'];
		    }
        }
               
	    foreach($data2 as $k => $v)
	    {
	    	if($user_info)
		    	$data2[$k]['user_name'] = $user_info[$k];
		    if($sort_info)
		    {
		        foreach($v as $key => $vo)
		        {
		    	    $data2[$k][$key]['sort_name'] = $sort_info[$vo['sort_id']];
		    	}
		    }
	    }
	    return  $data2;
    }
  
    
    public function delete($ids)
    {
    	if($ids)
    	{
    		$sql = "SELECT id FROM " .DB_PREFIX. "qingjia_record WHERE id IN(" . $ids . ")";
    		$q = $this->db->query($sql);
    		$mid = $space = '';
    		$qingjia_id = 0;
    		$sort = array();
    		while($row = $this->db->fetch_array($q))
    		{
	    		$mid .= $space . $row['material_id'];
	    		$space = ',';
	    		if($row['sort_id'])
	    		{
	    			$sort[] = $row['sort_id'];		    		
	    		}
	    		$qingjia_id = $row['qingjia_id'];//目前删除，批量删除，只能删除某个订单下的
    		}
			$sql = "DELETE FROM " .DB_PREFIX. "qingjia_record WHERE id IN(" . $ids . ")";
		    $this->db->query($sql);
    	    return array('id' => $ids); 
    	}  
    }
    
    public function cancel_records($id)
    {
		$sql = "UPDATE " .DB_PREFIX. "qingjia_record SET state = 0  WHERE id=" .$id;
		$q = $this->db->query($sql);
		$sql = "DELETE FROM " .DB_PREFIX. "audit_record WHERE type ='qingjia' and bill_id =" . $id;
	    $this->db->query($sql);
	    return array('id' => $id);  
    }
    

    
    public function detail($id)
    {
	    if($id)
	    {
		    $sql = "SELECT * FROM " .DB_PREFIX. "qingjia_record WHERE id=" . $id;
	    }
	    else
	    {
		     $sql = "SELECT * FROM " .DB_PREFIX. "qingjia_record WHERE 1";
	    }
		$f = $this->db->query_first($sql);
        $f['img'] = unserialize($f['img']);
        $f['img_url'] = hg_fetchimgurl($f['img'],200,200);
		return $f;
    }
    
    public function fill_message($data)
    {
	    if(!empty($data))
	    {
	    	$extra = $space = '';
		    foreach($data as $k => $v)
		    {
			    $extra .= $space . $k . "='" . $v . "'";
			    $space = ',';
		    }
		    $sql = "INSERT INTO " .DB_PREFIX. "qingjia_message SET " . $extra;
		    $this->db->query($sql);		   
		    return $data;
	    }
    }
    
    public function checkState($record_id)
    {
	    if($record_id)
	    {
		    $sql = "SELECT * FROM " .DB_PREFIX. "qingjia_record WHERE id IN(" . trim($record_id) . ")";
		    $q = $this->db->query($sql);
		    while($row = $this->db->fetch_array($q))
		    {
			    if($row['state'] != 1)
			    {
				    return array('error' => NO_AUDITED);
			    }
		    }
		    return true;
	    }
	    return array('error' => NO_RECORDID);
    }
    
    public function update_qingjia($qingjia_id,$record_id)
    {
	    if($qingjia_id && $record_id)
	    {
	    	$sql = "UPDATE " .DB_PREFIX. "qingjia_record SET qingjia_id=0 WHERE qingjia_id=" . intval($qingjia_id);
	    	$this->db->query($sql);
		    $sql = "UPDATE " .DB_PREFIX. "qingjia_record SET qingjia_id=" . intval($qingjia_id) . " WHERE state=1 AND id IN(" . trim($record_id) . ")";//只能更新审核通过
		    $this->db->query($sql);
		    $rows = $this->db->affected_rows();
		    if($rows)
		    {
		    	$condition = " AND state=1 AND qingjia_id=" . $qingjia_id;//某个单子下的已审核的状态
		        $data = array();
		        $data = $this->show($condition);
		        $ret = array();
		        if($data)
		        {
		        	$tmp = 0;
			        foreach($data as $key => $value)
			        {
			        	$tmp += $value['cost'];
			        }
			        if($tmp)
			        {
				        $ret = array(
				        	'total' => $tmp,
				        );
			        }
			        $this->reaccess($qingjia_id,$ret['total']);//并且重新统计
		        }
		    	return true;   
		    }
		    else
		    {
			    return false;
		    }
	    }
	    return false;
    }
    
    
}
?>