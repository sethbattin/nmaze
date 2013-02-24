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
                <input id="maze_seed" class="maze_seed" name="maze_seed" value="<?php echo $json['seed']; ?>" /><br />
                <?php foreach ($json['dims'] as $i => $v){ ?>
                <label for="dim_<?= $i ?>">Dimension <?= ($i + 1) ?>:</label>
                <input id="dim_<?= $i ?>" class="maze_dim" name="maze_dims[<?= $i ?>]" value="<?= $v ?>" /><br />
                <?php } ?>
                <input type="button" value="Make maze"/>
            </div>
        </div>
        <div id="controls_container">
        </div>
	</body>
</html>