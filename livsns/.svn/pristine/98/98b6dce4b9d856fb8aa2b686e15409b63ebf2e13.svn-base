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
      * Content-type:mulitpart/form-data;boundary=dkakdfjaldf
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
      * 证书文件
      *
      */
      protected  $certFile;
     /**
      * 证书密码
      */
      protected  $certPasswd;

     /**
      * 证书类型PEM
      */
      protected $certType;


     /**
      * CA文件
      */
      protected  $caFile;

     
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
      
      public function __get($key) {
          return $this->$key;
      }

      public function __set($key, $value) {
          $this->$key = $value;
      }

     //设置证书信息
     function setCertInfo($certFile, $certPasswd, $certType="PEM") {
         $this->certFile = $certFile;
         $this->certPasswd = $certPasswd;
         $this->certType = $certType;
     }
      
      /**
       * 发送get请求
       */
      public function get($url, $paramters = array()) {
          $reponse = $this->request($url, 'get', $paramters);
          return $reponse;
      }   
      
      /**
       * post
       * 
       * @param $url string 请求api地址
       * @param $paramters array  请求body部分
       * @param $mulit boole body中是否需要上传文件 
       *        
       *        $paramters = array(
       *            'a'       => 'upload',
       *            'file'    => "@".$_FILES['file']['tmp_name'] . ';' . $_FILES['file']['type'] . ';' . urlencode($_FILES['file']['name']),
       *            'file'    => '@http://mat1.gtimg.com/www/images/qq2012/qqlogo_2x.png',    
       *        )
       *
       *        
       *        文件类型value值格式  @文件地址;mine类型;文件名    
       *        @和文件地址必填
       *        mine类型、文件名是可选字段
       */       
       public function post($url, $paramters = array(), $mulit = false) {
           $response = $this->request($url, 'post', $paramters, $mulit);
           return $response;
       }

       /**
       * delete
       */
       
       public function delete($url, $paramters = array()) {
           $response = $this->request($url, 'delete', $paramters);
           return $response;
       }      

      
      /**
       * 通过curl发送http请求
       * @param string $url  请求地址
       * @param string $method 请求方式 post  get delete等
       * @param array $paramters   需要传递的参数
       * @param boolean $mulit 是否含有多媒体文件  图片 视频等
       */
       
      public function request($url, $method, $paramters, $mulit = false) {

          if (!$url) {
              return false;
          }
          $cl = curl_init();
          
          /************设置curl请求参数************/
          curl_setopt($cl,CURLOPT_TIMEOUT,$this->mTimeout); //设置超时
          curl_setopt($cl, CURLOPT_HEADER, 0);  //不显示返回的header区域内容
          curl_setopt($cl,CURLOPT_RETURNTRANSFER,true); //获取的内容已文件流的形式返回
          //设置代理服务器地址 
          if ($this->mProxy) {
              curl_setopt($cl, CURLOPT_PROXY, $this->mProxy);
          }
          //模拟用户浏览器
          if ($this->mUserAgent) {
              curl_setopt($cl, CURLOPT_USERAGENT, $this->mUserAgent);
          }


          //设置证书信息
          if($this->certFile != "")
          {
              curl_setopt($cl, CURLOPT_SSLCERT, $this->certFile);
              curl_setopt($cl, CURLOPT_SSLCERTPASSWD, $this->certPasswd);
              curl_setopt($cl, CURLOPT_SSLCERTTYPE, $this->certType);
          }

          //设置CA
          if($this->caFile != "") {
              // 对认证证书来源的检查，0表示阻止对证书的合法性的检查。1需要设置CURLOPT_CAINFO
              curl_setopt($cl, CURLOPT_SSL_VERIFYPEER, 1);
              curl_setopt($cl, CURLOPT_CAINFO, $this->caFile);

          } else {
              if (stripos($url, 'https://') !== false) {
                 curl_setopt($cl, CURLOPT_SSL_VERIFYPEER, 1);//不启用对认证证书来源的检查
                 curl_setopt($cl, CURLOPT_SSL_VERIFYHOST, 1); //从证书用检测ssl加密协议是否存在
              }
              else {
                // 对认证证书来源的检查，0表示阻止对证书的合法性的检查。1需要设置CURLOPT_CAINFO
                 curl_setopt($cl, CURLOPT_SSL_VERIFYPEER, 0);
                  curl_setopt($cl, CURLOPT_SSL_VERIFYHOST, 0); //不从证书用检测ssl加密协议是否存在
              }

          }

//          if (stripos($url, 'https://') !== false) {
//             curl_setopt($cl, CURLOPT_SSL_VERIFYPEER, 0);//不启用对认证证书来源的检查
//             curl_setopt($cl, CURLOPT_SSL_VERIFYHOST, 1); //从证书用检测ssl加密协议是否存在
//          }


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
                  curl_setopt($cl, CURLOPT_POST, true);
                  curl_setopt($cl, CURLOPT_POSTFIELDS, $paramters);
                  $this->mPostFileds = $paramters;
                  break;
              case 'get':
                  if($paramters)
                  {
                      $url = $url . '?' . $paramters;
                  }
              case 'delete':
                    curl_setopt($cl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                    if (!empty($paramters)) {
                      $url = $url . '?' . $paramters;
                    }                     
              default:
                  break;
          }
          
          //设置http请求头
          curl_setopt($cl, CURLOPT_HTTPHEADER, $this->mHeader);
          curl_setopt($cl, CURLINFO_HEADER_OUT, TRUE );

          curl_setopt($cl, CURLOPT_URL, $url);
          $reponse = curl_exec($cl);
          /*******************获取请求头信息****************************/
          $http_code = curl_getinfo($cl,CURLINFO_HTTP_CODE);
          $http_info = curl_getinfo($cl);
          
          /*****调试模式******/
          if ($this->mDebug) {
              echo "<pre>";   
              echo "===============url=================<br/>";
              var_dump($url);
              echo "===============header=================<br/>";
              var_dump($this->mHeader);              
              echo "==============post fields==========<br/>";
              var_dump($this->mPostFileds);
              echo "===============request info============<br/>";
              var_dump($http_info);
              echo "================repose==============<br/>";
              var_dump($reponse);
          } 
          
          curl_close($cl);
          $func = $this->mFormat . 'ToArray';
          $reponse = $this->$func($reponse);
          return $reponse;
      }

      /**
       * 模拟form表单multipart请求
       */
      public function mulit_http_build_query($paramters) {          
          if(empty($paramters)) {
              return false;
          }

          $boundary = uniqid('------------------');
          $mp_boundary = '--'.$boundary;     
          $end_mp_boundary = $mp_boundary. '--';    //结束符
          $mulit_body = '';
          
          uksort($paramters, 'strcmp');
          foreach ($paramters as $key => $val) {
              //附件地址格式 @地址;mine类型;文件名  mine类型 和文件名可选
              if ($val{0} == '@') {
                  $val = ltrim($val, '@');
                  list($url, $minetype, $filename) = explode(";", $val);
                  $content = file_get_contents($url);
                  $array = explode(',', basename($url));
                  $filename = $filename ? $filename : $array[0]; //文件名
                  if (!$minetype) {
                    $minetype = "application/octet-stream";
                  }
                  
                  $mulit_body .= $mp_boundary . "\r\n";
                  $mulit_body .= 'Content-Disposition: form-data; name="' . $key . '"; filename="' . $filename . '"'. "\r\n";
                  $mulit_body .= 'Content-type: ' . $minetype . "\r\n\r\n";
                  $mulit_body .= $content . "\r\n";
              }

              else {
                  $mulit_body .= $mp_boundary . "\r\n";
                  $mulit_body .= 'Content-Disposition: form-data; name="' . $key . "\"\r\n\r\n";
                  $mulit_body .= $val . "\r\n";
              }
          }
          $mulit_body .= $end_mp_boundary;
          
          //设置http请求头信息
          $this->mHeader[] = "Content-Type: multipart/form-data; boundary=". $boundary;    
          $this->mHeader[] = 'Content-Length:' . strlen($mulit_body);         
          return $mulit_body;
      }


      private function jsonToArray($data) {
          $ret = json_decode($data,true);
          if (is_array($ret))
          {
              return $ret;
          }
          else
          {
            return $data;
          }
      }
      
      private function xmlToArray($data) {
          return $data;
      }

     private function strToArray($data)
     {
         return $data;
//         parse_str($data, $arr);
//         return $arr;
     }
 }





?>