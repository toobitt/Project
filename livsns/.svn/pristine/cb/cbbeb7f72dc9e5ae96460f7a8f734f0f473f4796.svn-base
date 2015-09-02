<?php
require_once('./global.php');
require_once CUR_CONF_PATH.'lib/staff.class.php';
define('MOD_UNIQUEID','staff');//模块标识
require_once CUR_CONF_PATH.'core/imagick.class.php';
require_once ROOT_PATH . 'lib/class/material.class.php';
require_once CUR_CONF_PATH.'core/download.class.php';
class staff_updateApi extends adminUpdateBase
{   
    public function __construct()
    {
        parent::__construct();
        $this->staff = new staff();
        $this->watermark = new ImageMagick();
        $this->material = new material();
    }
    public function __destruct()
    {
        parent::__destruct();
    }
    public function create()
    {
        $data = array(
            'surname'=>addslashes(trim($this->input['surname'])),
            'name'=>addslashes(trim($this->input['name'])),
            'english_name'=>addslashes(trim($this->input['english_name'])),
            'number'=>addslashes(trim($this->input['number'])),
            'sex'=>intval($this->input['sex']),
            'native_place'=>addslashes(trim($this->input['native_place'])),
            'nation'=>addslashes(trim($this->input['nation'])),
            'political_status'=>addslashes(trim($this->input['political_status'])),
            'is_married'=>intval($this->input['married']),
            'company'=>addslashes(trim($this->settings['staff_infor']['company'])),
            'department_id'=>intval($this->input['department_id']),
            'position'=>addslashes(trim($this->input['position'])),
            'en_position'=>addslashes(trim($this->input['en_position'])),
            'degree'=>intval($this->input['degree']),
            'english_level'=>addslashes(trim($this->input['english_level'])),
            'tel'=>addslashes(trim($this->settings['staff_infor']['tel'])),
            'ext_num'=>addslashes(trim($this->input['ext_num'])),
            'mobile'=>addslashes(trim($this->input['mobile'])),
            'email'=>addslashes(trim($this->input['email'])),
            'address'=>addslashes(trim($this->input['address'])),
            'fax'=>addslashes(trim($this->input['fax'])),
            'qq'=>addslashes(trim($this->input['qq'])),
            'company_addr'=>addslashes(trim($this->settings['staff_infor']['company_addr'])),
            'en_company_addr'=>addslashes(trim($this->settings['staff_infor']['en_company_addr'])),
            'web'=>addslashes(trim($this->settings['staff_infor']['web'])),
            'create_time'=>TIMENOW,
            'update_time'=>TIMENOW,
            'user_name'=>$this->user['user_name'],
            'ip'=>$this->user['ip'],
        );
        if (!$data['name'] || !$data['surname'])
        {
            $this->errorOutput("姓名不能为空");
        }
        if ($_FILES)
        {
            if ($_FILES['Filedata'])
            {
                $material = array();
                $avatar = array();
                $material = $this->staff->avatar($_FILES, '');
                if (!empty($material))
                {
                    $avatar = array(
                        'host'=>$material['host'],
                        'dir'=>$material['dir'],
                        'filepath'=>$material['filepath'],
                        'filename'=>$material['filename'],
                    );
                    $data['avatar'] = addslashes(serialize($avatar));
                }
            }
        }
        $ret = $this->staff->create($data,'staff');
        if (!$ret['id'])
        {
            $this->errorOutput('数据库错误');
        }
        $skills = addslashes($this->input['skills']);
        $this->staff->storedIntoDB(array('skills'=>$skills,'id'=>$ret['id']), 'staff_skills');
        $education = addslashes($this->input['education']);
        $this->staff->storedIntoDB(array('education'=>$education,'id'=>$ret['id']), 'staff_education');
        $experience = addslashes($this->input['experience']);
        $this->staff->storedIntoDB(array('experience'=>$experience,'id'=>$ret['id']), 'staff_work_experience');
        $data['id']= $ret['id'];
        $data['skills']= $skills;
        $data['education']= $education;
        $data['experience']= $experience;
        $this->addItem($data);
        $this->output();                
    }
    public function update()
    {
        $id = intval($this->input['id']);
        if (!$id)
        {
            $this->errorOutput(NOID);
        }
        $data = array(
            'surname'=>addslashes(trim($this->input['surname'])),
            'name'=>addslashes(trim($this->input['name'])),
            'english_name'=>addslashes(trim($this->input['english_name'])),
            'number'=>addslashes(trim($this->input['number'])),
            'sex'=>intval($this->input['sex']),
            'native_place'=>addslashes(trim($this->input['native_place'])),
            'nation'=>addslashes(trim($this->input['nation'])),
            'political_status'=>addslashes(trim($this->input['political_status'])),
            'is_married'=>intval($this->input['married']),
            'company'=>addslashes(trim($this->settings['staff_infor']['company'])),
            'department_id'=>intval($this->input['department_id']),
            'position'=>addslashes(trim($this->input['position'])),
            'en_position'=>addslashes(trim($this->input['en_position'])),
            'degree'=>intval($this->input['degree']),
            'english_level'=>addslashes(trim($this->input['english_level'])),
            'tel'=>addslashes(trim($this->settings['staff_infor']['tel'])),
            'ext_num'=>addslashes(trim($this->input['ext_num'])),
            'mobile'=>addslashes(trim($this->input['mobile'])),
            'email'=>addslashes(trim($this->input['email'])),
            'address'=>addslashes(trim($this->input['address'])),
            'fax'=>addslashes(trim($this->input['fax'])),
            'qq'=>addslashes(trim($this->input['qq'])),
            'company_addr'=>addslashes(trim($this->settings['staff_infor']['company_addr'])),
            'en_company_addr'=>addslashes(trim($this->settings['staff_infor']['en_company_addr'])),
            'web'=>addslashes(trim($this->settings['staff_infor']['web'])),
            'update_time'=>TIMENOW,
            'user_name'=>$this->user['user_name'],
            'ip'=>$this->user['ip'],
        );
        if (!$data['name'] || !$data['surname'])
        {
            $this->errorOutput("姓名不能为空");
        }
        if ($this->input['delete_avatar'])
        {
            $data['avatar'] = '';
        }
        if ($_FILES)
        {
            if ($_FILES['Filedata'])
            {
                $material = array();
                $avatar = array();
                $material = $this->staff->avatar($_FILES, '');
                if (!empty($material))
                {
                    $avatar = array(
                        'host'=>$material['host'],
                        'dir'=>$material['dir'],
                        'filepath'=>$material['filepath'],
                        'filename'=>$material['filename'],
                    );
                    $data['avatar'] = addslashes(serialize($avatar));
                }
            }
        }
        $ret = $this->staff->update($data,'staff',$id);
        $skills = addslashes($this->input['skills']);
        $this->staff->storedIntoDB(array('skills'=>$skills,'id'=>$id), 'staff_skills');
        $education = addslashes($this->input['education']);
        $this->staff->storedIntoDB(array('education'=>$education,'id'=>$id), 'staff_education');
        $experience = addslashes($this->input['experience']);
        $this->staff->storedIntoDB(array('experience'=>$experience,'id'=>$id), 'staff_work_experience');
        $data['id']= $id;
        $data['skills']= $skills;
        $data['education']= $education;
        $data['experience']= $experience;
        $this->addItem($data);
        $this->output();
            
    }
    public function delete()
    {
        $ids = $this->input['id'];
        if (!$ids)
        {
            $this->errorOutput(NOID);
        }
        $data = $this->staff->delete($ids);
        $this->addItem($data);
        $this->output();
    }
    public function audit()
    {
        $ids = $this->input['id'];
        if (!$ids)
        {
            $this->errorOutput(NOID);
        }
        $status = intval($this->input['status']);
        $data = $this->staff->audit($ids,$status);
        $this->addItem($data);
        $this->output();            
    }
    
    //生成名片 
    public function name_card()
    {
        $ids = $this->input['id'];
        if (!$ids)
        {
            $this->errorOutput(NOID);
        }
        $data = $this->staff->userinfo($ids);
        $qrcode = array();
        $card = array();
        $name_card = array();
        if (!empty($data))
        {
            foreach ($data as $key=>$val)
            {
                $card[$key] = $val['card_id'];
                if ($this->settings['staff_phpqrcode']['open'])
                {
                    $value = "BEGIN:VCARD\r\nVERSION:2.1\r\nN:".$val['surname'].";".$val['name']."\r\nFN:".$val['surname'].$val['name']."\r\nORG:".$val['company']."\r\nTITLE:".$val['position']."\r\nTEL;CELL:".$val['mobile']."\r\nTEL;WORK:".$val['tel']."\r\nTEL;EXT:".$val['ext_num']."\r\nEMAIL:".$val['email']."\r\nURL:".$val['web']."\r\nEND:VCARD";
                    //目前二维码设置由配置设置
                    $ret = $this->staff->qrcode($value);
                    if (!$ret)
                    {
                        $this->errorOutput('二维码创建失败');
                    }else {
                        $qrcode[$key] = $ret;
                    }
                }   
            }
            //获取所有方案
            //$images = $this->staff->card_configs(implode(',', array_unique($card)));
            
            //生成名片
            foreach ($data as $key=>$val)
            {
                $imgTemplate = '../data/image/front.tif';
        
                $literayOpt = array();
                $imgOpt = array();
                
                if ($val['surname'])
                {
                    $literayOpt[] = array(
                            'titileOpt'=>array('literal'=>'','pos_x'=>0,'pos_y'=>0,'size'=>0),
                            'contentOpt'=>array('literal'=>$val['surname'],'pos_x'=>90,'pos_y'=>220,'size'=>44,'font'=>'../data/font/ltjh.ttf','color'=>'#0091db'),
                    );
                }
                
                if ($val['name'])
                {
                    $num = $this->staff->abslength($val['surname']);
                    $x = 90+43*$num+15;
                    $literayOpt[] = array(
                            'titileOpt'=>array('literal'=>'','pos_x'=>0,'pos_y'=>0,'size'=>0),
                            'contentOpt'=>array('literal'=>$val['name'],'pos_x'=>$x,'pos_y'=>220,'size'=>44,'font'=>'../data/font/ltjh.ttf','color'=>'#0091db')
                    );
                }
                
                if ($val['english_name'])
                {
                    $num = $this->staff->abslength($val['name']);                   
                    $x = $x+43*$num+30;
                    $literayOpt[] = array(
                        'titileOpt'=>array('literal'=>'','pos_x'=>0,'pos_y'=>0,'size'=>0),
                        'contentOpt'=>array('literal'=>$val['english_name'],'pos_x'=>$x,'pos_y'=>220,'size'=>44,'font'=>'../data/font/Avenir.ttf','color'=>'#0091db')
                    );
                }
                
                if ($val['position'])
                {
                    $literayOpt[] = array(  
                           'titileOpt'=>array('literal'=>'','pos_x'=>0,'pos_y'=>0,'size'=>0),
                           'contentOpt'=>array('literal'=>$val['position'],'pos_x'=>90,'pos_y'=>265,'size'=>30,'font'=>'../data/font/ltjh.ttf','color'=>'#878787')
                    );
                }
                
                if ($val['en_position'])
                {
                    $num = $this->staff->abslength($val['position']);
                    $xx = 90 + 28*$num+40;
                    $literayOpt[] = array(                  
                           'titileOpt'=>array('literal'=>'','pos_x'=>0,'pos_y'=>0,'size'=>0),
                           'contentOpt'=>array('literal'=>$val['en_position'],'pos_x'=>$xx,'pos_y'=>265,'size'=>30,'font'=>'../data/font/ltjh.ttf','color'=>'#878787')
                    );
                    
                }
                $y =  352;
                if ($val['company_addr'])
                {
                    $literayOpt[] = array(       
                            'titileOpt'=>array('literal'=>'','pos_x'=>0,'pos_y'=>0,'size'=>0),
                            'contentOpt'=>array('literal'=>$val['company_addr'],'pos_x'=>90,'pos_y'=>$y,'size'=>30,'font'=>'../data/font/ltjh.ttf','color'=>'#878787')                      
                    );
                }
                
                if ($val['en_company_addr'])
                {
                    $y = $y+37;
                    $literayOpt[] = array(
                            'titileOpt'=>array('literal'=>'','pos_x'=>0,'pos_y'=>0,'size'=>0),
                            'contentOpt'=>array('literal'=>$val['en_company_addr'],'pos_x'=>90,'pos_y'=>$y,'size'=>30,'font'=>'../data/font/Avenir.ttf','color'=>'#878787')
                    );  
                }
                if ($val['tel'])
                {
                    $y = $y+37+9;
                    $num = $this->staff->abslength($val['tel']);
                    $number  = intval($num/3);
                    $tel = substr($val['tel'], 0,$number).' '.substr($val['tel'], $number,($num-$number*2)). ' '.substr($val['tel'], (-$number),$number);
                    $literayOpt[] = array(
                            'titileOpt'=>array('literal'=>'T.','pos_x'=>90,'pos_y'=>$y,'size'=>30,'font'=>'../data/font/Avenir_Black.ttf','color'=>'#878787'),
                            'contentOpt'=>array('literal'=>$tel,'pos_x'=>125,'pos_y'=>$y,'size'=>30,'font'=>'../data/font/Avenir.ttf','color'=>'#878787')
                    );
                    if ($val['ext_num'])
                    {
                        $num = $this->staff->abslength($val['tel']);
                        $xxx = 90 + $num * 21+ 25;
                        $literayOpt[] = array(
                                'titileOpt'=>array('literal'=>'T.','pos_x'=>90,'pos_y'=>$y,'size'=>30,'font'=>'../data/font/Avenir_Black.ttf','color'=>'#878787'),
                                'contentOpt'=>array('literal'=>'-'.$val['ext_num'],'pos_x'=>$xxx,'pos_y'=>$y,'size'=>30,'font'=>'../data/font/Avenir.ttf','color'=>'#878787')
                        );  
                    }   
                }
                
                if ($val['mobile'])
                {
                    $y = $y+37;
                    $literayOpt[] = array(                      
                            'titileOpt'=>array('literal'=>'M.','pos_x'=>90,'pos_y'=>$y,'size'=>30,'font'=>'../data/font/Avenir_Black.ttf','color'=>'#878787'),
                            'contentOpt'=>array('literal'=>$val['mobile'],'pos_x'=>125,'pos_y'=>$y,'size'=>30,'font'=>'../data/font/Avenir.ttf','color'=>'#878787')
                    );  
                }
                if ($val['email'])
                {
                    $y = $y+37;
                    $literayOpt[] = array(                      
                            'titileOpt'=>array('literal'=>'E.','pos_x'=>90,'pos_y'=>$y,'size'=>30,'font'=>'../data/font/Avenir_Black.ttf','color'=>'#878787'),
                            'contentOpt'=>array('literal'=>$val['email'],'pos_x'=>125,'pos_y'=>$y,'size'=>30,'font'=>'../data/font/Avenir.ttf','color'=>'#878787')
                    );  
                }
                if ($val['web'])
                {
                    $y = $y+37;
                    if (substr($val['web'], 0,7) != 'http://')
                    {
                        $val['web'] = 'http://' . $val['web'];
                    }
                    $literayOpt[] = array(
                            'titileOpt'=>array('literal'=>'','pos_x'=>0,'pos_y'=>0,'size'=>0,'font'=>'../data/font/Avenir_Black.ttf','color'=>'#878787'),
                            'contentOpt'=>array('literal'=>$val['web'],'pos_x'=>90,'pos_y'=>$y,'size'=>30,'font'=>'../data/font/Avenir.ttf','color'=>'#878787')
                    );  
                }
                
                $name_card[$key]['id'] = $val['id'];
                $name_card[$key]['name'] = $val['surname'].$val['name'];
                
                $dir = $this->settings['staff_card']['path'].date('Ymd',TIMENOW);
                
                if (!is_dir(CUR_CONF_PATH.$dir))
                {
                    hg_mkdir(CUR_CONF_PATH.$dir);
                }
                //正面TIF
                $frontname = md5('front'.$key).'.tif';
                $f_watername =$this->watermark->addWaterMark($imgTemplate,CUR_CONF_PATH.$dir.'/'.$frontname,$imgOpt,$literayOpt,'',array('x'=>300,'y'=>'300','unit'=>1));
                if ($f_watername)
                {   
                    //$name_card[$key]['front'] = $this->settings['staff_card']['protocol'].$this->settings['staff_card']['host'].'/'.$this->settings['staff_card']['dir'].$dir.'/'.$frontname; 
                    
                    $front = $this->settings['staff_card']['protocol'].$this->settings['staff_card']['host'].'/'.$this->settings['staff_card']['dir'].$dir.'/'.$frontname;  
                    $material = $this->material->localMaterial($front, 0, 0, '-1');
                    $material = $material[0];
                    if (!empty($material))
                    {
                        $name_card[$key]['front']['tif'] = array(
                            'host'      =>  $material['host'],
                            'dir'       =>  $material['dir'],
                            'filepath'  =>  $material['filepath'],
                            'filename'  =>  $material['filename'],
                        );
                        //删除图片
                        if (file_exists($f_watername))
                        {
                            unlink($f_watername);
                        }
                    }
                }
                //正面PNG
                $imgTemplatePNG = '../data/image/front.png';
                $frontnamePNG = md5('front'.$key).'.png';
                $f_waternamePNG =$this->watermark->addWaterMark($imgTemplatePNG,CUR_CONF_PATH.$dir.'/'.$frontnamePNG,$imgOpt,$literayOpt,'',array('x'=>300,'y'=>'300','unit'=>1));
                if ($f_waternamePNG)
                {   
                    //$name_card[$key]['front'] = $this->settings['staff_card']['protocol'].$this->settings['staff_card']['host'].'/'.$this->settings['staff_card']['dir'].$dir.'/'.$frontname; 
                    
                    $frontPNG = $this->settings['staff_card']['protocol'].$this->settings['staff_card']['host'].'/'.$this->settings['staff_card']['dir'].$dir.'/'.$frontnamePNG;    
                    $material = $this->material->localMaterial($frontPNG, 0, 0, '-1');
                    $material = $material[0];
                    if (!empty($material))
                    {
                        $name_card[$key]['front']['png'] = array(
                            'host'      =>  $material['host'],
                            'dir'       =>  $material['dir'],
                            'filepath'  =>  $material['filepath'],
                            'filename'  =>  $material['filename'],
                        );
                        //删除图片
                        if (file_exists($f_waternamePNG))
                        {
                            unlink($f_waternamePNG);
                        }
                    }
                }
                

                //背面TIF
                $imgTemplate = '../data/image/back.tif';
                $water=$qrcode[$key];    //水印图
                if ($water)
                {
                    $imgOpt[]=array('waterImg'=>$water,'waterOpt'=>array('pos_x'=>798,'pos_y'=>58, 'alpha'=>1));//添加logo
                }
                $backname = md5('back'.$key).'.tif';
                $b_watername = $this->watermark->addWaterMark($imgTemplate,CUR_CONF_PATH.$dir.'/'.$backname,$imgOpt,'','',array('x'=>300,'y'=>'300','unit'=>1));
                if ($b_watername)
                {
                    //$name_card[$key]['back'] = $this->settings['staff_card']['protocol'].$this->settings['staff_card']['host'].'/'.$this->settings['staff_card']['dir'].$dir.'/'.$backname;
                    
                    $back = $this->settings['staff_card']['protocol'].$this->settings['staff_card']['host'].'/'.$this->settings['staff_card']['dir'].$dir.'/'.$backname;    
                    $material = $this->material->localMaterial($back, 0, 0, '-1');
                    $material = $material[0];
                    if (!empty($material))
                    {
                        $name_card[$key]['back']['tif'] = array(
                            'host'      =>  $material['host'],
                            'dir'       =>  $material['dir'],
                            'filepath'  =>  $material['filepath'],
                            'filename'  =>  $material['filename'],
                        );
                        //删除图片
                        if (file_exists($b_watername))
                        {
                            unlink($b_watername);
                        }
                    }
                }
                
                
                //背面PNG
                $imgTemplatePNG = '../data/image/back.png';
                /*
                $water=$qrcode[$key];    //水印图
                if ($water)
                {
                    $imgOpt[]=array('waterImg'=>$water,'waterOpt'=>array('pos_x'=>798,'pos_y'=>58, 'alpha'=>1));//添加logo
                }
                */
                $backnamePNG = md5('back'.$key).'.png';
                $b_waternamePNG = $this->watermark->addWaterMark($imgTemplatePNG,CUR_CONF_PATH.$dir.'/'.$backnamePNG,$imgOpt,'','',array('x'=>300,'y'=>'300','unit'=>1));
                if ($b_waternamePNG)
                {
                    //$name_card[$key]['back'] = $this->settings['staff_card']['protocol'].$this->settings['staff_card']['host'].'/'.$this->settings['staff_card']['dir'].$dir.'/'.$backname;
                    
                    $backPNG = $this->settings['staff_card']['protocol'].$this->settings['staff_card']['host'].'/'.$this->settings['staff_card']['dir'].$dir.'/'.$backnamePNG;  
                    $material = $this->material->localMaterial($backPNG, 0, 0, '-1');
                    $material = $material[0];
                    if (!empty($material))
                    {
                        $name_card[$key]['back']['png'] = array(
                            'host'      =>  $material['host'],
                            'dir'       =>  $material['dir'],
                            'filepath'  =>  $material['filepath'],
                            'filename'  =>  $material['filename'],
                        );
                        //删除图片
                        if (file_exists($b_waternamePNG))
                        {
                            unlink($b_waternamePNG);
                        }
                    }
                }

                //背面纯白+QRCODE PNG
                $imgTemplatePNG = '../data/image/whiteback.png';
                $whitebacknamePNG = md5('whiteback'.$key).'.png';
                $wb_waternamePNG = $this->watermark->addWaterMark($imgTemplatePNG, CUR_CONF_PATH.$dir.'/'.$whitebacknamePNG, $imgOpt, '', '', array('x'=>300,'y'=>'300','unit'=>1));
                if ($b_waternamePNG)
                {
                    $whitebackPNG = $this->settings['staff_card']['protocol'].$this->settings['staff_card']['host'].'/'.$this->settings['staff_card']['dir'].$dir.'/'.$whitebacknamePNG;
                    $material = $this->material->localMaterial($whitebackPNG, 0, 0, '-1');
                    $material = $material[0];
                    if (!empty($material))
                    {
                        $name_card[$key]['whiteback']['png'] = array(
                            'host'      =>  $material['host'],
                            'dir'       =>  $material['dir'],
                            'filepath'  =>  $material['filepath'],
                            'filename'  =>  $material['filename'],
                        );
                        //删除图片
                        if (file_exists($wb_waternamePNG))
                        {
                            unlink($wb_waternamePNG);
                        }
                    }
                }

                //背面纯白+QRCODE TIFF
                $imgTemplateTIFF = '../data/image/whiteback.tif';
                $whitebacknameTIFF = md5('whiteback'.$key).'.tif';
                $wb_waternameTIFF = $this->watermark->addWaterMark($imgTemplateTIFF, CUR_CONF_PATH.$dir.'/'.$whitebacknameTIFF, $imgOpt, '', '', array('x'=>300,'y'=>'300','unit'=>1));
                if ($wb_waternameTIFF)
                {
                    $whitebackTIFF = $this->settings['staff_card']['protocol'].$this->settings['staff_card']['host'].'/'.$this->settings['staff_card']['dir'].$dir.'/'.$whitebacknameTIFF;
                    $material = $this->material->localMaterial($whitebackTIFF, 0, 0, '-1');
                    $material = $material[0];
                    if (!empty($material))
                    {
                        $name_card[$key]['whiteback']['tif'] = array(
                            'host'      =>  $material['host'],
                            'dir'       =>  $material['dir'],
                            'filepath'  =>  $material['filepath'],
                            'filename'  =>  $material['filename'],
                        );
                        //删除图片
                        if (file_exists($wb_waternameTIFF))
                        {
                            unlink($wb_waternameTIFF);
                        }
                    }
                }


                //删除二维码图片
                if (file_exists($qrcode[$key]))
                {
                    unlink($qrcode[$key]);
                }
                
            }
        }
        $this->addItem($name_card);
        $this->output();
    }
    //zip压缩文件
    public function packZip()
    {
        $id = $this->input['infolist'];
        $name = $this->input['staffName'];
        $frontTif =$this->input['frontTif'];
        $backTif = $this->input['backTif'];
        $whitebackTif = $this->input['whitebackTif'];
        //$id = 1;
        //$name = array(1=>'沈伟');
        //$frontTif = array(1=>'http://img.dev.hogesoft.com:233/material/staff/img/2013/03/11946c76f7a8b2b82d10bf2919c62b23.tif');
        //$backTif = array(1=>'http://img.dev.hogesoft.com:233/material/staff/img/2013/03/5df1463ba59a493a3417bcd53c7c4f8f.tif');
        //$dfile =  tempnam('/tmp', 'tmp');//产生一个临时文件，用于缓存下载文件
        $temp =date('Ymd_His',TIMENOW).'_'.rand(1,100).'.tmp';
        $dir = CUR_CONF_PATH.$this->settings['staff_zip']['path'];
        if (!is_dir($dir))
        {
            hg_mkdir($dir);
        }
        $dfile = $dir.$temp;
        $zip = new zipfile();
        $filename = 'image.zip'; //下载的默认文件名
        $image = array();
        if ($id && !empty($name) && !empty($frontTif) && !empty($backTif))
        {
            if (is_array($id))
            {
                foreach ($id as $key=>$val)
                {
                    $name[$val] = iconv('UTF-8', 'GB2312//IGNORE', $name[$val]);
                    $image[] = array('image_src'=>$frontTif[$val],'image_name' => $name[$val].'_F_'.$val.'.tif');
                    $image[] = array('image_src'=>$backTif[$val],'image_name' => $name[$val].'_B_'.$val.'.tif');
                    $image[] = array('image_src'=>$whitebackTif[$val],'image_name' => $name[$val].'_WB_'.$val.'.tif');
                }
            }
        }
        if (!empty($image))
        {
            foreach ($image as $v)
            {
                $zip->add_file(file_get_contents($v['image_src']), $v['image_name']);
            }
            $zip->output($dfile);
            $data = array(
                'zipname'=>$filename,
                'zipfilename'=>$this->settings['staff_zip']['protocol'].$this->settings['staff_zip']['host'].'/'.$this->settings['staff_zip']['dir'].$this->settings['staff_zip']['path'].'/'.$temp,
            );
        }
        $this->addItem($data);
        $this->output();
        /*
        $image = array(
            array('image_src' => 'pic1.jpg', 'image_name' => '图片1.jpg'),
            array('image_src' => 'pic2.jpg', 'image_name' => 'pic/图片2.jpg'),
        );
        foreach($image as $v){
            $zip->add_file(file_get_contents($v['image_src']),  $v['image_name']);
            // 添加打包的图片，第一个参数是图片内容，第二个参数是压缩包里面的显示的名称, 可包含路径
            // 或是想打包整个目录 用 $zip->add_path($image_path);
        }
        */

        
        
    }
    public function sort()
    {
    
    }
    public function publish()
    {
        
    }
    
    public function unknow()
    {
        $this->errorOutput(NOMETHOD);
    }
}
$ouput= new staff_updateApi();
if(!method_exists($ouput, $_INPUT['a']))
{
    $action = 'unknow';
}else{
    $action = $_INPUT['a'];
}
$ouput->$action();
