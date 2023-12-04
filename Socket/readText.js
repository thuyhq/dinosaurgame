fs = require('fs')

function getRndInteger(min, max) {
    return Math.floor(Math.random() * (max - min + 1) ) + min;
}


function createEnemy() {

        var enemies = '';
         for(i = 0; i < 20; i++) {
           index = getRndInteger(1,5);
           timeout = getRndInteger(600, 950);
           enemies += index + ',' + timeout + ';';
         }
         for(i = 0; i < 880; i++) {
           index = getRndInteger(1,10) - 1;
           timeout = getRndInteger(550, 900);
           if (i < 879) {
             enemies += index + ',' + timeout + ';';
           } else {
             enemies += index + ',' + timeout + '';
           }
         }
         fs.writeFile('enemy.txt', enemies, function(err) {
                if(err) {
                    return console.log(err);
                }
                console.log('Saved');
            });
}
function readEnemy() {
  fs.readFile('enemy.txt', 'utf8', function (err,enemyData) {
    if (err) {
            return console.log(err);
          }
        console.log(enemyData);
        var enemyArray = enemyData.split(',');
        for (i =0; i < enemyArray.length; i++) {
          console.log(enemyArray[i]);
        }
        // console.log(enemyArray.length);
      });
}
createEnemy();
// readEnemy();
