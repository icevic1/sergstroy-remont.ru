// JavaScript Document
var windowFocus 		= true;
var username;
var chatHeartbeatCount 	= 0;
var minChatHeartbeat 	= 1000;
var maxChatHeartbeat 	= 33000;
var chatHeartbeatTime 	= minChatHeartbeat;
var originalTitle;
var blinkOrder 			= 0;

var chatboxFocus 		= new Array();
var newMessages 		= new Array();
var newMessagesWin 		= new Array();
var chatBoxes 			= new Array();
var user1='';
var user2='';
var base_url='';
function chatWith(chatboxid,chatboxtitle) {
	createChatBox(chatboxid,chatboxtitle);
	$("#chatbox_"+chatboxid+" .chatboxtextarea").focus();
}
function chatHeartbeat(){
  var itemsfound = 0;
	if (windowFocus == false) {
		var blinkNumber = 0;
		var titleChanged = 0;
		for (x in newMessagesWin) {
			if (newMessagesWin[x] == true) {
				++blinkNumber;
				if (blinkNumber >= blinkOrder) {
					document.title = x+' says...';
					titleChanged = 1;
					break;	
				}
			}
		}
		
		if (titleChanged == 0) {
			document.title = originalTitle;
			blinkOrder = 0;
		} else {
			++blinkOrder;
		}

	} else {
		for (x in newMessagesWin) {
			newMessagesWin[x] = false;
		}
	}

	for (x in newMessages) {
		if (newMessages[x] == true) {
			if (chatboxFocus[x] == false) {
				//FIXME: add toggle all or none policy, otherwise it looks funny
				$('#chatbox_'+x+' .chatboxhead').toggleClass('chatboxblink');
			}
		}
	}
	
   var cct = $.cookie('csrf_cookie_name');
   $.post(base_url+'chat/get_chat',{"csrf_sc_name":cct,"user":user1}, function(data) {
		$.each(data.items, function(i,item){
			if(item){
				chatboxid = item.f;
				chatboxtitle=item.f_name;
				if ($("#chatbox_"+chatboxid).length <= 0) {
					createChatBox(chatboxid,chatboxtitle);
				}
				if ($("#chatbox_"+chatboxid).css('display') == 'none') {
					$("#chatbox_"+chatboxid).css('display','block');
					restructureChatBoxes();
				}
				
				if (item.s == 1) {
					//item.f = username;
				}

				if (item.s == 2) {
					$("#chatbox_"+chatboxid+" .chatboxcontent").append('<div class="chatboxmessage"><span class="chatboxinfo">'+item.m+'</span></div>');
				} else {
					newMessages[chatboxtitle] = true;
					newMessagesWin[chatboxtitle] = true;
					$("#chatbox_"+chatboxid+" .chatboxcontent").append('<div class="chatboxmessage"><span class="chatboxmessagefrom">'+item.f_name+':&nbsp;&nbsp;</span><span class="chatboxmessagecontent">'+item.m+'</span></div>');
				}

				$("#chatbox_"+chatboxid+" .chatboxcontent").scrollTop($("#chatbox_"+chatboxid+" .chatboxcontent")[0].scrollHeight);
				itemsfound += 1;
			}
		});
		chatHeartbeatCount++;

		if (itemsfound > 0) {
			chatHeartbeatTime = minChatHeartbeat;
			chatHeartbeatCount = 1;
		} else if (chatHeartbeatCount >= 10) {
			chatHeartbeatTime *= 2;
			chatHeartbeatCount = 1;
			if (chatHeartbeatTime > maxChatHeartbeat) {
				chatHeartbeatTime = maxChatHeartbeat;
			}
		}
		setTimeout('chatHeartbeat();',chatHeartbeatTime);
   },"json");
}
function createChatBox(chatboxid,chatboxtitle){
	if ($("#chatbox_"+chatboxid).length > 0) {
		if ($("#chatbox_"+chatboxid).css('display') == 'none') {
			$("#chatbox_"+chatboxid).css('display','block');
			restructureChatBoxes();
		}
		$("#chatbox_"+chatboxid+" .chatboxtextarea").focus();
		return;
	}
	$(" <div />" ).attr("id","chatbox_"+chatboxid)
	.addClass("chatbox")
	.html('<div class="chatboxhead"><div class="chatboxtitle">'+chatboxtitle+'</div><div class="chatboxoptions"><a href="javascript:void(0)" onclick="javascript:toggleChatBoxGrowth(\''+chatboxid+'\')">-</a> <a href="javascript:void(0)" onclick="javascript:closeChatBox(\''+chatboxid+'\')">X</a></div><br clear="all"/></div><div class="chatboxcontent"></div><div class="chatboxinput"><textarea class="chatboxtextarea" onkeydown="javascript:return checkChatBoxInputKey(event,this,\''+chatboxid+'\');"></textarea></div>')
	.appendTo($( "body" ));
			   
	$("#chatbox_"+chatboxid).css('bottom', '0px');
	
	chatBoxeslength = 0;

	for (x in chatBoxes) {
		if ($("#chatbox_"+chatBoxes[x]).css('display') != 'none') {
			chatBoxeslength++;
		}
	}

	if (chatBoxeslength == 0) {
		$("#chatbox_"+chatboxid).css('right', '200px');
	} else {
		width = (chatBoxeslength)*(225+7)+200;
		$("#chatbox_"+chatboxid).css('right', width+'px');
	}
	
	chatBoxes.push(chatboxid);
	
	$("#chatbox_"+chatboxid+" .chatboxtextarea").blur(function(){
		chatboxFocus[chatboxid] = false;
		$("#chatbox_"+chatboxid+" .chatboxtextarea").removeClass('chatboxtextareaselected');
	}).focus(function(){
		chatboxFocus[chatboxid] = true;
		newMessages[chatboxid] = false;
		$('#chatbox_'+chatboxid+' .chatboxhead').removeClass('chatboxblink');
		$("#chatbox_"+chatboxid+" .chatboxtextarea").addClass('chatboxtextareaselected');
	});

	$("#chatbox_"+chatboxid).click(function() {
		if ($('#chatbox_'+chatboxid+' .chatboxcontent').css('display') != 'none') {
			$("#chatbox_"+chatboxid+" .chatboxtextarea").focus();
		}
	});

	$("#chatbox_"+chatboxid).show();
}
function restructureChatBoxes() {
	align = 0;
	for (x in chatBoxes) {
		chatboxid = chatBoxes[x];

		if ($("#chatbox_"+chatboxid).css('display') != 'none') {
			if (align == 0) {
				$("#chatbox_"+chatboxid).css('right', '200px');
			} else {
				width = (align)*(225+7)+200;
				$("#chatbox_"+chatboxid).css('right', width+'px');
			}
			align++;
		}
	}
}
function closeChatBox(chatboxid) {
	$('#chatbox_'+chatboxid).css('display','none');
	restructureChatBoxes();
	/*$.post(base_url+'chat/close_chat', { chatbox: chatboxid,"csrf_sc_name":$.cookie('csrf_cookie_name')} , function(data){});*/
}
function checkChatBoxInputKey(event,chatboxtextarea,chatboxid) {
	if(event.keyCode == 13 && event.shiftKey == 0)  {
		message = $(chatboxtextarea).val();
		message = message.replace(/^\s+|\s+$/g,"");
		$(chatboxtextarea).val('');
		$(chatboxtextarea).focus();
		$(chatboxtextarea).css('height','44px');
		if (message != '') {
			$.post(base_url+'chat/send_chat', {user:user1,to: chatboxid, message: message,"csrf_sc_name":$.cookie('csrf_cookie_name')} , function(data){
				message = message.replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/\"/g,"&quot;");
				$("#chatbox_"+chatboxid+" .chatboxcontent").append('<div class="chatboxmessage"><span class="chatboxmessagefrom">'+data.chat_name+':&nbsp;&nbsp;</span><span class="chatboxmessagecontent">'+message+'</span></div>');
				$("#chatbox_"+chatboxid+" .chatboxcontent").scrollTop($("#chatbox_"+chatboxid+" .chatboxcontent")[0].scrollHeight);
			},"json");
		}
		chatHeartbeatTime = minChatHeartbeat;
		chatHeartbeatCount = 1;

		return false;
	}

	var adjustedHeight = chatboxtextarea.clientHeight;
	var maxHeight = 94;

	if (maxHeight > adjustedHeight) {
		adjustedHeight = Math.max(chatboxtextarea.scrollHeight, adjustedHeight);
		if (maxHeight)
			adjustedHeight = Math.min(maxHeight, adjustedHeight);
		if (adjustedHeight > chatboxtextarea.clientHeight)
			$(chatboxtextarea).css('height',adjustedHeight+8 +'px');
	} else {
		$(chatboxtextarea).css('overflow','auto');
	}
}
function toggleChatBoxGrowth(chatboxid) {
	if ($('#chatbox_'+chatboxid+' .chatboxcontent').css('display') == 'none') {  
		var minimizedChatBoxes = new Array();
		if ($.cookie('chatbox_minimized')) {
			minimizedChatBoxes = $.cookie('chatbox_minimized').split(/\|/);
		}
		var newCookie = '';
		for (i=0;i<minimizedChatBoxes.length;i++) {
			if (minimizedChatBoxes[i] != chatboxid) {
				newCookie += chatboxid+'|';
			}
		}
		newCookie = newCookie.slice(0, -1)
		$.cookie('chatbox_minimized', newCookie);
		$('#chatbox_'+chatboxid+' .chatboxcontent').css('display','block');
		$('#chatbox_'+chatboxid+' .chatboxinput').css('display','block');
		$("#chatbox_"+chatboxid+" .chatboxcontent").scrollTop($("#chatbox_"+chatboxid+" .chatboxcontent")[0].scrollHeight);
	} else {
		
		var newCookie = chatboxid;

		if ($.cookie('chatbox_minimized')) {
			newCookie += '|'+$.cookie('chatbox_minimized');
		}
		$.cookie('chatbox_minimized',newCookie);
		$('#chatbox_'+chatboxid+' .chatboxcontent').css('display','none');
		$('#chatbox_'+chatboxid+' .chatboxinput').css('display','none');
	}
}
function startChatSession(){
	$.ajax({
	  url: base_url+'chat/start_chat_session',
	  cache: false,
	  data:{"csrf_sc_name":$.cookie('csrf_cookie_name')},
	  dataType: "json",
	  success: function(data) {
		//username = data.username;
		$.each(data.items, function(i,item){
			if (item)	{ // fix strange ie bug
				chatboxtitle = item.f;
				if ($("#chatbox_"+chatboxtitle).length <= 0) {
					createChatBox(chatboxtitle,1);
				}
				
				if (item.s == 1) {
					item.f = username;
				}

				if (item.s == 2) {
					$("#chatbox_"+chatboxtitle+" .chatboxcontent").append('<div class="chatboxmessage"><span class="chatboxinfo">'+item.m+'</span></div>');
				} else {
					$("#chatbox_"+chatboxtitle+" .chatboxcontent").append('<div class="chatboxmessage"><span class="chatboxmessagefrom">'+item.f+':&nbsp;&nbsp;</span><span class="chatboxmessagecontent">'+item.m+'</span></div>');
				}
			}
		});
		
		for (i=0;i<chatBoxes.length;i++) {
			chatboxtitle = chatBoxes[i];
			$("#chatbox_"+chatboxtitle+" .chatboxcontent").scrollTop($("#chatbox_"+chatboxtitle+" .chatboxcontent")[0].scrollHeight);
			setTimeout('$("#chatbox_"+chatboxtitle+" .chatboxcontent").scrollTop($("#chatbox_"+chatboxtitle+" .chatboxcontent")[0].scrollHeight);', 100); // yet another strange ie bug
		}
		setTimeout('chatHeartbeat();',chatHeartbeatTime);
	   }
	});
}