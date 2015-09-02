<li class="common-list-data clear" id="r_{$v['id']}" _id="{$v['id']}" name="{$v['id']}" orderid="{$v['order_id']}">
     <div class="common-list-left">
        <div class="common-list-item paixu">
             <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
        </div>
    </div>
    <div class="common-list-right">
        
        <div class="common-list-item wd150">
            <span>{$v['brief']}</span>
        </div>
        <div class="common-list-item">
			<a class="btn-box" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"><em class="b3" ></em></a>
        </div>
        <div class="common-list-item wd150">
            <span>{$v['sort_name']}
        </div>
        <div class="common-list-item">
            <a target="formwin" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&file_name={$v['file_name']}&infrm=1">配置</a>
        </div>
        <div class="common-list-item">
            <a target="formwin" title="映射" href="./run.php?mid={$_INPUT['mid']}&a=map&type=map&id={$v['id']}&infrm=1">映射</a>
        </div>
        <div class="common-list-item">
            <a class="makefile handler-icon"  id="" onclick="return hg_ajax_post(this, '生成文件', 0);" href="./run.php?mid={$_INPUT['mid']}&a=build_api_file&id={$v['id']}">生成文件</a>
        </div>
        <div class="common-list-item">
            <a target="formwin" href="./run.php?mid={$_INPUT['mid']}&a=check_file&id={$v['id']}&infrm=1">查看</a>
        </div>
        <div class="common-list-item">
            <a href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&file_name={$v['file_name']}&type=copy&infrm=1" target="formwin">复制</a>
        </div>
       	<div class="common-list-item">
            <a class="preview">预览</a>
        </div>
    </div>
    <div class="common-list-biaoti">
	     <div class="common-list-item biaoti-transition">
	         <a href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&file_name={$v['file_name']}&infrm=1" target="formwin" title="/mobile/api/{$v['sort_dir']}{$v['file_name']}" class="common-title">
	         	<span class="m2o-common-title">{$v['file_name']}</span>
	         </a>
	     </div>
</li>