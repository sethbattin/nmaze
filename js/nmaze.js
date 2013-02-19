
// thanks, SO.
// http://stackoverflow.com/a/6700/1004027
Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};

NMaze = function(options){

	if (typeof(options) == "undefined"){
		options = {};
	}

	if (typeof(options.seed) == "undefined"){
		options.seed = "0";
	}
	if (typeof(Math.seedrandom) !== "function"){
		throw "Seedrandom library is unavailable.";
	}
	Math.seedrandom(options.seed);
	
	if (typeof(options.dims) == "undefined"){
		options.dims = [5, 5];
	}
	this.dims = options.dims;
	
    // build empty data structure
    // nested arrays of depth == dims/length
    // each object at bottom of structure properties: connectedness and paths
    // connectedness indicates the cell is connected to the reachable areas of the maze
    // paths indicate each dimension's possible open paths
	var _recurse = function (self, dim, pos)
	{
		var item = [];
		for (var i = 0; i < self.dims[dim]; i++){
		
			var _pos =  pos.concat([i]);
	
			if ((dim + 1) == self.dims.length){
				
				var _paths = [];
				for (var j = 0; j < self.dims.length; j++){
					_paths[j] = [false, false];
				}
				
				//silly javascript closures...
				var getN = (function (p){
					var _f = function(){
						var neighbors = [];
						for (var j = 0; j < self.dims.length; j++){
							var _temp = p.slice(0);
							if (p[j] > 0){
								_temp[j] = p[j] - 1;
								neighbors.push(self.getCell.apply(self,_temp));
							}
							if (p[j] < (self.dims[j] - 1)){
								_temp[j] = p[j] + 1;
								neighbors.push(self.getCell.apply(self,_temp));
							}
						}
						return neighbors;
					}
					return _f;
				})(_pos);
				
				var isN = function (cell){
					var result = false;
					var neighbors = getN();
					for (var n in neighbors){
						for (var p in neighbors[n].position){
							if (neighbors[n].position[p] == cell.position[p]){
								result = true;
							}
						}
					}
					return result;
				};
				
				item[i] = {
					connected: false, 
					paths : _paths, 
					position: _pos,
					getNeighbors: getN,
					isNeighbor: isN
				};
				
			} else {                
				item[i] = _recurse(self, dim + 1, _pos);
			}
			
		}
		return item;
	}
	
	this.maze = _recurse(this, 0, []);
	
	this.getCell = function(){
		var args = Array.prototype.slice.call(arguments);
		if (args.length != this.dims.length){
			return undefined;
		}
		
		var _cell;
		
		for (var i =0; i < this.dims.length; i++){
			if (i > 0){
				_cell = _cell[args[i]];
			} else {
				_cell = this.maze[args[i]];
			}
		}		
		return _cell;
	}
	
	this.openPath = function(cell1, cell2){
		if (!cell1.isNeighbor(cell2)){
			return false;
		}
		for (var p in cell1.position){
			if (cell1.position[p] > cell2.position[p]){
				cell1.paths[p][0] = true;
				cell2.paths[p][1] = true;
				cell2.connected = true;
			}
			if (cell1.position[p] < cell2.position[p]){
				cell1.paths[p][1] = true;
				cell2.paths[p][0] = true;
				cell2.connected = true;
			}
		}
	};
	
	
	var primStack = {};
	var randomStart = [];
	for (var i = 0; i < this.dims.length; i++){
		var r = Math.random() * this.dims[i];
		randomStart.push(r.toString().split(".")[0]);
	}
	this.getCell.apply(this, randomStart).connected = true;
	
	primStack[randomStart.join(",")] = randomStart;
	
	
	while(Object.size(primStack) > 0){
		var rooms = [];
		for (var cell in primStack){
			rooms.push(cell);
		}
		var r = (Math.random() * rooms.length).toString().split(".")[0];
		
		var selectedRoom = this.getCell.apply(this,(primStack[rooms[r]]));
		var neighbors = selectedRoom.getNeighbors();
		var _unconnecteds = [];
		for (var i in neighbors){
			if (!neighbors[i].connected){
				_unconnecteds.push(neighbors[i]);
			}
		}
		if (_unconnecteds.length == 0){
			delete primStack[rooms[r]];
		} else {
			var o = (Math.random() * _unconnecteds.length).toString().split(".")[0];
			this.openPath(selectedRoom, _unconnecteds[o]);
			primStack[_unconnecteds[o].position.join(",")] = _unconnecteds[o].position;
			if (_unconnecteds.length == 1){
				delete primStack[rooms[r]];
			}
		}
	}
	
		
	//open start and end.
	(function (self){
		var _b = self.getCell(0,0);
		var _e = self.getCell(4,4);
		_b.paths[0][0] = true;
		_e.paths[0][1] = true;
		
	})(this);
	
	
	

	
	
	
}