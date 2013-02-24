<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . '/nmaze/includes/main.php');
?>
<html>
	<head>
        <?php nm_head_links(); ?>
		<script type="text/javascript">
            <?php $json = array('seed' => 68, 'dims' => array(12, 12));?>
			var nMaze = new NMaze(<?php echo json_encode($json); ?>);
            var table_display = new TableDisplay(nMaze, "maze_container", "controls_container");
			
			var init = function (){ table_display.init(); };
			document.onreadystatechange = function(){
				if (document.readyState == "complete"){
					init();
				}
			};
            
            var tutorial_3 = function(){
                var endCell = table_display.nMaze.getEndCell();
                endCell.onEnter = function(){
                    alert("ALL TESTS PASSED!");
                    document.getElementById("messages").innerHTML =
                        document.getElementById("messages_4").innerHTML;
                    
                    
                }
            }
            
            
            var tutorial_2 = function () {
                
                var endCell = table_display.nMaze.getEndCell();
                
                endCell.onEnter = function (){
                    alert("ZOOMG!  Thank you for being alive, super-genius!");
                    nMaze = new NMaze({seed:34, dims : [4,4,3,3]});
                    table_display = new TableDisplay(nMaze, "maze_container", "controls_container");
                    table_display.init();
                    document.getElementById("messages").innerHTML =
                        document.getElementById("messages_3").innerHTML;
                    tutorial_3();
                    
                }
            };
            
            
            var tutorial_1 = function () {
            
                var endCell = table_display.nMaze.getEndCell();
                
                endCell.onEnter = function (){
                    alert("Great Job!  1000 gamer points to you!");
                    nMaze = new NMaze({seed: 21, dims: [4, 12, 3]});
                    table_display = new TableDisplay(nMaze, "maze_container", "controls_container");
                    table_display.init();
                    document.getElementById("messages").innerHTML =
                        document.getElementById("messages_2").innerHTML;
                    tutorial_2();
                };
            };
            
            
            tutorial_1();
            
			
		</script>
        <title>NMaze - n-dimensional tutorial</title>
	<head>
	<body>
        <div id="header_container">
            <?php include("header.php");?>
        </div>
        <div id="maze_container">
        </div>
        <div id="messages">
            <p>Hello!  Here is a basic 2D maze.  Easy!</p>
            <p>Your goal is to move your astronaut <img src="/nmaze/img/man.png" style="width:20px;"/> to the exit <img src="/nmaze/img/exit.jpg" style="width:20px;" />  You can move using the buttons or the arrow keys.</p><p>Get to the exit to continue!</p>
        </div>
        <div id="messages_2" style="display:none">
            <p>Job well done, astronaut.  You'll be able to manage a large team of technical experts in no time!  Let's attempt something more difficult: the 3D maze!</p>
            <p>You will need to move in an additional direction to reach the exit.  You can click the new buttons, or you can use the keys shown above them.</p>
            <p>Passages available through this extra dimensions will be marked with one or both of the stair icons <img src="/nmaze/img/stairs.png" style="width:20px;" />.  Go, Go, Go!</p>
        </div>
        <div id="messages_3" style="display:none;">
            <p>Astronaut.  Your super-human performance requires only one more test.  <strong>4 Dimensions!</strong></p>
            <p>This new dimension has new button and keyboard shortcuts.  The passages are marked with red and green, which is coincidentally the name of the directions in the dimension through which you will be moving.  Don't try to wrap your head around it.  Even your amazing intellect can't envision it.  Just get to the exit.</p>
            <p>If you get stuck...you have <a href="/nmaze/?5-5-3-20">your cyanide capsule.</a></p>
        </div>
        <div id="messages_4" style="display:none">
            <p>You are ready.  <a href="/nmaze/?4-4-4-4-50">Show the universe what you can do.</a></p>
        </div>"
        <div id="controls_container">
        </div>
	</body>
</html>