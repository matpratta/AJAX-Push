/* Description: AJAX Push API
 * Author: Matheus Pratta
 * Details: http://push.mpratta.com.br/
 *********************************************/

/* Create a new object called Push, connect it to 'server', wich will handle all requests and will raise 'handler'. Interval is optional and defaults to 500ms.
 * Note: this object is only used to retrieve messages.
 * Note 2: this object will return a XML DOM object and expects to have atleast one <time> tag inside a <message> tag. Check documentation for details. */
var Push = function(server, channel, handler, last, interval) {
	
	var last_id = "";
	
	if(last) last_id = last;
	
	if(!interval) interval = 500;
	if(!server) server = "push.php";
	
	var running = true;
	var connect = function() {
		if (window.XMLHttpRequest) xhr = new XMLHttpRequest();
		else xhr = new ActiveXObject("Microsoft.XMLHTTP");
		
		xhr.onreadystatechange = function() {
			if(xhr.readyState == 4 && xhr.status == 200) {
				
				// We got new Push messages, let's read them
				
				var xml;
				
				if (window.DOMParser) {
					var parser = new DOMParser();
					xml=parser.parseFromString(xhr.responseText, "text/xml");
				} else {
					xml=new ActiveXObject("Microsoft.XMLDOM");
					xml.async=false;
					xml.loadXML(xhr.responseText); 
				}
				
				// Find out wich is the most recent message, and set it as first on next fetch
				
				var recent = "";
				var times = xml.getElementsByTagName("time");
				
				for(i = 0; i < times.length; i++)
				{
					var current = times[i].childNodes[0].nodeValue;
					if(current > recent) recent = current;
				}
				
				last_id = recent;
				
				// Set next call timeout
				
				if(running) setTimeout(connect, interval);
				
				// Return processed data
				
				handler(xml);
			}
		};
		xhr.open("GET", server + "?channel=" + channel + "&last=" + last_id + "&rand=" + Math.random(), true);
		xhr.send();
	}
	
	connect();
	
	this.stop = function() {
		running = false;
	};
}