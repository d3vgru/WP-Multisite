<?php

function wpv_apply_user_functions($value) {
    $value = wpv_apply_user_function_url_param($value);
    $value = wpv_apply_user_function_view_param($value);
    
    return $value;
}

function wpv_apply_user_function_url_param($value) {
    $pattern = '/URL_PARAM\((.*?)\)/siU';
    
    if(preg_match_all($pattern, $value, $matches, PREG_SET_ORDER)) {
        foreach($matches as $match) {
            if (isset($_GET[$match[1]])) {
                $url_param = $_GET[$match[1]];
            } else {
                $url_param = '';
            }
            $search = $match[0];
            $value = str_replace($search, $url_param, $value);
        }
        
    }
    
    return $value;
}

function wpv_apply_user_function_view_param($value) {
    global $WP_Views;
    
    $pattern = '/VIEW_PARAM\((.*?)\)/siU';
    
    if(preg_match_all($pattern, $value, $matches, PREG_SET_ORDER)) {
        foreach($matches as $match) {
            $view_attr = $WP_Views->get_view_shortcodes_attributes();
            
            if (isset($view_attr[$match[1]])) {
                $view_param = $view_attr[$match[1]];
            } else {
                $view_param = '';
            }
            $search = $match[0];
            $value = str_replace($search, $view_param, $value);
        }
        
    }
    
    return $value;
}
