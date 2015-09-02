<?php
define('WITHOUT_LOGIN', true);
define('WITH_DB', false);
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'googlemap');
require('./global.php');
if ($_INPUT['a'] == 'test')
{
	exit;
}
//$latlng = trim($_GET['latlng']);
$latlng = $_INPUT['latlng'];
if (intval($_INPUT['longitude']) || intval($_INPUT['latitude']))
{
	$latlng = $_INPUT['latitude'].'x'.$_INPUT['longitude'];
}else {
	$latlng = '';
}
$name = $_INPUT['areaname'];
$zoomsize = $_INPUT['zoomsize'];
$drag = intval($_INPUT['drag']);
$domobj_id = trim($_GET['objid']);
$width = intval($_GET['width']) . 'px';
$height = intval($_GET['height']) . 'px';
$default_lat = DEFAULT_LOCATION;
if (GOOGLE_MAP_KEY)
{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>google地图标注</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<style type="text/css">
  html { height: 100% }
  body { height: 100%; margin: 0; padding: 0 }
  #map { width: <?php echo $width;?>;height: <?php echo $height;?>; }
</style>
<script type="text/javascript"
  src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAP_KEY; ?>&sensor=false">
</script>

<script type="text/javascript">
//创建Google地图
var map, mark, geocoder, infowindow, gsearchHide = true;
function createmap(point,zoomsize,drag)
{
	var mapOptions = {
		width:<?php echo intval($_INPUT['width']) ? $_INPUT['width'] : 600; ?>,
		height:<?php echo intval($_INPUT['height']) ? $_INPUT['height'] : 400; ?>,
		center: point,
		zoom: zoomsize,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	map = new google.maps.Map(document.getElementById("map"),mapOptions);
    geocoder = new google.maps.Geocoder();

	geocoder.geocode({'latLng': point}, function(results, status) {
	  if (status == google.maps.GeocoderStatus.OK) {
		if (results[1]) {
		  infowindow.setContent(results[1].formatted_address);
		  if (!drag)
		  {
			  //infowindow.open(map, marker);
		  }
		}
	  }
	});
	google.maps.event.addListener(map, 'center_changed', function() {
		//if (gsearchHide)
		{
			document.getElementById('search').style.display = 'block';
		}
	});
}

function hg_createrMark(point, drag)
{
	if (drag)
	{
		drag = true;
		var title = '拖动标记地点位置';
	}
	else
	{
		drag = false;
		var title = '';
	}
	marker = new google.maps.Marker({
		position: point,
		map: map,
		draggable: drag,
		title: title
	});
	infowindow = new google.maps.InfoWindow(
	{ 
		content: point.lat() + 'x' + point.lng(),
		size: new google.maps.Size(60,60)
	});
	google.maps.event.addListener(marker, 'click', function() {
		infowindow.open(map,marker);
	});
	google.maps.event.addListener(marker, 'dragstart', function(event) {
		infowindow.close();
	});
	google.maps.event.addListener(marker, 'dragend', function(event) {
		var latlng = event.latLng;
		
		geocoder.geocode({'latLng': latlng}, function(results, status) {
		  if (status == google.maps.GeocoderStatus.OK) {
			if (results[1]) {
			  infowindow.setContent(results[1].formatted_address);
			  //infowindow.open(map, marker);
			  <?php
			  if ($domobj_id)
			  {
				?>
					try
					{
						var obj = parent.document.getElementById('<?php echo $domobj_id; ?>');
						try
						{
							obj.value = results[1].formatted_address;	
							obj.innerHTML = results[1].formatted_address;	
						}
						catch (e)
						{
							obj.innerHTML = results[1].formatted_address;	
						}
					}
					catch (e)
					{
					}
				<?php
			  }
			  ?>
			}
		  } else {
			//alert("Geocoder failed due to: " + status);
		  }
		});
		document.getElementById("lat_lng").value =  latlng.lat() + 'x'+latlng.lng();			 
		try {
			parent.syscPoint( latlng.lng() + 'x'+latlng.lat() );
		 } catch (e) {
		}
	});
}

function showAddress(address,drag)
{
    geocoder.geocode( { 'address': address}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        map.setCenter(results[0].geometry.location);
		hg_createrMark(results[0].geometry.location, drag);
      }
	  else 
		{
        //alert("Geocode was not successful for the following reason: " + status);
      }
    });
}
function hg_backCenter()
{
	var lnglat = marker.getPosition();
    map.setCenter(lnglat);
}
function hg_hide_search()
{
	gsearchHide = gsearchHide ? false : true;
	document.getElementById('search').style.display='none';
}

function init()
 {
	var zoomsize= parseInt(' <?php echo intval($zoomsize); ?>',10);
	<?php 
		if ($drag)
		{	
	?>
	var drag = true;
	<?php 
		}else {
	?>
	var drag = false;
	<?php
	}
	if ($latlng)
	{
	?>
		var latlng =' <?php echo trim($latlng); ?>';
		var lat_lng = latlng.split('x');
		
		var point = new google.maps.LatLng(lat_lng[0], lat_lng[1]);
		createmap(point, zoomsize,drag);
		hg_createrMark(point, drag);
	<?php
	}
	else
	{
		?>
		var default_lat = '<?php echo $default_lat;?>';
    	default_lat = default_lat.split('x');
		var point = new google.maps.LatLng(default_lat[0], default_lat[1]);
    	createmap(point,zoomsize,drag);
		<?php
		if ($name)
		{
		?>
			showAddress('<?php echo $name;?>', drag);
		<?php
		}
		else
		{
		?>
			hg_createrMark(point, drag);
		<?php
		}
	}
	?>

}

function fnGetInfo() {
var sData = dialogArguments;
sData.document.getElementById('<?php echo $domobj_id?>').value = lat_lng.value;
}
</script>
</head>
<body onload="init();">	
<?php 
	if ($drag)
	{	
?>
<div style="position:absolute;display:none;z-index:999" id="search"><input type="text" name="address" id="address" value="" /><input type="button" value="搜索" onclick="showAddress(document.getElementById('address').value, <?php echo $drag; ?>);" /><input type="button" name="cancel" value="取消" onclick="hg_hide_search();" /></div>
<?php
	}
?>
<div id="map" style="width: <?php echo ($_INPUT['width']-25).'px';?>; height: <?php echo ($_INPUT['height']-25).'px';?>"></div>
<div style="padding:4px;text-align:center;display:none">
	<input type="hidden" id="lat_lng" value=""  name="lat_lng" />
</div>
</body>
</html>
<?php
//print_r($_INPUT);
}
?>