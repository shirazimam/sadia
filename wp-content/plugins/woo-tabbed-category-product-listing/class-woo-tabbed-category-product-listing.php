<?php

class Woo_Tabbled_Categoty{

    public function initialize()
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function enqueue_styles()
    {
        wp_enqueue_style(
            'woo_tabbed_category',
            plugins_url('woo-tabbed-category-product-listing/assets/css/admin.css'),
            array(),
            '0.1.0'
        );
    }


    public function enqueue_scripts()
    {
        wp_enqueue_script(
            'woo_tabbed_category',
            plugins_url('woo-tabbed-category-product-listing/assets/js/admin.js'),
            array('jquery'),
            '0.1.0'
        );
    }
    
}

?>