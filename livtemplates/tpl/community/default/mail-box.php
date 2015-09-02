{css:mail-box}
<div class="person-mail" id="person-mail">
       <div class="mail-top-bg"></div>
       <div class="mail-con-bg">
            <div class="person-mail-title">
                <span>私信，青</span>
                <a class="mail-del"></a>
            </div>
            <div class="person-mail-con clearfix">
                <div class="mail-item">
                        <span>发给：</span><p type="text" name="textfield" id="textfield" class="mail-txt"/>{$userinfo['nick_name']}</p>
                        <span class="tip-span">还可输入<em>140</em>字</span>
                 </div>
                 <div class="mail-textarea-con">
                 	<textarea name="textarea" id="textarea" cols="45" rows="5" class="mail-txtarea"></textarea>
                 </div>
                 <div class="mail-btn">
                        <div class="face-btn"></div><div class="mail-face-box"></div>
                        <a class="vod-btn"></a>
                        <a class="mail-btn-public">发送</a>
                        <span class="tip-span" style="margin-right:10px;">还可输入<em>140</em>字</span>
                 </div>
            </div>
       </div>
       <div class="mail-bottom-bg clear"></div>
</div>
