<?php
/**
 * Created by livworkbench.
 * User: wangleyuan
 * Date: 14-6-16
 * Time: 下午2:38
 */
define('WITH_DB', true);
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'publishsysColumn');
require_once('./global.php');
require_once('./lib/class/curl.class.php');
class publishsysColumn extends uiBaseFrm
{
    function __construct()
    {
        parent::__construct();
        $this->publishsys = new curl($this->settings['App_publishsys']['host'],$this->settings['App_publishsys']['dir']);
    }
    function __destruct()
    {
        parent::__destruct();
    }

    //注意此处没有做limit限制 也就是在子栏目很多的情况下可能会影响加载速度
    public function show()
    {
        $hg_columns = array();
        $this->publishsys->setSubmitType('post');
        $this->publishsys->setReturnFormat('json');
        $this->publishsys->initPostData();
        $this->publishsys->addRequestData('a', 'pageNode');
        $this->publishsys->addRequestData('offset', $this->input['offset']);
        $this->publishsys->addRequestData('count', $this->input['count']);
        $this->publishsys->addRequestData('fid', $this->input['fid']);
        $columns = $this->publishsys->request('publishsys.php');
        $columns  = $columns ? $columns[0] : array();
//        print_r($columns);exit;
        echo json_encode($columns);
        exit;
    }

}
include (ROOT_PATH . 'lib/exec.php');
?>
/* End of file fetch_publishys_column.php */