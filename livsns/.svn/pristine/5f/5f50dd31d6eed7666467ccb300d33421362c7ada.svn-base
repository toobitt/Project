<?php
require_once('./global.php');
require_once(CUR_CONF_PATH . 'lib/message_module.class.php');
class MesMod extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function show()
	{
		$this->setXmlNode('modules' , 'module');
		$mod = new MessageModule();
		if(!$this->input['fid'])
		{
			$sort = $mod->fetch();
			$info = array();
			foreach ($sort AS $k => $r)
			{
				$info[$r['fid']] = $k;
			}
			foreach($this->settings['message_module_type'] as $k=>$v)
			{
				if($info[$k])
				{
					if($k == 1)
					{
						$r = array('id'=>$k,"name"=>$v,"fid"=>0,"depth"=>0, 'input_k' => '_type' ,'attr' => 'attr','is_last'=>1);
					}
					else
					{
						$r = array('id'=>$k,"name"=>$v,"fid"=>0,"depth"=>0, 'input_k' => '_type' ,'attr' => 'attr');
					}		
				}
				else
				{
					$r = array('id'=>$k,"name"=>$v,"fid"=>0,"depth"=>0, 'input_k' => '_type' ,'attr' => 'attr','is_last'=>1);
				}
					$this->addItem($r);
			}
		}
		else 
		{
			if(intval($this->input['fid']) != 1)
			{
				$mod->set('fid=' . intval($this->input['fid']));
				$sort = $mod->fetch();
				foreach ($sort AS $k => $r)
				{
					$this->addItem($r);
				}
			}
		}
		$this->output();
	}
}
$output = new MesMod();
if(!method_exists($output,$_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$output->$action();
?>