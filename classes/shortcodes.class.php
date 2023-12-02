<?php

class LE_Shortcodes{

    public function __construct(){
        add_shortcode('courses', array($this, 'getCourses'));
    }
    
    public function le_enqueue(){
        wp_enqueue_style( 'le_style_fr', LE_PLUGIN_URL . '/assets/css/le_style.css', false, '1.0.0' );
    }

    public function getCourses($atts){
       
        $posts = $this->getPosts($atts);

        ob_start();
        include  LE_PLUGIN_DIR . '/views/shortcode_table_view.php';
        $template = ob_get_clean();
        return $template;
    }

    private function getPosts($atts){
        global $post;

        $args = array(
            'orderby'=>'date',
            'post_type'=>'courses',
            'post_status'=>'publish'
        );

        if(isset($atts) && !empty($atts)){
            if(isset($atts['limit']) && !empty($atts['limit'])){
                $args['numberposts'] = $atts['limit'];
            }

            if(isset($atts['order']) && !empty($atts['order'])){
                $args['order'] = $atts['order'];
            }

            if(isset($atts['categories']) && !empty($atts['categories'])){
                
                $args['tax_query'] =  array(
                    array(
                        'taxonomy' => 'categories',
                        'field' => 'slug', 
                        'terms' =>  explode(',',$atts['categories']), 
                        'include_children' => false
                      )
                );
            }

            if(isset($atts['ids']) && !empty($atts['ids'])){
                
                $args['post__in'] = explode(',',$atts['ids']);
            }

            if(isset($atts['schools']) && !empty($atts['schools'])){
                
                $args['tax_query'] =  array(
                    array(
                        'taxonomy' => 'schools',
                        'field' => 'slug', 
                        'terms' =>  explode(',',$atts['schools']), 
                        'include_children' => false
                      )
                );
            }
        }

        $posts = get_posts($args);


        foreach($posts as  $post){

            $post->course_image_link = get_post_meta( $post->ID, '_course_image_link', true );
            $post->course_availability = get_post_meta( $post->ID, '_course_availability', true );
            $post->course_price = get_post_meta( $post->ID, '_course_price', true );
            $post->course_sale_price = get_post_meta( $post->ID, '_course_sale_price', true );
            $post->course_payment_info = get_post_meta( $post->ID, '_course_payment_info', true );
            $post->course_start_date = get_post_meta( $post->ID, '_course_start_date', true );
            $post->course_duration = get_post_meta( $post->ID, '_course_duration', true );
            $post->course_link = get_post_meta( $post->ID, '_course_link', true );
            $post->course_external_id = get_post_meta( $post->ID, '_course_external_id', true );

            $school = get_the_terms($post,'schools');
            
            if($school){
                $school[0]->school_url = get_term_meta($school[0]->term_id, 'le_school_url', true);
                $school_meta_image = get_term_meta($school[0]->term_id, 'le_school_img', true);
                if($school_meta_image){
                   $school[0]->image_url =  wp_get_attachment_image_url($school_meta_image);
                }
                $post->school = $school[0];
            }
           
        }

        return $posts;
    }


    public function create_submenu(){

        add_submenu_page( 
            'edit.php?post_type=courses', 
            'Help', 
            'Help',
            'manage_options', 
            'le-courses-help', 
            array($this,'submenu_callback')
        ); 
       
    }

    public function submenu_callback(){
        include_once LE_PLUGIN_DIR.'/views/help.php';
    }
   
}