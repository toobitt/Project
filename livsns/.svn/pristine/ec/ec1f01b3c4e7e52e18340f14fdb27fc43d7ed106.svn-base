<?php
//先要过access_token取到用户的id
//通过用户的id取到用户的身份证号
$userid=$ret['0']['user_id'];
$idinfo=json_decode(file_get_contents(CACHE_DIR . $userid),ture);
foreach ($idinfo as $ikey => $ivalue) {
     if($ivalue['default'] == '1' ){
         $certId = $ivalue['certId'];
     }
}
foreach ($this->hg_agruments['ident'] as $hkey => $hvalue) {
        if($hvalue == 'certId'){
          $this->hg_agruments['value'][$hkey]=$certId;
        }
}
?>