<?php 
/* $Id: error.php 387 2011-07-26 05:31:22Z lijiaying $ */
?>
{template:head}
<div class="content clear" id="equalize">
		<div class="error">
			<div>
				<span></span>
				<p>对不起，可能因为以下原因：</p>
				<p style="color:#e64648">{$message}</p>
			</div>
			<ul>
				<li class="text">您可以:</li>
				<li><a href="{$url}">返回上一页</a></li>
				<li><a href="javascript:void(0);" onclick="closeWindow();">关闭本页</a></li>
<!--				<li><a>到帮助中心寻求答案</a></li>
				<li><a>给我们提建议</a></li>-->
			</ul>
		</div>
		<div class="common">
			<!--<h3>常见问题</h3>
			<ul>
			<li><a>我没有收到信息怎么办？</a></li>
			<li><a>我没有收到信息怎么办？</a></li>
			<li><a>我没有收到信息怎么办？</a></li>
			<li><a>我没有收到信息怎么办？</a></li>
			<li><a>我没有收到信息怎么办？</a></li>
			</ul>-->
		</div>
</div>
{template:foot}