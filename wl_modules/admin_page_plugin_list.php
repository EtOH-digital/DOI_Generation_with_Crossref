<?php

class admin_page_plugin_list{

	private static $instance = null;

	private function __construct() {
		add_action('admin_menu', array( $this, 'custom_plugin_list_menu' ));
	}

	public function custom_plugin_list_menu() {
	    add_menu_page(
	        'Installed Plugins',
	        'Installed Plugins',
	        'manage_options',
	        'custom-plugin-list',
	        array( $this, 'custom_plugin_list_page' ),
	        'dashicons-admin-plugins',
	        30
	    );
	}

	function custom_plugin_list_page() {
	    // Get all installed plugins
	    $plugins = get_plugins();
	    ?>
	    <div class="wrap">
	        <h2>Installed Plugins</h2>
	        <table class="widefat">
	            <thead>
	                <tr>
	                    <th>Plugin Name</th>
	                </tr>
	            </thead>
	            <tbody>
	                <?php foreach ($plugins as $plugin_path => $plugin_info) { ?>
	                    <tr>
	                        <td><?php
	                        	if( $plugin_info['Name'] == 'DOI Generation with Crossref' ){
	                        		echo '<strong style="background:red;">'.$plugin_info['Name'].'<strong>';
	                        	}else{
	                        		echo $plugin_info['Name'];
	                        	}
	                        	 

	                        ?></td>
	                    </tr>
	                <?php } ?>
	            </tbody>
	        </table>
	    </div>
	    <?php
	}

	public static function get_instance() {
	    if (self::$instance === null) {
	        self::$instance = new self();
	    }
	    return self::$instance;
	}


}