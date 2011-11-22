var doc = document;
var cI = 'lytecss';

var myUrl = doc.getElementById('lytescr').src;
var bU = myUrl.substring(0,myUrl.lastIndexOf('/')+1);

if (!doc.getElementById(cI)) {
    lk = doc.createElement('link');
    lk.id = cI;
    lk.rel = 'stylesheet';
    lk.type = 'text/css';
    lk.href = bU + 'lyte.css';
    doc.getElementsByTagName('head')[0].appendChild(lk);
}

function lyte() {
    lytes = getElementsByClassName("lyte", "div");
    for (var i = 0; i < lytes.length; i++) {
        lyte_id = lytes[i].id;
	vid = lyte_id.substring(4);
        p = doc.getElementById(lyte_id);
        p.className += " lP";
        pW = p.clientWidth;
        pH = p.clientHeight;
        pl = doc.createElement('div');
        p.appendChild(pl);
        p.onclick = plaYT;
        pl.id = "lyte_" + vid;
        pl.className = "pL";

        if (p.className.indexOf('audio') !== -1) {
	    setStyle(pl, 'height:' + pH + 'px;width:' + pW);
            pl.innerHTML = "<img src=\"" + bU + "controls-" + pW + ".png\" width=\"100%\" id=\"ctrl\" alt=\"\" style=\"max-width:" + pW + "px;\"/>";
	} else if (p.className.indexOf('playlist') !== -1){
            setStyle(pl, 'height:' + pH + 'px;width:' + pW + 'px;');
	    pl.innerHTML = "<img src=\"" + bU + "play.png\" alt=\"Click to play this playlist\" style=\"margin-top:" + ((pH / 2) - 30) + "px;opacity:0.7;\" onmouseover=\"this.style.opacity=1;\" onmouseout=\"this.style.opacity=0.8;\"/><img src=\"" + bU + "controls-" + pW + ".png\" width=\"100%\" id=\"ctrl\" alt=\"\" style=\"max-width:" + pW + "px;\"/>";
	    jsonUrl = "http://gdata.youtube.com/feeds/api/playlists/"+ vid +"?v=2&alt=json-in-script&callback=parsePL&fields=id,title,entry"
	    loadScript(jsonUrl)
	} else {
            setStyle(pl, 'height:' + pH + 'px;width:' + pW + 'px;background:url("http://img.youtube.com/vi/' + vid + '/0.jpg") no-repeat scroll center -10px rgb(0, 0, 0);background-size:contain;');
            pl.innerHTML = "<img src=\"" + bU + "play.png\" alt=\"Click to play this video\" style=\"margin-top:" + ((pH / 2) - 30) + "px;opacity:0.7;\" onmouseover=\"this.style.opacity=1;\" onmouseout=\"this.style.opacity=0.8;\"/><img src=\"" + bU + "controls-" + pW + ".png\" width=\"100%\" id=\"ctrl\" alt=\"\" style=\"max-width:" + pW + "px;\"/>";
	    if (p.className.indexOf('widget') === -1) {
	    	jsonUrl = "http://gdata.youtube.com/feeds/api/videos/" + vid + "?fields=id,title&alt=json-in-script&callback=parseV";
		loadScript(jsonUrl)
	    }
	}
    }
}

function plaYT() {
    this.onclick = "";
    vid=this.id.substring(4);

    if (this.className.indexOf("hidef") === -1) {
    	hidef=0;
    } else {
	hidef=1;
    }

    if (this.className.indexOf("playlist") === -1) {
    	eU="http://www.youtube.com/embed/" + vid
    } else {
    	eU="http://www.youtube.com/embed/p/" + vid
    }

    this.innerHTML="<iframe class=\"youtube-player\" type=\"text/html\" width=\"" + this.clientWidth + "\" height=\"" + this.clientHeight + "\" src=\""+eU+"?autoplay=1&amp;rel=0&amp;egm=0&amp;iv_load_policy=3&amp;probably_logged_in=false&amp;hd="+hidef+"\" frameborder=\"0\"></iframe>"
}

function parseV(r) {
    title = r.entry.title.$t;
    idu = r.entry.id.$t;
    id = "lyte_" + idu.substring(idu.length - 11);
    drawTitle(id,title);
}

function parsePL(r) {
   thumb=r.feed.entry[0].media$group.media$thumbnail[1].url
   idu=r.feed.id.$t
   id="lyte_"+idu.substring(idu.length - 16)
   title="Playlist: "+r.feed.title.$t
   pl=doc.getElementById(id)
   pH=pl.style.height;
   pW=pl.style.width;

   setStyle(pl, 'height:' + pH + ';width:' + pW + ';background:url("'+thumb+'") no-repeat scroll center -10px rgb(0, 0, 0); background-size:contain;')
   drawTitle(id,title)
   }

function drawTitle(id,title) {
    p = doc.getElementById(id);
    c = doc.createElement('div');
    c.className = "tC";
    p.appendChild(c);
    setStyle(c, "margin:-" + ((p.clientHeight / 2) + 15) + "px 5px;");
    t = doc.createElement('div');
    t.className = "tT";
    c.appendChild(t);
    t.innerHTML = title;
}

function setStyle(e, s) {
    if (typeof e.setAttribute === "function") e.setAttribute('style', s);
    else if (typeof e.style.setAttribute === "object") e.style.setAttribute('cssText', s)
}

function loadScript(url) {
    scr = doc.createElement('script');
    scr.src = url;
    scr.type = 'text/javascript';
    doc.getElementsByTagName('head')[0].appendChild(scr)
}

var getElementsByClassName = function (className, tag, elm) {
    if (doc.getElementsByClassName) {
        getElementsByClassName = function (className, tag, elm) {
            elm = elm || doc;
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
    } else if (doc.evaluate) {
        getElementsByClassName = function (className, tag, elm) {
            tag = tag || "*";
            elm = elm || doc;
            var classes = className.split(" "),
                classesToCheck = "",
                xhtmlNamespace = "http://www.w3.org/1999/xhtml",
                namespaceResolver = (doc.documentElement.namespaceURI === xhtmlNamespace) ? xhtmlNamespace : null,
                returnElements = [],
                elements, node;
            for (var j = 0, jl = classes.length; j < jl; j += 1) {
                classesToCheck += "[contains(concat(' ', @class, ' '), ' " + classes[j] + " ')]"
            }
            try {
                elements = doc.evaluate(".//" + tag + classesToCheck, elm, namespaceResolver, 0, null)
            } catch (e) {
                elements = doc.evaluate(".//" + tag + classesToCheck, elm, null, 0, null)
            }
            while ((node = elements.iterateNext())) {
                returnElements.push(node)
            }
            return returnElements
        }
    } else {
        getElementsByClassName = function (className, tag, elm) {
            tag = tag || "*";
            elm = elm || doc;
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
