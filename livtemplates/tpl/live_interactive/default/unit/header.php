<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$this->mTemplatesTitle}</title>

{csshere}
{css:reset}
{css:jquery-ui-min}
{css:alert/jquery.alerts}
<script type="text/javascript">
var RESOURCE_URL = '{$RESOURCE_URL}';
var SCRIPT_URL = '{$SCRIPT_URL}';
var client_id = 1;
var gMid = '{$_INPUT['mid']}';
var gMenuid = '{$_INPUT['menuid']}';
var gRelate_module_id = '{$relate_module_id}';
var gToken = "{$_user['token']}";
var hg_window_destruct = function () {};
</script>

{$this->mHeaderCode}

{jshere}

{js:jquery.min}
{js:jquery-ui-min}
{js:jquery.form}
{js:alert/jquery.alerts}
<script>
function array_values (list) {
	var ret = [];
	for (var item in list) {
		ret.push( list[item] );
	}
	return ret;
}	
$.format = function (str, param) {
	if ( arguments.length === 1 ) {
		return function () {
			var args = $.makeArray( arguments );
			return $.format.apply( null, args.unshift(str) );
		};
	}
	if ( arguments.length > 2 ) {
		param = $.makeArray( arguments ).slice(1);
	}
	if ( !$.isArray( param ) ) {
		param = [ param ];
	}
	$.each(param, function (i, n) {
		str = str.replace( new RegExp('\\{' + i + '\\}', 'g'), n );
	});
	return str;
};
</script>

{js:iframe/loading}

{if $_INPUT['infrm']}
<style type="text/css">
.wrap{border:0;box-shadow:none;padding:10px;}
.wrap .search_a{padding:0}
</style>
{/if}

</head>
<body{$this->mBodyCode}{$_scroll_style}>

