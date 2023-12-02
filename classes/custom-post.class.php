<?php

class CustomPost{
    
    
    private  $supports = array(
        'title',
        'editor', 
        'thumbnail',
        // 'excerpt',
        // 'revisions',
    );

    private  $cslabels = array(
        'name' => 'Курсы',
        'singular_name' => 'Курсы',
        'menu_name' => 'Курсы',
        'name_admin_bar' => 'courses',
        'add_new' => 'Добавить новое',
        'add_new_item' => 'Добавить новый курс',
        'new_item' => 'Новый курс',
        'edit_item' => 'Редактировать курс',
        'view_item' => 'Посмотреть курс',
        'all_items' => 'Все курсы',
        'search_items' => 'Поиск курса',
        'not_found' => 'Курс не найден.',
    );


    private $custom_fields = array(
        ['id'=>'courses_availability', 'title'=>'']
    );


    public function __construct(){
        $this->create();
    }

    public function create(){
        /**
         * Creating custom post type "Courses"
         */
        $args = array(
            'supports' => $this->supports,
            'labels' =>$this->cslabels,
            'public' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'course'),
            'has_archive' => true,
            'hierarchical' => false,
            'menu_icon' => 'dashicons-welcome-learn-more',
        );

        register_post_type('courses', $args);

        /**
         * Registers custom category for Courses
         */
        register_taxonomy( 'categories', array('courses'), array(
                'hierarchical' => true, 
                'label' => 'Категории', 
                'singular_label' => 'Категория', 
                'rewrite' => array( 'slug' => 'categories', 'with_front'=> false ),
                'default_term' =>array(
                    'name'=> 'Без категории',
                    'slug'=>'uncategoriezed',
                    'description' => 'Категория по умолчанию'
                ),
                'labels'=>array(
                    'name' => 'Категории',
                    'singular_name' => 'Категория',
                    'menu_name' => 'Категории',
                    'add_new' => 'Добавить новое',
                    'add_new_item' => 'Добавить новую категорию',
                    'new_item' => 'Новая категория',
                    'edit_item' => 'Редактировать категорию',
                    'view_item' => 'Посмотреть категорию',
                    'all_items' => 'Все категории',
                    'search_items' => 'Поиск категории',
                    'not_found' => 'Категория не найдена.',
                )
            )
        );

        register_taxonomy_for_object_type( 'categories', 'courses' );

         /**
         * Registers custom category "schools" for Courses
         */
        register_taxonomy( 'schools', array('courses'), array(
                'hierarchical' => true, 
                'label' => 'Школы', 
                'singular_label' => 'Школа', 
                'rewrite' => array( 'slug' => 'schools', 'with_front'=> false ),
                'default_term' =>array(
                    'name'=> 'Без названия',
                    'slug'=>'untitled',
                    'description' => 'Школа по умолчанию'
                ),
                'labels'=>array(
                    'name' => 'Школы',
                    'singular_name' => 'Школа',
                    'menu_name' => 'Школы',
                    'add_new' => 'Добавить новое',
                    'add_new_item' => 'Добавить новую школу',
                    'new_item' => 'Новая категория',
                    'edit_item' => 'Редактировать школу',
                    'view_item' => 'Посмотреть школу',
                    'all_items' => 'Все школы',
                    'search_items' => 'Поиск школы',
                    'not_found' => 'Школа не найдена.',
                )
            )
        );

        register_taxonomy_for_object_type( 'schools', 'courses' );

    }

    public  function meta_box(){
        add_meta_box(
            'courses_sectionid',
           'Детали курса',
            array($this,'course_meta_box_callback'),
            'courses'
        );
    }

    /**
     * Prints the box content.
     *
     * @param WP_Post $post The object for the current post/page.
     */
    public function course_meta_box_callback( $post ) {

        // Add a nonce field so we can check for it later.
        wp_nonce_field( 'courses_save_meta_box_data', 'courses_meta_box_nonce' );
        
        /*
        * Use get_post_meta() to retrieve an existing value
        * from the database and use the value for the form.
        */
        $course_image_link = get_post_meta( $post->ID, '_course_image_link', true );
        $course_availability = get_post_meta( $post->ID, '_course_availability', true );
        $course_price = get_post_meta( $post->ID, '_course_price', true );
        $course_sale_price = get_post_meta( $post->ID, '_course_sale_price', true );
        $course_payment_info =  get_post_meta( $post->ID, '_course_payment_info', true );
        $course_start_date = get_post_meta( $post->ID, '_course_start_date', true );
        $course_duration = get_post_meta( $post->ID, '_course_duration', true );
        $course_link = get_post_meta( $post->ID, '_course_link', true );
        $course_external_id = get_post_meta( $post->ID, '_course_external_id', true );
        
        include_once LE_PLUGIN_DIR.'/views/metabox.php';
       
    }

    /**
     * When the post is saved, saves our custom data.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function courses_save_meta_box_data( $post_id ) {
       
        if ( ! isset( $_POST['courses_meta_box_nonce'] ) ) {
        return;
        }

        if ( ! wp_verify_nonce( $_POST['courses_meta_box_nonce'], 'courses_save_meta_box_data') ) {
        return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
        }

        // Check the user's permissions.
        if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }

        } else {

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
        }

        if ( ! isset( $_POST['course_availability'] ) ) {
        return;
        }

        $course_image_link = sanitize_text_field( $_POST['course_image_link'] );
        $course_availability = sanitize_text_field( $_POST['course_availability'] );
        $course_price = sanitize_text_field( $_POST['course_price'] );
        $course_sale_price = sanitize_text_field( $_POST['course_sale_price'] );
        $course_payment_info = sanitize_text_field( $_POST['course_payment_info'] );
        $course_start_date = sanitize_text_field( $_POST['course_start_date'] );
        $course_duration = sanitize_text_field( $_POST['course_duration'] );
        $course_link = sanitize_text_field( $_POST['course_link'] );
        $course_external_id = sanitize_text_field( $_POST['course_external_id'] );

        update_post_meta( $post_id, '_course_image_link', $course_image_link );
        update_post_meta( $post_id, '_course_availability', $course_availability );
        update_post_meta( $post_id, '_course_price', $course_price );
        update_post_meta( $post_id, '_course_sale_price', $course_sale_price );
        update_post_meta( $post_id, '_course_payment_info', $course_payment_info );
        update_post_meta( $post_id, '_course_start_date', $course_start_date );
        update_post_meta( $post_id, '_course_duration', $course_duration );
        update_post_meta( $post_id, '_course_link', $course_link );
        update_post_meta( $post_id, '_course_external_id', $course_external_id );
    }

    public function le_school_add_term_fields( $taxonomy ) {
        include_once LE_PLUGIN_DIR.'/views/school_custom_add.php';
    }

    public function le_school_edit_term_fields( $term, $taxonomy ) {

        // get meta data value
        $le_school_url = get_term_meta( $term->term_id, 'le_school_url', true );
        $le_school_img = get_term_meta( $term->term_id, 'le_school_img', true );
        
        include_once LE_PLUGIN_DIR.'/views/school_custom_edit.php';
    }

    /**
     * Save schools custom fields
     */
  
    public function schools_save_term_fields( $term_id ) {
        
        update_term_meta(
            $term_id,
            'le_school_url',
            sanitize_text_field( $_POST[ 'le_school_url' ] )
        );
        update_term_meta(
            $term_id,
            'le_school_img',
            absint( $_POST[ 'le_school_img' ] )
        );
        
    }
    
    /**
     * Add custom js and css to Admin
     */
    public function le_enqueue($hook) {
        wp_enqueue_style( 'le_style', LE_PLUGIN_URL . '/assets/css/le_admin.css', false, '1.0.0' );
       
        if ('edit-tags.php' !== $hook && 'term.php' !== $hook) {
            return;
        }
        wp_enqueue_media();
       

        wp_enqueue_script('le_school_script', LE_PLUGIN_URL . '/assets/js/school_taxonomy.js',array('jquery', 'media-upload'), '1.0.0', true);
    }

}