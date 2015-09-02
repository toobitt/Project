<?php 
include_once('../lib/db/db_mysql.class.php');
//include_once('./tmp/cms_arr.php');
//include_once('./tmp/ve_arr.php');
include_once('./tmp/program.php');
include_once('./tmp/file.php');
include_once('./tmp/ve.php');
include_once('./tmp/lubo.php');
include_once('./tmp/shangwei.php');
date_default_timezone_set('PRC');
//栏目对应关系
$column = array(
	55=> 167,
	935=> 173,
	1546=> 174,
	58=> 175,
	1618=> 185,
	1757=> 181,
	625=> 183,
	548=> 184,
	538=> 172,
	56=> 168,
	57=> 169,
	59=> 170,
	1775=> 171,
	535=> 176,
	4=> 188,
	30=> 189,
	27=> 190,
	618=> 191,
	1093=> 192,
	609=> 193,
	31=> 194,
	536=> 195,
	631=> 196,
	537=> 197,
	583=> 198,
	543=> 199,
	1619=> 187,
	68=> 186,
	87=> 177,
	570=> 182,
	426=> 178,
	427=> 179,
	553=> 180,
);
//LivCMS数据
$c_db = array(
	'host' => '127.0.0.1',
	'user' => 'root',
	'pass' => 'qiang123',
	'database' => '3livcms',
	'charset'  => 'utf8',
	'pconncet' => 0,
	'dbprefix' => 'liv_',
);
//尚为数据
$s_db = array(
	'host' => '127.0.0.1',
	'user' => 'root',
	'pass' => 'qiang123',
	'database' => '2shinyv_mms_4',
	'charset'  => 'utf8',
	'pconncet' => 0,
	//'dbprefix' => 'liv_',
);
//淮安移植数据
$h_db = array(
	'host' => '127.0.0.1',
	'user' => 'root',
	'pass' => 'qiang123',
	'database' => '1huaian',
	'charset'  => 'utf8',
	'pconncet' => 0,
	//'dbprefix' => 'liv_',
);
//M2O数据
$m_db = array(
	'host' => '127.0.0.1',
	'user' => 'root',
	'pass' => 'qiang123',
	'database' => '1huaian',
	'charset'  => 'utf8',
	'pconncet' => 0,
	//'dbprefix' => 'liv_',
);


$c_DB = new db(); $s_DB = new db(); $h_DB = new db(); $m_DB = new db();
$c_DB->connect($c_db['host'], $c_db['user'], $c_db['pass'], $c_db['database'], $c_db['charset'], $c_db['pconnect'], $c_db['dbprefix']);
$s_DB->connect($s_db['host'], $s_db['user'], $s_db['pass'], $s_db['database'], $s_db['charset'], $s_db['pconnect'], $s_db['dbprefix']);
$h_DB->connect($h_db['host'], $h_db['user'], $h_db['pass'], $h_db['database'], $h_db['charset'], $h_db['pconnect'], $h_db['dbprefix']);
$m_DB->connect($m_db['host'], $m_db['user'], $m_db['pass'], $m_db['database'], $m_db['charset'], $m_db['pconnect'], $m_db['dbprefix']);

//LivCMS对接数据(录播)
function livcms_one()
{
	//liv_contentmap ------- pubdate,title,userid,columnid
	//liv_autorecord ------- playurl
	global $c_DB,$column;
	foreach($column as $k => $v)
	{
		$sql = 'select c.pubdate,c.title,c.userid,c.columnid,a.playurl from liv_contentmap c 
			left join liv_autorecord a on c.contentid=a.autorecordid where c.status=3 and c.columnid='.$k;
		$q = $c_DB->query($sql);
		while($row = $c_DB->fetch_array($q))
		{
			$tmp = explode('#',$row['playurl']);
			$programid = $tmp[0];  //节目id
			$re[$programid] = array(
				'title' => $row['title'],
				'userid' => $row['userid'],
				'columnid' => $column[$row['columnid']],
				'pubdate' => $row['pubdate'],
			);
		}
	}
	file_put_contents('./tmp/lubo.txt',var_export($re,1));
}
//LivCMS对接数据(标注)
function livcms_two()
{
	file_put_contents('./tmp/biaozhu.txt',var_export($re,1));
}

//尚为对接数据
//video_edition()
//streams(file_name,resource_length,resource_size)
//stream_publishpoint(file_relpath) 
//stream_server_point(publishpoint_location)
function shangwei()
{
	//以节目id为键值 节目信息为内容
	global $s_DB;
	//program(id,creation_time,editor_id)
	//program_video(id,duration,startpoint,)
	$sql = 'select p.id as programid,p.creation_time,p.editor_id,pv.id,pv.duration,pv.startpoint from program p 
			left join program_video pv on pv.programid=p.id';
	$q = $s_DB->query($sql);
	while($row = $s_DB->fetch_array($q))
	{
		$re[$row['programid']] = array(
			'creation_time' => $row['creation_time'],
			'editor_id' => $row['editor_id'],
			'id' => $row['id'],
			'duration' => $row['duration'],
			'startpoint' => $row['startpoint'],
		);
	}
	file_put_contents('./tmp/prgram.txt',var_export($re,1));
}
function files()
{
	//以视频id为键值 视频信息为内容
	global $s_DB;
	$sql = "select s.id as video_id,s.file_name,s.resource_length,s.resource_size,sp.file_relpath,ssp.publishpoint_location 
			from streams s left join stream_publishpoint sp on s.id=sp.stream_id 
			left join stream_server_point ssp on sp.publishpoint_id=ssp.id";
	$q = $s_DB->query($sql);
	while ($row = $s_DB->fetch_array($q))
	{
		$re[$row['video_id']] = array(
			'file_name' => $row['file_name'],
			'resource_length' => $row['resource_length'],
			'resource_size' => $row['resource_size'],
			'file_relpath' => $row['file_relpath'],
			'publishpoint_location' => $row['publishpoint_location'],
		);
	}
	file_put_contents('./tmp/file.txt', var_export($re,1));
}
function ve()
{
	//以视频&节目关联id为键值 视频信息为内容
	global $s_DB,$file,$program;
	$sql = "select program_videoid,videoid from video_edition";
	$q = $s_DB->query($sql);
	while ($row = $s_DB->fetch_array($q))
	{
		$re[$row['program_videoid']] = array(
			'file_name' => $file[$row['videoid']]['file_name'],
			'resource_length' => $file[$row['videoid']]['resource_length'],
			'resource_size' => $file[$row['videoid']]['resource_size'],
			'file_relpath' => $file[$row['videoid']]['file_relpath'],
			'publishpoint_location' => $file[$row['videoid']]['publishpoint_location'],
		);
	}
	
	file_put_contents('./tmp/ve.txt', var_export($re,1));
}
function shangwei_combine()
{
	//整合尚为数据
	global $program,$ve;
	foreach($program as $k => $v)
	{
		$re[$k] = array(
			'creation_time' => $v['creation_time'],
		    'editor_id' => $v['editor_id'],
		    'duration' => $v['duration'] ? $v['duration'] : $ve[$v['id']]['resource_length'],
		    'startpoint' => $v['startpoint'],
			'file_name' => $ve[$v['id']]['file_name'],
		    'resource_length' => $ve[$v['id']]['resource_length'],
		    'resource_size' => $ve[$v['id']]['resource_size'],
		    'file_relpath' => $ve[$v['id']]['file_relpath'],
		    'publishpoint_location' => $ve[$v['id']]['publishpoint_location'],
		);
	}
	file_put_contents('./tmp/shangwei.txt',var_export($re,1));
}
//生成移植数据
function lubo()
{
	global $lubo,$shangwei;
	foreach($lubo as $k => $v)
	{
		$re[$k] = array(
			'title' => $v['title'],
		    'userid' => $v['userid'],
		    'columnid' => $v['columnid'],
		    'pubdate' => $v['pubdate'],
		
			'creation_time' => $shangwei[$k]['creation_time'],
		    'editor_id' => $shangwei[$k]['editor_id'],
		    'duration' => $shangwei[$k]['duration'],
		    'startpoint' => $shangwei[$k]['startpoint'],
		    'file_name' => $shangwei[$k]['file_name'],
		    'resource_length' => $shangwei[$k]['resource_length'],
		    'resource_size' => $shangwei[$k]['resource_size'],
		    'file_relpath' => $shangwei[$k]['file_relpath'],
		    'publishpoint_location' => $shangwei[$k]['publishpoint_location'],
		);
	}
	file_put_contents('./tmp/lubo_data.txt',var_export($re,1));
}

//将移植数据插入移植表
function insert()
{
	global $h_DB;
}

//开始迁移
function move()
{
	global $m_DB;
}



$a = $_GET['a'];
if($a)
{
	$a();
}
else 
{
	echo '方法名:<br/> livcms_one </br> livcms_two </br> shangwei </br> insert </br> move';
}


function hg_pre($arr = array())
{
	echo "<pre>";
		print_r($arr);
	echo "</pre>";
}


?>