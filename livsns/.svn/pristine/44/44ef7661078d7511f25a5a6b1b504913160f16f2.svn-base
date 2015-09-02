<?php
require('global.php');
define('MOD_UNIQUEID', 'magic_view');
class MagicViewUpdateApi extends adminBase
{
    public function __construct() {
        parent::__construct();
    }
    
    public function cellUpdate() {
    	
        //快速专题、区块时不加权限      
        if (($this->input['bs'] == 'k') || ($this->input['bs'] == 'q')) {  
            
        }
        else {        
        	if($this->user['group_type'] > MAX_ADMIN_TYPE)
    		{
    			$action = $this->user['prms']['app_prms'][MOD_UNIQUEID]['action'];
                $action = empty($action) ? array() : $action;
    			if(!in_array('manage',$action))
    			{
    				$this->errorOutput("NO_PRIVILEGE");
    			}
    		}
        }
		
        if(!$this->input['arData']) {
            $this->errorOutput('data不能为空');
        }
        $arData         = $this->input['arData'];
        $blPreset       = $this->input['blPreset'];
        $intSiteId      = intval($arData[0]['site_id']);
        $intPageId      = intval($arData[0]['page_id']);
        $intPageDataId  = intval($arData[0]['page_data_id']);
        $intContentType = intval($arData[0]['content_type']);
        if (!class_exists('Magic')) {
            include(CUR_CONF_PATH . 'lib/magic.class.php');
        }
        $objMagic = new Magic($intSiteId, $intPageId, $intPageDataId, $intContentType, '', $blPreset);
        $arData = $objMagic->cellUpdate($arData);       
        if (!$arData) {
            $this->errorOutput('编辑失败');
        }
        if (is_array($arData) && count($arData) > 0){
            foreach ($arData as $k => $v) {
                $this->addItem($v);
            }
        }
        $this->output();        
    }
    
    /**
     * 魔力视图单元撤消
     */
    public function cellCancle() {
        //快速专题、区块时不加权限      
        if (($this->input['bs'] == 'k') || ($this->input['bs'] == 'q')) {  
            
        }
        else {
        	if($this->user['group_type'] > MAX_ADMIN_TYPE)
    		{
    			$action = $this->user['prms']['app_prms'][MOD_UNIQUEID]['action'];
                $action = empty($action) ? array() : $action;
    			if(!in_array('manage',$action))
    			{
    				$this->errorOutput("NO_PRIVILEGE");
    			}
    		}
        }
		
        $strIds = $this->input['id'];
        if(!$strIds) {
            $this->errorOutput("请选择需要撤消的单元");
        }
        if (!class_exists('Magic')) {
            include(CUR_CONF_PATH . 'lib/magic.class.php');
        }
        $objMagic = new Magic();
        $arData = $objMagic->cellCancle($strIds);        
        if (!$arData) {
            $this->errorOutput('撤销失败');
        }
        $this->addItem($arData);     
        $this->output();
    }
    
    /**
     * 上传图标
     */
    public function uploadIcon()
    {
        if ($_FILES['Filedata']) {
            if (!$_FILES['Filedata']['error']) {
                $info = array();
                $typetmp            = explode('.',$_FILES['Filedata']['name']);
                $filetype           = strtolower($typetmp[count($typetmp)-1]);
                $info['type']       = $filetype;
                if (!in_array($info['type'], array('jpg', 'gif', 'png', 'jpeg', 'bmp'))) {
                    $this->errorOutput('文件格式不正确');
                }
                $info['user_id']    = intval($this->user['user_id']);
                $info['user_name']  = urldecode($this->user['user_name']);
                $info['filepath']   = date('Ym') . '/';
                $info['name']       = urldecode($_FILES['Filedata']['name']);
                $tmp_filename       = date('YmdHis') . hg_generate_user_salt(4);
                $info['filename']   = $tmp_filename . '.' . $info['type'];
                $path               = CUR_CONF_PATH . 'data/icon/' . $info['filepath'];
                if (!hg_mkdir($path)) {
                    $this->errorOutput('目录创建失败');
                }
                if (!move_uploaded_file($_FILES["Filedata"]["tmp_name"], $path . $info['filename'])) {
                    $this->errorOutput('移动文件失败');
                }
                else {  
                    $imginfo = getimagesize($path.$info['filename']);
                    $info['imgwidth']     = $imginfo[0];
                    $info['imgheight']    = $imginfo[1];   
                    $info['filesize']     = $_FILES["Filedata"]["size"];
                    $info['create_time']  = TIMENOW;
                    $info['ip']           = hg_getip();
                    $this->db->insert_data($info, 'icons');
                    $info['real_url']     = ICON_URL . $info['filepath'] . $info['filename'];
                    $info['url']          = '<MATEURL>' . $info['filepath'] . $info['filename'];
                    $info['id'] = $this->db->insert_id();
                    $this->addItem($info);
                    $this->output();
                }                                                                   
            }
            else {
                $this->errorOutput('上传失败');
            }           
        }   
    }   

    public function uploadIndexPic() {
        if ($_FILES['Filedata']) {
            if (!$_FILES['Filedata']['error']) {
                if (!class_exists('material')) {
                    include(ROOT_PATH . 'lib/class/material.class.php');
                }
                $objMaterial = new material();
                $response = $objMaterial->addMaterial($_FILES);
                if (!empty($response)) {
                    $ret = array(
                        'host'  => $response['host'],
                        'dir'   => $response['dir'],
                        'filepath' => $response['filepath'],
                        'filename' => $response['filename'],
                    );
                }
                $this->addItem($ret);
                $this->output();                
            }
        }
        $this->errorOutput('上传失败');
    }
    
    public function cellStatic() {
        $intCellId = $this->input['id'];
        if (!$intCellId) {
            $this->errorOutput('NO ID');
        }
        $arInfo = array(
            'cell_type'     =>  3,
            'static_html'   =>  addslashes($this->input['static_html']),
        );
        $condition = ' id = ' . $intCellId;
        $this->db->update_data($arInfo,'cell', $condition);
        $this->addItem($this->input['static_html']);
        $this->output();
    }    

    public function updataBlockAndData() {
        $intCellId = intval($this->input['intCellId']);
        $arBlockInfo = $this->input['arBlockInfo'];
        if (!$intCellId && empty($arBlockInfo)) {
            $this->errorOutput('参数不完整,编辑失败');
        }
        $intDataNum = count($arBlockInfo['content']);
        //更改单元的数据源参数
        if (!class_exists('cell')) {
            include (CUR_CONF_PATH . 'lib/cell.class.php');
        }
        $objCell = new cell();
        $arCellInfo = $objCell->detail(' AND id = ' . $intCellId);
        $arParamAsso = $arCellInfo['param_asso'];
        $arParamAsso['input_param']['count'] = $intDataNum;
        $objCell->update(array('param_asso' => serialize($arParamAsso)), $intCellId); 
        //更改区块和数据
        $arBlockInfo['block']['line_num'] = $intDataNum;
        if (!class_exists('block')) {
            include (ROOT_PATH  . 'lib/class/block.class.php');
        }
        $objBlock = new block(); 
        $objBlock->updateBlockAndData($arBlockInfo);
        //重新处理该单元信息
        if (!class_exists('Magic')) {
            include(CUR_CONF_PATH . 'lib/magic.class.php');
        }
        $objMagic = new Magic();        
        $arCellInfo = $objMagic->cellProcess($arCellInfo);
        $this->addItem($arCellInfo);
        $this->output();                                  
    }

    //编辑单元数据
    public function cellDataUpdate() {
        $intCellId = intval($this->input['intCellId']);
        $intContentId = $this->input['intContentId'];
        if (!$intCellId || !$intContentId) {
            $this->errorOutput('NO ID');    
        }
        if (!empty($this->input['arIndexPic'])) {
            $this->input['arIndexPic']  = array(
                'host'  => $this->input['arIndexPic']['host'],
                'dir'  => $this->input['arIndexPic']['dir'],
                'filepath'  => $this->input['arIndexPic']['filepath'],
                'filename'  => $this->input['arIndexPic']['filename'],
            );
        } 
        $arData = array(
            'title'     => $this->input['strTitle'],
            'brief'      => $this->input['strBrief'],
            'content_url'   => $this->input['strContentUrl'],
            'indexpic'  => $this->input['arIndexPic'] ? serialize($this->input['arIndexPic']) : '',
       );
       if (!class_exists('cell')) {
            include (CUR_CONF_PATH . 'lib/cell.class.php');
        }
        $objCell = new cell();
        $arContent = $objCell->detail(' AND cell_id = ' . $intCellId . ' AND content_id = ' . $intContentId, 'cell_data');
        if (empty($arContent)) {
            $arData['cell_id'] = $intCellId;
            $arData['content_id'] = $intContentId;
            $this->db->insert_data($arData,'cell_data');
        } 
        else {
            $this->db->update_data($arData, 'cell_data', 'cell_id = ' . $intCellId . ' AND content_id = ' . $intContentId );
        }
        $arCellInfo = $objCell->detail(' AND id = ' . $intCellId);
        //删除单元内容缓存
        $objCell->delete_cell_data_cache($intCellId);
        //重新处理该单元信息
        if (!class_exists('Magic')) {
            include(CUR_CONF_PATH . 'lib/magic.class.php');
        }
        $objMagic = new Magic();        
        $arCellInfo = $objMagic->cellProcess($arCellInfo, true);
        $this->addItem($arCellInfo);
        $this->output();                 
    }
        
    public function unknow() {
        $this->errorOutput('unknow method');    
    }
    
    public function __destruct() {
         parent::__destruct();
    }
}   
$out    = new MagicViewUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'unknow';
}
$out->$action();
?>