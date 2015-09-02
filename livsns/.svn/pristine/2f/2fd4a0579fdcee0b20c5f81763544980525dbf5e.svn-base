<?php

require('global.php');
define('MOD_UNIQUEID','program_library');
class programLibraryUpdateApi extends adminBase
{   
    public function update() {
        if (!$this->input['title']) {
            $this->errorOutput('NO TITLE');
        }
        $indexpic = json_decode($this->input['indexpic'], 1);
        $info = array(
            'title'     => $this->input['title'],
            'start_time'=> $this->input['start_time'],
            'channel_id' =>$this->input['channel_id'],
            'indexpic'      => !empty($indexpic) ? addslashes(serialize($indexpic)) : '',
            'brief'         => $this->input['brief'],
            'week_day'   => !empty($this->input['week_day']) ? addslashes(serialize($this->input['week_day'])) : '',
        );
        $id = intval($this->input['id']);
        if ($id) {  //编辑
            $affected_rows= $this->db->update_data($info, 'program_library', 'id=' . $id);
            if ($affected_rows) {
                $arTem = array(
                    'edit_userid'  => $this->user['user_id'],
                    'edit_username'=> $this->user['user_name'],
                    'update_time'  => TIMENOW,
                );
                $this->db->update_data($arTem, 'program_library', 'id=' . $id);
            }
            $info['id'] = $id;
        }
        else {  //新增
            $info['create_time'] = $info['update_time'] = TIMENOW;
            $info['create_userid']  = $info['edit_userid'] = $this->user['user_id'];
            $info['create_username'] = $info['edit_username'] = $this->user['user_name'];        
            $insert_id = $this->db->insert_data($info, 'program_library');
            $info['id'] = $insert_id;
        }
        $info['week_day'] = $info['week_day'] ? unserialize(stripslashes($info['week_day'])) : array();
        $this->addItem($info);
        $this->output();
    }
    
    public function delete() {
        if (!$this->input['id']) {
            $this->errorOutput('NO ID');
        }
        $id = $this->input['id'];
        $sql = 'DELETE FROM '. DB_PREFIX . 'program_library WHERE 1 AND id IN('. $id .')';
        $this->db->query($sql);
        $this->addItem($id);
        $this->output();
    }    
    
   

    function unknow()
    {
        $this->errorOutput("此方法不存在！");
    }
}

$out = new programLibraryUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
    $action = 'unknow';
}
$out->$action();

?>