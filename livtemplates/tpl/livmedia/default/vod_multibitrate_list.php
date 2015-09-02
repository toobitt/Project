<style type="text/css">
.vod_config{width:405px;height:200px;margin-left:10px;}
.vod_config .item{width:100%;height:30px;}
.vod_config .item div{width:79px;height:30px;float:left;text-align:center;line-height:30px;}
</style>
<div class="vod_config">
<form action="run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data"  onsubmit="return hg_ajax_submit('multi_bitrate')" name="multi_bitrate"  id="multi_bitrate" >
 	<div class="item">
        <div>名称</div>
        <div>编码格式</div>
        <div>视频码流</div>
        <div>音频码流</div>
        <div>选择</div>
     </div>
     {foreach $formdata['config'] AS $k => $v}
     <div class="item">
        <div>{$v['name']}</div>
        <div>{$v['codec_format']}</div>
        <div>{$v['video_bitrate']}</div>
        <div>{$v['audio_bitrate']}</div>
        <div>
        {code}
            $flag = 0;
            if(in_array($v['id'],$formdata['ids']))
            {
            	$flag = 1;
            }
        {/code}
        {if $flag}
        <font color="green">已存在</font>
        {else}
        <input type='radio' value="{$v['id']}" style='margin-top:5px;' name="cid" />
        {/if}
        </div>
     </div>
     {/foreach}
     <input type="hidden" name="a" value="domulti_bitrate" />
     <input type="hidden" name="id" value="{$formdata['id']}" />
     <input type="submit" value="提交" class="button_4" style="float:left;margin-left:296px;margin-top:20px;" />
</form>
</div>