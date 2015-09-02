<?php
define('WITH_DB', true);
define('ROOT_DIR', '../');
define('SCRIPT_NAME', 'plug');
require_once('../global.php');
require_once('./http.php');
class plug extends uiBaseFrm
{
	private $http;
	public function __construct()
	{
		parent::__construct();
	}
	
	public function show() {}
	
    //获取样式图标
	public function get_icons() {
		if ($this->settings['App_publishsys']) {
            $postFields = array(
                'a'        => 'getAllIcons', 
                'offset'   => $this->input['offset'] ? $this->input['offset'] : 0,
                'count'    => $this->input['count'] ? $this->input['count'] : 20,
                'request'  => 'admin/magic.php',
            );		    
			$this->http = new Http($this->settings['App_publishsys']['host'], $this->settings['App_publishsys']['dir']);
            $hgDataReturn = $this->http->http($postFields);
			echo json_encode($hgDataReturn[0]);
		}
		else {
			$this->ReportError('此系统未安装');
		}
	}
	
    //上传样式图标
	public function upload_icon() {
		if (!$_FILES) {
			$this->ReportError('请选择文件');
		}
		if ( $this->settings['App_publishsys'] ) {
		    $postFields = array(
		          'a'         => 'uploadIcon',
		          'file'      => $_FILES,
		          'request'   => 'admin/magic_update.php',
            );
			$this->http = new Http($this->settings['App_publishsys']['host'], $this->settings['App_publishsys']['dir']);
            $hgDataReturn = $this->http->http($postFields);
			echo json_encode($hgDataReturn);					
		}
        else {
            $this->ReportError('此系统未安装');
        }        
	}
    
    public function uploadIndexPic() {
        if (!$_FILES) {
            $this->ReportError('请选择文件');
        }
        if ($this->settings['App_publishsys']) {
            $postFields = array(
                'a'     => 'uploadIndexPic',
                'file'  => $_FILES,
                'request' => 'admin/magic_update.php',
            );
            $objHttp = new Http($this->settings['App_publishsys']['host'], $this->settings['App_publishsys']['dir']);
            $hgDataReturn = $objHttp->http($postFields);
            echo json_encode($hgDataReturn);
        } else {
            $this->ReportError('此系统未安装');
        }
    }
	
	/**
	 * 取栏目的路径
	 */
	public function get_column_path() {
		$column_id = $this->input['column_id'];		
		if ( !$column_id ) {
			$this->ReportError('ID为空');
		}
		if (!class_exists('column')) {
		    include(ROOT_DIR . 'lib/class/column.class.php');
		}
		$column = new column();
		$ret['selected_ids'] = $column_id ? $column_id : '';
		$ret['selected_items'] = $column->get_selected_column_path($ret['selected_ids']);	
		if (is_array($ret['selected_items'])) {
			foreach ($ret['selected_items'] as $index => $item) {
				$hg_print_selected[$index] = array();
				$current = &$hg_print_selected[$index];
				$current['showName'] = '';
				foreach ($item as $sub_item) {
					if ($sub_item['is_auth']) {
						$current['is_auth'] = 1;
					}
					$current['id'] = $sub_item['id'];
					$current['name'] = $sub_item['name'];
					if ($sub_item['fid'] == 0) {
						$current['showName'] .=  $sub_item['name'] . ' > ';
					}
					else {
						$current['showName'] .= $sub_item['name'] . ' > ';
					}
				}
				$current['showName'] = substr($current['showName'], 0, -3);
				$selected_names[] = $current['name'];
			}
		}
		$ret['selected_items'] = $hg_print_selected;
		$ret['selected_names'] = isset($selected_names) ? implode(',', $selected_names) : '';
		echo json_encode($ret);exit();				
	}
	
    //取专题栏目
	public function get_special_column() {
		$special_id = $this->input['special_id'];
		if (!$special_id) {
			$this->ReportError('NO SPECIAL_ID');
		}
		if (!class_exists('special')) {
		    include(ROOT_DIR . 'lib/class/special.class.php');
		}
		$special = new special();	
		$ret = $special->get_special_column_new($special_id);
		$ret = $ret ? $ret : array();
        if (is_array($ret) && count($ret) > 0) {
            $newRet = array();
            foreach ($ret as $k => $v) {
                $newRet[$v['id']] = $v;
            }
        }
		echo json_encode($newRet);exit;		
	}
	
	/**
	 * 取专题栏目的路径
	 */
	public function get_special_column_path() {
		$column_id = $this->input['column_id'];		
		if ( !$column_id ) {
			$this->ReportError('ID为空');
		}
		if (!class_exists('special')) {
		    include(ROOT_DIR . 'lib/class/special.class.php');
		}
		$special = new special();	
		$ret['selected_ids'] = $column_id ? $column_id : '';
		$ret['selected_items'] = $special->get_special_column_byid($ret['selected_ids']);	
		if (is_array($ret['selected_items'])) {
			foreach ($ret['selected_items'] as $index => $item) {
				$hg_print_selected[$index] = array();
				$current = &$hg_print_selected[$index];
				$current['showName'] = $item['name'];
				$current['id'] = $item['id'];
				$current['name'] = $item['name'];
				$selected_names[] = $current['name'];
			}
		}
		$ret['selected_items'] = $hg_print_selected;
		$ret['selected_names'] = isset($selected_names) ? implode(',', $selected_names) : '';	
		echo json_encode($ret);exit();						
	}
	
    //魔力视图中切换栏目
	public function change_column() {
		if (!$this->input['site_id']) {
			$this->ReportError('站点为空');
		}
		if ($this->settings['App_publishsys']) {
		    $postFields = array(
                  'a'             => 'changeColumn',
		          'site_id'       => intval($this->input['site_id']),
		          'page_id'       => intval($this->input['page_id']),
		          'page_data_id'  => intval($this->input['page_data_id']),
		          'request'       => 'admin/magic.php',
            );
			$this->http = new Http($this->settings['App_publishsys']['host'], $this->settings['App_publishsys']['dir']);
            $hgDataReturn = $this->http->http($postFields);
			echo json_encode($hgDataReturn);					
		}
        else {
            $this->ReportError('此系统未安装');
        }         		
	}
	
	public function cell_static()
	{
		if (!$this->input['id']) {
			$this->ReportError('NO ID');
		}
		if ($this->settings['App_publishsys']) {
            $postFields = array(
                  'a'            => 'cellStatic',
                  'id'           => intval($this->input['id']),
                  'is_static'    => $this->input['is_static'],
                  'static_html'  => $this->input['static_html'],
                  'request'      => 'admin/magic_update.php',
            );
            $this->http = new Http($this->settings['App_publishsys']['host'], $this->settings['App_publishsys']['dir']);
            $hgDataReturn = $this->http->http($postFields);		    
			echo $hgDataReturn[0];
		}
        else {
            $this->ReportError('此系统未安装!');
        }         			
	}
    
    public function getBlockData() {
        $intBlockId = intval($this->input['block_id']);
        if (!$intBlockId) {
            $this->ReportError('NO BLOCK ID');
        }
        if ($this->settings['App_block']) {
            $postFields = array(
                'id'        => $intBlockId,
                'a'         => 'show',
                'request'   => 'admin/block_set.php',
            );
            $this->http = new Http($this->settings['App_block']['host'], $this->settings['App_block']['dir']);
            $response = $this->http->http($postFields);
            echo json_encode($response);
        }
        else {
            $this->ReportError('区块未安装!');
        }        
    }
    
    //编辑区块信息和区块数据
    public function updataBlockAndData() {
        $intCellId = intval($this->input['cell_id']);
        $arBlockInfo = $this->input['data'];
        if (!$arBlockInfo) {
            $this->ReportError('data不能为空');
        }
        $arBlockInfo = json_decode($arBlockInfo, 1);
        if (!$arBlockInfo) {
            $this->ReportError('非法字符,decode失败');
        }
        $this->http = new Http($this->settings['App_publishsys']['host'], $this->settings['App_publishsys']['dir']);
        $postFields = array(
            'intCellId'   => $this->input['cell_id'],
            'arBlockInfo' => $arBlockInfo,
            'a'           => 'updataBlockAndData',
            'request'     => 'admin/magic_update.php',
        );
        $reponse = $this->http->http($postFields);
        echo json_encode($reponse);        
    }
    
    public function updateBlockIndexPic() {
        $this->http = new Http($this->settings['App_block']['host'], $this->settings['App_block']['dir']);
        $postFields = array(
            'block_id' => intval($this->input['block_id']),
            'site_id'  => intval($this->input['site_id']),
            'page_id'  => intval($this->input['page_id']),
            'page_data_id' => intval($this->input['page_data_id']),
            'content_type' => intval($this->input['content_type']),
            'client_type'  =>intval($this->input['client_type']),
            'indexpic' => $this->input['indexpic'],
            'a' => 'update_indexpic',
            'request' => 'admin/block_update.php',
        );
        $reponse = $this->http->http($postFields);
        echo json_encode($reponse); 
    }
    
    public function getContent()
    {
        $intPP     = $this->input['page'] ? intval($this->input['page']) : 1;
        $intCount  = $this->input['count'] ? intval($this->input['count']) : 20;
        $intOffSet = intval(($intPP - 1) * $intCount);   
        $postFields = array(
            'offset'            => $intOffSet,
            'count'             => $intCount,
            'client_type'       =>  '2',
            'need_count'        => '1',
            'a'                 => 'get_content',
            'request'           => 'content.php',
        );
        if ($this->input['site_id']) {
            $postFields['site_id'] = intval($this->input['site_id']);
        }
        if ($this->input['info']) {
            $info = array();
            foreach($this->input['info'] as $k=>$v) {
                $info[$v['name']] = $v['value'];
            }
        }
        if ($info['special_modules']) {
            $postFields['bundle_id'] = $info['special_modules'];
        }
        if ($info['special_date_search']) {
            $postFields['date_search'] = $info['special_date_search'];
        }
        if ($info['k']) {
            $postFields['k'] = $info['k'];
        }
        if ($info['start_time']) {
            $postFields['starttime'] = $info['start_time'];
        }
        if ($info['end_time']) {
            $postFields['endtime'] = $info['end_time'];
        }
        if (isset($info['start_weight']) && intval($info['start_weight'])>=0) {
            $postFields['start_weight'] = $info['start_weight'];
        }
        if (isset($info['end_weight']) && intval($info['end_weight'])>=0) {
            $postFields['end_weight'] = $info['end_weight'];
        }
        $this->http = new Http($this->settings['App_publishcontent']['host'], $this->settings['App_publishcontent']['dir']);
        $re = $this->http->http($postFields);
        $postFields = array(
            'a'         => 'get_pub_content_type',
            'request'   => 'admin/content.php',
        );
        $return = $this->http->http($postFields);
        $return = $return[0];
        if (is_array($return)) {
            foreach ($return as $k => $v) {
                $bundles[$v['bundle']] = $v['name'];
            }
        }
        //$columns = $this->get_column();
        if (is_array($re['data'])) {
            foreach ($re['data'] as $k=>$v) {
                // $co_names = array();
                // if ($v['column_id']) {
                    // $co_arr = explode(" ",$v['column_id']);
                    // foreach ($co_arr as $ke=>$va) {
                        // $co_names[] = $columns[$va];
                    // }
                // }
                // $v['column_name'] = implode(" ",$co_names);
                $v['module_name'] = $bundles[$v['bundle_id']];
                $v['pic'] = json_encode($v['indexpic']);
                $ret[] = $v;
            }
        }
        $total_num =$re['total'];   //总的记录数
        if (intval($total_num % $intCount) == 0) {
            $return['total_page'] = intval($total_num/$intCount);
        }
        else {
            $return['total_page'] = intval($total_num/$intCount) + 1;
        }
        $return['total_num'] = $total_num;  //总的记录数
        $return['page_num'] = $intCount;    //每页显示的个数
        $return['current_page'] = $intPP;  //当前页码
        $retu['info'] = $ret;
        $retu['page_info'] = $return;
        echo json_encode($retu);
    }    
    
    public function getTemplateFile() {
        $this->http = new Http($this->settings['App_publishsys']['host'], $this->settings['App_publishsys']['dir']);
        $postFields = array(
            'site_id' 		=> $this->input['site_id'],
            'template_sign' => $this->input['template_sign'],
            'a' 			=> 'get_template_file',
            'request' 		=> 'admin/template.php',
        );
        $reponse = $this->http->http($postFields);
        echo json_encode($reponse); 
    }
    
   public function getTemplateFileInfo() {
        $this->http = new Http($this->settings['App_publishsys']['host'], $this->settings['App_publishsys']['dir']);
        $postFields = array(
            'template_id' 	=> intval($this->input['template_id']),
            'dir' 			=> $this->input['dir'],
            'a'		 		=> 'get_template_file_info',
            'request' 		=> 'admin/template.php',
        );
        $reponse = $this->http->http($postFields);
        echo json_encode($reponse); 
    }
    
    public function updateTemplateFileInfo() {
        $this->http = new Http($this->settings['App_publishsys']['host'], $this->settings['App_publishsys']['dir']);
        $postFields = array(
            'template_id' 	=> intval($this->input['template_id']),
            'dir' 			=> $this->input['dir'],
            'file_info' 	=> $this->input['file_info'],
            'a' 			=> 'update_template_file_info',
            'request' 		=> 'admin/template.php',
        );
        $reponse = $this->http->http($postFields);
        echo json_encode($reponse); 
    }
    
    public function getDatasourceRecord() {
        $this->http = new Http($this->settings['App_publishsys']['host'], $this->settings['App_publishsys']['dir']);
        $postFields = array(
            'id'            => intval($this->input['id']),
            'a'             => 'get_datasource_data',
            'request'       => 'admin/data_source.php',
        );
        $reponse = $this->http->http($postFields);
        $reponse = $reponse[0];
        $ret = array(
            'data'  => $reponse,
            'str_data' => print_r($reponse, 1),
        );
        echo json_encode($ret);         
    }
    
    public function updateTemplatePic() {
        $this->http = new Http($this->settings['App_publishsys']['host'], $this->settings['App_publishsys']['dir']);
        $postFields = array(
            'template_id' 	=> intval($this->input['template_id']),
            'dir' 			=> $this->input['dir'],
            'file_data' 	=> $this->input['file_data'],
            'a' 			=> 'update_template_pic',
            'request' 		=> 'admin/template.php',
        );
        $reponse = $this->http->http($postFields);
        echo json_encode($reponse); 
    }
    
     public function getTemplate() {
        $this->http = new Http($this->settings['App_publishsys']['host'], $this->settings['App_publishsys']['dir']);
        $postFields = array(
            'template_id' 	=> intval($this->input['template_id']),
            'a' 			=> 'get_template',
            'request' 		=> 'admin/template.php',
        );
        $reponse = $this->http->http($postFields);
        echo json_encode($reponse); 
    }
    
     public function checkTemplate() {
        $this->http = new Http($this->settings['App_publishsys']['host'], $this->settings['App_publishsys']['dir']);
        $postFields = array(
            'template_id' 	=> intval($this->input['template_id']),
            'content' 		=> $this->input['content'],
            'a' 			=> 'check_template',
            'request' 		=> 'admin/template_update.php',
        );
        $reponse = $this->http->http($postFields);
        echo json_encode($reponse); 
    }
    
    public function updateTemplate() {
        $this->http = new Http($this->settings['App_publishsys']['host'], $this->settings['App_publishsys']['dir']);
        $postFields = array(
            'template_id' 	=> intval($this->input['template_id']),
            'content' 		=> $this->input['content'],
            'a' 			=> 'update_template',
            'request' 		=> 'admin/template_update.php',
        );
        $reponse = $this->http->http($postFields);
        echo json_encode($reponse); 
    }
    
    public function __destruct() {
        parent::__destruct();
    }    

}
include (ROOT_PATH . 'lib/exec.php');
?>