{if (!$_INPUT['infrm'] && !$__top)}
	{if SCRIPT_NAME != 'login'}
		{template:foot/copyright,}
	{else}
		{template:foot/copyright,login_footer}
	{/if}
{/if}
{template:dialog}
{template:form/uploadTpl}
{$this->mFooterCode}
<script>

/*
 * 把nodeFrame中的搜查和几个按钮提升到mainwin中；resize nodeFrame；
 */
{if $_INPUT['infrm']}
	$(function ($){hg_resize_nodeFrame(true{code}echo $_INPUT['_firstload'] ? ','.$_INPUT['_firstload']: '';{/code});});
	hg_repos_top_menu();
	setTimeout("hg_resize_nodeFrame(true);", 100);
{else}
{/if}



/*实例化日期选择器*/
$(window).load( function(){
	$('html').find('.date-picker').removeClass('hasDatepicker').hg_datepicker();
} );
/*
if(top.livUpload.SWF)
{
	top.$('#flash_wrap').css({'left':'0px','top':'0px','position':'absolute'});
	top.setTimeout(function(){top.livUpload.SWF.setButtonDimensions(1,1);},500);
	top.livUpload.currentFlagId = 0;
}
*/
</script>
<div id="dragHelper" style="position: absolute; display: none; cursor: move; list-style-type: none; list-style-position: initial; list-style-image: initial; overflow-x: hidden; overflow-y: hidden; -webkit-user-select: none; "></div>
</body>
</html>