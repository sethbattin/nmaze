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
			
            var _doc_init = function() {
                
                var newSeed = function(){
                    var r = (Math.random() * 16000).toString().split(".")[0];
                    document.getElementById('maze_seed').value=r;
                };
                
                var _getDims = function(){
                    var inputs = this.getElementsByTagName('input');
                    var dims = [];
                    for (var d in inputs){
                        if (typeof(inputs[d].getAttribute) == "function"){
                            var c = inputs[d].getAttribute('class');
                            if (typeof(c) != "undefined" && c != null && c.indexOf('maze_dim') > -1){
                                dims.push(inputs[d]);
                            }
                        }
                    }
                    return dims;
                };
                
                
                //this.addDim = function(){
                //    var dims = _getDims.apply(this);
                //    var nextDim = dims.length;
                //    var addition = '<label for="dim_' + nextDim + '">Dimension '
                //        + (nextDim + 1) + ':</label><input id="dim_' + nextDim
                //        + '" class="maze_dim" name="maze_dims[' + nextDim
                //        + ']" value="2" /><br />';
                //    
                //    var brtag = dims[dims.length - 1].nextSibling;
                //    var nexttag = brtag.nextSibling;
                //    
                //    dims[dims.length - 1].parentNode.insertBefore(addition, nexttag);
                //};
                
                var newMaze = function(){
                    var param = "?";
                    var dims = _getDims.apply(this);
                    for (var d in dims){
                        param += "-" + dims[d].value;
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
                    return true;
                    
                };
                
                this.getElementById("new_maze").onclick =
                    function(){ newMaze.apply(document) };
                this.getElementById("seed_roll").onclick =
                    function(){ newSeed.apply(document) };
                
            };
            
            
			var init = function (){
                table_display.init();
                _doc_init.apply(document);
            };
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
                <input id="seed_roll" type="button" style=""/></br>
                <?php foreach ($json['dims'] as $i => $v){ ?>
                <label for="dim_<?= $i ?>">Dimension <?= ($i + 1) ?>:</label>
                <input id="dim_<?= $i ?>" class="maze_dim" name="maze_dims[<?= $i ?>]" value="<?= $v ?>" /><br />
                <?php } ?>
                <!--<input type="button" id="add_dim" value="Add Dimension" style="width:120px;" onclick="addDim()"/>-->
                <input id="new_maze" type="button" value="Make maze" />
            </div>
        </div>
        <div id="controls_container">
        </div>
	</body>
</html>