<html>
	<head>
		<link type="text/css" rel="stylesheet" href="nmaze.css"></link>
		<script type="text/javascript" src="/nmaze/js/seedrandom.js"></script>
		<script type="text/javascript" src="js/nmaze.js"></script>

		<script type="text/javascript">

			// this object's purpose is to connect the core maze logic to the
			// table display format.   It implements an interface that all
			// display formats require in order to be used by the core.
			var nMaze = new NMaze();
			var table_display = new (function (nMaze){
				this.nMaze = nMaze;
				// TODO: validate maze object

				this.getDimensionCount = function (){
					return 2;
				};
				this.getDimensions = function(){
					return [5,5];
				};
				this.getDirections = function(){
					return [["up", "down"], ["left", "right"]];
				};
				this.getDirVector = function (name){
					var _return = new Array();
					var dirs = this.getDirections();
					for (var i = 0; i < this.getDimensionCount(); i++){
						_return.push(0);
					}
					for (var i = 0; i < this.getDimensionCount(); i++){
						if (dirs[i][0] == name) { _return[i] = -1; break;}
						if (dirs[i][1] == name) { _return[i] = 1; break;}
					}
					return _return;
				};
				this.validateMove = function(x1, y1, x2, y2){
					var args = Array.prototype.slice.call(arguments);
					var dims = this.getDimensions();
					console.log(dims);
					if ((typeof(x1) == 'undefined') ||
						(typeof(y1) == 'undefined') ||
						(typeof(x2) == 'undefined') ||
						(typeof(y2) == 'undefined') ||
						(x1 < 0 || y1 < 0 || x2 < 0 || y2 < 0) ||
						(x1 >= dims[0] || x2 >= dims[0]) || 
						(y1 >= dims[1] || y2 >= dims[1]) || 
						((Math.abs(x1 - x2) + Math.abs(y1 -y2)) != 1) 
					){
						return false;
					}
					return this.nMaze.hasPathXY(x1, y1, x2, y2);
				};
				this.getTable = function(){
					return document.getElementById("table_display_maze");
				};
				this.getCell = function (){
					var args = Array.prototype.slice.call(arguments);
					var table = this.getTable();
					var cell = undefined;
					var getRow = function (i) {
						var rows = table.getElementsByTagName("tr");
						var row = undefined;
						if (rows.length > i){
							row = rows[i];
						}
						return row;
					};
					var getRowTd = function (row, i){
						var cells = row.getElementsByTagName("td");
						var cell = undefined;
						if (cells.length > i ){
							cell = cells[i];
						}
						return cell;
					};
					if (args.length != this.getDimensionCount()){
					} else {
						cell = getRowTd(getRow(args[1]),args[0]);
					}
					return cell;
				};
				this._classifyCell = function (x1, y1, _class){
					//it sure would be nice to be using jQuery right now.
					var cell = this.getCell(x1, y1);
					var currentClass = cell.getAttribute('class');
					if ((typeof(currentClass) == "undefined") ||
						((typeof(currentClass) == "object") && (currentClass == null))
					){
						currentClass = _class;
					} else if (typeof(currentClass == "string")){
						currentClass += " " + _class;
					}
					cell.setAttribute('class', currentClass);
				};
				this._declassifyCell = function (x1, y1, _class){
					var cell = this.getCell(x1, y1);
					var currentClass = cell.getAttribute('class');
					if ((typeof(currentClass) == "undefined") ||
						((typeof(currentClass) == "object") && (currentClass == null))
					){
						return;
					} else if (typeof(currentClass == "string")){
						currentClass = currentClass.split(" ");
						for (var i; i < currentClass.length; i++){
							if (currentClass[i] == _class){
								currentClass[i] = "";
							}
						}
					}
					cell.setAttribute('class', currentClass.join(" "));

				};

				this.init = function(){
					//expects 2d maze

					var dirs = this.getDirections();

					for (var i = 0; i < this.nMaze.dims[0]; i++){
						for (var j = 0; j < this.nMaze.dims[1]; j++){
							var cell = this.nMaze.getCell(i, j);
							for (var k = 0; k < this.nMaze.dims.length; k++){
								for (var l = 0; l < 2; l++){
									if (cell.paths[k][l]){
										var _class = "o_" + dirs[k][l];
										this._classifyCell(j, i, _class);
									}
								}
							}
						}
					}
					/*
					for (var i in cell.paths){
						for (var j in cell.paths[i]){
							if (cell.paths[
						}
					}
					
					
					if ((x2 - x1) == -1){
						this._classifyCell(x1, y1, "o_left");
						this._classifyCell(x2, y2, "o_right");
					}else if ((x2 - x1) == 1){
						this._classifyCell(x1, y1, "o_right");
						this._classifyCell(x2, y2, "o_left");
					}else if ((y2 - y1) == -1){
						this._classifyCell(x1, y1, "o_top");
						this._classifyCell(x2, y2, "o_bott");
					}else if ((y2 - y1) == 1){
						this._classifyCell(x1, y1, "o_bott");
						this._classifyCell(x2, y2, "o_top");
					} else {
						return false;
					}
					
					return true;
					*/

				};
			})(nMaze);

			var init = function (){
				table_display.init();
			}

			document.onreadystatechange = function(){
				if (document.readyState == "complete"){
					init();
				}
			};

			var man = {x: 0, y:0};

			var up_move = function(){
				result = table_display.validateMove(man.x, man.y, man.x, man.y - 1);
				if (result){
					table_display.
						getCell(man.x, man.y).
						getElementsByTagName('div')[0]
						.setAttribute('class', 'cell');
					man.y = man.y - 1;					
					table_display.
						getCell(man.x, man.y).
						getElementsByTagName('div')[0]
						.setAttribute('class', 'cell man');					
				}
			}
			var down_move = function(){
				result = table_display.validateMove(man.x, man.y, man.x, man.y + 1);
				if (result){
					table_display.
						getCell(man.x, man.y).
						getElementsByTagName('div')[0]
						.setAttribute('class', 'cell');
					man.y = man.y + 1;					
					table_display.
						getCell(man.x, man.y).
						getElementsByTagName('div')[0]
						.setAttribute('class', 'cell man');					
				}
			}
			function left_move(){
				result = table_display.validateMove(man.x, man.y, man.x - 1, man.y);
				if (result){
					table_display.
						getCell(man.x, man.y).
						getElementsByTagName('div')[0]
						.setAttribute('class', 'cell ');
					man.x = man.x - 1;					
					table_display.
						getCell(man.x, man.y).
						getElementsByTagName('div')[0]
						.setAttribute('class', 'cell man');					
				}
			}
			function right_move(){
				result = table_display.validateMove(man.x, man.y, man.x + 1, man.y);
				if (result){
					table_display.
						getCell(man.x, man.y).
						getElementsByTagName('div')[0]
						.setAttribute('class', 'cell ');
					man.x = man.x + 1;					
					table_display.
						getCell(man.x, man.y).
						getElementsByTagName('div')[0]
						.setAttribute('class', 'cell man');					
				}
			}
			document.onkeydown = function(event){
				var chCode = (('charCode' in event) && (event.charCode != 0)) ? event.charCode : event.keyCode;
				console.log(chCode);
				switch (chCode){
					case 38:
						up_move();
						break;
					case 40:
						down_move();
						break;
					case 37:
						left_move();
						break;
					case 39:
						right_move();
						break;
					default:
						break;
				}
			}

		</script>
	<head>
	<body>
		<table id="table_display_maze">
			<tr>
				<td><div class='cell man'></td>
				<td><div class='cell'></td>
				<td><div class='cell'></td>
				<td><div class='cell'></td>
				<td><div class='cell'></td>
			</tr>
			<tr>
				<td><div class='cell'></td>
				<td><div class='cell'></td>
				<td><div class='cell'></td>
				<td><div class='cell'></td>
				<td><div class='cell'></td>
			</tr>
			<tr>
				<td><div class='cell'></td>
				<td><div class='cell'></td>
				<td><div class='cell'></td>
				<td><div class='cell'></td>
				<td><div class='cell'></td>
			</tr>
			<tr>
				<td><div class='cell'></td>
				<td><div class='cell'></td>
				<td><div class='cell'></td>
				<td><div class='cell'></td>
				<td><div class='cell'></td>
			</tr>
			<tr>
				<td><div class='cell'></td>
				<td><div class='cell'></td>
				<td><div class='cell'></td>
				<td><div class='cell'></td>
				<td><div class='cell'></td>
			</tr>
		</table>
		<input type="button" name="up" value="up" onclick="up_move()" />
		<input type="button" name="down" value="down" onclick="down_move()" />
		<input type="button" name="left" value="left" onclick="left_move()" />
		<input type="button" name="right" value="right" onclick="right_move()" />
	</body>
<html>
