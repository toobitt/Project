<?php
/**
 * 文件缓存类.
 * User: wangleyuan
 * Date: 14/10/23
 * Time: 上午10:22
 */

class cache_file {

    /**
     * 默认配置
     */
    protected $settings = array(
        'suf'        => '.cache.php',   //缓存文件后缀
        'cache_type'       => 'serialize',        //缓存格式   array 数组 serialize串行化
        'cache_path' => CACHE_DIR, //缓存文件路径
        'expire'     => 3600,     //缓存时间 单位秒
    );

    /**
     * 构造函数
     * @param array $setting
     */
    public function __construct($settings = array())
    {
        if($settings) {
            $this->settings = array_merge($this->settings, $settings);
        }
        if (!in_array($this->settings['cache_type'], array('array', 'serialize')))
        {
            $this->settings['cache_type'] = 'serialize';
        }
    }


    /**
     * 获取缓存文件内容  文件不存在或过期后返回false
     * @param $id  unique key
     * @pamram  成功返回data 失败返回false
     */
    public function get($id)
    {
        $filepath = rtrim($this->settings['cache_path'], '/') . '/' .  substr($id,0,2) . '/';
        $filename = $id . $this->settings['suf'];
        if (!file_exists($filepath . $filename))
        {
            return false;
        }

        if ($this->settings['cache_type'] == 'array')
        {
            $data = @include($filepath . $filename);
        }
        else if($this->settings['cache_type'] == 'serialize')
        {
            $data = unserialize(file_get_contents($filepath . $filename));
        }
        if ( time() > ($data['time'] + $data['expire']) )
        {
            unlink($filepath . $filename);
            return false;
        }
        return $data['data'];
    }

    /**
     * 写入缓存
     * @param $id  unique key
     * @param $data  写入的数据
     * @param string $expire  过期时间
     */
    public function set($id, $data, $expire = '')
    {
        $filepath = rtrim($this->settings['cache_path'], '/') . '/' .  substr($id,0,2) . '/';
        $filename = $id . $this->settings['suf'];
        if (!is_dir($filepath))
        {
            mkdir($filepath, 0777, true);
        }

        $content = array(
            'time'      => time(),
            'expire'    => $expire ? intval($expire) : intval($this->settings['expire']),
            'data'      => $data,
        );
        if ($this->settings['cache_type'] == 'array')
        {
            $content = "<?php\nreturn " . var_export($content,true) . ";\n?>";
        }
        else if($this->settings['cache_type'] == 'serialize')
        {
            $content = serialize($content);
        }
        return $this->write_file($filepath . $filename, $content);
    }

    /**
     * 删除缓存文件
     * @param $id uniquid key
     * @return bool
     */
    public function delete($id)
    {
        $filepath = rtrim($this->settings['cache_path'], '/') . '/' .  substr($id,0,2) . '/';
        $filename = $id . $this->settings['suf'];
        return @unlink($filepath . $filename);
    }

    /**
     * 清空所有缓存文件
     * @return bool
     */
    public function clean()
    {
        return $this->delete_files($this->settings['cache_path']);
    }

    /**
     * 获取文件的meta信息
     * @param $id
     * @return array
     */
    public function get_metadata($id)
    {
        $filepath = rtrim($this->settings['cache_path'], '/') . '/' .  substr($id,0,2) . '/';
        $filename = $id . $this->settings['suf'];
        if(!file_exists($filepath . $filename))
        {
            return false;
        }

        $ret = array();
        $ret['filename'] = $filename;
        $ret['filepath'] = $filepath;
        $ret['filectime'] = filectime($filepath . $filename);
        $ret['filemtime'] = filemtime($filepath . $filename);
        $ret['filezie'] = filesize($filepath . $filename);
        return $ret;
    }


    /**
     * 写文件
     * @param $file
     * @param $data
     * @param string $mode
     * @return bool
     */
    private function write_file($file, $data, $mode = 'wb')
    {
        if ( !$fp = @fopen($file, $mode) )
        {
            return false;
        }
        flock($fp, LOCK_EX);
        fwrite($fp, $data);
        flock($fp, LOCK_UN);
        fclose($fp);
        return true;
    }

    /**
     * 删除文件
     * @param $path
     * @param bool $del_dir
     * @param int $level
     * @return bool
     */
    private function delete_files($path, $del_dir = false, $level = 0)
    {
        $path = rtrim($path, '/');
        if ( !$cur_dir = @opendir($path) )
        {
            return false;
        }

        while ( ($filename = @readdir($cur_dir)) != false)
        {
            if ($filename != '.' && $filename != '../')
            {
                if (is_dir($path . './' . $filename))
                {
                    $this->delete_files($path . './' . $filename, $del_dir, $level + 1);
                }
                else
                {
                    @unlink($path . $filename);
                }
            }
        }
        @closedir($cur_dir);
        if ($del_dir == true && $level > 0)
        {
            @rmdir($path);
        }
        return true;
    }

}
