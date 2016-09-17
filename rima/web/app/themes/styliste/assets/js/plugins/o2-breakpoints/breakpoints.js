/*
* breakpoints.js
* o2web.ca
* 2015
* GPL v2 License
*/

// AMD MODULE LOADER DEFINITION
(function (root, factory) {
  if (typeof define === 'function' && define.amd) {
    // Define AMD module
    define(['jquery', 'raf'], factory);
  } else {
    // JQUERY INIT
    factory(jQuery);
  }
}

// MAIN CODE
(this, function($){

	var app = this;
	if(!app.breakpoints) app.breakpoints = {
		init: {},
		resize: {},
		event: 'afterwindowresize',
		rafref: false
	};

	//
	//
	// BP object
	Breakpoints = function(args, options){

		var self = this;

		//
		//
		// add breakpoint
		this.add = function(minMax, width, callbacks, data, initial){
			initial = !!initial;
			var type = initial ? 'init' : 'resize';
			if(typeof callbacks != 'object') callbacks = [callbacks];

			// return if no width or no callbacks are defined
			if(!width || !callbacks.length) return;

			var bpWidth = width.toString();

			// check if BP exists
			if(!app.breakpoints[type][bpWidth]){
				app.breakpoints[type][bpWidth] = {
					active: false,
					width: width,
					min: [],
					max: []
				}
			}

			// push callbacks
			for(var i=0; i<callbacks.length; i++){
				if(typeof callbacks[i] == 'function'){
					app.breakpoints[type][bpWidth][minMax].push({
						callback: callbacks[i],
						data: data
					});
				}
			}

			// trigger init callbacks right now if already initiated
			if(initial && app.breakpoints.initiated) self.checkBreakpoints(true);

			// hook resize loop
			if(!app.breakpoints.rafref){
				app.breakpoints.rafref = app.raf.on(app.breakpoints.event, self.checkBreakpoints).ref;
			}

		}


		//
		//
		// SHORTHANDS

		// min-width shorthand
		this.min = function(width, callbacks, data){
			self.add('min', width, callbacks, data);
		}

		// max-width shorthand
		this.max = function(width, callbacks, data){
			self.add('max', width, callbacks, data);
		}

		// initial BPs
		this.initial = {
			min: function(width, callbacks, data){
				self.add('min', width, callbacks, data, true);
			},
			max: function(width, callbacks, data){
				self.add('max', width, callbacks, data, true);
			}
		}


		//
		//
		//
		this.checkBreakpoints = function(init){
			init = init === true;
			var bps = app.breakpoints[init ? 'init' : 'resize' ];
			var winWidth = app.innerWidth;

			for(var bpKey in bps){
				var bp = bps[bpKey];
				if((init||!bp.active) && winWidth<=bp.width){
					bp.active = true;
					if(bp.max.length){
						for(var i=0; i<bp.max.length; i++){
							bp.max[i].callback(bp.max[i].data);
						}
					}
				}
				if((init||bp.active) && winWidth>bp.width){
					bp.active = false;
					if(bp.min.length){
						for(var i=0; i<bp.min.length; i++){
							bp.min[i].callback(bp.min[i].data);
						}
					}
				}
			}

			// clear inital breakpoints
			if(init){
				for(var bpKey in bps){
					if(app.breakpoints.resize[bpKey]){
						app.breakpoints.resize[bpKey].active = bps[bpKey].active;
					}
				}
				app.breakpoints.init = [];
				app.breakpoints.initiated = true;
			}

		}

	}


	//
	//
	//
	//
	//
	//
	//	INIT

	$.breakpoints = new Breakpoints();

	$(document).ready(function(){
		$.breakpoints.checkBreakpoints(true);
	});


}));