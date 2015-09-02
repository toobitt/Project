<?php
function seekhelp_clean_value($val = '')
{
	$pregfind = array('&#60;&#33;--', '--&#62;', '&gt;', '&lt;', '&quot;', '&#33;', '&#39;', "\n", '&#036;', '');
	$pregreplace = array('<!--', '-->', '>', '<', '"', '!', "'", "\n", '$', "\r");
	$val = str_replace($pregfind, $pregreplace, $val);
	return $val;
}
