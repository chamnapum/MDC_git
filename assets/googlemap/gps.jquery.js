/*
//////////////////////////////////////////////////////////////////////////////////
///////Copyright © 2009 Bird Wing Productions, http://www.birdwingfx.com//////////
//////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

/////////////////////////////////////////////////////////////////////
*/


// jQuery GPS - http://www.birdwingfx.com/jQuery_gps/index.html
// modified with help of experts-exchange.com - http://www.experts-exchange.com/Programming/Languages/Scripting/JavaScript/Q_25353701.html
// Wanted to add custom marker code created with Google Map Custom Marker Maker and have ability to open and close tooltip window with mouse click
// Expert who did the work: wilq32 - personal blog: http://wilq32.blogspot.com/

(function($) {
    
    $.GoogleMapObjectDefaults = {        
        zoomLevel: 10,
        center: '37 103rd Ave. N.E. Bellevue, WA   98004',
        start: '#start',
        end: '#end',
        directions: 'directions',
        submit: '#getdirections',
        showTooltip: true,
        tooltip: 'false',
        image: false
    };

    function GoogleMapObject(elementId, options) {
        /* private variables */
        this._inited = false;
        this._map = null;
        this._geocoder = null;
        
        /* Public properties */
        this.ElementId = elementId;
        this.Settings = $.extend({}, $.GoogleMapObjectDefaults, options || '');
    }

    $.extend(GoogleMapObject.prototype, {
        init: function() {
            if (!this._inited) {
                if (GBrowserIsCompatible()) {
                    this._map = new GMap2(document.getElementById(this.ElementId));
                    this._map.addControl(new GSmallMapControl());
                    this._geocoder = new GClientGeocoder();
                }
                
                this._inited = true;
            }
        },
        load: function() {
            //ensure existence
            this.init();
            
            if (this._geocoder) {
                //"this" will be in the wrong context for the callback
                var zoom = this.Settings.zoomLevel;
                var center = this.Settings.center;
                var map = this._map;
				var showTooltip = this.Settings.showTooltip;

				if(showTooltip == true) {
					var customtooltip = true;
					var tooltipinfo = this.Settings.tooltip;
				} 
				
				
				var customimage=this.Settings.image;
                
                this._geocoder.getLatLng(center, function(point) {
                    if (!point) { alert(center + " not found"); }
                    else {
                        //set center on the map
                        map.setCenter(point, zoom);
                        
                       	if(customimage) {
                            //add the marker
                            var customicon = customimage;
                            var marker = new GMarker(point, { icon: customicon });
                            map.addOverlay(marker);
                        }else{
                            var marker = new GMarker(point);
                            map.addOverlay(marker);
                        }
                        
                        /*if(showTooltip == true) {
                            marker.openInfoWindowHtml(tooltipinfo);
                        }*/
						GEvent.addListener(marker,"click", function() {
							   marker.openInfoWindowHtml(tooltipinfo);
							   //$('#end').val($('#adds').val());
						}); 
                    }
                });
            }
            
            
            //make this available to the click element
            $.data($(this.Settings.submit)[0], 'inst', this);
            
            $(this.Settings.submit).click(function(e) {
                e.preventDefault();
                var obj = $.data(this, 'inst');
                var outputto = obj.Settings.directions;
                var from = $(obj.Settings.start).val();
                var to = $(obj.Settings.end).val();
                map.clearOverlays();
                var gdir = new GDirections(map, document.getElementById(outputto));
                gdir.load("from: " + from + " to: " + to);
                
                //open the google window
                //window.open("http://maps.google.com/maps?saddr=" + from + "&daddr=" + to, "GoogleWin", "menubar=1,resizable=1,scrollbars=1,width=750,height=500,left=10,top=10");
            });
            
            return this;
        }
    });

    $.extend($.fn, {
        googleMap: function(options) {
            // check if a map was already created
            var mapInst = $.data(this[0], 'googleMap');

            if (mapInst) {
                return mapInst;
            }
            
            //create a new map instance
            mapInst = new GoogleMapObject($(this).attr('id'), options);
            $.data(this[0], 'googleMap', mapInst);
            return mapInst;
        }
    });
})(jQuery);



// Google Map Custom Marker Maker 2009
// Please include the following credit in your code

// Sample custom marker code created with Google Map Custom Marker Maker
// http://www.powerhut.co.uk/googlemaps/custom_markers.php

var myIcon = new GIcon();
myIcon.image = 'http://magasinducoin.fr/assets/images/point.png';
myIcon.shadow = 'http://www.massagecenters.webbdemo.com/assets/jquery/markers/shadow.png';
myIcon.iconSize = new GSize(27,50);
myIcon.shadowSize = new GSize(52,50);
myIcon.iconAnchor = new GPoint(27,50);
myIcon.infoWindowAnchor = new GPoint(14,0);
myIcon.printImage = 'http://www.massagecenters.webbdemo.com/assets/jquery/markers/printImage.gif';
myIcon.mozPrintImage = 'http://www.massagecenters.webbdemo.com/assets/jquery/markers/mozPrintImage.gif';
myIcon.printShadow = 'http://www.massagecenters.webbdemo.com/assets/jquery/markers/printShadow.gif';
myIcon.transparent = 'http://www.massagecenters.webbdemo.com/assets/jquery/markers/transparent.png';
myIcon.imageMap = [14,0,15,1,15,2,24,3,24,4,25,5,25,6,24,7,23,8,22,9,21,10,20,11,19,12,18,13,17,14,16,15,16,16,16,17,17,18,17,19,17,20,17,21,17,22,18,23,18,24,18,25,18,26,17,27,17,28,17,29,17,30,17,31,17,32,17,33,17,34,17,35,17,36,16,37,16,38,16,39,16,40,15,41,15,42,14,43,13,44,13,45,11,46,10,47,10,48,9,48,9,47,9,46,10,45,10,44,10,43,9,42,9,41,9,40,8,39,8,38,8,37,7,36,7,35,8,34,7,33,7,32,7,31,7,30,7,29,8,28,8,27,8,26,8,25,9,24,9,23,10,22,10,21,10,20,10,19,10,18,10,17,11,16,11,15,10,14,9,13,8,12,7,11,6,10,4,9,1,8,2,7,2,6,2,5,11,4,11,3,12,2,12,1,13,0];