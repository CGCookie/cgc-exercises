!function(a){a.organicTabs=function(b,c){var d=this;d.$el=a(b),d.$nav=d.$el.find(".tab-nav"),d.init=function(){d.options=a.extend({},a.organicTabs.defaultOptions,c),a(".tab-hide").css({position:"relative",top:0,left:0,display:"none"}),d.$nav.delegate("a","click",function(b){b.preventDefault();var c=d.$el.find("a.current").attr("href").substring(1),e=a(this),f=e.attr("href").substring(1),g=d.$el.find(".tab-content"),h=g.height();g.height(h),f!=c&&0==d.$el.find(":animated").length&&d.$el.find("#"+c).fadeOut(d.options.speed,function(){d.$el.find("#"+f).fadeIn(d.options.speed);var a=d.$el.find("#"+f).height();g.animate({height:a},d.options.speed),d.$el.find(".tab-nav li a").removeClass("current"),e.addClass("current"),window.history&&history.pushState&&history.replaceState("","","?"+d.options.param+"="+f)})});var b={};if(window.location.href.replace(new RegExp("([^?=&]+)(=([^&]*))?","g"),function(a,c,d,e){b[c]=e}),b[d.options.param]){var e=a("a[href='#"+b[d.options.param]+"']");e.closest(".tab-nav").find("a").removeClass("current").end().next(".tab-content").find(".tab-display").hide(),e.addClass("current"),a("#"+b[d.options.param]).show()}},d.init()},a.organicTabs.defaultOptions={speed:300,param:"tab"},a.fn.organicTabs=function(b){return this.each(function(){new a.organicTabs(this,b)})}}(jQuery),jQuery(document).ready(function(a){function b(){a(h).html("Sending...")}function c(b){a(h).hide(),a(h).html(b),a(h).fadeIn(),a(k).fadeOut(),a(g).find("h2").text("Congrats!"),a(g).find(".cgc-universal-modal--intro").text("Congrats on submitting your work to be graded by the community. That is huge first step in becoming better. Take a moment to share out your work or grade others in the exercise."),a(g).find("form").fadeOut(),a(g).find(h).after('<a href="">Share</a>')}function d(a){return l.test(a)?"youtube":o.test(a)?"metacafe":n.test(a)?"dailymotion":m.test(a)?"vimeo":!1}a("#cgc-edu-tabs").organicTabs();var e=cgc_exercise_meta.ajaxurl,f=(cgc_exercise_meta.nonce,a("#cgc-grading-modal")),g=a("#cgc-exercise-submission-modal"),h=a("#cgc-edu-exercise--submission-results");a("#cgc-exercise-vote-form input").click(function(){a(this).attr("checked",!0),a("#cgc-exercise-vote-form").trigger("submit")}),a("#cgc-exercise-vote-form").submit(function(b){b.preventDefault();var c=a(this).serialize();a.post(e,c,function(b){a("#cgc-edu-exercise--vote-results").html(b)}),a(f).reveal()}),a(".comment-cancel").click(function(a){a.preventDefault(),location.reload()}),a("#cgc-grading-modal textarea").bind("input propertychange",function(){a("#cgc-grading-modal #submit").addClass("active")});var i=a(".cgc-edu-upload--bar"),j=a(".cgc-edu-upload--percent"),k=a(".cgc-edu-upload--progress");k.hide(),a("#cgc-exercise-submit-form").submit(function(b){return""==a("#cgc-exercise-submit-form textarea").val()||""==a('#cgc-exercise-submit-form input[type="text"]').val()?(b.preventDefault(),a(h).text("All fields required!"),a('#cgc-exercise-submit-form textarea, #cgc-exercise-submit-form input[type="text"]').css({border:"1px solid red"}),!1):void 0}),a("#cgc-exercise-submit-form").ajaxForm({target:h,beforeSubmit:b,success:c,url:e,beforeSend:function(){var b="0%";k.fadeIn(),i.width(b),j.html(b),a(g).addClass("image-uploading")},uploadProgress:function(a,b,c,d){var e=d+"%";i.width(e),j.html(e)}}),a("#cgc-exercise-submit-form input:file").live("change",function(){var b=a(this).val().split("\\").pop();a(".filename").text(b)}),a("#exercise-video").live("change",function(){var b=a(this).val(),c=d(b);a(".exercise-video-source").addClass("icon-"+c+" "),a("#exercise-video-provider").val(c)});var l=/^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/,m=/^.*(vimeo\.com\/)((channels\/[A-z]+\/)|(groups\/[A-z]+\/videos\/))?([0-9]+)/,n=/^.+dailymotion.com\/(video|hub)\/([^_]+)[^#]*(#video=([^_&]+))?/,o=/^.*(metacafe\.com)(\/watch\/)(\d+)(.*)/i}),jQuery(window).load(function(){jQuery("#cgc-media-loading").remove()});