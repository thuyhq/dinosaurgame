var express  = require('express');
var app      = express();
var http = require('http').Server(app);

var fs = require('fs');
var https = require('https');
var options = {
  key: fs.readFileSync('./file.pem'),
  cert: fs.readFileSync('./file.crt')
};
var serverPort = 8443;

var server = https.createServer(options, app);

var io = require('socket.io')(server);
// app.use(express.static(__dirname + '/game'));

rooms = [];
users = [];
io.use((socket, next) => {
  let handshake = socket.handshake;
  console.log("Use");
  console.log(handshake);
  next();
  // ...
});
io.on('connection', function(socket) {
	   let handshake = socket.handshake;
     console.log("On");
     console.log(handshake);
});

//http.listen(2087, function() {
//  console.log('listening on localhost:2087');
//});
server.listen(serverPort, function() {
  console.log('server up and running at %s port', serverPort);
});
