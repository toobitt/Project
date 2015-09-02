<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: item.php 5123 2011-11-22 03:24:07Z develop_tong $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require('livcms_frm.php');
class items extends LivcmsFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 
	 * 获取文章详细内容
	 */
	public function show()
	{	
		$id = $this->input['id'] ? intval($this->input['id']) : -1;
		if($pubdate = trim(urldecode($this->input['pre'])))
		{
			//以日期为标准 前一篇文章
			$condition = 'cm.pubdate < '.$pubdate;
		}
		else if($pubdate = trim(urldecode($this->input['next'])))
		{
			//以日期为标准 后一篇文章
			$condition = 'cm.pubdate > '.$pubdate;
		}
		else
		{
			$condition = '';
		}
		
		//符合html标记 不全
		$allow_tags = array(
							'<a>',
							'<p>',
							'<br>',
		);
		$liv_proc = array(
			'android' => 'httplive://'	
		);
		$img_event = array(
			1 => 'showImg',	
			2 => 'livAndroid.showImg',	
		);
		if($id > 0 || $condition != '')
		{	
			//有ID则以ID查询覆盖时间条件 否则已时间条件查询文章详细信息
			if($id > 0 )
			{
				$condition = 'cm.id = '.$id;
			}		
			$sql = "SELECT cm.id,cm.pubdate,cm.modeid, cm.columnid,cm.contentid,mat.materialid,mat.filepath,mat.filename, mat.thumbfile, col.columnid,col.colname
				FROM ".DB_PREFIX."contentmap cm 
					left join " . DB_PREFIX . "material mat 
						on mat.materialid = cm.indexpic 
					left join " . DB_PREFIX . "column col
						on cm.columnid=col.columnid
					WHERE {$condition} 
						and cm.status=3
						and cm.siteid=" . $this->site['siteid'];
			if ($this->input['debug'])
			{
				echo $sql;
			}
			//exit($sql);	
			$r = $this->db->query_first($sql);
			if (!$r)
			{
				$this->errorOutput('内容不存在或已被删除');
			}
			$msql = 'SELECT ma.*, c.modesign FROM ' . DB_PREFIX . 'mode_apply ma LEFT JOIN ' . DB_PREFIX . 'contentmode c ON ma.colmodeid=c.colmodeid WHERE ma.applyid=' . $r['modeid'];
			$mode = $this->db->query_first($msql);
			if ($r)
			{
				$csql = 'SELECT COUNT(*) AS count FROM ' . DB_PREFIX . 'comment WHERE contentid=' . $id;
				$c = $this->db->query_first($csql);
			}
			$r['indexpic'] = $this->getimageurl($r, 'filename');
			$r['picurl'] = $r['indexpic'];
			$modec = $this->db->query_first('SELECT * FROM ' . DB_PREFIX . $mode['modesign'] . ' WHERE ' . $mode['modesign'] . 'id=' . $r['contentid']);
			$r = $r + $modec;
			if ($modec['subtitle'])
			{
				$r['brief'] = hg_cutchars($modec['subtitle'], 30, '');
			}
			if (!$r['videoid'])
			{
				$r['videoid'] = $r['vid'];
			}
			if ($r['videoid'])
			{
				include_once(ROOT_PATH.'lib/class/curl.class.php');
				$curl = new curl($this->settings['App_livmedia']['host'], $this->settings['App_livmedia']['dir']);
				$curl->initPostData();
                $curl->addRequestData('a', 'detail');
				$curl->addRequestData('id', $r['videoid']);
				$video_info = $curl->request('vod.php');
				$video_info = $video_info[0];
				if($video_info)
				{
					$curl->addRequestData('a', 'updateclick');
					$curl->request('vod.php');
					$r['video'] = array(
						'host' => rtrim($video_info['hostwork'], '/') . '/',	
						'dir' => '',	
						'filepath' => $video_info['video_path'],	
						'filename' => str_replace('.mp4', '.m3u8', $video_info['video_filename']),	
						'is_audio' => $video_info['is_audio'],	
					);
					$r['vodurl'] = $video_info['vodurl'];
                    $r['vodid'] = $video_info['vodid'];
				}
				$r['newcontent'] = $modec['comment'];
			}
			else
			{
				$r['vodurl'] = '';
				$r['vodid'] =  0;
			}
			//内容转存于body表中 取出并拼接
			$weburl = $this->site['weburl'];
			if ($mode['bodyfield'])
			{
				$content = $this->getAllContent($r['articleid'], $r['modeid']);	
				@eval('$content = "'.addslashes($content).' " ;');
				$r['newcontent'] = $content;
			}

			$r['pubdate'] = date('Y-m-d H:i:s', $r['pubdate']);
			$r['comment_count'] = intval($c['count']);
			$pics = array();
			if($r['newcontent'])
			{
				//获取内容中存在的所有图片
				preg_match_all("/<img\s+.*?\s*src\s*=\s*[\'\"]([^\'\">\s]*)[\'\"]\s*[^>]*>/is", $r['newcontent'], $imgs);
				if ($imgs[1])
				{
					$i = 0;
					foreach ($imgs[1] AS $v)
					{
						//$hg_pics[] = '<div class="img" onclick="' . $img_event[$this->input['source']] . '(\'' . $v . '\');" ><img src="' . $v . '" width="70" /><span class="img_show"></span></div>';
						$v = explode('liv_loadfile/', $v);
						if (defined('CMS_IMG_DOMAIN') && CMS_IMG_DOMAIN && $v[0] == $this->site['weburl'] && $v[1])
						{
							$pic = array(
								'host' => CMS_IMG_DOMAIN,	
								'dir' => '',	
								'filepath' => '',	
								'filename' => $v[1],	
							);
							$pics[$i]['pic'] = $pic;
							$i++;
						}
						else
						{
							if (!$v[1])
							{
								$v[1] = '';
								$idir = '';
							}
							else
							{
								$idir = 'liv_loadfile/';
							}
							$pic = array(
								'host' => $v[0],	
								'dir' => $idir,	
								'filepath' => '',	
								'filename' => $v[1],	
							);
							$pics[$i]['pic'] = $pic;
							$i++;

						}
					}
				}			
			}
			else
			{
				$r['newcontent'] = $r['brief'];
			}
			if($this->input['debug'] == 1)
			{
				print_r($r);
			}
			$imgcnt = count($hg_pics);
			//内容内嵌图片集合 以|分割的字符串
			if($imgcnt > 0)
			{
				$allimg = '<div class="cont_img_show" style="width:100px;float:right;" align="absmiddle">' . implode('', $hg_pics)  . '</div>';
			}
			if ($liv_proc[$this->input['client']])
			{
				//$r['vedio'] = str_replace('http://', $liv_proc[$this->input['client']], $r['vedio']);
			}
			$r['newcontent'] = $allimg . strip_tags($r['newcontent'], implode('', $allow_tags));
			$r['newcontent'] = str_replace(array("\r", "\n"), '', $r['newcontent']);
			$r['content'] = str_replace(array('<a ', '<A '), '<a target="_blank" ', $r['newcontent']);
			$r['column_name'] = $r['colname'];
			$r['material'] = $pics;
			$r['content_url'] = $this->site['weburl'] . 'location.php?id=' . $r['id'];
			unset($r['colname']);
			$this->setXmlNode('contents' , 'content');
			
			if(is_array($r) && $r)
			{
				$this->addItem($r);
				$this->output();
			}
			else
			{
				$this->errorOutput('内容不存在或已被删除');	
			} 					
		}
		else
		{
			$this->errorOutput('未传入查询ID或文章发布日期');
		} 		
	}
	/**
	 * 获取转存表中的内容
	 */
	function getAllContent($articleid, $applyid)
	{
		$content = '';
		if(!$articleid)
		{
			return;
		}
		$sql  = "SELECT * FROM ".DB_PREFIX.'article_contentbody WHERE articleid = '.$articleid . ' AND applyid=' . $applyid . ' ORDER BY pageid ASC';
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$content .= $r['content'];
		}
		//过滤标签符合html5
		return $content = $this->filterTag($content);
	}
	/**
	 * 标签过滤 符合HTML5格式 并处理图片
	 */
	function filterTag($content)
	{
		if(!$content)
		{
			return;
		}
		$content = html_standardization($content);
		$content = preg_replace(array('/style=".*?"/is', '/class=".*?"/is'), '', $content);
		$content = preg_replace('/<p.*?>/is', '</p><p>', $content, 1);
		$content = preg_replace('/<p(.*?)>/is', '<p>', $content);
		$content = str_replace('<p>　　', '<p>', $content);
		$content = trim($content);
		if (substr($content, 0, 4) == '</p>')
		{
			$content = substr($content, 4);
		}
		if (substr($content, 0, 2) != '<p')
		{
			$content = '<p>' . $content . '</p>';
		}
		return $content;	
	}
}

/**
 *  程序入口
 */
$out = new items();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>
