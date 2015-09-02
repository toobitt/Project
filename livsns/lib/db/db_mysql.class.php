<?php

/* * *************************************************************************
 * LivSNS 0.1
 * (C)2004-2010 HOGE Software.
 *
 * $Id: db_mysql.class.php 45997 2015-06-02 08:30:56Z develop_tong $
 * ************************************************************************* */

class db
{

    var $querynum   = 0;
    var $link;
    var $histories;
    var $dbhost;
    var $dbuser;
    var $dbpw;
    var $dbcharset;
    var $pconnect;
    var $tablepre;
    var $time;
    var $goneaway   = 5;
    var $mErrorExit = true;

    function connect($dbhost, $dbuser, $dbpw, $dbname = '', $dbcharset = '', $pconnect = 0, $tablepre = '', $time = 0)
    {
        $this->dbhost    = $dbhost;
        $this->dbuser    = $dbuser;
        $this->dbpw      = $dbpw;
        $this->dbname    = $dbname;
        $this->dbcharset = $dbcharset;
        $this->pconnect  = $pconnect;
        $this->tablepre  = $tablepre;
        $this->time      = $time;

        if ($pconnect)
        {
            if (!$this->link = mysql_pconnect($dbhost, $dbuser, $dbpw))
            {
                $this->halt('Can not connect to MySQL server');
            }
        }
        else
        {
            if (!$this->link = mysql_connect($dbhost, $dbuser, $dbpw))
            {
                $this->halt('Can not connect to MySQL server');
            }
        }

        if ($this->version() > '4.1')
        {
            if ($dbcharset)
            {
                mysql_query("SET character_set_connection=" . $dbcharset . ", character_set_results=" . $dbcharset . ", character_set_client=binary", $this->link);
            }

            if ($this->version() > '5.0.1')
            {
                @mysql_query("SET sql_mode=''", $this->link); //关闭严格模式
            }
        }

        if ($dbname)
        {
            mysql_select_db($dbname, $this->link);
        }
        return $this->link;
    }

    function fetch_array($query, $result_type = MYSQL_ASSOC)
    {
        return @mysql_fetch_array($query, $result_type);
    }

    function result_first($sql)
    {
        $query = $this->query($sql);
        return $this->result($query, 0);
    }

    function query_first($sql)
    {
        $query = $this->query($sql);
        return $this->fetch_array($query);
    }

    function fetch_all($sql, $id = '')
    {
        $arr   = array();
        $query = $this->query($sql);
        while ($data  = $this->fetch_array($query))
        {
            $id ? $arr[$data[$id]] = $data : $arr[]           = $data;
        }
        return $arr;
    }

    function query($sql, $type = '', $cachetime = FALSE)
    {
        //检测sql语句有无漏洞
        $this->checksql($sql);
        $func  = $type == 'UNBUFFERED' && @function_exists('mysql_unbuffered_query') ? 'mysql_unbuffered_query' : 'mysql_query';
        if (!($query = $func($sql, $this->link)) && $type != 'SILENT')
        {
            $this->halt('MySQL Query Error', $sql);
        }
        $this->querynum++;
        $this->histories[] = $sql;
        return $query;
    }

    function affected_rows()
    {
        return mysql_affected_rows($this->link);
    }

    function error()
    {
        return (($this->link) ? mysql_error($this->link) : mysql_error());
    }

    function errno()
    {
        return intval(($this->link) ? mysql_errno($this->link) : mysql_errno());
    }

    function result($query, $row)
    {
        $query = @mysql_result($query, $row);
        return $query;
    }

    function num_rows($query)
    {
        $query = mysql_num_rows($query);
        return $query;
    }

    function num_fields($query)
    {
        return mysql_num_fields($query);
    }

    function free_result($query)
    {
        return mysql_free_result($query);
    }

    function insert_id()
    {
        return ($id = mysql_insert_id($this->link)) >= 0 ? $id : $this->result($this->query("SELECT last_insert_id()"), 0);
    }

    function fetch_row($query)
    {
        $query = mysql_fetch_row($query);
        return $query;
    }

    function fetch_fields($query)
    {
        return mysql_fetch_field($query);
    }

    function version()
    {
        return mysql_get_server_info($this->link);
    }

    function close()
    {
        return mysql_close($this->link);
    }

    function halt($message = '', $sql = '')
    {
        if (!$this->mErrorExit)
        {
            return;
        }
        $error   = mysql_error();
        $errorno = mysql_errno();
        if ($errorno == 2006 && $this->goneaway-- > 0)
        {
            $this->connect($this->dbhost, $this->dbuser, $this->dbpw, $this->dbname, $this->dbcharset, $this->pconnect, $this->tablepre, $this->time);
            $this->query($sql);
        }
        else
        {
            $entersplit = "\r\n<br />";
            $tmp_info   = debug_backtrace();
            $str .= $entersplit;

            $debug_tree = "";
            $max        = count($tmp_info);
            $i          = 1;

            foreach ($tmp_info as $debug_info)
            {
                $space      = str_repeat('&nbsp;&nbsp;', $max - $i);
                $debug_tree = $entersplit . $space . $debug_info['file'] . " on line " . $debug_info['line'] . ":" . $debug_tree;
                $i++;
            }
            $str   = $entersplit . '[' . date('Y-m-d H:i:s') . ']' . $debug_tree . $str;
            $s     = '<strong>version:</strong>' . $this->version() . '<br />';
            $s     = '<strong>Error:</strong>' . $error . '<br />';
            $s .= '<strong>Errno:</strong>' . $errorno . '<br />';
            $s .= '<strong>SQL:</strong>:' . $sql;
            $trace = $str;
            if (!DEVELOP_MODE)
            {
			    $error_dir =  LOG_DIR . 'sqlError/' . date('Ymd') . '/';
				if (!is_dir($error_dir))
				{
					mkdir($error_dir, 0777, 1);
				}
				$s = str_replace('<br />', "", $s. $trace);
				file_put_contents($error_dir . 'sqlerror.log',  $s, FILE_APPEND);
                exit('SQL ERROR');
            }
            exit($s . $trace);
        }
    }

    public function insert_data($data, $table, $replace = false)
    {
        if (!$table)
        {
            return false;
        }
        if (is_array($data))
        {
            $fields = array();
            foreach ($data as $k => $v)
            {
                $fields[] = $k . "='" . $v . "'";
            }
            $fields = implode(',', $fields);
        }
        else
        {
            $fields .= $data;
        }
        $sql = $replace ? "REPLACE INTO " : "INSERT INTO ";
        $sql .= DB_PREFIX . $table . " SET " . $fields;
        $this->query($sql);
        return $this->insert_id();
    }

    public function update_data($data, $table, $where = '')
    {
        if ($table == '' or $where == '')
        {
            return false;
        }
        $where = ' WHERE ' . $where;
        $field = '';
        if (is_string($data) && $data != '')
        {
            $field = $data;
        }
        elseif (is_array($data) && count($data) > 0)
        {
            $fields = array();
            foreach ($data as $k => $v)
            {
                $fields[] = $k . "='" . $v . "'";
            }
            $field = implode(',', $fields);
        }
        else
        {
            return false;
        }
        $sql = 'UPDATE ' . DB_PREFIX . $table . ' SET ' . $field . $where;
        $this->query($sql);
        return $this->affected_rows();
    }

    //事务开始
    public function commit_begin()
    {
        $this->query("SET AUTOCOMMIT=0");
        $this->query("START TRANSACTION");
    }

    //事务提交
    public function commit_end()
    {
        $this->query("COMMIT");
        $this->query("SET AUTOCOMMIT=1");
    }

    //回滚
    public function rollback()
    {
        $this->query("ROLLBACK");
        $this->query("SET AUTOCOMMIT=1");
    }

    private function checksql($db_string)
    {
        return $db_string;
        $clean    = '';
        $error    = '';
        $old_pos  = 0;
        $pos      = -1;
        $userIP   = hg_getip();
        $sql_type = substr(ltrim($db_string), 0, 6);

        //如果是普通查询语句，直接过滤一些特殊语法
        if (strcasecmp($sql_type, "SELECT") === 0)
        {
            $notallow1 = "[^0-9a-z@\._-]{1,}(union|sleep|benchmark|load_file|outfile)[^0-9a-z@\.-]{1,}";

            //$notallow2 = "--|/\*";
            if (preg_match("/" . $notallow1 . "/i", $db_string))
            {
                $this->check_mysql_halt($userIP . "\n" . date('Y-m-d H:i:s', TIMENOW) . "\n" . $error . "\n" . $db_string. "\n");
                exit('SQL REFUSED');
            }
        }

        //完整的SQL检查
        while (TRUE)
        {
            $pos = strpos($db_string, '\'', $pos + 1);
            if ($pos === FALSE)
            {
                break;
            }
            $clean .= substr($db_string, $old_pos, $pos - $old_pos);
            while (TRUE)
            {
                $pos1 = strpos($db_string, '\'', $pos + 1);
                $pos2 = strpos($db_string, '\\', $pos + 1);
                if ($pos1 === FALSE)
                {
                    break;
                }
                elseif ($pos2 == FALSE || $pos2 > $pos1)
                {
                    $pos = $pos1;
                    break;
                }
                $pos = $pos2 + 1;
            }
            $clean .= '$s$';
            $old_pos = $pos + 1;
        }
        $clean .= substr($db_string, $old_pos);
        $clean = trim(strtolower(preg_replace(array('~\s+~s'), array(' '), $clean)));
        
        if (strpos($clean, 'union') !== FALSE && preg_match('~(^|[^a-z])union($|[^[a-z])~s', $clean) != 0)
        {
            //$fail  = TRUE;
            //$error = "union detect";
        }
        /*
        elseif (strpos($clean, '/*') > 2 || strpos($clean, '--') !== FALSE || strpos($clean, '#') !== FALSE)
        {
            $fail  = TRUE;
            $error = "comment detect";
        }
        */
        elseif (strpos($clean, 'sleep') !== FALSE && preg_match('~(^|[^a-z])sleep($|[^[a-z])~s', $clean) != 0)
        {
            $fail  = TRUE;
            $error = "slown down detect";
        }
        elseif (strpos($clean, 'benchmark') !== FALSE && preg_match('~(^|[^a-z])benchmark($|[^[a-z])~s', $clean) != 0)
        {
            $fail  = TRUE;
            $error = "slown down detect";
        }
        elseif (strpos($clean, 'load_file') !== FALSE && preg_match('~(^|[^a-z])load_file($|[^[a-z])~s', $clean) != 0)
        {
            $fail  = TRUE;
            $error = "file fun detect";
        }
        elseif (strpos($clean, 'into outfile') !== FALSE && preg_match('~(^|[^a-z])into\s+outfile($|[^[a-z])~s', $clean) != 0)
        {
            $fail  = TRUE;
            $error = "file fun detect";
        }
        /**
          //子查询
          elseif (preg_match('~\([^)]*?select~s', $clean) != 0)
          {
          $fail  = TRUE;
          $error = "sub select detect";
          }
         */
        if ($fail)
        {
            if(APP_UNIQUEID=='convenience')
        {
            file_put_contents(ROOT_PATH.'uploads/1111.txt', $db_string."\n\n",FILE_APPEND);
        }
            $this->check_mysql_halt($userIP . "\n" . date('Y-m-d H:i:s', TIMENOW) . "\n" . $error . "\n" . $db_string. "\n");
            exit('SQL REFUSED');
        }
        
        
        return $db_string;
    }

    private function check_mysql_halt($content)
    {
        $filepath = LOG_DIR . 'mysql_error/' . date('Y-m', TIMENOW) . '/';
        $filename = date('Y-m-d', TIMENOW) . '.txt';
        hg_mkdir($filepath);
        file_put_contents($filepath . '/' . $filename, $content,FILE_APPEND);
        return true;
    }

}

?>