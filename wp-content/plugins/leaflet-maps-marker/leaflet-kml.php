<?php
/*
    KML generator - Leaflet Maps Marker Plugin
*/
//info: construct path to wp-load.php
while(!is_file('wp-load.php')){
  if(is_dir('../')) chdir('../');
  else die('Error: Could not construct path to wp-load.php - please check <a href="http://mapsmarker.com/path-error">http://mapsmarker.com/path-error</a> for more details');
}
include( 'wp-load.php' );
function hide_email($email) { $character_set = '+-.0123456789@ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz'; $key = str_shuffle($character_set); $cipher_text = ''; $id = 'e'.rand(1,999999999); for ($i=0;$i<strlen($email);$i+=1) $cipher_text.= $key[strpos($character_set,$email[$i])]; $script = 'var a="'.$key.'";var b=a.split("").sort().join("");var c="'.$cipher_text.'";var d="";'; $script.= 'for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));'; $script.= 'document.getElementById("'.$id.'").innerHTML="<a href=\\"mailto:"+d+"\\">"+d+"</a>"'; $script = "eval(\"".str_replace(array("\\",'"'),array("\\\\",'\"'), $script)."\")"; $script = '<script type="text/javascript">/*<![CDATA[*/'.$script.'/*]]>*/</script>'; return '<span id="'.$id.'">[javascript protected email address]</span>'.$script; }
//info: check if plugin is active (didnt use is_plugin_active() due to problems reported by users)
function lmm_is_plugin_active( $plugin ) {
	return in_array( $plugin, (array) get_option( 'active_plugins', array() ) ) || lmm_is_plugin_active_for_network( $plugin );
}
function lmm_is_plugin_active_for_network( $plugin ) {
	if ( !is_multisite() )
		return false;
	$plugins = get_site_option( 'active_sitewide_plugins');
	if ( isset($plugins[$plugin]) )
				return true;
	return false;
}
if (!lmm_is_plugin_active('leaflet-maps-marker/leaflet-maps-marker.php') ) {
	echo 'The WordPress plugin <a href="http://www.mapsmarker.com" target="_blank">Leaflet Maps Marker</a> is inactive on this site and therefore this API link is not working.<br/><br/>Please contact the site owner (' . hide_email(get_bloginfo('admin_email')) . ') who can activate this plugin again.';
} else {
global $wpdb;
$table_name_markers = $wpdb->prefix.'leafletmapsmarker_markers';
$table_name_layers = $wpdb->prefix.'leafletmapsmarker_layers';
$lmm_options = get_option( 'leafletmapsmarker_options' );
if ($lmm_options[ 'wms_wms_kml_support' ] == 'yes') { $wms_kml_output = '<NetworkLink id="mapsmarker_wms1"><name><![CDATA[' . $lmm_options[ 'wms_wms_name' ] . ']]></name><visibility>1</visibility><open>0</open><atom:author><![CDATA[' . $lmm_options[ 'wms_wms_attribution' ] . ']]></atom:author><Snippet maxLines="2"><![CDATA[' . $lmm_options[ 'wms_wms_attribution' ] . ']]></Snippet><Link><href><![CDATA[' . $lmm_options[ 'wms_wms_kml_href' ] . ']]></href><refreshMode>' . $lmm_options[ 'wms_wms_kml_refreshMode' ] . '</refreshMode><refreshInterval>' . $lmm_options[ 'wms_wms_kml_refreshInterval' ] . '</refreshInterval><viewRefreshMode>' . $lmm_options[ 'wms_wms_kml_viewRefreshMode' ] . '</viewRefreshMode><viewRefreshTime>' . $lmm_options[ 'wms_wms_kml_viewRefreshTime' ] . '</viewRefreshTime></Link></NetworkLink>'; };
if ($lmm_options[ 'wms_wms2_kml_support' ] == 'yes') { $wms2_kml_output = '<NetworkLink id="mapsmarker_wms2"><name><![CDATA[' . $lmm_options[ 'wms_wms2_name' ] . ']]></name><visibility>1</visibility><open>0</open><atom:author><![CDATA[' . $lmm_options[ 'wms_wms2_attribution' ] . ']]></atom:author><Snippet maxLines="2"><![CDATA[' . $lmm_options[ 'wms_wms2_attribution' ] . ']]></Snippet><Link><href><![CDATA[' . $lmm_options[ 'wms_wms2_kml_href' ] . ']]></href><refreshMode>' . $lmm_options[ 'wms_wms2_kml_refreshMode' ] . '</refreshMode><refreshInterval>' . $lmm_options[ 'wms_wms2_kml_refreshInterval' ] . '</refreshInterval><viewRefreshMode>' . $lmm_options[ 'wms_wms2_kml_viewRefreshMode' ] . '</viewRefreshMode><viewRefreshTime>' . $lmm_options[ 'wms_wms2_kml_viewRefreshTime' ] . '</viewRefreshTime></Link></NetworkLink>'; };
if ($lmm_options[ 'wms_wms3_kml_support' ] == 'yes') { $wms3_kml_output = '<NetworkLink id="mapsmarker_wms3"><name><![CDATA[' . $lmm_options[ 'wms_wms3_name' ] . ']]></name><visibility>1</visibility><open>0</open><atom:author><![CDATA[' . $lmm_options[ 'wms_wms3_attribution' ] . ']]></atom:author><Snippet maxLines="2"><![CDATA[' . $lmm_options[ 'wms_wms3_attribution' ] . ']]></Snippet><Link><href><![CDATA[' . $lmm_options[ 'wms_wms3_kml_href' ] . ']]></href><refreshMode>' . $lmm_options[ 'wms_wms3_kml_refreshMode' ] . '</refreshMode><refreshInterval>' . $lmm_options[ 'wms_wms3_kml_refreshInterval' ] . '</refreshInterval><viewRefreshMode>' . $lmm_options[ 'wms_wms3_kml_viewRefreshMode' ] . '</viewRefreshMode><viewRefreshTime>' . $lmm_options[ 'wms_wms3_kml_viewRefreshTime' ] . '</viewRefreshTime></Link></NetworkLink>'; };
if ($lmm_options[ 'wms_wms4_kml_support' ] == 'yes') { $wms4_kml_output = '<NetworkLink id="mapsmarker_wms4"><name><![CDATA[' . $lmm_options[ 'wms_wms4_name' ] . ']]></name><visibility>1</visibility><open>0</open><atom:author><![CDATA[' . $lmm_options[ 'wms_wms4_attribution' ] . ']]></atom:author><Snippet maxLines="2"><![CDATA[' . $lmm_options[ 'wms_wms4_attribution' ] . ']]></Snippet><Link><href><![CDATA[' . $lmm_options[ 'wms_wms4_kml_href' ] . ']]></href><refreshMode>' . $lmm_options[ 'wms_wms4_kml_refreshMode' ] . '</refreshMode><refreshInterval>' . $lmm_options[ 'wms_wms4_kml_refreshInterval' ] . '</refreshInterval><viewRefreshMode>' . $lmm_options[ 'wms_wms4_kml_viewRefreshMode' ] . '</viewRefreshMode><viewRefreshTime>' . $lmm_options[ 'wms_wms4_kml_viewRefreshTime' ] . '</viewRefreshTime></Link></NetworkLink>'; };
if ($lmm_options[ 'wms_wms5_kml_support' ] == 'yes') { $wms5_kml_output = '<NetworkLink id="mapsmarker_wms5"><name><![CDATA[' . $lmm_options[ 'wms_wms5_name' ] . ']]></name><visibility>1</visibility><open>0</open><atom:author><![CDATA[' . $lmm_options[ 'wms_wms5_attribution' ] . ']]></atom:author><Snippet maxLines="2"><![CDATA[' . $lmm_options[ 'wms_wms5_attribution' ] . ']]></Snippet><Link><href><![CDATA[' . $lmm_options[ 'wms_wms5_kml_href' ] . ']]></href><refreshMode>' . $lmm_options[ 'wms_wms5_kml_refreshMode' ] . '</refreshMode><refreshInterval>' . $lmm_options[ 'wms_wms5_kml_refreshInterval' ] . '</refreshInterval><viewRefreshMode>' . $lmm_options[ 'wms_wms5_kml_viewRefreshMode' ] . '</viewRefreshMode><viewRefreshTime>' . $lmm_options[ 'wms_wms5_kml_viewRefreshTime' ] . '</viewRefreshTime></Link></NetworkLink>'; };
if ($lmm_options[ 'wms_wms6_kml_support' ] == 'yes') { $wms6_kml_output = '<NetworkLink id="mapsmarker_wms6"><name><![CDATA[' . $lmm_options[ 'wms_wms6_name' ] . ']]></name><visibility>1</visibility><open>0</open><atom:author><![CDATA[' . $lmm_options[ 'wms_wms6_attribution' ] . ']]></atom:author><Snippet maxLines="2"><![CDATA[' . $lmm_options[ 'wms_wms6_attribution' ] . ']]></Snippet><Link><href><![CDATA[' . $lmm_options[ 'wms_wms6_kml_href' ] . ']]></href><refreshMode>' . $lmm_options[ 'wms_wms6_kml_refreshMode' ] . '</refreshMode><refreshInterval>' . $lmm_options[ 'wms_wms6_kml_refreshInterval' ] . '</refreshInterval><viewRefreshMode>' . $lmm_options[ 'wms_wms6_kml_viewRefreshMode' ] . '</viewRefreshMode><viewRefreshTime>' . $lmm_options[ 'wms_wms6_kml_viewRefreshTime' ] . '</viewRefreshTime></Link></NetworkLink>'; };
if ($lmm_options[ 'wms_wms7_kml_support' ] == 'yes') { $wms7_kml_output = '<NetworkLink id="mapsmarker_wms7"><name><![CDATA[' . $lmm_options[ 'wms_wms7_name' ] . ']]></name><visibility>1</visibility><open>0</open><atom:author><![CDATA[' . $lmm_options[ 'wms_wms7_attribution' ] . ']]></atom:author><Snippet maxLines="2"><![CDATA[' . $lmm_options[ 'wms_wms7_attribution' ] . ']]></Snippet><Link><href><![CDATA[' . $lmm_options[ 'wms_wms7_kml_href' ] . ']]></href><refreshMode>' . $lmm_options[ 'wms_wms7_kml_refreshMode' ] . '</refreshMode><refreshInterval>' . $lmm_options[ 'wms_wms7_kml_refreshInterval' ] . '</refreshInterval><viewRefreshMode>' . $lmm_options[ 'wms_wms7_kml_viewRefreshMode' ] . '</viewRefreshMode><viewRefreshTime>' . $lmm_options[ 'wms_wms7_kml_viewRefreshTime' ] . '</viewRefreshTime></Link></NetworkLink>'; };
if ($lmm_options[ 'wms_wms8_kml_support' ] == 'yes') { $wms8_kml_output = '<NetworkLink id="mapsmarker_wms8"><name><![CDATA[' . $lmm_options[ 'wms_wms8_name' ] . ']]></name><visibility>1</visibility><open>0</open><atom:author><![CDATA[' . $lmm_options[ 'wms_wms8_attribution' ] . ']]></atom:author><Snippet maxLines="2"><![CDATA[' . $lmm_options[ 'wms_wms8_attribution' ] . ']]></Snippet><Link><href><![CDATA[' . $lmm_options[ 'wms_wms8_kml_href' ] . ']]></href><refreshMode>' . $lmm_options[ 'wms_wms8_kml_refreshMode' ] . '</refreshMode><refreshInterval>' . $lmm_options[ 'wms_wms8_kml_refreshInterval' ] . '</refreshInterval><viewRefreshMode>' . $lmm_options[ 'wms_wms8_kml_viewRefreshMode' ] . '</viewRefreshMode><viewRefreshTime>' . $lmm_options[ 'wms_wms8_kml_viewRefreshTime' ] . '</viewRefreshTime></Link></NetworkLink>'; };
if ($lmm_options[ 'wms_wms9_kml_support' ] == 'yes') { $wms9_kml_output = '<NetworkLink id="mapsmarker_wms9"><name><![CDATA[' . $lmm_options[ 'wms_wms9_name' ] . ']]></name><visibility>1</visibility><open>0</open><atom:author><![CDATA[' . $lmm_options[ 'wms_wms9_attribution' ] . ']]></atom:author><Snippet maxLines="2"><![CDATA[' . $lmm_options[ 'wms_wms9_attribution' ] . ']]></Snippet><Link><href><![CDATA[' . $lmm_options[ 'wms_wms9_kml_href' ] . ']]></href><refreshMode>' . $lmm_options[ 'wms_wms9_kml_refreshMode' ] . '</refreshMode><refreshInterval>' . $lmm_options[ 'wms_wms9_kml_refreshInterval' ] . '</refreshInterval><viewRefreshMode>' . $lmm_options[ 'wms_wms9_kml_viewRefreshMode' ] . '</viewRefreshMode><viewRefreshTime>' . $lmm_options[ 'wms_wms9_kml_viewRefreshTime' ] . '</viewRefreshTime></Link></NetworkLink>'; };
if ($lmm_options[ 'wms_wms10_kml_support' ] == 'yes') { $wms10_kml_output = '<NetworkLink id="mapsmarker_wms10"><name><![CDATA[' . $lmm_options[ 'wms_wms10_name' ] . ']]></name><visibility>1</visibility><open>0</open><atom:author><![CDATA[' . $lmm_options[ 'wms_wms10_attribution' ] . ']]></atom:author><Snippet maxLines="2"><![CDATA[' . $lmm_options[ 'wms_wms10_attribution' ] . ']]></Snippet><Link><href><![CDATA[' . $lmm_options[ 'wms_wms10_kml_href' ] . ']]></href><refreshMode>' . $lmm_options[ 'wms_wms10_kml_refreshMode' ] . '</refreshMode><refreshInterval>' . $lmm_options[ 'wms_wms10_kml_refreshInterval' ] . '</refreshInterval><viewRefreshMode>' . $lmm_options[ 'wms_wms10_kml_viewRefreshMode' ] . '</viewRefreshMode><viewRefreshTime>' . $lmm_options[ 'wms_wms10_kml_viewRefreshTime' ] . '</viewRefreshTime></Link></NetworkLink>'; };
  
if (isset($_GET['layer'])) {
  $layer = mysql_real_escape_string($_GET['layer']);
  
  $q = ''; //info: removed limit 5000
  if ($layer == '*' or $layer == 'all')
    $q = ''; //info: removed limit 5000
  else {
	$mlm_layers = explode(',', $layer);
	  $mlm_checkedlayers = array();
	  foreach ($mlm_layers as $mlm_clayer) {
	    if (intval($mlm_clayer) > 0)
	      $mlm_checkedlayers[] = intval($mlm_clayer);
	  }
    if (count($mlm_checkedlayers) > 0)
	    $mlm_q = 'WHERE id IN ('.implode(',', $mlm_checkedlayers).')';
    $sql_mlm_check = 'SELECT multi_layer_map FROM '.$table_name_layers.' '.$mlm_q;
    $sql_mlm_check_list = 'SELECT multi_layer_map_list FROM '.$table_name_layers.' '.$mlm_q;
    $mlm_check = $wpdb->get_var($sql_mlm_check);
    $mlm_check_list = $wpdb->get_row($sql_mlm_check_list, ARRAY_A);
    if ($mlm_check == 0) {
	    $layers = explode(',', $layer);
	    $checkedlayers = array();
	    foreach ($layers as $clayer) {
	      if (intval($clayer) > 0)
	        $checkedlayers[] = intval($clayer);
	    }
	    if (count($checkedlayers) > 0)
	      $q = 'WHERE layer IN ('.implode(',', $checkedlayers).')';
    } else if ( ($mlm_check == 1) && (!in_array('all',$mlm_check_list) ) ){
	      $q = 'WHERE layer IN ('.implode(',', $mlm_check_list).')';
    } else if ( ($mlm_check == 1) && (in_array('all',$mlm_check_list) ) ){
	      $q = ''; //info: removed limit 5000
    }
  }
  $sql = 'SELECT m.id as mid, m.markername as mmarkername, m.layer as mlayer, m.icon as micon, m.createdby as mcreatedby, m.createdon as mcreatedon, m.lat as mlat, m.lon as mlon, m.popuptext as mpopuptext, m.kml_timestamp as mkml_timestamp, l.createdby as lcreatedby, l.createdon as lcreatedon, l.name as lname, l.wms as lwms, l.wms2 as lwms2, l.wms3 as lwms3, l.wms4 as lwms4, l.wms5 as lwms5, l.wms6 as lwms6, l.wms7 as lwms7, l.wms8 as lwms8, l.wms9 as lwms9, l.wms10 as lwms10 FROM '.$table_name_markers.' AS m INNER JOIN '.$table_name_layers.' AS l ON m.layer=l.id '.$q;
  $markers = $wpdb->get_results($sql, ARRAY_A);
  $sql_distinct = 'SELECT DISTINCT m.icon as micon FROM '.$table_name_markers.' AS m INNER JOIN '.$table_name_layers.' AS l ON m.layer=l.id '.$q;
  $styles_distinct = $wpdb->get_results($sql_distinct, ARRAY_A);
  	if ($_GET['layer'] != 'all') {
	  $sql_wms_layer_for_kml = 'SELECT l.id as lid, l.wms as lwms, l.wms2 as lwms2, l.wms3 as lwms3, l.wms4 as lwms4, l.wms5 as lwms5, l.wms6 as lwms6, l.wms7 as lwms7, l.wms8 as lwms8, l.wms9 as lwms9, l.wms10 as lwms10 FROM '.$table_name_layers.' AS l '.$mlm_q;
	  $wmslayer_kml = $wpdb->get_results($sql_wms_layer_for_kml, ARRAY_A);
	}
  //info: check if layer result is not null
  if (empty($markers)) {
  $error_layers_not_exists = sprintf( esc_attr__('Warning: no markers are assigned to the layer with the ID %1$s or the layer does not exist!','lmm'), $layer); 
  echo $error_layers_not_exists;
  } else {
  header('Cache-Control: no-cache, must-revalidate');
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
  header('Content-type: application/vnd.google-earth.kml+xml; charset=utf-8'); 
  header('Content-Disposition: attachment; filename="' .   preg_replace(array('/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'), array('_', '.', ''), get_bloginfo('name')) . '-layer-' . intval($_GET['layer']) . '.kml"');
  echo '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
  echo '<kml xmlns="http://www.opengis.net/kml/2.2" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:gx="http://www.google.com/kml/ext/2.2" xmlns:kml="http://www.opengis.net/kml/2.2">'.PHP_EOL;
  echo '<Document>'.PHP_EOL;
  echo '<description><![CDATA[powered by <a href="http://www.wordpress.org">WordPress</a> &amp; <a href="http://www.mapsmarker.com">MapsMarker.com</a>]]></description>'.PHP_EOL;    
  echo '<open>1</open>'.PHP_EOL;  
  foreach ($styles_distinct as $marker_icon) {
    if ($marker_icon['micon'] == null) {
        $micon_url = LEAFLET_PLUGIN_URL . 'leaflet-dist/images/marker.png';  
		$micon_name = 'default';
    } else {
        $micon_url = LEAFLET_PLUGIN_ICONS_URL . '/' . $marker_icon['micon']; 
		$micon_name = substr($marker_icon['micon'],0,-4);		
    }
	echo '<Style id="' . $micon_name . '"><IconStyle><Icon><href>' . $micon_url . '</href></Icon></IconStyle></Style>'.PHP_EOL;
  }
  
  $layername = $wpdb->get_var('SELECT name FROM '.$table_name_layers.' WHERE id = '.intval($_GET['layer']).'');
	if ($_GET['layer'] != 'all') {
	  echo '<Folder>'.PHP_EOL;
	  echo '<name>' . $layername . '</name>'.PHP_EOL;
	}
	
  foreach ($markers as $marker) {
    if ( isset($_GET['name']) && ($_GET['name'] == 'show') ) {
	$name = stripslashes($marker['mmarkername']);
	$name_popup = '';
    } else if ( isset($_GET['name']) && ($_GET['name'] == 'hide') ) {
	$name = '';
	$name_popup = '';
    } else if ( isset($_GET['name']) && ($_GET['name'] == 'popup') ) {
	$name = '';
	$name_popup = '<strong>' . stripslashes($marker['mmarkername']) . '</strong><br/><br/>';
    } else {
	$name = stripslashes($marker['mmarkername']);
	$name_popup = '';
    }
    if ($marker['micon'] == NULL) {
		$micon_name = 'default';
    } else {
		$micon_name = substr($marker['micon'],0,-4);		
    }
	if ($marker['mkml_timestamp'] == NULL) { 
		$date_kml =  strtotime($marker['mcreatedon']);
		$time_kml =  strtotime($marker['mcreatedon']);
	} else {
		$date_kml =  strtotime($marker['mkml_timestamp']);
		$time_kml =  strtotime($marker['mkml_timestamp']);
	}
	$offset_kml = date('H:i',abs(get_option('gmt_offset')*3600));
	if (get_option('gmt_offset') >= 0) { $plus_minus = '+'; } else { $plus_minus = '-'; };
	echo '<Placemark id="marker-' . $marker['mid'] . '">'.PHP_EOL;
	//info: google maps has problems displaying custom icons in ff - get parameter default_icons displays standard icons
	if (!isset($_GET['default_icons'])) {
	echo '<styleUrl>#' . $micon_name . '</styleUrl>'.PHP_EOL;
	}
	echo '<name>' . $name . '</name>'.PHP_EOL;
	echo '<TimeStamp><when>' . date("Y-m-d", $date_kml) . 'T' . date("h:m:s", $time_kml) . $plus_minus . $offset_kml . '</when></TimeStamp>'.PHP_EOL;
	echo '<atom:author>' . $marker['mcreatedby'] . '</atom:author>'.PHP_EOL;
	echo '<description><![CDATA[' .  $name_popup . stripslashes(preg_replace('/(\015\012)|(\015)|(\012)/','<br/>',$marker['mpopuptext'])) . ']]></description>'.PHP_EOL;
	echo '<Point>'.PHP_EOL;
	echo '<coordinates>' . $marker['mlon'] . ',' . $marker['mlat'] . '</coordinates>'.PHP_EOL;
	echo '</Point>'.PHP_EOL;
	echo '</Placemark>'.PHP_EOL;
  }
  
  	if ($_GET['layer'] != 'all') {
	  echo '</Folder>';
	}
	//info: output wms layer for kml-file
  	if ($_GET['layer'] != 'all') {
		foreach ($wmslayer_kml as $layer) {
			if ( ($lmm_options[ 'wms_wms_kml_support' ] == 'yes') && ($layer['lwms'] == '1') ) { echo $wms_kml_output; }
			if ( ($lmm_options[ 'wms_wms2_kml_support' ] == 'yes') && ($layer['lwms2'] == '1') ) { echo $wms2_kml_output; }
			if ( ($lmm_options[ 'wms_wms3_kml_support' ] == 'yes') && ($layer['lwms3'] == '1') ) { echo $wms3_kml_output; }
			if ( ($lmm_options[ 'wms_wms4_kml_support' ] == 'yes') && ($layer['lwms4'] == '1') ) { echo $wms4_kml_output; }
			if ( ($lmm_options[ 'wms_wms5_kml_support' ] == 'yes') && ($layer['lwms5'] == '1') ) { echo $wms5_kml_output; }
			if ( ($lmm_options[ 'wms_wms6_kml_support' ] == 'yes') && ($layer['lwms6'] == '1') ) { echo $wms6_kml_output; }
			if ( ($lmm_options[ 'wms_wms7_kml_support' ] == 'yes') && ($layer['lwms7'] == '1') ) { echo $wms7_kml_output; }
			if ( ($lmm_options[ 'wms_wms8_kml_support' ] == 'yes') && ($layer['lwms8'] == '1') ) { echo $wms8_kml_output; }
			if ( ($lmm_options[ 'wms_wms9_kml_support' ] == 'yes') && ($layer['lwms9'] == '1') ) { echo $wms9_kml_output; }
			if ( ($lmm_options[ 'wms_wms10_kml_support' ] == 'yes') && ($layer['lwms10'] == '1') ) { echo $wms10_kml_output; }
		}	
	}
  echo PHP_EOL . '<ScreenOverlay>'.PHP_EOL;
  echo '<name><![CDATA[powered by WordPress & MapsMarker.com]]></name>'.PHP_EOL;
  echo '<Icon>'.PHP_EOL;
  echo '<href>' . LEAFLET_PLUGIN_URL . 'img/kml-overlay-powered-by.png</href>'.PHP_EOL;
  echo '</Icon>'.PHP_EOL;
  echo '<overlayXY x="0" y="1" xunits="fraction" yunits="fraction"/>'.PHP_EOL;
  echo '<screenXY x="0" y="1" xunits="fraction" yunits="fraction"/>'.PHP_EOL;
  echo '<rotationXY x="0" y="0" xunits="fraction" yunits="fraction"/>'.PHP_EOL;
  echo '<size x="0" y="0" xunits="fraction" yunits="fraction"/>'.PHP_EOL;
  echo '</ScreenOverlay>'.PHP_EOL;
  echo '</Document>'.PHP_EOL;
  echo '</kml>';
  } //info: check if layer exists end
  }
elseif (isset($_GET['marker'])) {
  $markerid = mysql_real_escape_string($_GET['marker']);
  $markers = explode(',', $markerid);
  $checkedmarkers = array();
  foreach ($markers as $cmarker) {
    if (intval($cmarker) > 0)
      $checkedmarkers[] = intval($cmarker);
  }
  if (count($checkedmarkers) > 0)
    $q = 'WHERE m.id IN ('.implode(',', $checkedmarkers).')';
  else
    die();
  //info: added left outer join to also show markers without a layer
  $sql = 'SELECT m.layer as mlayer,m.icon as micon,m.popuptext as mpopuptext,m.id as mid,m.markername as mmarkername,m.createdby as mcreatedby, m.createdon as mcreatedon, m.wms as mwms, m.wms2 as mwms2, m.wms3 as mwms3, m.wms4 as mwms4, m.wms5 as mwms5, m.wms6 as mwms6, m.wms7 as mwms7, m.wms8 as mwms8, m.wms9 as mwms9, m.wms10 as mwms10, m.lat as mlat, m.lon as mlon, m.kml_timestamp as mkml_timestamp FROM '.$table_name_markers.' AS m LEFT OUTER JOIN '.$table_name_layers.' AS l ON m.layer=l.id '.$q;
  $markers = $wpdb->get_results($sql, ARRAY_A);
  $sql_distinct = 'SELECT DISTINCT m.icon as micon FROM '.$table_name_markers.' AS m INNER JOIN '.$table_name_layers.' AS l ON m.layer=l.id '.$q;
  $styles_distinct = $wpdb->get_results($sql_distinct, ARRAY_A);
  $sql_wms_layer_for_kml = 'SELECT m.id as mid, m.wms as mwms, m.wms2 as mwms2, m.wms3 as mwms3, m.wms4 as mwms4, m.wms5 as mwms5, m.wms6 as mwms6, m.wms7 as mwms7, m.wms8 as mwms8, m.wms9 as mwms9, m.wms10 as mwms10 FROM '.$table_name_markers.' AS m '.$q;
  $wmslayer_kml = $wpdb->get_results($sql_wms_layer_for_kml, ARRAY_A);
  //info: check if marker result is not null
  if ($markers == NULL) {
  $error_marker_not_exists = sprintf( esc_attr__('Error: a marker with the ID %1$s does not exist!','lmm'), $markerid); 
  echo $error_marker_not_exists;
  } else {
  header('Cache-Control: no-cache, must-revalidate');
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
  header('Content-type: application/vnd.google-earth.kml+xml; charset=utf-8'); 
  header('Content-Disposition: attachment; filename="' .   preg_replace(array('/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'), array('_', '.', ''), get_bloginfo('name')) . '-marker-' . intval($_GET['marker']) . '.kml"');
  echo '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
  echo '<kml xmlns="http://www.opengis.net/kml/2.2" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:gx="http://www.google.com/kml/ext/2.2" xmlns:kml="http://www.opengis.net/kml/2.2">'.PHP_EOL;
  echo '<Document>'.PHP_EOL;
  echo '<description><![CDATA[powered by <a href="http://www.wordpress.org">WordPress</a> &amp; <a href="http://www.mapsmarker.com">MapsMarker.com</a>]]></description>'.PHP_EOL;    
  echo '<open>0</open>'.PHP_EOL;  
  foreach ($styles_distinct as $marker_icon) {
    if ($marker_icon['micon'] == null) {
        $micon_url = LEAFLET_PLUGIN_URL . 'leaflet-dist/images/marker.png';  
		$micon_name = 'default';
    } else {
        $micon_url = LEAFLET_PLUGIN_ICONS_URL . '/' . $marker_icon['micon']; 
		$micon_name = substr($marker_icon['micon'],0,-4);		
    }
	echo '<Style id="' . $micon_name . '"><IconStyle><Icon><href>' . $micon_url . '</href></Icon></IconStyle></Style>'.PHP_EOL;
  }
  
  echo '<name>' . get_bloginfo('name') . '</name>'.PHP_EOL;  
  foreach ($markers as $marker) {
	if ( isset($_GET['name']) && ($_GET['name'] == 'show') ) {
		$name = stripslashes($marker['mmarkername']);
		$name_popup = '';
	} else if ( isset($_GET['name']) && ($_GET['name'] == 'hide') ) {
		$name = '';
		$name_popup = '';
	} else if ( isset($_GET['name']) && ($_GET['name'] == 'popup') ) {
		$name = '';
		$name_popup = '<strong>' . stripslashes($marker['mmarkername']) . '</strong><br/><br/>';
	} else {
		$name = stripslashes($marker['mmarkername']);
		$name_popup = '';
	}
	if ($marker['micon'] == null) {
		$micon_name = 'default';
	} else {
		$micon_name = substr($marker['micon'],0,-4);		
	}
	if ($marker['mkml_timestamp'] == NULL) { 
		$date_kml =  strtotime($marker['mcreatedon']);
		$time_kml =  strtotime($marker['mcreatedon']);
	} else {
		$date_kml =  strtotime($marker['mkml_timestamp']);
		$time_kml =  strtotime($marker['mkml_timestamp']);
	}
	$offset_kml = date('H:i',abs(get_option('gmt_offset')*3600));
	if (get_option('gmt_offset') >= 0) { $plus_minus = '+'; } else { $plus_minus = '-'; };
	echo '<Placemark id="marker-' . $marker['mid'] . '">'.PHP_EOL;
	//info: google maps has problems displaying custom icons in ff - get parameter default_icons displays standard icons
	if (!isset($_GET['default_icons'])) {
	echo '<styleUrl>#' . $micon_name . '</styleUrl>'.PHP_EOL;
	}
	echo '<name>' . $name . '</name>'.PHP_EOL;
	echo '<TimeStamp><when>' . date("Y-m-d", $date_kml) . 'T' . date("h:m:s", $time_kml) . $plus_minus . $offset_kml . '</when></TimeStamp>'.PHP_EOL;
	echo '<atom:author>' . $marker['mcreatedby'] . '</atom:author>'.PHP_EOL;
	echo '<description><![CDATA[' .  $name_popup . stripslashes(preg_replace('/(\015\012)|(\015)|(\012)/','<br/>',$marker['mpopuptext'])) . ']]></description>'.PHP_EOL;
	echo '<Point>'.PHP_EOL;
	echo '<coordinates>' . $marker['mlon'] . ',' . $marker['mlat'] . '</coordinates>'.PHP_EOL;
	echo '</Point>'.PHP_EOL;
	echo '</Placemark>';
  }
  	//info: output wms layer for kml-file
	foreach ($wmslayer_kml as $layer) {
			if ( ($lmm_options[ 'wms_wms_kml_support' ] == 'yes') && ($layer['mwms'] == '1') ) { echo $wms_kml_output; }
			if ( ($lmm_options[ 'wms_wms2_kml_support' ] == 'yes') && ($layer['mwms2'] == '1') ) { echo $wms2_kml_output; }
			if ( ($lmm_options[ 'wms_wms3_kml_support' ] == 'yes') && ($layer['mwms3'] == '1') ) { echo $wms3_kml_output; }
			if ( ($lmm_options[ 'wms_wms4_kml_support' ] == 'yes') && ($layer['mwms4'] == '1') ) { echo $wms4_kml_output; }
			if ( ($lmm_options[ 'wms_wms5_kml_support' ] == 'yes') && ($layer['mwms5'] == '1') ) { echo $wms5_kml_output; }
			if ( ($lmm_options[ 'wms_wms6_kml_support' ] == 'yes') && ($layer['mwms6'] == '1') ) { echo $wms6_kml_output; }
			if ( ($lmm_options[ 'wms_wms7_kml_support' ] == 'yes') && ($layer['mwms7'] == '1') ) { echo $wms7_kml_output; }
			if ( ($lmm_options[ 'wms_wms8_kml_support' ] == 'yes') && ($layer['mwms8'] == '1') ) { echo $wms8_kml_output; }
			if ( ($lmm_options[ 'wms_wms9_kml_support' ] == 'yes') && ($layer['mwms9'] == '1') ) { echo $wms9_kml_output; }
			if ( ($lmm_options[ 'wms_wms10_kml_support' ] == 'yes') && ($layer['mwms10'] == '1') ) { echo $wms10_kml_output; }
	}  
  echo PHP_EOL.'<ScreenOverlay>'.PHP_EOL;
  echo '<name><![CDATA[powered by WordPress & MapsMarker.com]]></name>'.PHP_EOL;
  echo '<Icon>'.PHP_EOL;
  echo '<href>' . LEAFLET_PLUGIN_URL . 'img/kml-overlay-powered-by.png</href>'.PHP_EOL;
  echo '</Icon>'.PHP_EOL;
  echo '<overlayXY x="0" y="1" xunits="fraction" yunits="fraction"/>'.PHP_EOL;
  echo '<screenXY x="0" y="1" xunits="fraction" yunits="fraction"/>'.PHP_EOL;
  echo '<rotationXY x="0" y="0" xunits="fraction" yunits="fraction"/>'.PHP_EOL;
  echo '<size x="0" y="0" xunits="fraction" yunits="fraction"/>'.PHP_EOL;
  echo '</ScreenOverlay>'.PHP_EOL;
  echo '</Document>'.PHP_EOL;
  echo '</kml>';
  } //info: check if marker exists end
}
} //info: end plugin active check
?>