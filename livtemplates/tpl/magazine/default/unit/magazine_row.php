{code}
$myPaixu = '
	<a class="lb" onclick="hg_row_interactive(\'#r_'.$v[id].'\', \'click\', \'cur\');" name="alist[]">
		<input type="checkbox" name="infolist[]" value="'.$v[$primary_key].'" title="'.$v[$primary_key].'" />
	</a>
';		
$myFengmian = '<img src="'.$v['url'].'" id="img_'.$v['id'].'"  />';
	
$myFabu = '';                 
if ($v['pubinfo']) {
	foreach ($v['pubinfo'] as $kk => $vv) {
		$cu = current($vv);
		$myFabu .= '<span class="common-list-pub">'.$cu.'</span>';
	}
}
$myFenlei = '<div class="overflow">'.$v['sort_name'].'</div>';
$myZhouqi = '<div>'.$_configs['release_cycle'][$v['release_cycle']].'</div>';
$myQihao = '<div>'.$v['year'].'第'.$v['issue'].'期&nbsp;总第'.$v['volume'].'期</div>';
$myStatus = '<span id="contribute_audit_'.$v['issue_id'].'">'.$v['audit'].'</span>';
$myQishu = '<span>'.$v['mana_nper'].'</span>';
$myRen = '<span class="name">'.$v['user_name'].'</span><span class="time">'.$v['create_time'].'</span>';
$title = '<span class="biaoti-content"><a id="title_'.$v['id'].'" href="./run.php?mid='.$relate_module_id.'&maga_id='.$v['id'].'&cur_nper='.$v['current_nper'].'&infrm=1">'.$v['name'].'</a></span>';
$rowData = array(
	'left' => array(
		'paixu' => $myPaixu,
		'fengmian' => $myFengmian,
	),
	'right' => array(
		'fenlei' => $myFenlei,
		'zhouqi' => $myZhouqi,	
		'qihao' => $myQihao,
		'zhuangtai' => $myStatus,
		'qishu' => $myQishu,
		'ren' => $myRen
	),
	'title' => array(
		'biaoti' =>	$title
	)
);
$rowData = array(
	'attr' => '_id="'.$v['id'].'" id="r_'.$v['id'].'" name="'.$v['id'].'" orderid="'.$v['order_id'].'"',
	'innerHtml' => array(
		'left' => array(
			'innerHtml' => array(
				array(
					'class' => 'paixu',
					'innerHtml' => $myPaixu
				),
				array(
					'class' => 'fengmian',
					'innerHtml' => $myFengmian
				)
			)
		),
		'right' => array(
			'innerHtml' => array(
				array(
					'class' => 'fenlei',
					'innerHtml' => $myFenlei
				),
				array(
					'class' => 'zhuoqi',
					'innerHtml' => $myZhouqi
				),
				array(
					'class' => 'qihao',
					'innerHtml' => $myQihao
				),
				array(
					'class' => 'zhuangtai',
					'innerHtml' => $myStatus
				),
				array(
					'class' => 'qishu',
					'innerHtml' => $myQishu
				),
				array(
					'class' => 'ren',
					'innerHtml' => $myRen
				)
			)
		),
		'biaoti' => array(
			//'class' => 'option-iframe',
			'attr' => 'href="./run.php?mid='.$relate_module_id.'&maga_id='.$v['id'].'&infrm=1"',
			'innerHtml' => array(
				array(
					'class' => 'biaoti biaoti-transition',
					'innerHtml' => $title
				)
			)
		)
	)
);

{/code}
{template:list/list_row}