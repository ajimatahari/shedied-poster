<?php

/**
  Plugin Name: SheDied Poster
  Plugin URI: http://www.shedied.xyz
  Description: Buat nyolong konten web orang.
  Version: 6.6.6
  Author: kelaskakap
  Author URI: http://www.shedied.xyz

 */
#composer in action
require_once 'vendor/autoload.php';

add_filter('plugin_row_meta', 'shedied_set_plugin_meta', 10, 2);
add_action('admin_menu', 'shedied_add_plugin_menu');
add_action('init', 'shedied_load_jquery_ui');
add_filter('wp_head', 'shedied_auto_post');

add_action('admin_print_scripts-toplevel_page_shedied-poster', 'shedied_action_javascript');
add_action('admin_print_styles-toplevel_page_shedied-poster', 'shedied_plugin_admin_styles');
add_action('wp_ajax_shedied_ajax', 'shedied_ajax');

function shedied_add_plugin_menu() {
    include("dashboard.php");
    add_menu_page('SheDied Poster', 'SheDied Poster', 'edit_posts', 'shedied-poster', 'shedied_my_panel');
}

function shedied_auto_post() {
    if (get_option("shedied_isAutopost") == "true") {
        $now_time = time();
        // run every 2 hours
        $interval = 2 * 60 * 60;
        $lastactive = get_option('lastactive');

        $_POST['date']['month'] = date("m");
        $_POST['date']['year'] = date("Y");
        $_POST['date']['day'] = date("d");
        $_POST['interval']['value'] = 1;
        $_POST['interval']['type'] = "hours";
        $_POST['bulk_post_status'] = "publish";
        $_POST['bulk_post_type'] = "post";
        $_POST['auto'] = true;


        //print_r($_POST);

        if (($now_time - $lastactive ) >= $interval) {
            // START Posting
            //if (1==1) {	
            $lastCampaign = get_option('lastCampaign');
            if ($lastCampaign == "" || !$lastCampaign) {
                $lastCampaign = 0;
            }
            $arrCampaign = shedied_load_campaign();
            $campaign = null;
            if ($arrCampaign[0] != "") {
                if ($lastCampaign >= count($arrCampaign)) {
                    $lastCampaign = 0;
                }
                $campaign = $arrCampaign[$lastCampaign];
                update_option('lastCampaign', $lastCampaign + 1);
            }
            if (isset($campaign) && $campaign != null) {
                $arr = explode(",", $campaign);
                $_POST['news_src'] = $arr[1];
                $_POST['category'] = $arr[2];
                $_POST['author'] = $arr[3];
                echo "$lastCampaign";
                update_option('lastactive', $now_time);
                include_once("dashboard.php");
                shedied_create_posts(5);
            }
        }
    }
}

function shedied_set_plugin_meta($links, $file) {
    $plugin = plugin_basename(__FILE__);

    // create link
    if ($file == $plugin) {
        return array_merge(
                $links, array(sprintf('<a href="edit.php?page=%s">%s</a>', $plugin, __('Settings')))
        );
        $settings_link = '<a href="options-general.php?page=custom-field-template.php">' . __('Settings') . '</a>';
        $links = array_merge(array($settings_link), $links);
    }
    return $links;
}

function shedied_load_jquery_ui() {
    global $wp_scripts;

    //  // tell WordPress to load jQuery UI tabs
    // wp_deregister_script('jquery'); 
//echo "load jquery ui";
    wp_register_script('kp_jquery', 'http://code.jquery.com/jquery-1.10.2.js', false, '1.10.2', false);
    wp_register_script('kp-jquery-ui', 'http://code.jquery.com/ui/1.11.4/jquery-ui.js', false, '1.11.4', false);
    wp_register_script('kp-main', plugins_url('main_news.js', __FILE__));
    wp_register_style('kp-bootstrap', plugins_url('overcast.css', __FILE__));
    wp_register_style('kp-style', plugins_url('style.css', __FILE__));
    // // get registered script object for jquery-ui
}

function shedied_plugin_admin_styles() {
    /*
     * It will be called only on your plugin admin page, enqueue our stylesheet here
     */
    wp_enqueue_style('kp-bootstrap');
    wp_enqueue_style('kp-style');
}

//add_action('wp_ajax_my_save_kw', 'save_kw');

function shedied_action_javascript() {
    wp_enqueue_script('kp_jquery');
    wp_enqueue_script('kp-main');
    wp_enqueue_script('kp-jquery-ui');
}

function shedied_load_campaign() {

    $campaign = get_option('shedied_campaign');
    $arr = explode("|", $campaign);
    return $arr;
}

function shedied_delete_campaign($id) {

    $campaign = get_option('shedied_campaign');
    $arr = explode("|", $campaign);
    $i = 0;
    foreach ($arr as $val) {
        if (strpos($val, $id) === 0) {
            break;
        }
        $i++;
    }
    unset($arr[$i]);
    $camp = implode($arr, "|");
    update_option('shedied_campaign', $camp);
    return $arr;
}

function shedied_save_campaign() {
    //echo "thanks";
    $campaign = get_option('shedied_campaign');
    if ($campaign != "") {
        $campaign .= "|" . uniqid() . "," . $_POST["news_src"] . "," . $_POST["category"] . "," . $_POST["author"];
    } else
        $campaign = uniqid() . "," . $_POST["news_src"] . "," . $_POST["category"] . "," . $_POST["author"];;
    // save it
    update_option('shedied_campaign', $campaign);
    echo json_encode(shedied_load_campaign());
}

function shedied_save_setting() {
    update_option('shedied_firstpara', $_POST["firstPara"]);
    update_option('shedied_lastpara', $_POST["lastPara"]);
    update_option('shedied_isAutopost', $_POST["isAutopost"]);
    update_option('shedied_isRewrite', $_POST["isRewrite"]);
    update_option('shedied_isFullSource', $_POST["isFullSource"]);
    update_option('shedied_isRemoveLink', $_POST["isRemoveLink"]);
    //1.4.4
    update_option('shedied_isTitleRewrite', $_POST["isTitleRewrite"]);
    echo "Setting Saved";
}

function shedied_ajax() {
    $todo = $_POST['todo'];
    switch ($todo) {
        case "save_setting":
            shedied_save_setting();
            break;
        case "add_campaign":
            shedied_save_campaign();
            break;
        case "delete_campaign":
            shedied_delete_campaign($_POST['id']);
            break;
        case "load_campaign":
            echo json_encode(shedied_load_campaign());
    }
    wp_die();
}

add_filter('upload_mimes', 'shedied_upload_mimes');

function shedied_upload_mimes($mimes) {
    $mime_types = array(
        'ac3' => 'audio/ac3',
        'mpa' => 'audio/MPA',
        'flv' => 'video/x-flv',
        'torrent' => 'application/x-bittorrent'
    );
    return array_merge($mimes, $mime_types);
}

require_once 'shedied_widget.php';
require_once 'shedied_bot.php';
