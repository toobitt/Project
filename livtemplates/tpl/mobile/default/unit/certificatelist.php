<li class="common-list-data clear" id="r_{$v['appid']}" name="{$v['appid']}" orderid="{$v['appid']}">
     <div class="common-list-left">
        <div class="common-list-item paixu">
             <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['appid']}" title="{$v['appid']}" /></a>
        </div>
    </div>
    <div class="common-list-right">
        <div class="common-list-item">
            <a class="btn-box" title="编辑" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['appid']}&infrm=1" target="formwin" ><em class="b2" ></em></a>
        </div>
        <div class="common-list-item">
            <a class="btn-box" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['appid']}"><em class="b3" ></em></a>
        </div>
        <div class="common-list-item">
            <a title="查看" href="./run.php?mid={$_INPUT['mid']}&a=check_cert&id={$v['appid']}&infrm=1">查看</a>
        </div>
        <div class="common-list-item">
            <a title="点击更改" href="#" onclick="hg_set_way({$v['appid']})" id="set_way_{$v['appid']}">{$v['send_way']}</a>
        </div>
        <div class="common-list-item wd150">
           <span>{$v['develop']}</span>
        </div>
        <div class="common-list-item wd150">
            <span>{$v['apply']}</span>
        </div>
        
    </div>
    <div class="common-list-biaoti">
    	<div class="common-list-item biaoti-transition">
		     <a href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['appid']}&infrm=1" class="common-title" target="formwin" >
		         <span class="m2o-common-title">{$v['appname']}</span>
		     </a>
	     </div>
	</div>
</li>