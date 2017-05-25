;(function ($, window, document, undefined){
	var pluginName = 'SimpleMarquee';

    function Plugin (element, options) {
        this.element = element;
        this._name = pluginName;
        this._defaults = $.fn.SimpleMarquee.defaults;
        this.settings = $.extend( {}, this._defaults, options );
        this.marqueeSpawned = [];
        this.marqueeHovered = false;
        this.documentHasFocus = false;        
        //
        this.counter = 0;

        this.timeLeft = 0;
        this.currentPos = 0;
        this.distanceLeft = 0;
        this.totalDistance = 0;
        this.contentWidth = 0;
        this.endPoint = 0;
        this.duration = 0;
        this.hovered = false;
        this.padding = 0;
        
        
        this.init();
    }
    function marqueeObj(newElement){
    	this.el=newElement;
    	this.counter=0;
    	this.name="";
    	this.timeTop=0;
    	this.currentPos=0;
    	this.distanceTop=0;
    	this.totalDistance=0;
    	this.contentWidth=0;
    	this.endPoint=0;
    	this.duration=0;
    	this.hovered=false;
    	this.padding=0;
    }
    //methods for plugin
    $.extend(Plugin.prototype, {

        // Initialization logic
        init: function () {
            this.buildCache();
            this.bindEvents();
            var config = this.settings;
            //init marquee
            if($(config.marquee_class).width() == 0){
	            console.error('');
	            return;
	        }
	
	        if(typeof $(config.marquee_class) === 'undefined'){
	            console.error('FATAL: marquee class not valid');
	            return;
	        }
	
	        if(typeof $(config.container_class) === 'undefined'){
	            console.error('FATAL: marquee container class not valid');
	            return;
	        }
	
	        if(config.sibling_class != 0 && typeof $(config.sibling_class) === 'undefined'){
	            console.error('FATAL: sibling class container class not valid');
	            return;
	        }
	        
	        //create the Marquee
	        this.createMarquee();
        },

        // Remove plugin instance completely
        destroy: function() {
            this.unbindEvents();
            this.$element.removeData();
        },

        // Cache DOM nodes for performance
        buildCache: function () {
            this.$element = $(this.element);
        },

        // Bind events that trigger methods
        bindEvents: function() {
        	var plugin = this;
        	$(window).on('focus',function(){
        		plugin.documentHasFocus = true;
        		for (var key in plugin.marqueeSpawned){
      	          plugin.marqueeManager(plugin.marqueeSpawned[key]);   
      	      	} 
        	});
        	$(window).on('blur',function(){
        		plugin.documentHasFocus = false;
        		for (var key in plugin.marqueeSpawned){
        	        plugin.marqueeSpawned[key].el.clearQueue().stop(); 
        	        plugin.marqueeSpawned[key].hovered = true;
        	    }
        	});

        },

        // Unbind events that trigger methods
        unbindEvents: function() {
        	$(window).off('blur focus');
        },
        getPosition: function(elName){
        	this.currentPos = parseInt($(elName).css('left'));
            return this.currentPos;
        },
        createMarquee: function(){
        	var plugin = this;
        	var config = plugin.settings;
        	var marqueeContent =  $(config.marquee_class).html();
            var containerWidth = $(config.container_class).width();
            var contentWidth = $(config.marquee_class).width();
            
            var widthToIgnore = 0;
            if (config.sibling_class != 0){ 
            	widthToIgnore = $(config.sibling_class).width();
            } 
            
            var spawnAmount = Math.ceil(containerWidth / contentWidth);
            
            $(config.marquee_class).remove();

            if(spawnAmount<=2){
                spawnAmount = 3;
            } else {
              spawnAmount++;
            }

            var totalContentWidth = (contentWidth + config.padding)*spawnAmount;

            var endPoint = -(totalContentWidth - containerWidth);

            var totalDistance =  containerWidth - endPoint;
            
            
            
            
            for (var i = 0; i < spawnAmount; i++) {
            	
            	var newElement = false;
            	
                if(config.hover == true){

                  
                  newElement = $('<div class="marquee-' + (i+1) + '">' + marqueeContent + '</div>')        
                  .mouseenter(function() {


                    if ((plugin.documentHasFocus == true) && (plugin.marqueeHovered == false)){
                      plugin.marqueeHovered = true;

                      for (var key in plugin.marqueeSpawned){
                        plugin.marqueeSpawned[key].el.clearQueue().stop(); 
                        plugin.marqueeSpawned[key].hovered = true;
                      }
                      

                    }

                  })
                  .mouseleave(function() {


                      if ((plugin.documentHasFocus == true) && (plugin.marqueeHovered == true)){

                        for (var key in plugin.marqueeSpawned){
                          plugin.marqueeManager(plugin.marqueeSpawned[key]);   
                        } 

                        plugin.marqueeHovered = false;
                      } 
                  });

                } else {

                  newElement = $('<div class="marquee-' + (i+1) + '">' + marqueeContent + '</div>') ;   

                }

                plugin.marqueeSpawned[i] = new marqueeObj(newElement);

                $(config.container_class).append(newElement);

                plugin.marqueeSpawned[i].currentPos = (widthToIgnore + (contentWidth*i))+(config.padding*i);  //initial positioning
                plugin.marqueeSpawned[i].name = '.marquee-'+(i+1); 

                plugin.marqueeSpawned[i].totalDistance = totalDistance;  
                plugin.marqueeSpawned[i].containerWidth = containerWidth;  
                plugin.marqueeSpawned[i].contentWidth = contentWidth;  
                plugin.marqueeSpawned[i].endPoint = endPoint;  
                plugin.marqueeSpawned[i].duration = config.duration;  
                plugin.marqueeSpawned[i].padding = config.padding;  

                plugin.marqueeSpawned[i].el.css('left', plugin.marqueeSpawned[i].currentPos+config.padding +'px'); //setting left according to postition

                 if (plugin.documentHasFocus == true){
                  plugin.marqueeManager(plugin.marqueeSpawned[i]);
                }

            }
            //end for
            
            if(document.hasFocus()){
	        	 plugin.documentHasFocus = true;
        	}else{
	        	plugin.documentHasFocus = false;
	        }
            
        },
        marqueeManager: function(marqueed_el){
        	var plugin = this;
        	var elName = marqueed_el.name;
        	if (marqueed_el.hovered == false) { 

                if (marqueed_el.counter > 0) {  //this is not the first loop
                  
                      marqueed_el.timeLeft = marqueed_el.duration;
                      marqueed_el.el.css('left', marqueed_el.containerWidth +'px'); //setting margin 
                      marqueed_el.currentPos = marqueed_el.containerWidth; 
                      marqueed_el.distanceLeft = marqueed_el.totalDistance - (marqueed_el.containerWidth - plugin.getPosition(elName));

                } else {    // this is the first loop
                  
                  marqueed_el.timeLeft = (((marqueed_el.totalDistance - (marqueed_el.containerWidth - plugin.getPosition(elName)))/ marqueed_el.totalDistance)) * marqueed_el.duration;
                }

            } else {
                  marqueed_el.hovered = false;
                  marqueed_el.currentPos = parseInt(marqueed_el.el.css('left'));
                  marqueed_el.distanceLeft = marqueed_el.totalDistance - (marqueed_el.containerWidth - plugin.getPosition(elName));
                  marqueed_el.timeLeft = (((marqueed_el.totalDistance - (marqueed_el.containerWidth - marqueed_el.currentPos))/ marqueed_el.totalDistance)) * marqueed_el.duration;
            }

        	plugin.marqueeAnim(marqueed_el);
        },
        marqueeAnim: function(marqueeObject){
        	var plugin = this;
        	marqueeObject.counter++;
            marqueeObject.el.clearQueue().animate(
            		{'left': marqueeObject.endPoint+'px'}, 
            		marqueeObject.timeLeft, 
            		'linear', 
            		function(){
            			plugin.marqueeManager(marqueeObject);
        			});
        },
        callback: function() {
            // Cache onComplete option
            var onComplete = this.settings.onComplete;

            if ( typeof onComplete === 'function' ) {
                onComplete.call(this.element);
            }
        }

    });
    //end methods for plugin
    
    $.fn.SimpleMarquee = function (options) {
        this.each(function() {
            if ( !$.data( this, "plugin_" + pluginName ) ) {
                $.data( this, "plugin_" + pluginName, new Plugin( this, options ) );
            }
        });
        return this;
    };
    $.fn.SimpleMarquee.defaults = {
            property: 'value',
            onComplete: null,
            duration: 20000,
            padding: 10,
            marquee_class: '.marquee',
            container_class: '.simple-marquee-container',
            sibling_class: 0,
            hover: true
    };
    
})( jQuery, window, document );
