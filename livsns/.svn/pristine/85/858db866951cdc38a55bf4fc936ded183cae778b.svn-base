<?php
define('ROOT_DIR','../');
define('ROOT_PATH', ($dir = @realpath(ROOT_DIR)) ? $dir . '/' : ROOT_DIR);
require( ROOT_PATH . 'conf/db_server.conf.php');
require( ROOT_PATH . 'lib/func/functions.php'); 
require(ROOT_PATH . 'lib/func/functions_ui.php'); 
define('STARTTIME', microtime());
define('MEMORY_INIT', memory_get_usage());
include(ROOT_PATH . 'lib/func/debug.php');
$_INPUT = hg_init_input();

date_default_timezone_set(TIMEZONE);
include_once  ROOT_PATH . 'lib/db/db_mysql.class.php';
$db_choice = $_REQUEST['server'];
$db_config = $gDBServer[$db_choice];

$db_server_options = '<label>请选择一个数据库服务器&nbsp;&nbsp;</label><select name="server" id="server" onchange="check_server()"><option value="0">--请选择--</option>';
foreach($gDBServer as $_db => $config)
{
	$selected = ($_db == $db_choice) ? ' selected="selected" ' : '';
	 
	$db_server_options .= '<option value="' . $_db . '" ' . $selected . '>' . $config['name'] . '</option>';
}



$db_server_options .= '</select>';
if($db_choice){ 
	//建立一个数据库连接
	$DB = new db();
	$DB->connect($db_config['host'], $db_config['user'], $db_config['pass'], $db_config['database'], $db_config['charset'], $db_config['pconnect'], $db_config['db_prefix']);
	
	//分页偏移量
	$pp = intval($_REQUEST['pp']); 
	
	//处理sql语句，添加SQL_CALC_FOUND_ROWS参数计算总记录数目
	$db_sql = trim(stripslashes(strtolower(urldecode($_REQUEST['sql'])))); 
	if(!preg_match('/^select(.*)/',$db_sql))
	{
		exit('当前只允许执行select查询语句，其他sql语句不被允许，请<a href="javascript:history.go(-1)">返回</a>，重新输入');  
	}
	
	//添加时间
	$begin_time = $_REQUEST['begin_time'] ? strtotime($_REQUEST['begin_time'] . ' 00:00:00') : strtotime(date('Y-m-d',time()));
	$end_time = $_REQUEST['end_time'] ? strtotime($_REQUEST['end_time'] . ' 23:59:59') : strtotime(date('Y-m-d',time()) . ' 23:59:59');
	$db_sql = str_replace('$begin_time',' ' . $begin_time . ' ',$db_sql);
	$db_sql = str_replace('$end_time' , ' ' . $end_time . ' ',$db_sql); 
	
	
	
	$tt1 = substr($db_sql,6,strlen($db_sql)); 
	$total_sql = substr($db_sql,0,6); 
	$total_sql .= '  SQL_CALC_FOUND_ROWS  ';
	$db_sql = $total_sql . $tt1; 
	
	 
	$db_sql = stripslashes($db_sql);
	//添加limit限制。默认每页显示30条记录
	$str = strstr($db_sql,'limit'); 
	if(!$str)
	{  
		$db_sql .= ' limit ' . $pp . ' , ' . 30;
		$qid = $DB->query($db_sql);
		$total = $DB->query_first('select found_rows() as total'); //此句要用在query之后
		$perpage = 30;
	}
	else
	{ 
		$tmp = explode(',',$str);   
		$perpage = intval(trim($tmp[1])); 
		$qid = $DB->query($db_sql);
		$total = $DB->query_first('select found_rows() as total');
		if($total['total'] > $perpage) 
		{
			$total['total'] = $perpage;
		}
	} 
	
	$total = $total['total'];
	
	//计算分页
	$page_link = array(
		'totalpages' => $total,
		'curpage' => intval($pp),
		'perpage' => $perpage,
		'pagelink' => '?sql=' . urlencode($_REQUEST['sql']) . '&server=' . $db_choice
	);
	$p_links = hg_build_pagelinks($page_link);
	
	$results = array();
	$result_tbl = '<table>'; 
	while(false != ($r = $DB->fetch_array($qid)))
	{  
		$results = array_keys($r);
		$result_tbl1 .= '<tr onmouseover="javascript:this.style.background=\'#D3DFEB\';" onmouseout="this.style.background=\'#fff\';">'; 
		foreach($r as $k => $v)
		{
			$result_tbl1 .= '<td>' . $v . '</td>' ;
		}
		$result_tbl1 .= '</tr>'; 
	}
	$result_tbl_th = '<tr class="result_title">';
	foreach($results as $vv)
	{
		$result_tbl_th .= '<th>' . $vv . '</th>';
	}
	$result_tbl_th .= '</tr>';
	$result_tbl .=  $result_tbl_th . $result_tbl1;
		
	$result_tbl .= '</table>';
	
	echo $db_sql;
} 
?>
<!-- <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> -->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>sql查询工具</title>
<style>
table tr{height:28px;}
.result_title{height:25px;background:#A5D1F3;} 
.result_title th{padding-left: 5px;padding-right: 5px;} 
.pagelink {margin: 5px 0 0; width: auto;height: 15px;}
.pagelink .pages li {background: none repeat scroll 0 0 transparent;border: 0 none;float: left;font-size: 12px;list-style: none outside none;margin: 0;padding: 0;width: auto;}
.pagelink .pages li a { background: none repeat scroll 0 0 #FFFFFF;border: 0 none;float: left;line-height: 15px;padding: 0 10px;width: auto;}
</style>
</head>

<body>
<div style="border:1px solid #ccc;padding-top:10px;">
<!-- <p style="font-size:12px;padding:5px;width:600px;height: 50px;border:1px solid #ccc;background:#FFFFdd;margin-left:10px;margin-top:5px;">如果sql中需要根据需要使用起止时间的参数，请将sql中使用时间的地方换成对应的变量<br /><br /><b>开始时间</b>变量：<b>$begin_time</b>,<b>结束时间</b>:<b>$end_time</b>。若这两个变量在下面均未赋值，将使用当日的时间</p> -->
<form name="sql_query" action="" method="post">
<table style="font-size:12px;margin-left:10px;">
	<tr>
		<td><?php echo $db_server_options?></td><td id="tip4server" style="display:none">(<font style="font-size:11px;color:red;">数据库服务器不可为空，请选择！</font>)</td>
	</tr>
	<tr>
		<td colspan="2" align="center"><font color="red">只支持select查询语句，其他sql语句暂不支持！</font><!-- &nbsp;如果需要添加时间区间参数值，请<a href="javascript:void(0);" onclick="show_params()">点击这里</a> --></td>
	</tr>
	<tr id="show_time_param" style="display:none;"><td>开始时间：<input title="参数名称:$begin_time" type="text" name="begin_time" value="" size="20"/>&nbsp;&nbsp;结束时间：<input title="参数名称:$end_time" type="text" name="end_time" value="" size="20" /></td><td>日期格式:yyyy-mm-dd(如：2011-4-01)</td></tr>
	<tr>
		<td><span style="vertical-align: top;font-size:13px">sql:</span><textarea id="sql" name="sql" style="width:400px;height:120px"><?php echo urldecode($_REQUEST['sql'])?></textarea></td><td id="tip4sql" style="display:none;">(<font style="font-size:11px;color:red;">sql语句不正确，请重新填写</font>)</td>
	</tr>
	<tr>
		<td style="text-align:center;"> <input type="button" name="btn_submit" value="执行" onclick="checkvalue(1);" style="margin-left:320px;"/></td>
	</tr>
</table>
</form>
</div>
<div>
<p>查询结果(共计<b><?php echo intval($total)?></b>条记录)</p>
<div style="border:1px solid #ccc;">
<div class="pagelink" ><?php echo $p_links;?></div>
<br/>
<?php echo $result_tbl?>
<br></br>
<?php echo $p_links;?>
</div>
</div>
<script>
function check_server()
{
	var server_v = document.getElementById('server').value;
	if(server_v == "0")
	{ 
		document.getElementById('tip4server').style.display = 'block';
	}
	else
	{
		document.getElementById('tip4server').style.display = 'none';
	}
}
function checkvalue()
{ 
	var server_v = document.getElementById('server').value;
	if(server_v == "0")
	{ 
		document.getElementById('tip4server').style.display = 'block';
	}
	else
	{
		document.getElementById('tip4server').style.display = 'none';
	}
	var sql_v = document.getElementById('sql').value; 
	if(!sql_v)
	{
		document.getElementById('tip4sql').style.display = 'block';
	}
	else if(sql_v.length < 20)
	{ 
		document.getElementById('tip4sql').style.display = 'block'; 
		document.getElementById('sql').focus();
	}
	else if(sql_v.search(/^select(.*)/i) == -1)
	{ //只允许sql语句
		document.getElementById('tip4sql').style.display = 'block'; 
		document.getElementById('sql').focus();
	}
	else
	{ 
		document.getElementById('tip4sql').style.display = 'none'; 
		document.getElementById('tip4server').style.display = 'none'; 
		document.forms[0].submit();
	}
}
function show_params()
{
	document.getElementById('show_time_param').style.display = 'block';
}
</script>
</body>
</html>