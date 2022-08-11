<?php
 
/* enqueue script for parent theme stylesheet */   
function hello_elementor_child() {
     
    // enqueue style
    wp_enqueue_style( 'parent', get_template_directory_uri().'/style.css' );
         
}
add_action( 'wp_enqueue_scripts', 'hello-elementor');