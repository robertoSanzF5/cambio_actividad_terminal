window.pui||(window.pui={});window.pui.baseVersion="6";window.pui.fixPackVersion="3.3";window.pui.version=window.pui.baseVersion+"."+window.pui.fixPackVersion;window.init=t;window.checkUpdate=u;Ext.BLANK_IMAGE_URL="/ext-3.4.0/resources/images/default/s.gif";Ext.onReady(function(){});
var v=Array("RPG Development",1,"","","Start Visual Designer",2,"/profoundui/designer","","Launch Session",2,"/profoundui/start","","Launch Program Anonymously",2,"","anon","Online Documentation",2,"http://www.profoundlogic.com/docs","","Getting Started Videos",2,"http://www.youtube.com/ProfoundLogicTV","","Getting Started Guide",2,"http://www.profoundlogic.com/docs/display/PUI/Getting+Started","","Forum",2,"http://www.profoundlogic.com/forums/phpbb3/viewforum.php?f=53","","PHP Development",1,"",
"","Start Visual Designer",2,"/profoundui/viewdesigner","","Online Documentation",2,"http://www.profoundlogic.com/docs/display/PUI/PHP+Coding","","Forum",2,"http://www.profoundlogic.com/forums/phpbb3/viewforum.php?f=53","","Node.js Development",1,"","","Online Documentation",2,"http://www.profoundlogic.com/docs/display/PUI/Profound.js+Overview","","Forum",2,"http://www.profoundlogic.com/forums/phpbb3/viewforum.php?f=59","","Universal Display Files",1,"","","Start Editor",2,"/profoundui/universal",
"","Launch Program",2,"","uni","Online Documentation",2,"http://www.profoundlogic.com/docs/display/PUI/Universal+Display+Files","","JumpStart",1,"","","Start JumpStart",2,"/profoundui/jumpstart","","Online Documentation",2,"http://www.profoundlogic.com/docs/display/PUI/JumpStart",""),w=Array("Web-enable 5250 On-the-fly",1,"","","Start Genie Administrator",2,"/profoundui/genieadmin","","Launch Genie Skin",2,"","skins","Genie Development Guide",2,"http://www.profoundlogic.com/docs/display/PUI/Genie",
"","Forum",2,"http://www.profoundlogic.com/forums/phpbb3/viewforum.php?f=38","","Convert Green Screens",1,"","","Convert DDS Source",2,"/profoundui/designer?convert=true","","Forum",2,"http://www.profoundlogic.com/forums/phpbb3/viewforum.php?f=53",""),x=Array("Create a Web Portal",1,"","","Start Atrium",2,"/profoundui/atrium","","Atrium User Guide",2,"http://www.profoundlogic.com/docs/display/PUI/Atrium","","Forum",2,"http://www.profoundlogic.com/forums/phpbb3/viewforum.php?f=52","");
function t(){document.getElementById("version").innerHTML=pui.baseVersion+", Fix Pack "+pui.fixPackVersion;z("devMenu",v,"Orange");z("modMenu",w,"Green");z("intMenu",x,"Blue");A("skins","Green");B("anon","Orange");C();D(document.getElementById("Green1"),"Green");D(document.getElementById("Green2"),"Green");D(document.getElementById("Blue1"),"Blue");u(true)}
function z(f,c,a){for(var e="/profoundui/proddata/images/splash/"+a+".gif",g=document.getElementById(f),d,b,i,h,j=0,o=0,k=0,l=0;l<c.length/4;l++){b=c[l*4];d=c[l*4+1];i=c[l*4+2];h=c[l*4+3];var m=document.createElement("img");m.id=f+"b"+l;m.src=e;var n=document.createElement("a");n.innerHTML=b;if(i!=""){n.href=i;n.target="_blank"}b=document.createElement("div");b.className="menuLine level"+d;b.name="level"+d;if(h!="")b.id=h;if(d==1){d=document.createElement("img");d.src="/profoundui/proddata/images/splash/"+
a+"-Plus.png";d.className="expander";b.appendChild(d);b.onclick=function(){D(this,a)};if(a=="Orange"){j++;b.id=a+j}if(a=="Green"){o++;b.id=a+o}if(a=="Blue"){k++;b.id=a+k}}else if(d==2&&h!=""){d=document.createElement("img");d.src="/profoundui/proddata/images/splash/"+a+"-Plus.png";d.className="expander";b.appendChild(d);b.onclick=function(){var p=this,q=p.firstChild,r=p.name,s;if(q.src.indexOf("Plus.png")>=0){s="block";q.src="/profoundui/proddata/images/splash/"+a+"-Minus.png"}else{s="none";q.src=
"/profoundui/proddata/images/splash/"+a+"-Plus.png"}for(;p.nextSibling&&p.nextSibling.name!=r;){p=p.nextSibling;p.style.display=s}};b.style.display="none"}else{b.style.display="none";b.appendChild(m)}b.appendChild(n);g.appendChild(b)}}
function D(f,c){var a=f.firstChild,e=f.name,g,d;if(a.src.indexOf("Plus.png")>=0){g="block";a.src="/profoundui/proddata/images/splash/"+c+"-Minus.png"}else{g="none";a.src="/profoundui/proddata/images/splash/"+c+"-Plus.png"}if(g=="none")for(;f.nextSibling&&f.nextSibling.name!=e;){f=f.nextSibling;f.style.display=g}else for(;f.nextSibling&&f.nextSibling.name!=e;){f=f.nextSibling;f.style.display=f.name=="level3"?d:g;if(f.name=="level2"&&f.id!="")d=f.firstChild.src.indexOf("Plus")>=0?"none":"block";else if(f.name!=
"level3")d="block"}}function A(f,c){var a=new pui.Ajax("/profoundui/PUI0004001.pgm");a.async=true;a.method="POST";a.onfail=function(){alert("Error Loading Skins")};a.onsuccess=function(){for(var e=eval("("+a.a()+")"),g=document.getElementById(f),d,b,i="/profoundui/proddata/images/splash/"+c+".gif",h=0;h<e.length;h++){d=e[h];b="/profoundui/genie?skin="+e[h];E(g,d,b,i)}};a.send()}
function B(f,c){var a=new pui.Ajax("/profoundui/PUI0001105.pgm");a.async=true;a.method="POST";a.postData="Mode=load";a.onfail=function(){alert("Error Loading Anonymous Programs")};a.onsuccess=function(){for(var e=eval("("+a.a()+")").programs,g=document.getElementById(f),d,b,i="/profoundui/proddata/images/splash/"+c+".gif",h=0;h<e.length;h++){d=e[h].split("/");d=d.length==2?d[0].toLowerCase()+" / "+d[1]:e[h];b="/profoundui/start?pgm="+e[h];E(g,d,b,i)}};a.send()}
function C(){var f=new pui.Ajax("/profoundui/PUI0001105.pgm");f.async=true;f.method="POST";f.postData="Mode=load&set=uni";f.onfail=function(){alert("Error Loading Universal Display File Programs")};f.onsuccess=function(){for(var c=eval("("+f.a()+")").programs,a=document.getElementById("uni"),e,g,d=0;d<c.length;d++){var b=c[d].uri;e=b;g=HTTPS=="ON"?"https://":"http://";g+=HTTP_HOST;g+=c[d].auth?PUI_UNIVERSAL_AUTH:PUI_UNIVERSAL;g+=b;E(a,e,g,"/profoundui/proddata/images/splash/Orange.gif")}};f.send()}
function E(f,c,a,e){var g=document.createElement("img");g.src=e;e=document.createElement("a");e.href=a;e.target="_blank";e.innerHTML=c;c=document.createElement("div");c.className="menuLine level3";c.name="level3";c.style.display="none";c.appendChild(g);c.appendChild(e);f.nextSibling?f.parentNode.insertBefore(c,f.nextSibling):f.parentNode.appendChild(c)}
function u(f){var c;if(!f){c=new Ext.LoadMask(Ext.getBody());c.msg="Checking for udpate...";c.show()}(new Ext.data.ScriptTagProxy({url:UPDATE_SITE+"prdversion.rpgsp",timeout:1E4})).doRequest("read",null,{product:"profoundui",version:window.pui.version,system:SYSTEM,serial:SERIAL,pgroup:PGROUP,os:OSRELEASE},new Ext.data.JsonReader({root:"response"},Ext.data.Record.create([{name:"version"}])),function(a,e,g){f||c.hide();if(g==true){a=a.records[0].get("version");if(window.pui.version!=a||!f)F(pui.version,
a)}else f||Ext.MessageBox.show({title:"Error",icon:Ext.MessageBox.ERROR,buttons:Ext.MessageBox.OK,msg:'Unable to connect to the Profound UI update server. Either your PC is not connected to the Internet or the update server is temporarily unavailable.<br /><br />For information on Profound UI, including product updates, please contact Profound Logic.<br /><br />Toll free: 1-877-224-7768<br />Email: <a href="mailto:sales@profoundlogic.com&subject=Profound UI">sales@profoundlogic.com</a><br />Web: <a href="http://www.profoundlogic.com" target="_blank">www.profoundlogic.com</a>'})})}
function F(f,c){var a,e=c.split(".");a=e.shift();e=e.join(".");if(e==="0.0")e="0";if(f==c)a="You have the most current version of Profound UI (Version "+a+", Fix Pack "+e+").";else{a="A new release of Profound UI (Version "+a+", Fix Pack "+e+") is available.";a+='<br /><br /><a href="http://www.profoundlogic.com/download?product=profoundui" target="_blank">Click here</a> to download the new release.'}a+='<br /><br /><a href="'+UPDATE_SITE+'versions.rpgsp?product=profoundui" target="_blank">Click here</a> to see the version history.';
var g=new Ext.Window({title:"Profound UI",width:425,modal:true,resizable:false,closable:true,items:[new Ext.Panel({bodyStyle:"padding: 5px;",html:a})],buttons:[{text:"OK",handler:function(){g.close()}}]});g.show()};function G(f){this.onready=this.onfail=this.onsuccess=this.suppressAlert=this.password=this.user=this.async=this.url=this.reqData=this.postData=this.method=null;this.headers={};this.params=null;this.sendAsBinary=true;var c=this.overrideMimeType=null,a=null,e=null,g=null,d=typeof pui!="undefined"&&typeof pui.alert!="undefined"?pui.alert:alert,b=this;if(typeof f=="object")for(var i in f){if(typeof this[i]!="undefined")this[i]=f[i]}else this.url=f;if(window.XMLHttpRequest)c=new XMLHttpRequest;else if(window.ActiveXObject)c=
new ActiveXObject("Microsoft.XMLHTTP");else{d("Ajax request error: Unsupported browser.");return}this.send=this.send=function(){function h(){if(c.readyState==4){a=c.status+" - "+c.statusText;b.onready!=null&&b.onready(b);if(c.status==200){e=true;b.onsuccess!=null&&b.onsuccess(b)}else{e=false;b.suppressAlert!=true&&d(a);b.onfail!=null&&b.onfail(b)}g=false}}var j=null,o=null,k=null;if(b.method==null)j="GET";else if(typeof b.method!="string"&&(b.method.toUpperCase()!="GET"||b.method.toUpperCase()!="POST"||
b.method.toUpperCase()!="PUT")){d('Invalid value for property: "method".');return}else j=b.method.toUpperCase();if(j=="POST"||j=="PUT"){if(b.reqData!=null)k=b.reqData;else if(b.postData!=null)k=b.postData;if(k!=null){if(typeof k!="string"){d('Invalid value for property: "postData".');return}}else k=""}if(b.async==null)o=true;else if(b.async!=true&&b.async!=false){d('Invalid value for property: "async".');return}else o=b.async;if(typeof b.url!="string")d('Invalid value for property: "url".');else{if(b.user!=
null)if(typeof b.user!="string"){d('Invalid value for property: "user".');return}if(b.password!=null)if(typeof b.password!="string"){d('Invalid value for property: "password".');return}if(b.onsuccess!=null)if(typeof b.onsuccess!="function"){d('Invalid value for event: "onsuccess".');return}if(b.onfail!=null)if(typeof b.onfail!="function"){d('Invalid value for event: "onfail".');return}if(b.onready!=null)if(typeof b.onready!="function"){d('Invalid value for event: "onready".');return}var l=true;if(k!=
null)for(var m in b.headers)if(m.toUpperCase()=="CONTENT-TYPE")if(b.headers[m].toLowerCase().indexOf("www-form-urlencoded")==-1)l=false;var n=b.url,p=b.params;if(typeof p=="object"){var q="";for(m in p){var r=p[m],s=[];if(typeof r=="object"){if(r.length!=null&&r.length>0)s=r}else s.push(r);for(r=0;r<s.length;r++){if(q!="")q+="&";q+=encodeURIComponent(m)+"="+encodeURIComponent(s[r])}}if(q!="")if(l&&(j=="POST"||j=="PUT")){if(k!=null&&k!="")k+="&";else k="";k+=q}else{l=n.split("?");if(l.length==2&&l[1]!=
"")n=l[0]+"?"+l[1]+"&"+q;else n+="?"+q}}try{typeof b.user=="string"&&typeof b.password=="string"?c.open(j,n,o,b.user,b.password):c.open(j,n,o);n=false;var y=b.headers;for(m in y){c.setRequestHeader(m,y[m]);if(m.toUpperCase()=="CONTENT-TYPE")n=true}if(j=="POST")n||c.setRequestHeader("Content-type","application/x-www-form-urlencoded");typeof b.overrideMimeType=="string"&&typeof c.overrideMimeType=="function"&&c.overrideMimeType(b.overrideMimeType)}catch(H){d(H);return}g=true;if(o==true)c.onreadystatechange=
h;if(b.sendAsBinary==true)try{c.sendAsBinary(k)}catch(I){c.send(k)}else c.send(k);o!=true&&h()}};this.ok=this.f=function(){return e!=null?e:false};this.getResponseText=this.a=function(){return c.responseText};this.getResponseXML=this.b=function(){return c.responseXML};this.getStatus=this.c=function(){return c.status};this.getStatusText=this.e=function(){return c.statusText};this.getStatusMessage=this.d=function(){return a};this.getAllResponseHeaders=this.getAllResponseHeaders=function(){if(e)try{return c.getAllResponseHeaders()}catch(h){d(h)}};
this.getResponseHeader=this.getResponseHeader=function(h){if(e)try{return c.getResponseHeader(h)}catch(j){d(j)}};this.setRequestHeader=this.setRequestHeader=function(h,j){b.headers[h]=j};this.abort=this.abort=function(){if(!(g==null||g==true))try{c.abort()}catch(h){d(h)}}}if(typeof window.pui=="undefined")window.pui={};window.RPGspRequest=G;window.pui.Ajax=G;window.pui.AjaxRequest=G;
window.ajax=function(f,c){var a,e,g,d="";if(arguments.length==1&&typeof arguments[0]=="object"){a=arguments[0];a.async=null;a.onsuccess=null;if(typeof a.handler=="function"){e=true;g=a.handler}else e=false}else if(c!=null&&typeof c=="function"){e=true;g=c}else e=false;a=new G(arguments[0]);a.async=e;a.onsuccess=function(b){d+=b.a();e==true&&g(d)};a.send();if(e==false)return d};
window.ajaxXML=function(f,c){var a,e,g,d=null;if(arguments.length==1&&typeof arguments[0]=="object"){a=arguments[0];a.async=null;a.onsuccess=null;a.onfail=null;a.onsuccess=null;if(typeof a.handler=="function"){e=true;g=a.handler}else e=false}else if(c!=null&&typeof c=="function"){e=true;g=c}else e=false;a=new G(arguments[0]);a.async=e;a.onsuccess=function(b){d=b.b();e==true&&g(d)};a.send();if(e==false)return d};
window.ajaxJSON=function(f,c){var a,e,g,d=null,b=false;if(arguments.length==1&&typeof arguments[0]=="object"){a=arguments[0];a.async=null;a.onsuccess=null;if(a.saveResponse==true)b=true;if(typeof a.handler=="function"){e=true;g=a.handler}else e=false}else if(c!=null&&typeof c=="function"){e=true;g=c}else e=false;a=new G(arguments[0]);a.async=e;a.onsuccess=function(i){i=i.a();if(b&&typeof pui=="object")pui.savedJSON=i;try{d=eval("("+i+")")}catch(h){g(null,h);return}e==true&&g(d)};a.send();if(e==false)return d};
window.ajaxSubmit=function(f,c){var a,e="",g=typeof pui!="undefined"&&typeof pui.alert!="undefined"?pui.alert:alert;if(typeof f=="object")a=f;else{a=document.getElementById(f);if(a==null)a=document.forms[f]}var d;if(a!=null)d=a.tagName;if(a==null||d==null||d.toUpperCase()!="FORM"){g("Ajax request error: Invalid form object.");return""}if(f.action==""){g("Ajax request error: Invalid form action.");return""}for(g=0;g<a.elements.length;g++){d=a.elements[g];if(d.name!=null&&d.name!=""){var b=false;if(d.tagName==
"INPUT"){var i=d.type;i=i.toLowerCase();if(i=="hidden")b=true;if(i=="password")b=true;if(i=="text")b=true;if(pui.g(i))b=true;if(i=="")b=true;if(i=="checkbox"||i=="radio")if(d.checked)b=true}if(d.tagName=="TEXTAREA")b=true;if(d.tagName=="SELECT")b=true;if(b){if(e!="")e+="&";e+=d.name+"="+encodeURIComponent(d.value)}}}a=new G(a.action);a.method="POST";a.postData=e;var h="",j;if(c!=null&&typeof c=="function")j=true;else{j=false;a.async=false}a.onsuccess=function(o){h+=o.a();j==true&&c(h)};a.send();if(j==
false)return h};