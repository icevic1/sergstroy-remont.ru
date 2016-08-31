// JavaScript Document
$('.editor').each(function(){
	CKEDITOR.replace( $(this).attr('id'), {
		fullPage: false,
		allowedContent: true
	});
});
$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
	 for(var instanceName in CKEDITOR.instances){
		 CKEDITOR.instances[instanceName].destroy();
		 CKEDITOR.replace( instanceName, {
			fullPage: false,
			allowedContent: true
		});
	 }
	 /*$('.editor').each(function(){
		CKEDITOR.instances[$(this).attr('id')].destroy();
		CKEDITOR.replace( $(this).attr('id'), {
			fullPage: false,
			allowedContent: true
		});
	 });*/
	 /*CKEDITOR.replaceAll(); */ 
});