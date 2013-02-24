<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . '/nmaze/includes/main.php');
?>
<html>
	<head>
        <title>NMaze - WTF?</title>
	<head>
	<body>
        <div id="header_container">
            <?php include("header.php");?>
        </div>
        <div id="faq">
            <h3>What(TF) is this?</h3>
            <p>NMaze is a game about mazes.  Random mazes, any size and shape you want.  Even more, the mazes are not flat, 2-dimensional paths cut out of a cornfield.  Your paths can lap over each other into the 3rd dimension.  And even more, they can veer off into a 4th dimension.  And a 5th, and a 6th.  <strong>ANY NUMBER OF DIMENSIONS.</strong>  It is recommended that you start with only three or four.  Or even better, try <a href="/nmaze/tutorial/">the tutorial.</a></p>
            <h3>I beat one.  Now what?</h3>
            <p>Play again.  Make a completely different maze, or play the same shape with a different random seed.  Copy the url and send it to your smartass friend.  Mock him for his failure.  Repeat!</p>
            <h3>How big can I make a maze?</h3>
            <p>There is no hard limit to the size of generated mazes.  It takes a few seconds to produce a 200x200 2d maze.  I have grand hopes of doing some profiling for various absurd configurations, to see how much ram you will burn in the process.</p>
            <p>Until that gets done, I recommend trial and error.  It is my unfounded belief that the only relevant number in terms of maze generation is the total number of cells, which is the product of the sizes of each dimension used.  There is a lot of recursion for lots of dimensions, though.  Try it and see!</p>
            <h3>Can I play this game using internet browser {insert crappy browser here}?</h3>
            <p>Probably not.  Maybe.  I haven't tried.  Here's the thing, this site is almost 100% javascript.  I made no effort to optimize it whatsoever.  If you attempt to run it in IE6, your computer will probably start on fire.  If you even have to ask this question, you are using a really crappy browser.  <a href="http://www.google.com/chrome/" target="_blank">Download Chrome, for the love of everything holy.</a></p>
            <h3>How to brag?</h3>
            <p>You have to do it yourself.  :(  Coming soon: <ul><li>Link-sharing aides</li><li>Leaderboards</li><li>Replays</li></ul></p>
        </div>
	</body>
</html> 