/* To do register subiz widget code */
function do_register(data) {
	var subiz_confirm = confirm(data.msg);
	window.location.reload();
}

$(document).ready(function() {
	/* Register subiz widget code */
	$('#subiz_register_widget').submit(function() {
		var displayname = $('#subiz_register_name').val();
		var email = $('#subiz_register_email').val();
		var registerUrl = 'https://dashboard.subiz.com/signup.html';
		$.ajax({
			url    : registerUrl,
			beforeSend: function(){
				$('#subiz_register_widget').css('display', 'none');
				$('#subiz_loading_register').css('display', 'block');
			},
			dataType : "jsonp",
			type   : 'get',
			data   : 'displayname='+displayname+'&email='+email+'&callback=do_register',
		});
		return false;
	})	

	/* Install subiz widget code */
	$('#subiz_install_widget').submit(function() {
		var subiz_email = $('#subiz_verified_email').val();
		var postUrl = 'http://dashboard.subiz.com/widget/embed.html'; 
		$.ajax({
			url    : postUrl,
			beforeSend: function(){
				$('#subiz_install_widget').css('display', 'none');
				$('#subiz_loading_action').css('display', 'block');
			},
			dataType : "jsonp",
			type   : 'get',
			data   : 'email='+subiz_email+'&callback=do_install',
		});
		return false;
	})

	$("#subizBtRegister").click(function() {
		$("#subizActivateBox").css("display", "none");
		$("#subizDisActivateBox").css("display", "block");
	})
	$("#subizBtActivate").click(function() {
		$("#subizActivateBox").css("display", "block");
		$("#subizDisActivateBox").css("display", "none");
	})
})

/* To do install subiz widget code */
function do_install(data) {
	if(data.status == true) {
		var subiz_code 				= data.code;
		var subiz_verified_email 	= $('#subiz_verified_email').val();
	}
	$.ajax({
		url    : ajaxurl,
		type   : 'post',
		data   : {action: 'my_action', subiz_code: subiz_code},
		success: function(data) {
			document.forms["subiz_install_widget"].submit();
		}
	});
	return false;
}