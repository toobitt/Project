<?php

class Homepage extends BaseCore {

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->view('head.php');
    }

    public function list_pic()
    {
        $this->load->view('list2.php');
    }

    public function testphp()
    {
        ShowError('SUCCESS!', BASE_URI . '/Homepage');
    }

    public function test()
    {

    }

}