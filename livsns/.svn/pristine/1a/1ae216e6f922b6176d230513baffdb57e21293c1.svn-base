<?php
require('global.php');
define('MOD_UNIQUEID', 'dynpro');
class dynproUpdate extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/common.php');
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	public function create()
	{
		/*if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if(!in_array('dynpro',$action))
			{
				$this->errorOutput("NO_PRIVILEGE");
			}
		}*/
		
		if (!$this->input['title']) {
			$this->errorOutput('title is empty');
		}
		$data = array(
			'site_id'	    => $this->input['site_id'],
			'title' 		=> $this->input['title'],
			'filepath' 		=> $this->input['filepath'],
			'filename'		=> $this->input['filename'],
			'headcode'		=> $this->input['headcode'],
			'mediacode'		=> $this->input['mediacode'],
			'tailcode'		=> $this->input['tailcode'],
			'type'			=> intval($this->input['type']),
			'callbackfunc'  => $this->input['callbackfunc'],
			'data_id'		=> $this->input['data_id'],
			'user_id'		=> $this->user['user_id'],
			'user_name'     => $this->user['user_name'],
			'create_time'   => TIMENOW,
			'update_time'   => TIMENOW,
		);
		$sql = "SELECT id FROM " .DB_PREFIX. "dynpro WHERE filepath = '".$data['filepath']."' AND filename = '".$data['filename']."'";
	    if ($this->db->query_first($sql)) {
	    	$this->errorOutput('文件名重复');
	    }
		if (!class_exists(publishconfig)) {
			include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		}
		$this->pub = new publishconfig();		
		$site_id = $data['site_id'] ? $data['site_id'] : 1;
		$site_info = $this->pub->get_site_first('sub_weburl,weburl,program_dir', $site_id);
		if ($site_info) {
			$data['access_uri'] = rtrim($site_info['sub_weburl'], '.') . '.' . rtrim($site_info['weburl'], '/') . '/' . rtrim($site_info['program_dir'], '/') . '/' . rtrim($data['filepath'], '/') . '/' . $data['filename'];
		}		
		$data['id'] = $this->db->insert_data($data, 'dynpro');
		$this->built_api($data['id']);     //生成api
		$this->addItem($data);
		$this->output();
	}
	
	public function update()
	{
		/*if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if(!in_array('dynpro',$action))
			{
				$this->errorOutput("NO_PRIVILEGE");
			}
		}*/
		
		$id = intval($this->input['id']);
		if (!$id) {
			$this->errorOutput('NO ID');
		}
		$condition = " id = " . $id;
		$data = array(
			'site_id'	    => $this->input['site_id'],
			'title'			=> $this->input['title'],
			'filepath'		=> $this->input['filepath'],
			'filename'		=> $this->input['filename'],
			'headcode'		=> $this->input['headcode'],
			'mediacode'		=> $this->input['mediacode'],
			'tailcode'		=> $this->input['tailcode'],
			'type'			=> intval($this->input['type']),
			'callbackfunc'  => $this->input['callbackfunc'],
			'data_id'		=> $this->input['data_id'],			
		);
		$sql = "SELECT id FROM " .DB_PREFIX. "dynpro WHERE filepath = '".$data['filepath']."' AND filename = '".$data['filename']."'";
	    if ($q = $this->db->query_first($sql) && $q['id'] == $id) {
	    	$this->errorOutput('文件名重复');
	    }		
		if (!class_exists(publishconfig)) {
			include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		}
		$this->pub = new publishconfig();		
		$site_id = $data['site_id'] ? $data['site_id'] : 1;
		$site_info = $this->pub->get_site_first('sub_weburl,weburl,program_dir', $site_id);
		if ($site_info) {
			$data['access_uri'] = rtrim($site_info['sub_weburl'], '.') . '.' . rtrim($site_info['weburl'], '/') . '/' . rtrim($site_info['program_dir'], '/') . '/' . rtrim($data['filepath'], '/') . '/' . $data['filename'];
		}			
		$affected_rows = $this->db->update_data($data, 'dynpro', $condition);
		if ($affected_rows) {
			$arr = array(
				'update_time' => TIMENOW,
			);
			$this->db->update_data($arr, 'dynpro', $condition);
		}
		$data['id'] = $id;
		$this->built_api($id);
		$this->addItem($data);
		$this->output();
	}
	
	public function delete()
	{
		/*if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if(!in_array('dynpro',$action))
			{
				$this->errorOutput("NO_PRIVILEGE");
			}
		}*/
		
		$id = urldecode($this->input['id']);
		if (!$id) {
			$this->errorOutput('NO ID');
		}
		$sql = "DELETE FROM " . DB_PREFIX . "dynpro WHERE id IN('" . $id . "')";
		$this->db->query($sql);
		$this->addItem($id);
		$this->output();
	}
	
	public function audit()
	{
		
	}
	
	public function sort()
	{
		
	}
	
	public function publish()
	{
		
	}
	
	public function built_api($id= '')
	{
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if(!in_array('dynpro',$action))
			{
				$this->errorOutput("NO_PRIVILEGE");
			}
		}
		
		$id = $id ? $id : urldecode($this->input['id']);
		if (!$id) {
			$this->errorOutput('NO ID');
		}
		if (!class_exists(publishconfig)) {
			include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		}
		$this->pub = new publishconfig();		
		$sql = "SELECT * FROM ".DB_PREFIX."dynpro WHERE id IN(".$id.")";
		$q = $this->db->query($sql);
		while ($row = $this->db->fetch_array($q)) {		
			$dir = array();	
			if ($row['site_id']) {
				$site_info = $this->pub->get_site_first('site_dir,program_dir,id', $row['site_id']);
				$site_dir = rtrim($site_info['site_dir'], '/') . '/';
				$program_dir = rtrim($site_info['program_dir'], '/') . '/';
				$dir[] = $site_dir . $program_dir;
				$condition = " AND site_id = " . $site_info['id'] . " AND childdomain != ''";
				$column = $this->pub->get_column('column_dir,relate_dir,site_id', $condition);
				if (is_array($column) && count($column) > 0) {
					foreach ($column as $k => $v) {
						$dir[] = $site_dir . rtrim($v['column_dir'], '/') . '/' . $program_dir;
					}
				}
			}
			else		//所有站点共用
			{
				$sites = $this->pub->get_site();
				if (is_array($sites) && count($sites) > 0 ) {
					$siteids = $site_dir = array();
					foreach ($sites as $site_info) {
						$siteids[] = $site_info['id'];
						$site_dir[$site_info['id']] = rtrim($site_info['site_dir'], '/') . '/';
						$program_dir[$site_info['id']] = rtrim($site_info['program_dir'], '/') . '/';
						$dir[] = rtrim($site_info['site_dir'], '/') . '/' . rtrim($site_info['program_dir'], '/') . '/';
					}
					$siteids = implode(',', $siteids);
					$condition = " AND site_id IN('" . $siteids . "') AND childdomain != ''";
					$column = $this->pub->get_column('column_dir,relate_dir,site_id', $condition);
					if (is_array($column) && count($column) > 0 ) {
						foreach ($column as $k => $v) {
							$dir[] = $site_dir[$v['site_id']] . rtrim($v['column_dir'], '/') . '/' . $program_dir[$v['site_id']];	
						}
					}
				}
			}
			$dynpropath = $row['filepath'] ? rtrim($row['filepath'], '/') . '/' : '';
			$relative_num = count(explode('/', $dynpropath))-1;
			$row['relative_dir'] = $relative_num == 0 ? './' : str_repeat('../',$relative_num);
			$html = $this->join_html($row);	
			if (is_array($dir) && count($dir) > 0 ) {
				foreach ($dir as $k => $v) {
					$filepath = $v . $dynpropath;
					hg_mkdir($filepath);
					hg_file_write($filepath . $row['filename'], $html);
				}
			}	
		}
		$this->addItem($id);
		$this->output();
	}
	
	private function join_html($param)
	{
		$__str  =  '';
		$__str  =  "<?php \n" .
				 'define(\'M2O_ROOT_PATH\', \'' . $param['relative_dir'] . '\');' . "\n" .
		 		 'require(M2O_ROOT_PATH . "global.php");' . "\n";
		$data_source_info = common::get_datasource_info($param['data_id']);
		$input_param = $data_source_info['input_param'];
		if (is_array($input_param) && count($input_param) > 0) {
			foreach	($input_param as $k => $v) {
			    $v['value'] = html_entity_decode($v['value']);
				if ($v['add_status'] == 1) {
					if (($v['type'] == 'auto') && (strpos($v['value'], '.') !== false)) {
						$val = explode('.', $v['value']);
						$__str .= '$input_param[\''.$v['sign'].'\'] = isset($_REQUEST[\'' . $v["sign"] . '\']) ? $_REQUEST[\'' . $v["sign"] . '\'] : $gGlobalConfig[\'v_'.$val[0].'\'][\''.$val[1].'\'];' . "\n";
					}
					else {
						$__str .= '$input_param[\'' . $v["sign"] . '\'] = $_REQUEST[\'' . $v["sign"] . '\'] ? $_REQUEST[\'' . $v["sign"] . '\'] : \''. $v["value"] . '\';' . "\n";
					}
				}
				else {
					if (($v['type'] == 'auto') && (strpos($v['value'], '.') !== false)) {
						$val = explode('.', $v['value']);
						$__str .= '$input_param[\''.$v['sign'].'\'] = $gGlobalConfig[\'v_'.$val[0].'\'][\''.$val[1].'\'];' . "\n";
					}
					else {										
						$__str .= '$input_param[\'' . $v["sign"] . '\'] = \'' . $v["value"] . '\';' . "\n";
					}	
				}	
			}
		}
		
		$__str  .= 'if($input_param){' . "\n" .
				 '	include_once(M2O_ROOT_PATH . "include/' . $param["data_id"] . '.php");' . "\n" . 
				 '	$obj = new ds_' . $param['data_id'] . '();' . "\n". 
				 '  $data = $obj->show($input_param);' . "\n";
					$__str  .= '$__html = \'' . $this->parse_html(htmlspecialchars_decode(html_entity_decode($param['headcode'], ENT_QUOTES), ENT_QUOTES)) . '\';' . "\n";
					$__str  .= '$ret = $input_param[\'need_count\'] == 1 ? $data[\'data\'] : $data;' . "\n";
					$__str  .= 'if(is_array($ret) && count($ret)>0){foreach($ret as $k => $v) { if(is_array($v) && count($v) > 0 ){foreach($v as $__key => $__value){$$__key = $__value;}} ' . "\n";
					$__str  .= '	$__html .= \'' . $this->parse_html(htmlspecialchars_decode(html_entity_decode($param['mediacode'],ENT_QUOTES),ENT_QUOTES)) . '\';' . "\n";
					$__str  .= '}}' . "\n";
					if ($param['type'] == 1) {
						$__str .= 'if($__html){echo $__html;}else{echo json_encode($data);}exit;' . "\n";
//						$__str .= 'echo $__html;exit;';
					}
					$__str  .= '$__html .= \'' . $this->parse_html(htmlspecialchars_decode(html_entity_decode($param['tailcode'],ENT_QUOTES),ENT_QUOTES)) . '\';' . "\n";
				 $__str  .= '}' . "\n";
		$__str   .= '?>' . "\n";
		if ($param['callbackfunc']) {
			$__str .= $param['callbackfunc'] .'(\'<?php echo $__html; ?>\')' . "\n";
		}
		else {
			$__str .= 'document.write(\'<?php echo $__html; ?>\')' . "\n";
		}
		return $__str;			
	}
	
	private function set_cache($html)
	{
		$html = $this->parse_html($html);
		hg_file_write(CACHE_DIR . 'dynpro_cache.php', $html);
	}
	
	private function parse_html($__html)
	{
		if (!$__html) {
			return '';
		}
		$preg = array(
			"/{([\w\[\]\'\"\x7f-\xff\+\-\*\/\%\$\s]+)}/",
		);
		$replace = array(
			"'.\\1.'",
		);
		$__html = 	preg_replace($preg, $replace, $__html);
		return $__html;
	}
	
	public function unknow()
	{
		$this->errorOutput('此方法不存在');
	}	
	
}
$out = new dynproUpdate();
$action = $_INPUT['a'];
if (!method_exists($out,$action)) {
	$action = 'unknow';
}
$out->$action();
?>
