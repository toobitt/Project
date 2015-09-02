<?php
class class_codeparse extends BaseFrm
{
	var $code_tag = array();
	var $code_text = array();
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	public function parse_smile($text)
	{
		$this->livime->func->check_cache('smile');
		$smile = $this->livime->cache['smile'];
		if ($smile)
		{
			foreach($smile as $a_id => $row)
			{
				$code = $row['smiletext'];
				$image = $row['image'];
				$title = $row['title'];
				$code = preg_quote($code, "/");
				$text = preg_replace("#($code)#ie", "\$this->convert_smilies('$code', '$image', '$title')", $text);
			}
		}
		return $text;
	}
	function convert($in = array('text' => '', 'allowsmilies' => 0, 'allowcode' => 0, 'usewysiwyg' => 0, 'change_editor' => 1))
	{
		$text = $in['text'];
		$text = preg_replace("/(<\s{0,}(script|link|style)(.*?)>(.*?)<\/s{0,}(script|link|style)\s{0,}>)/is", "", $text);
		if (empty($text))
		{
			return '';
		}
		$_INPUT = $this->input;
		$this->check_caches();
		$usewysiwyg = intval($in['usewysiwyg']);
		$change_editor = intval($in['change_editor']);
	
		if (!$change_editor)
		{
			$text = $this->safe_text($text);
		}
		$_INPUT['parseurl'] = isset($_INPUT['parseurl']) ? $_INPUT['parseurl'] : (isset($_INPUT['checkurl']) ? $_INPUT['checkurl'] : 222);
		$pregfind = array(
				'#<img[^>]+smilietext=(\'|")(.+)(\\1).*>#siU',
				'#<object[^>]+>(.*)src="(.*)"(.*)</object>#Ui',
				//'#<img[^>]+src=(\'|")(.+)(\\1).*>screen??(.*)>#siU',
				'#<img[^>]+src=(\'|")(.+)(\\1).*>#siU',
		);
		$pregreplace = array(
				'\2',
				$this->convert_flash_bbcode('', '', '\2'),
				//'[img]\2[/img]',
				'[img]\2[/img]',
		);
		$text = preg_replace($pregfind, $pregreplace, $text);
	
		if ($_INPUT['parseurl'])
		{
			$text = $this->convert_url($text, 0);
		}
		$text = $this->bbcode_check($text);
		$text = preg_replace("#(\?|&amp;|;|&)s=([0-9a-zA-Z]){32}(&amp;|;|&|$)?#e", "\$this->parse_bash_session('\\1', '\\3')", $text);
		if ($in['allowcode'])
		{
			$pregfind = array(
					"#\[email\](\S+?)\[/email\]#i",
					"#\[email\s*=\s*([\.\w\-]+\@[\.\w\-]+\.[\w\-]+)\s*\](.*?)\[\/email\]#i",
					"#\[email\s*=\s*\&quot\;([\.\w\-]+\@[\.\w\-]+\.[\.\w\-]+)\s*\&quot\;\s*\](.*?)\[\/email\]#i",
					"#\[indent\](.+?)\[/indent\]#is",
					"#\[url\](\S+?)\[/url\]#ie",
					'#\[url=(&quot;|"|\'|)(.*)\\1\](.*)\[/url\]#esiU',
			);
			$pregreplace = array(
					"<a href=\"mailto:\\1\">\\1</a>",
					"<a href=\"mailto:\\1\">\\2</a>",
					"<a href=\"mailto:\\1\">\\2</a>",
					"<blockquote>\\1</blockquote>",
					"\$this->parse_build_url(array('html' => '\\1', 'show' => '\\1'))",
					"\$this->parse_build_url(array('html' => '\\2', 'show' => '\\3'))",
			);
			$text = preg_replace($pregfind, $pregreplace, $text);
			$text = strip_tags($text, '<b><strong><i><em><u><sub><sup><s><a><div><span><p><blockquote><ol><ul><li><font><img><br><h1><h2><h3><h4><h5><h6><hr><table><td><tr>');
	
			if (!$change_editor)
			{
				$text = preg_replace("#\[emule\](.+?)\[/emule\]#ies" , "\$this->parse_emule('\\1')", $text);
			}
	
			while (preg_match("#\n?\[list\](.+?)\[/list\]\n?#is", $text))
			{
				$text = preg_replace("#\n?\[list\](.+?)\[/list\]\n?#ies", '$this->parse_list(\'\1\')', $text);
			}
			while (preg_match("#\n?\[list=(a|A|i|I|1)\](.+?)\[/list\]\n?#is", $text))
			{
				$text = preg_replace("#\n?\[list=(a|A|i|I|1)\](.+?)\[/list\]\n?#ies", "\$this->parse_list('\\2','\\1')", $text);
			}
			while (preg_match("#\[(b|i|u|sub|sup|s)\](.+?)\[/\\1\]#is", $text))
			{
				$text = preg_replace("#\[(b|i|u|sub|sup|s)\](.+?)\[/\\1\]#is", "<\\1>\\2</\\1>", $text);
			}
			while (preg_match("#\[(left|right|center)\](<br>|<br />|\r\n|\n|\r)??(.+?)(<br>|<br />|\r\n|\n|\r)??\[/\\1\]#is", $text))
			{
				$text = preg_replace("#\[(left|right|center)\](<br>|<br />|\r\n|\n|\r)??(.+?)(<br>|<br />|\r\n|\n|\r)??\[/\\1\]#is", "<p align='\\1'>\\3</p>", $text);
			}
			while (preg_match("#\[(font|size|color|bgcolor)=(&quot;|&\#39;|'|\"|)([^\]]+)(\\2)\](.+?)\[/\\1\]#is", $text))
			{
				$text = preg_replace("#\[(font|size|color|bgcolor)=(&quot;|&\#39;|'|\"|)([^\]]+)(\\2)\](.+?)\[/\\1\]#ies", "\$this->parse_font('\\1','\\3','\\5')", $text);
			}
			if (!$change_editor)
			{
				$text = preg_replace('#\[aid::(\d+)\]#iesU', "\$this->parse_attach('\\1')", $text);
				if (is_array($this->aip))
				{
					$text = $this->parse_attach_contents($text);
				}
			}
			$this->settings['allowimages'] = 1;
			if ($this->settings['allowimages'])
			{
				$text = preg_replace("#\[img\](.+?)\[/img\]#ie", "\$this->parse_image('\\1')", $text);
			}
		}
		//	$text = str_replace(array('[hr]', "\n"), array('<hr />', '<br />'), $text);
		$text = preg_replace('/&amp;#([0-9]+);/s', '&#\\1;', $text);
		$text = preg_replace('/(\s{0,}<br>\s{0,}|\s{0,}<br \/>\s{0,}){2,}/s', '<br>', $text);
	
		$text = $this->censoredwords($text);
	
		if (!empty($this->code_tag))
		{
			$text = str_replace($this->code_tag, $this->code_text, $text);
			$this->code_count = 0;
			$this->code_hash = '';
		}
		//		$text = $this->filter($text);
		return $text;
	}
	
	function safe_text($text = '')
	{
		$pregfind = array(
				'/moz\-binding:/is',
				'/script/i',
				'/alert/i',
				'/about:/i',
				'/onmouseover/i',
				'/onclick/i',
				'/onload/i',
				'/onsubmit/i',
				'/\[\/img\] *\}" border="0" \/>/i'
		);
		$pregreplace = array(
				'moz binding:',
				'&#115;cript',
				'&#097;lert',
				'&#097;bout:',
				'&#111;nmouseover',
				'&#111;nclick',
				'&#111;nload',
				'&#111;nsubmit',
				'[/img] '
		);
		$text = preg_replace($pregfind, $pregreplace, $text);
		return $text;
	}
	
	function convert_flash_bbcode($w = 0, $h = 0, $src = '')
	{
		if (!$src)
		{
			return '';
		}
		return (!$w && !$h) ? '[flash]' . $src . '[/flash]' : '[flash=' . $w . ',' . $h . ']' . $src . '[/flash]';
	}
	
	function convert_url($text, $inlink = 1)
	{
		$text = preg_replace(array(
				'#<a href="([^"]*)\[([^"]+)"(.*)>(.*)\[\\2</a>#siU',
				'#(<[^<>]+ (src|href))=(\'|"|)??(.*)(\\3)#esiU',
				'#<a[^<>]+href="([^"]*)"(.*)>(.*)</a>#siU',
				"#\[url\](\S+?)\[/url\]#ie",
				'#\[url=(&quot;|"|\'|)(.*)\\1\](.*)\[/url\]#esiU',
		), array(
				"<a href=\"\\1\"\\3>\\4</a>[\\2",
				"\$this->sanitize_url('\\1', '\\4')",
				"[url=\\1]\\3[/url]",
				"\$this->parse_build_url(array('html' => '\\1', 'show' => '\\1'))",
				"\$this->parse_build_url(array('html' => '\\2', 'show' => '\\3'))",
		), $text);
		$skiptaglist = 'url|email|code|real|music|movie';
		if(preg_match('#(^|\[/(' . $skiptaglist . ')\])(.+(?=\[(' . $skiptaglist . ')|$))#siUe',$text))
		{
			$text = preg_replace('#(^|\[/(' . $skiptaglist . ')\])(.+(?=\[(' . $skiptaglist . ')|$))#siUe',"\$this->convert_url_callback('\\3', '\\1')", $text);
		}
			
		if ($inlink)
		{
			$text = str_replace('<a ', '<a   ', $text);
		}
		return $text;
	}
	
	function bbcode_check($text = '')
	{
		$count = array();
		if (is_array($this->livime->cache['bbcode']) AND count($this->livime->cache['bbcode']))
		{
			foreach($this->livime->cache['bbcode'] AS $i => $r)
			{
				if ($r['twoparams'])
				{
					$count[$r['bbcodeid']]['open'] = substr_count($text, '[' . $r['bbcodetag'] . '=');
					$count[$r['bbcodeid']]['wrongopen'] = substr_count($text, '[' . $r['bbcodetag'] . ']');
				}
				else
				{
					$count[$r['bbcodeid']]['open'] = substr_count($text, '[' . $r['bbcodetag'] . ']');
					$count[$r['bbcodeid']]['wrongopen'] = substr_count($text, '[' . $r['bbcodetag'] . '=');
				}
				$count[$r['bbcodeid']]['closed'] = substr_count($text, '[/' . $r['bbcodetag'] . ']');
				if ($count[$r['bbcodeid']]['open'] != $count[$r['bbcodeid']]['closed'])
				{
					$this->error = ($count[$r['bbcodeid']]['wrongopen'] == $count[$r['bbcodeid']]['closed']) ? $this->livime->lang['_bbcodeerror1'] : $this->livime->lang['_bbcodeerror2'];
				}
				else
				{
					if (in_array($r['bbcodetag'], array("music", "movie", "real")))
					{
						$text = preg_replace("#(|(\[url=(?:&quot;|&\#39;)?(.+?)(?:&quot;|&\#39;)?\]))(\[" . $r['bbcodetag'] . "\])(.*)(\[/" . $r['bbcodetag'] . "\])(\[/url\]|)#siUe", "\$this->strip_url('\\5', '\\4', '\\6')", $text);
					}
				}
			}
		}
		return $text;
	}
	
	function censoredwords($text = '')
	{
		if ($text == '')
		{
			return '';
		}
		if (intval($this->user['passbadword']) == 1)
		{
			return $text;
		}
		$this->livime->func->check_cache('badword');
		if (is_array($this->livime->cache['badword']))
		{
			usort($this->livime->cache['badword'] , array('class_codeparse', 'word_length_sort'));
			if (count($this->livime->cache['badword']) > 0)
			{
				foreach($this->livime->cache['badword'] AS $idx => $r)
				{
					$replace = $r['badafter'] == '' ? '******' : $r['badafter'];
					$r['badbefore'] = preg_quote($r['badbefore'], '/');
					$text = ($r['type'] == 1) ? preg_replace("/(^|\b)" . $r['badbefore'] . "(\b|!|\?|\.|,|$)/i", "$replace", $text) : preg_replace("/" . $r['badbefore'] . "/i", "$replace", $text);
				}
			}
		}
		return $text;
	}
	
}