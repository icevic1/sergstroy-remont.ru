<?php if(isset($buyinbulk)){?>
	<?php if($buyinbulk){?>
    	<input type="hidden" value="<?php if(isset($pp_id))echo $pp_id?>" name="pp_id"/>
    	<ul>
    	<?php foreach($buyinbulk as $val){?>
        	<li><input type="checkbox" name="buyinbulk[]" value="<?php echo $val->bib_id?>" <?php echo $val->chk?>/><span><?php echo 'Buy in Bulk: '.$val->discount.'% for '.$val->renewals.' renewals'?></span></li>
        <?php }?>
        </ul>
    <?php }else{?>
    	No Record
    <?php }?>
<?php }else{?>
	No Record
<?php }?>