//元素名称：m_n; tab_m_n
//count:交换的个数
function showtab(m,n,count)
{
	for(var i=1;i<=count;i++)
	{
		if (i==n)
		{			
			getObject("td_"+m+"_"+i).parentNode.className="cur";
			getObject("tab_"+m+"_"+i).className="sub_nav_show";
		}
		else
		{
			getObject("td_"+m+"_"+i).parentNode.className="uncur";
			getObject("tab_"+m+"_"+i).className="sub_nav_hidden";
		}
	}
}
function showtab1(m,n,count)
{
	for(var i=1;i<=count;i++)
	{
		if (i==n)
		{			
			getObject("td_"+m+"_"+i).className="curs";
			getObject("tab_"+m+"_"+i).className="sub_nav_show";
		}
		else
		{
			getObject("td_"+m+"_"+i).className="uncurs";
			getObject("tab_"+m+"_"+i).className="sub_nav_hidden";
		}
	}
}
function showtab2(m,n,count)
{
	for(var i=1;i<=count;i++)
	{
		if (i==n)
		{			
			getObject("td_"+m+"_"+i).className="cur2";
			getObject("tab_"+m+"_"+i).className="sub_nav_show";
		}
		else
		{
			getObject("td_"+m+"_"+i).className="uncur2";
			getObject("tab_"+m+"_"+i).className="sub_nav_hidden";
		}
	}
}
function getObject(objectId)
{
	if(document.getElementById && document.getElementById(objectId))
	{
		// W3C DOM
		return document.getElementById(objectId);
	}
	else if(document.all && document.all(objectId))
	{
		// MSIE 4 DOM
		return document.all(objectId);
	}
	else if(document.layers && document.layers[objectId])
	{
		// NN 4 DOM.. note: this won't find nested layers
		return document.layers[objectId];
	}
	else
	{
		return false;
	}
}