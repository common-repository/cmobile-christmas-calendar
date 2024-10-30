var cmob_cc_links = [];
/**
* Get URL:s 
*/
function cmob_cc_getUrls(total_days) {
    cmob_cc_links.push(php_vars.day1_url);
    cmob_cc_links.push(php_vars.day2_url);
    cmob_cc_links.push(php_vars.day3_url);
    cmob_cc_links.push(php_vars.day4_url);
    cmob_cc_links.push(php_vars.day5_url);
    cmob_cc_links.push(php_vars.day6_url);
    cmob_cc_links.push(php_vars.day7_url);
    cmob_cc_links.push(php_vars.day8_url);
    cmob_cc_links.push(php_vars.day9_url);
    cmob_cc_links.push(php_vars.day10_url);
    cmob_cc_links.push(php_vars.day11_url);
    cmob_cc_links.push(php_vars.day12_url);
    cmob_cc_links.push(php_vars.day13_url);
    cmob_cc_links.push(php_vars.day14_url);
    cmob_cc_links.push(php_vars.day15_url);
    cmob_cc_links.push(php_vars.day16_url);
    cmob_cc_links.push(php_vars.day17_url);
    cmob_cc_links.push(php_vars.day18_url);
    cmob_cc_links.push(php_vars.day19_url);
    cmob_cc_links.push(php_vars.day20_url);
    cmob_cc_links.push(php_vars.day21_url);
    cmob_cc_links.push(php_vars.day22_url);
    cmob_cc_links.push(php_vars.day23_url);
    cmob_cc_links.push(php_vars.day24_url);
    if(total_days>24){
        cmob_cc_links.push(php_vars.day25_url);
    }
    return cmob_cc_links;
}

/**
 * Initate the day in christmas calendar
 * @param  number day.
 */
function cmob_cc_cmob_cc_GetSelectedTextValue(day,) {
    
    var selectedValue = parseInt(day);
    
    for (var i=0 ;i <= 25 ;i++){
            jQuery("#"+i+"d").remove();
            }

            cmob_cc_generatedoordiv(day);
            //show todays div
            jQuery("#"+day+"d").show(); 
		
     }

/**
 * Initate the christmas calendar on documet load
 */
jQuery(document).ready(function($){
  var total_days = php_vars.total_days_radio;
  var include_day_dropdown= php_vars.include_day_dropdown;
  var fake_date=php_vars.fake_date;
  var include_countdown=php_vars.include_countdown;
  //set defaultvalues if undefined
  if (include_day_dropdown==null){include_day_dropdown='on'}
  if (include_countdown==null){include_countdown='on'}
  $(function() {
    $('#box-link-door').on('click', function(e) {
        e.preventDefault();
        var self = this;
        setTimeout(function() {
            window.location.href = self.href;
        }, 1000);
    });
});
	cmob_cc_links=cmob_cc_getUrls(total_days);
	var rightNow = new Date(); 
    var month =rightNow.getMonth();
	var day  = rightNow.getDate();
    var year= rightNow.getFullYear();
	// put it all togeter
    var nowdate = year+'-'+month+'-'+day;
    var firstValue = nowdate.split('-');
    var secondValue =year+"-12-01";
    secondValue=secondValue.split('-');
   
    var firstDate=new Date();
    firstDate.setFullYear(firstValue[0],(firstValue[1] ),firstValue[2]);
    var secondDate=new Date();
    secondDate.setFullYear(secondValue[0],(secondValue[1] - 1 ),secondValue[2]);  
        //if fake date is set use it
        if (fake_date!=null && fake_date!=='') 
        {
            var fakeDate=new Date();
            var fakeDateSplit = fake_date.split('-');
            //pickout the day
            fake_day=fakeDateSplit[2];
            day=fake_day;
            firstDate=fake_date;
        }

        //show timer if not 1:a dec
       if (firstDate < secondDate)
            {
                if (include_countdown=='on')
                {
                    ringer.cmob_cc_init();
                }
                
              } else {
                  if(include_day_dropdown=='on'){
                        cmob_cc_generateselectdiv();
                        //show 24/25 even if past that date
                        if (day>24){
                        day=total_days;
                        };
                        //Fill the drop down list with days
                        // Get dropdown element from DOM
                        var dropdown = document.getElementById("selectDay");

                        // Loop through the array
                        for (var i = 1; i <= day; ++i) {
                            // Append the element to the end of Array list
                            dropdown[dropdown.length] = new Option(i, i);
                        }; 
                        jQuery("#selectDay").val(day);
                  }
                  if (day>total_days){day=total_days}
            cmob_cc_cmob_cc_GetSelectedTextValue(day,cmob_cc_links);//fake value for test replace with dd
            
          };
   });
   
/**
 * Generate the dropdown for days
 */
   function cmob_cc_generateselectdiv()
   {
   var selectdiv='<div id="christmasdaydropdownlist">';
   		selectdiv+='<select id="selectDay" onchange="cmob_cc_cmob_cc_GetSelectedTextValue(this.value)">';
       selectdiv+='<option>Välj dag</option>';
   		selectdiv+='</select>';
       selectdiv+='</div>';
       //jQuery("#doorhandler div:first").before(selectdiv);
		jQuery( "#doorhandler" ).append(selectdiv);
   };
/**
 * Generate calendar div 
 * @param  number day.
 */
   function cmob_cc_generatedoordiv(day){

   		var doordiv='<div id="'+day+'d" class="door'+day+'" style="display: none;">';
           doordiv+='<div class="door'+day+'__back"></div>';
           doordiv+='<div class="door'+day+'__front" tabindex="1"></div>';
           doordiv+='<div class="door'+day+'-link tabindex="2"">';
           doordiv+='<a id="box-link-door" class="box-link-door-url"';
           doordiv+='href="'+cmob_cc_links[day-1]+'" title="Open me" target="_self">';
           doordiv+='</a></div>';

          jQuery("#doorhandler").append(doordiv);
     
  
   };
/**
 * The countdown
 */

var ringer = {
  countdown_to: "12/01/2018",
  rings: {
    'DAGAR': { 
      s: 86400000, // mseconds in a day,
      max: 365
    },
    'TIMMAR': {
      s: 3600000, // mseconds per hour,
      max: 24
    },
    'MINUTER': {
      s: 60000, // mseconds per minute
      max: 60
    },
    'SEKUNDER': {
      s: 1000,
      max: 60
    },
    //'MICROSEK': {
   //   s: 10,
   //   max: 100
    //}
   },
  r_count: 5,
  r_spacing: 10, // px
  r_size: 60, // px
  r_thickness: 5, // px
  update_interval: 11, // ms
    
    
  cmob_cc_init: function(){
   
    $r = ringer;
    $r.cvs = document.createElement('canvas'); 
    
    $r.size = { 
      w: ($r.r_size + $r.r_thickness) * $r.r_count + ($r.r_spacing*($r.r_count-1)), 
      h: ($r.r_size + $r.r_thickness) 
    };
    


    $r.cvs.setAttribute('width',$r.size.w);           
    $r.cvs.setAttribute('height',$r.size.h);
    $r.ctx = $r.cvs.getContext('2d');
    jQuery(".doorsettings").append($r.cvs);
    $r.cvs = jQuery($r.cvs);    
    $r.ctx.textAlign = 'center';
    $r.actual_size = $r.r_size + $r.r_thickness;
    $r.countdown_to_time = new Date($r.countdown_to).getTime();
    $r.cvs.css({ width: $r.size.w+"px", height: $r.size.h+"px" });
    $r.go();
  },
  ctx: null,
  go: function(){
    var idx=0;
    
    $r.time = (new Date().getTime()) - $r.countdown_to_time;
    
    
    for(var r_key in $r.rings) $r.unit(idx++,r_key,$r.rings[r_key]);      
    
    setTimeout($r.go,$r.update_interval);
  },
  unit: function(idx,label,ring) {
    var x,y, value, ring_secs = ring.s;
    value = parseFloat($r.time/ring_secs);
    $r.time-=Math.round(parseInt(value)) * ring_secs;
    value = Math.abs(value);
    
    x = ($r.r_size*.5 + $r.r_thickness*.5);
    x +=+(idx*($r.r_size+$r.r_spacing+$r.r_thickness));
    y = $r.r_size*.5;
    y += $r.r_thickness*.5;

    
    // calculate arc end angle
    var degrees = 360-(value / ring.max) * 360.0;
    var endAngle = degrees * (Math.PI / 180);
    
    $r.ctx.save();

    $r.ctx.translate(x,y);
    $r.ctx.clearRect($r.actual_size*-0.5,$r.actual_size*-0.5,$r.actual_size,$r.actual_size);

    // first circle
    $r.ctx.strokeStyle = "rgba(128,128,128,0.2)";
    $r.ctx.beginPath();
    $r.ctx.arc(0,0,$r.r_size/2,0,2 * Math.PI, 2);
    $r.ctx.lineWidth =$r.r_thickness;
    $r.ctx.stroke();
   
    // second circle
    $r.ctx.strokeStyle = "rgba(197, 44, 2, 0.9)";
    $r.ctx.beginPath();
    $r.ctx.arc(0,0,$r.r_size/2,0,endAngle, 1);
    $r.ctx.lineWidth =$r.r_thickness;
    $r.ctx.stroke();
    
    // label
    $r.ctx.fillStyle = "#018810";
   
    $r.ctx.font = '8px Helvetica';
    $r.ctx.fillText(label, 0, 23);
    $r.ctx.fillText(label, 0, 23);   
    
    $r.ctx.font = 'bold 30px Helvetica';
    $r.ctx.fillText(Math.floor(value), 0, 10);
    
    $r.ctx.restore();
  }
}

//end countdown


