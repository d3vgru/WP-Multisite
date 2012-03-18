<?php
/**
Hook into WordPress
*/
add_action('admin_print_styles-post.php', 'marker_select_box_css');
add_action('admin_print_styles-post-new.php', 'marker_select_box_css');

function marker_select_box_css() {
	wp_register_style( 'lmm-tinymce-css', LEAFLET_PLUGIN_URL . 'css/marker_select_box.css', array(), NULL );
	wp_enqueue_style( 'lmm-tinymce-css' );	
}
add_action('init', 'mm_shortcode_button');

/**
Create Our Initialization Function
*/
function mm_shortcode_button() {
   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
     return;
   }
   if ( get_user_option('rich_editing') == 'true' ) {
     add_filter( 'mce_external_plugins', 'add_plugin' );
     add_filter( 'mce_buttons', 'register_button' );
   }
}

/**
Register Button
*/
function register_button( $buttons ) {
	array_push( $buttons, "|", "mm_shortcode" );
	return $buttons;
}

/**
Register TinyMCE Plugin
*/
function add_plugin( $plugin_array ) {
	$plugin_array['mm_shortcode'] = plugins_url( 'js/lmm_tinymce_shortcode.js' , __FILE__ );
	return $plugin_array;
}
add_action('wp_ajax_get_mm_list',  'get_mm_list');

function get_mm_list(){
    global $wpdb;

    $table_name_markers = $wpdb->prefix.'leafletmapsmarker_markers';
    $table_name_layers = $wpdb->prefix.'leafletmapsmarker_layers';
    
    $l_condition = isset($_GET['q']) ? "AND l.name LIKE '%" . mysql_real_escape_string($_GET['q']) . "%'" : '';
    $m_condition = isset($_GET['q']) ? "AND m.markername LIKE '%" . mysql_real_escape_string($_GET['q']) . "%'" : '';
    
    $marklist = $wpdb->get_results("
            (SELECT l.id, l.name as 'name', l.createdon, 'layer' as 'type' FROM $table_name_layers as l WHERE l.id != '0' $l_condition)
            UNION
            (SELECT m.id, m.markername as 'name', m.createdon, 'marker' as 'type' FROM $table_name_markers as m WHERE  m.id != '0' $m_condition)
            order by createdon DESC LIMIT 15", ARRAY_A);

    if(isset($_GET['q']) ){
        buildMarkersList($marklist);
        exit();
    }
?>

<!DOCTYPE html>
<html>
<head>
	<title>Insert map</title>
        <script type='text/javascript' src='/wp-admin/load-scripts.php?c=1&load=jquery'></script>
        <script type='text/javascript' src='/wp-includes/js/tinymce/tiny_mce_popup.js'></script>
        <script type='text/javascript' src='<?php echo plugins_url( 'js/lmm_tinymce_shortcode.js' , __FILE__ ) ?>'></script>
        <link rel='stylesheet' href='<?php echo plugins_url( 'css/marker_select_box.css' , __FILE__ ) ?>' type='text/css' media='all' />
</head>
<body>
<span id="msb_header_description"><?php _e('If no search term is entered, the latest 15 maps will be shown.','lmm'); ?></span>
<div id="msb_serchContainer"><?php _e('Search','lmm'); ?> <input type="text" name="q" id="msb_serch"/></div>
<div id="msb_listContainer">
	<div id="msb_listHint" ><?php _e('Please select the map you would like to add','lmm'); ?></div>
	<?php buildMarkersList($marklist); ?>
</div>

<input class="button-primary" type="button" href="#" id="msb_insertMarkerSC" value="<?php esc_attr_e('Add shortcode','lmm'); ?>" />       

<a href="#" id="msb_cancel"><?php _e('Cancel','lmm'); ?></a>
<br/><br/>
<span id="msb_attribution">powered by <a href="http://www.mapsmarker.com" target"_blank">MapsMarker.com</a></span>


<script type="text/javascript">
(function($){
    var selectMarkerBox = {
        markerID : '',
        mapsmarkerType : '',
        
        init : function(){
            var self = selectMarkerBox;
            
            $('.map_marker').live('click', function(){
                e.preventDefault();
                console.log( $(this).text() );
            })
	        $('#msb_insertMarkerSC').live('click', function(e){
                e.preventDefault();
                self.insert();
                self.close();
            })
            $('#msb_cancel').live('click', function(e){
                e.preventDefault();
                self.close();
            })
            $('.list_item').live('click', function(e){
                e.preventDefault();
                var id = $(this).find('input[name="msb_id"]').val();
                var type = $(this).find('input[name="msb_type"]').val(); 
                $('.list_item.active').removeClass('active');
                $(this).addClass('active');
                self.setMarkerID(id)
                self.setMarkerType(type);
            })
            $('#msb_serch').live('keyup', function(){
                $.post('/wp-admin/admin-ajax.php?action=get_mm_list&q='+$(this).val(), function(data){
                        $('.list_item').remove();
                        $('#msb_listContainer').append(data);
                })
            })
        },        
        setMarkerID : function(id) {
            selectMarkerBox.markerID = id;
        },
        setMarkerType : function(type) {
            switch (type)
            {
                case 'layer': 
                    selectMarkerBox.mapsmarkerType = 'layer';
                    break;
                case 'marker': 
                    selectMarkerBox.mapsmarkerType = 'marker';
                    break;
            }
        },
        getShortCode : function(){
          return '[mapsmarker '+ selectMarkerBox.mapsmarkerType +'="'+ selectMarkerBox.markerID +'"]';  
        },
        insert : function() {
            tinyMCEPopup.editor.execCommand('mceInsertContent', false, selectMarkerBox.getShortCode());
        },
        
        insertMarker : function() {
            return;
        },
        
        insertList : function() {
            return;
        },
        
        close : function() {
            tinyMCEPopup.close();        
        }
        
    }
    selectMarkerBox.init();
})(jQuery)
</script>
</body>
</html>
<?php    
exit;
}
function buildMarkersList($array){
?>    
    <?php foreach($array as $one):
		$date_prepare = strtotime($one['createdon']);
		$date = date("Y/m/d", $date_prepare);
		if ($one['name'] == NULL) {
			$name = '(ID '. $one['id'].')';
		} else {
			$name = $one['name'] . ' (ID '. $one['id'].')';
		}
	?>
    <div class="list_item">
        <span class="name" title="<?php esc_attr_e('name and ID','lmm');?>"><?php echo $name; ?></span><span class="date" title="<?php esc_attr_e('created on','lmm');?>"><?php echo $date; ?></span>
        <input type="hidden" value="<?php echo $one['type']?>" name="msb_type">
        <input type="hidden" value="<?php echo $one['id']?>" name="msb_id">
    </div>
    <?php endforeach; ?>  
<?php
}
?>