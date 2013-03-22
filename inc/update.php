<?php
define('DEBUG', false);
class WpPluginAutoUpdate {
    public $api_url;
    public $package_type;
    public $plugin_slug;
    public $plugin_file;

    public function WpPluginAutoUpdate($api_url, $type, $slug) {
        $this->api_url = $api_url;
        $this->package_type = $type;
        $this->plugin_slug = $slug;
        $this->plugin_file = $slug .'/'. $slug . '.php';
    }

    public function print_api_result() {
        print_r($res);
        return $res;
    }
    public function check_for_plugin_update($checked_data) {
        if (empty($checked_data->checked))
            return $checked_data;
        $request_args = array(
            'slug' => $this->plugin_slug,
            'version' => $checked_data->checked[$this->plugin_file],
            'package_type' => $this->package_type,
        );

        $request_string = $this->prepare_request('basic_check', $request_args);
        
        $raw_response = wp_remote_post($this->api_url, $request_string);

        if (!is_wp_error($raw_response) && ($raw_response['response']['code'] == 200)) {
            $response = unserialize($raw_response['body']);

            if (is_object($response) && !empty($response))
                $checked_data->response[$this->plugin_file] = $response;
        }
        return $checked_data;
    }

    public function plugins_api_call($def, $action, $args) {
        if ($args->slug != $this->plugin_slug)
            return false;
        
        // Get the current version
        $plugin_info = get_site_transient('update_plugins');
        $current_version = $plugin_info->checked[$this->plugin_file];
        $args->version = $current_version;
        $args->package_type = $this->package_type;
        
        $request_string = $this->prepare_request($action, $args);
        
        $request = wp_remote_post($this->api_url, $request_string);
        
        if (is_wp_error($request)) {
            $res = new WP_Error('plugins_api_failed', __('An Unexpected HTTP Error occurred during the API request.</p> <p><a href="?" onclick="document.location.reload(); return false;">Try again</a>'), $request->get_error_message());
        } else {
            $res = unserialize($request['body']);
            
            if ($res === false)
                $res = new WP_Error('plugins_api_failed', __('An unknown error occurred'), $request['body']);
        }
        
        return $res;
    }
    public function prepare_request($action, $args) {
	    $site_url = site_url();
		$plugin=str_replace('/inc','',plugin_basename(dirname(__FILE__)));
		
		$license=stripslashes(get_option( 'woocommerce_geev_license_key' ));
        $wp_info = array(
            'site-url' => $site_url,
            'version' => $wp_version,
			'license'=>$license,
			'plugin'=>$plugin,
        );

        return array(
            'body' => array(
                'action' => $action, 'request' => serialize($args),
                'api-key' => md5($site_url),
                'wp-info' => serialize($wp_info),
            ),
            'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
        );
    }
} 
$wp_plugin_auto_update = new WpPluginAutoUpdate('http://www.siteevaluator.nl/plugins/update.php', 'stable', str_replace('/inc','',plugin_basename(dirname(__FILE__))));

if (DEBUG) {
    set_site_transient('update_plugins', null);
    add_filter('plugins_api_result', array($wp_plugin_auto_update, 'print_api_result'), 10, 3);
}
add_filter('pre_set_site_transient_update_plugins', array($wp_plugin_auto_update, 'check_for_plugin_update'));
add_filter('plugins_api', array($wp_plugin_auto_update, 'plugins_api_call'), 10, 3);
?>