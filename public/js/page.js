$(document).ready(function() {
	// [name^='pages_title']
	$("input[name^='userfile']").change(function() {
		$("#upload-files-info").empty();
		for (var i = 0; i < $(this).get(0).files.length; ++i) {
			$("#upload-files-info").append($("<span class='label label-info margin-right-5'>"+$(this).get(0).files[i].name+"</span>"));
		}
	});

	$('[placeholder]').focus(function() {
	  var input = $(this);
	  if (input.val() == input.attr('placeholder')) {
		input.val('');
		input.removeClass('placeholder');
	  }
	}).blur(function() {
	  var input = $(this);
	  if (input.val() == '' || input.val() == input.attr('placeholder')) {
		input.addClass('placeholder');
		input.val(input.attr('placeholder'));
	  }
	}).blur().parents('form').submit(function() {
	  $(this).find('[placeholder]').each(function() {
		var input = $(this);
		if (input.val() == input.attr('placeholder')) {
		  input.val('');
		}
	  })
	});
	$('.editor').each(function(){
		CKEDITOR.replace( $(this).attr('id'), {
			fullPage: false,
			allowedContent: true
		});
	});

	$.validator.addMethod("valueNotEquals", function(value, element, arg){
		return arg != value;
		}, "Value must not equal arg.");
	
}); //end ready