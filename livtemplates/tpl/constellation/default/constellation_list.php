{template:head}
{code}
if(!isset($_INPUT['date_search']))
{
    $_INPUT['date_search'] = 1;
}
$attr_date = array(
	'class' => 'colonm down_list data_time',
	'show' => 'colonm_show',
	'width' => 104,/*列表宽度*/
	'state' => 1,/*0--正常数据选择列表，1--日期选择*/
	'is_sub'=> 0,
);
//print_r($list);
{/code}
{css:2013/list}
{css:2013/iframe}
{css:constellation}
{js:underscore}
{js:Backbone}
{js:jqueryfn/jquery.tmpl.min}
{js:common/record}
{js:common/record_view}
{js:common/publish_box}
{js:common/ajax_upload}
{js:constellation/constellation}
<script type="text/javascript">
function hg_audit_play(id)
{
	var url = "run.php?mid="+gMid+"&a=audit&id="+id;
	hg_ajax_post(url);
}

/*审核回调*/
function hg_audit_play_callback(obj)
{
	var obj = eval('('+obj+')');
	$('#status_'+obj.id).text(obj.status);
}
</script>
<div class="biaoz"  style="position:relative;z-index:1"  id="body_content">
	<div class="nav-box">
       <div id="hg_info_list_search" class="choice-area">
          <span class="serach-btn"></span>
             	   	
       </div>
       <div id="hg_parent_page_menu" class="controll-area fr mt5">
         <a target="mainwin" href="run.php?mid={$_INPUT['mid']}&a=configuare&infrm=1" class="set-button">配置</a>
       </div>
      <!-- <div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
       </div>-->
     </div>
	<div class="choice-area" id="transcode_info_list_search" style="position:absolute;top:1px;left:1px;height:43px;">
	    
	</div>
<div class="wrap clear">
<div class="tv-wrap">
 <form method="post" action="" name="vod_sort_listform">
  <ul class="tv-list play-list clear">
  	{if $list}
	   {foreach  $list  as $k => $v}
	     	{template:unit/constellationlist}
	     	
	   {/foreach}
	{else}
	  <p style="color:#da2d2d;text-align:center;font-size:20px;line-height:20px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
	{/if}
  </ul>

  </form>
</div>
 <div id="infotip"  class="ordertip"></div>
</div>
 <div class="prevent-go"></div>
 

{template:foot}
