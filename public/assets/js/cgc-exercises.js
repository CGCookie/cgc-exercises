!function(a){a.organicTabs=function(b,c){var d=this;d.$el=a(b),d.$nav=d.$el.find(".tab-nav"),d.init=function(){d.options=a.extend({},a.organicTabs.defaultOptions,c),a(".tab-hide").css({position:"relative",top:0,left:0,display:"none"}),d.$nav.delegate("a","click",function(b){b.preventDefault();var c=d.$el.find("a.current").attr("href").substring(1),e=a(this),f=e.attr("href").substring(1),g=d.$el.find(".tab-content"),h=g.height();g.height(h),f!=c&&0==d.$el.find(":animated").length&&d.$el.find("#"+c).fadeOut(d.options.speed,function(){d.$el.find("#"+f).fadeIn(d.options.speed);var a=d.$el.find("#"+f).height();g.animate({height:a},d.options.speed),d.$el.find(".tab-nav li a").removeClass("current"),e.addClass("current"),window.history&&history.pushState&&history.replaceState("","","?"+d.options.param+"="+f)})});var b={};if(window.location.href.replace(new RegExp("([^?=&]+)(=([^&]*))?","g"),function(a,c,d,e){b[c]=e}),b[d.options.param]){var e=a("a[href='#"+b[d.options.param]+"']");e.closest(".tab-nav").find("a").removeClass("current").end().next(".tab-content").find("div").hide(),e.addClass("current"),a("#"+b[d.options.param]).show()}},d.init()},a.organicTabs.defaultOptions={speed:300,param:"tab"},a.fn.organicTabs=function(b){return this.each(function(){new a.organicTabs(this,b)})}}(jQuery),jQuery(document).ready(function(a){a("#cgc-edu-tabs").organicTabs();var b=cgc_exercise_meta.ajaxurl,c=cgc_exercise_meta.nonce;a("#cgc-exercise-vote-form").submit(function(d){d.preventDefault();var e={action:"process_grading",nonce:c};a.post(b,e,function(b){a("#cgc-edu-exercise--vote-results").html(b)})}),a("#cgc-exercise-submit").click(function(d){d.preventDefault();var e={action:"process_submission",nonce:c};a.post(b,e,function(a){alert(a)})})});