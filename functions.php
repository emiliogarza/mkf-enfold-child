<?php
function enfold_theme_enqueue_styles() {

    $parent_style = 'avia-style';

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}
function avia_include_shortcode_template($paths) {
	$template_url = get_stylesheet_directory();
    	array_unshift($paths, $template_url.'/custom-avia-shortcodes/');

	return $paths;
}

add_action( 'wp_enqueue_scripts', 'enfold_theme_enqueue_styles' );

add_filter('avia_load_shortcodes', 'avia_include_shortcode_template', 15, 1);

?>