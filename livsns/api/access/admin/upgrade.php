<?php
/**
 * Created by PhpStorm.
 * User: wangleyuan
 * Date: 14/10/29
 * Time: 下午2:07
 */

require('global.php');
define('MOD_UNIQUEID','access');

class UpgradeCompatible extends appCommonFrm
{
    function __construct(){
        parent::__construct();
    }

    function __destruct()
    {
        parent::__destruct();
    }

    public function show()
    {
        set_time_limit(0);
        ob_start();
        //同步record_201410表结构
        $this->sync_table_struct();
        //同步record_201410表结构

        //从num表把title等信息同步到record_表
        $this->sync_data();
        //从num表把title等信息同步到record_表
    }


    function sync_data()
    {
        hg_flushMsg('开始同步数据');
        $sql = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "nums";
        $total = $this->db->query_first($sql);
        $total = $total['total'];

        $offset = 0;
        $count = 10000;
        //查询存在的分表
        include_once(CUR_CONF_PATH . 'lib/cache.class.php');
        $cache = new CacheFile();
        while ($offset < $total)
        {
            hg_flushMsg('开始同步第' . $offset . '到' . ($offset + $count) . '条');
            $table = $cache->get_cache('access_table_name');
            $table = convert_table_name($table);
            $table_str = '';
            if($table)
            {
                $table_str = implode(',', $table);
            }
            $sql = "ALTER TABLE " . DB_PREFIX . "merge UNION(".$table_str.")";
            $this->db->query($sql);
            //查询存在的分表
            $sql = "SELECT app_bundle, module_bundle, cid, title FROM " . DB_PREFIX . "nums WHERE 1 ORDER BY id ASC LIMIT " . $offset .", " . $count;
            $q = $this->db->query($sql);
            while ( ($row = $this->db->fetch_array($q)) != false )
            {
                $sql = "UPDATE " . DB_PREFIX . "merge SET title = '" . $row['title'] . "'
                        WHERE app_bundle='".$row['app_bundle']."' AND module_bundle = '".$row['module_bundle']."' AND cid='".$row['cid']."'";
//                hg_flushMsg($sql);
                $this->db->query($sql);
            }

            $offset += $count;
        }
        hg_flushMsg('数据同步完成');
    }


    function sync_table_struct()
    {
        //获取liv_record表结构和索引信息
        $record_fileds = $this->getFields(DB_PREFIX . 'record');
        $record_index = $this->getIndex(DB_PREFIX . 'record');
        //获取liv_record表结构和索引信息

        hg_flushMsg('开始更新表结构');
        $sql = "SHOW tables";
        $q = $this->db->query($sql);
        while( ($row = $this->db->fetch_array($q)) !== false )
        {
            list($a, $table_name) = each($row);
            if (preg_match('/^'.DB_PREFIX.'record_[0-9]*$/', $table_name))
            {
                hg_flushMsg("更新表" . $table_name);
                $fields = $this->getFields($table_name);
                $index = $this->getIndex($table_name);

                foreach ((array)$record_fileds as $k => $v)
                {
                    $altersql = array();
                    if (!$fields[$k])
                    {
                        if ($v['Null'] == 'NO')
                        {
                            $null = ' NOT NULL';
                        }
                        else
                        {
                            $null = ' NULL';
                        }
                        if ($v['Default'])
                        {
                            $default = " DEFAULT '{$v['Default']}'";
                        }
                        else
                        {
                            $default = '';
                        }
                        if ($v['Comment'])
                        {
                            $comment = " COMMENT '{$v['Comment']}'";
                        }
                        else
                        {
                            $comment = '';
                        }
                        $altersql[] = " ADD `$k` {$v['Type']}{$null} {$v['Extra']}{$default}{$comment}";
                    }
                    else
                    {
                        $cur = $record_fileds[$k];

                        if ($v['Null'] == 'NO')
                        {
                            $null = ' NOT NULL';
                        }
                        else
                        {
                            $null = ' NULL';
                        }
                        if ($v['Default'])
                        {
                            $default = " DEFAULT '{$v['Default']}'";
                        }
                        else
                        {
                            $default = '';
                        }
                        if ($v['Comment'])
                        {
                            $comment = " COMMENT '{$v['Comment']}'";
                        }
                        else
                        {
                            $comment = '';
                        }
                        if ($v['Type'] != $cur['Type'] || $v['Default'] != $cur['Default'])
                        {
                            $altersql[] = " CHANGE `$k` `$k` {$v['Type']}{$null} {$v['Extra']}{$default}{$comment}";
                        }
                    }

                    if ($altersql)
                    {
                        $altersql = 'ALTER TABLE ' . $table_name . ' ' . implode(',', $altersql);
                        hg_flushMsg($altersql);
                        $this->db->query($altersql);
                    }
                }

                //处理增加或修改的索引
                if ($record_index)
                {
                    foreach ($record_index AS $unique => $ind)
                    {
                        if (!$ind)
                        {
                            continue;
                        }

                        if (!$unique)
                        {
                            $typ = 'UNIQUE';
                        }
                        else
                        {
                            $typ = 'INDEX';
                        }
                        foreach ($ind AS $pk => $f)
                        {
                            if ($pk == 'PRIMARY')
                            {
                                continue;
                            }
                            $curind = $index[$unique][$pk];
                            if (!$curind)
                            {
                                $altersql = 'ALTER TABLE  ' . $table_name . ' ADD ' . $typ . ' (' . implode(',', $f) . ')';
                                hg_flushMsg($altersql);
                                $this->db->query($altersql);
                            }
                            else
                            {
                                $change = array_diff($curind, $f);
                                $change1 = array_diff($f, $curind);
                                if($change || $change1)
                                {
                                    $altersql = 'ALTER TABLE  ' . $table_name . ' DROP INDEX ' . $pk . ', ADD ' . $typ . ' (' . implode(',', $f) . ')';
                                    hg_flushMsg($altersql);
                                    $this->db->query($altersql);
                                }
                            }
                        }
                    }
                }


                //处理已删除的索引
                if ($index)
                {
                    foreach ($index AS $unique => $ind)
                    {
                        if (!$ind)
                        {
                            continue;
                        }

                        if (!$unique)
                        {
                            $typ = 'UNIQUE';
                        }
                        else
                        {
                            $typ = 'INDEX';
                        }
                        foreach ($ind AS $pk => $f)
                        {
                            if ($pk == 'PRIMARY')
                            {
                                continue;
                            }
                            $newind = $record_index[$unique][$pk];
                            if (!$newind)
                            {
                                $altersql = 'ALTER TABLE  ' . $table_name . ' DROP INDEX ' . $pk;
                                hg_flushMsg($altersql);
                                $this->db->query($altersql);
                            }
                        }
                    }
                }
                $sql = 'OPTIMIZE TABLE  ' . $table_name;
                $this->db->query($sql);
            }
        }
        hg_flushMsg('表更新完成');
    }


    /**
     * 获取表结构
     * @param $table
     * @return array
     */
    function getFields($table)
    {
        $fileds = array();
        if (!$table)
        {
            return $fileds;
        }

        $sql = "SHOW FULL columns FROM " . $table;
        $q = $this->db->query($sql);
        while (($row = $this->db->fetch_array($q)) !== false)
        {
            $fileds[$row['Field']] = $row;
        }
        return $fileds;
    }

    function getIndex($table)
    {
        $index = array();
        if (!$table)
        {
            return $index;
        }
        $sql = "SHOW  index FROM ".$table;
        $q = $this->db->query($sql);

        while ( ($row = $this->db->fetch_array($q)) !== false )
        {
            $index[$row['Non_unique']][$row['Key_name']][$row['Seq_in_index'] - 1] = $row['Column_name'];
        }
        return $index;
    }
}

$out = new UpgradeCompatible();
$action = $_INPUT['a'];
if(!method_exists($out,$action))
{
    $action = 'show';
}
$out->$action();