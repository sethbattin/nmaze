<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . '/nmaze/includes/main.php');
?>
<html>
	<head>
        <?php nm_head_links(); ?>
		<script type="text/javascript">
            <?php $json = nm_get_settings(); ?>
			var nMaze = new NMaze(<?php echo json_encode($json); ?>);
            var table_display = new TableDisplay(nMaze, "maze_container", "controls_container");
			
			var init = function (){ table_display.init(); };
			document.onreadystatechange = function(){
				if (document.readyState == "complete"){
					init();
				}
			};
			
		</script>
        <title>NMaze - n-dimensional mazes</title>
	<head>
	<body>
        <div id="header_container">
            <?php include("header.php");?>
        </div>
        <div id="maze_block">
            <div id="maze_container">
            </div>
            <div id="maze_info">
                <label for="maze_seed">Random Seed:</label>
                <input id="maze_seed" class="maze_seed" name="maze_seed" value="<?php echo $json['seed']; ?>" />
                <input type="button" value="New" onclick="newSeed()" /></br>
                <script type="text/javascript">
                    function newSeed(){
                        var r = (Math.random() * 16000).toString().split(".")[0];
                        document.getElementById('maze_seed').value=r; }
                    function newMaze(){
                        var param = "?";
                        var dims = document.getElementsByTagName('input');
                        for (var d in dims){
                            if (typeof(dims[d].getAttribute) == "function"){
                                var c = dims[d].getAttribute('class');
                                if (typeof(c) != "undefined" && c != null && c.indexOf('maze_dim') > -1){
                                    param += "-" + dims[d].value;
                                }
                            }
                        }
                        param += "-" + document.getElementById('maze_seed').value;
                        var path = document.location.protocol + "//" +
                            document.location.hostname +
                            document.location.pathname +
                            param;
                            
                        
                        if (confirm("New maze?")){
                            document.location = path;
                        } else {
                            return false;
                        }
                        
                    }
                </script>
                <br />
                <?php foreach ($json['dims'] as $i => $v){ ?>
                <label for="dim_<?= $i ?>">Dimension <?= ($i + 1) ?>:</label>
                <input id="dim_<?= $i ?>" class="maze_dim" name="maze_dims[<?= $i ?>]" value="<?= $v ?>" /><br />
                <?php } ?>
                <input type="button" value="Make maze" style="width:100px;" onclick="newMaze()"/>
            </div>
        </div>
        <div id="controls_container">
        </div>
	</body>
</html>