/**
Modified by Niels van der Elst (nielsos@gmx.net) 07-09-2008

- Select and de-select shapes onclick and with direct callback.
	Example: <a href="javascript:void(0);" onClick="mouseclick(null,document.getElementById('shapesid'));">Toggle shape</a>
	
- Link shapes with the linked attribute. Every shape with the same linked value will be turned on and off simultaneous
	Example: class="{alwaysOn:true,linked:1}"
**/

(function($) {
	var has_VML, create_canvas_for, add_shape_to, clear_canvas, shape_from_area,
		canvas_style, fader, hex_to_decimal, css3color, is_image_loaded;

	var counter = 0;

	has_VML = document.namespaces;
	has_canvas = document.createElement('canvas');
	has_canvas = has_canvas && has_canvas.getContext;

	if(!(has_canvas || has_VML)) {
		$.fn.maphilight = function() { return this; };
		return;
	}
	
	// For non IE browsers!!!
	if(has_canvas) {
		
		fader = function(element, opacity, interval) {
			if(opacity <= 1) {
				element.style.opacity = opacity;
				window.setTimeout(fader, 10, element, opacity + 0.1, 10);
			}
		};
		
		hex_to_decimal = function(hex) {
			return Math.max(0, Math.min(parseInt(hex, 16), 255));
		};
		css3color = function(color, opacity) {
			return 'rgba('+hex_to_decimal(color.substr(0,2))+','+hex_to_decimal(color.substr(2,2))+','+hex_to_decimal(color.substr(4,2))+','+opacity+')';
		};
		create_canvas_for = function(img) {
			var c = $('<canvas id="canvas_' + $(this).attr("id") + ' style="width:'+img.width+'px;height:'+img.height+'px;"></canvas>').get(0);
			c.getContext("2d").clearRect(0, 0, c.width, c.height);
			return c;
		};
		
		add_shape_to = function(canvas, shape, coords, options, name)
		{
					
			var i, context = canvas.getContext('2d');
			context.beginPath();
			
			if(shape == 'rect')
			{
				context.rect(coords[0], coords[1], coords[2] - coords[0], coords[3] - coords[1]);
			} 
			else if(shape == 'poly')
			{
				context.moveTo(coords[0], coords[1]);

				for(i=2; i < coords.length; i+=2)
				{
					context.lineTo(coords[i], coords[i+1]);
				}
				
			} 
			else if(shape == 'circ')
			{
				context.arc(coords[0], coords[1], coords[2], 0, Math.PI * 2, false);
			}
						
			context.closePath();
			
			if(options.fill)
			{
				context.fillStyle = css3color(options.fillColor, options.fillOpacity);
				context.fill();
			}
			if(options.stroke)
			{
				context.strokeStyle = css3color(options.strokeColor, options.strokeOpacity);
				context.lineWidth = options.strokeWidth;
				context.stroke();
			}
			if(options.fade)
			{
				fader(canvas, 0);
			}
		};
			
			clear_canvas = function(canvas, area) {
			canvas.getContext('2d').clearRect(0, 0, canvas.width,canvas.height);
		};
	} 
	// IE!!!
	else 
	{   
		// ie executes this code
		document.namespaces.add("v", "urn:schemas-microsoft-com:vml"); 
var style = document.createStyleSheet();
var shapes = ['shape','rect', 'oval', 'circ', 'fill', 'stroke', 'imagedata', 'group','textbox'];  
$.each(shapes,
    function()
    {
        style.addRule('v\\:' + this, "behavior: url(#default#VML); antialias:true");
    }
);
			
		create_canvas_for = function(img)
		{
			return $('<var id="iemainvmlcontainer" style="zoom:1;overflow:hidden;display:block;width:'+img.width+'px;height:'+img.height+'px;"></var>').get(0);
		};
		
		add_shape_to = function(canvas, shape, coords, options, name, id)
		{
			var fill, stroke, opacity, e;
					
			fill = '<v:fill color="#'+options.fillColor+'" opacity="'+(options.fill ? options.fillOpacity : 0)+'" />';
			
			stroke = (options.stroke ? 'strokeweight="'+options.strokeWidth+'" stroked="t" strokecolor="#'+options.strokeColor+'"' : 'stroked="f"');
		
			opacity = '<v:stroke opacity="'+options.strokeOpacity+'"/>';
			
			if(shape == 'rect')
			{
				e = $('<v:rect id="canvas_' + id + '" name="'+name+'" filled="t" '+stroke+' style="zoom:1;margin:0;padding:0;display:block;position:absolute;left:'+coords[0]+'px;top:'+coords[1]+'px;width:'+(coords[2] - coords[0])+'px;height:'+(coords[3] - coords[1])+'px;"></v:rect>');
			} 
			else if(shape == 'poly')
			{
   			e = $('<v:shape id="canvas_' + id + '" name="'+name+'" filled="t" '+stroke+' coordorigin="0,0" coordsize="'+canvas.width+','+canvas.height+'" path="m '+coords[0]+','+coords[1]+' l '+coords.join(',')+' x e" style="zoom:1;margin:0;padding:0;display:block;position:absolute;top:0px;left:0px;width:'+canvas.width+'px;height:'+canvas.height+'px;"></v:shape>');
			} 
			else if(shape == 'circ')
			{
				e = $('<v:oval id="canvas_' + id + '" name="'+name+'" filled="t" '+stroke+' style="zoom:1;margin:0;padding:0;display:block;position:absolute;left:'+(coords[0] - coords[2])+'px;top:'+(coords[1] - coords[2])+'px;width:'+(coords[2]*2)+'px;height:'+(coords[2]*2)+'px;"></v:oval>');
			}

			e.get(0).innerHTML = fill+opacity;
			$(canvas).append(e);
		};
		
		clear_canvas = function(canvas)
		{
			$(canvas).find('[name=highlighted]').remove();
		};
	}
	
	shape_from_area = function(area)
	{
		var i, coords = area.getAttribute('coords').split(',');

		for (i=0; i < coords.length; i++) { coords[i] = parseFloat(coords[i]); }
		
		return [area.getAttribute('shape').toLowerCase().substr(0,4), coords];

	};
	
	is_image_loaded = function(img) {
		if(!img.complete) { return false; } // IE
		if(typeof img.naturalWidth != "undefined" && img.naturalWidth == 0) { return false; } // Others
		return true;
	}

	canvas_style = {
		position: 'absolute',
		left: 0,
		top: 0,
		padding: 0,
		border: 0
	};
	
	$.fn.maphilight = function(opts) {
		opts = $.extend({}, $.fn.maphilight.defaults, opts);
		
		return this.each(function() {
			var img, wrap, options, map, canvas, canvas_always, mouseover, highlighted_shape;
			img = $(this);
		
			if(!is_image_loaded(this)) {
				// If the image isn't fully loaded, this won't work right.  Try again later.
				return window.setTimeout(function() {
					img.maphilight(opts);
				}, 200);
			}

			options = $.metadata ? $.extend({}, opts, img.metadata()) : opts;

			map = $('map[name="'+img.attr('usemap').substr(1)+'"]');

			if(!(img.is('img') && img.attr('usemap') && map.size() > 0)) { return; }

			if(img.hasClass('maphilighted')) {
				// We're redrawing an old map, probably to pick up changes to the options.
				// Just clear out all the old stuff.
				var wrapper = img.parent();
				img.insertBefore(wrapper);
				wrapper.remove();
				//alert('yes');
			}

			wrap = $('<div>').css({display:'block',background:'url('+this.src+')',position:'relative',padding:0,width:this.width,height:this.height});
			img.before(wrap).css('opacity', 0).css(canvas_style).remove();
			
			if($.browser.msie) { img.css('filter', 'Alpha(opacity=0)'); }
			
			wrap.append(img);
				
			canvas = create_canvas_for(this);
			$(canvas).css(canvas_style);
			canvas.height = this.height;
			canvas.width = this.width;
			canvas.id = this.id;
					
			mouseover = function(e)
			{
				var shape, area_options;
				area_options = $.metadata ? $.extend({}, options, $(this).metadata()) : options;
		
				if (area_options.linked)
				{
					var thislinked = area_options.linked;
					
					$(map).find('area[coords]').each(function()
					{
						var shape, area_options, object;
						area_options = $.metadata ? $.extend({}, options, $(this).metadata()) : options;		
									
						if (thislinked == area_options.linked) {
							shape = shape_from_area(this);
							add_shape_to(canvas, shape[0], shape[1], area_options, "highlighted", null);												
						}					
																									
					});
				}
				else
				{
					shape = shape_from_area(this);
					add_shape_to(canvas, shape[0], shape[1], area_options, "highlighted", null);
				}
		
			
				//if(!area_options.alwaysOn)
				//{
				// 	shape = shape_from_area(this);
				// 	add_shape_to(canvas, shape[0], shape[1], area_options, "highlighted", null);
				//}
				
			}
			
			
			draw = function(object)
			{
				var shape, area_options, object;
				area_options = $.metadata ? $.extend({}, options, $(this).metadata()) : options;		
				
				// NON IE
				if(has_canvas)
				{
					canvas_always = create_canvas_for(img.get());
				
					$(canvas_always).css(canvas_style);
					canvas_always.width = img.width();
					canvas_always.height = img.height();
					canvas_always.id = 'canvas_' + $(object).attr("id");
														
					img.before(canvas_always);
				}
									
				shape = shape_from_area(object);
				
				// IE!
				if ($.browser.msie)
				{
					add_shape_to(canvas, shape[0], shape[1], area_options, "", $(object).attr("id"));
				} 
				else
				{
					add_shape_to(canvas_always, shape[0], shape[1], area_options, "");
				}
			}
			
			mouseclick = function(e,id)
			{
				var shape, area_options, object;
				area_options = $.metadata ? $.extend({}, options, $(this).metadata()) : options;	
								
				if (id) {
					object = id;
				}
				else {
					object = this;
				}				
				
				if (!$('#canvas_' + $(object).attr('id')).attr('id'))
				{
					if (area_options.linked)
					{
						var thislinked = area_options.linked;
						
						$(map).find('area[coords]').each(function()
						{
							var shape, area_options, object;
							area_options = $.metadata ? $.extend({}, options, $(this).metadata()) : options;		
										
							if (thislinked == area_options.linked) {
								// alert($(this).attr('id') + ' ' + area_options.linked);
								draw(this);				
							}																			
																											
						});
					}
					else
					{
						draw(object);
					}
				}
				else
				{
					if (area_options.linked)
					{
						var thislinked = area_options.linked;
						
						$(map).find('area[coords]').each(function()
						{
							var shape, area_options, object;
							area_options = $.metadata ? $.extend({}, options, $(this).metadata()) : options;		
										
							if (thislinked == area_options.linked) {
								$('#canvas_' + $(this).attr('id')).remove();
							}																			
						});
						clear_canvas(canvas);
					}
					else
					{
						$('#canvas_' + $(object).attr('id')).remove();
						clear_canvas(canvas);	
					}
				}
			}
					
			if(options.alwaysOn) {
				$(map).find('area[coords]').each(mouseover);
			} else {
				if($.metadata) {
					// If the metadata plugin is present, there may be areas with alwaysOn set.
					// We'll add these to a *second* canvas, which will get around flickering during fading.
					$(map).find('area[coords]').each(function() {
												
						var shape, area_options;
						area_options = $.metadata ? $.extend({}, options, $(this).metadata()) : options;
						
						if(area_options.alwaysOn)
						{
							draw(this);							
						}
					});
				}
				
				alert(mouseover);
				
				$(map).find('area[coords]').mouseover(mouseover).mouseout(function(e) { clear_canvas(canvas); });
				$(map).find('area[coords]').click(mouseclick); 
			}
			
			img.before(canvas); // if we put this after, the mouseover events wouldn't fire.
			img.addClass('maphilighted');
		});
	};

	$.fn.maphilight.defaults = {
		fill: true,
		fillColor: '000000',
		fillOpacity: 0.9,
		stroke: false,
		strokeColor: 'ffffff',
		strokeOpacity: 1,
		strokeWidth: 1,
		fade: true,
		alwaysOn: false
	};
})(jQuery);
