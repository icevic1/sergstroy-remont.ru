<div class="ot-panel ot-panel-white">
    <div class="ot-panel-wrapper">
        <div class="ot-panel-content">
            <div class="ot-form ot-form-change-password">
               	<?php echo form_open('account/change_password', array('id' => 'frm_password'));?>
                    <h1><?php echo label('lbl_changing_the_pass')?></h1>
                    	<div class="ot-message-error"><?php if($this->session->flashdata('msg')) echo $this->session->flashdata('msg')?></div>
                    <div class="ot-item ot-item-account">
                     	<input type="hidden" name="h_cust_number" value="<?php if(isset($cust['cust_number'])) echo $cust['cust_number']?>"/>
                        <input type="password" name="txt_old_pwd" value="<?php if(isset($cust['pwd'])) echo $cust['pwd']?>" placeholder="<?php echo label('lbl_enter_old_pass')?>"/>
                    </div>
                    <div class="ot-item ot-item-account">
                        <span class="ot-description"><?php echo label('lbl_enter_new_pwd_note')?></span>
                        <input type="password" name="txt_new_pwd" placeholder="<?php echo label('lbl_enter_new_pwd')?>" />
                    </div>
                    <div class="ot-item ot-item-password">
                        <input type="password" name="txt_confirm_new_pwd" placeholder="<?php echo label('lbl_repeat_new_password')?>" />
                    </div>
                    <div class="ot-item">
                        <input type="submit" value="<?php echo label('lbl_save')?>" class="ot-btn ot-btn-orange ot-btn-default" />
                    </div>
                <?php echo form_close();?>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>