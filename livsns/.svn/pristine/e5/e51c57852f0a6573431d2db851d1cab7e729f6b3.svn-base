<?php
function log2file($user, $level, $message, $input, $output=array())
{
	if(!LOG_LEVEL)
	{
		return;
	}
	if(LOG_FOR_USER != 'ALL' && $user['user_name'] != LOG_FOR_USER)
	{
		return;
	}
	$level = strtoupper($level);
	$log_level = array('ERROR'=>1, 'DEBUG'=>2, 'ALL'=>3);
	if($log_level[$level] > LOG_LEVEL)
	{
		return;
	}
	$log_path = CUR_CONF_PATH . 'data/log/' . date('Y') . '/' . date('m') . '/';
	if(!is_dir($log_path))
	{
		hg_mkdir($log_path);
	}
	
	$input = json_encode($input);
	$output = json_encode($output);
	$time = date('Y-m-d H:i');
	$user = @json_encode($user);
	$log_message_tpl = <<<LC
Level   : {$level}
Message : {$message}
Input   : {$input}
Ouput   : {$output}
Date	: {$time}
User	: {$user}\n\n
LC;
	hg_file_write($log_path . 'log-'.date('Y-m-d').'.php', $log_message_tpl, 'a+');
}
?>