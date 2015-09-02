<?php
define('ROOT_DIR', '../../');
define('WITHOUT_DB', true);
require(ROOT_DIR . 'global.php');
require(ROOT_DIR . 'frm/configuare_frm.php');
class configuare extends configuareFrm
{	
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}

    function baseSet()
    {
        $basesetting = $this->input['base'];
        $content = @file_get_contents('conf/config.php');
        if (!$content)
        {
            $this->errorOutput('CONFIG_FILE_GONE');
        }
        if (!is_writeable('conf/config.php'))
        {
            $this->errorOutput('CONFIG_FILE_NOT_ALLOW_WRITE');
        }

        if ($basesetting)
        {
            foreach($basesetting AS $k => $v)
            {
                if (is_array($v))
                {
                    $vs = var_export($v, 1);
                }
                else
                {
                    $vs = "'$v'";
                }
                if (isset($this->settings[$k]))
                {
                    $content = preg_replace("/\\\$gGlobalConfig\['{$k}'\]\s*=\s*(.*?;)/is", "\$gGlobalConfig['{$k}'] =  " . $vs . ';', $content);
                }
                else
                {
                    $content = preg_replace("/\?>\n*\s*$/is", "\n\$gGlobalConfig['{$k}'] =  " . $vs . ";\n?>", $content);
                }
                //$this->errorOutput(json_encode($content));
            }
        }

        $write = @file_put_contents('conf/config.php', $content);

        $this->addItem_withkey('success', $write);
        $this->output();
    }

}
$module = 'configuare';
$$module = new $module();  

$func = $_INPUT['a'];
if (!method_exists($$module, $func))
{
	$func = 'show';	
}
$$module->$func();
?>