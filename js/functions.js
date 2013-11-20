function getTime(t){
	var mod = t % 60;
	var i = parseInt(t/60);
	var flag = 0;
	if(i > 12){
		i = i - 12;
		flag = 1;
	}
	if(mod < 10){
		var str = i + ':' + '0' + mod;
	}
	else{
		var str = i + ':' + mod;
	}
	if(flag){
		return str += 'pm';
	}
	else{
		return str += 'am';
	}
}
function daysInMonth(year,month){
	return new Date(year, month, 0).getDate();
}
function rgbToHex(string){
	if(!string || typeof string !== 'string') return false;
    if(string.substring(0,3) == 'rgb')
    {
        string = string.substring(4, string.length - 1).split(',');
        if(string.length == 3 && string[0] != '' && string[1] != '' && string[2] != '')
        {
            if(
                string[0] >= 0 && string[0] <= 255 && 
                string[1] >= 0 && string[1] <= 255 && 
                string[2] >= 0 && string[2] <= 255
            ){
                return ('#' + 
                    parseInt(string[0]).toString(16) +
                    parseInt(string[1]).toString(16) + 
                    parseInt(string[2]).toString(16)
                ).toUpperCase();
            }
            else return false;
        }
       	else return false;
    }
    else return false;
}
function hexOrRgb(string){
    if(!string || typeof string !== 'string') return false;
    if(
        string.substring(0,1) == '#' && 
        (string.length == 4 || string.length == 7) && 
        /^[0-9a-fA-F]+$/.test(string.substring(1, string.length))
    ){
        string = string.substring(1, string.length);
        if(string.length == 3) 
            string = string[0] + string[0] + string[1] + string[1] + string[2] + string[2];
        return 'rgb(' + 
            parseInt(string[0] + string[1], 16).toString() + ',' + 
            parseInt(string[2] + string[3], 16).toString() + ',' + 
            parseInt(string[4] + string[5], 16).toString() + 
        ')';
    }
    else if(string.substring(0,3) == 'rgb')
    {
        string = string.substring(4, string.length - 1).split(',');
        if(string.length == 3 && string[0] != '' && string[1] != '' && string[2] != '')
        {
            if(
                string[0] >= 0 && string[0] <= 255 && 
                string[1] >= 0 && string[1] <= 255 && 
                string[2] >= 0 && string[2] <= 255
            ){
                return ('#' + 
                    parseInt(string[0]).toString(16) + 
                    parseInt(string[1]).toString(16) + 
                    parseInt(string[2]).toString(16)
                ).toUpperCase();
            }
            else return false;
        }
        else return false;
    }
    else return false;
}
function areXYInside(e){  
        var w=e.target.offsetWidth;
        var h=e.target.offsetHeight;
        var x=e.offsetX;
        var y=e.offsetY;
        return !(x<0 || x>=w || y<0 || y>=h);
}