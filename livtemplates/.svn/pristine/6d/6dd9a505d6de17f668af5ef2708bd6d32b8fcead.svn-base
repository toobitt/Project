{code}
$myPaixu = '
	<a class="lb" onclick="hg_row_interactive(\'#r_'.$v[id].'\', \'click\', \'cur\');" name="alist[]">
		<input type="checkbox" name="infolist[]" value="'.$v[$primary_key].'" title="'.$v[$primary_key].'" />
	</a>
';	
$myFengmian = '
	<div class="fengmian-inner">
		<div style="position:relative">
			<img _src="'.$v['url'].'" id="img_'.$v['id'].'"  />
		</div>
	</div>
';
	
$myFabu = '';                 
if ($v['pubinfo']) {
	foreach ($v['pubinfo'] as $kk => $vv) {
		$cu = current($vv);
		$myFabu .= '<span class="common-list-pub">'.$cu.'</span>';
	}
}
$myFenlei = '<div class="overflow">'.$v['sort_name'].'</div>';
$myName = '<div class="overflow">'.$v['name'].'</div>';
$myStatus = '<span id="contribute_audit_'.$v['id'].'">'.$v['audit'].'</span>';
$myRiqi = '<div class="overflow">'.$v['pub_date'].'</div>';
$myRen = '<span class="name">'.$v['user_name'].'</span><span class="time">'.$v['create_time'].'</span>';
$title = '<span class="biaoti-content"><a href="./run.php?mid='.$relate_module_id.'&a=show&issue_id='.$v['id'].'&infrm=1">'.$v['year'].'第'.$v['issue'].'期</a></span>';
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
					'class' => 'mingcheng',
					'innerHtml' => $myName,
				),
				array(
					'class' => 'zhuangtai',
					'innerHtml' => $myStatus
				),
				array(
					'class' => 'riqi',
					'innerHtml' => $myRiqi
				),
				array(
					'class' => 'ren',
					'innerHtml' => $myRen
				)
			)
		),
		'biaoti' => array(
			//'class' => 'option-iframe',
			'attr' => 'href="./run.php?mid='.$relate_module_id.'&a=show&issue_id='.$v['id'].'&infrm=1"',
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


