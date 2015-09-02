<?php

/**
 * 
 * http请求类 curl模拟
 * 
 * @author leo
 * @copyright leo
 * 
 * 2013-11-26
 * 
 */
 
 class Http {
    
     /**
     * 请求地址
     */     
      protected $mUrl = '';
      
     /**
      * 响应时间
      *
      */
      protected $mTimeout = 30;
       
      /**
       * debug模式
       */
       protected $mDebug = false;
       
      /**
       * 返回值格式
       * json xml等
       */
       protected $mFormat = 'json'; 
      
     /**
      * 代理服务器地址 
      */
      protected $mProxy = '';
     
     
     /**
      * 浏览器信息 模拟浏览器
      * 
      * $_SERVER['HTTP_USER_AGENT']
      */
      protected $mUserAgent = '';
     
     
     /**
      * http头
      * Content-type:mulitpart/form-data;boundary:dkakdfjaldf
      * Content-length:200
      */
     protected $mHeader = array();
     
     /**
      * post数据
      * 
      */
      protected $mPostFields = '';
     
     /**
      * cookie
      * 
      */
      protected $mCookie = '';
     
     
     /**
      * 构造方法
      */  
      public function __construct() {
          
      }
      
      /**
       * 析构方法
       */
      public function __destruct() {
          
      }
      
      // public function __get($key) {
//           
      // }
//       
      // public function __set($key, $value) {
//           
      // }
      
      /**
       * 发送get请求
       */
      public function get($url, $paramters = array()) {
          $reponse = $this->request($url, 'get', $paramters);
          return $reponse;
      }   
      
      /**
       * post
       */
       
       public function post($url, $paramters = array(), $mulit = false) {
           $response = $this->request($url, 'post', $paramters, $mulit);
           return $response;
       }
      
      /**
       * 通过curl发送http请求
       * @param string $url  请求地址
       * @param string $method 请求方式 post  get delete等
       * @param array $paramters   需要传递的参数
       * @param boolean $mulit 是否含有多媒体文件  图片 视频等
       */
       
      private function request($url, $method, $paramters, $mulit = false) {
          
          if (!$url) {
              return false;
          }
          $ci = curl_init();
          
          /************设置curl请求参数************/
          curl_setopt($ci,CURLOPT_TIMEOUT,$this->mTimeout); //设置超时 
          curl_setopt($ci, CURLOPT_HEADER, 0);  //不显示返回的header区域内容
          curl_setopt($ci,CURLOPT_RETURNTRANSFER,true); //获取的内容已文件流的形式返回 
          //设置代理服务器地址 
          if ($this->mProxy) {
              curl_setopt($ci, CURLOPT_PROXY, $this->mProxy);
          }
          //模拟用户浏览器
          if ($this->mUserAgent) {
              curl_setopt($ci, CURLOPT_USERAGENT, $this->mUserAgent);
          }          
          if (stripos($url, 'https://') !== false) {
             curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, 0);//不启用对认证证书来源的检查
             curl_setopt($ci, CURLOPT_SSL_VERIFYHOST,1); //从证书用检测ssl加密协议是否存在   
          }
          
          //urlencode 传参
          $mulit = (!$mulit || $mulit === 'false') ? 0 : 1;
          if(!$mulit && (is_array($paramters) || is_object($paramters))) {
              $paramters = http_build_query($paramters);
          }
          else if ($mulit){
              $paramters = $this->mulit_http_build_query($paramters);
          }          
          switch ($method) {
              case 'post':
                  curl_setopt($ci, CURLOPT_POST, true);
                  curl_setopt($ci, CURLOPT_POSTFIELDS, $paramters);
                  $this->mPostFileds = $paramters;
                  break;
              case 'get':
                   $url = $url . '?' . $paramters;
              default:
                  
                  break;
          }
          
          //设置http请求头
          curl_setopt($ci, CURLOPT_HTTPHEADER, $this->mHeader);
          curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE );

          curl_setopt($ci, CURLOPT_URL, $url);
          $reponse = curl_exec($ci);
          /*******************获取请求头信息****************************/
          $http_code = curl_getinfo($ci,CURLINFO_HTTP_CODE);
          $http_info = curl_getinfo($ci); 
          
          /*****调试模式******/
          if ($this->mDebug) {
              echo "<pre>";   
              echo "===============url=================<br/>";
              var_dump($url);
              echo "==============post fields==========<br/>";
              var_dump($this->mPostFileds);
              echo "===============request info============<br/>";
              var_dump($http_info);
              echo "================repose==============<br/>";
              var_dump($reponse);
          } 
          
          curl_close($ci);
          $func = $this->mFormat . 'ToArray';
          $reponse = $this->$func($reponse);
          return $reponse;
      }

      /**
       * 模拟form表单multipart请求
       */
      private function mulit_http_build_query($paramters) {
          if(empty($paramters)) {
              return false;
          }
          $boundary = uniqid();
          $mp_boundary = '--'.$boundary;     
          $end_mp_boundary = $mp_boundary. '--';    //结束符
          $mulit_body = '';
          
          foreach ($paramters as $key => $val) {
              if (in_array($key, array('image','pic')) && $value{0} == '@' ) {
                  $url = ltrim($val, '@');
                  $content = file_get_contents($url);
                  $array = explode(',', basename($url));
                  $filename = $array[0];   //文件名
                  
                  $mulit_body .= $mp_boundary . "\r\n";
                  $mulit_body .= 'Content-Disposition: form-data; name="' . $key . '"; filename="' . $filename . '"'. "\r\n";
                  $mulit_body .= "Content-Type: image/unknown\r\n\r\n";
                  $mulit_body .= $content . "\r\n";
                  //设置http请求头信息
                  $this->mHeader[] = 'Content-type: multipart/form-data;boundary:' . $mp_boundary;   
                  $this->mHeader[] = 'Content-length:' . strlen($mulit_body);
              }

              else {
                  $mulit_body .= $mp_boundary . "\r\n";
                  $mulit_body .= 'Content-Disposition: form-data;name="'.$key.'"';
                  $mulit_body .= $val ."\r\n";
              }
          }
          $mulit_body .= $end_mp_boundary;
          
                   
          return $mulit_body;
      }

      private function jsonToArray($data) {
          return json_decode($data,true);
      }
      
      private function xmlToArray($data) {
          return $data;
      }
 }





?>