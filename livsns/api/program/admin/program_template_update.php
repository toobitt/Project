<?php

require('global.php');
define('MOD_UNIQUEID','program_template');
class programTemplateUpdateApi extends adminBase
{   
    public function update() {
        if (!$this->input['title']) {
            $this->errorOutput('NO TITLE');
        }
        $data = json_decode($this->input['data'],true);
        $dates = date('Y-m-d', TIMENOW);
        if(is_array($data) && count($data) >0) {
            foreach ($data as $k => $v) {
                $info_tmp = array();
                if(($k+1)<= $length) {
                    $next_end = strtotime($dates .  ' ' . $data[$k+1]['start']);
                }
                else {
                    $next_end = strtotime($dates . ' 23:59:59');
                } 
                $start =  strtotime($dates . ' ' . $v['start']);
                $data[$k]['toff'] = $next_end - $start;                  
            }
        }
        $info = array(
            'title'     => $this->input['title'],
            'data'      => !empty($data) ? addslashes(serialize($data)) : '',
        );
        $id = intval($this->input['id']);
        if ($id) {  //编辑
            $affected_rows= $this->db->update_data($info, 'program_template', 'id=' . $id);
            if ($affected_rows) {
                $arTem = array(
                    'edit_userid'  => $this->user['user_id'],
                    'edit_username'=> $this->user['user_name'],
                    'update_time'  => TIMENOW,
                );
                $this->db->update_data($arTem, 'program_template', 'id=' . $id);
            }
            $info['id'] = $id;
        }
        else {  //新增
            $info['create_time'] = $info['update_time'] = TIMENOW;
            $info['create_userid']  = $info['edit_userid'] = $this->user['user_id'];
            $info['create_username'] = $info['edit_username'] = $this->user['user_name'];        
            $insert_id = $this->db->insert_data($info, 'program_template');
            $info['id'] = $insert_id;
        }
        $info['data'] = $data;
        $this->addItem($info);
        $this->output();
    }
    
    public function delete() {
        if (!$this->input['id']) {
            $this->errorOutput('NO ID');
        }
        $id = $this->input['id'];
        $sql = 'DELETE FROM '. DB_PREFIX . 'program_template WHERE 1 AND id IN(\''. $id .'\')';
        $this->db->query($sql);
        $this->addItem($id);
        $this->output();
    }
    
    public function programTemplateSetDetail() {
        $intChannelId = intval($this->input['channelId']);
        if (!$intChannelId) {
            $this->errorOutput('NO CHANNELID');
        }
        //如果没有起始时间 和结束时间  则1-1 到12-31
        $this->input['startTime'] = $this->input['startTime'] ? $this->input['startTime'] : date('Y') . '-1-1';
        $this->input['endTime'] = $this->input['endTime'] ? $this->input['endTime'] : (date('Y')+1) . '-12-31';
        $intStartTime = intval(date('Ymd', strtotime($this->input['startTime']))); 
        $intEndTime = intval(date('Ymd', strtotime($this->input['endTime'])));
        $condition = ' AND channel_id = ' . $intChannelId;
        $condition .= ' AND intdate >= ' . $intStartTime;
        $condition .= ' AND intdate <= ' . $intEndTime;
        if (!class_exists('programTemplate')) {
            include (CUR_CONF_PATH  . 'lib/program_template.class.php');
        }
        $objProTemplate = new programTemplate();
        $relation = $objProTemplate->getProTemRelation($condition);
        $condition = '';
        $arTemplateList = $objProTemplate->getList($condition, 'id, title');
        $ret = array(
            'relation' => $relation,
            'templateList' => $arTemplateList,
        );
        $this->addItem($ret);
        $this->output();
    }
    
    public function programTemplateSet()
    {
        $intTemplateId = intval($this->input['templateId']);
        $intChannelId = intval($this->input['channelId']);
        $arDates = !is_array($this->input['date']) ? explode(',', $this->input['date']) : $this->input['date'];  
        if (!empty($arDates)) {
            foreach ($arDates as $k => $v) {
                $arDates[$k] = date('Y-m-d', strtotime($v));
            }
        }
        $strDates = implode(',', $arDates);
        if (!$strDates || !$intChannelId) {
            $this->errorOutput('NO DATES OR TEMPALTEID');
        }
        $sql = 'DELETE FROM ' .DB_PREFIX. 'program_template_relation WHERE 1 AND date IN(\''.implode('\',\'', $arDates).'\') AND channel_id = ' . $intChannelId; 
        $this->db->query($sql);
        if ($intTemplateId) {
            if (!class_exists('programTemplate')) {
                include (CUR_CONF_PATH  . 'lib/program_template.class.php');
            }
            $objProTemplate = new programTemplate();
            $arTemplateInfo = $objProTemplate->getOneById($intTemplateId);
            if (empty($arTemplateInfo)) {
                $this->errorOutput('NO TEMPLATE');
            }
            foreach ($arDates as $k => $v) {
                $arTmp = array(
                    'date' => $v,
                    'template_id' => $intTemplateId,
                    'template_title' => $arTemplateInfo['title'],
                    'channel_id' => $intChannelId,
                    'intdate' => date('Ymd', strtotime($v)),
                );
                $this->db->insert_data($arTmp, 'program_template_relation');
            }
        }
        $this->addItem('success');
        $this->output();
    }

    public function screenshotForTemplate()
    {
        $intTemplateId = intval($this->input['id']);
        $strIndexpic = $this->input['indexpic'];
        if (!$intTemplateId || !$strIndexpic) {
            $this->errorOutput('NO ID OR DATA');
        }
        if (!class_exists('material')) {
            include (ROOT_PATH . 'lib/class/material.class.php');
        }
        $mate = new material();
        $pic = $mate->imgdata2pic($strIndexpic);
        $pic = $pic[0];
        $p = array();
        if($pic && is_array($pic))
        {
            $p['host'] = $pic['host'];
            $p['dir'] = $pic['dir'];
            $p['filepath'] = $pic['filepath'];
            $p['filename'] = $pic['filename'];
        } 
        if (empty($p)) {
            $this->errorOutput('截图失败');
        }
        $this->db->update_data(array('indexpic' => addslashes(serialize($p))), 'program_template', 'id=' . $intTemplateId);
        $this->addItem($p);
        $this->output();            
    }
    

    function unknow()
    {
        $this->errorOutput("此方法不存在！");
    }
}

$out = new programTemplateUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
    $action = 'unknow';
}
$out->$action();

?>