var d = document;
var cI = 'lcss';
var w = window;

var myUrl = d.getElementById('lytescr').src;

var bU = myUrl.substring(0,myUrl.lastIndexOf('/')+1);

scheme="http";
if (myUrl.indexOf('https')!=-1) {scheme+="s";}

if (!d.getElementById(cI)) {
    lk = d.createElement('link');
    lk.id = cI;
    lk.rel = 'stylesheet';
    lk.type = 'text/css';
    lk.href = bU + 'lyte.css';
    d.getElementsByTagName('head')[0].appendChild(lk);
}

function lyte() {
    lytes = getElementsByClassName("lyte", "div");
    for (var i = 0; i < lytes.length; i++) {
        lyte_id = lytes[i].id;
	vid = lyte_id.substring(4);
        p = d.getElementById(lyte_id);
        p.className += " lP";
        pW = p.clientWidth;
        pH = p.clientHeight;
        pl = d.createElement('div');
        p.appendChild(pl);
        p.onclick = plaYT;
        pl.id = "lyte_" + vid;
        pl.className = "pL";

	qsa=getQ(vid);

        if (p.className.indexOf('audio') !== -1) {
	    setST(pl, 'height:' + pH + 'px;width:' + pW);
            pl.innerHTML = "<img src=\"" + bU + "controls-" + pW + ".png\" width=\"100%\" id=\"ctrl\" alt=\"\" style=\"max-width:" + pW + "px;\"/>";
	} else if (p.className.indexOf('playlist') !== -1){
            setST(pl, 'height:' + pH + 'px;width:' + pW + 'px;');
	    pl.innerHTML = "<img src=\"" + bU + "play.png\" alt=\"Click to play this playlist\" style=\"margin-top:" + ((pH / 2) - 30) + "px;opacity:0.7;\" onmouseover=\"this.style.opacity=1;\" onmouseout=\"this.style.opacity=0.8;\"/><img src=\"" + bU + "controls-" + pW + ".png\" width=\"100%\" id=\"ctrl\" alt=\"\" style=\"max-width:" + pW + "px;\"/>";
	    jsonUrl = scheme+"://gdata.youtube.com/feeds/api/playlists/"+ vid +"?v=2&alt=json-in-script&callback=parsePL&fields=id,title,entry"
	    loadSC(jsonUrl)
	} else {
            setST(pl, "height:" + pH + "px;width:" + pW + "px;background:url('" + scheme + "://img.youtube.com/vi/" + vid + "/0.jpg') no-repeat scroll center -10px rgb(0, 0, 0);background-size:contain;");
            pl.innerHTML = "<img src=\"" + bU + "play.png\" alt=\"Click to play this video\" style=\"margin-top:" + ((pH / 2) - 30) + "px;opacity:0.7;\" onmouseover=\"this.style.opacity=1;\" onmouseout=\"this.style.opacity=0.8;\"/><img src=\"" + bU + "controls-" + pW + ".png\" width=\"100%\" id=\"ctrl\" alt=\"\" style=\"max-width:" + pW + "px;\"/>";
	    if ((p.className.indexOf('widget') === -1) && (qsa.indexOf('showinfo=0') === -1)) {
	    	jsonUrl = scheme+"://gdata.youtube.com/feeds/api/videos/" + vid + "?fields=id,title&alt=json-in-script&callback=parseV";
		loadSC(jsonUrl)
	    }
	}
    }
}

function getQ(v) {
	qsa="";
	if ((typeof w.lst !== 'undefined')&&(typeof w.lst[v] !== 'undefined')) qsa=w.lst[v];
	return qsa;
}

function plaYT() {
    tH=this;
    tH.onclick = "";
    vid=tH.id.substring(4);

    if (tH.className.indexOf("hidef") === -1) {
    	hidef=0;
    } else {
	hidef=1;
    }

    if (tH.className.indexOf("playlist") === -1) {
    	eU=scheme+"://www.youtube.com/embed/" + vid
    } else {
    	eU=scheme+"://www.youtube.com/embed/p/" + vid
    }

    qsa=getQ(vid);

    tH.innerHTML="<iframe class=\"youtube-player\" type=\"text/html\" width=\"" + tH.clientWidth + "\" height=\"" + tH.clientHeight + "\" src=\""+eU+"?autoplay=1&amp;wmode=opaque&amp;rel=0&amp;egm=0&amp;iv_load_policy=3&amp;probably_logged_in=false&amp;hd="+hidef+qsa+"\" frameborder=\"0\"></iframe>"
}

function parseV(r) {
    tI = r.entry.title.$t;
    idu = r.entry.id.$t;
    id = "lyte_" + idu.substring(idu.length - 11);
    drawT(id,tI);
}

function parsePL(r) {
   thumb=r.feed.entry[0].media$group.media$thumbnail[1].url
   idu=r.feed.id.$t
   id="lyte_"+idu.substring(idu.length - 16)
   title="Playlist: "+r.feed.title.$t
   pl=d.getElementById(id)
   pH=pl.style.height;
   pW=pl.style.width;

   if ((scheme=="https")&&(thumb.indexOf('https'==-1))) {thumb=thumb.replace("http://","https://");}

   setST(pl, "height:" + pH + ";width:" + pW + ";background:url('" + thumb + "') no-repeat scroll center -10px rgb(0, 0, 0); background-size:contain;")
   drawT(id,title)
   }

function drawT(id,tI) {
    p = d.getElementById(id);
    c = d.createElement('div');
    c.className = "tC";
    p.appendChild(c);
    setST(c, "margin:-" + ((p.clientHeight / 2) + 15) + "px 5px;");
    t = d.createElement('div');
    t.className = "tT";
    c.appendChild(t);
    t.innerHTML = tI;
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

var getElementsByClassName = function (className, tag, elm) {
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
lyte();
