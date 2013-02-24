<html>
	<head>
		<link type="text/css" rel="stylesheet" href="nmaze.css"></link>
		<script type="text/javascript" src="js/seedrandom.js"></script>
		<script type="text/javascript" src="js/nmaze.js"></script>
        <script type="text/javascript" src="js/tabledisplay.js"></script>
		<script type="text/javascript">
		
			// this object's purpose is to connect the core maze logic to the
			// table display format.   It implements an interface that all
			// display formats require in order to be used by the core.
            <?php
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
            ?>
            
			var nMaze = new NMaze(<?php echo json_encode($json); ?>);
            var table_display = new TableDisplay(nMaze);
			
			var init = function (){
				table_display.init();
                
			}
			
			document.onreadystatechange = function(){
				if (document.readyState == "complete"){
					init();
				}
			};
			
		</script>
	<head>
	<body>
    
        <div id="maze_container">
        </div>
        <div id="controls_container">
        <!--
            <input type="button" name="up" value="up" onclick="move(this.value)" />
            <input type="button" name="down" value="down" onclick="move(this.value)" />
            <input type="button" name="left" value="left" onclick="move(this.value)" />
            <input type="button" name="right" value="right" onclick="move(this.value)" />
            -->
        </div>
	</body>
</html>