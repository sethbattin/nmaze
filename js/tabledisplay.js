
// this object's purpose is to connect the core maze logic to the
// table display format.   It implements an interface that all
// display formats require in order to be used by the core.

var TableDisplay = function (nMaze, mazeContId, controlsContId){
    
    this.nMaze = nMaze;
    // TODO: validate maze object
    this.getDimensions = function(){
        return this.nMaze.dims;
    };
    this.getDirections = function(){
        return [
            ["left", "right"],
            ["up", "down"],
            ['in', 'out'],
            ['green', 'red'],
            ['poop', 'jeff'],
            ['begin','end'],
            ['evil', 'good']
        ];
    };
    this.getDirVector = function (name){
        var _return = new Array();
        var dirs = this.getDirections();
        for (var i = 0; i < this.getDimensions().length; i++){
            _return.push(0);
        }
        for (var i = 0; i < this.getDimensions().length; i++){
            if (dirs[i][0] == name) { _return[i] = -1; break;}
            if (dirs[i][1] == name) { _return[i] = 1; break;}
        }
        return _return;
    };
    this.validateMove = function(x1, y1, x2, y2){
        var args = Array.prototype.slice.call(arguments);
        var dims = this.getDimensions();
        
        if (args.length != (dims.length * 2)){
            return undefined;
        }
        
        var coords1 = [];
        var coords2 = [];
        
        for (var i = 0; i < dims.length; i++){
            coords1.push(args.shift());
        }
        
        for (var i = 0; i < dims.length; i++){
            coords2.push(args.shift());
        }
        for (var i = 0; i < dims.length; i++){
            if (coords1[i] >= dims[i] ||
                coords2[i] >= dims[i] ||
                coords1[i] < 0 ||
                coords2[i] < 0){
                return false;
            }
        }
        return this.nMaze.hasPathXY.apply(this.nMaze, coords1.concat(coords2));
        //return this.nMaze.hasPathVY(x1, y1, x2, y2);
    };
    this.getTable = function(){
        var args = Array.prototype.slice.call(arguments);
        
        var table;
        
        if ((args.length % 2) == 1){
            args.push(0);
        }
        
        tables = document.getElementsByTagName('table');
        
        while (args.length > 2){
            var teir = ((args.length /2) - 1 );
            var topTable;
            for (var t in tables){
                if ((' ' + tables[t].className + ' ')
                    .indexOf(' t-' + teir + ' ') > -1)
                {
                    topTable = tables[t];
                    break;
                }
            }
            var row = args.pop();
            var td = args.pop();
            
            var tables = topTable.rows[row].cells[td].
                getElementsByTagName('table');
            
            
        }
        
        return tables[0];
    };
    this.getCell = function (){
        var args = Array.prototype.slice.call(arguments);
        var cell = undefined;
        
        if (args.length != this.getDimensions().length){
            return cell;
        }
        
        var table = this.getTable.apply(this, args);
        var getRow = function (i) {
            var rows = table.getElementsByTagName("tr");
            var row = undefined;
            if (rows.length > i){
                row = rows[i];
            }
            return row;
        };
        var getRowTd = function (i,row){
            var cells = row.getElementsByTagName("td");
            var cell = undefined;
            if (cells.length > i ){
                cell = cells[i];
            }
            return cell;
        };
        
        cell = getRowTd(args[0], getRow(args[1]));
        
        return cell;
    };
    this._classifyCell = function (){
        var args = Array.prototype.slice.call(arguments);
        var _class = args.pop();
        //it sure would be nice to be using jQuery right now.
        var cell = this.getCell.apply(this, args);
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
    this._declassifyCell = function (){
        var args = Array.prototype.slice.call(arguments);
        var _class = args.pop();
        var cell = this.getCell.apply(this, args);
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
    
    

    var man = new (
        function(tDisplay)
        {
            this.position = [];
            
            for (var i in tDisplay.getDimensions()){
                this.position.push(0);
            }
            this.move = function(direction){
                var vector = tDisplay.getDirVector(direction);
                var valid = 0;
                var newPos = [];
                for (var v in vector){
                    valid += vector[v];
                    newPos[v] = this.position[v] + vector[v];
                }
                if (!Math.abs(valid) == 1){
                    return;
                }
                var result = tDisplay.validateMove.apply(tDisplay, this.position.concat(newPos));
                if (result){
                    tDisplay.
                        getCell.apply(tDisplay, this.position)
                        .getElementsByTagName('div')[0]
                        .setAttribute('class', 'cell');
                    man.position = newPos;
                    tDisplay.
                        getCell.apply(tDisplay, this.position)
                        .getElementsByTagName('div')[0]
                        .setAttribute('class', 'cell man');
                    
                }
                        
                var newCell = tDisplay.nMaze.getCell.apply(tDisplay.nMaze, this.position);
                if (('onEnter' in newCell) && (typeof(newCell.onEnter) == "function")){
                    newCell.onEnter();
                }
            }
        }
    )(this);
    
    this.init = function(){
        //expects 2d maze
        
        var dirs = this.getDirections();
        var dims = this.getDimensions();
        
        var _applyClasses = function(values){
            var cell = this.nMaze.getCell.apply(this.nMaze, values);
            for (var j = 0; j < this.nMaze.dims.length; j++){
                for (var k = 0; k < 2; k++){
                    if (cell.paths[j][k]){
                        var _class = "o_" + dirs[j][k];
                        this._classifyCell.apply(this, values.concat(_class));
                    }
                }
            }
        };
        
        var _buildTable = function(_args, content, iteration){
        
            var args = _args.slice(0);
            if ((typeof(args) != "undefined") && (args.length == 1)){
                args.push(1);
            }
        
            var td = "<td>" + content + "</td>";
            var tr = "<tr>";
            var tdReps = args.shift();
            for (var i = 0; i < tdReps; i++){
                tr += td + "\n";
            }
            tr += "</tr>";
            
            var trReps = args.shift();
            
            var table = "<table class='t-" + iteration + "'>";
            
            for (var i = 0; i < trReps; i++){
                table += tr + "\n";
            }
            table += "</table>";
            
            var result = undefined;
            
            if (args.length > 0){
                result = _buildTable.call(this, args, table, iteration + 1);
            } else {
                result = table;
            }
            
            return result;
        }
        
        var _buildArgs = function(dims, arg, values, _onBuild, _onBuildArgs){
            if (arg < dims.length){
                for (var i = 0; i < dims[arg]; i++){
                    _buildArgs.call(this, dims, arg + 1, values.concat(i), _onBuild, _onBuildArgs);
                }
            } else {
                _onBuild.apply(this, [values].concat(_onBuildArgs));
            }
        };
        
        var structure = _buildTable.call(this, this.getDimensions(), "<div class='cell'></div>",0);
        document.getElementById(mazeContId).innerHTML = structure;
        
        _buildArgs.call(this, dims, 0, [], _applyClasses, []);
        var letters = ["Left", "Right", "Up", "Down", "Q","A","W","S","E","D","R","F","T","G"];
        var controls = "";
        var dirs = this.getDirections();
        for (var dir in dirs){
            if (dir >= this.getDimensions().length){
                break;
            }
            controls += "<fieldset id='c" + dir +
                "'><legend for='c" + dir + "'>" +
                letters[dir * 2] + "/" + letters[dir * 2 + 1] +
                "</legend>";
            for (var d in dirs[dir]){
                controls += "<input type='button' name='" +
                    dirs[dir][d] + "' value='" + dirs[dir][d] +
                    "' onclick='move(this.value)' />";
            }
            controls += "</fieldset>";
        }
        
        document.getElementById(controlsContId).innerHTML = controls;
        
        
        
        var end = this.getDimensions().slice(0);
        for (var v in end){
            end[v] = end[v] - 1;
        }
        this.getCell.apply(this, end)
            .setAttribute('class', 'exit');
            
        
        this.getCell.apply(this, man.position)
            .getElementsByTagName('div')[0]
            .setAttribute('class', 'cell man');
        
    };
    
    var move = function(button){
        if (typeof(button) == "string"){
            man.move(button);
        }else if ((typeof(button) == "object") && (button instanceof Array)){
            man.move(table_display.getDirections()[button[0]][button[1]]);
        }
    }
    
    document.onkeydown = function(event){
        var chCode = (('charCode' in event) && (event.charCode != 0)) ? event.charCode : event.keyCode;
        
        switch (chCode){
            case 37:
                move([0,0]);
                event.preventDefault();
                break;
            case 39:
                move([0,1]);
                event.preventDefault();
                break;
            case 38:
                move([1,0]);
                event.preventDefault();
                break;
            case 40:
                move([1,1]);
                event.preventDefault();
                break;
            case 81:
                move([2,0]);
                break;
            case 65:
                move([2,1]);
                break;
            case 87:
                move([3,0]);
                break;
            case 83:
                move([3,1]);
                break;
            case 69:
                move([4,0]);
                break;
            case 68:
                move([4,1]);
                break;
            case 82:
                move([5,0]);
                break;
            case 70:
                move([5,1]);
                break;
            default:
                break;
        }
    }

};