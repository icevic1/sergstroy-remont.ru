$(document).ready(function() {

	$('select[data-toggle="selected-changer"]').on("change", function(e) {
		$this = $(this);
		$(".ajax-preloader").show();
        //$this.val()
		// var selected = Number(this.checked).toString();
		// $this.data('photo_id')
		// console.log(Number(this.checked).toString(), this.checked.toString());return;
		$.ajax({
			url: $(this).data('action_url'),
			dataType: "json",
			data: {"id": $(this).data('item_id'), "field": $(this).data('field'), "value": $this.val()},
			type: "GET",
			success: function(response){
				$(".ajax-preloader").hide();
				// console.log(response);
			}
		});
	});

	$('.gallery-photos').magnificPopup({
		delegate: 'a.selector',
		type: 'image',
		tLoading: 'Loading image #%curr%...',
		mainClass: 'mfp-img-mobile',
		gallery: {
			enabled: true,
			navigateByImgClick: true,
			preload: [0,1] // Will preload 0 - before current, and 1 after the current image
		},
		image: {
			tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
			/*titleSrc: function(item) {
				return item.el.attr('title') + '<small>by Marsel Van Oosten</small>';
			}*/
		}
	});

	$("#form_upload_photo").validate({
// 		debug: true,
		errorElement: 'span',
		errorClass: 'error',
		rules: {
			"images[]": {required: true,},
			"gallery_id":{required: true, digits: true},
		},
		messages: {
			"images[]":{
				required: 'Пожалуйста, выберите изображение!'
			},
		},
		errorPlacement: function (error, element) {
			element.parent().next().html(error); //error.insertAfter(element);
		},
		submitHandler: function(form) {
			$("#ajax_preloader").show();
			$(form).submit();
		}
	});
	$(".category-changer").on("change", function(e) {
		$this = $(this);
		// console.log($this.data('photo_id'));return;
        $this.closest("li.thumbnail").children(".ajax-preloader").show();
		$.ajax({
			url: "/admin/galleries/change_photo_category",
			dataType: "json",
			data: {"photo_id": $this.data('photo_id'), "category_id": $this.val()},
			type: "GET",
			success: function(response){
                $this.closest("li.thumbnail").children(".ajax-preloader").hide();
				// console.log(response);
			}
		});
	});

    $(".status-selected").on("change", function(e) {
        // if(this.checked)
        $this = $(this);
        $this.closest("li.thumbnail").children(".ajax-preloader").show();
        // ajax-preloader

        // var selected = Number(this.checked).toString();
        // $this.data('photo_id')
        // console.log(Number(this.checked).toString(), this.checked.toString());return;
        $.ajax({
            url: "/admin/galleries/change_photo_destination",
            dataType: "json",
            data: {"photo_id": $(this).data('photo_id'), "selected": Number(this.checked).toString()},
            type: "GET",
            success: function(response){
                $this.closest("li.thumbnail").children(".ajax-preloader").hide();
                // console.log(response);
            }
        });
    });

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
	/*$('.editor').each(function(){
		alert(123);
		CKEDITOR.replace( $(this).attr('id'), {
			fullPage: false,
			allowedContent: true,
			filebrowserBrowseUrl: '/browser/browse.php',
			filebrowserUploadUrl: '/uploader/upload.php'
		});
	});*/

	$.validator.addMethod("valueNotEquals", function(value, element, arg){
		return arg != value;
		}, "Value must not equal arg.");
	
}); //end ready