<?php

include_once (CUR_CONF_PATH . 'lib/common.php');

/**
 * 魔力视图核心类，单元处理、单元列表、页面预览、单元编辑撤销等方法
 *    
 * @copyright hogesoft
 * @author wangleyuan
 * 
 *    1、魔力视图  
 *      $objMagic = new Magic($siteId, $pageId, $pageDataId, $contentType);
 *    2、模板预设
 *      $objMagic = new Magic($siteId, 0, 0, 0, $templateId, true);
 *    3、布局 
 *      $objMagic = new Magic(0, 0, 0, 0, 0, 0, $layoutId);
 *    4、快速专题
 *      $objMagic = new Magic(0, 0, $specialId, $columnId, $tempalteId);
 *    
 */
class Magic extends InitFrm
{

    public $intSiteId       = '';           //站点id
    public $intPageId       = '';    //页面id
    public $intPageDataId   = '';       //栏目id
    public $intContentType  = '';          //内容类型
    public $intTemplateId   = '';       // 模板id
    public $blPreset        = '';   //是否模板预设
    public $strTemplateSign = '';          //模板标示 
    public $intClientType   = '';          //客户端 
    public $intLayoutId     = '';          //布局id
    public $arSiteInfo      = array();        //站点信息  
    public $arPageInfo      = array();        //页面信息
    public $strDefaultStyle = 'default';      //默认模板套系标示
    public $strCurrStyle    = '';           //当前使用模板套系标示
    public $blBuiltCell     = true;           //是否取数据生成单元html,替换css,js中变量
    public $strMaterialUrl  = '';             //素材地址
    public $strTemplate  = '';             //当前页面模板
    public $arCellList   = array();        //当前页面单元
    public $strLayoutIds = '';             //布局id
    public $arLayoutInfo = array();        //布局详细信息
    public $arNeedPageInfo          = array();      //分页信息
    public $arPageClientInfo        = array();      //当前页面客户端
    public $arPageSiteInfo          = array();      //当前页面站点
    public $arPageColumnInfo        = array();      //当前页面栏目
    public $arPageSpecialInfo       = array();      //专题时 专题信息
    public $arPageSpecialColumnInfo = array();      //专题时 专题栏目

    public $intUseGlobalTemplate = '';  //是否使用全局模板

    public function __construct($intSiteId = '', $intPageId = '', $intPageDataId = '', $intContentType = '', $intTempalteId = '', $blPreset = false, $intLayoutId = '', $intClientType = '')
    {
        parent::__construct();
        $this->intSiteId      = intval($intSiteId);
        $this->intPageId      = intval($intPageId);
        $this->intPageDataId  = intval($intPageDataId);
        $this->intContentType = intval($intContentType);
        $this->intTemplateId  = intval($intTempalteId);
        $this->blPreset       = $blPreset;
        $this->intLayoutId    = $intLayoutId;
        $this->intClientType  = $intClientType ? intval($intClientType) : 2;
        if (!$this->arSiteInfo)
        {
            include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
            $this->objPub     = new publishconfig();
            $this->arSiteInfo = $this->objPub->get_site_first('*', $this->intSiteId);
        }
        if ($this->intTemplateId)
        {    //模板id存在是专题或者模板预设
            $sql    = "SELECT site_id,sort_id,sign,template_style FROM " . DB_PREFIX . "templates WHERE id = '" . $this->intTemplateId . "'";
            $arInfo = $this->db->query_first($sql);
            if (!$arInfo)
            {
                return array('errmsg' => '模板' . $this->intTemplateId . '不存在');
            }
            $this->intSiteId       = $arInfo['site_id'];
            $this->strTemplateSign = $arInfo['sign'];
            if ((!$this->blPreset || ($this->blPreset === 'false')) && !$this->intPageId)
            {   //快速专题
                $this->arPageInfo = common::getPageBySign('special', 'id');
                $this->intPageId  = !empty($this->arPageInfo) ? $this->arPageInfo['id'] : 0;
            }
            if ($this->intContentType)
            {  //专题栏目页  同名list模板、若不存在则使用默认列表模板(moban_list)
                $this->strTemplateSign = $this->strTemplateSign . '_list';
                if (!$this->getTemplate())
                {
                    $this->strTemplateSign = $this->settings['default_special_column_tem'] ? $this->settings['default_special_column_tem'] : 'moban_list';
                    if (!$this->getTemplate())
                    {
                        return array('errmsg' => '模板' . $this->strTemplateSign . '不存在');
                    }
                }
            }
        }
        //设置分页、站点、页面、栏目等信息
        $this->setPaginationInfo();
    }

    /**
     * 魔力视图初始化方法
     * 
     * @return boolean $blBuiltCell 是否取数据生成单元html,替换css,js中变量 
     * @return str $strMaterialUrl  素材地址
     * @return array  当前页面模板、单元、布局和样式、数据源等信息
     */
    public function searchCell($blBuiltCell = true, $strMaterialUrl = '')
    {
        $this->blBuiltCell    = $blBuiltCell;
        $this->strMaterialUrl = $strMaterialUrl;
        //合并此栏目部署模板的单元
        $this->mergeCell();
        if (is_array($this->arCellList) && count($this->arCellList) > 0)
        {
            foreach ($this->arCellList as $k => $v)
            {
                $this->arCellList[$k] = $this->cellProcess($v, true);
            }
        }
        if (is_array($this->arLayoutInfo) && count($this->arLayoutInfo) > 0)
        {
            foreach ($this->arLayoutInfo as $k => $v)
            {
                if (is_array($v['cells']) && count($v['cells']) > 0)
                {
                    foreach ($v['cells'] as $kk => $vv)
                    {
                        $cell                                 = array();
                        $this->arLayoutInfo[$k]['cells'][$kk] = $this->cellProcess($vv, true);
                    }
                }
            }
        }
        //当前站点和内容类型下的样式和数据源列表
        $mode_sort   = $this->intTemplateId ? common::get_mode_sort() : common::get_mode_sort($this->intSiteId);
        $cell_mode   = common::get_mode($this->intSiteId, $this->intContentType);
        $data_source = common::get_data_source();
        //读取模板缓存
        //$template    = $this->strTemplate ? $this->strTemplate : $this->getTemplate();
        $template = $this->getTemplate();
        //将布局按顺序放入模板中
        if (is_array($this->arLayoutInfo) && count($this->arLayoutInfo))
        {
            foreach ($this->arLayoutInfo as $k => $v)
            {
                $template = str_ireplace('</head>', '<style type="text/css">' . $v['css'] . '</style></head>', $template);
                $template = preg_replace('/<div[^>]*id="m2o-main-box"[^>]*>/', '\\0' . $v['content'], $template);
            }
        }
        $ret = array(
            'template' => $template,
            'cell' => array_values($this->arCellList),
            'cell_mode' => $cell_mode,
            'data_source' => $data_source,
            'site_id' => intval($this->intSiteId),
            'page_id' => intval($this->intPageId),
            'page_data_id' => intval($this->intPageDataId),
            'content_type' => intval($this->intContentType),
            'cell_type' => $this->settings['cell_type'],
            'mode_sort' => $mode_sort,
            'client_type' => $this->intClientType,
            'template_id' => $this->intTemplateId,
            'template_sign' => $this->strTemplateSign,
            'ispreset' => $this->blPreset,
            'layouts' => $this->arLayoutInfo,
            'layout_ids' => $this->strLayoutIds,
        );
        return $ret;
    }

    /**
     * 魔力视图预览
     * 
     * @return str 当前页面的合并单元、css、js后的模板
     */
    public function preview()
    {
        //合并此栏目部署模板的单元
        $this->mergeCell();
        //读取模板
        $template = $this->strTemplate ? $this->strTemplate : $this->getTemplate();
        if (is_array($this->arCellList) && count($this->arCellList) > 0)
        {
            $cssStr      = '<style type="text/css">';
            $jsStr       = '<script type="text/javascript">';
            $cellPattern = $cellReplace = array();
            foreach ($this->arCellList as $k => $v)
            {
                $v             = $this->cellProcess($v, true);
                $cssStr .= $v['css'] . ' ';
                $jsStr .= $v['js'] . '';
                $cellPattern[] = '/<span[\s]+(?:id|class)="livcms_cell".+?>liv_' . $v['cell_name'] . '<\/span>/';
                $cellReplace[] = $v['rended_html'];
            }
            $cssStr .= '</style>';
            $jsStr .= '</script>';
            $template = preg_replace($cellPattern, $cellReplace, $template);
            $template = str_ireplace('</head>', $cssStr . '</head>', $template);
            $template = str_ireplace('</body>', $jsStr . '</body>', $template);
        }
        if (is_array($this->arLayoutInfo) && count($this->arLayoutInfo) > 0)
        {
            foreach ($this->arLayoutInfo as $k => $v)
            {
                $template = str_ireplace('</head>', '<style type="text/css">' . $v['css'] . '</style></head>', $template);
                $template = preg_replace('/<div[^>]*id="m2o-main-box"[^>]*>/', '\\0' . $v['content'], $template);
                if (is_array($v['cells']) && count($v['cells']) > 0)
                {
                    $cssStr      = '<style type="text/css">';
                    $jsStr       = '<script type="text/javascript">';
                    $cellPattern = $cellReplace = array();
                    foreach ($v['cells'] as $kk => $vv)
                    {
                        $vv            = $this->cellProcess($vv, true);
                        $cssStr .= $vv['css'] . ' ';
                        $jsStr .= $vv['js'] . '';
                        $cellPattern[] = '/<span[\s]+(?:id|class)="livcms_cell".+?>liv_' . $vv['cell_name'] . '<\/span>/';
                        $cellReplace[] = $vv['rended_html'];
                    }
                    $cssStr .= '</style>';
                    $jsStr .= '</script>';
                    $template = preg_replace($cellPattern, $cellReplace, $template);
                    $template = str_ireplace('</head>', $cssStr . '</head>', $template);
                    $template = str_ireplace('</body>', $jsStr . '</body>', $template);
                }
            }
        }
        return $template;
    }

    /**
     * 编辑单元
     * 
     * @param  array $data 单元信息
     * @return  array 编辑过后的单元的详细信息
     */
    public function cellUpdate($data)
    {
        if (!$data)
        {
            return false;
        }
        if (is_array($data) && count($data) > 0)
        {
            if (!class_exists('cell'))
            {
                include_once(CUR_CONF_PATH . 'lib/cell.class.php');
            }
            $this->obj = new cell();
            $return    = array();
            foreach ($data as $k => $v)
            {
                $oriCellInfo = $this->obj->detail(" AND id = " . $v['id']);
                $info        = array();
                $condition   = " AND site_id = " . intval($v['site_id']) . " AND page_id = " . intval($v['page_id']) . " AND page_data_id = " . intval($v['page_data_id']) . " AND content_type = " . intval($v['content_type']) . " AND template_sign = '" . $v['template_sign'] . "' AND template_style = '" . $v['template_style'] . "' AND cell_name='" . $v['cell_name'] . "' AND original_id != 0 AND del=0";
                $q           = $this->obj->detail($condition);
                $param       = array();
                if (is_array($v['input_param']) && count($v['input_param']) > 0)
                {
                    foreach ($v['input_param'] as $kk => $vv)
                    {
                        $vv['value'] = str_replace("&#60;", '<', $vv['value']);
                        if ($vv['sign'] == 'count')
                        {
                            $dataCountNum = $vv['value'];
                        }
                        if ($vv['value'] !== $vv['default_value'])
                        {
                            $param['input_param'][$vv['sign']] = $vv['value'];
                        }
                    }
                }
                if (is_array($v['mode_param']) && count($v['mode_param']) > 0)
                {
                    foreach ($v['mode_param'] as $kk => $vv)
                    {
                        $vv['value'] = str_replace("&#60;", '<', $vv['value']);
                        if ($vv['value'] !== $vv['default_value'])
                        {
                            $param['mode_param'][$vv['sign']] = $vv['value'];
                        }
                    }
                }
                if (is_array($v['css_param']) && count($v['css_param']) > 0)
                {
                    foreach ($v['css_param'] as $kk => $vv)
                    {
                        if ($vv['value'] !== $vv['default_value'])
                        {
                            $param['css_param'][$vv['sign']] = $vv['value'];
                        }
                    }
                }
                if (is_array($v['js_param']) && count($v['js_param']) > 0)
                {
                    foreach ($v['js_param'] as $kk => $vv)
                    {
                        if ($vv['value'] !== $vv['default_value'])
                        {
                            $param['js_param'][$vv['sign']] = $vv['value'];
                        }
                    }
                }
                empty($this->arPageInfo) && ($this->arPageInfo    = common::getPageBySign('special', 'id'));
                $blSpecial           = ($v['page_id'] == $this->arPageInfo['id']) ? 1 : 0;  //取专题的页面,如果是专题单元时不自动选择css
                ($v['css_id'] == 0 && $v['cell_mode'] && !$blSpecial) && ($v['css_id']         = common::get_mode_default_css($v['cell_mode']));
                $v['css_id'] == -1 && $v['css_id']         = 0;
                $info                = array(
                    'css_id' => $v['css_id'],
                    'cell_type' => $v['cell_type'],
                    'cell_mode' => $v['cell_mode'],
                    'param_asso' => addslashes(serialize($param)),
                    'is_header' => $v['is_header'],
                    'header_text' => $v['header_text'],
                    'is_more' => $v['is_more'],
                    'more_href' => $v['more_href'],
                );
                $info['cell_type'] != 3 && ($info['static_html'] = $v['static_html']    = '');
                isset($v['data_source']) && ($info['data_source'] = $v['data_source']);
                if ($info['cell_mode'])
                {     //区块样式处理
                    $modeDetail          = $info['cell_mode'] ? common::modeDetail($info['cell_mode'], 'title') : array();
                    $info['using_block'] = $v['using_block']    = (substr($modeDetail['title'], 0, 3) == 'qk_') ? 1 : 0;
                    if ($info['using_block'])
                    {
                        $postFields = array(
                            'site_id' => intval($v['site_id']),
                            'page_id' => intval($v['page_id']),
                            'page_data_id' => intval($v['page_data_id']),
                            'content_type' => intval($v['content_type']),
                            'client_type' => $this->intClientType,
                            'datasource_id' => intval($info['data_source']),
                            'datasource_argument' => $param['input_param'],
                            'name' => $v['id'] . '_' . $v['cell_name'],
                            'expand_name' => $this->arPageColumnInfo['name'] ? $this->arPageColumnInfo['name'] : $this->arPageSiteInfo['site_name'],
                            'line_num' => $dataCountNum,
                        );
                    }
                    include_once (ROOT_PATH . 'lib/class/block.class.php');
                    $objBlock = new block();
                }

                if ($oriCellInfo['using_block'] && !$info['using_block'])
                {
                    $objBlock->delete_block($oriCellInfo['block_id']);
                    $info['block_id'] = $v['block_id']    = 0;
                }

                if ($q || ($this->blPreset && $this->blPreset != 'false') || $v['layout_id'])
                {
                    $info['update_time'] = TIMENOW;
                    if ($info['using_block'])
                    {
                        $response         = $v['block_id'] ? $objBlock->update_block($postFields, $v['block_id']) : $objBlock->insert_block($postFields);
                        !$v['block_id'] && ($info['block_id'] = $v['block_id']    = $response['id']);
                    }
                    if ($v['layout_id'])
                    {
                        $condition = " id = " . $v['id'];
                        $this->db->update_data($info, 'layout_cell', $condition);
                    }
                    else
                    {
                        $this->obj->update($info, $v['id']);
                    }
                }
                else
                {
                    $info['block_id']       = $response['id'];
                    $info['original_id']    = $v['id'];   //原单元id
                    $info['create_time']    = TIMENOW;
                    $info['update_time']    = TIMENOW;
                    $info['sign']           = uniqid();
                    $info['cell_name']      = $v['cell_name'];
                    $info['template_style'] = $v['template_style'];
                    $info['sort_id']        = intval($v['sort_id']);
                    $info['template_id']    = $v['template_id'];
                    $info['template_sign']  = $v['template_sign'];
                    $info['site_id']        = $v['site_id'];
                    $info['page_id']        = $v['page_id'];
                    $info['page_data_id']   = $v['page_data_id'];
                    $info['content_type']   = $v['content_type'];
                    $v['original_id']       = $v['id'];
                    $v['id']                = $this->obj->create($info);
                    if ($info['using_block'])
                    {
                        $postFields['name'] = $v['id'] . '_' . $v['cell_name'];
                        $response           = $objBlock->insert_block($postFields);
                        $this->obj->update(array('block_id' => $response['id']), $v['id']);
                        $v['block_id']      = $response['id'];
                    }
                }
                $v['param_asso'] = $param;
                $v               = $this->cellProcess($v, true);
                $return[]        = $v;
            }
        }
        return $return;
    }

    /**
     * 撤销单元
     * 
     * @param string $ids  单元id、多个逗号隔开
     * @return array 撤销后原始单元的信息
     */
    function cellCancle($ids)
    {
        if (!$ids)
        {
            return false;
        }
        if (!class_exists('cell'))
        {
            include_once(CUR_CONF_PATH . 'lib/cell.class.php');
        }
        $this->obj      = new cell();
        $condition      = " AND c.id IN(" . $ids . ")";
        $data           = $this->obj->show($condition);
        $intSiteId      = $intPageId      = $intPageDataId  = $intContentType = '';
        $originalId     = $deleteId       = $blockId        = array();
        if (is_array($data) && count($data) > 0)
        {
            $tmp_arr = array(
                'page_id' => 0,
                'page_data_id' => 0,
                'content_type' => 0,
                'cell_type' => 0,
                'param_asso' => '',
                'cell_mode' => 0,
                'data_source' => 0,
                'using_block' => 0,
                'block_id' => 0,
                'css_id' => 0,
            );
            ###original_id为真时delete此单元 为假时update    
            foreach ($data as $k => $v)
            {
                $originalId[] = $v['original_id'] ? $v['original_id'] : $v['id'];
                if (!$v['original_id'])
                {
                    $condition = " id = " . intval($v['id']);
                    if ($v['layout_id'])
                    {
                        $this->db->update_data($tmp_arr, 'layout_cell', $condition);
                    }
                    else
                    {
                        $this->db->update_data($tmp_arr, 'cell', $condition);
                    }
                }
                else
                {
                    $this->intSiteId      = $v['site_id'];
                    $this->intPageId      = $v['page_id'];
                    $this->intPageDataId  = $v['page_data_id'];
                    $this->intContentType = $v['content_type'];
                    $deleteId[]           = $v['id'];
                }
                $blockId[] = $v['block_id'];
            }
        }
        $deleteId = implode(',', $deleteId);
        if ($deleteId)
        {
            $this->obj->delete($deleteId);
        }
        $blockId = implode(',', $blockId);
        if ($blockId)
        {
            include_once (ROOT_PATH . 'lib/class/block.class.php');
            $objBlock = new block();
            $objBlock->delete_block($blockId);
        }
        ###设置分页信息
        $this->setPaginationInfo();
        ###设置分页信息           
        $originalId = implode(',', $originalId);
        $condition  = " AND c.id IN(" . $originalId . ")";
        $cell       = $this->obj->show($condition);
        $return     = array();
        if (is_array($cell) && count($cell))
        {
            foreach ($cell as $k => $v)
            {
                $v        = $this->cellProcess($v, true);
                $return[] = $v;
            }
        }
        return $return;
    }

    /**
     * 合并当前页面模板
     *
     *
     *
     */
    private function mergeCell()
    {
        if (!class_exists('publishconfig'))
        {
            include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
        }
        $this->objPub = new publishconfig();
        if (!$this->arSiteInfo)
        {
            $this->arSiteInfo = $this->objPub->get_site_first('*', $this->intSiteId);
        }
        if ($this->intLayoutId)
        {
            if (!class_exists('layout'))
            {
                include(CUR_CONF_PATH . 'lib/layout.class.php');
            }
            $objLayout        = new layout();
            $this->arCellList = $objLayout->getLayoutCell($this->intLayoutId);
            return '';
        }
        if ($this->blPreset && $this->blPreset != 'false')
        {
            $deploy           = common::get_template_cell($this->intSiteId, $this->intTemplateId, $this->strTemplateSign);
            $this->arCellList = $deploy['default_cell'];
            return;
        }
        if ($this->strTemplateSign && (!$this->blPreset || $this->blPreset === 'false'))
        {    //快速专题  取出此专题的布局信息  内容类型存在时是列表页  列表页暂不支持布局
            if (!$this->intContentType)
            {
                $sql            = "SELECT * FROM " . DB_PREFIX . "template_layout WHERE special_id = " . $this->intPageDataId;
                $templateLayout = $this->db->query_first($sql);
                $layoutIds      = $templateLayout['layout_ids'];
                if ($layoutIds)
                {
                    $this->strLayoutIds = $layoutIds;
                    if (!class_exists('layout'))
                    {
                        include(CUR_CONF_PATH . 'lib/layout.class.php');
                    }
                    $objLayout  = new layout();
                    $sql        = "SELECT id, content, css, is_header, header_text, is_more, more_href,original_id 
                            FROM " . DB_PREFIX . "layout WHERE id IN(" . $layoutIds . ")";
                    $q          = $this->db->query($sql);
                    $layoutInfo = array();
                    while ($row = $this->db->fetch_array($q))
                    {
                        $row = $objLayout->layout_namespace_and_header_process($row);
                        $layoutInfo[$row['id']] = $row;
                    }
                    $layoutIds = explode(',', $layoutIds);
                    $layouts   = array();
                    if (is_array($layoutIds) && count($layoutIds) > 0)
                    {
                        $layoutIds = array_reverse($layoutIds);
                        foreach ($layoutIds as $k => $v)
                        {
                            $cells                   = array();
                            $cells                   = $objLayout->getLayoutCell($v);
                            $layoutInfo[$v]['cells'] = $cells;
                            $layouts[]               = $layoutInfo[$v];
                        }
                    }
                    $this->arLayoutInfo = $layouts;
                }
            }
        }
        else
        {
            include_once(CUR_CONF_PATH . 'lib/rebuild_deploy.class.php');
            $deploy                = new rebuilddeploy();
            $deployTemplate        = $deploy->get_deploy_templates($this->intSiteId, $this->intPageId, $this->intPageDataId);
            //赛选此客户端类型下该内容类型下部署的模板
            $this->strTemplateSign = $deployTemplate[$this->intClientType][$this->intContentType]['template_sign'];

            //没有部署模板时使用全局类型模板
            if ( !$this->strTemplateSign )
            {
                $sql    = "SELECT id, sign FROM " . DB_PREFIX . "templates WHERE site_id = 0 AND content_type = " . $this->intContentType;
                $arInfo = $this->db->query_first($sql);
                if (!$arInfo)
                {
                    return array('errmsg' => '该内容类型全局模板不存在');
                }
                $this->strTemplateSign = $arInfo['sign'];
                $this->intUseGlobalTemplate = 1;
            }
        }

        //模板合并机制更改  老机制多套系切换时有bug 20140831
        if (!$this->arSiteInfo)
        {
            $this->arSiteInfo = $this->objPub->get_site_first('tem_style', $this->intSiteId);
        }
        $this->strCurrStyle = $this->arSiteInfo['tem_style'];
        //使用默认套系
        if ($this->strDefaultStyle == $this->arSiteInfo['tem_style'])
        {
            //默认套系中该模板预设单元
            $condition      = " AND c.template_style='" . $this->strDefaultStyle . "' AND c.template_sign IN('" . $this->strTemplateSign . "') AND c.original_id = 0";
            $arDefaultCell  = common::get_cell($condition);
            //默认套系中页面单元
            $condition      = " AND c.site_id = " . $this->intSiteId . " AND c.page_id=" . $this->intPageId . " AND c.page_data_id=" . $this->intPageDataId . " AND c.content_type = " . $this->intContentType . "
                            AND c.template_style='" . $this->strDefaultStyle . "' AND c.template_sign IN('" . $this->strTemplateSign . "') AND c.original_id != 0";
            $defaultSetCell = common::get_cell($condition);
            //合并模板单元和页面单元 优先使用页面单元
            if (is_array($defaultSetCell) && count($defaultSetCell))
            {
                foreach ($defaultSetCell as $k => $v)
                {
                    if (array_key_exists($k, $arDefaultCell))
                    {
                        $arDefaultCell[$k] = $defaultSetCell[$k];
                    }
                }
            }
        }
        else   //使用其他套系  当使用套系中模板不存在时使用默认套系模板
        {
            $sql    = "SELECT id FROM " . DB_PREFIX . "templates WHERE template_style = '".$this->arSiteInfo['tem_style']."' AND sign = '" . $this->strTemplateSign . "'";
            $info = $this->db->query_first($sql);
            if (!$info['id'])   //当前使用套系中不存在此模板时使用默认套系模板
            {
                //默认套系中该模板预设单元
                $condition      = " AND c.template_style='" . $this->strDefaultStyle . "' AND c.template_sign IN('" . $this->strTemplateSign . "') AND c.original_id = 0";
                $arDefaultCell  = common::get_cell($condition);
                //默认套系中页面单元
                $condition      = " AND c.site_id = " . $this->intSiteId . " AND c.page_id=" . $this->intPageId . " AND c.page_data_id=" . $this->intPageDataId . " AND c.content_type = " . $this->intContentType . "
                            AND c.template_style='" . $this->strDefaultStyle . "' AND c.template_sign IN('" . $this->strTemplateSign . "') AND c.original_id != 0";
                $defaultSetCell = common::get_cell($condition);
                //合并模板单元和页面单元 优先使用页面单元
                if (is_array($defaultSetCell) && count($defaultSetCell))
                {
                    foreach ($defaultSetCell as $k => $v)
                    {
                        if (array_key_exists($k, $arDefaultCell))
                        {
                            $arDefaultCell[$k] = $defaultSetCell[$k];
                        }
                    }
                }
            }
            else    //使用当前使用套系模板
            {
                //当前使用套系模板预设单元
                $condition          = " AND c.template_style='" . $this->arSiteInfo['tem_style'] . "' AND c.template_sign IN('" . $this->strTemplateSign . "')  AND c.original_id = 0";
                $arDefaultCell        = common::get_cell($condition);
                //当前使用套系页面单元
                $condition      = " AND c.site_id=" . $this->intSiteId . " AND c.page_id=" . $this->intPageId . " AND c.page_data_id=" . $this->intPageDataId . " AND c.content_type = " . $this->intContentType . "
                            AND c.template_style='" . $this->arSiteInfo['tem_style'] . "' AND c.template_sign IN('" . $this->strTemplateSign . "') AND c.original_id != 0 ";
                $defaultSetCell = common::get_cell($condition);
                //合并模板预设单元和页面单元 优先使用页面单元
                if (is_array($defaultSetCell) && count($defaultSetCell) > 0)
                {
                    foreach ($defaultSetCell as $k => $v)
                    {
                        if (array_key_exists($k, $arDefaultCell))
                        {
                            $arDefaultCell[$k] = $defaultSetCell[$k];
                        }
                    }
                }
            }
        }
        //模板合并机制更改  老机制多套系切换时bug 20140831


//        //默认套系中该模板单元
//        $condition      = " AND c.template_style='" . $this->strDefaultStyle . "' AND c.template_sign IN('" . $this->strTemplateSign . "') AND c.original_id = 0";
//        $arDefaultCell  = common::get_cell($condition);
//        //默认套系中页面单元
//        $condition      = " AND c.site_id = " . $this->intSiteId . " AND c.page_id=" . $this->intPageId . " AND c.page_data_id=" . $this->intPageDataId . " AND c.content_type = " . $this->intContentType . "
//                            AND c.template_style='" . $this->strDefaultStyle . "' AND c.template_sign IN('" . $this->strTemplateSign . "') AND c.original_id != 0";
//        $defaultSetCell = common::get_cell($condition);
//        //第一次合并  默认套系中的模板单元和默认套系中的页面单元
//        if (is_array($defaultSetCell) && count($defaultSetCell))
//        {
//            foreach ($defaultSetCell as $k => $v)
//            {
//                if (array_key_exists($k, $arDefaultCell))
//                {
//                    $arDefaultCell[$k] = $defaultSetCell[$k];
//                }
//            }
//        }
//        //当前使用套系该模板单元
//        if (!$this->arSiteInfo)
//        {
//            $this->arSiteInfo = $this->objPub->get_site_first('tem_style', $this->intSiteId);
//        }
//        $this->strCurrStyle = $this->arSiteInfo['tem_style'];
//        $condition          = " AND c.template_style='" . $this->arSiteInfo['tem_style'] . "' AND c.template_sign IN('" . $this->strTemplateSign . "')  AND c.original_id = 0";
//        $arUsingCell        = common::get_cell($condition);
//        //第二次合并 第一次合并后的单元和当前使用套系模板单元
//        if (is_array($arUsingCell) && count($arUsingCell) > 0)
//        {
//            foreach ($arUsingCell as $k => $v)
//            {
//                if (array_key_exists($k, $arDefaultCell))
//                {
//                    if ($arUsingCell[$k]['cell_mode'] || $arUsingCell[$k]['data_source'])
//                    {
//                        $arDefaultCell[$k] = $arUsingCell[$k];
//                    }
//                }
//            }
//        }
//        //当前使用套系页面单元
//        $condition      = " AND c.site_id=" . $this->intSiteId . " AND c.page_id=" . $this->intPageId . " AND c.page_data_id=" . $this->intPageDataId . " AND c.content_type = " . $this->intContentType . "
//                            AND c.template_style='" . $this->arSiteInfo['tem_style'] . "' AND c.template_sign IN('" . $this->strTemplateSign . "') AND c.original_id != 0 ";
//        $arUsingSetCell = common::get_cell($condition);
//        //第三次合并  第二次合并后的单元和当前使用套系页面单元
//        if (is_array($arUsingSetCell) && count($arUsingSetCell) > 0)
//        {
//            foreach ($arUsingSetCell as $k => $v)
//            {
//                if (array_key_exists($k, $arDefaultCell))
//                {
//                    $arDefaultCell[$k] = $arUsingSetCell[$k];
//                }
//            }
//        }

        $this->arCellList = $arDefaultCell;
        return;
    }

    /**
     * 设置分页、站点、页面、栏目、专题、专题栏目信息
     */
    private function setPaginationInfo()
    {
        if (!$this->arSiteInfo && $this->intSiteId)
        {
            include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
            $this->objPub     = new publishconfig();
            $this->arSiteInfo = $this->objPub->get_site_first('*', $this->intSiteId);
        }
        //设置文件生成方式
        if ($this->intSiteId)
        {
            $needPageInfo['file_mktype']   = $this->arSiteInfo['produce_format'];
            $needPageInfo['page_filename'] = $this->arSiteInfo['indexname'];
            $needPageInfo['page_url']      = $this->arSiteInfo['site_info']['url'];
            $needPageInfo['dir']           = $this->arSiteInfo['site_dir'];
        }
        if ($this->intPageId)
        {
            $arPageType                    = common::get_page_manage($this->intSiteId, $this->intPageId, 'id');
            $arPageTypeDetail              = $arPageType[$this->intPageId];
            //设置文件生成方式
            $needPageInfo['file_mktype']   = $arPageTypeDetail['maketype'];
            $needPageInfo['page_filename'] = $arPageTypeDetail['colindex'];
            $needPageInfo['page_url']      = $arPageTypeDetail['column_domain'];
            $needPageInfo['dir'] .= $arPageTypeDetail['column_dir'];
        }
        if ($this->intPageDataId)
        {
            empty($this->arPageInfo) && ($this->arPageInfo = common::getPageBySign('special', 'id'));
            if ($this->intPageId == $this->arPageInfo['id'])
            {  //专题信息
                $arSpecialInfo = common::get_special_info($this->intPageDataId);
                if ($this->intContentType)
                {
                    $arSpecialColumnInfo = common::get_special_column_info($this->intContentType);
                }
            }
            else
            {
                $arPageData                    = common::get_page_data($this->intPageId, '', '', '', '', $this->intPageDataId);
                $arColumnInfo                  = $arPageData['page_data'][0];
                //设置文件生成方式
                $needPageInfo['file_mktype']   = $arColumnInfo['maketype'];
                $needPageInfo['page_filename'] = $arColumnInfo['colindex'];
                $needPageInfo['page_url']      = $arColumnInfo['column_domain'];
                $needPageInfo['dir'] .= $arColumnInfo['column_dir'];
            }
        }
        if ($this->intClientType)
        {
            if (!class_exists('publishconfig'))
            {
                include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
            }
            $this->objPub           = new publishconfig();
            $this->arPageClientInfo = $this->objPub->get_client_first($this->intClientType);
        }
        $this->arNeedPageInfo          = $needPageInfo;
        $this->arPageSiteInfo          = $this->arSiteInfo;
        $this->arPageColumnInfo        = $arColumnInfo;
        $this->arPageSpecialInfo       = $arSpecialInfo;
        $this->arPageSpecialColumnInfo = $arSpecialColumnInfo;
    }

    /**
     * 处理单元、生成单元的html
     * 
     * @param array $info 单元详细信息
     * @param boolean $force 是否强制生成单元缓存
     * @param array $arData 数据、有此参数时用该数据生成单元hmtl 用于单元预览
     * @param array 处理过后的单元详细信息
     */
    public function cellProcess($info, $force = false, $arData = array())
    {
        if (!$info['cell_mode'])
        {
            //return array();
        }
        
        /**********专题栏目链接处理***************************/
        if (strpos($info['more_href'], 'COLURL') !== false) {
            $intColumnId = intval(str_replace('COLURL', '', $info['more_href']));
            if (!class_exists('special')) {
                include(ROOT_PATH . 'lib/class/special.class.php');
            }
            $objSpecial = new special(); 
            $info['more_href'] = $objSpecial->get_special_col_url($intColumnId); 
        } 
        /**********专题栏目链接处理***************************/
           
               
        $mode_info   = common::get_mode_info(intval($info['cell_mode']), $info['id'], intval($info['css_id']), intval($info['js_id']), $info['param_asso']);
        $blBuiltCell = ($this->blBuiltCell && ($this->blBuiltCell !== 'false')) ? 1 : 0;
        if (($info['cell_type'] == 3) && $blBuiltCell)
        {
            $html = $info['static_html'];
        }
        else
        {
            $content  = $mode_info['mode_info']['content'];
            $content  = str_replace('&nbsp;', ' ', $content);
            $ret_data = array();
            if (!$info['data_source'])
            {
                $map = common::get_mode_map($mode_info);
                if ($blBuiltCell)
                {
                    $ret_data = !empty($arData) ? $arData : $mode_info['mode_info']['default_param'];
                }
                $ret_data = $info['using_block'] ? common::getBlockData($info['block_id']) : $ret_data;
            }
            else
            {
                $data_source = common::get_datasource_info($info['data_source'], $info['param_asso']);
                if ($info['using_block'] && $blBuiltCell)
                {
                    $ret_data = !empty($arData) ? $arData : common::getBlockData($info['block_id']);
                }
                else
                {
                    $map = common::get_cell_map($mode_info, $data_source, $info['param_asso']);
                    if ($blBuiltCell)
                    {
                        $ret_data = common::get_content_by_datasource($info['data_source'], $map['data_input_variable']);
                        if (isset($ret_data['total']))
                        {
                            $intTotal = $ret_data['total'];
                            $ret_data = $ret_data['data'];
                        }
                        if (!$info['layout_id'])   //快速专题布局单元暂不支持数据编辑
                        {
                            //替换已经编辑过的单元数据
                            if (!class_exists('cell'))
                            {
                                include (CUR_CONF_PATH . 'lib/cell.class.php');
                            }
                            $objCell    = new cell();
                            $arCellData = $objCell->getCellData($info['id']);
                            if (is_array($ret_data) && count($ret_data) > 0)
                            {
                                foreach ($ret_data as $k => $v)
                                {
                                    !empty($arCellData[$v['id']]) && ($arCellData[$v['id']]['id'] = $arCellData[$v['id']]['content_id']);
                                    $ret_data[$k]               = !empty($arCellData[$v['id']]) ? $arCellData[$v['id']] : $v;
                                    if (!empty($arData))
                                    {
                                        if ($v['id'] == $arData['content_id'])
                                        {    //arData 预览提交的数据
                                            $arData['id'] = $arData['content_id'];
                                            $ret_data[$k] = $arData;
                                        }
                                    }
                                }
                            }
                        }
                        if (isset($intTotal))
                        {
                            $ret_data = array('total' => $intTotal, 'data' => $ret_data);
                        }
                    }
                }
            }
            $cache_file = $info['layout_id'] ? $info['id'] . '_'.$info['layout_id'].'.php' : $info['id'] . '.php';
            $cache_filepath = MODE_CACHE_DIR . substr(md5($cache_file), 0, 2) . '/';
            include_once(CUR_CONF_PATH . 'lib/parse.class.php');
            $parse      = new Parse();
            $parse->parse_template(stripcslashes($content), $info['id'], $mode_info['mode_info'], $map['relation_map'], $map['mode_variable_map'], $map['variable_function_relation']);
            if ($blBuiltCell)
            {
                if (MAGIC_DEBUG)
                {
                    $path = CUR_CONF_PATH . 'cache/log/data/';
                    hg_mkdir($path);
                    hg_file_write($path . $info['id'] . '.txt', var_export($map['data_input_variable'], 1) . var_export($ret_data, 1));
                }
                $html = $parse->built_cell_html($ret_data, $cache_file, $mode_info['mode_info'], $this->arNeedPageInfo, $this->arPageSiteInfo, $this->arPageColumnInfo, $this->arPageClientInfo, $this->arPageSpecialInfo, $this->arPageSpecialColumnInfo, $map['data_input_variable'], $force, $cache_filepath);
                if ($info['is_header'])
                {
                    $find    = array('{$header_text}', '{$more_href}', '{$more_text}');
                    $replace = array($info['header_text'], $info['is_more'] ? $info['more_href'] : '#', $info['is_more'] ? '更多>>' : '');
                    $header  = str_replace($find, $replace, $this->settings['header_dom']['cell']);
                    $html    = $header . $html;
                }
                // if (empty($ret_data)) {
                // $html = '<span>暂无数据</span>' . $html;
                // }
            }
            else
            {
                $parse->built_mode_cache($cache_file, $cache_filepath);
            }
        }
        $ret                 = array();
        $ret                 = array_merge($info, $mode_info);
        $ret['mode_detail']  = $ret['mode_info'];     //生成时用
        unset($ret['mode_info']);
        (!$ret['using_block'] && $ret['data_source']) && ($ret['can_edit']     = 1);  //有数据源且不是区块单元时单元数据可编辑
        $ret['rended_html']  = $html;
        $ret['input_param']  = $data_source['input_param'];
        $ret['site_id']      = $this->intSiteId;
        $ret['page_id']      = $this->intPageId;
        $ret['page_data_id'] = $this->intPageDataId;
        $ret['content_type'] = $this->intContentType;
        if ($blBuiltCell)
        {
            $strNsPre           = $ret['layout_id'] ? 'layout_cell' : 'cell';
            $ret['css']         = str_replace('<MATEURL>', ICON_URL, preg_replace('/<NS([0-9a-zA-Z]*)>/', '.' . $strNsPre . '_' . $ret['id'] . '_\\1', $ret['css']));
            $ret['js']          = str_replace('<MATEURL>', ICON_URL, preg_replace('/<NS([0-9a-zA-Z]*)>/', '.' . $strNsPre . '_' . $ret['id'] . '_\\1', $ret['js']));
            $ret['rended_html'] = str_replace('<MATEURL>', ICON_URL, preg_replace('/<NS([0-9a-zA-Z]*)>/', $strNsPre . '_' . $ret['id'] . '_\\1', $ret['rended_html']));
            
            $ret['css']         = preg_replace('/<NNS([0-9a-zA-Z]*)>/', $strNsPre . '_' . $ret['id'] . '_\\1', $ret['css']);
            $ret['js']          = preg_replace('/<NNS([0-9a-zA-Z]*)>/', $strNsPre . '_' . $ret['id'] . '_\\1', $ret['js']);
            $ret['rended_html'] = preg_replace('/<NNS([0-9a-zA-Z]*)>/', $strNsPre . '_' . $ret['id'] . '_\\1', $ret['rended_html']);            
            if ($this->input['data'] == 1 || $this->input['return_data'] == 1)
            {
                if (is_array($ret_data) && count($ret_data) > 0)
                {
                    foreach ($ret_data as $k => $v)
                    {
                        $ret['data'][] = array(
                            'id' => $v['id'],
                            'title' => $v['title'],
                            'brief' => $v['brief'],
                            'indexpic' => $v['indexpic'],
                            'content_url' => $v['content_url'],
                        );
                    }
                }
            }
        }
        return $ret;
    }

    private function getTemplate()
    {
        if ($this->intLayoutId)
        {
            if (!class_exists('layout'))
            {
                include(CUR_CONF_PATH . 'lib/layout.class.php');
            }
            $objLayout = new layout();
            $template  = $objLayout->joinLayoutTemplate($this->intLayoutId);
        }
        else
        {
            if ($this->strTemplateSign)
            {
                $strStyle       = $this->strCurrStyle ? $this->strCurrStyle : $this->strDefaultStyle;
                $strMaterialUrl = $this->strMaterialUrl ? $this->strMaterialUrl : $this->settings['template_image_url'];
                $intSiteId = $this->intUseGlobalTemplate ? 0 : $this->intSiteId;
                $template       = common::get_template_cache($this->strTemplateSign, $strStyle, $intSiteId, $strMaterialUrl);
            }
        }
        $this->strTemplate = $template;
        return $this->strTemplate;
    }

    public function __destruct()
    {
        parent::__destruct();
    }

}
