var base_url=$('#hd_baseurl').val();
function change_pwd(){
	if($('#frm_pwd').valid() == true){
		$.post( base_url+"/change_password",$('#frm_pwd').serialize(), function(data) {
		   alert(data);
		},"json");
	}
}
function change_tariff(){
	var cct = $.cookie('csrf_cookie_name');
	$.post( base_url+"/change_tariff",{"csrf_sc_name":cct}, function(data) {
	   alert(data);
	},"json");
}
function change_simname(){
	//if($('#frm_pwd').valid() == true){
		$.post( base_url+"/change_simname",$('#frm_simname').serialize(), function(data) {
		   alert(data);
		},"json");
	//}
}
function change_contactnumber(){
	//if($('#frm_pwd').valid() == true){
		$.post( base_url+"/change_contactnumber",$('#frm_contactnumber').serialize(), function(data) {
		   alert(data);
		},"json");
	//}
}
function reset_pwd(selft){
	var form=$(selft).closest("form");
	var url=form.attr('action').replace("login", "reset_password");
	$.post(url,form.serialize(), function(data) {
	  $.msgBox({
		content:data,
		type:"info"
	});
	},"json");
}
/*function change_email(){
	$.post( base_url+"/change_email",$('#frm_email').serialize(), function(data) {
	   alert(data);
	},"json");
}*/
function show_message(_message){
	$.msgBox({
		content:_message,
		type:"info"
	});
}