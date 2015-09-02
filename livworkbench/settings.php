<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: index.php 2 2011-05-03 10:51:54Z develop_tong $
***************************************************************************/
define('WITH_DB', true);
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'settings');
require('./global.php');
require_once(ROOT_PATH . 'lib/class/curl.class.php');
class settings extends uiBaseFrm
{	
	private $app;
	private $mibao;
	function __construct()
	{
		parent::__construct();
		$appuniqueueid = $this->input['app_uniqueid'];
		
		//如果是auth应用的话就加载密保卡操作类
		if($appuniqueueid == 'auth')
		{
			include_once(ROOT_PATH .'lib/class/MibaoCard.class.php');
			$this->mibao = new MibaoCard();
		}

		if (!$appuniqueueid)
		{
			$mid = intval($this->input['mid']);
			$sql = 'SELECT app_uniqueid FROM ' . DB_PREFIX . "modules WHERE id='{$mid}'";
			$app = $this->db->query_first($sql);
			if (!$app)
			{
				$this->ReportError('此应用不存在!');
			}
			$appuniqueueid = $app['app_uniqueid'];
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . "applications WHERE softvar='{$appuniqueueid}'";
		$app = $this->db->query_first($sql);
		if (!$app)
		{
			$this->ReportError('此应用不存在!');
		}
		$app['mid'] = $mid;
		$app['dir'] = str_replace($app['admin_dir'], '', $app['dir']);
		$this->app = $app;
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$app = $this->app;
		$curl = new curl($app['host'], $app['dir']);
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a', 'setting_group');
		$setting_groups = $curl->request('configuare.php');
		$setting_groups = $setting_groups[0];

		$curl->initPostData();
		$curl->addRequestData('a', 'settings');
		$settings = $curl->request('configuare.php');

		$sql = 'SELECT * FROM ' . DB_PREFIX . "crontab WHERE app_uniqueid='{$this->app['softvar']}'";
		$q = $this->db->query($sql);
		$crontabs = array();
		$exist_crontabs = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['run_time'] = date('Y-m-d H:i:s',$r['run_time']);
			if ($r['is_use'])
			{
				$r['is_use'] = '是';
				$r['op'] = '停止';
			}
			else
			{
				$r['is_use'] = '否';
				$r['op'] = '启用';
			}
			$exist_crontabs[$r['mod_uniqueid']] = $r['id'];
			$crontabs[$r['id']] = $r;
		}
		$curl->initPostData();
		$curl->addRequestData('a', 'get_cron_file');
		$init_crontabs = $curl->request('configuare.php');
		
		$used_crontables = array();
		if ($init_crontabs)
		{
			$testcurl = new curl($app['host'], $app['dir'] . 'cron/');
			$testcurl->mNotInitedNeedExit = false;
			$testcurl->setErrorReturn(false);
			foreach($init_crontabs AS $cron)
			{
				$testcurl->initPostData();
				$testcurl->addRequestData('a', 'initcron');
				$crondata = $testcurl->request($cron);
				if (!is_array($crondata))
				{
					continue;
				}
				$crondata = $crondata[0];

				if (!$crondata['mod_uniqueid'])
				{
					continue;
				}
				if ($exist_crontabs[$crondata['mod_uniqueid']])
				{
					//计划任务数据有变动
					$data = array(
						'name' => $crondata['name'],
						'brief' => $crondata['brief'],
						'file_name' => $cron,
					);
					hg_fetch_query_sql($data, 'crontab', 'id=' . $exist_crontabs[$crondata['mod_uniqueid']]);
					$crontabs[$exist_crontabs[$crondata['mod_uniqueid']]]['file_name'] = $cron;
					$crontabs[$exist_crontabs[$crondata['mod_uniqueid']]]['name'] = $data['name'];
					$crontabs[$exist_crontabs[$crondata['mod_uniqueid']]]['brief'] = $data['brief'];
					unset($exist_crontabs[$crondata['mod_uniqueid']]);
				}
				else
				{ //新增计划任务
					$data = array(
						'app_uniqueid' => $this->app['softvar'],
						'mod_uniqueid' => $crondata['mod_uniqueid'],
						'name' => $crondata['name'],
						'brief' => $crondata['brief'],
						'space' => $crondata['space'],
						'file_name' => $cron,
						'is_use' => $crondata['is_use'],
						'host' => $this->app['host'],
						'dir' => $this->app['dir'] . 'cron/',
						'port' => 80,
						'run_time' => TIMENOW,
						'create_time' => TIMENOW,
					);
					hg_fetch_query_sql($data, 'crontab');
					$data['id'] = $this->db->insert_id();
					$data['run_time'] = date('Y-m-d H:i:s',$data['run_time']);
					if ($data['is_use'])
					{
						$data['is_use'] = '是';
						$data['op'] = '停止';
					}
					else
					{
						$data['is_use'] = '否';
						$data['op'] = '启用';
					}
					$crontabs[$data['id']] = $data;
				}
			}
			//清除已经取消的计划任务
			if ($exist_crontabs)
			{
				$sql = 'DELETE FROM ' . DB_PREFIX . 'crontab WHERE id IN(' . implode(',', $exist_crontabs) . ')';
				$this->db->query($sql);
				foreach ($exist_crontabs AS $id)
				{
					unset($crontabs[$id]);
				}
			}
		}
		else
		{
			//该应用无计划任务
			$sql = 'DELETE FROM ' . DB_PREFIX . "crontab WHERE app_uniqueid='{$this->app['softvar']}'";
			$this->db->query($sql);
			$crontabs = array();
		}
		if ($crontabs)
		{
			$setting_groups['cron'] = '计划任务';
		}

        /**************获取水印设置***************/
        if ($app['app_uniqueid'] != 'material' && !empty($this->settings['App_material']))
        {
            $curl = new curl($this->settings['App_material']['host'], $this->settings['App_material']['dir']);
            $curl->setSubmitType('post');
            $curl->setReturnFormat('json');
            $curl->initPostData();
            $curl->setErrorReturn('');
            $curl->addRequestData('app_uniqueid', $app['app_uniqueid']);
            $curl->addRequestData('a', 'fetchWatermarkSet');
            $watermark = $curl->request('admin/material_update.php');
            isset($watermark[0]) && $watermark = $watermark[0];
        }

        /**************获取水印设置***************/

		if (DEVELOP_MODE)
		{
			$s = '<ul class="form_ul">
					<li class="i">
						<div class="form_ul_div">
							<span  class="title">&nbsp;&nbsp;&nbsp;测试配置1：</span>
							<input type="text" value="{$settings[\'define\'][\'DB_PREFIX\']}" name=\'define[DB_PREFIX]\' style="width:200px;">
							<font class="important" style="color:red"></font>
						</div>
					</li>
					<li class="i">
						<div class="form_ul_div">
							<span  class="title">&nbsp;&nbsp;&nbsp;测试配置2：</span>
							<input type="text" value="{$settings[\'base\'][\'testset\'][\'host\']}" name=\'base[testset][host]\' style="width:200px;">
							
							<font class="important" style="color:red"></font>
						</div>
					</li>
					<li class="i">
						<div class="form_ul_div">
							<span  class="title">&nbsp;&nbsp;&nbsp;测试配置21：</span>			
							{template:form/radio,base[testset][open],$settings[\'base\'][\'testset\'][\'open\'],$option}
							<font class="important" style="color:red"></font>
						</div>
					</li>
					<li class="i">
						<div class="form_ul_div">
							<span  class="title">&nbsp;&nbsp;&nbsp;测试配置3：</span>
							<input type="text" value="{$settings[\'base\'][\'testsetad\']}" name=\'base[testsetad]\' style="width:200px;">
							
							<font class="important" style="color:red"></font>
						</div>
					</li>
					<li class="i">
						<div class="form_ul_div">
							<span  class="title">&nbsp;&nbsp;&nbsp;测试配置4：</span>
							<input type="text" value="{$settings[\'base\'][\'article_status\'][1]}" name=\'base[article_status][1]\' style="width:200px;">
							<input type="text" value="{$settings[\'base\'][\'article_status\'][2]}" name=\'base[article_status][2]\' style="width:200px;">
							<input type="text" value="{$settings[\'base\'][\'article_status\'][3]}" name=\'base[article_status][3]\' style="width:200px;">
							<input type="text" value="{$settings[\'base\'][\'article_status\'][4]}" name=\'base[article_status][4]\' style="width:200px;">
							
							<font class="important" style="color:red"></font>
						</div>
					</li>
				</ul>';
				$example = nl2br(htmlspecialchars($s));
				$this->tpl->addVar('example', $example);
				$this->tpl->setScriptDir(); 
				$this->tpl->setTemplateVersion();
		}
		else
		{
			$this->tpl->setScriptDir('app_' . $app['softvar'] . '/'); 
			$this->tpl->setTemplateVersion($app['softvar'] . '/' . $app['version']); 
		}
		$this->tpl->setSoftVar($app['softvar']); //设置软件界面
		
		$this->tpl->addVar('setting_groups', $setting_groups);
		$this->tpl->addVar('crontabs', $crontabs);
		$this->tpl->addVar('settings', $settings);
		$this->tpl->addVar('firstvisit', $this->input['mid']);
		$this->tpl->addVar('app_uniqueid', $this->app['softvar']);
        $this->tpl->addVar('watermark', $watermark);
		$this->tpl->outTemplate('settings');
	}

	public function chgstate()
	{
		$id = intval($this->input['id']);
		$sql = 'SELECT * FROM ' . DB_PREFIX . "crontab WHERE app_uniqueid='{$this->app['softvar']}' AND id = " . $id;
		$cron = $this->db->query_first($sql);
		if (!$cron)
		{
			$this->ReportError('此计划任务不存在或您的操作非法!');
		}
		$sql = 'UPDATE ' . DB_PREFIX . "crontab SET is_use=" . intval(!$cron['is_use']) . ' WHERE id=' . $id;
		$this->db->query($sql);
		if ($cron['is_use'])
		{
			$clew = '计划任务[' . $cron['name'] . ']已停止';
		}
		else
		{
			$clew = '计划任务[' . $cron['name'] . ']已启用';
		}	
		if ($cron['is_use'])
		{
			$cron['is_use'] = '否';
			$cron['op'] = '启用';
		}
		else
		{
			$cron['is_use'] = '是';
			$cron['op'] = '停止';
		}
		$this->redirect($clew, 0, 0, '', 'hg_chg_state("' . $cron['id'] . '", "' . $cron['is_use'] . '", "' . $cron['op'] . '")');
	}

	public function modify_space()
	{
		$id = intval($this->input['id']);
		$sql = 'SELECT * FROM ' . DB_PREFIX . "crontab WHERE app_uniqueid='{$this->app['softvar']}' AND id = " . $id;
		$cron = $this->db->query_first($sql);
		if (!$cron)
		{
			$this->ReportError('此计划任务不存在或您的操作非法!');
		}
		$space = intval($this->input['space']);
		if (!$space)
		{
			$space = 300;
		}
		$sql = 'UPDATE ' . DB_PREFIX . "crontab SET space=" . $space . ' WHERE id=' . $id;
		$this->db->query($sql);
		$this->redirect('', 0, 0, '', 'hg_chg_space("' . $cron['id'] . '", "' . $space . '")');
	}

    public function modify_next_time()
    {
        $id = intval($this->input['id']);
        $sql = 'SELECT * FROM ' . DB_PREFIX . "crontab WHERE app_uniqueid='{$this->app['softvar']}' AND id = " . $id;
        $cron = $this->db->query_first($sql);
        if (!$cron)
        {
            $this->ReportError('此计划任务不存在或您的操作非法!');
        }
        $next_time = $this->input['next_time'];
        $next_time = strtotime($next_time);
        $sql = "UPDATE " . DB_PREFIX . "crontab SET run_time='" . $next_time . "' WHERE id=" . $id;
        $this->db->query($sql);
        $this->redirect('修改成功');
    }

	public function set($message = '')
	{
		$app = $this->app;
		$curl = new curl($app['host'], $app['dir']);
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a', 'doset');
		$ret = $curl->request('configuare.php');
		if($ret['success'])
		{
			if($ret['isOpenMibao'])
			{
				$this->input['ajax'] = 1;
				$img = $this->mibao->create_secret_image($ret['mibaoInfo']['cardid'],$ret['mibaoInfo']['zuobiao']);
				$this->Redirect('设置已修改','',1,0, "mibaoCallback('".$img."')");
			}
			$this->redirect('设置已修改');
		}
		else
		{
			$this->ReportError('设置修改失败!');
		}
	}
	
	
	public function create()
	{
	}
	
	/*******************************************处理密保卡***********************************************************************/
	//下载密保卡(图片)
	public function download_card()
	{
		if(!$this->mibao)
		{
			$this->ReportError('您不能下载密保卡');
		}
		$this->mibao->download_card($this->input['img'], $this->user['user_name']);
		//删除密保卡
		$cmd = " rm -Rf cache/mibao/";
		exec($cmd);
	}
	
	//下载所有的密保卡（zip打包）
	public function download_all_mibao()
	{
		if(!$this->mibao)
		{
			$this->ReportError('您不能下载密保卡');
		}
		$ret = $this->mibao->bind_all_user();
		if($ret)
		{
			$mibaoSavePath = array();//密保卡的保存路径
			foreach ($ret AS $k => $v)
			{
				$mibaoSavePath[] = $this->mibao->create_secret_image($k,$v);
			}
		}
		
		if($mibaoSavePath)
		{
			$cmd = 'cd cache/ ;zip -r mibao/mibao.zip ';
			foreach($mibaoSavePath AS $k => $v)
			{
				$cmd .= ' ' . str_replace('/cache', '', $v) . ' '; 
			}
			exec($cmd);
		}
		//下载
		$this->mibao->download_card_zip('cache/mibao/mibao.zip');
		//删除密保卡
		$cmd = " rm -Rf cache/mibao/";
		exec($cmd);
	}
	/*******************************************处理密保卡***********************************************************************/
}
include (ROOT_PATH . 'lib/exec.php');
?>