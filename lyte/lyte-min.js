var doc = document;
var cI = 'lytecss';

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
        p = doc.getElementById(lyte_id);
        p.className += " lP";
        pW = p.clientWidth;
        pH = p.clientHeight;
        if ((p.className.indexOf('widget') === -1) && (p.className.indexOf('audio') === -1)){
            jsonUrl = "http://gdata.youtube.com/feeds/api/videos/" + lyte_id + "?fields=id,title&alt=json-in-script&callback=parseMe";
            loadScript(jsonUrl)
        }
        pl = doc.createElement('div');
        p.appendChild(pl);
        p.onclick = plaYT;
        pl.id = "lyte_" + lyte_id;
        pl.className = "pL";

        if (p.className.indexOf('audio') === -1){
            setStyle(pl, 'height:' + pH + 'px;width:' + pW + 'px;background:url("http://img.youtube.com/vi/' + lyte_id + '/0.jpg") no-repeat scroll center -10px rgb(0, 0, 0);background-size:contain;');
            pl.innerHTML = "<img src=\"" + bU + "play.png\" alt=\"Click to play this video\" style=\"margin-top:" + ((pH / 2) - 30) + "px;opacity:0.7;\" onmouseover=\"this.style.opacity=1;\" onmouseout=\"this.style.opacity=0.8;\"/><img src=\"" + bU + "controls-" + nT + pW + ".png\" width=\"100%\" id=\"ctrl\" alt=\"\" style=\"max-width:" + pW + "px;\"/>";
	} else {
	    setStyle(pl, 'height:' + pH + 'px;width:' + pW);
	    pl.innerHTML = "<img src=\"" + bU + "controls-" + nT + pW + ".png\" width=\"100%\" id=\"ctrl\" alt=\"\" style=\"max-width:" + pW + "px;\"/>";
	}
    }
}

function plaYT() {
    this.onclick = "";
    if (nT=="newtube-") {
    	this.innerHTML = "<iframe class=\"youtube-player\" type=\"text/html\" width=\"" + this.clientWidth + "\" height=\"" + this.clientHeight + "\" src=\"http://www.youtube.com/embed/" + this.id + "?autoplay=1&amp;rel=0&amp;egm=0&amp;iv_load_policy=3&amp;probably_logged_in=false\" frameborder=\"0\"></iframe>"
    } else {
    	this.innerHTML = "<embed src=\"http://www.youtube-nocookie.com/v/" + this.id + "&amp;autoplay=1&amp;rel=0&amp;egm=0&amp;fs=1&amp;iv_load_policy=3&amp;probably_logged_in=false\" type=\"application/x-shockwave-flash\" allowfullscreen=\"true\" id=\"lyte_" + this.id + "\" wmode=\"transparent\" width=\"" + this.clientWidth + "\" height=\"" + this.clientHeight + "\" allowscriptaccess=\"always\"></embed>"
    }
}

function parseMe(r) {
    title = r.entry.title.$t;
    idu = r.entry.id.$t;
    p = doc.getElementById("lyte_" + idu.substring((idu.length - 11)));
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
