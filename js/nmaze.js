
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
		options.seed = 0;
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
		
			var _pos =  pos.slice(0);
            _pos.unshift(i);
	
			if (dim == 0){
				
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
				item[i] = _recurse(self, dim - 1, _pos);
			}
			
		}
		return item;
	}
	
	this.maze = _recurse(this, this.dims.length - 1, []);
	
	this.getCell = function(){
		var args = Array.prototype.slice.call(arguments);
		if (args.length != this.dims.length){
			return undefined;
		}
		for (var i in args){
			if (args[i] >= this.dims[i] || args[i] < 0){
				return undefined;
			}
		}
		
		var _cell;
		
		for (var i = this.dims.length; i > 0; i--){
			if (i == this.dims.length){
				_cell = this.maze[args[i - 1]];
			} else {
				_cell = _cell[args[i - 1]];
			}
		}		
		return _cell;
	};
	
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
	this.hasPathXY = function (){
		var args = Array.prototype.slice.call(arguments);
		if (args.length != (2 * this.dims.length)){
			return undefined;
		}
		var cell1Args = [];
		var cell2Args = [];
		for (var i = 0; i < args.length; i++){
			if (i < this.dims.length){
				cell1Args.push(args[i]);
			} else {
				cell2Args.push(args[i]);
			}
		}
		
		var cell1 = this.getCell.apply(this, cell1Args);
		var cell2 = this.getCell.apply(this, cell2Args);
		return this.hasPath(cell1, cell2);
	};
	this.hasPath = function (cell1, cell2){
		if (!cell1.isNeighbor(cell2)){
			return false;
		}
		var result;
		for (var p in cell1.position){
			if (cell1.position[p] > cell2.position[p]){
				result = (cell1.paths[p][0] && cell2.paths[p][1]);
			}
			if (cell1.position[p] < cell2.position[p]){
				result = (cell1.paths[p][1] && cell2.paths[p][0]);
			}
		}
		return result;
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
	};
    this.getEndCell = function(){
        var c = [];
        for (var i = 0; i < this.dims.length; i++){
            c[i] = this.dims[i] - 1;
        }
        var endCell = this.getCell.apply(this, c);
        return endCell;
    };
	
		
	//open start and end.
    // why is this in a self executing anonymous function?  Who knows...
	(function (self){
        
		var _b = self.dims.slice(0);
		var _e = self.dims.slice(0);
        
        for (var i = 0; i < self.dims.length; i++){
            _b[i] = 0;
            _e[i] = self.dims[i] - 1;
        }
        _b = self.getCell.apply(self, _b);
        _e = self.getCell.apply(self, _e);
        
		_b.paths[0][0] = true;
		_e.paths[0][1] = true;
        _e.onEnter = function (){
            alert("victory");
        }
		
	})(this);
	
}