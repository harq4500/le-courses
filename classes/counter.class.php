<?php

class Counter{

    public function  rewrites_init(){
        add_rewrite_endpoint( 'le-redirect', EP_ROOT );
    }

    public function redirect(){
        if ( $redirectUrl = get_query_var( 'le-redirect' ) ) {
            if(isset($redirectUrl) && !empty($redirectUrl)){
                if(  !empty($_GET['id'])){
                    $post = get_post($_GET['id']);

                    if($this->updateVisits($post)){
                        $post->course_link = get_post_meta( $post->ID, '_course_link', true );
                        if(!empty($post->course_link)){
                           
                            wp_redirect($post->course_link);

                            die();
                        }
                       
                        
                    }
                }
            }
            
    
            // then stop processing
            wp_redirect('/');
        }
    }

    private function updateVisits($post){
        $visits  = get_post_meta( $post->ID, '_visits', true );
        if(empty($visits)){
            update_post_meta( $post->ID, '_visits', 1 );
        }else{
            $visits  = (int)$visits += 1; 

            update_post_meta( $post->ID, '_visits', $visits );
        }
       
        return true;
    }

    public function handle_request($vars){
        if ( isset( $vars['le-redirect'] ) && empty( $vars['le-redirect'] ) ) {
            $vars['le-redirect'] = 'default';
        }
        return $vars;

    }
    
    public function set_custom_edit_columns($columns){
   
        unset( $columns['visits'] );
        $columns['visits'] = __( 'Visits', 'your_text_domain' );
    
        return $columns;
    }

    public function custom_column( $column, $post_id ) {
        
        switch ( $column ) {
            case 'visits' :
                $visits =  get_post_meta( $post_id , '_visits' , true );
                echo (!empty($visits) ? $visits : 0); 
            break;
    
        }
    }
}