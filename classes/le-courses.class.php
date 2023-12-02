<?php

class LE_Courses{

  private static $initiated = false;
    
  public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
	}

    /**
	 * Initializes WordPress hooks
	 */
	private static function init_hooks() {
		self::$initiated = true;

    $CustomPost  = new CustomPost();
    $Import = new Import();
    $LE_Shortcodes =  new LE_Shortcodes(); 

    add_action('add_meta_boxes', array($CustomPost, 'meta_box'));

    add_action('admin_menu', array($Import,'create_submenu'));
    add_action('admin_menu', array($LE_Shortcodes,'create_submenu'));

    add_action('save_post', array( $CustomPost, 'courses_save_meta_box_data') );

    add_action('admin_enqueue_scripts', array($CustomPost, 'le_enqueue'));

    add_action('wp_enqueue_scripts', array($LE_Shortcodes, 'le_enqueue'));

    add_action( 'schools_add_form_fields', array($CustomPost, 'le_school_add_term_fields' ));
    add_action( 'schools_edit_form_fields', array($CustomPost, 'le_school_edit_term_fields' ), 10, 2 );

    add_action( 'created_schools',  array($CustomPost,'schools_save_term_fields' ));
    add_action( 'edited_schools',  array($CustomPost,'schools_save_term_fields' ));
    add_filter( 'cron_schedules', array('LE_Courses', 'le_add_weekly' ));

    add_action( 'le_import_feeds', array($Import, 'cron_import' ));
  }

  public static function plugin_activation(){
    
    $LE_Db = new LE_Db();
    $LE_Db->create();

      $timestamp = wp_next_scheduled( 'le_import_feeds' );

      //If $timestamp == false schedule weekly imports since it hasn't been done previously
      if( $timestamp == false ){
        //Schedule the event for right now, then to repeat weekly using the hook 'le_import_feeds'
        wp_schedule_event( time(), 'weekly', 'le_import_feeds' );
      }

  }

  public static function plugin_deactivation(){
    // $LE_Db = new LE_Db();
    // $LE_Db->delete();
  }

  public static function le_add_weekly( $schedules ) {
      // add a 'weekly' schedule to the existing set
      $schedules['weekly'] = array(
        'interval' => 604800,
        'display' => __('Once Weekly')
      );
      return $schedules;
  }
}