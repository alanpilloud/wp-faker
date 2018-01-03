<?php
require __DIR__. '/../wp-load.php';
$upload_dir = wp_upload_dir();
define('UPLOAD_DIR', $upload_dir['path']);

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/WpFaker/Post.php';
require __DIR__ . '/WpFaker/Config.php';

$WpFaker = new Post();
$Config = Config::load_config_file();

Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);

$faking_content_url = get_site_url().'/wp-faker/index.php';

if (filter_input(INPUT_GET, 'proceed') == 1) {
    include(ABSPATH.'wp-admin/includes/image.php'); 
    
    if (!username_exists('WpFakerUser')) {
        $WpFaker->createUser();
    } else {
        $user = get_user_by('login', 'WpFakerUser');
        $WpFaker->user_id = $user->data->ID;
    }

    /**
     * Generating content !
     */
    $WpFaker->createPost($Config);

    /**
     * Generating post thumbnail !
     */
    if ($Config->post_thumbnail) {
        $attachement_id = $WpFaker->saveImage($Config->post_thumbnail);
        update_post_meta( $WpFaker->post_id, '_thumbnail_id', $attachement_id ); // Associating attachement to the post
    }
    
    /**
     * Generating Acf values !
     */
    if (isset($Config->acf_values)) {
        $WpFaker->saveAcf($Config->acf_values);
    }
    
    /**
     * Add terms
     */
    $WpFaker->saveTerms($Config);        
    
    /**
     * Prepare values for the template
     */
    $data['post'] = [
        'id' => $WpFaker->post_id,
        'url' => get_permalink($WpFaker->post_id),
        'title' => $Config->post_title,
    ];

    $data['footer'] = '<a href="'.$faking_content_url.'?proceed=1&using='.$Config->using.'" class="btn btn-sm btn-primary">Great, one more time!</a> <small>or</small> <a href="'.$faking_content_url.'?proceed=0&using='.$Config->using.'" class="btn btn-sm btn-secondary">what\'s my config again?</a>';
    $template = 'go.twig';
    
} else {
    /**
     * Asking to confirm config
     */
    $data['config'] = [
        'Post type : '.$Config->post_type,
        'Post title : '.$Config->post_title,
        'Post content : '.$Config->post_content
    ];
    
    if (isset($Config->acf_values)) {
        $data['config'][] = 'Dummy Acf values example : <pre class="pre-scrollable">'.print_r($Config->acf_values,1).'</pre>';
    }
    
    $data['footer'] = '<a href="'.$faking_content_url.'?proceed=1&using='.$Config->using.'" class="btn btn-sm btn-primary">OK, let\'s go!</a>';
    $template = 'ready.twig';
}

if ($Config->get_config_files()) {
    $data['config_files'] = $Config->config_files;
}

echo $twig->render($template, $data);
