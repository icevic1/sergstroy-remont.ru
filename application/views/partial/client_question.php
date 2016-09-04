<div id="clientQuestion" class="container-fluid question-holder relative">
    <div class="row">
        <div class="col-sm-12 title text-center">
            <h1>Вопросы <?php // echo $myvar ?> </h1>
        </div>
    </div>
    <div class="row question-holder-content">
        <div class="col-md-12">
            <div class="container price-pack-holder">
                <div class="row">
                    <div class="col-sm-12">
                        <h2 class="q-title">Остались вопросы?</h2>
                        <span class="sub-qtitle">Задать их нашему специалисту</span>
                    </div>
                </div>
                <div class="row q-form">
                    <?php echo form_open('question/store', array('id' => 'form_guest_question', 'name'=>'form-guest-question', 'class' => 'form-horizontal form-guest-question', 'role'=>'form'));?>
                    <?php if (isset($succeed) && $succeed == true) { ?>
                        <div class="col-sm-12 error-holder">
                            <div class="alert alert-success alert-dismissible">
                                <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
                                <strong>Спосибо!</strong> Скоро с вами свяжутся по указанному телефону.
                            </div>
                        </div>
                    <?php } elseif (!isset($succeed) || (isset($succeed) && $succeed == false)) { ?>
                        <?php if( !empty($errors)) { ?>
                            <div class="col-sm-12 error-holder">
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
                                    <strong>Ошибка!</strong>
                                    <ul class="errors-list">
                                        <?php foreach ($errors as $errorItem) echo '<li>'.$errorItem. '</li>';?>
                                    </ul>
                                </div>
                            </div>
                        <?php } ?>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-6">
                                <?php echo form_input(array('name'=>'name','value'=>set_value('name'), 'class'=>'form-control','id'=>'q_name','placeholder'=>'Ваше имя'));?>
                                <?php //echo form_error('name');?>
                            </div>
                            <div class="col-sm-6">
                                <?php echo form_input(array('name'=>'phone','value'=>set_value('phone'), 'class'=>'form-control','id'=>'q_phone','placeholder'=>'Номер телефона'));?>
                                <?php //echo form_error('phone');?>
                            </div>
                        </div>
                        <div class="row q-text">
                            <div class="col-sm-12">
                                <?php echo form_textarea(array('name'=>'question','value'=>set_value('question'), 'class'=>'form-control','rows'=>'3','id'=>'review','placeholder'=>'Ваш вопрос...'));?>
                                <?php //echo form_error('question');?>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 q-action">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php echo form_submit(array('name'=>'submit_question', 'value'=> "Задать вопрос", 'class'=>'btn app-btn btn-orange btn-block'));?>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
    <div id="ajax_preloader" class="bg_opacity">
        <img class="ajax-loader" alt="waiting..." src="<?php echo base_url('public/img/ajax-loader.gif'); ?>" />
    </div>
</div><!-- end .question-holder -->
