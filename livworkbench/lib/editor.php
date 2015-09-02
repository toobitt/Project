<?php
/***************************************************************************
* LivCMS5.0
* (C)2009-2010 HOGE Software.
*
* $Id: editor.php 5439 2013-12-16 10:22:32Z zhangfeihu $
***************************************************************************/

class Editor
{
	public $settings;
	public $mEditorNum = 0;
	function __construct($num = 0,$lang = 'schi')
	{
		//$this->name = 'editor';
		$lang = 'zh-CHS';
		$this->name = RESOURCE_DIR.'LiveEditor';
		if(!$num)
		{
			if ('english' != $lang)
			{
				echo '<script type="text/javascript">var LanguageDirectory = "' . $lang . '";</script>';
				echo '<script type="text/javascript" src="' .$this->name.'/scripts/language/' . $lang . '/editor_lang.js"></script>';
			}
			echo '<script type="text/javascript" src="' .$this->name.'/scripts/innovaeditor.js"></script>';
		}
	}

	function __destruct()
	{
	}

	/**
	 * 编辑器内容显示处理
	 * @param $sHTML 编辑器内容
	 * @return String 处理后的内容
	 */
	public function EncodeHtml($sHTML)
	{
		$sHTML = stripslashes($sHTML);
		$sHTML = str_replace('&amp;', '&', $sHTML);
		$sHTML = str_replace('&', '&amp;', $sHTML);
		$sHTML = str_replace('<', '&lt;', $sHTML);
		$sHTML = str_replace('>', '&gt;', $sHTML);
		return $sHTML;
	}

	/**
	 * 创建编辑器代码
	 * @param $ElementName 需要初始化成编辑器的元素
	 * @param $w 初始化宽度
	 * @param $h 初始化高度
	 * @return String 初始化代码， 在此元素下方输出
	 */
	public function InitEditor($ElementName = '', $w = 0, $h = 0)
	{
	//	$this->mEditorNum++;
		$nc = '_'.$ElementName;
		$init_code = '
		  <script type="text/javascript">
			var oEdit' . $nc . ' = new InnovaEditor("oEdit' . $nc . '");
				oEdit' . $nc . '.features=["FullScreen","Table","Guidelines","|","Undo","Redo","|","Hyperlink","Image","|","JustifyLeft","JustifyCenter","JustifyRight","JustifyFull","|","RemoveFormat","FontName","FontSize","Bold","ForeColor","BackColor","|","XHTMLSource"];
		';
		/*
			oEdit' . $nc . '.arrCustomButtons = [["paging","insert_paging();","插入分页符","paging.gif"]];
		oEdit' . $nc . '.arrCustomButtons = [["imgLocal","submit_allimg();","图片本地化","imgLocal.gif"]];*/
/*"Cut","Copy","Paste","PasteText","|",*/
		if ($w)
		{
			$init_code .= '
			oEdit' . $nc . '.width = ' . $w . ';';
		}
		if ($w)
		{
			$init_code .= '
				oEdit' . $nc . '.height = ' . $h . ';';
		}
		$init_code .= '
				oEdit' . $nc . '.mode = "HTMLBody";
				oEdit' . $nc . '.REPLACE("' . $ElementName . '");
			function insert_paging()
			{
				var oEditor = oUtil.oEditor;
				var oSel=oEditor.getSelection();
				var range = oSel.getRangeAt(0);
				sHTML = "<br\/>\/0<br\/>";
				var docFrag = range.createContextualFragment(sHTML);
				range.deleteContents();
				range.insertNode(docFrag);
			}
		  </script>';
		return $init_code;
	}

	public function InitEditor_page_bak($ElementName = '', $w = 0, $h = 0)
	{
		$str = '
				<script type="text/javascript" src="1' .$this->name.'/scripts/editor_extra.js"></script>
				<script language="javascript" type="text/javascript">
				var ElementName = "' . $ElementName . '";
				</script>
				<script language="javascript" type="text/javascript">
				//InnovaEditor.new2012 = true;
				var oEdit1 = new InnovaEditor("oEdit1");
				oEdit1.width = ' . ($w < 690 ? 690 : $w). ';
				oEdit1.height = ' . $h . ';
				if(!InnovaEditor.new2012){
					oEdit1.features = ["FullScreen","Flash","Media","Image","|",
					"Undo","Redo","|","Hyperlink","Bookmark","|",
					"JustifyLeft","JustifyCenter","JustifyRight","JustifyFull","|",
					"FontName","FontSize","|",
					"Bold","Italic","Underline","Strikethrough","|",
					"ForeColor","BackColor","ClearAll","paging","XHTMLSource"];
					oEdit1.arrCustomButtons = [["paging","insert_paging();","插入分页符","btnPaging.gif"]];
				}else{
					oEdit1.features = ["Undo","Redo","FontName","FontSize","Bold","Italic","Underline","ForeColor","JustifyLeft","JustifyCenter","JustifyRight","Hyperlink","Media","paging","Image","FullScreen"];
					oEdit1.arrCustomButtons = [["paging","insert_paging();","插入分页符","btnPaging.gif"]];
				}


				oEdit1.mode="HTMLBody";
				';
		$str .= '
			function insert_paging()
			{
				var title = insert_paging.title;
				var oEditor = oUtil.oEditor;
				/*var oSel=oEditor.getSelection();
				var range = oSel.getRangeAt(0);
				oEdit1.saveForUndo();*/
				var str = oEditor.document.body.innerHTML;
				var pagebg = "<div style=\"margin-top:20px;font-size:0;line-height:0;height:8px;background:url('.$this->name.'/scripts/icons/page/bg.gif) repeat-x 0 0;\">&nbsp;<\/div>";
				var tpl = "<span style=\"display:inline-block;margin:10px 30px 0;color:#ccc;font-size:12px;\">page:{number}</span><div style=\"margin:10px 20px;text-align:center;color:#ccc;height:22px;line-height:1;border:1px dashed #ccc;font-size:22px;padding:9px 0;font-weight:bold;vertical-align:middle;\" _pagebiaozhi=\"1\">" +title+ "<\/div><br\/>";
				var tplreplace = function(number){
					return tpl.replace("{number}", number);
				}
				var matchs = str.match(/_pagebiaozhi/ig);
				if(!matchs){
					oEditor.document.body.innerHTML += tplreplace(1);
					if(navigator.appName.indexOf("Microsoft")!=-1){
						oUtil.oEditor.document.body.attachEvent("onmouseup", insert_paging.mouseup);
					}else{
						oUtil.oEditor.document.addEventListener("mouseup", insert_paging.mouseup, false);
					}
				}
				else{
					var number = matchs.length + 1;
					tpl = pagebg + tplreplace(number);
					oEditor.document.body.innerHTML += tpl;
					/*var docFrag = range.createContextualFragment(sHTML);
					range.deleteContents();
					range.insertNode(docFrag);*/
				}
				oEditor.focus();
			}
			insert_paging.title = "请输入分页标题";
			insert_paging.mouseup = function(event){
				event = event || oUtil.oEditor.event || window.event;
				if(!event) return;
				var ctarget;
				if(ctarget = insert_paging.target){
					var cinner = ctarget.innerHTML;
					if(cinner == "&nbsp;" || cinner == ""){
						ctarget.innerHTML = insert_paging.title;
						ctarget.style.border = "1px dashed #ccc";
						ctarget.style.color = "#ccc";
						insert_paging.target = null;
					}
				}
				var target = event.target || event.srcElement;
				if(target && target.getAttribute("_pagebiaozhi") == 1){
					if(target.innerHTML == insert_paging.title){
						target.innerHTML = "&nbsp;";
						target.style.border = "1px solid #ccc";
						target.style.color = "#000";
						insert_paging.target = target;
					}
				}
			}
		  </script>';
				return $str;
	}

	public function InitEditor_page_bak_20120702($ElementName = '', $w = 0, $h = 0, $num = 1)
	{
		$str = '
				<script type="text/javascript">
					'.($num == 1 ? '
					function EditorPage(index){
						var oEditor = window["oEdit" + index];
						var oEditorWindow = oUtil.oEditor;
						var node;
						if (navigator.appName.indexOf("Microsoft") != -1) {
							var selection = oEditorWindow.document.selection.createRange();
							if(selection.parentElement){
								node = selection.parentElement();
							}else{
								node = selection.item(0);
							}
						}else{
							var selection = oEditorWindow.getSelection();
							node = getSelectedElement(selection);
						}
						if(checkInPage(node)){
							alert("不能在此添加分页");
							return false;
						}

						oEditor.saveForUndo();
						var title = EditorPage.title;
						var bg = "background:url('.$this->name.'/scripts/icons/page/bg.gif) repeat-x 0 0;";
						var pagebgbefore = "<div style=\"padding:40px 0 10px 0;margin:20px -15px 0;position:relative;{replace}\" _pagebiaozhi=\"1\" _pagebg=\"1\"><span style=\"position:absolute;right:0;top:10px;color:#ccc;cursor:pointer;padding:0 8px;\" _pagebiaozhi=\"1\" _pageclose=\"1\"></span>";
						var pagebgafter = "</div><br/>";
						var tpl = "<div _pagebiaozhi=\"1\" _pagetitle=\"1\" style=\"display:block;width:80%;margin:0 auto;height:22px;font-size:22px;padding:5px;border:1px dashed #ccc;text-align:center;color:#ccc;\">"+ title +"</div>";
						var hecheng = function(first){
							return pagebgbefore.replace("{replace}", first ? "" : bg) + tpl + pagebgafter;
						}
						var matchs = oEditor.getHTMLBody().match(/_pagetitle/ig);
						oEditor.insertHTML(hecheng());
						if(!matchs){
							oEditorWindow.document.body.innerHTML = hecheng(true) + oEditorWindow.document.body.innerHTML;
							oEditor.setFocus();
							if (navigator.appName.indexOf("Microsoft") != -1) {
								oEditorWindow.document.body.attachEvent("onmouseup", mouseup);
								oEditorWindow.document.body.attachEvent("onmouseup", keydown);
							}else{
								oEditorWindow.document.addEventListener("mouseup", mouseup, false);
								oEditorWindow.document.addEventListener("keydown", keydown, false);
								oEditorWindow.document.addEventListener("click", mouseclick, false);

							}
						}
						changePageNumber();

						function mouseclick(event){
							event = event || window.event;
							var target = event.target || event.srcElement;
							if(!target || !target.getAttribute("_pageclose")) return;
							var node = target.parentNode;
							var next = node.nextSibling;
							if(next && next.tagName.toLowerCase() == "br"){
								next.parentNode.removeChild(next);
							}
							oEditor.saveForUndo();
							var nearElement = node.nextSibling == null ? (node.previousSibling == null ? node.parentNode : node.previousSibling) : node.nextSibling;
							node.parentNode.removeChild(node);
							changePageNumber();
							if (navigator.appName.indexOf("Microsoft") != -1) {

							}else{
								var sel = oEditorWindow.getSelection();
								sel.removeAllRanges();
								var range = oEditorWindow.document.createRange();
								range.setStart(nearElement, 0);
								range.setEnd(nearElement, 0);
								sel.addRange(range);
								oEditor.focus();
							}
						}

						function mouseup(event){
							event = event || oEditorWindow.event;
							if(!event) return;
							var target = event.target || event.srcElement;
							var currentPage = oEditor.currentPage;
							if(currentPage && !check(currentPage.innerHTML)){
								currentPage.innerHTML = title;
								currentPage.style.border = "1px dashed #ccc";
								currentPage.style.color = "#ccc";
								oEditor.currentPage = null;
							}
							if(!target.getAttribute("_pagebiaozhi")){
								return;
							}
							if(target.getAttribute("_pagetitle")){
								if(check(target.innerHTML) == title){
									target.innerHTML = "&nbsp;";
									target.style.border = "1px solid #ccc";
									target.style.color = "#000";
									setTimeout(function(){
										target.focus();
									}, 200);
									oEditor.currentPage = target;
								}
							}

						}

						function keydown(event){
							event = event || oEditorWindow.event;
							var key = event.keyCode;
							var node;
							if (navigator.appName.indexOf("Microsoft") != -1) {
								var selection = oEditorWindow.document.selection.createRange();
								if(selection.parentElement){
									node = selection.parentElement();
								}else{
									node = selection.item(0);
								}
							}else{
								var selection = oEditorWindow.getSelection();
								node = getSelectedElement(selection);
							}
							if(key == 8){
								if(node.getAttribute("_pagetitle")){
									if(node.innerHTML == "&nbsp;"){
										event.preventDefault();
										event.returnValue = false;
									}
									return false;
								}
								var prev = node.previousSibling;
								if(!(prev && prev.getAttribute("_pagebiaozhi"))){
									return;
								}
								event.preventDefault();
								event.returnValue = false;
								oEditor.saveForUndo();
								if(node.tagName.toLowerCase() == "br"){
									node.parentNode.removeChild(node);
								}else if(node.tagName.toLowerCase() == "body"){

								}
								var nearElement = prev.nextSibling == null ? (prev.previousSibling == null ? prev.parentNode : prev.previousSibling) : prev.nextSibling;
								prev.parentNode.removeChild(prev);
								changePageNumber();
								if (navigator.appName.indexOf("Microsoft") != -1) {

								}else{
									var sel = oEditorWindow.getSelection();
									sel.removeAllRanges();
									var range = oEditorWindow.document.createRange();
									range.setStart(nearElement, 0);
									range.setEnd(nearElement, 0);
									sel.addRange(range);
									oEditor.focus();
								}
							}
							if(key == 13){
								if(checkInPage(node)){

									event.preventDefault();
									event.returnValue = false;
									var bg = getPageBg(node);
									//var title = bg.firstChild.nextSibling;

									var currentPage = oEditor.currentPage;
									if(currentPage && !check(currentPage.innerHTML)){
										currentPage.innerHTML = title;
										currentPage.style.border = "1px dashed #ccc";
										currentPage.style.color = "#ccc";
										oEditor.currentPage = null;
									}

									if (navigator.appName.indexOf("Microsoft") != -1) {

									}else{
										var sel = oEditorWindow.getSelection();
										sel.removeAllRanges();
										var range = oEditorWindow.document.createRange();

										var next = bg.nextSibling;
										range.setStart(next, 0);
										range.setEnd(next, 0);
										sel.addRange(range);
										oEditor.focus();
									}
								}
							}
						}

						function check(str){
							return str.replace(/^\s*|\s*$/, "").replace("&nbsp;", "");
						}

						function checkInPage(node){
							while(node){
								if(node.tagName == "BODY"){
									return false;
								}
								if(node.getAttribute("_pagebiaozhi")){
									return true;
								}
								node = node.parentNode;
							}
							return false;
						}

						function getPageBg(node){
							while(node){
								if(node.tagName == "BODY"){
									return null;
								}
								if(node.getAttribute("_pagebg")){
									return node;
								}
								node = node.parentNode;
							}
							return null;
						}
						function changePageNumber(){
							var alldiv = oEditorWindow.document.getElementsByTagName("DIV");
							var allpage = [];
							for(var i=0, len=alldiv.length; i<len; i++){
								if(alldiv[i].getAttribute("_pagebg")){
									allpage.push(alldiv[i]);
								}
							}
							if(allpage.length == 1){
								var next = allpage[0].nextSibling;
								if(next && next.tagName.toLowerCase() == "br"){
									next.parentNode.removeChild(next);
								}
								allpage[0].parentNode.removeChild(allpage[0]);
							}else{
								for(i=0, len=allpage.length; i<len; i++){
									if(i == 0){
										allpage[i].style.background = "none";
										allpage[i].style.marginTop = "0px";
									}else{
										allpage[i].style.background = "url('.$this->name.'/scripts/icons/page/bg.gif) repeat-x 0 0;";
										allpage[i].style.marginTop = "20px";
									}
									allpage[i].firstChild.innerHTML = "第"+ (i+1) +"页.删除";
								}
							}
						}

						function tip(str){
							var div = $(\'#div\');
							if(!div.get(0)){
								div = $(\'<div id="div"></div>\').appendTo(\'body\').css({
									position : \'fixed\',
									top : 0,
									right : 0,
									width : \'200px\',
									\'max-height\' : \'300px\',
									overflow : \'auto\',
									border : \'1px solid red\',
									background : \'#000\',
									color : \'#fff\'
								});
							}
							div.html(div.html() + str +\'<br />\');
						}
					}
					EditorPage.title = "请输入分页标题";
				' : '').'
				</script>
				<script language="javascript" type="text/javascript">
				var ElementName = "' . $ElementName . '";
				</script>
				<script type="text/javascript" src="' .$this->name.'/scripts/editor_extra.js"></script>
				<script language="javascript" type="text/javascript">
				var oEdit'.$num.' = new InnovaEditor("oEdit'.$num.'");
				oEdit'.$num.'.width = ' . $w. ';
				oEdit'.$num.'.height = ' . $h . ';
				oEdit'.$num.'.css = "'.$this->name.'/styles/default.css";
				oEdit'.$num.'.arrCustomButtons.push(["Page", "EditorPage('.$num.')", "插入分页符", "btnPage.gif"]);
				oEdit'.$num.'.groups = [
					["group1", "", ["Bold", "Italic", "Underline", "FontName", "ForeColor", "TextDialog", "RemoveFormat"]],
					["group2", "", ["Bullets", "Numbering", "JustifyLeft", "JustifyCenter", "JustifyRight"]],
					["group3", "", ["LinkDialog", "ImageDialog", "TableDialog", "Emoticons", "Page"]],
					["group4", "", ["Undo", "Redo", "FullScreen", "SourceDialog"]]
				];
				oEdit'.$num.'.useBR = true;
				oEdit'.$num.'.useDIV = false;

				</script>
				';
				return $str;
	}


	public function InitEditor_page($ElementName = '', $w = 0, $h = 0, $num = 1)
    {
        $autoheight = 500;
        $js = '
            var number = '.$num.';
            var name = "pagebg";
            var cname = "." + name;
            var parentObj = parent.window["cpage" + number];
            var autoHeight = '.$autoheight.';
        ';
        $js = str_replace(array("\n", "\r"), '', $js);
        $str = '
                <script language="javascript" type="text/javascript">
                var ElementName = "' . $ElementName . '";
                </script>
                <script type="text/javascript" src="' .$this->name.'/scripts/editor_extra.js"></script>
                <script>
                var _oldDoKeyPress = doKeyPress;
                window.doKeyPress = function(evt, obj){
                    if(evt.keyCode != 86){
                        _oldDoKeyPress(evt, obj);
                    }
                }

                </script>
                <!-- 分页JS -->
                <script type="text/javascript" src="' .$this->name.'/scripts/page_class.js"></script>
                <script type="text/javascript" src="' .$this->name.'/scripts/page_event_moz.js"></script>

                <!-- 右边统一JS + CSS -->
                <script type="text/javascript" src="' .$this->name.'/scripts/slide_class.js"></script>
                <link  rel="stylesheet" type="text/css" href="' .$this->name.'/scripts/slide_class.css"/>

                <!-- 图片管理JS -->
                <script type="text/javascript" src="' .$this->name.'/scripts/image_event.js"></script>
                <!-- <script type="text/javascript" src="' .$this->name.'/scripts/jquery.css3.js"></script> -->

                <!-- 图片属性管理JS -->
                <script type="text/javascript" src="' .$this->name.'/scripts/image_info_event.js"></script>
                <link  rel="stylesheet" type="text/css" href="' .$this->name.'/scripts/jquery-ui.css"/>
                <script type="text/javascript" src="' .$this->name.'/scripts/jquery-ui/jquery.ui.core.js"></script>
                <script type="text/javascript" src="' .$this->name.'/scripts/jquery-ui/jquery.ui.widget.js"></script>
                <script type="text/javascript" src="' .$this->name.'/scripts/jquery-ui/jquery.ui.mouse.js"></script>
                <script type="text/javascript" src="' .$this->name.'/scripts/jquery-ui/jquery.ui.slider.js"></script>


                <!-- 附件管理JS -->
                <script type="text/javascript" src="' .$this->name.'/scripts/attach_event.js"></script>

                <!-- 附件管理JS -->
                <script type="text/javascript" src="' .$this->name.'/scripts/refer_event.js"></script>

                <!-- 附件属性JS -->
                <script type="text/javascript" src="' .$this->name.'/scripts/refer_info_event.js"></script>

				<!-- 水印设置JS -->
				<link  rel="stylesheet" type="text/css" href="' .$this->name.'/scripts/colourPicker/colourPicker.css"/>
				<link  rel="stylesheet" type="text/css" href="' .$this->name.'/scripts/watermark.css"/>
				<script type="text/javascript" src="' .$this->name.'/scripts/colourPicker/colourPicker.js"></script>
				<script type="text/javascript" src="' .$this->name.'/scripts/watermark_event.js"></script>
				<script type="text/javascript" src="' .$this->name.'/scripts/jquery-ui/jquery.ui.draggable.js"></script>
                <script type="text/javascript" src="' .$this->name.'/scripts/jquery-ui/jquery.ui.droppable.js"></script>

                <!-- 统计设置JS -->
                <script type="text/javascript" src="' .$this->name.'/scripts/editor_statistics.js"></script>

                <!-- 标注面板JS -->
                <script type="text/javascript" src="' .$this->name.'/scripts/biaozhu_event.js"></script>

                <!-- 文本面板JS -->
                <script type="text/javascript" src="' .$this->name.'/scripts/text_event.js"></script>

                <!-- office上传JS -->
                <script type="text/javascript" src="' .$this->name.'/scripts/office.js"></script>

                <script language="javascript" type="text/javascript">
                oUtil.spcChar.length = oUtil.spcChar.length - 1;
                var oEdit'.$num.' = new InnovaEditor("oEdit'.$num.'");
                oEdit'.$num.'.css = "'.$this->name.'/styles/default.css";
                oEdit'.$num.'.arrCustomButtons.push(["Page", "EditorPage'.$num.'()", "插入分页符", "btnPage.gif"]);
                oEdit'.$num.'.arrCustomButtons.push(["AutoPage", "EditorAutoPage'.$num.'()", "自动插入分页符  '.$autoheight.'px分割 ", "btnAutoPage.gif"]);
                oEdit'.$num.'.arrCustomButtons.push(["CImage", "EditorImage'.$num.'()", "图片管理", "btnImage.gif"]);
                oEdit'.$num.'.arrCustomButtons.push(["CAttach", "EditorAttach'.$num.'()", "附件管理", "btnAttach.gif"]);
                oEdit'.$num.'.arrCustomButtons.push(["CRefer", "EditorRefer'.$num.'()", "引用素材", "btnRefer.gif"]);
                oEdit'.$num.'.arrCustomButtons.push(["CWatermark", "EditorWatermark'.$num.'()", "水印设置", "btnWaterMark.gif"]);
                oEdit'.$num.'.arrCustomButtons.push(["CLocal", "EditorLocalImage'.$num.'()", "图片本地化", "btnLocalImg.gif"]);

                oEdit'.$num.'.arrCustomButtons.push(["CText", "EditorText'.$num.'()", "文本", "btnText.gif"]);
                oEdit'.$num.'.arrCustomButtons.push(["CRemove", "EditorRemove'.$num.'()", "清除格式", "btnRemoveFormat.gif"]);

                if($.officeconvert){
                    oEdit'.$num.'.arrCustomButtons.push(["CWord", "EditorWord'.$num.'()", "上传word文档", "btnWord.gif"]);
                }

                oEdit'.$num.'.groups = [
                    ["group1", "", ["Bold", "Italic", "Underline", "ForeColor", "CText", "Bullets", "Numbering", "JustifyLeft", "JustifyCenter", "JustifyRight", "Undo", "Redo", "SourceDialog","Page", "AutoPage", "CImage", "CAttach", "CRefer", "CWatermark", "CLocal", /*"RemoveFormat",*/ "CRemove", "CWord" ]]
                ];

                oEdit'.$num.'.mode = "HTMLBody";
                oEdit'.$num.'.width = 690;
                oEdit'.$num.'.height = 400;
                //oEdit'.$num.'.useBR = false;
                //oEdit'.$num.'.useDIV = true;
                oEdit'.$num.'.useTagSelector = false;
                oEdit'.$num.'.showResizeBar = false;
                oEdit'.$num.'.returnKeyMode = 1;
                oEdit'.$num.'.onKeyPress = function(event){
                    try{
                        var ele = getSelectedElement($("#idContent" + this.oName)[0].contentWindow.getSelection());
                        var tagName = ele.tagName;
                        if(tagName == "BR"){
                            var $ele = $(ele).parent();
                            if($ele.is("div") && $ele.children().length == 1){
                                $ele.css("text-indent", "2em");
                                this.applyJustifyLeft();
                            }
                        }
                    }catch(e){}
                    return true;
                };



                var cpage'.$num.' = null;
                var slideManage'.$num.' = new SlideManage();
                var globalEditorConfig = {
                    path : "'.$this->name.'/scripts/",
                    //page : "<br class=\"pagebefore\" /><img class=\"pagebg\" src=\"' .$this->name.'/scripts/icons/page/bg.png\"/><br class=\"pageafter\" />"
                    page : "<img class=\"pagebg\" src=\"' .$this->name.'/scripts/icons/page/bg.png\"/>",
                    before : "<img src=\"'.$this->name.'/scripts/icons/slide/before-biaozhu-ok.png\" _id=\"{id}\" _name=\"{name}\" rand=\"{rand}\" class=\"before-biaozhu-ok\" />",
                    after : "<img src=\"'.$this->name.'/scripts/icons/slide/after-biaozhu-ok.png\" rand=\"{rand}\" class=\"after-biaozhu-ok\" />",
                };

                jQuery.pixelRatio = !!window.devicePixelRatio ? window.devicePixelRatio : 1;
                if(jQuery.pixelRatio > 1){
                    globalEditorConfig["page"] = "<img class=\"pagebg\" src=\"' .$this->name.'/scripts/icons/page/bg-2x.png\"/>";
                    globalEditorConfig["before"] = "<img src=\"'.$this->name.'/scripts/icons/slide/before-biaozhu-ok-2x.png\" _id=\"{id}\" _name=\"{name}\" rand=\"{rand}\" class=\"before-biaozhu-ok\" />";
                    globalEditorConfig["after"] = "<img src=\"'.$this->name.'/scripts/icons/slide/after-biaozhu-ok-2x.png\" rand=\"{rand}\" class=\"after-biaozhu-ok\" />";

                    jQuery(function($){
                        $("img[unselectable=\"on\"]").each(function(){
                            var me = $(this);
                            var src = me.css("width", "29px").attr("src");
                            var newSrc = src.replace(".gif", "-2x.gif");
                            var img = new Image();
                            img.onload = function(){
                                me.attr("src", newSrc);
                            };
                            img.src = newSrc;
                        });
                    });
                }

                function EditorRemove'.$num.'(){
                    mysaveForUndo();  //保存撤销
                    var content = $("#idContentoEdit'.$num.'");
                    var mywindow = content.get(0).contentWindow;
                    var body = $(mywindow.document.body);
                    body.find("img.before-biaozhu-ok, img.after-biaozhu-ok").remove();
                    body.find("img").each(function(){
                        var clone = $(this).clone();
                        var div = $("<div></div>");
                        var imgHtml = div.html(clone).html();
                        //$(this).replaceWith("{{{"+ $(this).attr("src") +"}}}");
                        $(this).replaceWith("{{{"+  encodeURIComponent(imgHtml) +"}}}");
                        div.remove();
                    });
                    body.find("br").each(function(){
                        $(this).replaceWith("{{{br}}}");
                    });
                    body.find("span[style]").filter(function(){
                        return $(this).css("font-weight") == "bold";
                    }).add(body.find("b, strong")).each(function(){
                        $(this).replaceWith("{{{strong}}}" + $.trim($(this).text()) + "{{{/strong}}}");
                    });
                    /*body.find("b, strong").each(function(){
                        $(this).replaceWith("{{{strong}}}" + $(this).text() + "{{{/strong}}}");
                    });*/
                    body.find("p").each(function(){
                        $(this).replaceWith("{{{p}}}" + $(this).text() + "{{{/p}}}");
                    });
                    var string = body.text();
                    string = string.replace(/({{{p}}}){1,}/g, "<p>");
                    string = string.replace(/({{{\/p}}}){1,}/g, "</p>");
                    string = string.replace(/({{{br}}}){1,}/g, "<br/>");
                    string = string.replace(/({{{strong}}}){1,}/g, "<strong>");
                    string = string.replace(/({{{\/strong}}}){1,}/g, "</strong>");
                    string = string.replace(/{{{([^}]*)}}}/g, function(all, match){
                        //return "<p style=\"text-align:center;text-indent:0;\"><img src=\""+ match +"\"/></p>";
                        return "<div style=\"text-align:center;\">"+ decodeURIComponent(match) +"</div>";
                    });
                    body.html(string);
                    body.find("img.pagebg").unwrap();
                    body.contents().filter(function(){
                        return this.nodeType == 3;
                    }).wrap("<p></p>");
                    body.find("p").each(function(){
                        if($(this).find("img").length){
                            return;
                        }
                        $(this).html(function(){
                            return $.trim($(this).html());
                        });
                    }).filter(function(){
                        return $.trim($(this).text()) == "";
                    }).remove();

                    body.find("br").filter(function(){
                        return $(this).prev().is("p") && $(this).next().is("p");
                    }).remove();

                    /*body.find("strong").each(function(){
                        $(this).replaceWith("<span>" + $(this).html() + "</span>").css("font-weight", "bold");
                    });*/
                }

                function EditorPage'.$num.'(){
                     mysaveForUndo();  //保存撤销
                     var editor = oEdit'.$num.';
                     var imgRand = + new Date();
                     var html = $(globalEditorConfig["page"]).attr("rand", imgRand)[0].outerHTML;
                     editor.insertHTML(html);
                     var $bg = $("#idContent" + editor.oName).contents().find("img.pagebg[rand=\"" + imgRand + "\"]").removeAttr("rand");
                     if($bg.length){
                        if(!$bg.parent().is("body")){
                            var $parent;
                            $bg.parents().each(function(){
                                if($(this).parent().is("body")){
                                    $parent = this;
                                    return false;
                                }
                            });
                            $parent && $bg.insertAfter($parent);
                        }
                     }
                     contentWindow'.$num.'("refresh");
                     window["slideManage'.$num.'"].openOne("pageslide");
                }
                function EditorAutoPage'.$num.'(){
                    mysaveForUndo();  //保存撤销
                    var editorWindow = $("#idContentoEdit'.$num.'")[0].contentWindow;
                    var autoPage = function(){
                        contentWindow'.$num.'("_auto");
                        window["slideManage'.$num.'"].openOne("pageslide");
                    };
                    if($(editorWindow.document).find(".pagebg").length){
                        jConfirm("编辑器里面已经有分页，确定要再自动分页？", "", function(result){
                            if(result){
                                autoPage();
                            }
                        }).position($("#AutoPageoEdit'.$num.'").parent()[0]);
                    }else{
                        autoPage();
                    }
                }
                function contentWindow'.$num.'(type, value){
                     var content = $("#idContentoEdit'.$num.'");
                     var mywindow = content.get(0).contentWindow;
                     if(1 || $.browser.mozilla){
                        $(mywindow.document).trigger(type, [value]);
                     }else if($.browser.webkit){
                        mywindow.jQuery(mywindow.document).trigger(type, [value]);
                     }
                }



                ;(function(){
                    var number = '.$num.';
                    var myoEditor =  window["oEdit" + number];
                    var myoEditorWindow, contentPage, content;
                    var timer = setInterval(function(){
                        content = $("#idContentoEdit" + number);
                        if(!content.get(0)){
                            return;
                        }
                        clearInterval(timer);

                        $("#oEdit"+ number +"grp").parent().addClass("editor-header").removeAttr("style");
                        $("#idAreaoEdit" + number).css("border-bottom", "none").find("td:first").removeAttr("style");
                        $("#cntContaineroEdit" + number).find("td:first").removeAttr("style").css({
                            "vertical-align" : "top"
                        });

                        var height = content.height();
                        content.before("<div class=\"content-page-outer-box\"><div class=\"content-page-box\" id=\"page-left-box"+ number +"\"></div></div>");
                        $("#oEdit" + number + "grp").css({"position" : "relative", "z-index" : 999});
                        content.parent().css("background", "#F2F2F2");

                        myoEditorWindow = content.get(0).contentWindow;

                        $("#btnBoldoEdit" + number).parents("td").eq(1).removeAttr("style");
                        content.height($(window).height() - 200);

                        /*$(window).on("resize", function(){
                            var win = $(window),
                                width = win.width() - parseInt($(".form-middle").css("left")),
                                height = win.height();
                            $("#idAreaoEdit" + number).css({
                                width : width + "px",
                                height : height + "px"
                            });
                            var content = $("#idContentoEdit" + number);
                            height = height - content.offset().top;
                            content.height(height);
                            var testDiv = $("<div/>").appendTo("body").css({
                                position : "absolute",
                                left : "-1000px",
                                top : 0,
                                width: "100px",
                                height : "20px",
                                overflow : "auto"
                            }).html("张飞虎，张飞虎，张飞虎，张飞虎，张飞虎，张飞虎，张飞虎，张飞虎，张飞虎，张飞虎，张飞虎，张飞虎，张飞虎");
                            var barWidth = parseInt(testDiv[0].offsetWidth) - parseInt(testDiv[0].clientWidth);
                            testDiv.remove();
                            testDiv=null;
                            content.width(barWidth + 691);
                        }).triggerHandler("resize");*/
                        /*$("<div id=\"global-bottom-mask\"></div>").appendTo(".form-middle").css({
                            position : "absolute",
                            bottom : 0,
                            left : 0,
                            "z-index" : 100000,
                            width : "100%",
                            height : "5px",
                            background : "#cfcfcf"
                        });*/

                        EditorImage'.$num.'(true);
                        EditorAttach'.$num.'(true);


                        window["cpage" + number] = new PageClass(number);

                        $.each(["page-normal.png", "page-current.png", "page-bg.png", "page-body.png"], function(i, n){
                            var img = new Image();
                            img.src = "' .$this->name.'/scripts/icons/page/" + n;
                        });

                        if(1 || $.browser.mozilla){
                            mozPageEvent(myoEditorWindow, window["cpage" + number], "pagebg", ".pagebg", '.$autoheight.', number);
                            var loadInit = false;
                            content.on("load", function(){
                                if(loadInit) return;
                                loadInit = true;
                                initEditorBind(myoEditorWindow, number);
                                contentWindow'.$num.'("refresh");
                            });
                            var intervalTimer = setInterval(function(){
                                if(loadInit){
                                    clearInterval(intervalTimer);
                                    return;
                                }
                                if(!$(myoEditorWindow.document).find("body").length){
                                    return;
                                }
                                loadInit = true;
                                initEditorBind(myoEditorWindow, number);
                                contentWindow'.$num.'("refresh");
                            }, 1000);
                        }else{
                            pageCreateScript(myoEditorWindow, "'.SCRIPT_URL.'jquery.min.js");
                            var bindtimer = window.setInterval(function(){
                                if(!myoEditorWindow.jQuery){
                                    return;
                                }
                                window.clearInterval(bindtimer);
                                pageCreateScript(myoEditorWindow, false, \''.$js.'\');
                                pageCreateScript(myoEditorWindow, "' .$this->name.'/scripts/page_event.js");
                                pageCreateScript(myoEditorWindow, false, "window.onload = function(){$(document).trigger(\"refresh\");}");
                            }, 100);
                        }

                        //initEditorBind(myoEditorWindow, number);
                        window["statistics" + number] = new editorStatistics(number);

                    }, 100);

                })();

                function EditorText'.$num.'(){
                    slideManage'.$num.'.close();
                    var me = EditorText'.$num.';
                    if(!me.event) {
                        me.slide = new SlideClass('.$num.', "text");
                        slideManage'.$num.'.add("text", me.slide);
                        me.event = new TextEvent('.$num.', me.slide);
                    }
                    me.slide.open();
                }

                function EditorImage'.$num.'(init){
                    slideManage'.$num.'.close();
                    var me = EditorImage'.$num.';
                    if(!me.event) {
                        me.slide = new SlideClass('.$num.', "image");
                        slideManage'.$num.'.add("image", me.slide);
                        me.event = new ImageEvent('.$num.', me.slide);
                        me.event.set(imgList);
                    }
                    !init && me.slide.open();
                }

                function EditorLocalImage'.$num.'(url, callback){
                    var me = EditorLocalImage'.$num.';
                    if(!me.get){
                        me.state = false;
                        me.get = function(){
                            return me.state;
                        };
                        me.set = function(state){
                            me.state = state;
                        }
                    }
                    if(me.get()){
                        jAlert("已有图片在本地化，请等下再操作！", "提示");
                        return;
                    }
                    me.set(true);
                    var num = '.$num.';
                    var start = function(cb, url){
                        EditorImage'.$num.'.event.local(function(result){
                            me.set(false);
                            cb && cb(result);
                        }, url);
                    }
                    if(!url){
                        var localOffset = $("#CLocaloEdit" + num).offset();
                        localOffset["top"] += 30;
                        var officeBox = $.officeBox(num).trigger("offset", [localOffset]).trigger("html", ["正在收集需要本地化的图片..."]);
                        setTimeout(function(){
                            start(function(result){
                                if(!result){
                                    officeBox.trigger("html", ["没有发现需要本地化的图片"]);
                                    setTimeout(function(){
                                        officeBox.trigger("hide");
                                    }, 800);
                                }
                            });
                        }, 1000);
                    }else{
                        start(callback, url);
                    }
                }

                function EditorAttach'.$num.'(init){
                    slideManage'.$num.'.close();
                    var me = EditorAttach'.$num.';
                    if(!me.event) {
                        me.slide = new SlideClass('.$num.', "attach");
                        slideManage'.$num.'.add("attach", me.slide);
                        me.event = new AttachEvent('.$num.', me.slide);
                        me.event.set(attachList);
                    }
                    !init && me.slide.open();
                }

                function EditorRefer'.$num.'(){
                    slideManage'.$num.'.close();
                    var me = EditorRefer'.$num.';
                    if(!me.event) {
                        me.slide = new SlideClass('.$num.', "refer");
                        slideManage'.$num.'.add("refer", me.slide);
                        me.event = new ReferEvent('.$num.', me.slide);
                    }
                    me.slide.open(1);
                }
				
				function EditorWatermark'.$num.'() {
					slideManage'.$num.'.close();
					var me = EditorWatermark'.$num.';
					if( !me.event ) {
						me.slide = new SlideClass('.$num.', "watermark");
						slideManage'.$num.'.add("watermark", me.slide);
						me.event = new WatermarkEvent('.$num.', me.slide);
					}
					me.slide.open();
				}

				function EditorWord'.$num.'(){
                    $.doWordUpload('.$num.');
				}

                </script>
                ';
                return $str;
    }

	
	
	public function InitEditor_page_none()
	{
	/*	<script type="text/javascript">var LanguageDirectory = "schi";</script>
		<script type="text/javascript" src="' . RESOURCE_DIR .  'editor/scripts/language/schi/editor_lang.js"></script>
		<script type="text/javascript" src="' . RESOURCE_DIR .  'editor/scripts/innovaeditor.js"></script>
		<script type="text/javascript" src="' . RESOURCE_DIR . 'editor/scripts/editor_extra.js"></script>*/
		$str = '';
		return $str;
	}
}


?>