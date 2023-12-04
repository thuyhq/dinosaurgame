
	function clickChangeHash(hashTag){
		location.hash = hashTag;
		return false;
	}
	function getByID(id) {
			return e = document.getElementById(id);
		}
	function autoScale() {
		var windowWidth = window.innerWidth;
		var windowHeight = window.innerHeight;
		var e = getByID("M1");
		var s1 = windowHeight / 566;
		var s2 = windowWidth / 1150;
		if (windowWidth >= 1150 && windowHeight >= 566) {
			e.style.transform = "translate(-50%, -50%) scale(1)";
		} else {
			if (s1 < s2) {
				e.style.transform = "translate(-50%, -50%) scale(" + s1 + ")";
			} else {
				e.style.transform = "translate(-50%, -50%) scale(" + s2 + ")";
			}
		}
	}

	autoScale();
	window.addEventListener("resize", function () {
		autoScale();
	});

	// $('#sharetounlock').on('click', '._share', function () {
	//     $('#sharetounlock').hide();
	// });

	$('._share').bind('click',function(){
		$('#sharetounlock').hide();
	});
	$(function()
			{
				if (location.hash.length == 0) location.hash = '#index';

				// Making the tabPage ID different to the hash to prevent jumping to those elements
				// Name your tabPage's xxxxTab

				var tabPostFix = 'Tab';

				$(window).bind('hashchange', function(){
                    var hash = location.hash;
                    hash = hash.split('?')[0]+tabPostFix;
					if (hash.length > 0)
					{
                        //hide all
						$('.tabPage').hide();
                        //show which tab click
                        $(hash).show();
                        //check if edit infor
                        if(hash == "#editProfileTab"){
                            loadUserInfo();
                        }
					}
				});

				// Load Tabbed Navigation

				$('#secondTab').load('second.htm',function()
				{
					$('#thirdTab').load('third.htm',function()
					{
					// trigger pagesloaded event
					$('body').trigger('pagesloaded');
					});
				});


				$(window).trigger('hashchange');

				// $('.tab').bind('click',function(){
				// 	var idx = $('.tab').index(this);
				// 	location.hash = $('.tabPage:eq('+idx+')').attr('id').replace(tabPostFix,'');
				// 	return false;
				// });
            });
            function clickChangeHash(hashTag){
                location.hash = hashTag;
                return false;
            }
	var isPlayed = false;
	var Func = new Funcs();
	//main screen
	// loading game first
	$(".main-container, .social-group").hide();
	$("#iframe-game-on-page").show();

	function loadIframe() {

		var url = '45.33.124.160/Dino2/Run/';
	   // var url = '//localhost/Dino/Run/';
	  //  $("#sco-game").attr('src', url);
		$(".main-container, .social-group").hide();
	}

	// function loadIframeAds() {
	//     $("#sco-game-ads").show();
	//     $("#sco-game").hide();
	//     $(".main-container, .social-group").hide();
	// }

	//unity call functions show menu
	function loadGamePopup(data){
		$("#iframe-game-on-page").hide();
		$(".main-container, .social-group").show();
	}

	function play() {


	   // loadIframe();
		var name = "";
		var nickname = $('#nickname').val();
		var data;
		if(nickname == ""){
			//random 30-50
			nickname = "Player#" + (Math.floor(Math.random() * 50) + 30);
		}
		//save skinn ID
		var params = document.cookie.split(";");
		for (var i=0; i<params.length; i++) {
			var param = params[i].split("=");
			if(param[0].trim() == "userInfo_name"){ name = param[1]; }
		}
		var userName =  decodeURIComponent(name).replace(/\+/g, ' ');
		if(userName != ""){
			data = {
					'userName': userName,
					'skinID':$('#skin-item').attr('data-skinID')
				};
			playFunction(userName);
			document.getElementById("sco-game").contentWindow.playerName = userName;
		}else{
			data = {
					'userName': nickname,
					'skinID':$('#skin-item').attr('data-skinID')
				};
			playFunction(nickname);
			document.getElementById("sco-game").contentWindow.playerName = nickname;
		}

		// check thông tin người dùng trong db.
		$.ajax({
			url: configLink.url_server+"funcUser/getUserInfoByUserName.php",

			//url: "http://localhost:1234/sco/funcUser/getUserInfoByUserName.php",
			method: "POST",
			data: data,
			dataType: "json",
			success: function (data) {
				//console.log(data);
				$("#iframe-game-on-page").show();
				//hide main container
				$(".main-container, .social-group").hide();
				// //set forcus iframe
				document.getElementById("sco-game").contentWindow.focus();
				// //play game unity without
				 document.getElementById("sco-game").contentWindow.setFocus();
			},
			complete: function () {
			},
			error: function (msg) {
				console.log(msg);
			},
		});


		//loadIframe();
		//loadIframeAds();
	}

	// function chooseSkin(act) {
	//     var name = "";
	//     var isLogin = 1;
	//     var currentSkinID = parseInt($('#skin-item').attr('data-skinID'));
	//     //default 3 skin with share
	//     var totalSkinId = 3;
	//     var params = document.cookie.split(";");
	//     for (var i=0; i<params.length; i++) {
	//         var param = params[i].split("=");
	//         if(param[0].trim() == "userInfo_name"){ name = param[1]; }
	//     }
	//     var userName =  decodeURIComponent(name).replace(/\+/g, ' ');
	//     //default all skin with login
	//     if(userName != ""){
	//         totalSkinId = 7;
	//     }

	//     switch (act) {
	//         case 'prev':
	//             if (currentSkinID <= 1) {
	//                 var skinID = 1;
	//             } else {
	//                 var skinID = currentSkinID - 1;
	//             }
	//             break;
	//         case 'next':
	//             if (currentSkinID >= totalSkinId) {
	//                 var skinID = totalSkinId;
	//             } else {
	//                 var skinID = currentSkinID + 1;
	//             }
	//             break;
	//         default :
	//             return;
	//             break;
	//     }
	//     var data = {'skinID': skinID};
	//     $.ajax({
	//         url: "http://conquersky.io/chooseskin.php",
	//         //url: "http://localhost:1234/sco/chooseskin.php",
	//         method: "POST",
	//         data: data,
	//         dataType: "json",
	//         beforeSend: function () {
	//         },
	//         success: function (ress) {
	//             if (!ress.status) {
	//                 alert('Sorry, operation failed, please try again');
	//             } else {
	//                 $('#skin-item').attr('data-skinID', ress.id);
	//                 $('#skin-item').empty().html(ress.content);
	//             }
	//         },
	//         complete: function () {
	//         },
	//         error: function (msg) {
	//             console.log(msg);
	//         },
	//     });
	// }

	function reloadScore(){
		console.log("Reload score");
		var highScore = "";
		//save skinn ID
		var params = document.cookie.split(";");
		for (var i=0; i<params.length; i++) {
			var param = params[i].split("=");
			if(param[0].trim() == "usScore")
			{
				highScore = param[1];
				break;
			}
		}
		if(document.getElementById("userScore") != null){
			document.getElementById("userScore").textContent = highScore;
		}
	}

	// $('document').ready(function(){
	//     var roomId = document.location.search;
	//     var indexOfRoomID
	//     if(roomId != ""){
	//         var url = '//45.33.124.160/sco/'+ roomId;
	//         $("#sco-game").attr('src', url);
	//         $(".main-container, .social-group").hide();
	//         $("#iframe-game-on-page").show();
	//     }
	// });

FixInputPassword.init();
	function submitFunc(){
		var data = {
						'pwd': $('#user_passs').val(),
						'email': $('#user_email').val(),
					};
		$.ajax({
			url: configLink.requestLink_login,
			method: "POST",
			data: data,
			dataType: "json",
			success: function (data) {
				if(data.msg == "login fails")
				{
					$("#mgs_alter").removeClass("hidden");
				}else{
					location.hash = "index";
					initPageContent();
				}
			},
			complete: function () {
			},
			error: function (msg) {
				console.log(msg);
			},
		});
	}

	function register(){

		console.log(configLink.requestLink_register);
		var data = {
						'email': $('#regis_email').val().replace(/(<([^>]+)>)/ig,""),
						'pwd': $('#regis_pwd').val(),
					};
		$.ajax({
			url: configLink.requestLink_register,
			method: "POST",
			data: data,
			dataType: "json",
			success: function (data) {
				if(data.msg == "exist email")
				{
					$("#mgs_alter_exist").removeClass("hidden");
				}else if(data.msg == "register success"){
					location.hash = "index";
					initPageContent();
				}
			},
			complete: function () {
			},
			error: function (msg) {
				console.log(msg);
			},
		});
	}

	function showPopupEmail(){
		$('.party-group').show();
	}
	function hidePopupEmail(){
		$('.party-group').hide();
	}

	function loadUserInfo(){
		// check thông tin người dùng trong db.
		var params = document.cookie.split(";");
		for (var i=0; i<params.length; i++) {
			var param = params[i].split("=");
			if(param[0].trim() == "userInfo_name"){ name = param[1]; }
		}
		var userName =  decodeURIComponent(name).replace(/\+/g, ' ');
		var data = {'userName': userName};
		$.ajax({
			url: configLink.requestLink_getInfo,
			method: "POST",
			data: data,
			dataType: "json",
			success: function (data) {
				$('#user_email_edit').val(data.email);
				if(data.userName == undefined){
					$('#user_nickname_edit').val(userName);
				}else{
					$('#user_nickname_edit').val(data.userName);
				}
			},
			complete: function () {
			},
			error: function (msg) {
				console.log(msg);
			},
		});
	}

	function submitForm(){
		var data = {
						'userName': $('#user_nickname_edit').val(),
						'pwd': $('#user_passs_edit').val(),
						'email': $('#user_email_edit').val(),
					};
		$.ajax({
			url: configLink.requestLink_updateInfo,
			method: "POST",
			data: data,
			dataType: "json",
			success: function (data) {
				$('#user_email_edit').val(data.email);
				$('#user_nickname_edit').val(data.userName);
			},
			complete: function () {
			},
			error: function (msg) {
				console.log(msg);
			},
		});
	}

	function logout(){
		//logout facebook
		fbLogout();
		var data = {};
		$.ajax({
			url: configLink.requestLink_logout,
			method: "POST",
			data: data,
			dataType: "json",
			success: function (data) {

				if(data.msg == "logout success"){
					initPageContent();
				}
			},
			complete: function () {
			},
			error: function (msg) {
				console.log(msg);
			},
		});
	}

	function loginWithFb(){
		var data = {};
		$.ajax({
			url: configLink.requestLink_loginFb,
			method: "GET",
			data: data,
			success: function (data) {
				console.log(data);
				if(data.msg == "logout success"){
					initPageContent();
				}
			},
			complete: function () {
			},
			error: function (msg) {
				console.log(msg);
			},
		});
	}

	function getCookie(name) {
		var dc = document.cookie;
		var prefix = name + "=";
		var begin = dc.indexOf("; " + prefix);
		if (begin == -1) {
			begin = dc.indexOf(prefix);
			var end = document.cookie.indexOf(";", begin);
			if (begin != 0) return null;
		}
		else
		{
			begin += 2;
			var end = document.cookie.indexOf(";", begin);
			if (end == -1) {
				end = dc.length;
			}
		}
		// because unescape has been deprecated, replaced with decodeURI
		//return unescape(dc.substring(begin + prefix.length, end));
		return decodeURI(dc.substring(begin + prefix.length, end));
	}
	function initPageContent(){
		if(getCookie('userInfo_name') == null){
			//share block
			$('#sharetounlock').show();
			//btn logout hide
			$('#btn_logOut').hide();
			//btn login show
			$('#btn_logIn').show();
			//usser infor
			$('#userInfo').hide()
			//userGuset
			$('#userGuest').show();
		}else{
			$('#sharetounlock').hide();
			//btn logout show
			$('#btn_logOut').show();
			//btn login hide
			$('#btn_logIn').hide();
			//usser infor
			$('#userInfo').show();
			//set user avtar src
			$('#userInfo_avatar').attr('src', configLink.public_link + decodeURIComponent(getCookie('userInfo_avatar')));
			$('#userInfo_name').text(getCookie('userInfo_name'));
			$('#userScore').html(getCookie('usScore'));
			//userGuset
			$('#userGuest').hide();
		}
	}
	//decode html
	function decodeHtml(encodedStr){
		var parser = new DOMParser;
		var dom = parser.parseFromString(
			'<!doctype html><body>' + encodedStr,
			'text/html');
		var decodedString = dom.body.textContent;

		console.log(decodedString);
	}
	//init page;
initPageContent();
  window.fbAsyncInit = function() {
		// FB JavaScript SDK configuration and setup
		FB.init({
		appId      : configLink.facebookAppId, // FB App ID
		cookie     : true,  // enable cookies to allow the server to access the session
		xfbml      : true,  // parse social plugins on this page
		version    : 'v2.8' // use graph api version 2.8
		});

		// Check whether the user already logged in
		FB.getLoginStatus(function(response) {
			if (response.status === 'connected') {
				//display user data
				//getFbUserData();
			}
		});
	};

	// Load the JavaScript SDK asynchronously
	(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/en_US/sdk.js";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));

	// Facebook login with JavaScript SDK
	function fbLogin() {
		FB.login(function (response) {
			if (response.authResponse) {
				// Get and display the user profile data
				getFbUserData();
			} else {
				document.getElementById('status').innerHTML = 'User cancelled login or did not fully authorize.';
			}
		}, {scope: 'email'});
	}

	// Fetch the user profile data from facebook
	function getFbUserData(){
		FB.api('/me', {locale: 'en_US', fields: 'id,first_name,last_name,email,link,gender,locale,picture.type(large)'},
		function (response) {
			//send info to save
			var data = {
				email : response.email,
				id : response.id,
				last_name : response.last_name,
				first_name : response.first_name,
				picture : response.picture.data.url
			}

			$.ajax({
				url: configLink.requestLink_saveFbInfo,
				dataType: 'json',
				type: 'post',
				contentType: 'application/json',
				data: JSON.stringify(data),
				success: function( data, textStatus, jQxhr ){
					if(data.success){
						location.hash = "index";
						initPageContent();
					}
				},
				error: function( jqXhr, textStatus, errorThrown ){
					console.log( errorThrown );
				}
			});
		});
	}

	// Logout from facebook
	function fbLogout() {
		FB.logout(function() {
			console.log("logout");
		});
	}
	   function twitterAccess(){
		//twitter(this);
		var twiWindow = window.open(configLink.requestLink_loginTwiter, '_blank', 'height=400,width=800,left=250,top=100,resizable=yes', true);
		var timer = setInterval(function() {
			if(twiWindow.closed) {
				clearInterval(timer);
				location.hash = "index";
				initPageContent();
			}
		}, 500);
	}

	function gpAccess(){
		//twitter(this);
		var gpWindow = window.open(configLink.requestLink_loginGPlus, '_blank', 'height=400,width=800,left=250,top=100,resizable=yes', true);
		var timer = setInterval(function() {
			if(gpWindow.closed) {
				clearInterval(timer);
				location.hash = "index";
				initPageContent();
			}
		}, 500);
	}
	 function reloadIndexAds(){
		aiptag.cmd.display.push(
			function() {
				aipDisplayTag.display('dinosaurgame-io_300x250_ATF1');
				aipDisplayTag.display('dinosaurgame-io_300x250_ATF2');
			});
		}
	function disabledPWF(){

		document.getElementById("PlayWF").style.background = "#ccc" ;
		document.getElementById("PlayWF").disabled = "true";
	}
	function loadTabContent(tabUrl){
		$("#preloader").show();
		jQuery.ajax({
			url: tabUrl,
			cache: false,
			success: function(message) {
				jQuery("#tabcontent").empty().append(message);
				$("#preloader").hide();
			}
		});
	}
	loadTabContent('./funcUser/mini-leader-board.php?id=_all');
	$("#_tab1").addClass('current');
	jQuery(document).ready(function(){

		$("#preloader").hide();
		jQuery("[id^=_tab]").click(function(){

			// get tab id and tab url
			tabId = $(this).attr("id");
			tabUrl = jQuery("#"+tabId).attr("href");

			jQuery("[id^=_tab]").removeClass("current");
			jQuery("#"+tabId).addClass("current");

			// load tab content
			loadTabContent(tabUrl);
			return false;
		});
	});
