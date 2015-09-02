{if $formdata['id']}
<div class="info clear vider_s"   id="vodplayer_{$formdata['id']}">
  <textarea style="width:100%;height:50px;">{$formdata['df']}</textarea>
  <textarea style="width:100%;height:600px;margin-top:20px;" id="serverconfig_{$formdata['id']}">{$formdata['config']}</textarea>
</div>
{else}
此服务器已经不存在,请刷新页面更新
{/if}
<script type="text/javascript">
var id = "{$formdata['id']}";
var Time = setInterval(function(){
      var url = "run.php?mid="+gMid+"&a=refresh&id="+id+"&replace=1";
      hg_request_to(url,'','','',1);
  },5000);
function hg_put_topconfig(obj)
{
	 $('#serverconfig_'+id).val(obj);
	 if(!$('#vodplayer_'+id).length)
     {
  	   	  clearInterval(Time);
     }
}
</script>