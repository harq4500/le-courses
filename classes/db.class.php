<?php

class LE_Db{




    public function create(){
       $this->import_urls_table();
    }

    public function delete(){
        global $wpdb;

        $table_name = $wpdb->prefix . 'le_import_urls';

        $wpdb->query( "DROP TABLE IF EXISTS $table_name" );
    }

    public function getAll(){
        global $wpdb;

        $table_name = $wpdb->prefix . 'le_import_urls';

        $results = $wpdb->get_results( "SELECT * FROM $table_name" );

        foreach ( $results as $result ) {

            
           $result->school =  get_term($result->school);
        }

        return $results;
    }

    public function insert($school, $url){
        
        global $wpdb;

        $table_name = $wpdb->prefix . 'le_import_urls';
      
        $result = $wpdb->insert(
            $table_name,
            array(
                'school' => $school,
                'url'  => $url
            )
        );

     
        return true;
    }

    public function remove($id){
        
        global $wpdb;

        $table_name = $wpdb->prefix . 'le_import_urls';
      
        $wpdb->delete( $table_name, array( 'id' => $id ) );

     
        return true;
    }

    private function import_urls_table(){
        global $wpdb;

        $table_name = $wpdb->prefix . 'le_import_urls';
    
        $charset_collate = $wpdb->get_charset_collate();
    
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            school tinytext NOT NULL,
            url varchar(255) DEFAULT '' NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";
    
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql );
    }
}