/**
 * Configure Object, define basic information of the system
 */
var config = {
	baseURL : '/videotours/',
	url : 'app/index'
};

/**
 * Extend String Object
 */
//upper first letter
String.prototype.upFirst = function(){
	var first = this.substr(0,1);
	return first.toUpperCase() + this.substr(1);
}
String.prototype.trim = function(){
	var str = "";
	var start = 0;
	var end = this.length;
	for(var i=0;i<this.length;i++){
		if(this[i] != " "){
			start = i;
			break;
		}
	}
	for(var i=this.length-1;i>=0;i--){
		if(this[i] != " "){
			end = i+1;
			break;
		}
	}
	return this.substring(start,end);
}
//calcuate width and height of string in doucment
String.prototype.size = function(){
	var span = $('<span></span>');
	$('body').append(span);
	span.html(this.toString());
	var width = span.width();
	var height = span.height();
	span.remove();
	return {'width':width,'height':height};
}
/**
 * Extend Array Object
 */
Array.prototype.explode = function(delimeter){
	var str = "";
	var length = this.length;
	for(var i=0;i<length;i++){
		if(typeof(this[i]) == "string")
			str += "'" + this[i] + "'" + delimeter;
		else
			str += this[i] + delimeter;
	}
	return str.substr(0,str.length-1);
}
Array.prototype.in_array = function(value){
	for(var i=0;i<this.length;i++){
		if(this[i] === value)
			return true;
	}
	return false;
}
Array.prototype.delValue = function(v){
	var i,j,temp;
	for(i=0;i<this.length;i++){
		if(this[i] == v)
			break;
	}
	for(j=i+1;j<this.length;j++,i++){
		temp = this[i];
		this[i] = this[j];
		this[j] = temp;
	}
	this.pop();
}
Array.prototype.delKey = function(k){
	var i,j,temp;
	for(i=0;i<this.length;i++){
		if(i == k)
			break;
	}
	for(j=i+1;j<this.length;j++,i++){
		temp = this[i];
		this[i] = this[j];
		this[j] = temp;
	}
	this.pop();
}
Array.prototype.reverse = function(){
	var arr = [];
	for(i = this.length-1;i>=0;i--){
		arr.push(this[i]);
	}
	return arr;
}
/**
 * Data filter Object , mainly support data selection 
 */
var filter = {
	filter1 : function(data,type){//data : response, type : xml,json,html,script
		
	}
};
/**
 * the root  object of the whole system
 */
var register = {
	components : {},
	controllers : {},
	getController : function(name){
		if(typeof(this.controllers[name])!="undefined")
			return this.controllers[name];
		return null;
	},
	setController : function(controller){
		var name = controller.name;
		if(name){
			this.controller[name] = controller;
		}
	}
};
function Storage(){
	
}
Storage.prototype.setItem = function(k,v,local){
	if(local != undefined && !!local){
		var db = window.localStorage;
	}
	else{
		var db = window.sessionStorage;
	}
	db.setItem(k,v);
}
Storage.prototype.removeItem = function(k,local){
	if(local != undefined && !!local){
		var db = window.localStorage;
	}
	else{
		var db = window.sessionStorage;
	}
	db.removeItem(k);
}
Storage.prototype.getItem = function(k,local){
	if(local != undefined && !!local){
		var db = window.localStorage;
	}
	else{
		var db = window.sessionStorage;
	}
	return db.getItem(k);
}
Storage.getInstance = function(){
	if(!!this.instance){
		return this.instance;
	}
	this.instance = new Storage();
	return this.instance;
}
/**
 * This is event handler of components, and responsible for fetching data from server to the components.
 * It is also a bridge of coordinating communications among components.
 */
function Controller(name){
	this.name = name;
	this.storage = Storage.getInstance();
	this.event = {};
	this.data = {};
	this.cache = true;
	this.my_success = null;
	this.my_error = null;
	this.ajax = {
		url : null,
		async : true,
		cache : false,
		data : null,
		dataFilter : null,
		error : this.error,
		success : this.success,
		headers : {},	//a map of additional header key/value
		timeout : 1000,	//1 second
		type : 'POST',
		username : null,
		password : null
	};
	this.html = null;
	this.source = null;
	this.process = null;
	this.auto_number = 1;
	this.listeners = {};
	this.listen_gap = 2000;
}
/**
  * conduct ajax request
  * @param settings Object request options
*/
Controller.prototype.ajaxRequest = function(settings){
	this.start();
	if(typeof(settings) == "object" && settings.constructor != "Array"){
		for(var i in settings){
			if(this.ajax.isset(i)){
				this.ajax[i] = settings[i];
			}
		}
		$.ajax(this.ajax);
	}
}
/*
 * global error handler of ajax request
 * @param xhr XmlHttpRequest object
 * @param textStatus String : "timeout","error","abort","parseerror"
 * @param errorThrown String : textual portion of HTTP status eg. "Not Found","Internal Server Error"
*/	
Controller.prototype.error = function(xhr,textStatus,errorThrown){
	var loading = dispatcher.getComponent('loading');
	loading.hide();
	var options = {message : errorThrown};
	this.appError("message",options);
}
	/*
	 * global success handler of ajax request
	 * @param data data returned from server
	 * @param textStatus String 
	 */
Controller.prototype.success = function(data,textStatus,xhr){
	var my_data = JSON.parse(data);
	if(dispatcher.current.my_success){
		dispatcher.current.my_success(my_data);
	}
	var loading = dispatcher.getComponent('loading');
	loading.hide();
}
/*
 * global ajax start function
 */
Controller.prototype.start = function(pid){
	var loading = dispatcher.getComponent('loading');
	loading.show(pid);
}
Controller.prototype.addListener = function(option){
	var con = {
		dom : this.html,
		listener : null,
		func : "",
		type : "",
		data : {},
		params : {}
	};
	for(var i in option){
		con[i] = option[i];
	}
	con.dom.unbind(con.type).bind(con.type, {
		"com" : this,"data":con.data
	}, function(e) {
		if(typeof(con.listener) == 'string') {
			if(con['params'] == 'undefined')
				con.params = null;
			dispatcher.run(con.listener, con.params, e);
		} else if(typeof(con.listener) == 'function') {
			con.listener(e);
		} else{
			con.listener[con.func](e);
		}
			
	});
}
Controller.prototype.reflesh = function(){
	if(this.source != 'undefined'){
		dispatcher.current = this;
		var ajax = {
			url : null,
			async : true,
			cache : false,
			data : null,
			error : this.error,
			success : this.success,
			headers : {'request':'ajax'},	//a map of additional header key/value
			timeout : 2000,	//1 second
			type : 'POST'
	
		};
		ajax.url = config.baseURL + this.source;
		this.start(this.html);
		$.ajax(ajax);
	}
}
Controller.prototype.request = function(option){
	dispatcher.current = this;
	var url = config.baseURL + option.url; 
	var ajax = {
		url : url,
		async : true,
		data : null,
		error : this.error,
		headers : {'request':'ajax'},	//a map of additional header key/value
		type : 'POST'

	};
	if(option.data != 'undefined'){
		ajax.data = option.data;
	}
	ajax.success = function(data,textStatus,xhr){
		if(data){
			var my_data = JSON.parse(data);
		}
		else{
			var my_data = null;
		}
		if(dispatcher.current.process){
			dispatcher.current.process(my_data);
		}
		var loading = dispatcher.getComponent('loading');
		loading.hide();
	};
	if(!option.pid){
		option.pid = this.html;
	}
	this.start(option.pid);
	$.ajax(ajax);
}
Controller.prototype.getAutoNumber = function(){
	var num = this.auto_number;
	this.auto_number++;
	return num;
}
Controller.prototype.openListener = function(config){
	this.listeners[config.name] = {};
	this.listeners[config.name]['url'] = config.url;
	this.listeners[config.name]['params'] = config.params;
	this.listeners[config.name]['callback'] = config.callback;
}
Controller.prototype.listen = function(name){
	if(this.listeners[name]){
		this.listeners[name]['stop'] = false;
		var url = this.listeners[name]['url'];
		var params = this.listeners[name]['params'];
		var callback = this.listeners[name]['callback'];
		var req = $.ajax({
			'url' : config.baseURL + url,
			'async' : true,
			'data' : params,
			'headers' : {'action':'ajax'},
			'type' : 'POST',
			'global' : false,
			'success' : function(data,textStatus,req){
				var controller = dispatcher.getComponent(req.controller_name);
				var callback = req.callback_name;
				var listener = req.listener_name;
				var my_data = JSON.parse(data);
				if(controller[callback]){
					controller[callback](my_data);
					if(!controller.listeners[listener].stop){
						dispatcher.request = req;
						window.setTimeout(function(){
							var c = dispatcher.request.controller_name;
							var controller = dispatcher.getComponent(c);
							var listener = dispatcher.request.listener_name;
							controller.listen(listener);
						},2000);
					}
				}
			},
			'error' : function(req,textStatus,err){
				var controller = dispatcher.getComponent(req.controller_name);
				var callback = req.callback_name;
				var listener = req.listener_name;
				controller.listeners[listener].stop = true;
			}
		});
		req.controller_name = this.name;
		req.listener_name = name;
		req.callback_name = callback;
	}
}
Controller.prototype.setWidth = function(w){
	this.html.width(w);
	this.resize();
}
Controller.prototype.init = function(){}
Controller.prototype.setContent = function(data){}
Controller.prototype.resize = function(){}
/**
 * App Error Controller
 */
function ErrorController(){
	Controller.apply(this);
}
ErrorController.prototype = new Controller();
	//show error message
ErrorController.prototype.message = function(){
	var options = this.params;
	if(options.message != 'undefined'){
		var popup = dispatcher.getComponent('popup');
		popup.setContent({
			title:'Error',
			message : options.message
		});
		popup.show();
	}
}
/*
 * dispatch data request to the related controller according to the url
 */
function Dispatcher(){
	this.controller = {};
	this.action = "";
	this.params = {};
	this.event = {};
	this.components = {};
	//current controller
	this.current = null;
}
	/**
	 * dispath request to controllr
	 */
Dispatcher.prototype.run = function(url,params,event){
	var p = [];
	if(event != 'undefined'){
		this.event = event;
	}	
	var str_arr = url.split("/");
	var controller = str_arr[0];
	this.action = str_arr[1];
	this.controller = this.getController(controller);
	if(params) this.controller.params = params;
	if(str_arr.length > 2){
		var p = [];
		for(var i=2;i<str_arr.length;i++){
			p.push(str_arr[i]);
		}
	}
	this.controller.event = this.event;
	return this.invoke(p);
}
Dispatcher.prototype.getController = function(controller){
	var obj = this.getComponent(controller);
	if(!obj){
		var command ="new " + controller.upFirst() + "Controller('" + controller + "');";
		obj = eval(command);
	}
	return obj;
}
Dispatcher.prototype.invoke = function(p){
	if(this.controller){
		var con = this.controller;
		if(con[this.action] !='undefined'){
			if(p.length>0){
				return con[this.action].apply(con,p);
			} 
			else return con[this.action].call(con);
		}
	}
}

Dispatcher.prototype.getComponent = function(name){
	if(this.components[name] == 'undefined')
		return null;
	return this.components[name];
}
Dispatcher.prototype.register = function(obj){
	var name = obj.name;	
	this.components[name.toString()] = obj;
}
Dispatcher.prototype.init = function(){
	if(this.components){
		for(var i in this.components){
			var com = this.components[i];
			if(com.init){
				com.init();
			}
		}
	}
}
var dispatcher = new Dispatcher();
$(document).ready(function(){
	dispatcher.init();
	dispatcher.run(config.url);
});
