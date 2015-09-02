<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: template.class.php 519 2010-12-14 06:12:26Z develop_tong $
***************************************************************************/
/**
 * 系统模板解析类，解析系统模板
 * @author develop_tong
 *
 */
class Template
{
    var $mTemplateCacheDir = '';
    var $mTemplateDir = '';
	function __construct()
	{
		$this->mTemplateCacheDir = CACHE_DIR . 'tpl/';
		hg_mkdir($this->mTemplateCacheDir);
		$this->mTemplateDir = TEMPLATES_DIR;
	}
	
	function __destruct()
	{
	}
	
	/**
	 * 解析模板，生成模板缓存
	 * @param $FileName
	 * @return String
	 */
	public function ParseTemplate($FileName = '')
	{
		$file = $this->mTemplateDir . $FileName;
		if (!is_file($file))
		{
			return false;
		}
		$content = file_get_contents($file);
		$content = $this->ParseNestTemplate($content);
		$cache_file = $this->mTemplateCacheDir . md5($FileName . realpath($this->mTemplateDir)) . '.php';
		hg_file_write($cache_file, $content);
		return $cache_file;
	}
	
	/**
	 * 解析嵌套模板，将嵌套引入的模板生成单一文件
	 * @param $Content， 模板内容
	 * @return String
	 */
	private function ParseNestTemplate($Content)
	{
		$eregtag = '/<\?php[\s]+(?:include_once|include)[\s]*(\({0,1})[\s]*hg_load_template[\s]*\(([\'|\"])([^\(].+?(?=\\2|\)|\?>))\\2\)\\1[;]*[\s]*?\?>/ise';
		$Content = preg_replace($eregtag, "\$this->GetTemplateContent('\\3')", $Content);
		return $Content;
	}
	
	/**
	 * 获取模板文件内容
	 * @param $template 模板名
	 * @return String 文件内容
	 */
	private function GetTemplateContent($template)
	{
		$file = $this->mTemplateDir . $template . '.tpl.php';
		$content = @file_get_contents($file);
		$Content = $this->ParseNestTemplate($content);
		return $content;
	}
	
	/**
	 * 解析模板中的特定的变量
	 * @param $Content， 模板内容
	 * @return String
	 */
	private function ExecTemplateVar($Content)
	{
		return $Content;
	}
}
?>