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
	
	var _recurse = function (self, start)
	{
		var item = [];
		for (var i = 0; i < self.dims[start]; i++){
		
			if ((start +1) == self.dims.length){
				
				var paths = [];
				for (var j = 0; j < self.dims.length; j++){
					paths.push(false, false);
				}
				item[i] = paths;
				
			} else {
			
				item[i] = _recurse(self, start + 1);
				
			}
			
		}
		return item;
	}
	
	this.maze = _recurse(this, 0);
	
	
	
	
}