<?php
require_once('functions.php');
//define(PIC_DIR,'data/picrures/');
//define(FONT_DIR,'data/fonts/');
class Captche 
{
	private $width;				//宽
	private $height;				//高
	private $codeNum;			//字符数
	private $image;				//图像资源
	private $checkCode;			//验证码字符
	private $answer;				//算术验证码答案
	private $fontFace;			//字体
	private $fontsize;			//字体大小
	private $font_mark;			//关于字体的标记
	private $fontspace;			//字符间距
	private $translation;		//平移量
	private $fontcolor;			//字符颜色
	private $fontcolor_mark;		//关于字符颜色标记
	private $bg_color;			//背景颜色
	private $angle;				//角度
	private $line_num;			//干扰线数量
	private $point_num;			//干扰点数量
	private $type;				//类型
	private $operation;			//运算方式(+-×÷)
	private $bgpicture_id;		//背景图片id
	private $bg_pic;				//背景图片名字
	private $pic_type;			//背景图类型
	
	function __construct($parameter = array())
	{
		$this->codeNum = $parameter['length'];
		
		if($parameter['fontface'])
		{
			$this->fontFace = FONT_DIR.$parameter['fontface'];
		}
		else
		{
			echo "fontFace不存在";
		}
		
		$this->fontsize = $parameter['fontsize'];
		if(substr_count($this->fontsize,','))
		{
			$this->font_mark = 1; //表示字体大小随机
			$arr_tmp = explode(',',$this->fontsize);
			$arr_tmp[0] -= 5;
			$arr_tmp[1] -= 5;
			$this->fontsize = implode(',',$arr_tmp);
		}
		else
		{
			$this->font_mark = 0;
			$this->fontsize = $this->fontsize-5; 
		}
		
		//角度处理
		if($parameter['angle'])
		{
			$this->angle = explode(',',$parameter['angle']);
		}
		else
		{
			$this->angle[0] = 0;
			$this->angle[1] = 0;
		}
		
		//字符颜色处理
		if($parameter['fontcolor'] == 1)
		{
			$this->fontcolor_mark = 1;
		}
		else
		{
			$color = hex2rgb($parameter['fontcolor']);
			$this->fontcolor = array(
				0 => $color['r'],
				1 => $color['g'],
				2 => $color['b'],
			);
		}
		
		$this->line_num = $parameter['line_num'];
		$this->point_num = $parameter['point_num'];
		$this->type = $parameter['type_id'];
		$this->operation = $parameter['operation'];
		
		if($this->type == '5')
		{
			$re = $this->get_math_expression(); //算术表达式
			$this->checkCode = $re['string'];
			$this->answer = $re['answer'];
			$this->mark = $re['mark'];
		}
		else
		{
			$this->checkCode = $this->createCheckCode();
		}
		
		//背景颜色处理
		if($parameter['bg_color'])
		{
			$color = hex2rgb($parameter['bg_color']);
			$this->bg_color = array(
				0 => $color['r'],
				1 => $color['g'],
				2 => $color['b'],
			);
		}
		$this->bgpicture_id = $parameter['bgpicture_id'];
		$this->bg_pic = $parameter['bg_pic'];
		$this->pic_type = $parameter['pic_type'];
		if($this->font_mark)
		{
			$tmp_size = explode(',',$this->fontsize);
			$tmp_size[0] = floor(($tmp_size[0]*4)/3);
			$tmp_size[1] = floor(($tmp_size[1]*4)/3);
			$this->fontsize = $tmp_size[0].','.$tmp_size[1];
		}
		else
		{
			$this->fontsize = floor(($this->fontsize*4)/3); //由"磅"单位转换为"像素"单位
		}
		$this->fontspace = $parameter['font_space'];
		$this->translation = $parameter['translation'];
		
		//宽高处理
		if($parameter['width'] == '0' && $parameter['height'] == '0')
		{
			//因为汉字和字母(或数字)宽高比不一样,所以在此作区别
			if($this->type == '4')
			{
				$this->width = $this->codeNum*$this->fontsize+($this->codeNum-1)*$parameter['font_space']+10;
				$this->height = $this->fontsize+16;
			}
			elseif($this->type == '5')
			{
				//算术验证码按7个字符算
				$this->width = 7*$this->fontsize+($this->codeNum-1)*$parameter['font_space'];//+15;
				$this->height = $this->fontsize+16;
			}
			else
			{
				$this->width = $this->codeNum*$this->fontsize*1/2+($this->codeNum-1)*$parameter['font_space']+10;
				$this->height = $this->fontsize+16;
			}
			if($this->width > 255)
			{
				$this->width = 255;
			}
			if($this->height > 60)
			{
				$this->width = 60;
			}
		}
		else
		{
			$this->width = $parameter['width'];
			$this->height = $parameter['height'];
		}

	}
	
	/**
	 * 通过访问该方法向浏览器中输出图像
	 * Enter description here ...
	 */
	function showImage()
	{
		
		$this->createImage();		//第一步：创建图像背景
		$this->outputText();			//第二步：向图像中随机画出文本
		$this->setDisturbColor();	//第三步：设置干扰元素
		//$border = imagecolorallocate($this->image, 255, 255, 255);	 //设置边框颜色
		//imagerectangle($this->image, 0, 0, $this->width-1, $this->height-1, $border); //画出矩形边框

		$this->outputImage();		//第四步：输出图像
	}
		
	/**
	 * 通过调用该方法获取随机创建的验证码字符串
	 * Enter description here ...
	 */
	function getCheckCode()
	{
		if($this->type == '5')
		{
			$this->checkCode = $this->answer;
		}
		return $this->checkCode;
	}
	
	
	
	
	
	
	
	
	/**
	 * 创建背景
	 * Enter description here ...
	 */
	private function createImage()
	{
		if(!$this->bgpicture_id)
		{
			//创建图像资源
			$this->image = imagecreatetruecolor($this->width, $this->height);
			$backColor = imagecolorallocate($this->image, $this->bg_color[0], $this->bg_color[1], $this->bg_color[2]);
			imagefill($this->image, 0, 0, $backColor);
		}
		else
		{
			//使用背景图片
			$pic_info = getimagesize(PIC_DIR.$this->bg_pic.'.'.$this->pic_type);//获取图片宽高
			$this->width = $pic_info[0];
			$this->height = $pic_info[1];
			switch ($pic_info[2])
			{
				case 1:$this->image = imagecreatefromgif(PIC_DIR.$this->bg_pic.'.'.$this->pic_type);break;
				case 2:$this->image = imagecreatefromjpeg(PIC_DIR.$this->bg_pic.'.'.$this->pic_type);break;
				case 3:$this->image = imagecreatefrompng(PIC_DIR.$this->bg_pic.'.'.$this->pic_type);break;
				case 15:$this->image = imagecreatefromwbmp(PIC_DIR.$this->bg_pic.'.'.$this->pic_type);break;
				case 16:$this->image = imagecreatefromxbm(PIC_DIR.$this->bg_pic.'.'.$this->pic_type);break;
			}
			/*
			 * array getimagesize ( string filename [, array &imageinfo] )
				☆☆ 索引 0 包含图像宽度的像素值，
				     索引 1 包含图像高度的像素值。
				     索引 2 是图像类型的标记：1 = GIF，2 = JPG，3 = PNG，4 = SWF，5 = PSD，
				6 = BMP，7 = TIFF(intel byte order)，8 = TIFF(motorola byte order)，9 = JPC
				，10 = JP2，11 = JPX，12 = JB2，13 = SWC，14 = IFF，15 = WBMP，16 = XBM。这
				些标记与 PHP 4.3.0 新加的 IMAGETYPE 常量对应。
				索引 3 是文本字符串，内容为“height="yyy" width="xxx"”，可直接用于IMG 标记。 
			 * */
		}
	}

	
	
	/**
	 * 设置干扰元素
	 * Enter description here ...
	 */
	private function  setDisturbColor()
	{
		for($i=0; $i<$this->point_num; $i++)
		{
			$color = imagecolorallocate($this->image, rand(0, 255), rand(0, 255), rand(0, 255));
			imagesetpixel($this->image, rand(1, $this->width-2), rand(1, $this->height-2), $color); 
		}

		for($i=0; $i<$this->line_num; $i++)
		{
			$color = imagecolorallocate($this->image, rand(200, 255), rand(200, 255), rand(200, 255));
			imagearc($this->image, rand(-10, $this->width), rand(-10, $this->height), rand(30, 300), rand(20, 200), 55, 44, $color);
		}
	}
	
	
	/**
	 * 创建随机字符
	 * Enter description here ...
	 */
	private function createCheckCode()
	{
				/*** 纯数字 ***/
		$code1 = "0123456789";
				/*** 纯字母 ***/
		$code2 = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"	;
				/*** 字母+数字 ***/
		$code3 = "23456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJKMNPQRSTUVWXYZ";
				/*** 汉字 ***/
		$code4 = "的一是在了不和有大这主中人上为们地个用工时要动国产以我到他会作来分生对于学下级就年阶义发成部民可出能方进同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批如应形想制心样干都向变关点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书术状厂须离再目海交权且儿青才证低越际八试规斯近注办布门铁需走议县兵固除般引齿千胜细影济白格效置推空配刀叶率述今选养德话查差半敌始片施响收华觉备名红续均药标记难存测士身紧液派准斤角降维板许破述技消底床田势端感往神便贺村构照容非搞亚磨族火段算适讲按值美态黄易彪服早班麦削信排台声该击素张密害侯草何树肥继右属市严径螺检左页抗苏显苦英快称坏移约巴材省黑武培著河帝仅针怎植京助升王眼她抓含苗副杂普谈围食射源例致酸旧却充足短划剂宣环落首尺波承粉践府鱼随考刻靠够满夫失包住促枝局菌杆周护岩师举曲春元超负砂封换太模贫减阳扬江析亩木言球朝医校古呢稻宋听唯输滑站另卫字鼓刚写刘微略范供阿块某功套友限项余倒卷创律雨让骨远帮初皮播优占死毒圈伟季训控激找叫云互跟裂粮粒母练塞钢顶策双留误础吸阻故寸盾晚丝女散焊功株亲院冷彻弹错散商视艺灭版烈零室轻血倍缺厘泵察绝富城冲喷壤简否柱李望盘磁雄似困巩益洲脱投送奴侧润盖挥距触星松送获兴独官混纪依未突架宽冬章湿偏纹吃执阀矿寨责熟稳夺硬价努翻奇甲预职评读背协损棉侵灰虽矛厚罗泥辟告卵箱掌氧恩爱停曾溶营终纲孟钱待尽俄缩沙退陈讨奋械载胞幼哪剥迫旋征槽倒握担仍呀鲜吧卡粗介钻逐弱脚怕盐末阴丰编印蜂急拿扩伤飞露核缘游振操央伍域甚迅辉异序免纸夜乡久隶缸夹念兰映沟乙吗儒杀汽磷艰晶插埃燃欢铁补咱芽永瓦倾阵碳演威附牙芽永瓦斜灌欧献顺猪洋腐请透司危括脉宜笑若尾束壮暴企菜穗楚汉愈绿拖牛份染既秋遍锻玉夏疗尖殖井费州访吹荣铜沿替滚客召旱悟刺脑措贯藏敢令隙炉壳硫煤迎铸粘探临薄旬善福纵择礼愿伏残雷延烟句纯渐耕跑泽慢栽鲁赤繁境潮横掉锥希池败船假亮谓托伙哲怀割摆贡呈劲财仪沉炼麻罪祖息车穿货销齐鼠抽画饲龙库守筑房歌寒喜哥洗蚀废纳腹乎录镜妇恶脂庄擦险赞钟摇典柄辩竹谷卖乱虚桥奥伯赶垂途额壁网截野遗静谋弄挂课镇妄盛耐援扎虑键归符庆聚绕摩忙舞遇索顾胶羊湖钉仁音迹碎伸灯避泛亡答勇频皇柳哈揭甘诺概宪浓岛袭谁洪谢炮浇斑讯懂灵蛋闭孩释乳巨徒私银伊景坦累匀霉杜乐勒隔弯绩招绍胡呼痛峰零柴簧午跳居尚丁秦稍追梁折耗碱殊岗挖氏刃剧堆赫荷胸衡勤膜篇登驻案刊秧缓凸役剪川雪链渔啦脸户洛孢勃盟买杨宗焦赛旗滤硅炭股坐蒸凝竟陷枪黎救冒暗洞犯筒您宋弧爆谬涂味津臂障褐陆啊健尊豆拔莫抵桑坡缝警挑污冰柬嘴啥饭塑寄赵喊垫康遵牧遭幅园腔订香肉弟屋敏恢忘衣孙龄岭骗休借丹渡耳刨虎笔稀昆浪萨茶滴浅拥穴覆伦娘吨浸袖珠雌妈紫戏塔锤震岁貌洁剖牢锋疑霸闪埔猛诉刷狠忽灾闹乔唐漏闻沈熔氯荒茎男凡抢像浆旁玻亦忠唱蒙予纷捕锁尤乘乌智淡允叛畜俘摸锈扫毕璃宝芯爷鉴秘净蒋钙肩腾枯抛轨堂拌爸循诱祝励肯酒绳穷塘燥泡袋朗喂铝软渠颗惯贸粪综墙趋彼届墨碍启逆卸航雾冠丙街莱贝辐肠付吉渗瑞惊顿挤秒悬姆烂森糖圣凹陶词迟蚕亿矩";
		//$code = iconv("GBK", "utf-8",$code);
		$string = '';
		
		switch ($this->type)
		{
			case 1:$code = $code1;break;//纯数字
			case 2:$code = $code2;break;//纯字母
			case 3:$code = $code3;break;//字母+数字
			case 4:$code = $code4;break;//汉字
		}
		
		if($this->type !== '4')
		{
			for($i=0; $i < $this->codeNum; $i++)
			{
				$char = $code{rand(0, strlen($code)-1)};
				$string .= $char;
			}
		}
		else
		{
			for($i=0;$i<$this->codeNum;$i++)
			{
	   			$string .= substr($code,mt_rand(0,502)*3,3);//要以三字节为单位处理
			}
		}
			return $string;
	}
	
	
	/**
	 * 算术表达式
	 * $operation 运算方式(+-×÷)
	 * Enter description here ...
	 */
	private function get_math_expression()
	{
		$x = rand(1,20);
		$y = rand(1,20);
		$z = $x+$y;
		
		$a = rand(1,10);
		$b = rand(1,10);
		$c = $a*$b;
		
		
		$str1 = '?'.'+'.$y.'='.$z;
		$str2 = $x.'+'.'?'.'='.$z;
		$str3 = $x.'+'.$y.'='.'?';
	
		$str4 = $z.' - '.$y.' = '.'?';
		$str5 = $z.' - '.'?'.' = '.$x;
		$str6 = '?'.' - '.$y.' = '.$x;
	
		$str7 = '?'.' × '.$b.' = '.$c;
		$str8 = $a.' × '.'?'.' = '.$c;
		$str9 = $a.' × '.$b.' = '.'?';
	
		$str10 = $c.' ÷ '.$b.' = '.'?';
		$str11 = $c.' ÷ '.'?'.' = '.$a;
		$str12 = '?'.' ÷ '.$b.' = '.$a;
		
		
		if($this->operation == '5')
		{
			$this->operation = rand(1,4);
		}
		else
		{
			$this->operation = $this->operation;
		}
	
		if($this->operation == '1')
		{
			$i = rand(1,3);
			switch($i)
			{
				case 1;$str = $str1;$answer = $x;$mark = 1;break;
				case 2;$str = $str2;$answer = $y;$mark = 1;break;
				case 3;$str = $str3;$answer = $z;$mark = 1;break;
			}
		}
		else if($this->operation == '2')
		{
			$i = rand(4,6);
			switch($i)
			{
				case 4;$str = $str4;$answer = $x;$mark = 2;break;
				case 5;$str = $str5;$answer = $y;$mark = 2;break;
				case 6;$str = $str6;$answer = $z;$mark = 2;break;
			}
		}
		else if($this->operation == '3')
		{
			$i = rand(7,9);
			switch($i)
			{
				case 7;$str = $str7;$answer = $a;$mark = 3;break;
				case 8;$str = $str8;$answer = $b;$mark = 3;break;
				case 9;$str = $str9;$answer = $c;$mark = 3;break;
			}
		}
		else if($this->operation == '4')
		{
			$i = rand(10,12);
			switch($i)
			{
				case 10;$str = $str10;$answer = $a;$mark = 4;break;
				case 11;$str = $str11;$answer = $b;$mark = 4;break;
				case 12;$str = $str12;$answer = $c;$mark = 4;break;
			}
		}
		$data = array(
			'string' => $str,
			'answer' =>	$answer,
			'mark'	 => $mark,
		);
		return $data;
	}
	
	
	/**
	 * 画入随机字符
	 * Enter description here ...
	 */
	private function outputText()
	{
		if($this->font_mark)
		{
			$fontwidth_tmp = explode(',',$this->fontsize);
			if($fontwidth_tmp[0] > $fontwidth_tmp[1])
			{
				$fontwidth = $fontwidth_tmp[0];
			}
			else
			{
				$fontwidth = $fontwidth_tmp[1];
			}
		}
		else
		{
			$fontwidth = $this->fontsize;
		}
		if($this->fontcolor_mark)
		{
			$fontcolor = null;
		}
		else
		{
			$fontcolor = imagecolorallocate($this->image, $this->fontcolor[0], $this->fontcolor[1], $this->fontcolor[2]);
		}
		
		
		$arr = array();
		if($this->type == '4')//汉字
		{
			$fontsize = $this->fontsize;
			if($this->font_mark)
			{
				$fontsize = explode(',', $fontsize);
			}
			for($i=0;$i<$this->codeNum;$i++)
			{
	   			$arr[] = substr($this->checkCode,$i*3,3);//要以三字节为单位处理
			}
			
			$y = floor(($this->height-$this->fontsize)/2)+$this->fontsize;
			for($i=0;$i<$this->codeNum;$i++)
			{
				if($this->fontcolor_mark)
				{
					$this->fontcolor = array(rand(0,255),rand(0,255),rand(0,255));
					$fontcolor = imagecolorallocate($this->image, $this->fontcolor[0], $this->fontcolor[1], $this->fontcolor[2]);
				}
				if($i == 0 && $this->translation == 0)
				{
					$x = 0;
				}
				else 
				{
					$x = $fontwidth*$i+($i*$this->fontspace)+$this->translation;
				}
				if($this->font_mark)
				{
					imagettftext($this->image,rand($fontsize[0],$fontsize[1]),rand($this->angle['0'],$this->angle['1']),$x,$y,$fontcolor, $this->fontFace,$arr{$i});
				}
				else
				{
					imagettftext($this->image,$fontsize,rand($this->angle['0'],$this->angle['1']),$x,$y,$fontcolor, $this->fontFace,$arr{$i});
				}
			}
		}
		else if($this->type == '5')//算术
		{
			$str = $this->checkCode;
			$fontsize = $this->fontsize;
			if($this->font_mark)
			{
				$fontsize = explode(',', $fontsize);
			}
			switch($this->mark)
			{
				case 1;$arr1 = explode('+',$str);$arr2 = explode('=',$arr1[1]);$data[1] = '＋';break;
				case 2;$arr1 = explode('-',$str);$arr2 = explode('=',$arr1[1]);$data[1] = '－';break;
				case 3;$arr1 = explode('×',$str);$arr2 = explode('=',$arr1[1]);$data[1] = '×';break;
				case 4;$arr1 = explode('÷',$str);$arr2 = explode('=',$arr1[1]);$data[1] = '÷';break;
			}
			$data[0] = trim($arr1[0]);
			$data[2] = trim($arr2[0]);
			$data[3] = trim('＝');
			$data[4] = trim($arr2[1]);
			
			$y = floor(($this->height-$this->fontsize)/2)+$this->fontsize;
			for($i=0;$i<count($data);$i++)
			{
				if($this->fontcolor_mark)
				{
					$this->fontcolor = array(rand(0,255),rand(0,255),rand(0,255));
					$fontcolor = imagecolorallocate($this->image, $this->fontcolor[0], $this->fontcolor[1], $this->fontcolor[2]);
				}
				if($i == 0 && $this->translation == 0)
				{
					$x = 0;
				}
				else 
				{
					$x = $fontwidth*$i+($i*$this->fontspace)+$this->translation;
				}
				if($this->font_mark)
				{
					imagettftext($this->image,rand($fontsize[0],$fontsize[1]),rand($this->angle['0'],$this->angle['1']),$x,$y,$fontcolor, $this->fontFace,$data[$i]);
				}
				else
				{
					imagettftext($this->image,$fontsize,rand($this->angle['0'],$this->angle['1']),$x,$y,$fontcolor, $this->fontFace,$data[$i]);
				}
			}
		}
		else	//其他
		{
			$fontsize = $this->fontsize;
			if($this->font_mark)
			{
				$fontsize = explode(',', $fontsize);
			}
			$fontwidth = $fontwidth/2;
			$y = floor(($this->height-$this->fontsize)/2)+$this->fontsize;
			for($i=0;$i<$this->codeNum;$i++)
			{
				if($this->fontcolor_mark)
				{
					$this->fontcolor = array(rand(0,255),rand(0,255),rand(0,255));
					$fontcolor = imagecolorallocate($this->image, $this->fontcolor[0], $this->fontcolor[1], $this->fontcolor[2]);
				}
				if($i == 0 && $this->translation == 0)
				{
					$x = 0;
				}
				else 
				{
					$x = $fontwidth*$i+($i*$this->fontspace)+$this->translation;
				}
				if($this->font_mark)
				{
					imagettftext($this->image,rand($fontsize[0],$fontsize[1]),rand($this->angle['0'],$this->angle['1']),$x,$y,$fontcolor, $this->fontFace,$this->checkCode{$i});
				}
				else
				{
					imagettftext($this->image,$fontsize,rand($this->angle['0'],$this->angle['1']),$x,$y,$fontcolor, $this->fontFace,$this->checkCode{$i});
				}
			}
		}
	}
	
	
	
	
	/**
	 * 输出图像资源
	 * Enter description here ...
	 */
	private function outputImage() 
	{
		if(imagetypes() & IMG_GIF)
		{
			header("Content-Type:image/gif");
			imagepng($this->image);
		}
		else if(imagetypes() & IMG_JPG)
		{
			header("Content-Type:image/jpeg");
			imagepng($this->image);
		}
		else if(imagetypes() & IMG_PNG)
		{
			header("Content-Type:image/png");
			imagepng($this->image);
		}
		else if(imagetypes() & IMG_WBMP)
		{
			header("Content-Type:image/vnd.wap.wbmp");
			imagepng($this->image);
		}
		else
		{
			die("PHP不支持图像创建");
		}
	}
	
	function __destruct()
	{
		imagedestroy($this->image);
	}
}