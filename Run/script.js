var playerName;
  function setFocus(){
  console.log('Focus');
    document.getElementById("GameCanvas").focus();
  }
  function loadIframeAds(score) {
    $("#if-ads").show();
    getUserScore(score);
  }
  function getUserScore(score) {
      setTimeout(function(){ getPrintScore(score); }, 300);
  }
  function getPrintScore(score){
    var highScore = "";
    var skinId = "1";
    //save skinn ID
    var params = document.cookie.split(";");	
    for (var i=0; i<params.length; i++) {
        var param = params[i].split("=");
        // if(param[0].trim() == "highScore")
        // { 
        //     highScore = param[1]; 
        // }
        if(param[0].trim() == "skinID")
        { 
            skinId = param[1]; 
        }
    }
  
    var iframeAds = document.getElementById("sco-game-ads").contentWindow.document;
    iframeAds.getElementById("highScore").textContent = score;
    iframeAds.getElementById("skinAvatar").src ="http://dinosaurgame.io/img/dino.png";
    //reload ads
    document.getElementById("sco-game-ads").contentWindow.reloadAds();
  }
  
  function continueGame(){
	again(playerName);
	setFocus();
  }
  
  function reloadDie5times(){
      //reload ads
      document.getElementById("sco-game-ads").contentWindow.loadadsvideo();
  }
  function goToMenu(){
	  backToMenu();
	}
	function abc1234() {
		console.log(abc1234);
	}
  

