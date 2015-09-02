<?php

class Cache
{

    public $error_message = '';
    protected $dir = 'default';
    private $file_name;
    private $file_suffix = '.php';
    private $dir_depth = 1;
    private $dir_name_length = 2;

    public function __construct()
    {
        
    }

    public function initialize($dir, $file_suffix = '', $dir_depth = '', $dir_name_length = '')
    {
        if ($dir)
        {
            $this->dir = $dir;
        }
        if ($file_suffix)
        {
            $this->file_suffix = $file_suffix;
        }
        if ($dir_depth)
        {
            $this->dir_depth = $dir_depth;
        }

        if (!$this->dir_isvalid($this->dir))
        {
            die($this->tip_message); //创建目录失败
        }

        if ($dir_name_length)
        {
            $this->dir_name_length = $dir_name_length;
        }
    }

    public function set($cache_key, $cache_value, $life_time = 1800)
    {
        $cache_data['data']      = $cache_value;
        $cache_data['life_time'] = $life_time;
        $put_result = @file_put_contents($this->get_file_name($cache_key), serialize($cache_data));
        
        if ($put_result!=='false')
        {
            return true;
        }
        else
        {
            return '写入缓存失败.';
        }
    }

    public function get($cache_key, $need_check_time = false)
    {
        $file_dir = $this->get_file_name($cache_key, false);
        if (!file_exists($file_dir))
        {
            return 'no_file_dir';
        }
        $data = unserialize(file_get_contents($file_dir));
        if ($need_check_time)
        {
            if (!$this->check_isvalid($data['life_time']))
            {
                return 'no_file_dir';
            }
        }
        return $data['data'];
    }

    public function delete($cache_key, $deletes = false)
    {
        if ($deletes)
        {
            $data = explode(',', $cache_key);
        }
        else
        {
            $data[] = $cache_key;
        }
        foreach ($data as $v)
        {
            $file_dir = $this->get_file_name($v, false);
            if (file_exists($file_dir))
            {
                @unlink($file_dir);
            }
        }
    }

    public function flush()
    {
        $this->delete_file($this->dir);
    }

    public function auto_delete_expired_file()
    {
        $this->delete_file($this->dir, false);
    }

    private function dir_isvalid($dir)
    {
        if (is_dir($dir))
            return true;
        else
        {
            @mkdir($dir, 0777);
        }
        return true;
    }

    private function check_isvalid($expired_time = 0)
    {
        if (!(@$mtime = filemtime($this->file_name)))
        {
            return false;
        }
        if (time() - $mtime > $expired_time)
        {
            return false;
        }
        return true;
    }

    private function get_file_name($key, $is_mk_dir = true)
    {
        $code      = md5($key);
        $this->dir = rtrim($this->dir, '/') . '/';
        for ($i = 0, $j = 0; $j < $this->dir_depth; $j++, $i+=$this->dir_name_length)
        {
            for ($z = 0; $z < $this->dir_name_length; $z++)
            {
                $this->dir .= $code{$i + $z};
            }
            if (!file_exists($this->dir) && $is_mk_dir)
            {
                @mkdir($this->dir, 0777);
            }
            $this->dir .= '/';
        }

        $this->file_name = $this->dir . $code . $this->file_suffix;
        return $this->file_name;
    }

    protected function delete_file($dir, $mode = true)
    {
        $dh   = opendir($dir);
        while ($file = readdir($dh))
        {
            if ($file != "." && $file != "..")
            {
                $fullpath = $dir . "/" . $file;
                if (!is_dir($fullpath))
                {
                    if ($mode)
                    {
                        unlink($fullpath);
                    }
                    else
                    {
                        $this->file_name = $fullpath;
                        if (!$this->get_isvalid_by_path($fullpath))
                            unlink($fullpath);
                    }
                }
                else
                {
                    delete_file($fullpath, $mode);
                }
            }
        }
        closedir($dh);
    }

    private function get_isvalid_by_path($path)
    {
        $data = unserialize(file_get_contents($path));
        return $this->check_isvalid($data['life_time']);
    }

}

?>