<?php
/**
 * HOGE DingDone Client-API
 *
 * cURL类
 *
 * @package DingDone Client-API
 * @author RDC3 - YaoJian
 * @copyright Copyright (c) 2014, HOGE CO., LTD (http://hoge.cn/)
 * @since Version 0.0.1
 */
defined('ROOT_PATH') or die('Access denied');

class CurlApi
{

    /**
     * curl资源句柄
     *
     * @var resource
     */
    private $ch = NULL;

    /**
     * 请求超时时间
     *
     * @var integer
     */
    private $timeout = 30;

    /**
     * 请求地址
     *
     * @var string
     */
    private $url = '';

    /**
     * 请求头信息
     *
     * @var array
     */
    private $headerData = array();

    /**
     * 请求数据
     *
     * @var array
     */
    private $requestData = array();

    public function __construct()
    {
        $this->ch = curl_init();
    }

    public function __destruct()
    {
        if ($this->ch !== NULL)
        {
            curl_close($this->ch);
        }
    }

    /**
     * 设置超时
     *
     * @param integer $time
     */
    public function setTimeout($time)
    {
        $this->timeout = $time;
    }

    /**
     * 设置请求地址
     *
     * @param string $url
     */
    public function setRequestUrl($url)
    {
        $this->url = $url;
    }

    /**
     * 发起GET请求
     *
     * @param array $data
     */
    public function get($data = array())
    {
        if ($data && is_array($data))
        {
            $params = http_build_query($data);
            if (strpos($this->url, '?') === false)
            {
                $params = '?' . $params;
            }
            else
            {
                $params = '&' . $params;
            }
            
            $this->url .= $params;
        }
        return $this->request('get');
    }

    /**
     * 发起POST请求
     *
     * @param array $data
     */
    public function post($data = array())
    {
        if ($data && is_array($data))
        {
            $this->requestData = array_merge($this->requestData, $data);
        }
        return $this->request('post');
    }

    /**
     * 发送文件
     *
     * @param array $data
     */
    public function file($data = array())
    {
        if ($data && is_array($data))
        {
            $fileData = array();
            foreach ($data as $k=>$v)
            {
                if (is_array($v['tmp_name']))
                {
                    foreach ($v['tmp_name'] as $kk=>$vv)
                    {
                        $fileData[$k . '[' . $kk . ']'] = "@{$vv};type={$v['type'][$kk]};filename={$v['name'][$kk]}";
                    }
                }
                else
                {
                    $fileData[$k] = "@{$v['tmp_name']};type={$v['type']};filename={$v['name']}";
                }
            }
            $this->requestData = array_merge($this->requestData, $fileData);
        }
    }

    /**
     * 设置头信息
     *
     * @param array $data
     */
    public function setHeader($data = array())
    {
        if ($data && is_array($data))
        {
            $this->headerData = $data;
        }
    }

    /**
     * 发送请求
     *
     * @param string $method
     */
    private function request($method)
    {
        $options = array(
                CURLOPT_URL => $this->url, 
                CURLOPT_RETURNTRANSFER => TRUE, 
                CURLOPT_TIMEOUT => $this->timeout);
        if ($this->headerData)
        {
            $options[CURLOPT_HTTPHEADER] = $this->headerData;
        }
        if ($method === 'post' && $this->requestData)
        {
            $options[CURLOPT_POST] = TRUE;
            $options[CURLOPT_POSTFIELDS] = $this->requestData;
        }
        @curl_setopt_array($this->ch, $options);
        
        $result = curl_exec($this->ch);
        
        if ($result === false)
        {
            $code = curl_errno($this->ch);
            $msg = curl_error($this->ch);
            return $this->response($msg, $code);
        }
        else
        {
            $info = curl_getinfo($this->ch);
            if ($info['http_code'] !== 200)
            {
                $msg = 'ERROR: 0000001, 请求异常，[http_code=' . $info['http_code'] . '], url=' . $info['url'];
                return $this->response($msg);
            }
            else
            {
                $result = json_decode($result, TRUE);
                if (isset($result['ErrorCode']))
                {
                    return $this->response($result['ErrorText']);
                }
                else
                {
                    return $this->response('success', 0, $result);
                }
            }
        }
    }

    /**
     * 格式化输出
     *
     * @param string $msg
     * @param integer $code
     * @param array $data
     */
    private function response($msg, $code = -1, $data = array())
    {
        $export = array(
                'code' => $code, 
                'msg' => $msg);
        if ($data) $export['data'] = $data;
        return $export;
    }
}