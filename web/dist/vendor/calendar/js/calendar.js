"use strict";Date.prototype.getWeek=function(t){if(t){var e=new Date(this.valueOf()),a=(this.getDay()+6)%7;e.setDate(e.getDate()-a+3);var o=e.valueOf();return e.setMonth(0,1),4!=e.getDay()&&e.setMonth(0,1+(4-e.getDay()+7)%7),1+Math.ceil((o-e)/6048e5)}var i=new Date(this.getFullYear(),0,1);return Math.ceil(((this.getTime()-i.getTime())/864e5+i.getDay()+1)/7)},Date.prototype.getMonthFormatted=function(){var t=this.getMonth()+1;return t<10?"0"+t:t},Date.prototype.getDateFormatted=function(){var t=this.getDate();return t<10?"0"+t:t},String.prototype.format||(String.prototype.format=function(){var t=arguments;return this.replace(/{(\d+)}/g,(function(e,a){return void 0!==t[a]?t[a]:e}))}),String.prototype.formatNum||(String.prototype.formatNum=function(t){for(var e=""+this;e.length<t;)e="0"+e;return e}),function(t){var e={tooltip_container:"body",width:"100%",view:"month",day:"now",time_start:"06:00",time_end:"22:00",time_split:"30",events_source:"",events_cache:!1,format12:!1,am_suffix:"AM",pm_suffix:"PM",tmpl_path:"tmpls/",tmpl_cache:!0,classes:{months:{inmonth:"cal-day-inmonth",outmonth:"cal-day-outmonth",saturday:"cal-day-weekend",sunday:"cal-day-weekend",holidays:"cal-day-holiday",today:"cal-day-today"},week:{workday:"cal-day-workday",saturday:"cal-day-weekend",sunday:"cal-day-weekend",holidays:"cal-day-holiday",today:"cal-day-today"}},modal:null,modal_type:"iframe",modal_title:null,views:{year:{slide_events:1,enable:1},month:{slide_events:1,enable:1},week:{enable:1},day:{enable:1},agenda:{enable:1}},merge_holidays:!1,display_week_numbers:!0,weekbox:!0,onAfterEventsLoad:function(t){},onBeforeEventsLoad:function(t){t()},onAfterViewLoad:function(t){},onAfterModalShown:function(t){},onAfterModalHidden:function(t){},events:[],templates:{year:"",month:"",week:"",day:"",agenda:""},stop_cycling:!1},a={first_day:2,week_numbers_iso_8601:!1,holidays:{"01-01":"New Year's Day","01+3*1":"Birthday of Dr. Martin Luther King, Jr.","02+3*1":"Washington's Birthday","05-1*1":"Memorial Day","04-07":"Independence Day","09+1*1":"Labor Day","10+2*1":"Columbus Day","11-11":"Veterans Day","11+4*4":"Thanksgiving Day","25-12":"Christmas"}},o={error_noview:"Calendar: View {0} not found",error_dateformat:'Calendar: Wrong date format {0}. Should be either "now" or "yyyy-mm-dd"',error_loadurl:"Calendar: Event URL is not set",error_where:'Calendar: Wrong navigation direction {0}. Can be only "next" or "prev" or "today"',error_timedevide:"Calendar: Time split parameter should divide 60 without decimals. Something like 10, 15, 30",no_events_in_day:"No events in this day.",title_year:"{0}",title_month:"{0} {1}",title_week:"week {0} of {1}",title_day:"{0} {1} {2}, {3}",week:"Week {0}",all_day:"All day",time:"Time",events:"Events",before_time:"Ends before timeline",after_time:"Starts after timeline",m0:"January",m1:"February",m2:"March",m3:"April",m4:"May",m5:"June",m6:"July",m7:"August",m8:"September",m9:"October",m10:"November",m11:"December",ms0:"Jan",ms1:"Feb",ms2:"Mar",ms3:"Apr",ms4:"May",ms5:"Jun",ms6:"Jul",ms7:"Aug",ms8:"Sep",ms9:"Oct",ms10:"Nov",ms11:"Dec",d0:"Sunday",d1:"Monday",d2:"Tuesday",d3:"Wednesday",d4:"Thursday",d5:"Friday",d6:"Saturday"},i="";try{"object"==t.type(window.jstz)&&"function"==t.type(jstz.determine)&&(i=jstz.determine().name(),"string"!==t.type(i)&&(i=""))}catch(t){}function s(e,o){var i=null!=e.options[o]?e.options[o]:null,s=null!=e.locale[o]?e.locale[o]:null;if("holidays"==o&&e.options.merge_holidays){var n={};return t.extend(!0,n,s||a.holidays),i&&t.extend(!0,n,i),n}return null!=i?i:null!=s?s:a[o]}function n(e,a){var o=[],i=s(e,"holidays");for(var l in i)o.push(l+":"+i[l]);if(o.push(a),(o=o.join("|"))in n.cache)return n.cache[o];var p=[];return t.each(i,(function(e,o){var i=null,s=null,n=!1;if(t.each(e.split(">"),(function(t,o){var l,p=null;if(l=/^(\d\d)-(\d\d)$/.exec(o))p=new Date(a,parseInt(l[2],10)-1,parseInt(l[1],10));else if(l=/^(\d\d)-(\d\d)-(\d\d\d\d)$/.exec(o))parseInt(l[3],10)==a&&(p=new Date(a,parseInt(l[2],10)-1,parseInt(l[1],10)));else if(l=/^easter(([+\-])(\d+))?$/.exec(o))p=function(t,e){var a=t%19,o=Math.floor(t/100),i=t%100,s=Math.floor(o/4),n=o%4,r=Math.floor((o+8)/25),l=(19*a+o-s-Math.floor((o-r+1)/3)+15)%30,p=(32+2*n+2*Math.floor(i/4)-l-i%4)%7,d=l+p+7*Math.floor((a+11*l+22*p)/451)+114,h=Math.floor(d/31)-1;return new Date(t,h,d%31+1+(e||0),0,0,0)}(a,l[1]?parseInt(l[1],10):0);else if(l=/^(\d\d)([+\-])([1-5])\*([0-6])$/.exec(o)){var d=parseInt(l[1],10)-1,h=l[2],c=parseInt(l[3]),u=parseInt(l[4]);switch(h){case"+":for(var m=new Date(a,d,-6);m.getDay()!=u;)m=new Date(m.getFullYear(),m.getMonth(),m.getDate()+1);p=new Date(m.getFullYear(),m.getMonth(),m.getDate()+7*c);break;case"-":for(m=new Date(a,d+1,7);m.getDay()!=u;)m=new Date(m.getFullYear(),m.getMonth(),m.getDate()-1);p=new Date(m.getFullYear(),m.getMonth(),m.getDate()-7*c)}}if(!p)return r("Unknown holiday: "+e),n=!0,!1;switch(t){case 0:i=p;break;case 1:if(p.getTime()<=i.getTime())return r("Unknown holiday: "+e),n=!0,!1;s=p;break;default:return r("Unknown holiday: "+e),n=!0,!1}})),!n){var l=[];if(s)for(var d=new Date(i.getTime());d.getTime()<=s.getTime();d.setDate(d.getDate()+1))l.push(new Date(d.getTime()));else l.push(i);p.push({name:o,days:l})}})),n.cache[o]=p,n.cache[o]}function r(e){"object"==t.type(window.console)&&"function"==t.type(window.console.warn)&&window.console.warn("[Bootstrap-Calendar] "+e)}function l(a,o){return this.options=t.extend(!0,{position:{start:new Date,end:new Date}},e,a),this.setLanguage(this.options.language),this.context=o,o.css("width",this.options.width).addClass("cal-context"),this.view(),this}function p(e,a,o,i){e.stopPropagation();var s=(a=t(a)).closest(".cal-cell"),n=s.closest(".cal-before-eventlist"),r=s.data("cal-row");a.fadeOut("fast"),o.slideUp("fast",(function(){var e=t(".events-list",s);o.html(i.options.templates["events-list"]({cal:i,events:i.getEventsBetween(parseInt(e.data("cal-start")),parseInt(e.data("cal-end")))})),n.after(o),i.activecell=t("[data-cal-date]",s).text(),t("#cal-slide-tick").addClass("tick"+r).show(),o.slideDown("fast",(function(){t("body").one("click",(function(){o.slideUp("fast"),i.activecell=0}))}))})),setTimeout((function(){t("a.event-item").mouseenter((function(){t('a[data-event-id="'+t(this).data("event-id")+'"]').closest(".cal-cell1").addClass("day-highlight dh-"+t(this).data("event-class"))})),t("a.event-item").mouseleave((function(){t("div.cal-cell1").removeClass("day-highlight dh-"+t(this).data("event-class"))})),i._update_modal()}),400)}n.cache={},l.prototype.setOptions=function(e){t.extend(this.options,e),"language"in e&&this.setLanguage(e.language),"modal"in e&&this._update_modal()},l.prototype.setLanguage=function(e){window.calendar_languages&&e in window.calendar_languages?(this.locale=t.extend(!0,{},o,calendar_languages[e]),this.options.language=e):(this.locale=o,delete this.options.language)},l.prototype._render=function(){this.context.html(""),this._loadTemplate(this.options.view),this.stop_cycling=!1;var t={};if("agenda"!=this.options.view){t.cal=this,t.day=1,1==s(this,"first_day")?t.days_name=[this.locale.d1,this.locale.d2,this.locale.d3,this.locale.d4,this.locale.d5,this.locale.d6,this.locale.d0]:t.days_name=[this.locale.d0,this.locale.d1,this.locale.d2,this.locale.d3,this.locale.d4,this.locale.d5,this.locale.d6];var e=parseInt(this.options.position.start.getTime()),a=parseInt(this.options.position.end.getTime());switch(t.events=this.getEventsBetween(e,a),this.options.view){case"month":break;case"week":case"day":this._calculate_hour_minutes(t)}t.start=new Date(this.options.position.start.getTime()),t.lang=this.locale}else t.cal=this,t.agenda=this.options.events,t.lang=this.locale;this.context.append(this.options.templates[this.options.view](t)),this._update()},l.prototype._format_hour=function(t){var e=t.split(":"),a=parseInt(e[0]),o=parseInt(e[1]),i="";return this.options.format12&&(i=a<12?this.options.am_suffix:this.options.pm_suffix,0==(a%=12)&&(a=12)),a.toString().formatNum(2)+":"+o.toString().formatNum(2)+i},l.prototype._format_time=function(t){return this._format_hour(t.getHours()+":"+t.getMinutes())},l.prototype._calculate_hour_minutes=function(e){var a=this,o=parseInt(this.options.time_split),i=60/o,s=Math.min(i,1);(i>=1&&i%1!=0||i<1&&1440/o%1!=0)&&t.error(this.locale.error_timedevide);var n=this.options.time_start.split(":"),l=this.options.time_end.split(":");"00"===l[0]&&"00"===l[1]?e.hours=24*s:e.hours=(parseInt(l[0])-parseInt(n[0]))*s;var p=e.hours*i-parseInt(n[1])/o,d=6e4*o,h=new Date(this.options.position.start.getTime());h.setHours(n[0]),h.setMinutes(n[1]);var c=new Date(this.options.position.end.getTime()-864e5);"00"===l[0]&&"00"===l[1]?(c.setHours(l[0]),c.setMinutes(l[1]),c.setTime(c.getTime()+864e5)):(c.setHours(l[0]),c.setMinutes(l[1])),e.all_day=[],e.by_hour=[],e.after_time=[],e.before_time=[],t.each(e.events,(function(t,o){var i=new Date(parseInt(o.start)),s=new Date(parseInt(o.end));if(o.start_hour=moment(i).format(jsTimeFormat),o.end_hour=moment(s).format(jsTimeFormat),o.start<h.getTime()&&(r(1),o.start_hour=i.getDate()+" "+a.locale["ms"+i.getMonth()]+" "+o.start_hour),o.end>c.getTime()&&(r(1),o.end_hour=s.getDate()+" "+a.locale["ms"+s.getMonth()]+" "+o.end_hour),o.start<h.getTime()&&o.end>c.getTime())e.all_day.push(o);else if(o.end<h.getTime())e.before_time.push(o);else if(o.start>c.getTime())e.after_time.push(o);else{var n=h.getTime()-o.start;o.top=n>=0?0:Math.abs(n)/d;var l=Math.abs(p-o.top),u=(o.end-o.start)/d;n>=0&&(u=(o.end-h.getTime())/d),o.lines=u,u>l&&(o.lines=l),e.by_hour.push(o)}}))},l.prototype._hour_min=function(t){var e=this.options.time_start.split(":"),a=parseInt(this.options.time_split),o=60/a;return 0==t?o-parseInt(e[1])/a:o},l.prototype._hour=function(t,e){var a=this.options.time_start.split(":"),o=parseInt(this.options.time_split),i=""+(parseInt(a[0])+t*Math.max(o/60,1)),s=""+o*e,n=this._format_hour(i.formatNum(2)+":"+s.formatNum(2));return moment(n,"HH:mm").format(jsTimeFormat)},l.prototype._week=function(e){this._loadTemplate("week-days");var a={},o=parseInt(this.options.position.start.getTime()),i=parseInt(this.options.position.end.getTime()),n=[],r=s(this,"first_day");return t.each(this.getEventsBetween(o,i),(function(t,e){var a=new Date(parseInt(e.start));a.setHours(0,0,0,0);var i=new Date(parseInt(e.end));i.setHours(23,59,59,999),e.start_day=new Date(parseInt(a.getTime())).getDay(),1==r&&(e.start_day=(e.start_day+6)%7),i.getTime()-a.getTime()<=864e5?e.days=1:e.days=(i.getTime()-a.getTime())/864e5,a.getTime()<o&&(e.days=e.days-(o-a.getTime())/864e5,e.start_day=0),e.days=Math.ceil(e.days),e.start_day+e.days>7&&(e.days=7-e.start_day),n.push(e)})),a.events=n,a.cal=this,this.options.templates["week-days"](a)},l.prototype._month=function(t){this._loadTemplate("year-month");var e={cal:this},a=t+1;e.data_day=this.options.position.start.getFullYear()+"-"+(a<10?"0"+a:a)+"-01",e.month_name=this.locale["m"+t];var o=new Date(this.options.position.start.getFullYear(),t,1,0,0,0);return e.start=parseInt(o.getTime()),e.end=parseInt(new Date(this.options.position.start.getFullYear(),t+1,1,0,0,0).getTime()),e.events=this.getEventsBetween(e.start,e.end),this.options.templates["year-month"](e)},l.prototype._day=function(e,a){this._loadTemplate("month-day");var o={tooltip:"",cal:this},i=this.options.classes.months.outmonth,n=this.options.position.start.getDay();2==s(this,"first_day")?n++:n=0==n?7:n,a=a-n+1;var r=new Date(this.options.position.start.getFullYear(),this.options.position.start.getMonth(),a,0,0,0),l=!1;a>0&&(l=!0,i=this.options.classes.months.inmonth);var p=new Date(this.options.position.end.getTime()-1).getDate();a+1>p&&(this.stop_cycling=!0),a>p&&(l=!1,a-=p,i=this.options.classes.months.outmonth),i=t.trim(i+" "+this._getDayClass("months",r)),a<=0&&(a=new Date(this.options.position.start.getFullYear(),this.options.position.start.getMonth(),0).getDate()-Math.abs(a),i+=" cal-month-first-row");var d=this._getHoliday(r);return!1!==d&&(o.tooltip=d),o.data_day=r.getFullYear()+"-"+r.getMonthFormatted()+"-"+(a<10?"0"+a:a),o.cls=i,o.day=a,o.start=parseInt(r.getTime()),o.end=parseInt(o.start+864e5),o.events=l?this.getEventsBetween(o.start,o.end):[],this.options.templates["month-day"](o)},l.prototype._layouts=function(t,e,a){this._loadTemplate("agenda-layouts");for(var o={tooltip:"",cal:this},i=[],s=0,n=0;n<t.length;n++)t[n].isPriority>s&&t[n].eventTypeId==a&&(s=t[n].isPriority);for(n=0;n<t.length;n++)if(t[n].eventTypeId==a&&0!=t[n].layoutId){var r=e[t[n].layoutId],l=t[n],p=0,d="",h="";l.isPriority==s&&0!=s?(p=1,d="fa-bullseye event-important",h="high-priority"):l.isPriority<s&&(p=-1,h="low-priority"),i.push({eventPriorityFlag:p,eventId:l.eventId,layoutId:l.layoutId,layoutName:r.layout,layoutStatus:r.status,eventFromDt:moment(l.fromDt,"X").tz?moment(l.fromDt,"X").tz(timezone).format(jsDateFormat):moment(l.fromDt,"X").format(jsDateFormat),eventToDt:moment(l.toDt,"X").tz?moment(l.toDt,"X").tz(timezone).format(jsDateFormat):moment(l.toDt,"X").format(jsDateFormat),eventDayPartId:l.dayPartId,isAlways:l.isAlways,isCustom:l.isCustom,layoutDuration:r.duration,layoutDisplayOrder:l.displayOrder,eventPriority:l.isPriority,itemClass:h,itemIcon:d})}return i.length>0?(o.layouts=i,o.layouts.type=a,this.options.templates["agenda-layouts"](o)):""},l.prototype._displaygroups=function(t,e){this._loadTemplate("agenda-displaygroups");for(var a={tooltip:"",cal:this},o={},i=0,s=0;s<t.length;s++)o[t[s].displayGroupId]=e[t[s].displayGroupId],i++;return i>0?(a.displaygroups=o,this.options.templates["agenda-displaygroups"](a)):""},l.prototype._campaigns=function(t,e){this._loadTemplate("agenda-campaigns");for(var a={tooltip:"",cal:this},o={},i=0,s=0;s<t.length;s++)void 0!==e[t[s].campaignId]&&(o[t[s].campaignId]=e[t[s].campaignId],i++);return i>0?(a.campaigns=o,this.options.templates["agenda-campaigns"](a)):""},l.prototype._breadcrumbTrail=function(t,e,a){this._loadTemplate("breadcrumb-trail");for(var o={},i={},s=e.results[e.selectedDisplayGroup],n=s.events,r=0;r<n.length;r++)n[r].layoutId==t&&n[r].eventId==a&&(i=n[r]);var l=s.layouts[t];o.layout={link:l.link,name:l.layout},void 0!==s.campaigns[i.campaignId]&&(o.campaign={link:"",name:s.campaigns[i.campaignId].campaign}),o.schedule={link:i.link,fromDt:1e3*i.fromDt,toDt:1e3*i.toDt},o.displayGroups=[];var p=i.displayGroupId;for(e.selectedDisplayGroup!=p&&void 0!==s.displayGroups[e.selectedDisplayGroup]&&o.displayGroups.push({link:"",name:s.displayGroups[e.selectedDisplayGroup].displayGroup}),r=i.intermediateDisplayGroupIds.length;r>=0;r--){var d=i.intermediateDisplayGroupIds[r];void 0!==s.displayGroups[d]&&o.displayGroups.push({link:"",name:s.displayGroups[d].displayGroup})}return void 0!==s.displayGroups[p]&&o.displayGroups.push({link:"",name:s.displayGroups[p].displayGroup}),this.options.templates["breadcrumb-trail"](o)},l.prototype._getHoliday=function(e){var a=!1;return t.each(n(this,e.getFullYear()),(function(){var o=!1;if(t.each(this.days,(function(){if(this.toDateString()==e.toDateString())return o=!0,!1})),o)return a=this.name,!1})),a},l.prototype._getHolidayName=function(t){var e=this._getHoliday(t);return!1===e?"":e},l.prototype._getDayClass=function(t,e){var a=this,o=function(e,o){var i;"string"==typeof(i=a.options.classes&&t in a.options.classes&&e in a.options.classes[t]?a.options.classes[t][e]:"")&&i.length&&o.push(i)},i=[];switch(e.toDateString()==(new Date).toDateString()&&o("today",i),!1!==this._getHoliday(e)&&o("holidays",i),e.getDay()){case 0:o("sunday",i);break;case 6:o("saturday",i)}return o(e.toDateString(),i),i.join(" ")},l.prototype.view=function(t){if(t){if(!this.options.views[t].enable)return;this.options.view=t}this._init_position(),this._loadEvents(),this._render(),this.options.onAfterViewLoad.call(this,this.options.view)},l.prototype.navigate=function(e,a){var o=t.extend({},this.options.position);if("next"==e)switch(this.options.view){case"year":o.start.setFullYear(this.options.position.start.getFullYear()+1);break;case"month":o.start.setMonth(this.options.position.start.getMonth()+1);break;case"week":o.start.setDate(this.options.position.start.getDate()+7);break;case"day":case"agenda":o.start.setDate(this.options.position.start.getDate()+1)}else if("prev"==e)switch(this.options.view){case"year":o.start.setFullYear(this.options.position.start.getFullYear()-1);break;case"month":o.start.setMonth(this.options.position.start.getMonth()-1);break;case"week":o.start.setDate(this.options.position.start.getDate()-7);break;case"day":case"agenda":o.start.setDate(this.options.position.start.getDate()-1)}else"today"==e?o.start.setTime((new Date).getTime()):"date"==e?o.start.setTime(a.format("x")):t.error(this.locale.error_where.format(e));this.options.day=o.start.getFullYear()+"-"+o.start.getMonthFormatted()+"-"+o.start.getDateFormatted(),this.view(),_.isFunction(a)&&a()},l.prototype._init_position=function(){var e,a,o;if("now"==this.options.day){var i=new Date;e=i.getFullYear(),a=i.getMonth(),o=i.getDate()}else if(this.options.day.match(/^\d{4}-\d{2}-\d{2}$/g)){var n=this.options.day.split("-");e=parseInt(n[0],10),a=parseInt(n[1],10)-1,o=parseInt(n[2],10)}else t.error(this.locale.error_dateformat.format(this.options.day));switch(this.options.view){case"year":this.options.position.start.setTime(new Date(e,0,1).getTime()),this.options.position.end.setTime(new Date(e+1,0,1).getTime());break;case"month":this.options.position.start.setTime(new Date(e,a,1).getTime()),this.options.position.end.setTime(new Date(e,a+1,1).getTime());break;case"day":this.options.position.start.setTime(new Date(e,a,o).getTime()),this.options.position.end.setTime(new Date(e,a,o+1).getTime());break;case"week":var r,l=new Date(e,a,o);r=1==s(this,"first_day")?l.getDate()-(l.getDay()+6)%7:l.getDate()-l.getDay(),this.options.position.start.setTime(new Date(e,a,r).getTime()),this.options.position.end.setTime(new Date(e,a,r+7).getTime());break;case"agenda":this.options.position.start.setTime(new Date(e,a,o).getTime()),this.options.position.end.setTime(new Date(e,a,o).getTime());break;default:t.error(this.locale.error_noview.format(this.options.view))}return this},l.prototype.getTitle=function(){var t=this.options.position.start;switch(this.options.view){case"year":return this.locale.title_year.format(t.getFullYear());case"month":return this.locale.title_month.format(this.locale["m"+t.getMonth()],t.getFullYear());case"week":return this.locale.title_week.format(t.getWeek(s(this,"week_numbers_iso_8601")),t.getFullYear());case"day":case"agenda":return this.locale.title_day.format(this.locale["d"+t.getDay()],t.getDate(),this.locale["m"+t.getMonth()],t.getFullYear())}},l.prototype.getYear=function(){return this.options.position.start.getFullYear()},l.prototype.getMonth=function(){var t=this.options.position.start;return this.locale["m"+t.getMonth()]},l.prototype.getDay=function(){var t=this.options.position.start;return this.locale["d"+t.getDay()]},l.prototype.isToday=function(){var t=(new Date).getTime();return t>this.options.position.start&&t<this.options.position.end},l.prototype.getStartDate=function(){return this.options.position.start},l.prototype.getEndDate=function(){return this.options.position.end},l.prototype._loadEvents=function(){var e,a=this,o=null;switch("events_source"in this.options&&""!==this.options.events_source?o=this.options.events_source:"events_url"in this.options&&(o=this.options.events_url,r("The events_url option is DEPRECATED and it will be REMOVED in near future. Please use events_source instead.")),t.type(o)){case"function":e=function(){return o(a.options.position.start,a.options.position.end,i)};break;case"array":e=function(){return[].concat(o)}}e||t.error(this.locale.error_loadurl),this.options.onBeforeEventsLoad.call(this,(function(){a.options.events.length&&a.options.events_cache||(a.options.events=e(),a.options.events.sort((function(t,e){var a;return 0==(a=t.start-e.start)&&(a=t.end-e.end),a}))),a.options.onAfterEventsLoad.call(a,a.options.events)}))},l.prototype._templatePath=function(t){return"function"==typeof this.options.tmpl_path?this.options.tmpl_path(t):this.options.tmpl_path+t+".html"},l.prototype._loadTemplate=function(e){this.options.templates[e]||(this.options.templates[e]=_.template(t("#"+this._templatePath(e)).html()))},l.prototype._update=function(){var e=this;t('*[data-toggle="tooltip"]').tooltip({container:this.options.tooltip_container}),t("*[data-cal-date]").click((function(){var a=t(this).data("cal-view");e.options.day=t(this).data("cal-date"),e.view(a)})),t(".cal-cell").dblclick((function(){var a=t("[data-cal-date]",this).data("cal-view");e.options.day=t("[data-cal-date]",this).data("cal-date"),e.view(a)})),this["_update_"+this.options.view](),this._update_modal()},l.prototype._update_modal=function(){t("a[data-event-id]",this.context).unbind("click"),"XiboFormButton"!=!t("a[data-event-id]",this.context).attr("data-event-class")&&t("a[data-event-id]",this.context).on("click",(function(e){e.preventDefault(),e.stopPropagation();var a=t(this).data("eventStart"),o=t(this).data("eventEnd");if(void 0!==a&&void 0!==o){var i={eventStart:a,eventEnd:o};XiboFormRender(t(this),i)}else XiboFormRender(t(this))}))},l.prototype._update_day=function(){t("#cal-day-panel").height(t("#cal-day-panel-hour").height());var e=9*this._hour(22,0).length+20;t("#cal-day-panel").css("padding-left",parseInt(e)+"px"),t("#cal-day-panel-hour").css("margin-left",-parseInt(e)+"px")},l.prototype._update_week=function(){},l.prototype._update_year=function(){this._update_month_year()},l.prototype._update_agenda=function(){},l.prototype._update_month=function(){this._update_month_year();var e=this;if(1==this.options.weekbox){var a=t(document.createElement("div")).attr("id","cal-week-box"),o=this.options.position.start.getFullYear()+"-"+this.options.position.start.getMonthFormatted()+"-";e.context.find(".cal-month-box .cal-row-fluid").on("mouseenter",(function(){var i=new Date(e.options.position.start),n=t(".cal-cell1:first-child .cal-month-day",this),r=n.hasClass("cal-month-first-row")?1:t("[data-cal-date]",n).text();i.setDate(parseInt(r)),r=r<10?"0"+r:r,a.html(e.locale.week.format(1==e.options.display_week_numbers?i.getWeek(s(e,"week_numbers_iso_8601")):"")),a.attr("data-cal-week",o+r).show().appendTo(n)})).on("mouseleave",(function(){a.hide()})),a.click((function(){e.options.day=t(this).data("cal-week"),e.view("week")}))}e.context.find("a.event").mouseenter((function(){t('a[data-event-id="'+t(this).data("event-id")+'"]').closest(".cal-cell1").addClass("day-highlight dh-"+t(this).data("event-class"))})),e.context.find("a.event").mouseleave((function(){t("div.cal-cell1").removeClass("day-highlight dh-"+t(this).data("event-class"))}))},l.prototype._update_month_year=function(){if(this.options.views[this.options.view].slide_events){var e=this,a=t(document.createElement("div")).attr("id","cal-day-tick").html('<i class="icon-chevron-down glyphicon glyphicon-chevron-down"></i>');e.context.find(".cal-month-day, .cal-year-box .span3").on("mouseenter",(function(){0!=t(".events-list",this).length&&t(this).children("[data-cal-date]").text()!=e.activecell&&a.show().appendTo(this)})).on("mouseleave",(function(){a.hide()})).on("click",(function(i){0!=t(".events-list",this).length&&t(this).children("[data-cal-date]").text()!=e.activecell&&p(i,a,o,e)}));var o=t(document.createElement("div")).attr("id","cal-slide-box");o.hide().click((function(t){t.stopPropagation()})),this._loadTemplate("events-list"),a.click((function(a){p(a,t(this),o,e)}))}},l.prototype.getEventsBetween=function(e,a){var o=[],i=moment(e,"x"),s=moment(a,"x");return t.each(this.options.events,(function(){if(null==this.start)return!0;let t=moment(this.scheduleEvent.fromDt,"YYYY-MM-DD HH:mm:ss"),e=null!=this.end?moment(this.scheduleEvent.toDt,"YYYY-MM-DD HH:mm:ss"):t;t.isBefore(s)&&e.isSameOrAfter(i)&&(this.start=t.valueOf(),this.end=e.valueOf(),o.push(this))})),o},t.fn.calendar=function(t){return new l(t,this)}}(jQuery);