<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8" />
    <meta name="robots" content="all" />
    <meta name="author" content="w3school.com.cn" />
    <link rel="stylesheet" type="text/css" href="/c5.css" />

    <title>PHP extract() 函数</title>

</head>

<body class="serverscripting">

<div id="wrapper">

    <div id="header">
        <a href="/index.html" title="w3school 在线教程" style="float:left;">w3school 在线教程</a>
        <div id="ad_head">
            <script type="text/javascript"><!--
                google_ad_client = "pub-3381531532877742";
                /* 728x90, 创建于 08-12-1 */
                google_ad_slot = "7423315034";
                google_ad_width = 728;
                google_ad_height = 90;
                //-->
            </script>
            <script type="text/javascript"
                    src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
            </script>
        </div>
    </div>

    <div id="navfirst">
        <ul id="menu">
            <li id="h"><a href="/h.asp" title="HTML 系列教程">HTML 系列教程</a></li>
            <li id="b"><a href="/b.asp" title="浏览器脚本教程">浏览器脚本</a></li>
            <li id="s"><a href="/s.asp" title="服务器脚本教程">服务器脚本</a></li>
            <li id="d"><a href="/d.asp" title="ASP.NET 教程">ASP.NET 教程</a></li>
            <li id="x"><a href="/x.asp" title="XML 系列教程">XML 系列教程</a></li>
            <li id="ws"><a href="/ws.asp" title="Web Services 系列教程">Web Services 系列教程</a></li>
            <li id="w"><a href="/w.asp" title="建站手册">建站手册</a></li>
        </ul>
    </div>

    <div id="navsecond">

        <div id="course"><h2>PHP 基础教程</h2>
            <ul>
                <li><a href="/php/index.asp" title="PHP 教程">PHP 教程</a></li>
                <li><a href="/php/php_intro.asp" title="PHP 简介">PHP 简介</a></li>
                <li><a href="/php/php_install.asp" title="PHP 安装">PHP 安装</a></li>
                <li><a href="/php/php_syntax.asp" title="PHP 语法">PHP 语法</a></li>
                <li><a href="/php/php_variables.asp" title="PHP 变量">PHP 变量</a></li>
                <li><a href="/php/php_echo_print.asp" title="PHP Echo 和 Print 语句">PHP Echo / Print</a></li>
                <li><a href="/php/php_datatypes.asp" title="PHP 数据类型">PHP 数据类型</a></li>
                <li><a href="/php/php_string.asp" title="PHP 字符串函数">PHP 字符串函数</a></li>
                <li><a href="/php/php_constants.asp" title="PHP 常量">PHP 常量</a></li>
                <li><a href="/php/php_operators.asp" title="PHP 运算符">PHP 运算符</a></li>
                <li><a href="/php/php_if_else.asp" title="PHP If...Else 语句">PHP If...Else</a></li>
                <li><a href="/php/php_switch.asp" title="PHP Switch 语句">PHP Switch</a></li>
                <li><a href="/php/php_looping.asp" title="PHP while 循环">PHP While 循环</a></li>
                <li><a href="/php/php_looping_for.asp" title="PHP for 循环">PHP For 循环</a></li>
                <li><a href="/php/php_functions.asp" title="PHP 函数">PHP 函数</a></li>
                <li><a href="/php/php_arrays.asp" title="PHP 数组">PHP 数组</a></li>
                <li><a href="/php/php_arrays_sort.asp" title="PHP 数组排序">PHP 数组排序</a></li>
                <li><a href="/php/php_superglobals.asp" title="PHP 超全局变量">PHP 超全局</a></li>
            </ul>
            <h2>PHP 表单</h2>
            <ul>
                <li><a href="/php/php_forms.asp" title="PHP Date()">PHP 表单处理</a></li>
                <li><a href="/php/php_form_validation.asp" title="PHP Include 文件">PHP 表单验证</a></li>
                <li><a href="/php/php_form_required.asp" title="PHP 文件处理">PHP 表单必填</a></li>
                <li><a href="/php/php_form_url_email.asp" title="PHP 文件上传">PHP 表单 URL/E-mail</a></li>
                <li><a href="/php/php_form_complete.asp" title="PHP Cookies">PHP 表单完成</a></li>
            </ul>
            <h2>PHP 高级教程</h2>
            <ul>
                <li><a href="/php/php_arrays_multi.asp" title="PHP 多维数组">PHP 多维数组</a></li>
                <li><a href="/php/php_date.asp" title="PHP Date()">PHP 日期</a></li>
                <li><a href="/php/php_includes.asp" title="PHP Include 文件">PHP Include</a></li>
                <li><a href="/php/php_file.asp" title="PHP 文件处理">PHP 文件</a></li>
                <li><a href="/php/php_file_open.asp" title="PHP 文件上传">PHP 文件打开/读取</a></li>
                <li><a href="/php/php_file_create.asp" title="PHP 文件上传">PHP 文件创建/写入</a></li>
                <li><a href="/php/php_file_upload.asp" title="PHP 文件上传">PHP 文件上传</a></li>
                <li><a href="/php/php_cookies.asp" title="PHP Cookies">PHP Cookies</a></li>
                <li><a href="/php/php_sessions.asp" title="PHP Sessions">PHP Sessions</a></li>
                <li><a href="/php/php_mail.asp" title="PHP 发送电子邮件">PHP E-mail</a></li>
                <li><a href="/php/php_secure_mail.asp" title="PHP 安全的电子邮件">PHP 安全 E-mail</a></li>
                <li><a href="/php/php_error.asp" title="PHP 错误处理">PHP Error</a></li>
                <li><a href="/php/php_exception.asp" title="PHP 异常处理">PHP Exception</a></li>
                <li><a href="/php/php_filter.asp" title="PHP 过滤器（Filter）">PHP Filter</a></li>
            </ul>
            <h2>PHP 数据库</h2>
            <ul>
                <li><a href="/php/php_mysql_intro.asp" title="MySQL 简介">MySQL 简介</a></li>
                <li><a href="/php/php_mysql_connect.asp" title="PHP MySQL 连接数据库">MySQL Connect</a></li>
                <li><a href="/php/php_mysql_create.asp" title="PHP MySQL 创建数据库和表">MySQL Create</a></li>
                <li><a href="/php/php_mysql_insert.asp" title="PHP MySQL Insert Into">MySQL Insert</a></li>
                <li><a href="/php/php_mysql_select.asp" title="PHP MySQL Select">MySQL Select</a></li>
                <li><a href="/php/php_mysql_where.asp" title="PHP MySQL Where 子句">MySQL Where</a></li>
                <li><a href="/php/php_mysql_order_by.asp" title="PHP MySQL Order By 关键词">MySQL Order By</a></li>
                <li><a href="/php/php_mysql_update.asp" title="PHP MySQL Update">MySQL Update</a></li>
                <li><a href="/php/php_mysql_delete.asp" title="PHP MySQL Delete From">MySQL Delete</a></li>
                <li><a href="/php/php_db_odbc.asp" title="PHP Database ODBC">PHP ODBC</a></li>
            </ul>
            <h2>PHP XML</h2>
            <ul>
                <li><a href="/php/php_xml_parser_expat.asp" title="PHP XML Expat 解析器">XML Expat Parser</a></li>
                <li><a href="/php/php_xml_dom.asp" title="PHP XML DOM">XML DOM</a></li>
                <li><a href="/php/php_xml_simplexml.asp" title="PHP SimpleXML">XML SimpleXML</a></li>
            </ul>
            <h2>PHP 和 AJAX</h2>
            <ul>
                <li><a href="/php/php_ajax_intro.asp" title="AJAX XMLHttpRequest">AJAX 简介</a></li>
                <li><a href="/php/php_ajax_xmlhttprequest.asp" title="AJAX XMLHttpRequest">XMLHttpRequest</a></li>
                <li><a href="/php/php_ajax_suggest.asp" title="PHP 和 AJAX 请求">AJAX Suggest</a></li>
                <li><a href="/php/php_ajax_xml.asp" title="PHP 和 AJAX XML 实例">AJAX XML</a></li>
                <li><a href="/php/php_ajax_database.asp" title="PHP 和 AJAX MySQL 数据库实例">AJAX Database</a></li>
                <li><a href="/php/php_ajax_responsexml.asp" title="PHP 和 AJAX responseXML 实例">AJAX responseXML</a></li>
                <li><a href="/php/php_ajax_livesearch.asp" title="PHP 和 AJAX Live Search">AJAX Live Search</a></li>
                <li><a href="/php/php_ajax_rss_reader.asp" title="PHP 和 AJAX RSS 阅读器">AJAX RSS Reader</a></li>
                <li><a href="/php/php_ajax_poll.asp" title="PHP 和 AJAX 投票">AJAX Poll</a></li>
            </ul>
            <h2>PHP 参考手册</h2>
            <ul>
                <li><a href="/php/php_ref_array.asp" title="PHP Array 函数">PHP Array</a></li>
                <li><a href="/php/php_ref_calendar.asp" title="PHP Calendar 函数">PHP Calendar</a></li>
                <li><a href="/php/php_ref_date.asp" title="PHP Date / Time 函数">PHP Date</a></li>
                <li><a href="/php/php_ref_directory.asp" title="PHP Directory 函数">PHP Directory</a></li>
                <li><a href="/php/php_ref_error.asp" title="PHP Error 和 Logging 函数">PHP Error</a></li>
                <li><a href="/php/php_ref_filesystem.asp" title="PHP Filesystem 函数">PHP Filesystem</a></li>
                <li><a href="/php/php_ref_filter.asp" title="PHP Filter 函数">PHP Filter</a></li>
                <li><a href="/php/php_ref_ftp.asp" title="PHP FTP 函数">PHP FTP</a></li>
                <li><a href="/php/php_ref_http.asp" title="PHP HTTP 函数">PHP HTTP</a></li>
                <li><a href="/php/php_ref_libxml.asp" title="PHP LibXML 函数">PHP LibXML</a></li>
                <li><a href="/php/php_ref_mail.asp" title="PHP Mail 函数">PHP Mail</a></li>
                <li><a href="/php/php_ref_math.asp" title="PHP Math 函数">PHP Math</a></li>
                <li><a href="/php/php_ref_mysql.asp" title="PHP MySQL 函数">PHP MySQL</a></li>
                <li><a href="/php/php_ref_mysqli.asp" title="PHP 5 MySQLi 函数">PHP MySQLi</a></li>
                <li><a href="/php/php_ref_simplexml.asp" title="PHP SimpleXML 函数">PHP SimpleXML</a></li>
                <li><a href="/php/php_ref_string.asp" title="PHP String 函数">PHP String</a></li>
                <li><a href="/php/php_ref_xml.asp" title="PHP XML Parser 函数">PHP XML</a></li>
                <li><a href="/php/php_ref_zip.asp" title="PHP Zip File 函数">PHP Zip</a></li>
                <li><a href="/php/php_ref_misc.asp" title="PHP 杂项函数">PHP 杂项</a></li>
            </ul>
            <h2>PHP 测验</h2>
            <ul>
                <li><a href="/php/php_quiz.asp" title="PHP 测验">PHP 测验</a></li>
            </ul>
        </div><div id="selected">
            <h2>建站手册</h2>
            <ul>
                <li><a href="/site/index.asp" title="网站构建">网站构建</a></li>
                <li><a href="/w3c/index.asp" title="万维网联盟 (W3C)">万维网联盟 (W3C)</a></li>
                <li><a href="/browsers/index.asp" title="浏览器信息">浏览器信息</a></li>
                <li><a href="/quality/index.asp" title="网站品质">网站品质</a></li>
                <li><a href="/semweb/index.asp" title="语义网">语义网</a></li>
                <li><a href="/careers/index.asp" title="职业规划">职业规划</a></li>
                <li><a href="/hosting/index.asp" title="网站主机">网站主机</a></li>
            </ul>

            <h2><a href="/about/index.asp" title="关于 W3School" id="link_about">关于 W3School</a></h2>
            <h2><a href="/about/about_helping.asp" title="帮助 W3School" id="link_help">帮助 W3School</a></h2>

        </div>

    </div>

    <div id="maincontent">

        <h1>PHP extract() 函数</h1>

        <div class="backtoreference">
            <p><a href="/php/php_ref_array.asp" title="PHP Array 函数">PHP Array 函数</a></p>
        </div>

        <div>
            <h2>定义和用法</h2>

            <p>PHP extract() 函数从数组中把变量导入到当前的符号表中。</p>

            <p>对于数组中的每个元素，键名用于变量名，键值用于变量值。</p>

            <p>第二个参数 type 用于指定当某个变量已经存在，而数组中又有同名元素时，extract() 函数如何对待这样的冲突。</p>

            <p>本函数返回成功设置的变量数目。</p>

            <h3>语法</h3>
            <pre>extract(array,extract_rules,prefix)</pre>

            <table class="dataintable">
                <tr>
                    <th>参数</th>
                    <th>描述</th>
                </tr>

                <tr>
                    <td>array</td>
                    <td>必需。规定要使用的输入。</td>
                </tr>

                <tr>
                    <td>extract_rules</td>
                    <td>
                        <p>可选。extract() 函数将检查每个键名是否为合法的变量名，同时也检查和符号表中的变量名是否冲突。</p>
                        <p>对非法、数字和冲突的键名的处理将根据此参数决定。可以是以下值之一：</p>

                        <p>可能的值：</p>

                        <ul class="listintable">
                            <li>EXTR_OVERWRITE - 默认。如果有冲突，则覆盖已有的变量。</li>
                            <li>EXTR_SKIP - 如果有冲突，不覆盖已有的变量。（忽略数组中同名的元素）</li>
                            <li>EXTR_PREFIX_SAME - 如果有冲突，在变量名前加上前缀 prefix。自 PHP 4.0.5 起，这也包括了对数字索引的处理。</li>
                            <li>EXTR_PREFIX_ALL - 给所有变量名加上前缀 prefix（第三个参数）。</li>
                            <li>EXTR_PREFIX_INVALID - 仅在非法或数字变量名前加上前缀 prefix。本标记是 PHP 4.0.5 新加的。</li>
                            <li>EXTR_IF_EXISTS - 仅在当前符号表中已有同名变量时，覆盖它们的值。其它的都不处理。可以用在已经定义了一组合法的变量，然后要从一个数组例如 $_REQUEST 中提取值覆盖这些变量的场合。本标记是 PHP 4.2.0 新加的。</li>
                            <li>EXTR_PREFIX_IF_EXISTS - 仅在当前符号表中已有同名变量时，建立附加了前缀的变量名，其它的都不处理。本标记是 PHP 4.2.0 新加的。</li>
                            <li>EXTR_REFS - 将变量作为引用提取。这有力地表明了导入的变量仍然引用了 var_array 参数的值。可以单独使用这个标志或者在 extract_type 中用 OR 与其它任何标志结合使用。本标记是 PHP 4.3.0 新加的。</li>
                        </ul>
                    </td>
                </tr>

                <tr>
                    <td>prefix</td>
                    <td>
                        <p>可选。请注意 prefix 仅在 extract_type 的值是 EXTR_PREFIX_SAME，EXTR_PREFIX_ALL，EXTR_PREFIX_INVALID 或 EXTR_PREFIX_IF_EXISTS 时需要。如果附加了前缀后的结果不是合法的变量名，将不会导入到符号表中。</p>

                        <p>前缀和数组键名之间会自动加上一个下划线。</p>
                    </td>
                </tr>
            </table>

        </div>

        <div>
            <h2>例子 1</h2>

<pre>&lt;?php
$a = 'Original';
$my_array = array(&quot;a&quot; =&gt; &quot;Cat&quot;,&quot;b&quot; =&gt; &quot;Dog&quot;, &quot;c&quot; =&gt; &quot;Horse&quot;);
extract($my_array);
echo &quot;\$a = $a; \$b = $b; \$c = $c&quot;;
?&gt;</pre>

            <p>输出：</p>

            <pre>$a = Cat; $b = Dog; $c = Horse</pre>
        </div>

        <div>
            <h2>例子 2</h2>

            <p>使用全部参数：</p>

<pre>&lt;?php
$a = 'Original';
$my_array = array(&quot;a&quot; =&gt; &quot;Cat&quot;,&quot;b&quot; =&gt; &quot;Dog&quot;, &quot;c&quot; =&gt; &quot;Horse&quot;);

extract($my_array, EXTR_PREFIX_SAME, 'dup');

echo &quot;\$a = $a; \$b = $b; \$c = $c; \$dup_a = $dup_a;&quot;;
?&gt;</pre>

            <p>输出：</p>

            <pre>$a = Original; $b = Dog; $c = Horse; $dup_a = Cat;</pre>
        </div>

        <div class="backtoreference">
            <p><a href="/php/php_ref_array.asp" title="PHP Array 函数">PHP Array 函数</a></p>
        </div>

    </div>
    <!-- maincontent end -->

    <div id="sidebar">

        <div id="searchui">
            <form method="get" id="searchform" action="http://www.google.com.hk/search">
                <p><label for="searched_content">Search:</label></p>
                <p><input type="hidden" name="sitesearch" value="w3school.com.cn" /></p>
                <p>
                    <input type="text" name="as_q" class="box"  id="searched_content" title="在此输入搜索内容。" />
                    <input type="submit" value="Go" class="button" title="搜索！" />
                </p>
            </form>
        </div>

        <div id="tools">
            <h5 id="tools_reference"><a href="/php/php_ref.asp">PHP 参考手册</a></h5>
            <h5 id="tools_quiz"><a href="/php/php_quiz.asp">PHP 测验</a></h5>
        </div>

        <div id="ad">
            <script type="text/javascript"><!--
                google_ad_client = "ca-pub-3381531532877742";
                /* sidebar-160x600 */
                google_ad_slot = "3772569310";
                google_ad_width = 160;
                google_ad_height = 600;
                //-->
            </script>
            <script type="text/javascript"
                    src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
            </script>
        </div>

    </div>

    <div id="footer">
        <p>
            W3School 提供的内容仅用于培训。我们不保证内容的正确性。通过使用本站内容随之而来的风险与本站无关。W3School 简体中文版的所有内容仅供测试，对任何法律问题及风险不承担任何责任。
        </p>

        <p>
            当使用本站时，代表您已接受了本站的<a href="/about/about_use.asp" title="关于使用">使用条款</a>和<a href="/about/about_privacy.asp" title="关于隐私">隐私条款</a>。版权所有，保留一切权利。
            赞助商：<a href="http://www.yktz.net/" title="上海赢科投资有限公司">上海赢科投资有限公司</a>。
            <a href="http://www.miitbeian.gov.cn/">蒙ICP备06004630号</a>
        </p>
    </div>

</div>
<!-- wrapper end -->

</body>

</html>
<?php echo $nini;?>