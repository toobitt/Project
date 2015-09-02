{code}
$navs = array(
	array(
		'name' => '首页',
		'page_name' => 'index',
		'href' => 'index.php',
	), 
	array(
		'name' => '新闻',
		'page_name' => 'news', 
		'href' => 'news.php',
	),
	array(
		'name' => '直播',
		'page_name' => 'live', 
		'href' => 'live.php',
	), 
	array(
		'name' => '视频',
		'page_name' => 'vedio',
		'href' => 'vedio.php',
	), 
	array(
		'name' => '点播',
		'page_name' => 'dianbo', 
		'href' => 'dianbo.php',
	), 
	array(
		'name' => '图片',
		'page_name' => 'photo', 
		'href' => 'photo.php',
	), 
	array(
		'name' => '全部',
		'page_name' =>'all',
		'href' => 'index.php',
	), 
);
{/code}


<div class="header-bg">
     <div class="head layout">
          <h1 class="logo fl">M2O新媒体综合运营平台</h1>
          <div class="fr mt15">
               <div class="search-area fl">
                    <a class="search-icon"></a>
                    <input type="text" class="search-txt"/>
               </div>
               <ul class="news-list fl">
               	   <li><a href="#">站票打折</a></li>
               	   <li><a href="#">钓岛问题</a></li>
               	   <li><a href="#">抢票插件</a></li>
               </ul>
          </div>
     </div>
</div>
<div class="nav-bg">
     <div class="nav layout">
          <ul class="menu">
          {foreach $navs as $v}
          	<li {if $v['page_name'] == $data['page_name']}class="on"{/if}>
          		<a {if $v['page_name'] == 'all'}class="all"{/if} href="{$v['href']}">{$v['name']}</a>
          		{if $v['page_name'] == 'all'}
          		<ul class="nav-more">
          			<li><a>票务</a></li>
          			<li><a>路况</a></li>
          			<li><a>杂志</a></li>
          		</ul>
          		{/if}
          	</li>
          {/foreach}
          </ul>
     </div>
</div>