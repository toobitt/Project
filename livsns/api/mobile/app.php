<?php
$app = array(
/*	array(
		'tab' => 'home',
		'father' => '',
		'data' => array(
			'title' => '首页',
			'url' => '/ui/main/Home.js',
			'barColor' => '#132a51',
			'backgroundColor' => '#fff',
			'backgroundImage' => '',
			'titleImage' => 'images/logo.png',
			'OrientationModes' => array(1),
			'tab' => array(
				'icon' => 'images/index.png'
			),
			'haschild' => 1,
		),
	),
	*/
	array(
		'tab' => 'live',
		'father' => '',
		'data' => array(
			'title' => '直播',
			'url' => '/ui/main/Live.js',
			'barColor' => '#f2f2f2',
			'backgroundColor' => '#000',
			'backgroundImage' => 'images/live-bg.png',
			//'titleImage' => 'images/logo.png',
			'OrientationModes' => array(1),
			'tab' => array(
				'icon' => 'images/live.png',
				'activeIcon' => 'images/live-active.png',
			)
		),
	),
	array(
		'tab' => 'column',
		'father' => '',
		'data' => array(
			'title' => '栏目',
			'url' => '/ui/main/Column.js',
			'barColor' => '#f2f2f2',
			'backgroundColor' => '#fff',
			'backgroundImage' => '',
			//'titleImage' => 'images/logo.png',
			'OrientationModes' => array(1),
			'tab' => array(
				'icon' => 'images/column.png',
				'activeIcon' => 'images/column-active.png',
			)
		),
	),
	array(
		'tab' => 'special',
		'father' => '',
		'data' => array(
			'title' => '专题',
			'url' => '/ui/main/Special.js',
			'barColor' => '#f2f2f2',
			'backgroundColor' => '#fff',
			'backgroundImage' => '',
			//'titleImage' => 'images/logo.png',
			'OrientationModes' => array(1),
			'tab' => array(
				'icon' => 'images/news.png',
				'activeIcon' => 'images/news-active.png',
			)
		),
	),
	array(
		'tab' => 'more',
		'father' => '',
		'data' => array(
			'title' => '更多',
			'url' => '/ui/main/More.js',
			'barColor' => '#f2f2f2',
			'backgroundColor' => '#fff',
			'backgroundImage' => '',
			//'titleImage' => 'images/logo.png',
			'OrientationModes' => array(1),
			'tab' => array(
				'icon' => 'images/setting.png',
				'activeIcon' => 'images/setting-active.png',
			)
		),
	),
);
$app_child = array(
	'home' =>  array(
		array(
			'tab' => 'home',
			'father' => '',
			'data' => array(
				'title' => '首页',
				'url' => '/ui/main/Home.js',
				'barColor' => '#132a51',
				'backgroundColor' => '#fff',
				'backgroundImage' => 'images/live-bg.png',
				'titleImage' => 'images/logo.png',
				'OrientationModes' => array(1),
				'tab' => array(
					'icon' => 'images/index.png'
				)
			),
		),
		array(
			'tab' => 'live',
			'father' => '',
			'data' => array(
				'title' => '直播',
				'url' => '/ui/main/Live.js',
				'barColor' => '#132a51',
				'backgroundColor' => '#fff',
				'backgroundImage' => 'images/live-bg.png',
				//'titleImage' => 'images/logo.png',
				'OrientationModes' => array(1),
				'tab' => array(
					'icon' => 'images/live.png'
				)
			),
		),
	)
);
if (!$_GET['app'])
{
	echo json_encode($app);
}
else
{
	echo json_encode($app_child[$_GET['app']]);
}

?>