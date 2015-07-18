<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 15/7/16
 * Time: 上午11:21
 */
class User extends BaseCore
{
    public function __construct()
    {
        parent::__construct();
    }
    public function regist ()
    {
        $params = array(
            'name' => $_POST['name'],
            'password' => md5($_POST['password']),
            'sex' => $_POST['sex'],
            'birthday' => strtotime($_POST['birthday']),
            'address' => $_POST['address'],
            'registtime' => time(),
        );
        $sql = 'INSERT INTO sign_user SET ';
        $p = '';
        foreach($params as $key => $vo)
        {
            $sql .= $p . $key . '=' . '"' . $vo . '"';
            $p = ',';
        }
        $re = $this->pdo->exec($sql);
        if($re){
            unset($params['password']);
            print_r($params);
        }
    }
}