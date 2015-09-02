<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: index.php 1571 2011-01-06 06:30:12Z repheal $
***************************************************************************/

define('ROOT_DIR', '../');
define('SCRIPTNAME', 'index');
require('./global.php');
require(ROOT_PATH . 'lib/class/status.class.php');
require_once(ROOT_PATH . 'lib/user/user.class.php');
class index extends uiBaseFrm
{	
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{ 
		$_REQUEST['ajax'] = 1;
		$html = $this->tpl->outTemplate('top','hg_getnotify');
		$html = json_decode($html,true);
		return $html['html'];
	}
	
}
$out = new index();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$html = $out->$action();
$htmls = str_replace(array("\r", "\n"), '', $html);
?> 
document.write('<?php echo $htmls;?>'); 
 