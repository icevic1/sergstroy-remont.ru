<div class="ot-panel ot-panel-white">
    <div class="ot-panel-wrapper">
        <div class="ot-panel-content">
            <div class="ot-form ot-form-new-password">
                <?php echo form_open('account/update_password');?>
                    <h1><?php echo label('lbl_create_your_password')?></h1>
                    <div class="ot-message-error"><?php if($this->session->flashdata('msg')) echo $this->session->flashdata('msg')?></div>
                    <div class="ot-item ot-item-account">
                        <input type="hidden" name="h_cust_number" value="<?php if(isset($cust['cust_number'])) echo $cust['cust_number']?>"/>
                        <input type="hidden" name="txt_old_pwd" value="<?php if(isset($cust['pwd'])) echo $cust['pwd']?>"/>
                        <input type="password" name="txt_new_pwd" placeholder="<?php echo label('lbl_enter_new_pwd')?>"/>
                    </div>
                    <div class="ot-item ot-item-password">
                        <input type="password" name="txt_new_pwd_confirm" placeholder="<?php echo label('lbl_repeat_new_password')?>"/>
                        <span class="ot-description"><?php echo label('lbl_min_pwd_length')?></span>
                    </div>
                    <div class="ot-item">
                        <input type="submit" value="<?php echo label('lbl_confirm')?>" class="ot-btn ot-btn-orange ot-btn-default" />
                    </div>
                 <?php echo form_close();?>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>