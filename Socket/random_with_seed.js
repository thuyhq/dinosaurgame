var user = [];

function createArray() {
  for (i = 0; i < 5; i++) {
    var obj = new Object();
    obj.name = 'abc' + i;
    obj.age = i;
    user.push(obj);
  }
  for (i = 0; i < user.length; i++) {
    var obj = user[i];
    if (obj.name == 'abc2') {
      user.splice(i, 1);
    }
  }
  console.log(user);
}

createArray();
