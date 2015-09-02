//定义相关变量
var title;
var ptime;
var body;
var replyCount;
var replyBoard;
var source;
var more;
var moreButton;
var myBody;
var myTitle;
var textSize;
var width;
var hideConditionNum;
var topHideNum;
var bottomHideNum;
var voteArray = {};

var lastRequestLoadImgStart = -1;
var lastRequestLoadImgEnd = -1;

var myVote;

function getTitle() {
	myTitle = document.getElementById("title");
	if (window.news) {
		title = window.news.getTitle();
		myTitle.innerHTML = title;
	}
}

function getSource() {
	var mySource = document.getElementById('source');
	if (window.news) {
		source = window.news.getSource();
		mySource.innerHTML = source;
	}
}

function getTime() {
	var myTime = document.getElementById('ptime');
	if (window.news) {
		ptime = window.news.getTime();
		myTime.innerHTML = ptime;
	}
}

function getBody() {
	myBody = document.getElementById('article_body');
	if (window.news) {
		body = window.news.getBody();
		myBody.innerHTML = body;
	}
}

function getTextSize() {
	if (window.news) {
		textSize = window.news.getTextSize();
		switch (textSize) {
		case 0:
			showSuperBigSize();
			break;
		case 1:
			showBigSize();
			break;
		case 2:
			showMidSize();
			break;
		case 3:
			showSmallSize();
			break;
		}
	}
}

function goToLink(url) {
	if (window.news) {
		window.news.goToLink(url);
	}
}

function getScreenWidth() {
	if (window.news) {
		width = window.news.getScreenWidth();
	}
}

function initialize() {
	// getIndexPic();
	// getMainColor();
	// getTitle();
	// getSource();
	// getTime();
	getBody();
	// var body = document.getElementsByTagName("body")[0];
	// body.style.display = '';
}

function over(obj) {
	var showmore = document.getElementById('showmore');
	var loading = document.getElementById('loading');

	showmore.style.position = "relative";
	showmore.style.top = 1;
	showmore.style.left = 1;

	showmore.style.display = "none"

	loading.style.display = "block";

	if (window.news) {
		window.news.getMore();
	}

}

function goMoreTie() {
	if (window.news) {
		window.news.goMoreTie();
	}
}

function isLink(node) {
	var node = node;
	// 如果是相关新闻就作为超链接处理
	if (node.id.indexOf("relative_") != -1) {
		return true;
	}

	while (node && node.nodeName && node.nodeName != "A"
			&& node.nodeName != "IMG") {
		if (node.nodeName == "HTML")
			return false;
		node = node.parentNode;
	}
	return true;
}

function clickEvent() {
	if (isLink(event.target))
		return;
}

document.addEventListener('click', clickEvent, false);

// 修改相关新闻字体
function showRelativeNewsTextSize(size) {
	var relativeNames = document.getElementsByName("relative_name");
	for (i = 0; i < relativeNames.length; i++) {
		relativeNames[i].style.fontSize = size + "px";
	}
}

function showSuperBigSize() {
	myBody.style.fontSize = "26px";
	myBody.style.lineHeight = "160%";
}

function showBigSize() {
	myBody.style.fontSize = "22px";
	myBody.style.lineHeight = "160%";
}

function showMidSize() {
	myBody.style.fontSize = "18px";
	myBody.style.lineHeight = "180%";
}

function showSmallSize() {
	myBody.style.fontSize = "16px";
	myBody.style.lineHeight = "160%";
}

function toRelative(i) {
	if (window.news) {
		window.news.toRelative(i);
	}
}

function setRelativeIsRead(obj) {
	var ids = obj.split(",");
	for ( var i = 0; i < ids.length; i++) {
		document.getElementById("relative_" + ids[i]).style.color = '#8E8E8E';
	}
}

function setImage(nodeSrcName, filePath) {
	var obj = document.getElementsByName(nodeSrcName);
	for ( var o in obj) {
		var ob = obj[o];
		if (filePath) {
			ob.setAttribute("src", "file://" + filePath);
		} else {
			ob.setAttribute("src", "big_reload_img.png");
		}
	}
}

function openImageUrl(img) {
	var src = img.getAttribute("src");
	var name = img.getAttribute("name");
	if ("big_reload_img.png" == src) {
		img.setAttribute("src", "default_pic.png");
		setTimeout(function(){
			window.news.reload(name);
		},1000); 
	} else if ("default_pic.png" == src) {

	} else {
		window.news.openImageUrl(name);
	}
}

function openImageUrls(img) {
	var src = img.getAttribute("src");
	var name = img.getAttribute("name");
	var column = img.getAttribute("column");
	if ("big_reload_img.png" == src) {
		img.setAttribute("src", "default_pic.png");
		setTimeout(function(){
			window.news.reload(name);
		},1000); 
	} else if ("default_pic.png" == src) {

	} else {
		window.news.openImageUrls(column);
	}
}

function openVideo(img) {
	var src = img.getAttribute("src");
	var name = img.getAttribute("name");
	var column = img.getAttribute("column");
	if ("big_reload_img.png" == src) {
		window.news.reload(name);
	} else if ("default_pic.png" == src) {

	} else {
		window.news.openVideoUrl(column);
	}
}


// 初始化函数
window.onload = function() {
	// var body = document.getElementsByTagName("body")[0];
	// body.style.display = 'none';
	// 延迟300ms, 防止4.4系统显示的问题
	setTimeout('initialize()', 300);
}

/** ***********************投票开始************************************************************* */

function submitVote(voteid, max_option) {
	var vote_bodys = document.getElementsByName(voteid);
	var items = document.getElementById(voteid).getElementsByTagName("img");
	var checkedItems = "";
	var checkedCount = 0;
	for ( var i = 0; i < items.length; i++) {
		var item = items[i];
		var voopid = item.getAttribute("tag");
		var src = item.getAttribute("src");
		if (src == "topic_subscribed.png") {
			checkedItems += voopid + "%2C";
			checkedCount++;
		}
	}
	if (checkedCount == 0) {
		window.news.toast("至少选择一个选项");
		return;
	}
	if (checkedCount > max_option) {
		window.news.toast("最多选择" + max_option + "个选项");
		return;
	}
	window.news.submitVote(checkedItems, voteid);
}

function selectOption(d) {
	var img = d.getElementsByTagName("img")[0];
	var che = img.getAttribute("src");
	if (che == "topic_subscribed.png") {
		img.setAttribute("src", "topic_not_subscribed.png");
	} else {
		img.setAttribute("src", "topic_subscribed.png");
	}
}

// 取消投票
function cancelVoteImg(id) {
	var img = document.getElementById(id);
	img.setAttribute("src", "topic_not_subscribed.png");

}

function getIndexPic() {
	var img = document.getElementById("indexpic");
	if (img) {
		var h = img.style.height;
		window.news.getIndexPic(h);
	}

}

function setIndexPic(path) {
	var img = document.getElementById("indexpic");
	if (img) {
		img.style.backgroundImage = "url(" + path + ")";
	}

}

function getMainColor() {
	if (window.news) {
		var color = window.news.getMainColor();
		myTitle = document.getElementById("title");
		myTitle.style.borderColor = color;

		var isbackground = myTitle.getAttribute("isbackground");
		if (isbackground) {
			myTitle.style.background = color;
		}

		var pTime = document.getElementById("ptime");
		pTime.style.borderColor = color;
	}

}

function showVoteResult(id, vote_sum) {
	var vote_bodys = document.getElementsByName(id);
	for ( var i = 0; i < vote_bodys.length; i++) {
		var vote_body = vote_bodys[i];
		if (i == 0) {
			vote_body.style.display = "none";
		} else {
			vote_body.style.display = "block";
		}
	}
	var ele = document.getElementById(id + "_total");
	if (ele)
		ele.innerText = vote_sum + "人参与";

	var button = document.getElementById(id + "_button");
	if (button)
		button.style.display = "none";
}

function calculateVoteResult(id, percentage) {
	var html_body1 = document.getElementById(id + "_1");
	var html_body2 = document.getElementById(id + "_2");
	var pos, runTime, startTime = +new Date, timer = setInterval(function() {
		runTime = +new Date - startTime;
		pos = runTime / 350;
		if (pos >= 1) {
			clearInterval(timer);
			html_body1.style.width = 85 * percentage + '%';
		} else {
			html_body1.style.width = 85 * percentage * pos + '%';
		}
	}, 13);
	html_body2.innerText = round2(percentage * 100, 1) + "%";
}

function round2(number, fractionDigits) {
	with (Math) {
		return round(number * pow(10, fractionDigits))
				/ pow(10, fractionDigits);
	}
}

/** ***********************投票结束************************************************************* */

function goToCommentList(){
	if (window.news) {
		window.news.goToCommentList();
	}
}

function goToLink(url){
	if (window.news) {
		window.news.goToLink(url);
	}
}

