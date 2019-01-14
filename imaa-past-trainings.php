<?php

/**
 * Plugin Name: Past Trainings
 * Description: Custom Gallery for The Event Calendar
 * Author: Nick Bosswell
 * Version: 1.0
 * Text Domain: imaa-past-trainings
 */

/**
 * Add image sizes for sliders
 */
add_image_size( 'slider-image', 820, 485, true );
add_image_size( 'event-thumbnail', 80, 80, true );

/**
 * Load CSS and JavaScript files
 */
function imma_past_training_css_js() {
    wp_enqueue_style( 'imma_past_training_bxslider_css', plugins_url( 'css/', __FILE__ ).'bxslider.css' );
    wp_enqueue_style( 'imma_past_training_owl_css', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css' );
    wp_enqueue_style( 'imma_past_training_owl_theme_css', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css' );
    wp_enqueue_style( 'imma_past_training_font_awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css'); 
    wp_enqueue_style( 'imma_past_training_style_css', plugins_url( 'css/', __FILE__ ).'style.css' ); 
    wp_enqueue_script( 'imma_past_training_bxslider_js',  plugins_url( 'js/', __FILE__ ).'bxslider.js', array('jquery') );
    wp_enqueue_script( 'imma_past_training_owl_js', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js', array('jquery') );
    wp_enqueue_script( 'imma_past_training_script_js',  plugins_url( 'js/', __FILE__ ).'script.js', array('jquery') );
}
add_action( 'wp_enqueue_scripts', 'imma_past_training_css_js' );

/**
 * Custom Loop shortcode [imaa_past_trainings]
 */
function imaa_past_trainings_shortcode($atts){

    // define shortcode variable
    extract(shortcode_atts(array(
        'post_type' => array(TribeEvents::POSTTYPE),
        'orderby' => 'date',
        'posts_per_page' => 5,
        'eventDisplay' => 'past',
        'tax_terms' => 'ma-trainings'
    ), $atts));

    // define parameters
    $args = array(
        'post_type' => $post_type,
        'orderby' => $orderby,
        'posts_per_page' => $posts_per_page,
        'eventDisplay'=> $eventDisplay,
        'tax_query' => array(
            array(
                'taxonomy' => 'tribe_events_cat',
                'field' => 'slug',
                'terms' => $tax_terms,
            ),
        ),
    );

    // query the posts
    $posts = new WP_Query($args);

    // begin output variable
    if($posts->have_posts()){
        $output .= '<div class="events-container">';
            $output .= '<div class="left-slider">';
            global $post;
            while($posts->have_posts()): $posts->the_post();
                $output .= '<div class="slider-wrap slider-' . $post->ID . '">';
                    $output .= '<div class="owl-carousel owl-theme">';
                    $event_gallery = get_field('imaa_event_gallery');
                    if( $event_gallery ){
                        foreach( $event_gallery as $event_gallery_image ){
                            $output .= wp_get_attachment_image( $event_gallery_image['ID'], 'slider-image' );
                        }
                    }
                    $output .= '</div>';
                $output .= '</div>';
            endwhile;
            $output .= '</div>';
            $output .= '<div class="right-slider">';
                $output .= '<div class="vertical">';
                while($posts->have_posts()): $posts->the_post();
                    $output .= '<div>';
                        $output .= '<a href="' . get_permalink() . '" class="event-item" data-event-id="' . $post->ID . '">';
                            $output .= '<div>';
                                $output .= get_the_post_thumbnail( $post->ID, 'event-thumbnail' );
                            $output .= '</div>';
                            $output .= '<div>';
                                $output .= '<h3>' . wp_trim_words( get_the_title(), 4) . '</h3>';
                                $output .= '<div class="date">' . tribe_get_start_date( $post, false, 'F j, Y') .'</div>';
                            $output .= '</div>';
                        $output .= '</a>';
                    $output .= '</div>';
                endwhile;
                $output .= '</div>';
            $output .= '</div>';
        $output .= '</div>';
    }
    else{
        $output .= '<div class="alert alert-danger">' . esc_html__('Sorry, no posts matched your criteria.', 'imaa-past-trainings') . '</div>';
    }

    // reset post data
    wp_reset_postdata();

    // return output
    return $output;

}

// register shortcode function
add_shortcode('imaa_past_trainings', 'imaa_past_trainings_shortcode');