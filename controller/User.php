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
        session_start();
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
                'name'       => trim($_POST['name']),
                'password'   => md5(trim($_POST['password'])),
                'sex'        => $_POST['sex'],
                'birthday'   => strtotime($_POST['birthday']),
                'address'    => trim($_POST['address']),
                'registtime' => time(),
            );
            $sql    = 'INSERT INTO sign_user SET ';
            $p      = '';
            foreach ($params as $key => $vo) {
                $sql .= $p . $key . '=' . '"' . $vo . '"';
                $p = ',';
            }
            if ($this->pdo->exec($sql)) {
                echo $this->pdo->lastInsertId();
            } else {
                return FALSE;
            }
        }
    }

    public function login()
    {
        if (isset($_POST['name']) && isset($_POST['pwd'])) {
            $sql = 'SELECT * FROM sign_user WHERE name=' . "'" . $_POST['name'] . "' AND password=" . "'" . md5($_POST['pwd']) . "'";
            $res = $this->pdo->query($sql);
            if ($res->fetch()) {
                echo 1;
            } else {
                $sql = 'SELECT * FROM sign_user WHERE name=' . "'" . $_POST['name'] . "'";
                $res = $this->pdo->query($sql);
                if ($res->fetch()) {
                    echo 2;
                } else {
                    echo 0;
                }
            }
        } else {
            $_SESSION['username'] = trim($_POST['username']);
            $_SESSION['password'] = md5(trim($_POST['password']));
            ShowError('LOGIN_SUCCESSFULLY', BASE_URL);
        }
    }

    public function logout()
    {
        if ($_POST['data'] && $_POST['data'] == 1) {
            $_SESSION = array();
            session_destroy();
            echo 1;
        }
    }
}