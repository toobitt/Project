{template:head/head_mark_list}
<div class="top clear">
    <div class="clear top_menu">
            <ul class="menu_part">
                <li class="first"><a href="#"></a><em></em></li>
                <li><a href="#">视频库视频库视频库</a><em></em></li>
                <li><a href="#">视频库</a><em></em></li>
                <li><a href="#">视频库</a><em></em></li>
                <li><a href="#">视频库</a><em></em></li>
                <li class="last"><a href="#">视频库</a><em></em></li>
            </ul>
            <div class="menu">        
             <ul class="right">
            	<li><a href="#">新增视频</a></li>
                <li><a href="#">批量新增</a></li>
                <li class="list"><a>切换到列表</a></li>
            </ul>
        </div>
    </div>

    
</div>
<div class="content clear">

            <ul class="been_marked">
                <li><a><em></em>全部视频</a></li>
                <li class="color_green check"><a id="col_1"  onclick="check(this);"><em></em>编辑上传</a>
                    <ul id="col_1_1" style="display:none">
                        <li><a>网台原创</a></li>
                        <li><a>网台原创</a></li>
                        <li><a>网台原创</a></li>
                        <li><a>网台原创</a></li>
                    </ul>
                </li>
                <li class="color_blue">
                    <a id="col_3" onclick="check(this);"><em></em>网友上传</a>
                    <ul id="col_3_1" style="display:none">
                        <li><a>网台原创</a></li>
                        <li><a>网台原创</a></li>
                        <li><a>网台原创</a></li>
                        <li><a>网台原创</a></li>
                    </ul>
                </li>
                <li class="color_yellow"><a id="col_4" onclick="check(this);" ><em></em>直播归档</a>
                    <ul id="col_4_1" style="display:none">
                        <li><a>网台原创</a></li>
                        <li><a>网台原创</a></li>
                        <li><a>网台原创</a></li>
                        <li><a>网台原创</a></li>
                    </ul>
                </li>
                <li class="color_zs"><a id="col_5" onclick="check(this);"><em></em>标注归档</a>
                    <ul id="col_5_1" style="display:none">
                        <li><a>网台原创</a></li>
                        <li><a>网台原创</a></li>
                        <li><a>网台原创</a></li>
                        <li><a>网台原创</a></li>
                    </ul>
                </li>
                <li class="sy"><a><em></em><strong></strong>最近使用</a></li>
                <li class="sc"><a><em></em><strong></strong>星标收藏</a></li>
                <li class="jh"><a><em></em><strong></strong>视频集合</a></li>
            </ul>

          <div class="right" style="background:url(images/ybz_title.png) top repeat-x;border-left:1px solid #d8d8d8;min-width:1000px;">
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
                    
                    
                    
                    <li class="clear">
                    	<span class="left"><a class="lb"><input name="" type="checkbox" value="" /></a><a class="slt"><img src="IMG/22.gif" width="40" height="30" /></a><a class="bf"><em class="current"></em></a></span>
                        <span class="right"><a class="fb"><em class="b2"></em></a><a class="ml"><em>500</em></a><a class="fl"><em class="color_zs">标注归档</em></a><a class="zt">待审核</a><a class="tjr"><em>马婷婷</em><span>2011-12-12 12:12:60</span></a>
                        
                             <span class="fb_column">
                             	<span class="fb_column_l"></span>
                                <span class="fb_column_r"></span>
                                <span class="fb_column_m"><em></em><span class="fsz">发送至栏目：</span><a>国内</a>，<a>国外</a></span>
                        	 </span>
                        </span><span class="title"><em></em><a href="javascript:void(0);" onclick="check_menu(1,3);" id="t_1">王立军全票当选重庆市副市长体现啥？<strong>3'21"</strong></a>
                        
                       
                        
                      </span>
                        <div class="content_more clear" id="content_1" style="display:none">
                            	<ul class="content_more_left">
                                	<li>来&nbsp;&nbsp;&nbsp;&nbsp;源：<span>新闻综合频道</span></li>
                                    <li>分&nbsp;&nbsp;&nbsp;&nbsp;类：<span>新闻综合频道</span></li>
                                    <li>关键字：<span>新闻综合频道</span></li>
                                    <li>发布至：<span>新闻综合频道</span></li>
                                    <li class="more">描&nbsp;&nbsp;&nbsp;&nbsp;述：<span>新闻综合频道新闻综合频道新闻综合频道新闻综合频道新闻综合频道新闻综合频道新闻综合频道新闻综合频道新闻综合频道新闻综合频道</span></li>
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
                    
                 <li class="clear">
                    	<span class="left"><a class="lb"><input name="" type="checkbox" value="" /></a><a class="slt"><img src="IMG/13.gif" width="40" height="30" /></a><a class="bf"><em></em></a></span>
                        <span class="right"><a class="fb"><em class="b2"></em></a><a class="ml"><em>400</em></a><a class="fl"><em class="color_green">编辑上传</em></a><a class="zt">待审核</a><a class="tjr"><em>马婷婷</em><span>2011-12-12 12:12:60</span></a></span><span class="title"><em></em><a href="javascript:void(0);" onclick="check_menu(2,3);" id="t_2">安徽铜陵狮子山区加强党的建设工作综述<strong>3'21"</strong></a></span>
                        <div class="content_more clear" id="content_2" style="display:none">
                            	<ul class="content_more_left">
                                	<li>来&nbsp;&nbsp;&nbsp;&nbsp;源：<span>新闻综合频道</span></li>
                                    <li>分&nbsp;&nbsp;&nbsp;&nbsp;类：<span>新闻综合频道</span></li>
                                    <li>关键字：<span>新闻综合频道</span></li>
                                    <li>发布至：<span>新闻综合频道</span></li>
                                    <li class="more">描&nbsp;&nbsp;&nbsp;&nbsp;述：<span>新闻综合频道新闻综合频道新闻综合频道新闻综合频道新闻综合频道新闻综合频道新闻综合频道新闻综合频道新闻综合频道新闻综合频道</span></li>
                                </ul>
                            <div class="content_more_right clear">
                            <ul>
                            	<li class="button">
                                        <span class="button_left"></span>
                                        <span class="button_right"></span>
                                        <span class="button_middle"><a>编辑</a></span>
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
                    
                    
                    
                    <li class="clear">
                    	<span class="left"><a class="lb"><input name="" type="checkbox" value="" /></a><a class="slt"><img src="IMG/1.gif" width="40" height="30" /></a><a class="bf"><em></em></a></span>
                        <span class="right"><a class="fb"><em></em></a><a class="ml"><em>300</em></a><a class="fl"><em class="color_blue">网友上传</em></a><a class="zt"><em><sup>转码中</sup><sub><span></span></sub></em></a><a class="tjr"><em>马婷婷</em><span>2011-12-12 12:12:60</span></a></span><span class="title"><em></em><a href="javascript:void(0);" onclick="check_menu(3,3);" id="t_3">前波黑塞族领导人被控战争罪逃亡10余年被捕(组图)<strong>3'21"</strong></a></span>
                        <div class="content_more clear" id="content_3" style="display:none">
                            	<ul class="content_more_left">
                                	<li>来&nbsp;&nbsp;&nbsp;&nbsp;源：<span>新闻综合频道</span></li>
                                    <li>分&nbsp;&nbsp;&nbsp;&nbsp;类：<span>新闻综合频道</span></li>
                                    <li>关键字：<span>新闻综合频道</span></li>
                                    <li>发布至：<span>新闻综合频道</span></li>
                                    <li class="more">描&nbsp;&nbsp;&nbsp;&nbsp;述：<span>新闻综合频道新闻综合频道新闻综合频道新闻综合频道新闻综合频道新闻综合频道新闻综合频道新闻综合频道新闻综合频道新闻综合频道</span></li>
                                </ul>
                            <div class="content_more_right clear">
                            <ul>
                            	<li class="button">
                                        <span class="button_left"></span>
                                        <span class="button_right"></span>
                                        <span class="button_middle"><a>编辑</a></span>
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
                </ul>
            </div>
          <div class="bottom clear">
        <div class="left"><a class="lb"><input type="checkbox" value="" name=""></a><a>审核</a><a>审核</a><a>审核</a><a>审核</a><a>审核</a></div>
            	<div align="center" class="fy"><span class="xiy"><a>上一页</a></span><span class="xiy"></span><span class="xiy"></span> <span class="lj">1</span> <a href="179562_2.html" title="2" class="lj2">2</a> <a href="179562_3.html" title="3" class="lj2">3</a><span class="xiy"><a id="liv_nextpage" href='179562_2.html' title='下一页'>下一页</a></span><span class="xiy"><a href='179562_4.html' title='最后一页'>末页</a></span><span class="fl">&nbsp;&nbsp;总共: 4页</span></div>
            </div>	

</div>
<div style="display:none" class="iframe" id="loginForm">
<div class="bg_middle">
            <div class="info clear">
            	<div class="colonm input" id="colonm_id">
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
            <a id="volume" href="#"><span class="mark-bg"></span>切换到批量模式</a>
            </div>           	
                <div class="info clear">
               	  	<div class="info-left"><input type="text"  class="info-title info-input-left"/><textarea rows="2" class="info-description info-input-left"></textarea></div>
                      <div class="info-right">
                      		
                          <div class="laiyuan input" id="">
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
                          <span class="left">来源：</span>
                      </div>
                </div>
                <div class="info">
                	<table width="100%" border="0" class="info-table">
                      <tr>
                        <td width="4%">副题</td>
                        <td width="96%"><input type="text"  class="subtitle info-input-left"/></td>
                      </tr>
                      <tr>
                        <td>来源</td>
                        <td><select name="" class="subtitle info-input-right" style="width:180px;height:24px;border:1px solid #C5C4C4">
                          <option value="自动"></option>
                        </select></td>
                      </tr>
                    </table>
              </div>
              
              <div class="info_show">
                            <ul class="part clear">
                               <li id="show" class="show">
                               <ul id="column_1" class="clear">
                                   <li>幸福晚点名</li>
                                   <li>百姓聊斋</li>
                                   <li>非诚勿扰</li>
                               </ul>
                               <span id="edit" class="edit"></span><a id="column_a">发布至网站栏目</a>
                               
                               </li>
                               <li id="column" class="clear shows">
         	<div id="all">
            	<div class="pub_div_bg clear">
                    <div class="pub_div clear">
                     <ul>
                     	<li class="first"><span class="checkbox"></span><a href="##">最近使用<strong>&gt;&gt;</strong></a></li>
                          <li><input name="" type="checkbox" value="" class="checkbox" /><a href="##">新闻<strong>&gt;&gt;</strong></a> </li>
                          <li><input name="" type="checkbox" value="" class="checkbox" /><a href="##">财经<strong>&gt;&gt;</strong></a></li>
                          <li><input name="" type="checkbox" value="" class="checkbox" /><a href="##">体育<strong>&gt;&gt;</strong></a></li>
                          <li><input name="" type="checkbox" value="" class="checkbox" /><a href="##">财经<strong>&gt;&gt;</strong></a></li>
                          <li><input name="" type="checkbox" value="" class="checkbox" /><a href="##">汽车<strong>&gt;&gt;</strong></a></li>
                        <li><input name="" type="checkbox" value="" class="checkbox" /><a href="##">科技<strong>&gt;&gt;</strong></a></li>
                        
                        <li><input name="" type="checkbox" value="" class="checkbox" /><a href="##">女性<strong>&gt;&gt;</strong></a></li>
                        <li><input name="" type="checkbox" value="" class="checkbox" /><a href="##">时尚<strong>&gt;&gt;</strong></a></li>
                        <li><input name="" type="checkbox" value="" class="checkbox" /><a href="##">美食<strong>&gt;&gt;</strong></a></li>  
                     </ul>
                    </div>
                 <div class="pub_div">
                     <ul>
                        <li><input name="" type="checkbox" value="" class="checkbox" /><a href="###">高清首页<strong>&gt;&gt;</strong></a></li>
                        <li><input name="" type="checkbox" value="" class="checkbox" /><a href="###">电影<strong>&gt;&gt;</strong></a></li>
                        <li><input name="" type="checkbox" value="" class="checkbox" /><a href="###">时事<strong>&gt;&gt;</strong></a></li>
                         <li><input name="" type="checkbox" value="" class="checkbox" /><a href="###">大周五电影院<strong>&gt;&gt;</strong></a></li>
                          <li><input name="" type="checkbox" value="" class="checkbox" /><a href="###">直播<strong>&gt;&gt;</strong></a></li>
                     </ul>
                 </div>
             </div>
	     
 </div>
 <input type="hidden" value="" id="select_cols" name="all_col_target">
                               </li>
                            </ul>
                            <!--* <dl class="published">
                            	<dt>发布时间</dt>
                                <dd><input name="" type="text" value="2010-12-13 16：32：16" /></dd>
                            </dl>
                            <dl class="workflow">
                            	<dt>工作流</dt>
                                <dd><input name="" type="text" /></dd>
                            </dl> -->
                        	
              </div>

              <div class="submit clear">
              	<span>
                <a>编辑：刘军</a>
              	<a>退出</a>
                </span>
              	<input type="submit" class="fix" value="确定并继续标注">
                <input type="submit" class="fix" value="确定">
              </div>
            </div>

</div>  
</body>
</html>
