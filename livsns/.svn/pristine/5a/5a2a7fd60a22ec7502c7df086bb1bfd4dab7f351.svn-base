<?php
class Parse extends InitFrm
{
	public 		$data_variable = '$m2o';					//数据源返回顶级变量名称
	public 		$parsed_content = '';						//解析过后内容
	protected 	$relation_map = array();					//当前样式数据返回变量关联哈希表  'data' => 'news>0>title'  'title' => 'news>0>title>0>title2'
	public 		$variables_functions_relation = array();		//样式数据返回变量关联的函数 关系
	public      $mode_variables = array();					//样式变量哈希表
	public 		$curr_variable = '';						//当前变量
	public 		$curr_relation = '';						//当前变量关联关系  news>0>title
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	/**
	 * 解析样式
	 * @param $content
	 * @return String
	 * 
	 */
	public function parse_template($content = '', $cell_id, $mode_info = '', $relation_map = array(), $mode_variable = array(), $variables_functions_relation = array())		
	{
//		$content = '<ul class="rankList"> ' .
//				'{foreach $data}
//		{if empty($title)}
//		<li><span class="front ranknum">1</span><a href="{$content_url}">{$title}</a><span class="more">{$click_num}</span></li> ' .
//		'{/if}{/foreach}
//</ul>';
//		$relation_map = array(
//			'data' => 'news',
//			'content_url' => 'news>0>content_url',
//			'title'       => 'news>0>title',
//			'click_num'   => 'news>0>click_num',
//		);
//		$mode_variable = array(
//			'title' => 99,
//		);
		if(!$content){
			return false;
		}
		$content = str_replace('&copy;','&copy',$content);   //处理json_encode &copy;bug
		$content = html_entity_decode($content,ENT_QUOTES);
		$content = str_replace('&copy','&copy;',$content);	//处理json_encode &copy;bug	
		$this->relation_map = $relation_map;
		$this->mode_variables = $mode_variable;
		$this->variables_functions_relation = $variables_functions_relation;
		$pregs = array(
				"/<\?php[\s]*[\n]*\?>/is",
				'/\{code\}/is',
				'/\{\/code\}/is',				
//				'/<NS([0-9a-zA-Z]*)>/',
				'/\{if[\s]*(.*?)\}/ise',
				'/\{else[\s]*if[\s]*(.*?)\}/ise',
				'/\{else\}/is',								//else放在变量前面，防止把{else}匹配成变量
				'/\{foreach[\s]*(.*?)\}/ise',
				'/([^{]?)\{\/if\}([^}]?)/is',
				'/\{\/foreach\}/is',
				"/\{[\s]*([\w\[\]\'\"\x7f-\xff\+\-\*\/\%\$#]+)[\s]*\}/ise",
				"/([\\$|\\#][a-zA-Z_]+[0-9_]*)([\s|,|\+|'|\=|;|\\.)])/ise",
				'/($\s*$)|(^\s*^)/m',

		);
		$pregs_replace = array(
				'',
				'<?php ',
				'?>',				
//				'cell_'.$cell_id.'_\\1',
				"\$this->format_if('\${0}','\${1}')",
				"\$this->format_elseif('\${0}','\${1}')",
				"<?php } else { ?>",
				"\$this->format_loop('\${0}','\${1}')",
				"\${1}<?php } ?>\${2}",
				"<?php } }?>",
				"\$this->format_variable('\${0}','\${1}')",
				"\$this->format_variable1('\${1}','\${2}')",
				'',
		);
		$this->parsed_content = $this->pre_evaluate();
		$content = preg_replace($pregs, $pregs_replace, $content);//匹配样式中的变量
		if ($mode_info['mode_type'] == 1)
		{
			$str = '<?php if(is_array($m2o[\'data\'])){foreach ($m2o[\'data\'] as $data_k => $data_v) { $k = $data_k;?>';
			$content = $str . $content . '<?php  }} ?>';
		}		
		$this->parsed_content .= $content;
// 		echo $content;
		return $content;
	}
	
	//解析css,js
	public function parse_cssjs( $content, $variable = '')
	{
		if (!$content) 
		{
			return false;
		}	
		$this->mode_variables = $variable;
		$pregs = array(
		 	'/\{if[\s]* #([a-z_\x7f-\xff][\w\[\]\'\"\x7f-\xff]*)\}/is',
		 	'/\{else[\s]*if[\s]* #([a-z_\x7f-\xff][\w\[\]\'\"\x7f-\xff]*)\}/is',
		 	'/\{else\}/is',	
		 	'/\{\/if\}/is',
		 	"/\{#([a-z_\x7f-\xff][\w\[\]\'\"\x7f-\xff]*)\}/is",		
		 );
		 $pregs_replace = array(
		 	"<?php if($\\1) { ?>",
		 	"<?php }elseif($\\1){ ?>",
		 	"<?php }else { ?>",
			"<?php } ?>",
		 	"<?php echo $\\1;?>",
		);
		$this->parsed_content = $this->pre_evaluate();
		$content = preg_replace($pregs, $pregs_replace, $content);	
		$this->parsed_content .= $content;
		return $content;
	}
	
	public function pre_evaluate()
	{
		$content = '<?php ';
//		$content .= "include_once (CUR_CONF_PATH.'lib/web_functions.php');";
		if (is_array($this->mode_variables) && count($this->mode_variables) > 0 )
		{
			foreach($this->mode_variables as $k => $v)
			{
                /**
                 * $content .=  '$' . $k . '=\''.str_replace("'", '&#039',$v).'\';';
                 * @describe这个地方原来上上面的替换方式，但在魔力视图会看到被转义，所以做了这个处理
                 * @auth dong
                 * @updatedate 20150416
                 */
                $content .=  '$' . $k . '=\''.str_replace("'", '"',$v).'\';';
			}
		}
		$content .= ' ?>';
		return $content;
	}	
	function format_loop($match,$variable)
	{
		$this->curr_variable = substr($variable,1);
		$this->curr_relation = $this->relation_map[substr($variable,1)];
		$loopvar = $this->create_loopvar();
		$variable = $this->look_relation();
		if(!$variable || !$loopvar){
			$str = '<?php if(0){{ ?>';			//防止变量值不存在时报错, {{和结束}}匹配
		}else {
			$loopkey = str_repeat('k',($this->look_loop_nums() + 1));
			$str =  '<?php if(is_array('.$variable.')){foreach ('.$variable. ' as '.$loopvar.'_k => '.$loopvar.'_v) { $'.$loopkey.' = '.$loopvar.'_k;?>';
		}
		return $str;
	}
	function format_variable($match, $con)
	{
		$con = $this->parse_variable($con);	
		return '<?php echo '.$con.';?>';
	}
	function format_variable1($var, $char)
	{
		$variable = $this->get_real_variable($var);
		return $variable . $char;
	}
	public function parse_variable($con)
	{
		if(!$con){
			return fasle;
		}
		$pregs = array(
			'/([\$|#][a-z_\x7f-\xff][\w\[\]\'\"\x7f-\xff]*)/ie'
		);
		$pregs_replace = array(
			"\$this->get_variable('\${0}','\${1}')",
		);
		$con = preg_replace($pregs, $pregs_replace, $con);
		return $con;			
	}	
	function get_variable($match, $variable)
	{
		$variable = $this->get_real_variable($variable);
		return $variable;
	}	
	public function format_if($match,$con)
	{
		$con = $this->parse_variable($con);	
		return '<?php if(' . $con . '){ ?>';
	}
	public function format_elseif($match,$con)
	{	
		$con = $this->parse_variable($con);
		return '<?php }elseif(' . $con . '){ ?>';	
	}
	public function get_real_variable($variable)
	{
		$var = (strpos($variable,'$')===false && strpos($variable,'#')===false) ? $variable : substr($variable,1);
		$this->curr_variable = $var;
		$this->curr_relation = $this->relation_map[$var];
		if(!$this->is_identifier($variable)){
			return '000';
		}
		$str = '';
		if(strpos($variable,'$') === false)   //样式变量
		{
			$variable = $this->get_mode_variable();
		}
		else 								 //样式中数据变量
		{
			$variable = $this->look_relation();
		}
		return $variable;
	}	
	public function look_relation()
	{
		$variable_name = '$'.$this->curr_variable;
		if($this->curr_relation)
		{
			$relation = explode('>', substr($this->data_variable,1).'>'.$this->curr_relation);
			$variable_name = $this->data_variable;
			$i = 1;
			$len = count($relation);
			$indexs = array_keys($relation, '0');
			if($indexs)				//有0的情况
			{
				$i = max($indexs) - 1;
				while ($relation[$i] == '0'){
					$i--;
				}
				$variable_name = '$'.$relation[$i++];
				while ( $i < $len && $relation[$i] == '0' ){
					$variable_name .= '_v';
					$i++;
				}
			}
			while( $i < $len ){
				$variable_name .= '[\''.$relation[$i].'\']';
				$i++;
			}
		}
		switch($this->curr_variable)
		{
			case 'm2o_page_link':
				$variable_name = '$m2o[\'data\']';
				$func = $this->variables_functions_relation[$this->curr_variable] ? $this->variables_functions_relation[$this->curr_variable] : array('function' => 'web_build_page_link','param' => '');
				break;
			default:
				$func = $this->variables_functions_relation[$this->curr_variable];
				break;
		}
		if($func['function'])
		{
			$variable_name = $func['param'] ? $variable_name . ',' .$func['param'] : $variable_name;
			$variable_name = $func['function'] . '(' . $variable_name . ')';
		}
		return $variable_name;
	}
	public function look_loop_nums()
	{
		if($this->curr_relation)
		{
			$relation = explode('>', substr($this->data_variable,1).'>'.$this->curr_relation);
			$indexs = array_keys($relation, '0');
			return count($indexs);
		}
	}
	function create_loopvar()
	{
		if($this->curr_relation){
			$relation = explode('>', $this->curr_relation);
			$len = count($relation);
			$i = $len;
			while ($relation[--$i] == '0'){}		//倒序查找最后一个非0的
			$loopvar = '$'.$relation[$i];
			while (++$i < $len){
				$loopvar .= '_v';
			}
			return $loopvar;
		}
		return '$' . $this->curr_variable;
	}
	public function is_identifier($str)
	{
		return true;
	}
	public function get_mode_variable()
	{
		return "'" . str_replace("'", '&#039',$this->mode_variables[$this->curr_variable]) . "'";
	}
	public function built_mode_cache($filename,$filepath = MODE_CACHE_DIR)
	{
		if(!is_dir($filepath))
		{
			mkdir($filepath, 0777, true);
		}
		$return = file_put_contents($filepath.$filename, $this->parsed_content);
		return $return ? $return : false;		
	}
	public function built_cell_html($data = '', $cache_file = '', $mode_info = '', $need_page_info='', $page_site_info='', $page_column_info='', $page_client_info='', $page_special_info = '',$page_special_column_info='', $data_input_param = '', $force = '', $filepath = MODE_CACHE_DIR)
	{ 
		if ($mode_info['need_pages'])
		{
			$m2o['data'] = $data['data'];
			$_REQUEST['__page_total'] = $data['total'];
			$_REQUEST['__page_count'] = $data_input_param['need_count'];
		}
		else
		{
			$m2o['data'] = $data;
		}
		if(!file_exists($filepath . $cache_file) || $force)
		{
			$this->built_mode_cache($cache_file, $filepath);
		}
		$GLOBALS['need_page_info'] = $need_page_info;
		$__info['site'] = $page_site_info;
		$__info['column'] = $page_column_info;	
		$__info['client'] = $page_client_info;
		$__info['special'] = $page_special_info;
		$__info['special_column'] = $page_special_column_info;
		$__configs = $this->settings;
		include_once (CUR_CONF_PATH.'lib/m2o/lib/web_functions.php');
		if(php_check_syntax($filepath . $cache_file,$error))
		{
			ob_start();
			include $filepath . $cache_file;
			$html = ob_get_contents();
		}
		else
		{	
			$html = $error;
		}
		ob_clean();
		return $html;
	}
	public function built_cssjs($cache_file, $force = false, $filepath = CSS_CACHE_DIR)
	{
		if(!file_exists($filepath . $cache_file) || $force)
		{
			$this->built_mode_cache($cache_file, $filepath);
		}
		if (php_check_syntax($filepath . $cache_file,$error))
		{
			ob_start();
			include $filepath . $cache_file;
			$cssjs = ob_get_contents();
		}
		else
		{	
			$cssjs = $error;
		}
		ob_clean();
		return $cssjs;			
	}
}
// $obj = new Parse();
// $obj->parse_template();
?>