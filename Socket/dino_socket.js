var express  = require('express');
var app      = express();
var http = require('http').Server(app);

var fs = require('fs');
var https = require('https');
var options = {
  key: fs.readFileSync('./file.pem'),
  cert: fs.readFileSync('./file.crt')
};
var serverPort = 2053;

var server = https.createServer(options, app);

var io = require('socket.io')(server);
// app.use(express.static(__dirname + '/game'));

rooms = [];
users = [];
io.on('connection', function(socket) {
	   socket.on('register', function(data) {
		  var obj = JSON.parse(data);
		  socket.name = obj.name;
		  socket.room = obj.room;
		  socket.type = obj.type;
		  socket.isDead = false;
		  socket.join(obj.room);
		  // console.log('A user register with id: ' + socket.id + ', room: ' + socket.room + ', name: ' + socket.name + ', type: ' + socket.type);
		  // var newPlayer = new Object();
		  // newPlayer.id = socket.id;
		  // newPlayer.name = obj.name;
		  // var result = JSON.stringify(newPlayer);
		  var isNewRoom = true;
		  for (i = 0; i < rooms.length; i++) {
			if (rooms[i].roomName == obj.room) {
			  isNewRoom = false;
			  socket.emit('playerOnline', rooms[i].players.length + 2)
			  break;
			}
		  }
		  if (isNewRoom) {
			var room = new Object();
			room.roomName = obj.room;
			room.players = [];
			rooms.push(room);
			socket.emit('playerOnline', '1')
		  }
		  var enemy = '';
		  fs.readFile('enemy.txt', 'utf8', function (err,enemyData) {
			if (err) {
					return console.log(err);
				  }
				// console.log(enemyData);
				// var enemyArray = enemyData.split(',');
				// for (i =0; i < enemyArray.length; i++) {
				//   console.log(enemyArray[i]);
				// }
				// enemy = enemyData;
				socket.emit("registerComplete", enemyData);

				// console.log(enemyArray.length);
			  });
	   });
	   socket.on('disconnect', (reason) => {
		 if (!socket.isDead) {
		   var room = [];
		   room.players = [];
		   var isRoom = false;
		   for (i = 0; i < rooms.length; i++) {
			 if (rooms[i].roomName == socket.room) {
			   room = rooms[i];
			   isRoom = true;
			   break;
			 }
		   }
		   if (!isRoom) {
			   console.log("Room cannot found: " + socket.room);
		   }
		   for (i = 0; i < room.players.length; i++) {
			 var player = room.players[i];
			 if (player.id == socket.id) {
			   room.players.splice(i, 1);
			 }
		   }

		   var leaderBoard = [];
		   for (i =0; i < room.players.length; i++) {
			   var obj = room.players[i];
			   var now = new Date().getTime();
			   var score = Math.round((now - obj.time)/100, 0);
			   obj.score = score;
			   leaderBoard.push(obj);
		   }
		   var result = JSON.stringify(leaderBoard);
		   socket.broadcast.to(socket.room).emit("getLeaderBoard", result);
		   var player = new Object();
		   player.id = socket.id;
		   socket.broadcast.to(socket.room).emit("dead", JSON.stringify(player));
		   socket.broadcast.to(socket.room).emit("playerQuit",'');
		 }
		});
		socket.on('dead', function(data){
		  var room = [];
		  room.players = [];
		  for (i = 0; i < rooms.length; i++) {
			if (rooms[i].roomName == socket.room) {
			  room = rooms[i];
			  break;
			}
		  }
		  for (i = 0; i < room.players.length; i++) {
			var player = room.players[i];
			if (player.id == socket.id) {
			  room.players.splice(i, 1);
			}
		  }
		  var leaderBoard = [];
		  for (i =0; i < room.players.length; i++) {
			  var obj = room.players[i];
			  var now = new Date().getTime();
			  var score = Math.round((now - obj.time)/100, 0);
			  obj.score = score;
			  leaderBoard.push(obj);
		  }
		  var result = JSON.stringify(leaderBoard);
		  socket.broadcast.to(socket.room).emit("getLeaderBoard", result);
		  socket.emit("getLeaderBoard", result);
		  var player = new Object();
		  player.id = socket.id;
		  socket.broadcast.to(socket.room).emit("dead", JSON.stringify(player));
		  socket.isDead = true;
		});
	   socket.on('startMove', function(data){
		  var player = new Object();
		  player.id = socket.id;
		  player.type = socket.type;
		  player.data = data;
		  player.time = new Date().getTime();
		  player.name = socket.name;
		  var room = [];
		  room.players = [];
		  for (i = 0; i < rooms.length; i++) {
			if (rooms[i].roomName == socket.room) {
			  rooms[i].players.push(player);
			  room = rooms[i];
			  break;
			}
		  }
		  // users.push(player);
		  var result = JSON.stringify(player);
		  socket.broadcast.to(socket.room).emit("startMove", result);
		  // if (users.length < 5) {
		  if (room.players.length < 5) {
			var leaderBoard = [];
			for (i =0; i < room.players.length; i++) {
			  // if (i < users.length) {
				// var obj = users[i];
				var obj = room.players[i];
				var now = new Date().getTime();
				var score = Math.round((now - obj.time)/100, 0);
				obj.score = score;
				leaderBoard.push(obj);
			}
			var result = JSON.stringify(leaderBoard);
			socket.broadcast.to(socket.room).emit("getLeaderBoard", result);
		  }
	   });
	   socket.on('jump', function(data){
		 socket.broadcast.to(socket.room).emit("jump", socket.id);
	   });
	   socket.on('cancleJump', function(data){
		 socket.broadcast.to(socket.room).emit("cancleJump", socket.id);
	   });
	   socket.on('duck', function(data) {
		 socket.broadcast.to(socket.room).emit("duck", socket.id);
	   });
	   socket.on('cancleDuck', function(data){
		 socket.broadcast.to(socket.room).emit("cancleDuck", socket.id);
	   });
	   socket.on('getLeaderBoard', function(data){
		 var room = [];
		  room.players = [];
		 for (i = 0; i < rooms.length; i++) {
		   if (rooms[i].roomName == socket.room) {
			 room = rooms[i];
			 break;
		   }
		 }
		 var leaderBoard = [];
		 for (i =0; i < room.players.length; i++) {
			 var obj = room.players[i];
			 var now = new Date().getTime();
			 var score = Math.round((now - obj.time)/100);
			 obj.score = score;
			 leaderBoard.push(obj);
		 }
		 var result = JSON.stringify(leaderBoard);
		 socket.emit("getLeaderBoard", result);
	   });
});

//http.listen(2087, function() {
//  console.log('listening on localhost:2087');
//});
server.listen(serverPort, function() {
  console.log('server up and running at %s port', serverPort);
});
