<?php
$Cfg = array(
	'server' => array(
		'nginx' => array(
			'name' => 'nginx',
			'restart' => '/usr/local/nginx/sbin/nginx -s reload',
		),
		'apache' => array(
			'name' => 'apache',
			'restart' => '/usr/local/apache/bin/apachectl restart',
		),
	),
	'servertype' => array(
		'db' => array(
			'name' => '数据库服务器', 
			'host' => 'localhost', 
			'port' => '3306', 
			'user' => 'root', 
			'pass' => '', 
			'servtyp' => '', 
			'conf' => array(
				'hosts' => '/etc/hosts',
				'mysql' => '/etc/my.cnf'
			),
		),
		'app' => array(
			'name' => '应用服务器', 
			'domain' => 'mcp.', 
			'dir' => '', 
			'db' => 1,
			'servtyp' => 'nginx', 
			'conf' => array(
				'hosts' => '/etc/hosts',
				'php' => '/usr/local/php/lib/php.ini',
				'php-fpm' => '/usr/local/php/etc/php-fpm.conf',
				'nginx' => '/usr/local/nginx/conf/nginx.conf'
			)
		),
		'api' => array(
			'name' => '接口服务器', 
			'domain' => 'api.', 
			'dir' => '', 
			'db' => 2,
			'servtyp' => 'nginx', 
			'conf' => array(
				'hosts' => '/etc/hosts',
				'php' => '/usr/local/php/lib/php.ini',
				'php-fpm' => '/usr/local/php/etc/php-fpm.conf',
				'nginx' => '/usr/local/nginx/conf/nginx.conf'
			)
		),
		'img' => array(
			'name' => '图片服务器', 
			'domain' => 'imgapi.', 
			'dir' => '', 
			'uploaddir' => '../../uploads/', 
			'uploaddomain' => 'img.', 
			'rewrite' => '', 
			'servtyp' => 'nginx', 
			'db' => 1,
			'conf' => array(
				'hosts' => '/etc/hosts',
				'php' => '/usr/local/php/lib/php.ini',
				'php-fpm' => '/usr/local/php/etc/php-fpm.conf',
				'nginx' => '/usr/local/nginx/conf/nginx.conf'
			)
		),
		/*'dvr' => array(
			'name' => '时移服务器', 
			'domain' => 'dvr.',
			'dvrdir' => './dvr/',
			'conf' => array(
				'hosts' => '/etc/hosts',
				'Application' => '/usr/local/WowzaMediaServer/conf/output.xml',
				'license' => '/usr/local/WowzaMediaServer/conf/Server.license'
			)
		),*/
		'live' => array(
			'name' => '直播服务器',
			'domain' => 'live.',
			'conf' => array(
				'hosts' => '/etc/hosts',
				'Application' => '/usr/local/WowzaMediaServer/conf/Application.xml',
				'license' => '/usr/local/WowzaMediaServer/conf/Server.license'
			)
		),
		'record' => array(
			'name' => '录制服务器', 
			'recorddir' => './record/',
			'conf' => array(
				'hosts' => '/etc/hosts',
			)
		),
		'transcode' => array(
			'name' => '视频转码服务器',
			'conf' => array(
				'hosts' => '/etc/hosts',
			)
		),
		'mediaserver' => array(
			'name' => '视频上传服务器', 
			'domain' => 'vapi.', 
			'dir' => '', 
			'uploaddir' => '',
			'targetdir' => '', 
			'rewrite' => '', 
			'servtyp' => 'nginx', 
			'db' => 1,
			'conf' => array(
				'hosts' => '/etc/hosts',
				'php' => '/usr/local/php/lib/php.ini',
				'php-fpm' => '/usr/local/php/etc/php-fpm.conf',
				'nginx' => '/usr/local/nginx/conf/nginx.conf',
			)
		),
		'vodplay' => array(
			'name' => '视频播放服务器', 
			'targetdir' => '', 
			'vodomain' => 'vod.',
			'servtyp' => 'apache', 
			'conf' => array(
				'hosts' => '/etc/hosts',
				'php' => '/usr/local/php/lib/php.ini',
				'apache' => '/usr/local/apache/conf/extra/httpd-vhosts.conf'
			)
		),
	),
);
$Lang = array(
	'host' => '主机',
	'user' => '账号',
	'pass' => '密码',
	'domain' => '域名',
	'dir' => '目录',
	'uploaddir' => '图片存储目录',
	'uploaddomain' => '图片访问域名',
	'rewrite' => 'rewrite规则',
	'dvrdir' => '时移存储目录',
	'recorddir' => '录制存储目录',
	'targetdir' => '转码存储目录',
	'vodomain' => '视频访问域名',
);

$Cfg['serverconf'] = array(
	'default' => array (
		'nginx' => '
			server {
				set $htdocs $DIR;
				listen       80;
				server_name  $DOMAIN;

				#charset koi8-r;

				#access_log  logs/host.access.log  main;

				location / {
					root   $htdocs;
					index  index.html index.htm index.php;
				}
				location ~ .*\.php?$ {
					root          $htdocs;
					fastcgi_pass   127.0.0.1:9000;
					fastcgi_index  index.php;
					fastcgi_param  SCRIPT_FILENAME  $htdocs$fastcgi_script_name;
					include        fastcgi_params;
				}
			}',
		'apache' => '',
	),
	'app' => array(
		'nginx' => '
			server {
				set $htdocs $DIR;
				listen 	80;
				server_name  $DOMAIN;

				charset utf-8;

				#access_log  logs/host.access.log  main;
				location /vod/
						{
						proxy_pass http://$VODDOMAIN/;
								proxy_redirect             off;
						}
				location / {
					root   $htdocs;
					index  index.html index.htm index.php;
				}

				# pass the PHP scripts to FastCGI server listening on 127.0.0.1:9000
				#
				location ~ .*\.php?$ {
					root          $htdocs;
					fastcgi_pass   127.0.0.1:9000;
					fastcgi_index  index.php;
					fastcgi_param  SCRIPT_FILENAME  $htdocs$fastcgi_script_name;
					include        fastcgi_params;
				}
			}',
		'apache' => ''
	),
	'img' => array(
		'nginx' => '
			server {
			root $DIR;
			listen       80;
			server_name  $DOMAIN;
			
			#location ~* ^.+\.(gif|jpg|png|swf|flv|rar|zip)(\?\w*)$ {
				
			#}
			if ( !-e $request_filename )
			{
			  rewrite $ http://$IMGAPIDOMAIN/createfile.php?host=$host&refer_to=$request_uri;
			}
		}',
		'apache' => ''
	),
	'mediaserver' => array(
		'nginx' => '
			server {
				set $htdocs $DIR;
				listen 	80;
				server_name  $DOMAIN;

				charset utf-8;

				rewrite ^/admin/snap/(\d+)/(\d+)/([\d.]*)\-([\d.]*).jpg$ http://$DOMAIN/admin/snap.php?stime=\$2&data=1&id=\$1&width=\$3&height=\$4;
				#access_log  logs/host.access.log  main;
				location / {
					root   $htdocs;
					index  index.html index.htm index.php;
				}

				# pass the PHP scripts to FastCGI server listening on 127.0.0.1:9000
				#
				location ~ .*\.php?$ {
					root          $htdocs;
					fastcgi_pass   127.0.0.1:9000;
					fastcgi_index  index.php;
					fastcgi_param  SCRIPT_FILENAME  $htdocs$fastcgi_script_name;
					include        fastcgi_params;
				}
			}
			',
		'apache' => '
			<VirtualHost *:8009>
				DocumentRoot  $DIR
				ServerName $DOMAIN
				RewriteEngine On
				RewriteCond %{HTTP_HOST} ^$DOMAIN
				RewriteCond %{REQUEST_URI} ^/admin/snap/(.*).jpg$
				RewriteRule ^/snap/(\d+)/(\d+)/([\d.]*)\-([\d.]*).jpg$ http://$DOMAIN/admin/snap.php?stime=$2&data=1&id=$1&width=$3&height=$4 [P]
			</VirtualHost>
		',
	),
	'vodplay' => array(
		'apache' => '
			<VirtualHost *:8009>
				DocumentRoot  $DIR
				ServerName $DOMAIN
			</VirtualHost>
		',
		'nginx' => ''
	),
);
$Cfg['serverapp']['app'] = array(
		'livmcp' => array('name' => 'LivMCP', 'checked' => 'checked="checked"'),
	);	
$Cfg['serverapp']['img'] = array(
		'material' => array('name' => '图片服务', 'checked' => 'checked="checked"'),	
	);
$Cfg['serverapp']['mediaserver'] = array(
		'mediaserver' => array('name' => '上传服务', 'checked' => 'checked="checked"'),	
	);
$Cfg['serverapp']['api'] = array(
		'auth' => array('name' => '授权系统', 'checked' => 'checked="checked"'),	
		'logs' => array('name' => '日志系统', 'checked' => 'checked="checked"'),	
		'recycle' => array('name' => '回收站', 'checked' => 'checked="checked"'),
		'new_live' => array('name' => '直播台', 'checked' => ''),	
		'livmedia' => array('name' => '视频库', 'checked' => ''),
		'news' => array('name' => '文稿库', 'checked' => ''),	
		'tuji' => array('name' => '图集库', 'checked' => ''),		
		'publishcontent' => array('name' => '发布内容', 'checked' => 'checked="checked"'),	
		'publishplan' => array('name' => '发布计划', 'checked' => 'checked="checked"'),		
		'mobile' => array('name' => '手机终端', 'checked' => 'checked="checked"'),
		'player' => array('name' => '播放器接口', 'checked' => 'checked="checked"'),	
		'adv' => array('name' => '广告中心', 'checked' => 'checked="checked"'),	
		'magazine' => array('name' => '杂志库'),
		'message' => array('name' => '评论系统', 'checked' => 'checked="checked"'),	
		'contribute' => array('name' => '爆料中心', 'checked' => 'checked="checked"'),	
		'opinion' => array('name' => '爆料审核', 'checked' => 'checked="checked"'),	
		'access' => array('name' => '访问统计', 'checked' => 'checked="checked"'),			
		'member' => array('name' => '会员中心', 'checked' => 'checked="checked"'),		
		'servermonitor' => array('name' => '服务器监控', 'checked' => ''),	
		'share' => array('name' => '应用分享'),	
		'vote' => array('name' => '投票系统', 'checked' => 'checked="checked"'),	
		'weather' => array('name' => '天气系统', 'checked' => 'checked="checked"'),	
		'weiboGroup' => array('name' => '微博圈'),	
		'verifycode' => array('name' => '验证码', 'checked' => 'checked="checked"'),		
		'email' => array('name' => '邮件'),		
	);
?>