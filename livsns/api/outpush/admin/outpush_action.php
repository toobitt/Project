<?php
//此模块用来向外部接口推送视频，图集，文稿。
define('MOD_UNIQUEID', 'outpush');
require_once('global.php');

class outpush_action extends adminUpdateBase {

    var $vod;

    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
    }

    public function create()
    {
        $ids = trim($this->input['pid']) ? trim($this->input['pid']) : '';
        if (! $ids) {
            $this->errorOutput(NOID);
        }
        //视频outpush
        if ($this->input['pushType'] == "vod") {
            include_once(ROOT_DIR . 'lib/class/livmedia.class.php');
            $outpush = new livmedia();
            $res     = $outpush->get_videos_info($ids);
            file_put_contents('data.txt', var_export($res, 1));
            if (! $res) {
                $this->errorOutput(NOSOURCE);
            }
            foreach ($res as $key => $vo) {
                $task['id']              = md5('hoge' . rand(1, 100000000) . time()); //必填 任务ID，最长不超过36个字符
                $task['name']            = $vo['title']; //必填 任务名称
                $task['resourceId']      = md5('hoge' . rand(1, 200000000) . time()); //必填 资源ID
                $task['resourceType']    = 0; //必填 资源类型 0：视音频资源 1：纯音频资源 3：图片资源 4：文档资源
                $task['sourceType']      = 4; //必填 1:信号采集 2:导入 3:esb 4:webservice/rest 5:dirwatch 6：launcher 7：web 8：收录 9:quickcut
                $task['sourceSystem']    = SYSMARK_VDO;//必填 来源于厚建M2O-视频填：hoge；来源于厚建M2O-文稿填：hoge-news
                $task['ccid']            = '';//可选 编目类
                $task['programCode']     = '';//可选 节目代码
                $task['programType']     = '';//可选 节目类型 0 节目 1 素材
                $task['hdFlag']          = 0; //可选 高标清标识 0标清 1高清
                $task['afd']             = 0;//可选 AFD值（0-15）
                $task['creatorId']       = '';//可选 创建人ID
                $task['creatorName']     = '';//可选 创建人名称
                $task['segmentInfo']     = '';//可选 Segment信息
                $task['memo']            = '';//可选 描述
                $task['priority']        = 0;//可选 优先级
                $task['inpoint']         = 0;//可选 入点，单位帧
                $task['outpoint']        = 0;//可选 出点，单位帧
                $task['duration']        = '';//可选 时长，单位帧
                $task['metadataInfo']    = '';//可选 元数据信息
                $task['files']           = array(
                    array(
                        'id'     => 'HOGE' . $task['id'],//主键，36位GUID
                        'source' => '<?xml version="1.0" encoding="UTF-8"?><SourceInfo><SourceFile FileType="0" Channel="0" FileName="' . $vo['video_filename'] . '" PathID="" Path="' . $vo['hostwork'] . '/' . $vo['video_path'] . '" TrimIn="0" TrimOut="0" RelativePath="" MD5Code="" StreamMediaInfoID=""/></SourceInfo>',
                    )
                );
                $taskList['taskList'][0] = $task;
                $sendata                 = json_encode($taskList);
                $res                     = $this->postData(SEND_URL, $sendata);
                file_put_contents('result.txt', var_export($res, 1));
            }
            //res返回成功，则更改相应数据库outpush_id字段
            if (strpos($res, 'false') == false && strpos($res, 'true')) {
                $change = $this->changeOutpushState('dev_media', 'vodinfo', $ids);
                if (! $change) {
                    $this->errorOutput(MYSQL_WRONG);
                }
                $this->addItem('success');
                $this->output();
            } else {
                $this->errorOutput(SQL_FAILED);
            }
            //文稿推送
        } elseif ($this->input['pushType'] == "news") {
            include_once(ROOT_DIR . 'lib/class/news.class.php');
            $new = new news();
            $res = $new->details($ids);
            file_put_contents('res.txt', var_export($res, 1));
            if (! $res) {
                $this->errorOutput(NOSOURCE);
            }
            foreach ($res as $vo) {
                $task['id']           = md5('hoge' . rand(1, 100000000) . time()); //必填 任务ID，最长不超过36个字符
                $task['name']         = $vo['title']; //必填 任务名称
                $task['resourceId']   = md5('hoge' . rand(1, 200000000) . time()); //必填 资源ID
                $task['resourceType'] = 10; //必填 资源类型 0：视音频资源 1：纯音频资源 3：图片资源 4：文档资源 10:全媒体稿件资源
                $task['sourceType']   = 4; //必填 1:信号采集 2:导入 3:esb 4:webservice/rest 5:dirwatch 6：launcher 7：web 8：收录 9:quickcut
                $task['sourceSystem'] = SYSMARK_NEWS;//必填 来源于厚建M2O-视频填：hoge；来源于厚建M2O-文稿填：hoge-news
                $task['ccid']         = '';//可选 编目类
                $task['programCode']  = '';//可选 节目代码
                $task['programType']  = '';//可选 节目类型 0 节目 1 素材
                $task['hdFlag']       = 0; //可选 高标清标识 0标清 1高清
                $task['afd']          = 0;//可选 AFD值（0-15）
                $task['creatorId']    = '';//可选 创建人ID
                $task['creatorName']  = '';//可选 创建人名称
                $task['segmentInfo']  = '';//可选 Segment信息
                $task['memo']         = '';//可选 描述
                $task['priority']     = 0;//可选 优先级
                $task['inpoint']      = 0;//可选 入点，单位帧
                $task['outpoint']     = 0;//可选 出点，单位帧
                $task['duration']     = '';//可选 时长，单位帧
                //文稿必选 元数据信息
                $task['metadataInfo']
                    = '<?xml version="1.0" encoding="UTF-8"?><MetaData MetaDataCount="3"><sAttribute enumType="0" strName="标题"><![CDATA[' . $vo['title'] . ']]></sAttribute><sAttribute enumType="0" strName="创建人"><![CDATA[' . $vo['author'] . ']]></sAttribute><sAttribute enumType="0" strName="富文本内容"><![CDATA[' . $vo['content'] . ']]></sAttribute></MetaData>';
                $id = 'HOGE' . $task['id'];//主键，36位GUID
                $source
                    = '<?xml version="1.0" encoding="UTF-8"?><SourceInfo>';
                //验证是否有图片
                if ($vo['is_img'] == 1) {
                    foreach ($vo['material'] as $m) {
                        $source .= '<SourceFile FileType="9" Channel="0" FileName="' . $m['pic']['filename'] . '" PathID="" Path="' . $m['pic']['host'] . $m['pic']['dir'] . $m['pic']['filepath'] . '" TrimIn="" TrimOut="" RelativePath="" MD5Code="" StreamMediaInfoID=""/>';
                    }
                }

                //验证是否有图集或视频
                if ($vo['file_info'] != array()) {
                    $tuji_id = array();
                    foreach ($vo['file_info'] as $a => $f) {
                        if ($f['app'] == 'tuji') {
                            $tuji_id[] = substr($a, 5);
                        } elseif ($f['app'] == 'livmedia') {
                            $source .= '<SourceFile FileType="5" Channel="0" FileName="' . basename($f['video_url']) . '" PathID="" Path="' . dirname($f['video_url']) . '/' . '" TrimIn="0" TrimOut="0" RelativePath="" MD5Code="" StreamMediaInfoID=""/>';
                        }
                    }
                    //文稿中若有图集则首先推送，获取返回resourceId,此处重写$_INPUT.调用图集入库
                    if ($tuji_id != array()) {
                        $this->input['ids']      = $tuji_id;
                        $this->input['pushType'] = 'tuji';
                        $this->input['inner']    = TRUE;
                        $res                     = $this->create($this->input);
                        if ($res == array()) {
                            $this->errorOutput(TUJI_PUSH_ERROR);
                        }
                        $task['relationResourceIds'] = implode(',', $res);
                    }
                }
                $source .= '</SourceInfo>';
                if (strpos($source, 'SourceFile') !== FALSE) {
                    $task['files'] = array(
                        array(
                            'id'     => $id,
                            'source' => $source
                        )
                    );//文稿如有图片和视频，以此文件形式推送。
                }
                file_put_contents('vod.txt', var_export($task, 1));
                $taskList['taskList'][0] = $task;
                $sendata                 = json_encode($taskList);
                $res                     = $this->postData(SEND_URL, $sendata);
                file_put_contents('sss444.txt', var_export($res, 1));//判断文稿是否推送成功
                //成功则更改对应数据库的outpush_id值，返回对应id以及outpush_id
            }
            if (strpos($res, 'false') === FALSE && strpos($res, 'true')) {
                $change = $this->changeOutpushState('dev_news', 'article', $ids);
                if (! $change) {
                    $this->errorOutput(MYSQL_WRONG);
                }
                $this->addItem('success');
                $this->output();
            } else {
                $this->errorOutput(SQL_FAILED);
            }

            //图集推送
        } elseif ($this->input['pushType'] == "tuji") {
            include_once(ROOT_DIR . 'lib/class/tuji.class.php');
            $tuji = new tuji();
            $res  = $tuji->details($ids);
            if (! $res) {
                $this->errorOutput(NOSOURCE);
            }
            foreach ($res as $vo) {
                $task['id']              = md5('hoge' . rand(1, 1000000000) . time()); //必填 任务ID，最长不超过36个字符
                $task['name']            = $vo['title']; //必填 任务名称
                $task['resourceId']      = 'HOGE' . $vo['id']; //必填 资源ID
                $task['resourceType']    = 2; //必填 资源类型 0：视音频资源 1：纯音频资源 3：图片资源 4：文档资源
                $task['sourceType']      = 4; //必填 1:信号采集 2:导入 3:esb 4:webservice/rest 5:dirwatch 6：launcher 7：web 8：收录 9:quickcut
                $task['sourceSystem']    = SYSMARK_TUJI;//必填 来源于厚建M2O-视频填：hoge；来源于厚建M2O-文稿填：hoge-news
                $task['ccid']            = '图集类';//可选 编目类
                $task['programCode']     = '';//可选 节目代码
                $task['programType']     = 1;//可选 节目类型 0 节目 1 素材
                $task['hdFlag']          = ''; //可选 高标清标识 0标清 1高清
                $task['afd']             = '';//可选 AFD值（0-15）
                $task['creatorId']       = '';//可选 创建人ID
                $task['creatorName']     = '';//可选 创建人名称
                $task['segmentInfo']     = '';//可选 Segment信息
                $task['memo']            = '';//可选 描述
                $task['priority']        = 0;//可选 优先级
                $task['inpoint']         = '0';//可选 入点，单位帧
                $task['outpoint']        = '0';//可选 出点，单位帧
                $task['duration']        = '0';//可选 时长，单位帧
                $taskList['taskList'][0] = $task;
                if (isset($vo['pics']) && ! empty($vo['pics'])) {
                    $img = array();
                    $i   = 1;
                    foreach ($vo['pics'] as $n => $p) {
                        $img['id']                = md5('hoge' . rand(1, 1000000000) . time());
                        $img['name']              = $vo['title'] . '-图片 ' . $i;
                        $img['resourceId']        = $n;
                        $img['parentId']          = 'HOGE' . $vo['id'];
                        $img['resourceType']      = 3; //必填 资源类型 0：视音频资源 1：纯音频资源 3：图片资源 4：文档资源
                        $img['sourceType']        = 4; //必填 1:信号采集 2:导入 3:esb 4:webservice/rest 5:dirwatch 6：launcher 7：web 8：收录 9:quickcut
                        $img['sourceSystem']      = SYSMARK_PICS;//必填 来源于厚建M2O-视频填：hoge；来源于厚建M2O-文稿填：hoge-news
                        $img['ccid']              = '图片类';//可选 编目类
                        $img['programCode']       = '';//可选 节目代码
                        $img['programType']       = 1;//可选 节目类型 0 节目 1 素材
                        $img['hdFlag']            = ''; //可选 高标清标识 0标清 1高清
                        $img['afd']               = '';//可选 AFD值（0-15）
                        $img['creatorId']         = '';//可选 创建人ID
                        $img['creatorName']       = '';//可选 创建人名称
                        $img['segmentInfo']       = '';//可选 Segment信息
                        $img['memo']              = '';//可选 描述
                        $img['priority']          = 0;//可选 优先级
                        $img['inpoint']           = '0';//可选 入点，单位帧
                        $img['outpoint']          = '0';//可选 出点，单位帧
                        $img['duration']          = '';//可选 时长，单位帧
                        $source                   = '<?xml version="1.0" encoding="UTF-8"?><SourceInfo><SourceFile FileType="9" Channel="0" FileName="' . $p['filename'] . '" PathID="" Path="' . $p['host'] . $p['dir'] . $p['filepath'] . '" TrimIn="" TrimOut="" RelativePath="" MD5Code="" StreamMediaInfoID="" />';
                        $img['files']             = array(
                            array(
                                'source' => $source,
                            ),
                        );
                        $taskList['taskList'][$i] = $img;
                        $i++;
                    }
                } else {
                    $this->errorOutput(NOPICS);
                }
                $sendata = json_encode($taskList);
                $res     = $this->postData(SEND_URL, $sendata);
                file_put_contents('sss.txt', $res);//此处判断是否推送成功
                $return[] = 'HOGE' . $vo["id"];
            }
            if ($this->input['inner']) {
                return $return;
            } else {
                if (strpos($res, 'false') === FALSE && strpos($res, 'true')) {
                    $change = $this->changeOutpushState('dev_tuji', 'tuji', $ids);
                    if (! $change) {
                        $this->errorOutput(MYSQL_WRONG);
                    }
                    $this->addItem('success');
                    $this->output();
                } else {
                    $this->errorOutput(SQL_FAILED);
                }
            }
        }
    }

    public function postData($url, $data)
    {
        $ch       = curl_init();
        $timeout  = 300;
        $header[] = 'content-type:text/html';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $res = curl_exec($ch);
        curl_close($ch);

        return $res;
    }

    public function changeOutpushState($database, $table, $ids)
    {
        global $gDBconfig;
        include_once(ROOT_DIR . 'lib/db/db_mysql.class.php');
        $db           = new db();
        $db->host     = $gDBconfig['host'];
        $db->user     = $gDBconfig['user'];
        $db->pass     = $gDBconfig['pass'];
        $db->database = $database;
        $db->charset  = $gDBconfig['charset'];
        $link         = $db->connect($db->host, $db->user, $db->pass, $db->database, $db->charset);
        $sql          = 'UPDATE ' . DB_PREFIX . $table . ' SET outpush_id=1 WHERE id in (' . $ids . ')';

        return (mysql_query($sql, $link));
    }

    public function update()
    {
    }

    public function sort()
    {
    }

    public function publish()
    {
    }

    public function delete()
    {
    }

    public function audit()
    {
    }

}

if ($_INPUT['a']) {
    $action = $_INPUT['a'];
} else {
    $action = 'create';
}
$obj = new outpush_action();
$obj->$action();