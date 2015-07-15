<?php
class Homepage extends BaseCore
{
    public function index()
    {
        $data = array('nini'=>'wowo');//头，中，尾部分别加载，单独传参。
        $this->load->view('head.php');
        $this->load->view('index.php',$data);
    }

    public function list_pic()
    {
        $this->load->view('list2.php');
    }

    public function testphp()
    {
        ShowError('SUCCESS!',BASE_URI.'/Homepage');
    }

    public function test()
    {
        $curl = $this->load->method('curl');
        echo $curl->a;
    }
}