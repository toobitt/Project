<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: getblog.php 12333 2012-10-10 10:47:07Z repheal $
***************************************************************************/
		if($this->input['order_type'])
		{
			$order = ' ASC ';
		}
		else
		{
			$order = ' DESC ';	
		}

		/**
		 * 如果传递了获取大于某个ID的微博
		 */
		if($this->input['newest_id'])
		{
			$newest_id_condition = " AND sta.id > " . $this->input['newest_id'];
		}

		$extra = '';
		//若指定此参数，则只返回ID比since_id大（即比since_id发表时间晚的）的点滴消息
		if ($flag == 1)
		{
			$this->input['since_id'] = intval($this->input['since_id']);
			$this->input['max_id'] = intval($this->input['max_id']);
			if($this->input['since_id'])
			{
				$extra .= "and sta.id >".$this->input['since_id'];
			}
			//若指定此参数，则返回ID小于或等于max_id的点滴消息 
			if($this->input['max_id'])
			{
				$extra .= "and sta.id <".$this->input['max_id'];
			} 
			/**
			 * 取原创微博
			 */
			if($this->input['ori'])
			{
				$extra .= " AND sta.reply_status_id =0";
			}
		}
		//第一次请求该函数才执行此操作
		$all = array();

		if(is_array($ids))
		{
			if($ids[0]=='recent')
			{

				//取最近用户的博客信息
				//$sql = "SELECT sta.* , mea.source FROM ".DB_PREFIX."status sta  LEFT JOIN ".DB_PREFIX."media mea ON sta.id = mea.status_id ORDER BY sta.id DESC  limit $offset , $count";
				$sql = "SELECT sta.* , exl.transmit_count,exl.reply_count,exl.comment_count FROM ".DB_PREFIX.
				"status sta  LEFT JOIN ".DB_PREFIX."status_extra exl ON sta.id = exl.status_id where sta.status=0 " . $newest_id_condition . $extra . " ORDER BY sta.create_at " . $order . $this->end;

			}
			elseif($ids[0]=='count')
			{

				//根据关键字取得博客信息
				$this->sqlcount = "SELECT count(*) as total FROM ".DB_PREFIX."status as sta WHERE 1 $this->search" . $newest_id_condition . $extra;	 
				
				
				$sql = "SELECT sta.* , exl.transmit_count,exl.reply_count,exl.comment_count FROM ".DB_PREFIX.
				"status sta  LEFT JOIN ".DB_PREFIX."status_extra exl ON sta.id = exl.status_id where 1 $this->search  " . $newest_id_condition . $extra . " ORDER BY sta.create_at " . $order .  $this->end;

			}
			else 
			{
				$sta = implode(',',$ids);
				//取得不固定用户的点滴信息
				//$sql = "SELECT * FROM ".DB_PREFIX."status where id in($sta) AND status=0";
				$sql = "SELECT sta.* , exl.transmit_count,exl.reply_count,exl.comment_count FROM ".DB_PREFIX.
				"status sta  LEFT JOIN ".DB_PREFIX."status_extra exl ON sta.id = exl.status_id where sta.id in($sta) AND sta.status=0 ".$extra . $newest_id_condition . " ORDER BY sta.create_at " . $order;

			}
			
		}
		else
		{
			$u_id = $ids;
			//判断是否是当前登录用户
			if ($this->input['user_id'] != $userinfo['id'])
			{
				$ex_sta = "AND sta.status=0";
			}
			//取总数或取数据
			
			if($this->gettotal == 'gettotal')
			{
				$sql = "SELECT count(id) as total FROM " . DB_PREFIX . "status as sta  where member_id=$u_id " . $ex_sta . "$extra" . $newest_id_condition;
			}
			else 
			{
				//取得固定用户的点滴信息
				$sql = "SELECT sta.* , exl.transmit_count,exl.reply_count,exl.comment_count FROM " . DB_PREFIX . "status sta  LEFT JOIN " . DB_PREFIX . "status_extra exl ON sta.id = exl.status_id where sta.member_id=$u_id " . $newest_id_condition . $ex_sta . "$extra ORDER BY sta.create_at ". $order . $this->end;
				
			}
		}
		
		if($this->gettotal == 'gettotal')
		{	
			$result = $this->db->query_first($sql);
			$this->total = $result;
			return;
		}
		else
		{
			$result = $this->db->query($sql);
		}
		if (!$this->db->num_rows($result))
		{
			//退出
			return ;
		}
		$members = $trans = array();
		while($row = $this->db->fetch_array($result))
		{		
			$members[$row['member_id']] = $row['member_id'];
			if($row['reply_status_id'])
			{
				$this->trans[] = $row['reply_status_id'];
			}
			//格式化时间
			//$row['create_at'] = date("Y-m-d H:i:s",$row['create_at']);
			$blog[] = $row;
		}
		
		//取得对应的用户信息
		$members = implode(',',$members);
		$members = $this->member->getMemberById($members);
		if(count($members) <= 1)
		{
			$members = $members[0];
		}
		//file_put_contents('./cache/1ss.php',var_export($members,true));
		//对应user的键值
		foreach ($members as $key => $values)
		{
			$mem[$values['id']] = $values;
		}
		
		//file_put_contents('./cache/1s.php',var_export($mem,true));
		//博客信息和用户信息合并
		foreach ($blog as $key =>$values)
		{
			$values['user'] = $mem[$values['member_id']];
			$all[$values['id']]=$values;
		}
?>