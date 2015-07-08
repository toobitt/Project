<?php
class Homepage extends BaseCore
{
    public function index()
    {
        $data = array('nini'=>'wowo');
        $this->load->view('index.php',$data);
    }

    public function list_pic()
    {
        $list = array(1,2,3,4,5,6,7,8);
        $data['list'] = $list;
        $this->load->view('list.php',$data);
    }
}