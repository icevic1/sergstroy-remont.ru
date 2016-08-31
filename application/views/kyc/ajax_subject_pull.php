<?php if (isset($Questions) && $Questions) foreach ($Questions as $QuestionID => $QTitle) {?>
<div class="form-group">
	<label class="col-md-3 control-label"><span class="required">*</span></label>
	<div class="col-md-9">
		<?php echo $QTitle;?><br />
            <input class="form-control" name="Answers[<?php echo $QuestionID;?>]" type="text" value="<?php echo set_value("Answers[{$QuestionID}]", ((isset($loadedAnswers[$QuestionID]))? $loadedAnswers[$QuestionID]:'')); ?>" />
            <?php echo form_error("Answers[{$QuestionID}]"); ?>
	</div>
</div>
<?php }?>