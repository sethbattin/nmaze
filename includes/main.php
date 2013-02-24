<?php
set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ );
//var_dump(get_include_path()); die();

function nm_head_links(){

    echo ('
		<link type="text/css" rel="stylesheet" href="/nmaze/nmaze.css"></link>
		<script type="text/javascript" src="/nmaze/js/seedrandom.js"></script>
		<script type="text/javascript" src="/nmaze/js/nmaze.js"></script>
        <script type="text/javascript" src="/nmaze/js/tabledisplay.js"></script>
        ');
}

function nm_get_settings(){
    
    if (count($_GET) == 0){
        $settings = array(5,5,2,2,40);
    } else {
        $keys = array_keys($_GET);
        $param = preg_replace("/([^0-9\-])/", "",$keys[0]);
        $params = explode("-",$param);
        $settings = array();
        foreach ($params as $p){
            if ($p != '' && is_numeric($p) && is_integer((int)$p)){
                $settings[] = (int)$p;
            }
        }
    }
    if (count($settings) == 0){
        $seed = 20;
    } else {
        $seed = array_pop($settings);
    }
    if (count($settings) == 0){
        $settings[] = 10;
    }
    if (count($settings) == 1){
        $settings[] = $settings[0];
    }
    
    $json = array('seed' => $seed, 'dims' => $settings);
    return $json;
}