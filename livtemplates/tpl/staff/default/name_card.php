{template:head}
{css:common/common_list}
{js:common/common_list}
{js:vod_opration}
<style>
.common-list .card{width:350px;overflow:hidden;}
.common-list .card img{width:270px;height:150px;}
.common-list-data .card{height:180px;}
.common-list .card-title{min-width:200px;}
.public-list .common-list-item{height:180px;}
.common-list .common-list-bottom{margin:0;}

</style>
{code}
    if($formdata['zipname'] && $formdata['zipfilename'])
    {
        
        header('Pragma: public');
        header('Last-Modified:'.gmdate('D, d M Y H:i:s') . 'GMT');
        header('Cache-Control:no-store, no-cache, must-revalidate');
        header('Cache-Control:pre-check=0, post-check=0, max-age=0');
        header('Content-Transfer-Encoding:binary');
        header('Content-Encoding:none');
        header('Content-type:multipart/form-data');
        header('Content-Disposition:attachment; filename="'.$formdata['zipname'].'"'); //设置下载的默认文件名
        header('Content-length:'. filesize($formdata['zipfilename']));
        $fp = fopen($formdata['zipfilename'], 'r');
        while(connection_status() == 0 && $buf = @fread($fp, 8192)){
            echo $buf;
        }
        
        
    }
{/code}
<ul class="common-list" id="list_head">
     <li class="common-list-head public-list-head">
          <div class="common-list-left">
                <div class="common-paixu common-list-item">编号</div> 
          </div>
           <div class="common-list-biaoti">
                <div class="common-list-item card-title">姓名</div>
                <div class="common-list-item card">名片正面</div>
                <div class="common-list-item card">名片反面</div>
            </div>
     </li>
</ul>
<form id="downForm" action="run.php" method="post">
<ul class="common-list public-list" id="record-list">
    {if $formdata}
    {foreach $formdata as $k=>$v}
    <li class="common-list-data clear"  id="r_{$v['id']}"  name="{$v['id']}" >
        <div class="common-list-left">
            <div class="common-list-item common-paixu">
                    <a class="lb" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}" /></a>
            </div>
        </div>
        {code}
            if(!in_array('',$v['front']['png']))
            {
                $front = $v['front']['png']['host'].$v['front']['png']['dir'].$v['front']['png']['filepath'].$v['front']['png']['filename'];
            }
            if(!in_array('',$v['back']['png']))
            {
                $back = $v['back']['png']['host'].$v['back']['png']['dir'].$v['back']['png']['filepath'].$v['back']['png']['filename'];
            }
            if(!in_array('',$v['front']['tif']))
            {
                $fronttif = $v['front']['tif']['host'].$v['front']['tif']['dir'].$v['front']['tif']['filepath'].$v['front']['tif']['filename'];
            }
            if(!in_array('',$v['back']['tif']))
            {
                $backtif = $v['back']['tif']['host'].$v['back']['tif']['dir'].$v['back']['tif']['filepath'].$v['back']['tif']['filename'];
            }
            if(!in_array('',$v['whiteback']['tif']))
            {
                $whitebacktif = $v['whiteback']['tif']['host'].$v['whiteback']['tif']['dir'].$v['whiteback']['tif']['filepath'].$v['whiteback']['tif']['filename'];
            }
            if(!in_array('',$v['whiteback']['png']))
            {
                $whitebackpng = $v['whiteback']['png']['host'].$v['whiteback']['png']['dir'].$v['whiteback']['png']['filepath'].$v['whiteback']['png']['filename'];
            }
        {/code}
         <div class="common-list-biaoti">
              <div class="common-list-item card-title">{$v['name']}</div>
              <div class="common-list-item card">{if $front}<img src="{$front}"/>{/if}</div>
              <div class="common-list-item card">{if $back}<img src="{$back}"/>{/if}</div>
              <div class="common-list-item card">{if $whitebackpng}<img src="{$whitebackpng}"/>{/if}</div>
         </div>
         <input type="hidden" name="staffName[{$k}]" value="{$v['name']}"/>
         <input type="hidden" name="frontTif[{$k}]" value="{$fronttif}"/>
         <input type="hidden" name="backTif[{$k}]" value="{$backtif}"/>
         <input type="hidden" name="whitebackTif[{$k}]" value="{$whitebacktif}"/>
    </li>
    {/foreach}
    {/if}
</ul>
<ul class="common-list  public-list">
    <li class="common-list-bottom clear">
        <div class="common-list-left">  
            <input type="checkbox" name="checkall"  value="infolist" title="全选" rowtag="LI" /> 
            <a style="cursor:pointer;" id="download">下载</a>
            <a class="option-iframe-back">返回</a>
        </div>
        {$pagelink}
    </li>
</ul>
<input type="hidden" name="a" value="packZip" />
<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
</form>
<script>
$(function () {
    var list = $('#record-list'),
        form = $('#downForm');
    $('#download').click(function () {
        var ids = [], data = $([]);
        var lis = list.find('li').filter(function () {
            return $(this).find('input:checkbox').prop('checked');
        });
        if (lis.size()) {
            form.submit();
        } else {
            alert('请选择要下载的名片！');
        }
    });
});
</script>