<?php
define('ROOT_DIR', '../');
define('SCRIPT_NAME', 'magic');
require_once('../global.php');
require_once('./http.php');
class magic extends uiBaseFrm
{
    private $http;
    public function __construct() {
        parent::__construct();
        $this->http = new Http($this->settings['App_publishsys']['host'], $this->settings['App_publishsys']['dir']);
    }

    public function show(){}
    
    public function searchCell() {
        $postFields = array(
            'a'             => 'searchCell',
            'intSiteId'     => $this->input['site_id'],
            'intPageId'     => intval($this->input['page_id']),
            'intPageDataId' => intval($this->input['page_data_id']),
            'intContentType'=> intval($this->input['content_type']),
            'intTemplateId' => intval($this->input['template_id']),
            'intLayoutId'   => intval($this->input['layout_id']),
        );
        $blPreset = $this->input['ispreset'];
        $strBs = $this->input['bs'];
        $blPreset = ($strBs == 'p' || $strBs == 'b' || ($blPreset && $blPreset != 'false')) ? 1 : 0;
        $postFields['blPreset'] = $blPreset;
        $postFields['request'] = 'admin/magic.php';
        $hgDataReturn = $this->http->http($postFields);
        echo json_encode($hgDataReturn);
    }

    public function preview() {
        $postFields = array(
            'a'             => 'preview',
            'intSiteId'     => $this->input['site_id'],
            'intPageId'     => intval($this->input['page_id']),
            'intPageDataId' => intval($this->input['page_data_id']),
            'intContentType'=> intval($this->input['content_type']),
            'intTemplateId' => intval($this->input['template_id']),
            'intLayoutId'   => intval($this->input['layout_id']),
        );
        $blPreset = $this->input['ispreset'];
        $strBs = $this->input['bs'];
        $blPreset = ($strBs == 'p' || $strBs == 'b' || ($blPreset && $blPreset != 'false')) ? 1 : 0;
        $postFields['blPreset'] = $blPreset;
        $postFields['request'] = 'admin/magic.php';
        $hgDataReturn = $this->http->http($postFields);        
        echo $hgDataReturn[0];
    }

    public function cellUpdate() {
        $arData = $this->input['data'];
        if (!$arData) {
            $this->ReportError('data不能为空');
        }
        $arData = json_decode($arData, 1);
        if (!$arData) {
            $this->ReportError('非法字符,decode失败');
        }
        $blPreset = $this->input['ispreset'];
        $strBs = $this->input['bs']; 
        $blPreset = ($strBs == 'p' || $strBs == 'b' || ($blPreset && $blPreset != 'false')) ? 1 : 0; 
        $postFields = array(
            'a'         => 'cellUpdate',
            'blPreset'  => $blPreset,
            'arData'    => $arData,
            'html'      => true,
            'request'   => 'admin/magic_update.php',
        );
        $hgDataReturn = $this->http->http($postFields);
        echo json_encode($hgDataReturn);
    }
    
    public function cellCancle() {
        $strIds = $this->input['id'];
        if (!$strIds) {
            $this->ReportError('请选择要恢复的单元');
        }
        $postFields = array(
            'a'         => 'cellCancle',
            'id'        => $strIds,
            'html'      => true,
            'request'   => 'admin/magic_update.php',
        );
        $hgDataReturn = $this->http->http($postFields);  
        echo json_encode($hgDataReturn);        
    }
    
    //单元数据编辑
    public function cellDataUpdate() {
        $intCellId = intval($this->input['cell_id']);
        $arData = $this->input['data'];
        $arData = json_decode($arData, 1);
        if (!$arData) {
            $this->ReportError('非法字符,decode失败');
        }
        $intContentId = intval($arData['content_id']);
        if (!$intCellId || !$intContentId) {
            $this->ReportError('NO CELLID');
        }                     
        $postFields = array(
            'a'             => 'cellDataUpdate',
            'intCellId'     => $intCellId,
            'intContentId'  => $intContentId,
            'strTitle'      => $arData['title'],
            'strBrief'      => $arData['brief'],
            'strContentUrl' => $arData['content_url'],
            'arIndexPic'    => $arData['indexpic'],   
            'return_data'   => 1,
            'request'       => 'admin/magic_update.php',        
        );
        $hgDataReturn = $this->http->http($postFields);  
        echo json_encode($hgDataReturn);          
    }
    
    
    //单元数据编辑预览、不保存数据信息
    public function cellPreview() {
        $intCellId = intval($this->input['cell_id']);
        if (!$intCellId) {
            $this->ReportError('NO CELLID');
        }
        $arData = $this->input['data'];
        $arData = json_decode($arData, 1);
        if (!$arData) {
            $this->ReportError('非法字符,decode失败');
        }        
        $postFields = array(
            'a'         => 'cellPreview',
            'intCellId' => $intCellId,
            'arData'    => $arData,
            'request'   => 'admin/magic.php',
        );
        $hgDataReturn = $this->http->http($postFields);  
        echo json_encode($hgDataReturn);        
    }
    
    public function __destruct() {
        parent::__destruct();
    }
}
include (ROOT_PATH . 'lib/exec.php');
?>