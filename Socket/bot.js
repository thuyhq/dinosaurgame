var random_name = require('node-random-name');

module.exports = {
  createBot: function () {
    // whatever
    const socket = require('socket.io-client')('https://dinosaurgame.io:2053', { transports: ['websocket'], rejectUnauthorized: false });
    var obj = new Object();
    var botName = random_name();
    if (botName.length > 15) {
      botName = botName.substring(0, 15);
    }
    obj.name = botName;
    obj.room = "global";
    obj.type = "bot";
    var result = JSON.stringify(obj);
    socket.emit("register", result);
    socket.on("registerComplete", function(data){
      socket.emit('startMove', '');
    });
    var duration = getRndInteger(5000, 60000);
    // console.log(duration);
    setTimeout(function () {
      socket.disconnect();
    }, duration);
  }
};

function getRndInteger(min, max) {
    return Math.floor(Math.random() * (max - min + 1) ) + min;
}
var fs = require('fs');
var options = {
  key: fs.readFileSync('./file.pem'),
  cert: fs.readFileSync('./file.crt')
};
function createBot() {
	console.log("Create Bot 8443");
	// var io = require('socket.io-client');
  //   var socket = io('https://127.0.0.1:8443');
  //   console.log(io);
  const socket = require('socket.io-client')('https://dinosaurgame.io:8443', { transports: ['websocket'], rejectUnauthorized: false });
	socket.on('connect', function() {
		console.log('check 2', socket.connected);
	});
    var obj = new Object();
    var botName = random_name();
    if (botName.length > 15) {
      botName = botName.substring(0, 15);
    }
    obj.name = botName;
    obj.room = "global";
    obj.type = "bot";
    var result = JSON.stringify(obj);
    socket.emit("register", result);
    socket.on("registerComplete", function(data){
      socket.emit('startMove', '');
    });
    var duration = getRndInteger(5000, 60000);
    // console.log(duration);
    setTimeout(function () {
      socket.disconnect();
    }, duration);
}
createBot();
