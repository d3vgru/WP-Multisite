<?php

function wpv_post_default_settings($view_settings) {

    if (!isset($view_settings['post_type'])) {
        $view_settings['post_type'] = array();
    }
    if (!isset($view_settings['post_type_dont_include_current_page'])) {
        $view_settings['post_type_dont_include_current_page'] = true;
    }

    return $view_settings;
}

add_filter('wpv_view_settings_save', 'wpv_post_types_defaults_save', 10, 1);
function wpv_post_types_defaults_save($view_settings) {

    // we need to set 0 for the checkboxes that aren't checked and are missing for the $_POST.
    
    $defaults = array('post_type_dont_include_current_page' => 0);
    $view_settings = wpv_parse_args_recursive($view_settings, $defaults);

    return $view_settings;
}

function wpv_get_post_filter_summary($view_settings) {
    
    $view_settings = wpv_post_default_settings($view_settings);
    $selected = $view_settings['post_type'];
    
    $post_types = get_post_types(array('public'=>true), 'objects');
    $selected_post_types = sizeof($selected);
    switch ($selected_post_types) {
        case 0:
            _e('This View selects <strong>ALL</strong> post types', 'wpv-view');
            break;
        
        case 1:
            echo sprintf(__('This View selects <strong>%s</strong>', 'wpv-view'), $post_types[$selected[0]]->labels->name);
            break;
        
        default:
            _e('This View selects ', 'wpv-view');
            for($i = 0; $i < $selected_post_types - 1; $i++) {
                if ($i != 0) {
                    echo ', ';
                }
                echo '<strong>' . $post_types[$selected[$i]]->labels->name . '</strong>';
            }
            _e(' and ', 'wpv-view');
            echo '<strong>' . $post_types[$selected[$i]]->labels->name . '</strong>';
            break;
        
    }
            
}

function wpv_post_types_checkboxes($view_settings) {
    $post_types = get_post_types(array('public'=>true), 'objects');
    
    // remove any post types that don't exist any more.
    foreach($view_settings['post_type'] as $type) {
        if (!isset($post_types[$type])) {
            unset($view_settings['post_type'][$type]);
        }
    }

    ?>
        <ul style="padding-left:30px;">
            <?php foreach($post_types as $p):?>
                <?php 
                    $checked = @in_array($p->name, $view_settings['post_type']) ? ' checked="checked"' : '';
                ?>
                <li><label><input type="checkbox" name="_wpv_settings[post_type][]" value="<?php echo $p->name ?>" <?php echo $checked ?> onclick="wpv_filter_vmenu_items();" />&nbsp;<?php echo $p->labels->name ?></label></li>
            <?php endforeach; ?>
        </ul>
    <?php
}

function wpv_post_types_settings($view_settings) {
    
    ?>
    <strong><?php echo __('Settings:', 'wpv-views'); ?></strong>
    <ul style="padding-left:30px;">
        <?php $checked = $view_settings['post_type_dont_include_current_page']  ? ' checked="checked"' : '';?>
        <li><label><input type="checkbox" name="_wpv_settings[post_type_dont_include_current_page]" value="1" <?php echo $checked ?> />&nbsp;<?php echo __('Don\'t include current page in query result', 'wpv-views'); ?></label></li>
    </ul>
    <?php
}
