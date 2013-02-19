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
	var _recurse = function (self, start)
	{
		var item = [];
		for (var i = 0; i < self.dims[start]; i++){
		
			if ((start +1) == self.dims.length){
				
				var _paths = [];
				for (var j = 0; j < self.dims.length; j++){
					_paths[j] = [false, false];
				}
				item[i] = {connected: false, paths : _paths};
				
			} else {
                
				item[i] = _recurse(self, start + 1);
				
			}
			
		}
		return item;
	}
	
	this.maze = _recurse(this, 0);
	
    for
    
	
	
	
}