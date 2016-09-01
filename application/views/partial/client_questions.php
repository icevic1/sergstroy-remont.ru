<div id="clientQuestion" class="container-fluid question-holder">
    <div class="row">
        <div class="col-sm-12 title text-center">
            <h1>Вопросы</h1>
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
                    <?php echo form_open('home/leave-question', array('id' => 'form-guest-question', 'name'=>'form-guest-question', 'class' => 'form-horizontal form-guest-question', 'role'=>'form'));?>
                    <div class="col-sm-12 error-holder">
                        <div class="alert alert-danger none">
                            <a href="#" class="close" data-hide="alert">&times;</a>
                            <strong>Ошибка!</strong>
                            <ul class="errors-list"></ul>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-6">
                                <?php echo form_input(array('name'=>'name','value'=>set_value('name'), 'class'=>'form-control','id'=>'q_name','placeholder'=>'Ваше имя'));?>
                                <?php echo form_error('name');?>
                            </div>
                            <div class="col-sm-6">
                                <?php echo form_input(array('name'=>'phone','value'=>set_value('phone'), 'class'=>'form-control','id'=>'q_phone','placeholder'=>'Номер телефона'));?>
                                <?php echo form_error('phone');?>
                            </div>
                        </div>
                        <div class="row q-text">
                            <div class="col-sm-12">
                                <?php echo form_textarea(array('name'=>'question','value'=>set_value('question'), 'class'=>'form-control','rows'=>'3','id'=>'review','placeholder'=>'Ваш вопрос...'));?>
                                <?php echo form_error('question');?>
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
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div><!-- end .question-holder -->
