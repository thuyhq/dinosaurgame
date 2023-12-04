var WebSocketServer = require('websocket').server;
var http = require('http');

var fs = require('fs');
var https = require('https');
var express  = require('express');
var app      = express();
var options = {
  key: fs.readFileSync('./file.pem'),
  cert: fs.readFileSync('./file.crt')
};
var serverPort = 8443;

var server = https.createServer(options, app);
server.listen(serverPort, function() {
    console.log((new Date()) + ' Server is listening on port 8443');
});

wsServer = new WebSocketServer({
    httpServer: server,
    // You should not use autoAcceptConnections for production
    // applications, as it defeats all standard cross-origin protection
    // facilities built into the protocol and the browser.  You should
    // *always* verify the connection's origin and decide whether or not
    // to accept it.
    autoAcceptConnections: false
});

function originIsAllowed(origin) {
  // put logic here to detect whether the specified origin is allowed.
  return true;
}

var rooms = [];

try {
	wsServer.on('request', function(request) {
		if (request.requestedProtocols.indexOf('echo-protocol') === -1) {
			request.reject();
			console.log("Reject connection");
		}
		else {
			var connection = request.accept('echo-protocol', request.origin);
			console.log((new Date()) + ' Connection accepted.');
			const uuidv4 = require('uuid/v4');
			request.id = uuidv4();
			console.log("Id: " + request.id);
			connection.on('message', function(message) {
				if (message.type === 'utf8') {
					console.log('Received Message: ' + message.utf8Data);
					connection.sendUTF(message.utf8Data);
					var received = message.utf8Data;
					var obj = JSON.parse(received);
          if (obj.action == 'register') {
            
          }
				}
				//else if (message.type === 'binary') {
				//	console.log('Received Binary Message of ' + message.binaryData.length + ' bytes');
				//	connection.sendBytes(message.binaryData);
				//}
			});
			connection.on('close', function(reasonCode, description) {
				console.log((new Date()) + ' Peer ' + connection.remoteAddress + ' disconnected.');
			});
		}


	});
} catch(e) {
	console.log("Catch: " + e.message);
}
