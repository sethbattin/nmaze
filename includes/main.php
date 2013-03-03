<?php
set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ );
define("NM_LOCAL",(strpos(get_include_path(), '/var/www/nmaze') !== FALSE));

function nm_head_links(){
    $links = 
    '
		<link type="text/css" rel="stylesheet" href="/nmaze/nmaze.css"></link>
		<script type="text/javascript" src="/nmaze/js/seedrandom.js"></script>
		<script type="text/javascript" src="/nmaze/js/nmaze.js"></script>
        <script type="text/javascript" src="/nmaze/js/tabledisplay.js"></script>
        ';
        
    if (!NM_LOCAL){
        $links .= 
        "<script type=\"text/javascript\">

            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-38760974-1']);
            _gaq.push(['_trackPageview']);

            (function() {
              var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
              ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
              var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();
        </script>
        ";
    }
    echo $links;
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
    if (count($settings) > 4) {
        $settings = array_slice($settings,0,4);
    }
    while(count($settings) < 4){
        $settings[] = 1;
    }
    
    $json = array('seed' => $seed, 'dims' => $settings);
    return $json;
}