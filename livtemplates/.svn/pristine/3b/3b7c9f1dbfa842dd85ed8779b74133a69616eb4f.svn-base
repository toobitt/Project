{js:jquery.blockUI}
{css:mark_right_style}
{js:mark_list_executive}
{template:head}
<div class="right" style="float:none">
                <div class="search_a">
                    <div class="right_1">
                    	<div class="data_time input">
                            	<span class="input_left"></span>
                                <span class="input_right"></span>
                                <span class="input_middle"><a><em></em>内容1</a></span>
                        </div>
                        <div class="transcoding down_list" id="transcoding_id">
                            	<span class="input_left"></span>
                                <span class="input_right"></span>
                                <span class="input_middle"><a><em></em>转码中</a></span>
                                <ul id="transcoding_show" style="display:none">
                                    	<li><a>已审核</a></li>
                                        <li><a>待审核</a></li>
                                        <li><a>被打回</a></li>
                                </ul>
                        </div>
                        <div class="colonm down_list" id="colonm_id">
                            	<span class="input_left"></span>
                                <span class="input_right"></span>
                                <span class="input_middle"><a><em></em>网站栏目</a></span>
                                <ul id="colonm_show" style="display:none">
                                    	<li><a>编辑上传</a></li>
                                        <li><a>网友上传</a></li>
                                        <li><a>直播归档</a></li>
                                        <li><a>娱乐新闻</a></li>
                                        <li><a>直播归档</a></li>
                                </ul>
                        </div>
                    </div>
                    <div class="right_2">
                    	<div class="button">
                            	<span class="button_left"></span>
                                <span class="button_right"></span>
                                <span class="button_middle"><a>搜索</a></span>
                        </div>
                    	<div class="search input clear" id="search">
                            	<span class="input_left"></span>
                                <span class="input_right"></span>
                                <span class="input_middle"><em></em><input name="" type="text"  id="search_id"/></span>
                        </div>
                        
                    </div>
                </div>
                <ul class="list">
                    <li class="first clear">
                    	<span class="left"><a class="lb"><em></em></a><a class="slt">缩略图</a><a class="bf">播放</a></span>
                        <span class="right"><a class="fb">发布</a><a class="ml">码流</a><a class="fl">分类</a><a class="zt">状态</a><a class="tjr">添加人/时间</a></span><a class="title">标题</a>
                    </li>
                    
                    {foreach $list AS $k => $v}
                    <li class="clear" onclick="hg_row_interactive(this, 'click', 'clear cur');" id="r{$v[$primary_key]}">
                    	<span class="left"><a class="lb"><input name="" type="checkbox" value="" /></a><a class="slt"><img src="IMG/22.gif" width="40" height="30" /></a>

						<a class="bf"><em class="current"></em></a>
						
						</span>
                        <span class="right"><a class="fb"><em class="b2"></em></a><a class="ml"><em>{$v['stream']}</em></a><a class="fl"><em class="color_zs">{$v['categories']}</em></a><a class="zt">{$v['audit']}</a><a class="tjr"><em>{$v['name']}</em><span>{$v['pubdate']}</span></a>
                        
                             <span class="fb_column">
                             	<span class="fb_column_l"></span>
                                <span class="fb_column_r"></span>
                                <span class="fb_column_m"><em></em><span class="fsz">发送至栏目：</span>
								{foreach $column AS $kk => $vv}
								<a>{$vv['column']}</a>
								{/foreach}
								</span>
                        	 </span>
                        </span><span class="title"><em></em><a href="javascript:void(0);" onclick="check_menu(1,3);" id="t_1">{$v['title']}<strong>{$v['time']}</strong></a>
                        
                       
                        
                      </span>
                        <div class="content_more clear" id="content_1" style="display:none">
                            	<ul class="content_more_left">
                                	<li>来&nbsp;&nbsp;&nbsp;&nbsp;源：<span>{$v['source']}</span></li>
                                    <li>分&nbsp;&nbsp;&nbsp;&nbsp;类：<span>{$v['categories']}</span></li>
                                    <li>关键字：<span>{$v['key']}</span></li>
                                    <li>发布至：{foreach $colunm AS $kk => $vv}<span>{$vv['column']}</span>{/foreach}</li>
                                    <li class="more">描&nbsp;&nbsp;&nbsp;&nbsp;述：<span>{$v['brief']}</span></li>
                                </ul>
                                <div class="content_more_right clear">
                            <ul>
                            	<li class="button">
                                        <span class="button_left"></span>
                                        <span class="button_right"></span>
                                        <span class="button_middle"><a id="windows" href="javascript:void(0);" onclick="check_windows(1,3);">编辑</a></span>
                                </li>
                                <li class="button">
                                        <span class="button_left"></span>
                                        <span class="button_right"></span>
                                        <span class="button_middle"><a>删除</a></span>
                                </li>
                                <li class="button">
                                        <span class="button_left"></span>
                                        <span class="button_right"></span>
                                        <span class="button_middle"><a>审核</a></span>
                                </li>
                                <li class="button">
                                        <span class="button_left"></span>
                                        <span class="button_right"></span>
                                        <span class="button_middle"><a>打回</a></span>
                                </li>
                            </ul>
                                <p><a>添加集合</a></p>
                                <p><a>发布至网站</a></p>
                            </div>
                        </div>
                    </li>
                    {/foreach}
                 
                </ul>
            </div>
          <div class="bottom clear">
        <div class="left"><a class="lb"><input type="checkbox" value="" name=""></a><a>审核</a><a>审核</a><a>审核</a><a>审核</a><a>审核</a></div>
            	<div align="center" class="fy"><span class="xiy"><a>上一页</a></span><span class="xiy"></span><span class="xiy"></span> <span class="lj">1</span> <a href="179562_2.html" title="2" class="lj2">2</a> <a href="179562_3.html" title="3" class="lj2">3</a><span class="xiy"><a id="liv_nextpage" href='179562_2.html' title='下一页'>下一页</a></span><span class="xiy"><a href='179562_4.html' title='最后一页'>末页</a></span><span class="fl">&nbsp;&nbsp;总共: 4页</span></div>
</div>
{template:foot}