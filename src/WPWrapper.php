<?php

namespace SheDied;

use SheDied\parser\InterfaceParser;

class WPWrapper {

    public function __construct() {
        ;
    }

    public static function get_page_by_title($post_title, $output = 'OBJECT', $post_type = 'post') {
        return get_page_by_title($post_title, $output, $post_type);
    }

    public static function get_option($option) {
        return get_option($option);
    }

    public static function plugin_dir_path($file) {
        return plugin_dir_path($file);
    }

    public static function wp_insert_post(InterfaceParser $parser) {
        //file_put_contents('/tmp/' . str_replace(' ', '_', $parser->getTitle()) . '.txt', var_export($parser->getContent(), true));
        return wp_insert_post($parser->toWordpressPost());
    }

    public static function current_time($type = 'mysql', $gmt = 0) {
        return current_time($type, $gmt);
    }

    public static function get_cat_name($category_id) {
        return get_cat_name($category_id);
    }

    public static function generate_featured_image(InterfaceParser $parser, $post_id) {
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $filename = media_sideload_image($parser->getFeaturedImage(), $post_id, null, 'src');
        $attach_id = self::get_attachment_id_from_src($filename, $parser->getDefaultAttachID());
        return set_post_thumbnail($post_id, $attach_id);
    }

    private static function get_attachment_id_from_src($image_src, $default) {
        if (is_string($image_src)) {
            global $wpdb;
            $query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";
            $id = $wpdb->get_var($query);
            if ($id) {
                return $id;
            }
        }
        return $default;
    }

    public static function pojokjogja_set_source($post_id, $td_source = '', $td_source_url = '') {
        $meta_value['td_source'] = $td_source;
        $meta_value['td_source_url'] = $td_source_url;
        update_post_meta($post_id, 'td_post_theme_settings', $meta_value);
    }

    public static function add_to_yoast_seo($post_id, $meta_title = '', $meta_description = '', $meta_keywords = '') {
        $ret = false;
        //if (is_plugin_active('wordpress-seo/wp-seo.php')) {
            $updated_title = update_post_meta($post_id, '_yoast_wpseo_title', $meta_title);
            $updated_desc = update_post_meta($post_id, '_yoast_wpseo_metadesc', $meta_description);
            $updated_kw = update_post_meta($post_id, '_yoast_wpseo_metakeywords', $meta_keywords);

            if ($updated_title && $updated_desc && $updated_kw) {
                $ret = true;
            }
        //}
        return $ret;
    }

    public static function pojokjogja_set_source_for_jobstreet($post_id, $td_source = '', $td_source_url = '', $company = '') {
        $meta_value['td_source'] = $td_source;
        $meta_value['td_source_url'] = $td_source_url;
        $meta_value['td_subtitle'] = $company;
        $meta_value['smart_list_template'] = 'td_smart_list_1';
        $meta_value['td_smart_list_h'] = 'h3';
        $meta_value['td_smart_list_order'] = 'asc_1';
        update_post_meta($post_id, 'td_post_theme_settings', $meta_value);
    }

    public static function pojokjogja_set_tags_for_jobstreet($post_id, $company = '', $tags = []) {
        wp_set_post_tags($post_id, strtoupper($company), true);
        wp_set_post_terms($post_id, $tags, 'post_tag', true);
    }

    public static function pojokjogja_set_map_to_post($post_id, InterfaceParser $parser) {
        $map = false;
        if ($parser->hasMap() && is_plugin_active('mappress-google-maps-for-wordpress/mappress.php')) {
            $poi = new \Mappress_Poi([
                'title' => $parser->getTitle(),
                'point' => [
                    'lat' => $parser->getLatitude(),
                    'lng' => $parser->getLongitude()
                ]
            ]);
            $map = new \Mappress_Map([
                'width' => 425,
                'height' => 350,
                'title' => $parser->getTitle()
            ]);
            $map->pois = [$poi];
            $map->display();
            $map->save($post_id);
        }
        return $map;
    }

    public static function mappress_get_map($mapid = '') {
        $map = false;
        if (is_plugin_active('mappress-google-maps-for-wordpress/mappress.php')) {
            $map = \Mappress_Map::get($mapid);
        }
        return $map;
    }

    public static function mappress_update_map($post_id, \Mappress_Map $map) {
        return $map->save($post_id);
    }

    public static function upload_remote_file($filename = '', $remote_file = '') {
        $get = wp_remote_get($remote_file);
        return wp_upload_bits($filename, null, wp_remote_retrieve_body($get));
    }

}
