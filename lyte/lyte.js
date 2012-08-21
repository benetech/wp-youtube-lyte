(function( ly ) {
d = document;

var sch="http";
if (bU.indexOf('https')!=-1) {sch+="s"}

ly.te = function() {
    if (!rn) {
	var rn=1;
	var iOs=navigator.userAgent.match(/(iphone|ipad|ipod)/i);
	lts = getElementsByClassName("lyMe", "div");
	for (var i = 0, lln = lts.length; i < lln; i += 1) {
	    p = lts[i];
	    vid = p.id.substring(4);
	    cN = p.className.replace(/lyMe/, "lyte")+ " lP";
	    p.className = cN;
	    pl = d.createElement('div');
	    pl.id = "lyte_" + vid;
	    pl.className = "pL";

            if (iOs === null) {
                p.onclick = ly.play;
                pW = p.style.width.match(/\d+/g)[0];
                pH = p.style.height.match(/\d+/g)[0];

		bgA="-60px";
		if ((cN.indexOf('widget') !== -1)||(pW/pH<1.7)) bgA="-10px";

		qsa=getQ(p);

		cImg="<img src=\"" + bU + "controls-" + pW + ".png\" height=\"40px\" width=\""+pW+"px\" class=\"ctrl\" alt=\"\"/>";
		pImg="<img src=\"" + bU + "play.png\" width=\"83px\" height=\"55px\" alt=\"Click to play\" style=\"margin-top:" + ((pH / 2) - 30) + "px;opacity:0.7;\" onmouseover=\"this.style.opacity=1;\" onmouseout=\"this.style.opacity=0.8;\"/>";
		ytA="://gdata.youtube.com/feeds/api/";

        	if (cN.indexOf('audio') !== -1) {
	        	setST(pl, 'height:' + pH + 'px;width:' + pW + 'px;');
			pl.innerHTML = cImg;
	        } else if (cN.indexOf('playlist') !== -1) {
			setST(pl, 'height:' + pH + 'px;width:' + pW + 'px;');
			pl.innerHTML = pImg+cImg;
			joU = sch+ytA+"playlists/"+ vid +"?v=2&alt=json-in-script&callback=ly.prsPL&fields=id,title,entry";
			loadSC(joU);
	        } else {
			setST(pl, "height:" + pH + "px;width:" + pW + "px;background:url('" + sch + "://img.youtube.com/vi/" + vid + "/0.jpg') no-repeat scroll center " + bgA + " rgb(0, 0, 0);background-size:cover;");
                	pl.innerHTML = pImg+cImg;
	            	if ((cN.indexOf('widget') === -1) && (qsa.indexOf('showinfo=0') === -1)) {
	                	joU = sch+ytA+"videos/" + vid + "?fields=id,title&alt=json-in-script&callback=ly.prsV";
		        	loadSC(joU)
	            	}
	        }
		p.appendChild(pl);
	    } else {
	    	ly.play(p.id);
	    }
        }
    }
    var rn="";
}

function getQ(nD) {
	qsa="";
	if (rqs=nD.className.match(/qsa_(.*)\s/,"$1")) qsa=rqs[1].replace(/\\([\&\=\?])/g, "$1");
	return qsa;
}

ly.play = function(id) {
    if (typeof id === 'string') {
    	tH=d.getElementById(id);
	aP=0;
    } else {
    	tH=this;
	tH.onclick="";
	aP=1;
    }
    vid=tH.id.substring(4);

    hidef=0;
    if (tH.className.indexOf("hidef") !== -1) { hidef=1; }

    if (tH.className.indexOf("playlist") === -1) {
    	eU=sch+"://www.youtube.com/embed/" + vid + "?"
    } else {
    	eU=sch+"://www.youtube.com/embed/videoseries?list=PL" + vid + "&"
    }

    qsa=getQ(tH);

    if (tH.className.indexOf("audio") !== -1) { qsa+="&autohide=0";aHgh="438";aSt="position:relative;top:-400px;" } else { aHgh=tH.clientHeight;aSt=""; }

    tH.innerHTML="<iframe id=\"iF_" + vid + "\" width=\"" + tH.clientWidth + "px\" height=\"" + aHgh + "px\" src=\""+eU+"autoplay="+aP+"&amp;wmode=opaque&amp;rel=0&amp;egm=0&amp;iv_load_policy=3&amp;hd="+hidef+qsa+"\" frameborder=\"0\" style=\"" + aSt + "\"></iframe>"

    if(typeof tH.firstChild.getAttribute('kabl')=="string") tH.innerHTML="Please check Karma Blocker's config.";
}

ly.prsV = function(r) {
    tI = r.entry.title.$t;
    idu = r.entry.id.$t;
    id = "lyte_" + idu.substring(idu.length - 11);
    drawT(id,tI);
}

ly.prsPL = function(r) {
   thumb=r.feed.entry[0].media$group.media$thumbnail[1].url
   idu=r.feed.id.$t
   id="lyte_"+idu.substring(idu.length - 16)
   title="Playlist: "+r.feed.title.$t
   pl=d.getElementById(id)
   pH=pl.style.height;
   pW=pl.style.width;

   if ((sch=="https")&&(thumb.indexOf('https'==-1))) {thumb=thumb.replace("http://","https://");}

   setST(pl, "height:" + pH + ";width:" + pW + ";background:url('" + thumb + "') no-repeat scroll center -50px rgb(0, 0, 0); background-size:cover;")
   drawT(id,title)
   }

function drawT(id,tI) {
    p = d.getElementById(id);
    c = d.createElement('div');
    c.className = "tC";
    t = d.createElement('div');
    t.className = "tT";
    c.appendChild(t);
    t.innerHTML = tI;
    p.appendChild(c);
}

function setST(e, s) {
    if (typeof e.setAttribute === "function") e.setAttribute('style', s);
    else if (typeof e.style.setAttribute === "object") e.style.setAttribute('cssText', s)
}

function loadSC(url) {
    scr = d.createElement('script');
    scr.src = url;
    scr.type = 'text/javascript';
    d.getElementsByTagName('head')[0].appendChild(scr)
}

function getElementsByClassName (className, tag, elm) {
    if (d.getElementsByClassName) {
        getElementsByClassName = function (className, tag, elm) {
            elm = elm || d;
            var elements = elm.getElementsByClassName(className),
                nodeName = (tag) ? new RegExp("\\b" + tag + "\\b", "i") : null,
                returnElements = [],
                current;
            for (var i = 0, il = elements.length; i < il; i += 1) {
                current = elements[i];
                if (!nodeName || nodeName.test(current.nodeName)) {
                    returnElements.push(current)
                }
            }
            return returnElements
        }
    } else if (d.evaluate) {
        getElementsByClassName = function (className, tag, elm) {
            tag = tag || "*";
            elm = elm || d;
            var classes = className.split(" "),
                classesToCheck = "",
                xhtmlNamespace = "http://www.w3.org/1999/xhtml",
                namespaceResolver = (d.documentElement.namespaceURI === xhtmlNamespace) ? xhtmlNamespace : null,
                returnElements = [],
                elements, node;
            for (var j = 0, jl = classes.length; j < jl; j += 1) {
                classesToCheck += "[contains(concat(' ', @class, ' '), ' " + classes[j] + " ')]"
            }
            try {
                elements = d.evaluate(".//" + tag + classesToCheck, elm, namespaceResolver, 0, null)
            } catch (e) {
                elements = d.evaluate(".//" + tag + classesToCheck, elm, null, 0, null)
            }
            while ((node = elements.iterateNext())) {
                returnElements.push(node)
            }
            return returnElements
        }
    } else {
        getElementsByClassName = function (className, tag, elm) {
            tag = tag || "*";
            elm = elm || d;
            var classes = className.split(" "),
                classesToCheck = [],
                elements = (tag === "*" && elm.all) ? elm.all : elm.getElementsByTagName(tag),
                current, returnElements = [],
                match;
            for (var k = 0, kl = classes.length; k < kl; k += 1) {
                classesToCheck.push(new RegExp("(^|\\s)" + classes[k] + "(\\s|$)"))
            }
            for (var l = 0, ll = elements.length; l < ll; l += 1) {
                current = elements[l];
                match = false;
                for (var m = 0, ml = classesToCheck.length; m < ml; m += 1) {
                    match = classesToCheck[m].test(current.className);
                    if (!match) {
                        break
                    }
                }
                if (match) {
                    returnElements.push(current)
                }
            }
            return returnElements
        }
    }
    return getElementsByClassName(className, tag, elm)
};

}( window.ly = window.ly || {} ));

(function(){
var w = window;
var d = document;

if(w.addEventListener) {
	w.addEventListener('load', ly.te, false);
	d.addEventListener('DomContentLoaded', function(){setTimeout("ly.te()",750)}, false);
} else {
	w.onload=ly.te;
	setTimeout("ly.te()",1000);
}}())
