<?php
class obj_node
{
	public $original_node = array();
	public $node = array();
	public $values = array();
	public $length = 0;

	function load($content , $partern = '')
	{
		if(!$partern)
		{
			if(stristr(PHP_OS , 'win') !== false)
			{
				$partern = "\r\n";
			}
			else
			{
				$partern = "\n";
			}
		}
		$content = explode($partern , $content);
		$blank = $blank_count = 0;
		foreach($content as $k => $v)
		{
			$v = trim($v);
			if($v == '')
			{
				$blank = 1;
				$blank_count ++;
			}
			else
			{
				$this->node[] = array('index' => $k , 'value' => $v , 'blank_count' => $blank_count);
				$this->values[] = $v;
				$this->length ++;
				$blank = $blank_count = 0;
			}
			$this->original_node[] = $v;
		}
	}

	function indexOf($value , $baseIndex = 0)
	{
		$blankcount = 0;
		for($i = $baseIndex ; $i < $this->length ; $i++)
		{
			$blankcount += $this->node[$i]['blank_count'];
			if($this->node[$i]['value'] == $value)
			{
				return array($i , $blankcount);
			}
			$blankcount ++;
		}
		return -1;
	}

	function similar($str  ,$baseIndex)
	{
		return similar_text($this->node[$baseIndex]['value'] . $this->node[$baseIndex + 1]['value'] , $str);
	}

	function levenshtein($str  ,$baseIndex)
	{
		return @levenshtein($this->node[$baseIndex]['value'] . $this->node[$baseIndex + 1]['value'] , $str);
	}


}

#+++++ index of
function max_similar(&$node1 , $index1 , &$node2 , $index2 , $caller = '')
{
	$start_search = $similar = 0;
	for($i = $index1; $i < $node1->length ;  $i++)
	{
		if($node1->node[$i]['value'] == $node2->node[$index2]['value'])
		{
			if(!$start_search)
			{
				#echo "[$caller][$i]start...<br/>";
				$start_search = $i;
			}
			#echo "[$caller][$i]".htmlspecialchars($node1->node[$i]['value'])."=>".htmlspecialchars($node2->node[$index2]['value']).'<br />';
			$similar++;
			$index2++;
		}
		else
		{
			if($start_search)
			{
				#echo "[$caller][$i]not same ......search ending;<br />";
				break;
			}
			#echo "[$caller][$i]".htmlspecialchars($node1->node[$i]['value'])."=>".htmlspecialchars($node2->node[$index2]['value']).'<br />';
			#if $node2->node[$index2]['value'] not in node1 skip
			if($node1->indexOf($node2->node[$index2]['value'], $i ) == -1)
			{
				$index2++;
			}
		}
	}
	return array($similar , $start_search);
}

function caculate_similar(&$node1 , &$node2)
{
	$cnode1 = new obj_node();
	$cnode2 = new obj_node();
	$cnode1->load($node1['value'] , ' ');
	$cnode2->load($node2['value'] , ' ');

	$diff2 = array_diff($cnode2->values , $cnode1->values);
	$diff1 = array_diff($cnode1->values , $cnode2->values);
	if($diff2)
	{
		if($diff2 == $cnode2->values)
		{
			return false;
		}
		$blank = $node2['value'] = '';
		foreach($cnode2->values as $k => $v)
		{
			$flag = in_array($v , $diff2);
			$node2['value'] .= $blank . '<!'.($flag ? 'd':'s').'>'.$v.'</!>';
			$blank = '<!'.($flag ? 'd':'s').'> </!>';
		}
		//echo $node2['value'];
		$blank = $node1['value'] = '';
		foreach($cnode1->values as $k => $v)
		{
			$flag = in_array($v , $diff1);
			$node1['value'] .= $blank . '<!'.($flag ? 'd':'s').'>'.$v.'</!>';
			$blank = '<!'.($flag ? 'd':'s').'> </!>';
		}
		$node1['same'] = 1;
		$node2['same'] = 1;
	}
}

function analyse($str1 , $str2)
{
	$node1 = new obj_node();
	$node2 = new obj_node();
	$node1->load($str1);
	$node2->load($str2);

	$index1 = $index2 = 0;
	while(1)
	{
		if($index1 == $node1->length || $index2 == $node2->length)
		{
			break;
		}
	 	if($node1->node[$index1]['value'] == $node2->node[$index2]['value'])
		{
			$max_blank = max($node1->node[$index1]['blank_count'] , $node2->node[$index2]['blank_count']);
			!isset($node1->node[$index1]['fix']) && $node1->node[$index1]['fix'] += $max_blank - $node1->node[$index1]['blank_count'];
			!isset($node2->node[$index2]['fix']) && $node2->node[$index2]['fix'] += $max_blank - $node2->node[$index2]['blank_count'];
			$node1->node[$index1]['same'] = 1;
			$node2->node[$index2]['same'] = 1;
			$index1++;
			$index2++;
			#echo '#Result:same<br />';
		}
		else
		{
			$ainb = $node2->indexOf($node1->node[$index1]['value'] , $index2);
			$bina = $node1->indexOf($node2->node[$index2]['value'] , $index1);
			#current a in restof b || current b in restof a
			if($ainb != -1 && $bina != -1)
			{
				//2009-12-18 17:07 ���ҲҪ��Ϊ���� ���㷨
				#echo '<br />';
				#echo '$[1]['.$index1.'] = '.$node1->node[$index1]['value'].' found at $[2]['.$ainb[0] .']<br />';
				#echo '$[2]['.$index2.'] = '.$node2->node[$index2]['value'].' found at $[1]['.$bina[0] .']<br />';
				#echo '#result:fix ';
				#echo '  %1 = '.$node2->similar($node1->node[$index1]['value'] . $node1->node[$index1 + 1]['value'] , $ainb[0]);
				#echo '  %2 = '.$node1->similar($node2->node[$index2]['value'] . $node2->node[$index2 + 1]['value'] , $bina[0]);
				#echo '<br />';
				#read 1 more line and compare
				if($node2->levenshtein($node1->node[$index1]['value'] . $node1->node[$index1 + 1]['value'] , $ainb[0]) <= $node1->levenshtein($node2->node[$index2]['value'] . $node2->node[$index2 + 1]['value'] , $bina[0]))
				#if($node2->similar($node1->node[$index1]['value'] . $node1->node[$index1 + 1]['value'] , $ainb[0]) <= $node1->similar($node2->node[$index2]['value'] . $node2->node[$index2 + 1]['value'] , $bina[0]))
				{
					#echo '#ignor $[2][' . $index2 . ']<br />';
					$node1->node[$index1]['fix'] += $ainb[1];
					$node2->node[$ainb[0]]['fix'] += $node1->node[$index1]['blank_count'];
					#echo '#skip to $[2][' . $ainb[0] . ']<br /><br />';
					$index2 = $ainb[0];
				}
				else
				{
					#echo '#ignor $[1][' . $index1 . ']<br /><br />';
					$node1->node[$bina[0]]['fix'] += $node2->node[$index2]['blank_count'];
					$node2->node[$index2]['fix'] += $bina[1];
					$index1 = $bina[0];
				}
			}
			else if($ainb != -1)
			{
				$max_similar1 = max_similar($node1 , $index1, $node2 , $ainb[0] , '1');
				$max_similar2 = max_similar($node2 , $index2 , $node1 , $index1+1 , '2');
				if($max_similar1[0] > $max_similar2[0])
				{
					#echo '$[1]['.$index1.'] = '.$node1->node[$index1]['value'].' found at $[2]['.$ainb[0] .']<br />';
					#echo '#ignor $[2][' . $index2 . ']<br />';
					$node1->node[$index1]['fix'] += $ainb[1];
					$node2->node[$ainb[0]]['fix'] += $node1->node[$index1]['blank_count'];
					#echo '#skip to $[2][' . $ainb[0] . ']<br /><br />';
					$index2 = $ainb[0];
				}
				else
				{
					$fix = $node1->indexOf($node2->node[$max_similar2[1]]['value'] , $index1);
					$blank_count = 0;
					for($i = $index2 ;$i<$max_similar2[1] ; $i++)
					{
						$blank_count += $node2->node[$i]['blank_count'] + 1;
					}
					$node1->node[$fix[0]]['fix'] += $blank_count + $node2->node[$max_similar2[1]]['blank_count'];
					$node2->node[$max_similar2[1]]['fix'] += $fix[1];
					$index1 = $fix[0];
					$index2 = $max_similar2[1];
				}
			}
			else if($bina != -1)
			{
				#echo '#ignor $[1][' . $index1 . ']<br /><br />';
				$node1->node[$bina[0]]['fix'] += $node2->node[$index2]['blank_count'];
				$node2->node[$index2]['fix'] += $bina[1];
				$index1 = $bina[0];
			}
			else
			{
				$max_blank = max($node1->node[$index1]['blank_count'] , $node2->node[$index2]['blank_count']);
				$node1->node[$index1]['fix'] += $max_blank - $node1->node[$index1]['blank_count'];
				$node2->node[$index2]['fix'] += $max_blank - $node2->node[$index2]['blank_count'];
				//$levenshtein = levenshtein($node1->node[$index1]['value'] , $node2->node[$index2]['value']);
				$similar = similar_text($node1->node[$index1]['value'] , $node2->node[$index2]['value']);
				#echo $similar;
				#if(($similar + $levenshtein == strlen($node1->node[$index1]['value'])) || ($similar + $levenshtein == strlen($node2->node[$index2]['value'])))
				#{
				#	$node1->node[$index1]['same'] = 1;
				#	$node2->node[$index2]['same'] = 1;
				#}

				//caculate_similar(&$node1->node[$index1] , &$node2->node[$index2]);
                //allow_call_time_pass_reference = Off时会出错 应在函数定义时指定参数传递类型
                caculate_similar($node1->node[$index1] , $node2->node[$index2]);
				//$return = caclulate_similar_text($node1->node[$index1]['value'] , $node2->node[$index2]['value']);
				//$node1->node[$index1]['value'] = print_text($return[0]);
				//$node2->node[$index2]['value'] = print_text($return[1]);

				#$node1->node[$index1]['value'] = $node1->node[$index1]['value'] . $levenshtein .'/' . $similar .'/' . mb_strlen($node1->node[$index1]['value']);
				#$node2->node[$index2]['value'] = $node2->node[$index2]['value'] . $levenshtein .'/' . $similar .'/' . mb_strlen($node2->node[$index2]['value']);

				$index1++;
				$index2++;
			}
		}
	}
	return array($node1 , $node2);
}

function draw_table($obj  , $withline = false , $table_pre = '')
{
	$line = $index = 0;
	for($i = 0;$i< $obj->length ; $i++)
	{
		if($obj->node[$i]['fix'])
		{
			for($j = 0 ; $j< $obj->node[$i]['fix'];$j++)
			{
				$str .= '<tr id="'.$table_pre.$index.'" class="fix">'.($withline ? '<td class="line">&nbsp;</td>':'').'<td>&nbsp;</td></tr>';
				$index++;
			}
		}
		if($obj->node[$i]['blank_count'])
		{
			for($j = 0 ; $j< $obj->node[$i]['blank_count'];$j++)
			{
				$str .= '<tr id="'.$table_pre.$index.'" class="blank">'.($withline ? '<td class="line">'.(++$line).'</td>':'').'<td>&nbsp;</td></tr>';
				$index++;
			}
		}
		$line = $obj->node[$i]['index'] + 1;

		$code = htmlspecialchars($obj->node[$i]['value']);
		$code = str_replace(array('&lt;!d&gt;' , '&lt;!s&gt;' , '&lt;/!&gt;') , array('<span class="span_diff">','<span class="span_same">' , '</span>') , $code);



		$str .= '<tr id="'.$table_pre.$index.'" class="'.($obj->node[$i]['same'] ? 'same':'notsame').'">'.($withline ? '<td class="line '.($obj->node[$i]['same'] ? '':'notsame').'">'.($line).'</td>':'').'<td class="code">'.$code.'</td></tr>';
		$index++;
	}
	return array($str , $line , $index);
}



class object_text_node
{
	public $length = 0;
	public $chars = array();

	function __construct($str = '' , $charset = 'UTF-8')
	{
		$this->load($str , $charset);
		mb_internal_encoding($charset);

	}

	function load($str = '' , $charset = 'UTF-8')
	{
		$this->length = mb_strlen($str , $charset);
		$split=1;
		for ($i=0; $i<strlen($str);)
		{
			$value = ord($str[$i]);
			if($value > 127)
			{
				if($value >= 192 && $value <= 223)
				{
					$split = 2;
				}
				elseif($value >= 224 && $value <= 239)
				{
					$split = 3;
				}
				elseif($value >= 240 && $value <= 247)
				{
					$split = 4;
				}
			}
			else
			{
				$split=1;
			}
			$key = null;
			for ($j=0; $j<$split; $j++,$i++)
			{
				$key .= $str[$i];
			}
			$this->chars[] = array('char' => $key);
		}
	}

	function indexOf($char , $index)
	{
		for($i = $index;$i< $this->length ; $i++)
		{
			if($this->chars[$i]['char'] === $char)
			{
				return $i;
			}
		}
		return -1;
	}
}

function caclulate_similar_text($str1 = '' , $str2 = '')
{
	$text1 = new object_text_node($str1);
	$text2 = new object_text_node($str2);
	$index1 = $index2 = 0;
	$single_char = '<span class="char missing">&nbsp;</span>';
	while(1)
	{
		if($index1 == $text1->length || $index2 == $text2->length)
		{
			break;
		}

		$char1 = $text1->chars[$index1]['char'];
		$char2 = $text2->chars[$index2]['char'];

		$indexof2 = $text1->indexOf($char2 , $index1);
		$indexof1 = $text2->indexOf($char1 , $index2);


		#both not found
		if($indexof1 == -1 && $indexof2 == -1)
		{
			$text1->chars[$index1]['prefix'] .= '<!--ns-->';
			$text1->chars[$index1]['endfix'] = '<!--/ns-->';

			$text2->chars[$index2]['prefix'] .= '<!--ns-->';
			$text2->chars[$index2]['endfix'] = '<!--/ns-->';
			$index1++;
			$index2++;
		}
		else if($indexof1 == -1 )
		{
			#echo '#skip:[1]['.$index1.']<br />';
			$text1->chars[$index1]['prefix'] .= '<!--ns-->';
			$text1->chars[$index1]['endfix'] = '<!--/ns-->';
			$text2->chars[$index2]['prefix'] .= $single_char;
			$index1++;
		}
		else if($indexof2 == -1)
		{
			#echo '#skip:[2]['.$index2.']<br />';
			$text2->chars[$index2]['prefix'] .= '<!--ns-->';
			$text2->chars[$index2]['endfix'] = '<!--/ns-->';
			$text1->chars[$index1]['prefix'] .= $single_char;
			$index2++;
		}
		else
		{
			if($indexof2 == $index1 && $indexof1 == $index2)
			{
				$text1->chars[$index1]['prefix'] .= '<!--s-->';
				$text1->chars[$index1]['endfix'] = '<!--/s-->';

				$text2->chars[$index2]['prefix'] .= '<!--s-->';
				$text2->chars[$index2]['endfix'] = '<!--/s-->';
			}
			else
			{
				$text1->chars[$index1]['prefix'] .= '<!--ns-->';
				$text1->chars[$index1]['endfix'] = '<!--/ns-->';

				$text2->chars[$index2]['prefix'] .= '<!--ns-->';
				$text2->chars[$index2]['endfix'] = '<!--/ns-->';
			}
			$index1 ++;
			$index2 ++;
		}
		echo '<br />';

	}

	return array($text1->chars , $text2->chars);
}

function max_similar_line(&$text1 , $index1, &$text2 ,$index2)
{
	$search_start1 = $search_start2 = $search_end1 = $search_end2 = $start_search = $max_similar = $similar  = 0;
	for($i = $index2 ; $i < $text2->length ; $i++)
	{
		#echo '[1]['.$index1.'] [2]['.$i.']';
		if($text2->chars[$i]['char'] == $text1->chars[$index1]['char'])
		{
			#echo ' == <br />';
			#��û�п�ʼѰ�� ��¼}�߳�ʼλ��
			if(!$start_search)
			{
				#echo '[1]['.$index1.'] [2]['.$i.']<br />';
				#$start_search = true;
				$search_start1 = $index1;
				$search_start2 = $i;
			}
			$similar ++;
			$index1++;
		}
		else
		{
			#echo ' != <br />';
			#����Ѿ���ʼѰ����
			if($start_search)
			{
				#���ǰѭ����������ƶȴ�����ʷ��������ƶ�
				#��¼ѭ�������λ��
				#����Ҫ��ѯ����ݽ��и�λ
				if($similar > $max_similar)
				{
					$max_similar = $similar;
					$search_end1 = $index1 - 1;
					$search_end2 = $i - 1;
				}
				$index1 = $search_start1;
			}
			$start_search = false;
			$similar = 0;
		}
	}

	return array(
			'max_similar'	=> $max_similar ,
			'search_start1'	=> $search_start1 ,
			'search_end1'	=> $search_end1,
			'search_start2'	=> $search_start2 ,
			'search_end2'	=> $search_end2,
		);
}

function get_next_similar_text(&$text1 , $index1 , &$text2 , $index2)
{
	$similar = 0;
	$search_start = false;
	for($i = $index1 ; $i< $text1->length ; $i++)
	{
		if($text1->chars[$i]['char'] == $text2->chars[$index2]['char'])
		{
			if(!$search_start)
			{
				$search_start = 1;
			}
			$index2++;
		}
		else
		{

		}
	}
}

function print_text($str)
{
	$return = '';
	if(!is_array($str)) return $str;
	foreach($str as $v)
	{
		if($v['prefix'])
		{
			$start = true;
			$return .= str_replace(array('<!--s-->' , '<!--ns-->') , array('<span class="same">','<span class="diff">') , $v['prefix']);
		}
		if(!$start && !$v['prefix'])
		{
			$return .= '<span class="diff">';
		}
		$return .= '<span class="char">'.htmlspecialchars($v['char']).'</span>';
		if(!$start && !$v['endfix'])
		{
			$return .= '</span>';
		}
		if($v['endfix'])
		{
			$start = false;
			$return .= str_replace(array('<!--/s-->' , '<!--/ns-->') ,'</span>' , $v['endfix']);
		}
	}
	return $return;

}



?>