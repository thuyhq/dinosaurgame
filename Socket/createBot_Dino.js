// setInterval(function () {
//   var bot = require('./bot');
//     bot.createBot();
// }, 1000);

function getRndInteger(min, max) {
    return Math.floor(Math.random() * (max - min + 1) ) + min;
}

function createBot(duration) {
  setTimeout(function () {
    var bot = require('./bot');
    bot.createBot();
    var timeBetweenBot = getRndInteger(150, 500);
    createBot(timeBetweenBot);
  }, duration);
}

createBot(1000);
