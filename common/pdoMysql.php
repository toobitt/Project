<?php
/*
 * @pdo对于mysql的操作。
 */
define('HOST','127.0.0.1');
define('DBNAME','sign');
define('USER','root');
define('PWD','1234');
define('LONGLINK',false);
class pdoMysql
{
    function connect()
    {
        try
        {
            $pdo = new PDO("mysql:host=".HOST.";dbname=".DBNAME,USER,PWD);
            $pdo->query('set names utf8');
            return $pdo;
        }catch(PDOException $e)
        {
            echo 'Failed to connect to db'.$e->getMessage().$e->getLine();
        }
    }
}