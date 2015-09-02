<?php 
require('./lib/template.class.php');
/*
 * 将curl请求方式改变
 * 把传递选项的$_REQUEST
 * 暂时改为数组$this->options
 * 并给出默认值 
 *
 * */
class output
{
	private $mTpl;
	private $options = array(
		'softvar' => 'm2o',
		'group' => 'default',
		'template' => 'index',
	);
	function __construct()
	{
		foreach ($_REQUEST as $k => $v)
		{
			$this->options[$k] = $v;
		}
	}
	public function show()
	{
		$softvar = $this->options['softvar'];
		$group = $this->options['group'];
		$this->mTpl = new Template($softvar, $group);
		$template_name = $this->options['template'];
		echo $this->mTpl->ParseTemplate($template_name);
	}
	public function getassets()
	{
		$softvar = $this->options['softvar'];
		$group = $this->options['group'];
		$this->mTpl = new Template($softvar, $group);
		$template_name = $this->options['template'];
		$css = $this->mTpl->fetchAssets($template_name);
		echo json_encode($css);
	}
	public function getcss()
	{
		$softvar = $this->options['softvar'];
		$group = $this->options['group'];
		$this->mTpl = new Template($softvar, $group);
		$template_name = $this->options['template'];
		$css = $this->mTpl->fetchCssFile($template_name);
		echo json_encode($css);
	}

	public function getjs()
	{
		$softvar = $this->options['softvar'];
		$group = $this->options['group'];
		$this->mTpl = new Template($softvar, $group);
		$template_name = $this->options['template'];
		echo $this->mTpl->fetchJsFile($template_name);
	}

	public function getvars()
	{
		$softvar = $this->options['softvar'];
		$group = $this->options['group'];
		$this->mTpl = new Template($softvar, $group);
		$template_name = $this->options['template'];
		$vars = $this->mTpl->fetchTemplateVars($template_name);
		echo json_encode($vars);
	}
}
$out = new output();
$action = $_REQUEST['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>
