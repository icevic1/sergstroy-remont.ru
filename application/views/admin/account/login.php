<!-- Le styles -->
<style type="text/css">
html, body {
    background-color: #eee;
} 
body {
    padding-top: 150px;
    padding-bottom: 40px;        
}
.form-login {
     float: none;
	 padding: 20px;
     margin-left: auto;
     margin-right: auto;
}
.form-login{
		  -webkit-border-radius: 10px 10px 10px 10px;
       -moz-border-radius: 10px 10px 10px 10px;
            border-radius: 10px 10px 10px 10px;
    -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.15);
       -moz-box-shadow: 0 1px 2px rgba(0,0,0,.15);
            box-shadow: 0 1px 2px rgba(0,0,0,.15);
			 background-color: #fff;
}
em,.error{
	color:#FF0000;
}
</style>
<div class="span3 form-login"> 
<?php echo form_open(base_url('admin/account/login/'),array('id'=>'frmlogin','name'=>'frmlogin','autocomplete'=>'off','autocorrect'=>'off','autocapitalize'=>'off')); ?>
		<label class="brand"><h2>LOGIN</h2></label>
		<div class="row-fluid" style="border-top:1px solid #CCC; padding-top:5px">
				<div class="row-fluid">
					<label>Username</label>
					<input type="text" name="txt_username" autocomplete="off" autocorrect="off" autocapitalize="off"/><em>*</em>
				</div>							
				<div class="row-fluid">
					<label>Password</label>
					<input type="password" name="txt_pwd" autocomplete="off" autocorrect="off" autocapitalize="off"/><em>*</em>
				</div>
				<div class="row-fluid">
					 <input type="submit" class="btn btn-primary" value="Login" id="lbl_login_login"/>
				</div>
                 <?php 
					if($this->session->flashdata('msg')){
						echo '<div class="error" style="padding:5px 0">'.$this->session->flashdata('msg').'</div>';
					}
				?>
		</div>			
<?php echo form_close(); ?>  
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#frm_login").validate({
            rules: {	
                txt_username: "",
                txt_pwd: ""
            }
        }); 
    });
</script>