<?php
class astrojson extends InitFrm
{
	public function __construct()
	{
		parent::__construct();

	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function arraytojson($data,$fun,$id)
	{
		$day=array();
		$tomorrow=array();
		$week=array();
		$month=array();
		$year=array();
		$love=array();
		
		switch ($fun){

			case 'day':


				foreach ($data as $jsonkey => $jsonval)
				//print_r($jsonv);
				if(is_array($jsonval)&&!($jsonkey=='11')){

					foreach ($jsonval as $jsonkk => $jsonvv)
					{
						$day[]=$jsonvv;

					}
				}
				elseif($jsonkey=='11') {
					$day[]=$data[11];
				}
				unset($data);
				$data['zhys']=array('title'=>$day[0],'rank'=>$day[1],'value'=>$day[2]);
				$data['aqys']=array('title'=>$day[3],'rank'=>$day[4],'value'=>$day[5]);
				$data['gzzk']=array('title'=>$day[6],'rank'=>$day[7],'value'=>$day[8]);
				$data['lctz']=array('title'=>$day[9],'rank'=>$day[10],'value'=>$day[11]);
				$data['jkzs']=array('title'=>$day[12],'rank'=>$day[13],'value'=>$day[14]);
				$data['stzs']=array('title'=>$day[15],'rank'=>$day[16],'value'=>$day[17]);
				$data['xyys']=array('title'=>$day[18],'rank'=>$day[19],'value'=>$day[20]);
				$data['xysz']=array('title'=>$day[21],'rank'=>$day[22],'value'=>$day[23]);
				$data['spxz']=array('title'=>$day[24],'rank'=>$day[25],'value'=>$day[26]);
				$data['zhgs']=array('title'=>$day[27],'rank'=>$day[28],'value'=>$day[29]);
				$data['en']=$day[30];
				$data['cn']=$day[31];
				$data['astrotime']=$day[32];

				break;
			case 'tomorrow':

				foreach ($data as $jsonkey => $jsonval)

				if(is_array($jsonval)&&!($jsonkey=='11')){

					foreach ($jsonval as $jsonkk => $jsonvv)
					{
						$tomorrow[]=$jsonvv;

					}
				}
				elseif($jsonkey=='11') {
					$tomorrow[]=$data[11];
				}

				unset($data);
				$data['zhys']=array('title'=>$tomorrow[0],'rank'=>$tomorrow[1],'value'=>$tomorrow[2]);
				$data['aqys']=array('title'=>$tomorrow[3],'rank'=>$tomorrow[4],'value'=>$tomorrow[5]);
				$data['gzzk']=array('title'=>$tomorrow[6],'rank'=>$tomorrow[7],'value'=>$tomorrow[8]);
				$data['lctz']=array('title'=>$tomorrow[9],'rank'=>$tomorrow[10],'value'=>$tomorrow[11]);
				$data['jkzs']=array('title'=>$tomorrow[12],'rank'=>$tomorrow[13],'value'=>$tomorrow[14]);
				$data['stzs']=array('title'=>$tomorrow[15],'rank'=>$tomorrow[16],'value'=>$tomorrow[17]);
				$data['xyys']=array('title'=>$tomorrow[18],'rank'=>$tomorrow[19],'value'=>$tomorrow[20]);
				$data['xysz']=array('title'=>$tomorrow[21],'rank'=>$tomorrow[22],'value'=>$tomorrow[23]);
				$data['spxz']=array('title'=>$tomorrow[24],'rank'=>$tomorrow[25],'value'=>$tomorrow[26]);
				$data['zhgs']=array('title'=>$tomorrow[27],'rank'=>$tomorrow[28],'value'=>$tomorrow[29]);
				$data['en']=$tomorrow[30];
				$data['cn']=$tomorrow[31];
				$data['astrotime']=$tomorrow[32];

				break;
			case 'week':

				foreach ($data as $jsonkey => $jsonval)
				{

					foreach ($jsonval as $jsonkk => $jsonvv)
					{
						$week[]=$jsonvv;

					}
				}
				unset($data);
				$data['ztys']=array('title'=>$week[0],'rank'=>$week[1],'value'=>$week[2]);
				$data['aqys']=array('title'=>$week[3],'beau'=>array('yes'=>array('title'=>$week[4][0],'rank'=>$week[5][0],'value'=>$week[6][0]),
	'no'=>array('title'=>$week[4][1],'rank'=>$week[5][1],'value'=>$week[6][1])));
				$data['jkys']=array('title'=>$week[7],'rank'=>$week[8],'value'=>$week[9]);
				$data['gzxyy']=array('title'=>$week[10],'rank'=>$week[11],'value'=>$week[12]);
				$data['xyzs']=array('title'=>$week[13],'rank'=>$week[14],'value'=>$week[15]);
				$data['hxr']=array('title'=>$week[16],'rank'=>$week[17],'value'=>$week[18]);
				$data['hmr']=array('title'=>$week[19],'rank'=>$week[20],'value'=>$week[21]);
				$data['tip']=array('title'=>$week[22],'rank'=>$week[23],'value'=>$week[24]);
				$data['en']=$week[25];
				$data['cn']=$week[26];
				$data['starttime']=$week[27];
				$data['endtime']=$week[28];
				break;
			case "month":
				foreach ($data as $jsonk => $jsonv)
				{

					foreach ($jsonv as $jsonkk => $jsonvv)
					{
						$month[]=$jsonvv;

					}

				}
				unset($data);
				$data['ztys']=array('title'=>$month[0],'rank'=>$month[1],'value'=>$month[2]);
				$data['aqys']=array('title'=>$month[3],'rank'=>$month[4],'value'=>$month[5]);
				$data['tzlcy']=array('title'=>$month[6],'rank'=>$month[7],'value'=>$month[8]);
				$data['jyfs']=array('title'=>$month[9],'rank'=>$month[10],'value'=>$month[11]);
				$data['kyxmj']=array('title'=>$month[12],'rank'=>$month[13],'value'=>$month[14]);
				$data['en']=$month[15];
				$data['cn']=$month[16];
				$data['starttime']=$month[17];
				$data['endtime']=$month[18];
				break;
			case "year":
				foreach ($data as $jsonk => $jsonv)
				{

					foreach ($jsonv as $jsonkk => $jsonvv)
					{
						$year[]=$jsonvv;

					}

				}
				unset($data);
				$data['ztgk']=array('title'=>$year[0],'rank'=>$year[1],'value'=>$year[2]);
				$data['gkxy']=array('title'=>$year[3],'rank'=>$year[4],'value'=>$year[5]);
				$data['gzzc']=array('title'=>$year[6],'rank'=>$year[7],'value'=>$year[8]);
				$data['jqlc']=array('title'=>$year[9],'rank'=>$year[10],'value'=>$year[11]);
				$data['love']=array('title'=>$year[12],'rank'=>$year[13],'value'=>$year[14]);
				$data['xts']=array('title'=>$year[15],'rank'=>$year[16],'value'=>$year[17]);
				$data['en']=$year[18];
				$data['cn']=$year[19];
				$data['starttime']=$year[20];
				$data['endtime']=$year[21];
				break;
			case "love":
				foreach ($data as $jsonk => $jsonv)
				{

					foreach ($jsonv as $jsonkk => $jsonvv)
					{
						$love[]=$jsonvv;
					}

				}

				unset($data);
				$data['ztaqy']=array('title'=>$love[0],'value'=>$love[1]);
				$data['girl']=array('title'=>$love[2],'value'=>$love[3]);
				$data['boy']=array('title'=>$love[4],'value'=>$love[5]);
				$data['en']=$love[6];
				$data['cn']=$love[7];
				$data['starttime']=$love[8];
				$data['endtime']=$love[9];
				break;
			default:echo "对不起，您的日期选择超出范围！";
			break;
		}

		return $data;
	}
}