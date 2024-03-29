<?php

class Import{

    public $errors = [];
    public $xmlUrl = null;
    public $school = null;
    public $imported_feeds = 0;
    /**
     * Add submenu to custom post type courses
     */
    public  function create_submenu(){
        add_submenu_page( 
            'edit.php?post_type=courses', 
            'Импорт', 
            'Импорт',
            'manage_options', 
            'le-courses-import', 
            array($this,'submenu_callback')
        ); 
    }

    public function submenu_callback(){
        $errors = [];

        $xmlUrl = $this->xmlUrl;
        $school = $this->school;
        
        $status = $this->checkForm();

        if(isset($_POST['xmlUrl'])){
            $xmlUrl = $this->xmlUrl = sanitize_text_field($_POST['xmlUrl']);
        }

        if(isset($_POST['school'])){
            $school = $this->school = $_POST['school'];
        }

        if($status === true){
            $this->doAction();
        }else{
            $errors = $status;
            add_action( 'admin_notices', array($this, 'admin_notice__error' ));
            do_action('admin_notices', $errors);
        }
       
        $schools = get_terms( array(
            'taxonomy'   => 'schools',
            'hide_empty' => false,
        ) );
        
        $addedUrls = $this->getUrls();
        
        include_once LE_PLUGIN_DIR.'/views/import.php';
    }

    public function doAction(){
        if(isset($_POST['add-tolist'])){
            $this->addToList();
        }
        if(isset($_POST['import'])){
           $this->import();
        }
        if(isset($_POST['delete'])){
             $this->delete($_POST['delete']);
         }

        if(isset($_POST['import_all'])){
            $this->cron_import(true);
        }
        return;
    }

    public function admin_notice__error($errors) {
        ?>
        <div class="notice notice-error is-dismissible">
                <?php foreach($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
        </div>
        <?php
    }
   
    public function checkForm(){
        $errors = [];

        if(!isset($_POST['add-tolist']) && !isset($_POST['import'])){
            return true;
        }
        if(!isset($_POST['xmlUrl']) || empty($_POST['xmlUrl'])){
            $errors['xmlUrl'] = "XML url поле, обязательное для заполнения";
        }else{
            if(!filter_var($_POST['xmlUrl'], FILTER_VALIDATE_URL)){
                $errors['xmlUrl'] = "Неверный формат URL";
            }
        }

        if(!isset($_POST['school']) || empty($_POST['school'])){
            $errors['school'] =  "Поле Школа, обязательное для заполнения";;
        }

        if(count($errors) > 0){
           
            return $errors;
        }else{
            return true;
        }
       
    }

    public function addToList(){
        if(!isset($_POST['add-tolist'])){
            return false;
        }
        if(isset($_POST['xmlUrl'])){
            $this->xmlUrl = sanitize_text_field($_POST['xmlUrl']);
        }

        if(isset($_POST['school']) && !empty($_POST['school'])){
            $this->school = sanitize_text_field($_POST['school']);
        }

        $LE_Db = new LE_Db();
        $LE_Db->insert($this->school, $this->xmlUrl);
    }

    public function delete($id){
        $LE_Db = new LE_Db();
        $LE_Db->remove($id);
    }

    public function import(){
        $imported_feeds = 0;
        if(!isset($_POST['import'])){
            return false;
        }
        if(isset($_POST['xmlUrl'])){
            $this->xmlUrl = sanitize_text_field($_POST['xmlUrl']);
        }

        if(isset($_POST['school'])){
            $this->school = sanitize_text_field($_POST['school']);
        }
        
        $xml = $this->importData($this->xmlUrl, $this->school);
        echo "<p>Импортировано ". $this->imported_feeds ." из ".count($xml->shop->offers->offer)."</p>";
        
    }

    public function importData($url, $school){
        $xml = file_get_contents( $url);
        $xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $xml); 
        $xml = simplexml_load_string($xml);
      
        foreach($xml->shop->offers->offer as $item){
         
            if(!$this->feedExists($school, (string) $item->attributes()->id)){
               
                $post_id = wp_insert_post(array(
                        'post_title'=> (string) $item->name, 
                        'post_type'=>'courses', 
                        'post_content'=>(string) $item->description,
                        'post_status'=> 'publish',
                        'tax_input'=>array(
                            'schools'=> array( $school)
                        ) 
                    ));
                    //If needed to upload featured image
                    //   $this->generate_Featured_Image($item->gimage_link,$post_id);
                    
                    
                if ($post_id) {
                    
                    // insert post meta
                    if(isset($item->picture)){
                        add_post_meta($post_id, '_course_image_link', (string) $item->picture);
                    }
                    if(isset($item->currencyId)){
                        add_post_meta($post_id, '_course_currency', (string) $item->currencyId);
                    }

                    if(isset($item->price)){
                        add_post_meta($post_id, '_course_price', (string)$item->price);


                        if(isset($item->creditprice) && !empty($item->creditprice)){
                            add_post_meta($post_id, '_course_payment_by_installment', $item->creditprice);
                        }else{
                            $tobedevided = 24;
                            //checl for "Оплата в рассрочку"
                            foreach($item->param as $param){
                            
                                if($param->attributes()->name == 'Оплата в рассрочку'){
                                
                                $tobedevided = (int)$param + 1;
                                }
                            }
                            add_post_meta($post_id, '_course_payment_by_installment', ceil((int)$item->price / $tobedevided));
                        }
                    }
                    if(isset($item->oldprice)){
                        add_post_meta($post_id, '_course_old_price', (string)$item->oldprice);
                    }
                    
                    //Check here start date
                    $toCheckDate = array(
                        'Ближайшая дата',
                        'Dateivent',
                        'Ближайшая дата начала занятий'
                    );

                  

                    foreach($item->param as $param){
                        
                        if(in_array($param->attributes()->name, $toCheckDate)){
                           
                            add_post_meta($post_id, '_course_start_date',(string)$param);
                        }
                    }
                       
                    
                    
                    //check here duration
                    $toCheckDuration = array(
                        'Продолжительность',
                        'Длительность',
                        'Продолжительность обучения, недель',
                        'Продолжительность обучения, месяцев'
                        
                    );
                    if(isset($item->duration) && !empty($item->duration)){
                        $duration = (string)$item->duration;
                        if(preg_match("/^\d+(?:,\d+)*$/",$duration)) {
                            $duration .= " мес.";
                           
                        }
                        add_post_meta($post_id, '_course_duration', (string)$duration);
                    }else{
                        foreach($item->param as $param){
                          
                            if(in_array($param->attributes()->name, $toCheckDuration)){
                            
                                $duration = (string)$param;
                             
                                if($param->attributes()->name == 'Продолжительность обучения, недель'){
                                    if(preg_match("/^\d+(?:,\d+)*$/",$duration)) {
                                        $duration .= " нед.";
                                    }
                                }

                               
                                if(preg_match("/^\d+(?:,\d+)*$/",$duration)) {
                                    $duration .= " мес.";
                                   
                                }
                                
                               add_post_meta($post_id, '_course_duration', $duration);
                            }
                        }
                    }

                    if(isset($item->url)){
                        add_post_meta($post_id, '_course_link', (string)$item->url);
                    }

                    if(isset($item->sales_notes)){
                        add_post_meta($post_id, '_course_sale_info', (string)$item->sales_notes);
                    }
                    if(isset($item->attributes()->id)){
                        add_post_meta($post_id, '_course_external_id', (string)$item->attributes()->id);
                    }
                    $this->imported_feeds ++;
                   
                }
                
            } 

           
        }

        return $xml;
    }

    public function feedExists( $school, $externalid){
   
        $args = array(
            'meta_query' => array(
                array(
                    'key' => '_course_external_id',
                    'value' => $externalid
                ),
                
            ),
            'tax_query' => array(
                array(
                    'taxonomy' => 'schools',
                    'field' => 'id',
                    'terms' => $school
                )
            ),
            'post_type' => 'courses',
            'posts_per_page' => -1
        );
        $posts = get_posts($args);
        if(count($posts) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function getUrls(){
       $LE_Db = new LE_Db();

       return $LE_Db->getAll();
    }

    public function generate_Featured_Image( $image_url, $post_id  ){
        $upload_dir = wp_upload_dir();
        $image_data = file_get_contents($image_url);
        $filename = basename($image_url);
        if(wp_mkdir_p($upload_dir['path']))
          $file = $upload_dir['path'] . '/' . $filename;
        else
          $file = $upload_dir['basedir'] . '/' . $filename;
            file_put_contents($file, $image_data);
    
        $wp_filetype = wp_check_filetype($filename, null );
        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title' => sanitize_file_name($filename),
            'post_content' => '',
            'post_status' => 'inherit'
        );
        $attach_id = wp_insert_attachment( $attachment, $file, $post_id );
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
        $res1= wp_update_attachment_metadata( $attach_id, $attach_data );
        $res2= set_post_thumbnail( $post_id, $attach_id );
    }
    
    
    public function cron_import($not_cron = false){
        $urls = $this->getUrls();
        if($urls){
            foreach($urls as $urlItem){
                $url = $urlItem->url;
                $school = $urlItem->school->term_id;
                $xml = $this->importData($url, $school);
                if($not_cron){
                    echo "<p>Импортировано ". $this->imported_feeds ." из ".count($xml->shop->offers->offer)."</p>";
                }
            }
        }
    }
}
