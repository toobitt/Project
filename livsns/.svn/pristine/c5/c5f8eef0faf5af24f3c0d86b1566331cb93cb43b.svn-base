<?php
function hg_split_file($file_name,$pre = 'tmp_',$rows = 3000)
{
	if(file_exists($file_name))
	{
		$file_right = substr(sprintf('%o', fileperms($file_name)), -4);
		//echo $file_right;exit;
		if(intval($file_right) != 777)
		{
			@chmod($file_name,0777);
		}
		$cmd = 'split -l ' . $rows . ' ' . $file_name . ' ' . DATA_DIR . '/' . $pre;
		echo exec($cmd);
	}
}

function hg_get_total_line($file_name)
{
	if(file_exists($file_name))
	{
		$handle = fopen($file_name, "r");
		$i = 0;
		while(!feof($handle))
		{	
			++$i;
			fgets($handle, 4096);
		}
		fclose($handle);		
		return $i;
	}
	else
	{
		return false;
	}
}

//返回文件从X行到Y行的内容(支持php5、php4)
function hg_getFileLines($filename, $startLine = 1, $endLine=50, $method='rb')
{
	$content = array();
	$count = $endLine - $startLine;
	if(version_compare(PHP_VERSION, '5.1.0', '>='))// 判断php版本（因为要用到SplFileObject，PHP>=5.1.0）
	{
        $fp = new SplFileObject($filename, $method);
        $fp->seek($startLine-1);// 转到第N行, seek方法参数从0开始计数
		for($i = 0; $i <= $count; ++$i) 
		{
			$content[] = $fp->current();// current()获取当前行内容

			$fp->next();// 下一行
		}
	//	fclose($fp);
	}
	else
	{ //PHP<5.1
		$fp = fopen($filename, $method);
		if(!$fp) return 'error:can not read file';
		for ($i=1;$i<$startLine;++$i)
		{// 跳过前$startLine行
			fgets($fp);
		}
		for($i;$i<=$endLine;++$i)
		{
			$content[]=fgets($fp);// 读取文件行内容
		}
		fclose($fp);
	}
	return array_filter($content); // array_filter过滤：false,null,''
}
?>