{template:head}
{css:2013/list}
{css:common/common_list}
{js:vod_opration}
{js:2013/list}
{js:2013/ajaxload_new}
{js:common/common_list}
{js:members/member_list}
{code}
	if(!isset($_INPUT['state']))
	{
		$_INPUT['state'] = -1;
	}
	
	if(!isset($_INPUT['date_search']))
	{
		$_INPUT['date_search'] = 1;
	}
	//print_r($list);
{/code}
<style>
.m2o-bt:hover .common-title{padding-left:15px;}
.common-title{-webkit-transition: all 0.15s ease-in 0s;transition: all 0.15s ease-in 0s;}
.w60{width:60px;}
.w80{width:80px;}
.w120{width:120px;}
.color{color: #8fa8c6;}
.blacklist{text-decoration: underline;margin-right:10px;}
.blacklist:hover{text-decoration: underline;}
.isblack{cursor: not-allowed;color:red!important;}
.record-edit-more-info{padding:20px;}
.moreinfo_show{display: block;position: absolute;top: -80px;right: 0px;background: #4c4c4c;box-shadow: 0 0 3px 0 rgba(0, 0, 0, 0.6)}
.moreinfo_show .record-edit-back-close{top:135px;}
.info-list{width:100%;height:30px;line-height: 30px;color: #aaa;border-bottom: 1px dotted #666;display:-webkit-box;}
.info-list span{display:block;width:265px;color:#fff;margin-left:10px;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;}
.info-model .m2o-option-inner{display:none;}
.up-model .record-edit-back-close{top:167px;}
.verify{cursor:pointer;}
</style>
<div style="display:none">
    {template:unit/member_search}
	<div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
		<a class="blue mr10" href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" target="formwin">
			<span class="left"></span>
			<span class="middle"><em class="add">新增会员</em></span>
			<span class="right"></span>
		</a>
	</div>
</div>
<div class="common-list-content" style="min-height:auto;min-width:auto;">
<div id="add_question"  class="single_upload">
					<div id="question_option_con">
					</div>
				</div>
	<form action="" method="post">
	 <div class="m2o-list">
			<!--排序模式打开后显示排序状态-->
			<div class="m2o-title m2o-flex m2o-flex-center">
		 	   <div id="infotip" class="ordertip">排序模式已关闭</div>
		       <div class="m2o-item m2o-paixu" title="排序">
		        	<!--  <a title="排序模式切换/ALT+R" class="common-list-paixu"></a> -->
		       </div>
            <div class="m2o-item m2o-flex-one m2o-bt" title="头像/会员名(会员昵称)">头像/会员名(会员昵称)</div>
            <div class="m2o-item m2o-state w60" title="总积分">总积分</div>
            <div class="m2o-item m2o-num w120" title="用户组">用户组/等级</div>
             <div class="m2o-item m2o-sort w60" title="实名认证">实名认证</div>
            <div class="m2o-item m2o-sort w120" title="类型">类型</div>
            <div class="m2o-item m2o-sort w120" title="所属多用户系统">用户系统</div>
            <div class="m2o-item m2o-style w60" title="状态">状态</div>
            <div class="m2o-item m2o-time w120" title="添加人/时间">添加人/时间</div>
        </div>
        <div class="m2o-each-list">
        	{if is_array($list) && count($list)>0}
				{foreach $list as $k => $v}	
		            {template:unit/member_list_list}
		        {/foreach}
			{else}
				<p class="common-list-empty">没有你要找的内容！</p>
			{/if}
        </div>
        <div class="m2o-bottom m2o-flex m2o-flex-center">
		  	 <div class="m2o-item m2o-paixu">
        		<input type="checkbox" name="checkall" class="checkAll" rowtag="m2o-item" title="全选"/>
    		</div>
    		<div class="m2o-item m2o-flex-one">
    		   <a class="batch-handle">删除</a>
    		   <a class="blacklist" data-type="0">加入黑名单</a>
			   <a class="blacklist" data-type="1">取消黑名单</a>
		   <!--<a class="batch-verify" data-type="1">通过认证</a>
			   <a class="batch-verify" data-type="0">拒绝认证</a>-->
    		</div>
    		<div id="page_size">{$pagelink}</div>
		</div>
    </div>
   </form>
 </div>
 <script>
	var data = $.globalListData = {code}echo $list ? json_encode($list) : '{}';{/code};
</script>
<script type="tpl" id="record-info-tpl">
	<div class="info-list" title="${mobile}">
		<label>手机:</label>
		<span>{{if mobile}} ${mobile} {{else}} 未填写 {{/if}}</span>
	</div>
    <div class="info-list" title="${email}">
		<label>邮箱:<label>
		<span>{{if email}} ${email} {{else}} 未填写 {{/if}}</span>
	</div>
    <div class="info-list" title="${num}">
		<label>注册设备号:<label>
		<span>{{if num}} ${num} {{else}} 未填写 {{/if}}</span>
	</div>
	<div class="info-list" title="${last}">
		<label>最后登录设备号:</label>
		<span>{{if last}} ${last} {{else}} 未填写 {{/if}}</span></li>
	</div>
	<div class="info-list" title="${inviteuser}">
		<label>邀请人:</label>
		<span>{{if inviteuser}} ${inviteuser} {{else}} 未填写 {{/if}}</span></li>
	</div>
	<div class="info-list" title="${spreadcode}">
		<label>推广码:</label>
		<span>{{if spreadcode}} ${spreadcode} {{else}} 非推广用户 {{/if}}</span></li>
	</div>
	<span class="record-edit-back-close"></span>
</script>
<script type="text/x-jquery-tmpl" id="m2o-option-tpl">
<div class="m2o-option" data-id="{{= id}}">
    <div class="m2o-option-inner m2o-flex">
        <div class="m2o-btns m2o-flex">
			<div class="m2o-btn-area m2o-flex">
				<a href="./run.php?mid={$_INPUT['mid']}&a=form&id={{= id}}&infrm=1" target="formwin" need-back>编辑</a>
				<a class="option-blacklist" data-id="{{= id}}" _status="{{= status}}" _type="{{= isblack}}">{{if isblack == 0}}加入黑名单{{else}}取消黑名单{{/if}}</a>
				<a class="option-delete">删除</a>
 				<a class="more-info">更多信息</a>
			</div>
			<div class="m2o-option-line"></div>
        </div>
    </div>
	<div class="m2o-option-confirm">
			<p>确定要删除该内容吗？</p>
			<div class="m2o-option-line"></div>
			<div class="m2o-option-confim-btns">
				<a class="confim-sure">确定</a>
				<a class="confim-cancel cancel">取消</a>
			</div>
	</div>
	<div class="m2o-option-close"></div>
	<div class="record-edit-more-info">
	</div>
</div>
</script>
{template:foot}
