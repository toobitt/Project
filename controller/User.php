<?php

/**
 * Created by PhpStorm.
 * User: mac
 * Date: 15/7/16
 * Time: 上午11:21
 */
class User extends BaseCore {

    public function __construct()
    {
        parent::__construct();
    }

    public function regist()
    {
        if (isset($_POST['checkName']) && trim($_POST['checkName']) != '') {
            $sql = 'SELECT ID FROM sign_user WHERE name=' . "'" . $_POST['checkName'] . "'";
            $res = $this->pdo->query($sql);
            if ($res->fetch()) {
                echo 1;
                die;
            }
        } else {
            $params = array(
                'name'       => $_POST['name'],
                'password'   => md5($_POST['password']),
                'sex'        => $_POST['sex'],
                'birthday'   => strtotime($_POST['birthday']),
                'address'    => $_POST['address'],
                'registtime' => time(),
            );
            $sql    = 'INSERT INTO sign_user SET ';
            $p      = '';
            foreach ($params as $key => $vo) {
                $sql .= $p . $key . '=' . '"' . $vo . '"';
                $p = ',';
            }
            $this->pdo->exec($sql);
            echo $this->pdo->lastInsertId();
        }
    }

    public function login()
    {

    }
}