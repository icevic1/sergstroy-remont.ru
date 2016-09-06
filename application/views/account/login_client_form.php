<?php if ($this->session->userdata('msg')) {?>
    <div class="alert alert-danger">
        <strong>Внимание!</strong> <?php echo $this->session->userdata('msg'); $this->session->set_userdata('msg', null)?>
    </div>
<?php }?>
<?php echo form_open('account/login', array('id' => 'form_client_login', 'name'=>'form_client_login', 'class' => 'form-horizontal form_client_login', 'role'=>'form'));?>
    <div class="col-sm-8">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <?php
                    $client = $this->session->userdata('Client');
                    $options = array('name'=>'user_name', 'class'=>'form-control','id'=>'u_name','placeholder'=>'Имя клиента');
                    if ($client) {
                        $options['disabled'] = 'disabled';
                        $options['value'] = $client['name'];
                    }
                    echo form_input($options);?>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <?php
                    $options = array('name'=>'user_phone', 'class'=>'form-control','id'=>'u_phone','placeholder'=>'Номер телефона');
                    if ($client) {
                        $options['disabled'] = 'disabled';
                        $options['value'] = $client['mobile_no'];
                    }
                    echo form_input($options);?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-4 q-action">
        <div class="row">
            <div class="col-sm-12">
                <?php if ($this->session->userdata('Client')) {?>
                    <a href="/account/logout" class="btn app-btn btn-orange btn-block">Выйти</a>
                <?php } else { ?>
                    <?php echo form_submit(array('name'=>'submit_question', 'value'=> "Войти", 'class'=>'btn app-btn btn-orange btn-block'));?>
                <?php }?>
            </div>
        </div>
    </div>
<?php echo form_close(); ?>