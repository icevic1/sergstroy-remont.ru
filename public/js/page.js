$(document).ready(function() {

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