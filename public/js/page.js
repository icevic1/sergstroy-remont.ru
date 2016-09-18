$(document).ready(function() {

	$("a.delete-photo").on("click", function(e) {
		e.preventDefault();
		$this = $(this);
		if (confirm("Вы действительно хотите удулить фотографию безвозвратно?") == true) {
			$.ajax({
				url: $this.attr('href'),
				dataType: "json",
				// data: {typeid: $(this).val(), csrf_sc_name : csrf_sc_name},
				type: "GET",
				success: function(response){
					// console.log(response.msg, response.code, response);
					if (response.code == 0) {
						$('<div class="alert alert-success"><button data-dismiss="alert" class="close" type="button">×</button>' + response.msg + "</div>").insertBefore("ul.gallery-photos");
						$this.closest('li').remove();
					}
					else
						$('<div class="alert alert-error"><button data-dismiss="alert" class="close" type="button">×</button>' + response.msg + "</div>").insertBefore("ul.gallery-photos");

				}
			});
		}
		// alert(1112);
		// console.log(e);
	});
	// [name^='pages_title']
	$("input[name^='images']").change(function() {
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