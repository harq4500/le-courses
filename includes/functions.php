<?php


if(!function_exists('dump')){
	function dump($t){
		echo "<pre>",var_dump($t),"</pre>";
	}
}

if(!function_exists('le_redirectUrl')){
    function le_redirectUrl($id){
        return get_site_url()."/le-redirect?id=".$id;
    }
}