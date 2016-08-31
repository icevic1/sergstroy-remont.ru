<!-- Login panel -->
<div class="col-sm-8 col-sm-offset-2">
<div id="panel_login" class="panel panel-login">
	<div class="panel-heading">
		<h3 class="panel-hd-title"><?php echo label('lbl_login_title');?></h3>
	</div>
	<!-- recovery Panel body -->
	<div class="panel-body">
		<?php echo form_open('account/login', array('id' => 'frm_login', 'name'=>'frm_login', 'class' => 'form-horizontal frm_login', 'role'=>'form'));?>
			<input type="hidden" name="next_url" value="<?php echo $this->input->get('current_url');?>" />
			<div class="alerts-wraper"><?php echo (isset($err_msg) ? $err_msg:'');?></div>
			<div class="form-group">
				<label for="txt_email" class="col-sm-3 control-label">Login</label>
				<div class="col-sm-6">
					<input class="form-control inline-block" style="width: 90%;" type="text" placeholder="Enter your's login email or Phone #" name="txt_email" id="txt_email" value="" maxlength="50" autocomplete="off" />
				</div>
			</div>
			<div class="form-group">
				<label for="txt_email" class="col-sm-3 control-label">Password</label>
				<div class="col-sm-6">
					<input class="form-control inline-block" style="width: 90%;" type="password" placeholder="Password" id="txt_pwd" name="txt_pwd" value="" autocomplete="off" />
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-6 col-sm-offset-3">
					<div class="checkbox checkbox-success">
						<input type="checkbox" class="styled" id="chk_remember" name="chk_remember" /><label for="chk_remember" class="inline">Remember password</label>
					</div>
				</div>
			</div>
			<div class="form-group form-group-captcha">
				<label for="txt_email" class="col-sm-3 col-xs-12 control-label">Enter the code</label>
				<div class="col-sm-3 col-xs-6">
					<input class="form-control" type="text" placeholder="" id="txt_word" name="txt_word" value="" autocomplete="off" />
				</div>
				<div class="col-sm-3 col-height">
					<div class="ot-captcha col-middle inline-block"></div>
					<a href="" class="refresh_captcha"><span class="glyphicon glyphicon-refresh col-middle" aria-hidden="true"></span></a>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-6 col-sm-offset-3">
					<button type="submit" class="btn btn-success"><?php echo label('lbl_enter');?></button>
					<a class="ot-link-recover-pwd inline-blockcol-middle" href="" data-target="#panel_recovery" data-show="panel"><?php echo label('lbl_recover_password');?></a>
				</div>
			</div>
		<?php echo form_close();?>
	</div><!-- end panel body -->
</div><!-- end login panel -->
</div>
<!-- Recovery panel -->
<div id="panel_recovery" class="col-xs-12 col-sm-8 col-sm-offset-4 none">
<div class="panel panel-recovery ">
	<div class="panel-heading">
		<div class="btn-group pull-right">
			<button type="button" class="close" aria-label="Close" data-target="#panel_recovery" data-hide="panel"><span aria-hidden="true">&times;</span></button>
		</div>
		<h3 class="panel-title panel-hd-title"><?php echo label('lbl_recover_password')?></h3>
	</div>
	<!-- recovery Panel body -->
	<div class="panel-body">
		<?php echo form_open('account/reset_password', array('id'=>'frm_recovery_pass', 'name'=>'frm_recovery_pass', 'class'=>'form-horizontal', 'role'=>'form'));?>
			<div class="ot-reset-password aria-disabled-">
				<div class="alerts-wraper"></div>
				<div class="form-group">
					<label for="txt_email" class="col-sm-4 control-label">Login</label>
					<div class="col-sm-6">
						<input class="form-control" type="text" name="login_name" id="login_name" value="" maxlength="50" autocomplete="off" placeholder="Enter your's login email or Phone #" />
					</div>
				</div>
				<div class="form-group form-group-captcha">
					<label for="txt_email" class="col-sm-4 col-xs-12 control-label">Enter the captcha</label>
					<div class="col-sm-3 col-xs-6">
						<input class="form-control" type="text" id="captcha" name="captcha" value="" autocomplete="off" placeholder="" />
					</div>
					<div class="col-sm-3 col-height">
						<div class="ot-captcha col-middle inline-block"><img border="0" src="http://lk.smart.local/public/captcha/b2387.png" /></div>
						<a href="" class="refresh_captcha"><span class="glyphicon glyphicon-refresh col-middle" aria-hidden="true"></span></a>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-sm-6 col-sm-offset-4">
						<input type="submit" id="confirm_account" class="btn btn-success" value="Send verification code" />
					</div>
				</div>
			</div>
			<!-- check confirm code -->
			<div class="confirm-code none aria-disabled-">
				<hr class="featurette-divider" />
				<div class="alerts-wraper"></div>
				<div class="form-group">
					<label for="txt_email" class="col-sm-4 col-xs-12 control-label">Enter verification code</label>
					<div class="col-sm-4 col-xs-6">
						<input class="form-control" type="text" name="verify_code" value="" autocomplete="off" placeholder="" />
					</div>
					<div class="col-sm-3 padding-x-0">
						<input type="button" id="verify_code" class="btn btn-success verify_code" value="<?php echo label('lbl_confirm')?>" />
					</div>
				</div>
			</div><!-- end confirm code -->
			<!-- add new password -->
			<div class="new-password none">
				<hr class="featurette-divider" />
				<div class="alerts-wraper"></div>
				<div class="form-group">
					<label for="txt_email" class="col-sm-4 col-xs-12 control-label">Enter new password</label>
					<div class="col-sm-4 col-xs-6">
						<input class="form-control" type="password" id="new_password" name="new_password" value="" autocomplete="off" placeholder="" />
					</div>
				</div>
				<div class="form-group">
					<label for="txt_email" class="col-sm-4 col-xs-12 control-label">Repeat password</label>
					<div class="col-sm-4 col-xs-6">
						<input class="form-control" type="password" id="new_password2" name="new_password2" value="" autocomplete="off" placeholder="" />
					</div>
					<div class="col-sm-3 padding-x-0">
						<input type="button" id="save_password" class="btn btn-success" value="Save" />
					</div>
				</div>
			</div>
			
		<?php echo form_close();?><!-- end recovery form -->
	</div><!-- end panel body -->
	<!-- panel footer -->
	<div class="panel-footer text-right">
		<button type="button" class="btn btn-default btn-sm" data-target="#panel_recovery" data-hide="panel">
			<span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span><span> Close</span>
		</button>
	</div><!-- end panel footer -->
</div><!-- end recovery panel -->
</div><!-- end recovery panel -->

<script type="text/javascript">
 	
$(document).ready(function(e) {

	$('.ot-link-recover-pwd').click(function(e) {
		e.preventDefault();
		$('.reset-pwd-dialog').show();
	});

	$(document).on('click', '#confirm_account', function(e) {
		e.preventDefault();
		if ($('input[name="login_name"]').val() == '') {
			myAlert.notify('.ot-reset-password .alerts-wraper', 'Sorry, the login name has not been blank. Please try again.');
			return false;
		}
		
		preloading.show();
		$.post('<?php echo site_url('account/check_account');?>', $(this).closest("form").serialize(), function(response) {
			preloading.hide();
			
			bootbox.dialog({message: response.msg, buttons: { main: { label: "Close",  className: "btn-default btn-xs" /*, callback: function() { Example.show("Primary button");}*/ }}});
// 			bootbox.alert(response.msg, 'OK');
			if (response.status == 0) {
				$('.ot-reset-password').addClass('aria-disabled');
				$('.confirm-code').show();
			} else {
				myAlert.notify('.ot-reset-password .alerts-wraper', response.msg);
				get_captcha();
			}
		}, "json");
	});
	
	$(document).on('click', '#verify_code', function(e) {
		e.preventDefault();
		preloading.show();
		var formValues =  $(this).closest("form").serialize();
		
		$.post('<?php echo site_url('account/verify_code');?>', formValues, function(response) {
			preloading.hide();
			bootbox.dialog({message: response.msg, buttons: { main: { label: "Close",  className: "btn-default btn-xs" /*, callback: function() { Example.show("Primary button");}*/ }}});
			
			if (response.status == 0) {
				$('.confirm-code').addClass('aria-disabled');
				$('.new-password').show();
			} else {
				myAlert.notify('.confirm-code .alerts-wraper', response.msg);
			}
			
		}, "json");
	});
	
	$(document).on('click', '#save_password', function(e) {
		e.preventDefault();

		if($('input[name="login_name"]').val().match(/^[0-9]{6,13}$/) && !$('input[name="new_password"]').val().match(/^[0-9]{6}$/)) {
			myAlert.notify('.new-password .alerts-wraper', 'Password should have 6 digits. Please try again.');
			return false;
		}

		if ($('input[name="new_password"]').val() == '') {
			myAlert.notify('.new-password .alerts-wraper', 'Sorry, the password has not been blank. Please try again.');
			return false;
		}
		if ($('input[name="new_password"]').val() != $('input[name="new_password2"]').val()) {
			myAlert.notify('.new-password .alerts-wraper', 'Confirm password field are not matched. Please try again.');
			return false;
		}
		
		preloading.show();
		
		$.post('<?php echo site_url('account/save_password');?>', $(this).closest("form").serialize(), function(response) {
			preloading.hide();
			
			if (response.status == 0) {
				bootbox.dialog({message: response.msg, buttons: { main: { label: "Close",  className: "btn-default btn-xs" , callback: function() { location.reload(true);} }}});
			} else {
				bootbox.dialog({message: response.msg, buttons: { main: { label: "Close",  className: "btn-default btn-xs" /*, callback: function() { Example.show("Primary button");}*/ }}});
				myAlert.notify('.new-password .alerts-wraper', response.msg);
			}
			
		}, "json");
	});


 	var t;
	$('.ot-item-account .ot-ico-help, .ot-tooltip-help-phone-number').hover(function() {
		clearTimeout(t);
		$('.ot-tooltip-help-phone-number').show();
	}, function() {
		t = setTimeout(function() {$('.ot-tooltip-help-phone-number').hide();}, 20);
	});
	
	$('.ot-item-password .ot-ico-help, .ot-tooltip-help-recovery').hover(function() {
		clearTimeout(t);
		$('.ot-tooltip-help-recovery').show();
	}, function() {
		t = setTimeout(function() {$('.ot-tooltip-help-recovery').hide();}, 20);
	}); 
	
	$('.refresh_captcha').click(function(e) {
		e.preventDefault();
        get_captcha();
    });
	get_captcha();
	//refreshPage();
 });

function get_captcha(){
	var cct = $.cookie('csrf_cookie_name');
	$.post('<?php echo base_url('account/generate_captcha')?>',{'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}, function(data) {
	 	$('.ot-captcha').html(data.captcha);
	 	$('input[name="captcha"').val('');
	},"json");
}
function refreshPage(){
	setInterval(function() {
		var cct = $.cookie('csrf_cookie_name');
		$.post("<?php echo site_url('account/check_session_exp')?>",{'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}, function(res) {
			if(res==1){
				get_captcha();
			}
		},"json");
	}, 1000 * 60*60);
}
</script>