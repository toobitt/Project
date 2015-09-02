<li class="common-list-data clear" id="r_{$v['id']}" name="{$v['id']}" orderid="{$v['order_id']}">
     <div class="common-list-left">
        <div class="common-list-item paixu">
             <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
        </div>
    </div>
    <div class="common-list-right">
        <div class="common-list-item">
            <a class="btn-box" title="编辑" target="formwin" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1"><em class="b2" ></em></a>
        </div>
        <div class="common-list-item">
            <a class="btn-box" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"><em class="b3" ></em></a>
        </div>
        <div class="common-list-item wd120">
            <span>{$v['sort_dir']}</span>
        </div>
        <div class="common-list-item wd80">
       
             <a class="makefile handler-icon" onclick="return hg_ajax_post(this, '生成文件', 0);" href="./run.php?a=relate_module_show&app_uniq=mobile&mod_uniq=api&mod_a=build_api_file&sort_id={$v['id']}">生成文件</a>
          <!-- 
        	<a class="makefile handler-icon" _sortid="{$v['id']}">生成文件</a>
        	-->
        </div>
        <div class="common-list-item wd80">
            <a class="btn-box" target="formwin" title="复制分类下文件到新分类" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&type=copy&infrm=1">复制</a>
        </div>
        <div class="common-list-item wd60">
        	<!-- 
            <a onclick="downloadFile(this);" href="javascript:###" _href="{$_configs['App_mobile']['protocol']}{$_configs['App_mobile']['host']}/{$_configs['App_mobile']['dir']}admin/mobile_api_sort.php?a=export_file&sort_id={$v['id']}&sort_dir={$v['sort_dir']}&access_token={$_user['token']}">导出</a>
        	 -->
        	<a class="btn-box" title="导出分类下接口" href="./run.php?mid={$_INPUT['mid']}&a=download&sort_id={$v['id']}&sort_dir={$v['sort_dir']}">导出</a>
        </div>
        <div class="common-list-item wd60">
            <a class="importing handler-icon" _id="{$v['id']}" _dir="{$v['sort_dir']}">导入</a>
        </div>
    </div>
    <div class="common-list-biaoti">
	     <div class="common-list-item biaoti-transition">
	     	<a href="run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1" class="common-title" target="formwin">
			     <span class="m2o-common-title">{$v['sort_name']}</span>
			</a>
	     </div>
	</div>
</li>