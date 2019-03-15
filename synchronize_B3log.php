<?php

/*
Plugin Name: synchronize B3log
Plugin URI: https://github.com/zhaofeng-shu33/synchronize_B3log
Description: synchronize wordpress articles to B3log website 
Author: zhaofeng-shu33
Version: 0.1
Author URI: https://github.com/zhaofeng-shu33
*/
if ( ! defined( 'ABSPATH' ) ) exit;

// wordpress blog to B3log community website
function synchronize_B3log_b_to_c($ID, $post){
    $postJson = array(
        'article' => array(
            'id' =>  $ID,
            'title' => $post->post_title,
            'permalink' => get_permalink($ID),
            'tags' => join(',', wp_get_post_categories($ID)),
            'content' => $post->post_content
            ),
        'client' => array(
            'title' => get_option('blogname'),
            'host' => home_url(),
            'name' => 'wordpress',
            'ver' => $wp_version,
            'username' => get_the_author_meta( 'display_name', $post->post_author),
            'userB3Key' => '23316477'
        )
    );
    $response = wp_safe_remote_post('https://rhythm.b3log.org/api/article', array('body' => json_encode($postJson)));
    if(is_wp_error($response))
        return;
}

add_action('publish_post', 'synchronize_B3log_b_to_c', 10, 2);
?>