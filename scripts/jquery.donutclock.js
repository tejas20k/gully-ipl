(function($) {
  var defaultsettings = {
    'bgColor' : '#e5e5e5',
    'fgColor' : 'red',
    'size' : 160,
    'donutwidth': 40,
    'textsize': 16,
  }
  
  var methods = {
    init : function(options) {
      
      var initcanvas=true;
      
      if (typeof(options) == "object")
      {
        this.donutclocksettings = $.extend({}, defaultsettings, options);
    
        // autoscale donutwidth and textsize
        if (options["size"] && !options["donutwidth"])
          this.donutclocksettings["donutwidth"]=options["size"]/8;
        if (options["size"] && !options["textsize"])
          this.donutclocksettings["textsize"]=options["size"]/5;
      }
      else
      {
        if (typeof(this.donutclocksettings) == "object")
          initcanvas=false;
        else
          this.donutclocksettings = defaultsettings;
      }
      
      if (initcanvas)
      {
        $(this).css("position","relative");
        $(this).css("width",this.donutclocksettings.size+"px");
        $(this).css("height","200px");
        $(this).html("<canvas width='"+this.donutclocksettings.size+"' height='"+this.donutclocksettings.size+"'></canvas><div style='position:absolute;top:0;left:4px;line-height:"+this.donutclocksettings.size+"px;text-align:center;width:"+this.donutclocksettings.size+"px;font-family:Roboto;color: #888;font-size:"+this.donutclocksettings.textsize+"px;font-weight:bold;'></div>");
      
        var canvas = $("canvas",this).get(0);
      
        // excanvas support
        if (typeof(G_vmlCanvasManager) != "undefined")
          G_vmlCanvasManager.initElement(canvas);
      
        var ctx = canvas.getContext('2d');
        methods.drawBg.call(ctx, this.donutclocksettings);
      }

    },
    
    drawBg : function(settings) {
      this.clearRect(0,0,settings.size,settings.size);
      this.beginPath();
      this.fillStyle = settings.bgColor;
      this.arc(settings.size/2,settings.size/2,settings.size/2,0,2*Math.PI,false);
      this.arc(settings.size/2,settings.size/2,settings.size/2-settings.donutwidth,0,2*Math.PI,true);
      this.fill();
    },
    
    drawFg : function(settings,percent) {
      
      var ratio = percent/100 * 360;
      var startAngle = Math.PI*-90/180;
      var endAngle = Math.PI*(-90+ratio)/180;
	  var redamt = Math.round(255*percent/100);
	  var greenamt = Math.round(255*(1-(percent/100)));
	  var redamtstr = redamt.toString(16);
	  var greenamtstr = greenamt.toString(16);
	  
	  if (redamtstr.length == 1)
	  {
		  redamtstr = "0"+redamtstr;
	  }
	  
	  if (greenamtstr.length==1){
		  greenamtstr="0"+greenamtstr;
	  }

      this.beginPath();
	  this.fillStyle = "#"+redamtstr+greenamtstr+"00";
      this.arc(settings.size/2,settings.size/2,settings.size/2,startAngle,endAngle,false);
      this.arc(settings.size/2,settings.size/2,settings.size/2-settings.donutwidth,endAngle,startAngle,true);
      this.fill();
    },
  };
  
  $.fn.donutclock = function(method) {
    return this.each(function() {
      methods.init.call(this, method);

      if (method=="animate")
      {
        
        var starttime = $(this).attr("start-time");
        var canvas = $(this).children("canvas").get(0);
        var percenttext = $(this).children("div");
        var dcs = this.donutclocksettings;
		var rem_minute;
		var rem_sec;
        if (canvas.getContext)
        {
          var ctx = canvas.getContext('2d');
          var j = 0;
          
          function animateDonutClock()
          {
            j++;
            isClean = false;
            
            //percenttext.text(j);
			rem_minute = Math.floor((starttime-j)/60).toString();
			rem_sec = ((starttime-j)%60).toString();
			if (rem_minute.length == 1){
				rem_minute = "0"+rem_minute;
			}
			if (rem_sec.length == 1){
				rem_sec = "0"+rem_sec;
			}
			percenttext.text(rem_minute+"'"+rem_sec+"\"");

            methods.drawBg.call(ctx,dcs);
            methods.drawFg.apply(ctx,[dcs,j*100/starttime]);

            if (j > starttime){
              clearInterval(animationID);
              alert('Time\'s up! Your quiz will now be automatically submitted.');
              isClean = true;
              window.location.replace('take_quiz.php');
              window.onbeforeunload = null;
              }
          }
          var animationID = setInterval(animateDonutClock,1000); 
        }
      }

    })
  }
})( jQuery );