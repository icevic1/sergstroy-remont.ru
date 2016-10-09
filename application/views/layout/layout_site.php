<?php $this->view('layout/header_area');?>
<!-- Begin Body conainer block -->
<div id="top" class="container">
	<div class="row">
		<!-- start blok 1 -->
		<div class="block-1 col-xs-12 col-md-8">
      		<div class="panel panel-default -green-smart-gradient panel-customer-details">
				<div class="panel-heading">
					<h4 class="pull-left"><i class="icomoon icon-office"></i> <?php echo ((!empty($current_customer['CustName']))? $current_customer['CustName'] : '');?></h4>
					<?php if($this->acl->can_read(null, 20 /*Customer details*/)) {?>
						<a href="<?php echo site_url('home/ajax_customer_info');?>" class="customer-info pull-right" data-anchor-icon="new-window" data-anchor-position="left">Account details</a>
					<?php }?>
						<div class="clearfix"></div>
				</div>
				<div class="panel-body">
      				<dl class="-col-xxs-12 padding-xxs-0 <?php echo ($this->acl->can_read(null, 14 /*Corporate Sales Contacts*/) ? ' col-xs-6':'');?>">
				    	<dt><span class="glyphicon glyphicon-user"></span> Person in Charge</dt>
				        <dd><ul class="">
				        <?php if ($customer_pics) foreach ($customer_pics as $picItem) 
                					echo '<li class"active"><a href="" class="pic-item" data-staffid="'.$picItem['user_id'].'" data-anchor-icon="new-window" data-anchor-position="right">'. $picItem['name'].'</a></li>';
                				else echo '<dd><em class="gray">PIC are not set!</em><dd>';?>
                				</ul>
                			</dd>
					    </dl>
					    <?php if($this->acl->can_read(null, 14 /*Corporate Sales Contacts*/)) {?>
				    <dl class="-dl-horizontal -col-xxs-12 col-xs-6 padding-xxs-0">
				        <dt><span class="icomoon icon-user-4"></span> Key Account Managers</dt>
			        	<dd><ul class="">
				        	<?php if ($customer_kams) foreach ($customer_kams as $kamItem) 
                					echo '<li><a href="" class="kam-item" data-staffid="'.$kamItem['user_id'].'" data-anchor-icon="new-window" data-anchor-position="right">'. $kamItem['name'].'</a></li>';
                				else echo '<dd><em class="gray">KAM are not set!</em><dd>';?>
	                		</ul>
                		</dd>
					</dl>
					    <?php }?>
				</div><!-- end panel body-->
			</div><!-- end panel customer details -->
			<div class="clearfix"></div>
		</div><!-- end block 1 col customer details -->
		
		<!-- ==========  start blok 2 =================-->	
		<div id="right_sidebar" class="block-2 col-xs-12 col-md-4 pull-right-md">
  			<div class="panel-group right-current-subscriber">
				<div class="panel panel-smart-green">
					<div class="panel-body">
				    	<div class="row text-center agreement-number-holder">
				    		<div class="col-xs-6 col-sm-12">Agreement Number</div>
				    		<div class="col-xs-6 col-sm-12"><strong><?php echo (!empty($current_customer['AgreementId'])? $current_customer['AgreementId']:'N/A');?></strong></div>
				    		<div class="col-xs-6 col-sm-12">Group Account Status</div>
				    		<div class="col-xs-6 col-sm-12"><strong><?php echo (!empty($current_customer['CustStatus'])? $current_customer['CustStatus']: 'N/A');?></strong></div>
				    	</div>
					</div>
				</div>
				<div class="panel panel-default text-center balance-holder">
				    <div class="panel-body">
				    	<div class="row text-center">
				    		<?php if ('1787115357' == $current_subscriber['Tariff_Info']['OfferingId']) { /*if current OfferingName is reralted to Smart Employee*/?>
                       		<div class="col-xs-6 col-sm-12">Monthly Allowance</div>
					    	<div class="col-xs-6 col-sm-12"><strong><?php echo $current_subscriber['Balance_info']['TotalCreditAmount'];?> USD</strong></div>
				    		<div class="col-xs-6 col-sm-12">Available Allowance</div>
				    		<div class="col-xs-6 col-sm-12"><strong><?php echo $current_subscriber['Balance_info']['TotalRemainAmount'];?> USD</strong></div>
				            <?php } else {?>
				            <?php if($this->acl->can_read(null, 17 /*Master Bills*/)) {?>
				            <div class="col-xs-6 col-sm-12"><a href="" id="outstanding_bill" data-webcustid="<?php echo $current_customer['WebCustId'];?>" data-billtype="1">Outstanding Bill</a></div>
				    		<div class="col-xs-6 col-sm-12"><a href="" id="outstanding_bill" data-webcustid="<?php echo $current_customer['WebCustId'];?>" data-billtype="1"><strong><?php echo (isset($current_subscriber['Balance_info']['OutStandingAmount'])? $current_subscriber['Balance_info']['OutStandingAmount']: '');?> USD</strong></a></div>
				    		<?php }?>
				    		<div class="col-xs-6 col-sm-12">Total Credit Amount</div>
				    		<div class="col-xs-6 col-sm-12"><strong><?php echo (isset($current_subscriber['Balance_info']['TotalCreditAmount'])? $current_subscriber['Balance_info']['TotalCreditAmount']: 'N/A');?> USD</strong></div>
				    		<div class="col-xs-6 col-sm-12">Available Credit Amount</div>
				    		<div class="col-xs-6 col-sm-12"><strong><?php echo (isset($current_subscriber['Balance_info']['TotalRemainAmount'])? $current_subscriber['Balance_info']['TotalRemainAmount']: 'N/A');?> USD</strong></div>
				    		<!-- p class="hide faze2"><a href="#" class="ot-btn ot-btn-update-balance"><?php echo label('lbl_top_up')?></a></p -->
				            <?php }?>
				    	</div>
				    </div>
				</div>
			</div>
			<div class="clearfix"></div>
		</div><!-- end blok 2  #right_sidebar -->
      		
		<!-- ======== start blok 3 =========== -->
		<div class="block-3 col-xs-12 col-md-8">
			<div id="subs_info" class="panel -panel-default green-smart-gradient">
				<div class="panel-heading"><h4 class=""><i class="glyphicon glyphicon-info-sign"></i> Subscriber details</h4></div>
				<div class="panel-body" style="">
					<div class="row no-gutter">
						<div class="col-xs-6 col-sm-4 col-sm-offset-2 underline-filler-wrapper">
							<span class="underline-filler"><?php echo label('lbl_phone_number');?>:</span>
						</div>
						<div class="col-xs-6">
							<strong><?php if (isset($current_subscriber['Subscriber_Info']['PhoneNo'])) echo preg_replace('/([0-9]{2})([0-9]{3})([0-9]{3})([0-9]*)/', '0$1 $2 $3$4', $current_subscriber['Subscriber_Info']['PhoneNo']);?> </strong>
							<?php if(isset($current_subscriber['Subscriber_Info']['PhoneNo']) && $this->acl->can_write(null, 3 /*ST new*/)):?>
							<a href="<?php echo site_url("servicetickets/newST/?SubjectID=48");?>" title="<?php echo label('lbl_change');?>" class="ot-link-edit-contactnumber-"><?php echo label('lbl_change');?></a>
							<?php endif;?>
						</div>
					</div>
					<div class="row no-gutter">
						<div class="col-xs-6 col-sm-4 col-sm-offset-2 underline-filler-wrapper">
							<span class="underline-filler"><?php echo label('lbl_simid')?>:</span>
						</div>
						<div class="col-xs-6">
							<strong><?php echo  $current_subscriber['Subscriber_Info']['ICCID'];?></strong>
                            <?php if($this->acl->can_write(null, 3 /*ST new*/)):?>
                            <a href="<?php echo base_url('servicetickets/newST/?SubjectID=55');?>" title="<?php echo label('lbl_change');?>"><?php echo label('lbl_change');?></a>
                            <?php endif;?>
						</div>
					</div>
					<div class="row no-gutter">
						<div class="col-xs-6 col-sm-4 col-sm-offset-2 underline-filler-wrapper">
							<span class="underline-filler"><?php echo label('lbl_status')?>:</span>
						</div>
						<div class="col-xs-6">
							<strong><?php if(isset($current_subscriber['Subscriber_Info']['SubStatus'])) echo $current_subscriber['Subscriber_Info']['SubStatus']?></strong>
                            <?php if(false && $this->acl->can_write(null, 3 /*ST new*/)):?>	                                                               
	                        <a href="<?php echo base_url("servicetickets/newST/?SubjectID=24");?>" id="btn_block">Change</a>
	                        <?php endif;?>
						</div>
					</div>
					<?php if(isset($current_subscriber['Subscriber_Info']['EffectiveDate'])){?>
					<div class="row no-gutter">
						<div class="col-xs-6 col-sm-4 col-sm-offset-2 underline-filler-wrapper">
							<span class="underline-filler">Subscription date:</span>
						</div>
						<div class="col-xs-6">
							<strong><?php if($current_subscriber['Subscriber_Info']['EffectiveDate']){
                                		echo date('d-M-Y', strtotime($current_subscriber['Subscriber_Info']['EffectiveDate']));
                                	}?>
                            </strong>
						</div>
					</div>
					<?php }?>
				</div>
			</div><!-- end panel with subscriber information -->
			
			<!-- =========== TARIFF ============== -->
			<div id="subs_tariff" class="panel -panel-default green-smart-gradient">
				<div class="panel-heading">
					<h4 class="pull-left">
						<i class="glyphicon glyphicon-info-sign"></i> <?php echo label('lbl_tariff');?> 
						<a href="" class="ot-links-big" id="current_sub_pp" data-webofferid="2"><?php echo $current_subscriber['Tariff_Info']['OfferingName'];?></a>
					</h4>
					<button class="navbar-toggle btn-sm-visible -collapsed" type="button" data-button-label="Find a plan" data-toggle="collapse" data-target="#tariff_holder">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<div class="clearfix"></div>
				</div>
				<div class="panel-body">
				
					<!-- TARIFF PLAN LIST -->
					<div id="tariff_holder" class="row tariff-holder collapse out">
					
						<ul id="tariff_plans_tabs" class="nav nav-tabs">
							<?php foreach ($offer_plans as $k=>$ppItem):?>
			                <li class="bg-<?php echo $ppItem['css_class'];?> tabs-value<?php echo ($k==0?' active':'');?>"><a href="#offer_group_<?php echo $ppItem['group_id']?>" data-toggle="tab"><?php echo $ppItem['group_name']?></a></li>
			                <?php endforeach;?>
					    </ul>
					    
					    <div id="myTabContent" class="tab-content">
					    	<?php foreach ($offer_plans as $k=>$ppItem) {?>
					        <div class="tab-pane fade <?php echo ($k==0?'in active':'');?>" id="offer_group_<?php echo $ppItem['group_id']?>">
					        	<?php $GroupsOffers = $this->Offer_model->getGroupsOffers($ppItem['group_id']);?>
					        	<?php foreach($GroupsOffers as $offItem) {?>
					            <div class="col-sm-3">
									<div class="panel panel-default -panel-success tariff-plan<?php echo ((isset($current_subscriber['Tariff_Info']['OfferingId']) && $offItem['offering_id'] == $current_subscriber['Tariff_Info']['OfferingId'])? ' active':'');?>">
										<div class="panel-heading">
											<h4 class="-text-center">
												<a href="" class="offer-name" data-webofferid="<?php echo $offItem['web_offer_id'];?>"><?php echo $offItem['web_name'];?></a>
												<small class="block"><?php echo label('lbl_traffic_plan');?></small>
											</h4>
										</div>
										<!-- List group -->
										<ul class="list-group">
										<?php $OfferValues = $this->Offer_model->getOfferValues($offItem['web_offer_id'], '1');
                        					foreach($OfferValues as $valItem) {?>
											<li class="list-group-item">
												<label class="unit-label aria-label"><?php echo $valItem['value_name'];?>:</label>
												<?php if (is_numeric($valItem['amount']) && $valItem['amount'] > 0) :?>
				                            	<div class="unit-description"><strong><?php echo $valItem['amount'];?></strong> <small><?php echo (($valItem['unit'])?$valItem['unit']:'').(($valItem['duration_unit'])?'/'.$valItem['duration_unit']:'');?></small></div>
				                            	<?php elseif (is_numeric($valItem['amount']) && $valItem['amount'] < 0) :?>
				                            	<div class="unit-description"><strong>Unlimited</strong></div>
				                            	<?php elseif (is_numeric($valItem['amount']) && $valItem['amount'] == 0) :?>
				                            	<div class="unit-description"><strong>N/A</strong></div>
				                            	<?php elseif (false == is_numeric($valItem['amount'])) :?>
				                            	<span class="ot-item-description">
					                            	<?php echo $valItem['amount'];?>
					                            	<?php echo (($valItem['unit'])?$valItem['unit']:'').(($valItem['duration_unit'])?'/'.$valItem['duration_unit']:'');?>
				                            	</span>
				                            	<?php endif;?>
											</li>
											<?php }?>
										</ul>
										<div class="panel-footer">
											<?php if (!empty($offItem['offer_cost'])) {?>
											<div class="plan-cost">
												<sup class="currency"><i class="glyphicon glyphicon-usd"></i></sup><?php echo $offItem['offer_cost'];?><sup class="precision">00</sup>
												<span class="frequence">Per month</span>
											</div>
											<?php } ?>
											<?php if (isset($current_subscriber['Tariff_Info']['OfferingId']) && $offItem['offering_id'] == $current_subscriber['Tariff_Info']['OfferingId']) {?>
											<span class="btn btn-sm btn-block btn-success disabled">Change</span>
											<?php } else {?>
											<a class="btn btn-sm btn-block btn-success" href="<?php echo site_url("servicetickets/newST/?SubjectID=37");?>">Change</a>
											<?php }?>
										</div>
									</div>
								</div>
								<?php } //end foreach group offers ?>
					        </div>
					        <?php } //end foreach content offers group ?>
					    </div>
					</div><!-- end Tariff plan list -->
				
					<div class="row no-gutter">
						<div class="col-xs-6 col-sm-4 col-sm-offset-2 underline-filler-wrapper">
							<span class="underline-filler"><?php echo label('lbl_renewal_date')?>:</span>
						</div>
						<div class="col-xs-6">
							<strong><?php if (isset($current_subscriber['Tariff_Info']['EffectiveDate'])) echo date('d-M-Y', strtotime($current_subscriber['Tariff_Info']['EffectiveDate']));?> </strong>
						</div>
					</div>
					<?php if (isset($current_subscriber['Tariff_Info']['OfferingId']) && ($OfferDetails = $this->Offer_model->getOfferByOfferId($current_subscriber['Tariff_Info']['OfferingId']))) {?>
					<div class="row no-gutter">
						<div class="col-xs-6 col-sm-4 col-sm-offset-2 underline-filler-wrapper">
							<span class="underline-filler">Monthly fee:</span>
						</div>
						<div class="col-xs-6">
							<strong><?php echo $OfferDetails['offer_cost']?> USD/month</strong>
						</div>
					</div>
					<?php }?>
					<!-- ======= FREE UNIT ======== -->
					<?php 
					if ($current_subscriber['Free_Info']) {
						foreach ($current_subscriber['Free_Info'] as $FreeItemList) {
							
							$frequence = '';
							if (date('Ymd000000', strtotime('+1 day', strtotime($FreeItemList['FreeUnitItemDetail']['EffectiveTime']))) == $FreeItemList['FreeUnitItemDetail']['ExpireTime']) {
								if ($FreeItemList['TotalInitialAmount'] != '999999') {
									$frequence = '/day';
								}
							} else if (date('Ymd000000', strtotime('+1 month', strtotime($FreeItemList['FreeUnitItemDetail']['EffectiveTime']))) == $FreeItemList['FreeUnitItemDetail']['ExpireTime']) {
								if ($FreeItemList['TotalInitialAmount'] != '999999') {
									$frequence = '/month';
								}
							}
							
							$freeUnitLeft = round((1-(($FreeItemList['TotalInitialAmount']-$FreeItemList['TotalUnusedAmount'])/$FreeItemList['TotalInitialAmount']))*100);
						?>
					
					<?php if ($FreeItemList['MeasureUnit'] == '1003') {?>
					<div class="row no-gutter">
						<div class="col-xs-6 col-sm-4 col-sm-offset-2 underline-filler-wrapper">
							<span class="underline-filler"><?php echo $FreeItemList['FreeUnitTypeName'];?>:</span>
						</div>
						<?php if ($FreeItemList['TotalInitialAmount'] == '999999') { ?>
						<div class="col-xs-6"><strong>Unlimited</strong></div>
						<?php } else { ?>
						<div class="col-xs-6"><strong><?php echo round($FreeItemList['TotalInitialAmount'] / 60) ." min".$frequence ;?></strong></div>
						<div class="col-xs-12 col-sm-8 col-sm-offset-2">
							<div class="progress">
								<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $freeUnitLeft;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $freeUnitLeft;?>%;">Left <?php echo round($FreeItemList['TotalUnusedAmount']/60).' min';?></div>
								<div class="progress-bar progress-bar-danger" style="width: <?php echo (100 - $freeUnitLeft);?>%;"><span class="sr-only">10% Complete (danger)</span></div>
							</div>
						</div>
						<?php } ?>
					</div>
					<?php } elseif ($FreeItemList['FreeUnitType'] == "SMS" || strpos(strtolower($FreeItemList['FreeUnitType']), 'sms') !== false) { ?>
					<div class="row no-gutter">
						<div class="col-xs-6 col-sm-4 col-sm-offset-2 underline-filler-wrapper">
							<span class="underline-filler"><?php echo $FreeItemList['FreeUnitTypeName'];?>:</span>
						</div>
						<div class="col-xs-6">
							<strong><?php echo $FreeItemList['TotalInitialAmount'] ." SMS".$frequence; ?></strong>
						</div>
						<div class="col-xs-12 col-sm-8 col-sm-offset-2">
							<div class="progress">
								<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $freeUnitLeft;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $freeUnitLeft;?>%;">Left <?php echo round($FreeItemList['TotalUnusedAmount']).' SMS';?></div>
								<div class="progress-bar progress-bar-danger" style="width: <?php echo (100 - $freeUnitLeft);?>%;"><span class="sr-only">10% Complete (danger)</span></div>
							</div>
						</div>
					</div>
					<?php } elseif (in_array($FreeItemList['MeasureUnit'], array('1108', '1107')) ) { 
						switch ($FreeItemList['MeasureUnit']) {
							case 1107: 
								$total = byte_format($FreeItemList['TotalInitialAmount'] * 1024, 0);
								$left = byte_format($FreeItemList['TotalUnusedAmount'] * 1024, 1); break;
							case 1108: 
								$total = byte_format($FreeItemList['TotalInitialAmount'] * 1024 * 1024, 0);
								$left = byte_format($FreeItemList['TotalUnusedAmount'] * 1024 * 1024, 1); break;
							default: $total = 0; $left = 0;
						}
						
						?>
					<div class="row no-gutter">
						<div class="col-xs-6 col-sm-4 col-sm-offset-2 underline-filler-wrapper">
							<span class="underline-filler"><?php echo $FreeItemList['FreeUnitTypeName'];?>:</span>
						</div>
						<div class="col-xs-6">
							<strong><?php echo $total . $frequence;?></strong>
						</div>
						<div class="col-xs-12 col-sm-8 col-sm-offset-2">
							<div class="progress">
								<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $freeUnitLeft;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $freeUnitLeft;?>%;">Left <?php echo $left;?></div>
								<div class="progress-bar progress-bar-danger" style="width: <?php echo (100 - $freeUnitLeft);?>%;"><span class="sr-only">10% Complete (danger)</span></div>
							</div>
						</div>
					</div>
					<?php } else {  //var_dump($FreeItemList); ?>
					<div class="row no-gutter">
						<div class="col-xs-6 col-sm-4 col-sm-offset-2 underline-filler-wrapper">
							<span class="underline-filler"><?php echo $FreeItemList['FreeUnitTypeName'];?>:</span>
						</div>
						<div class="col-xs-6">
							<strong><?php echo round($FreeItemList['TotalInitialAmount']) . ' ' . $FreeItemList['MeasureUnitName'] . $frequence;?></strong>
						</div>
						<div class="col-xs-12 col-sm-8 col-sm-offset-2">
							<div class="progress">
								<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $freeUnitLeft;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $freeUnitLeft;?>%;">Left <?php echo round($FreeItemList['TotalUnusedAmount']).$frequence;?></div>
								<div class="progress-bar progress-bar-danger" style="width: <?php echo (100 - $freeUnitLeft);?>%;"><span class="sr-only">10% Complete (danger)</span></div>
							</div>
						</div>
					</div>
					<?php }?>
					
					<?php }}?>
				</div>
			</div><!-- end tariff panel -->
			
			<!-- =========== SERVICES ============== -->
			<div id="subs_services" class="panel -panel-default green-smart-gradient">
				<div class="panel-heading">
					<h4 class="pull-left">
						<i class="glyphicon glyphicon-info-sign"></i> <?php echo label('lbl_services');?> 
					</h4>
					<button class="navbar-toggle btn-sm-visible ot-btn-options" type="button" aria-expanded="false" data-button-label="Find a service" data-toggle="collapse" data-target="#services_holder">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<div class="clearfix"></div>
				</div>
				<div class="panel-body">
				
					<!-- SERVICES LIST -->
					<div id="services_holder" class="row services-holder collapse out" aria-expanded="false">
						<ul id="services_tabs" class="nav nav-tabs">
							<?php foreach ($offer_services as $k=>$ssItem):?>
			                <li class="bg-<?php echo $ssItem['css_class'];?> tabs-value<?php echo ($k==0?' active':'');?>"><a href="#offer_group_<?php echo $ssItem['group_id']?>" data-toggle="tab"><?php echo $ssItem['group_name']?></a></li>
			                <?php endforeach;?>
					    </ul>
					    
					    <div id="TabContentServices" class="tab-content">
					    	<?php foreach ($offer_services as $k=>$ssItem) { $GroupsOffers = $this->Offer_model->getGroupsOffers($ssItem['group_id']);?>
					        <div class="tab-pane fade <?php echo ($k==0?'in active':'');?>" id="offer_group_<?php echo $ssItem['group_id']?>">
					        <div class="col-xs-12">
					            <div class="table-responsive">
									<table class="bg-<?php echo $ssItem['css_class'];?> thead-green table table-bordered table-striped">
							            <thead>
							            	<tr><th>Service Name</th>
												<th>Price</th>
												<th>Remark</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
										<?php if ($GroupsOffers) {?>
										<?php foreach ($GroupsOffers as $offItem) :?>
											<tr><td><?php echo $offItem['web_name'];?></td>
												<td><?php echo ($offItem['offer_cost']? $offItem['offer_cost'].' USD': '');?></td>
												<td><?php echo $offItem['remark'];?></td>
												<td><?php if ( array_key_exists( $offItem['offering_id'], $current_subscriber['Service_Info'])) {?>
													<span class="gray"><i class="glyphicon glyphicon-check"></i> Choosed</span>
													<a class="btn-link" href="<?php echo site_url("servicetickets/newST/?SubjectID=34");?>"><i class="icomoon icon-remove-2"></i> Remove</a>
													<?php } else {?>
													<a class="btn-link" href="<?php echo site_url("servicetickets/newST/?SubjectID=33");?>"><i class="glyphicon glyphicon-plus"></i> Choose</a>
													<?php }?>
												</td>
											</tr>
										<?php endforeach;?>
										<?php } else {?>
										<tr><td colspan="4"><p>No record</p></td></tr>
										<?php }?>
										</tbody>
									</table>
								</div>
								</div>
					        </div>
					        <?php } //end foreach content offers group ?>
					    </div>
					</div><!-- end services list -->
				
					<div class="row active-services">
						<?php if (isset($current_subscriber['Service_Info'])) foreach ($current_subscriber['Service_Info'] as $serviceItem) {?>
 						<div class="col-xs-12 col-sm-6 service-item">
							<i class="icomoon icon-checkmark-circle icon-left"></i> <span class="underline-filler"><?php echo $serviceItem;?></span> <i class="icomoon icon-checkmark-circle icon-right hidden-xs"></i>
						</div>                       
						<?php }?>
					</div>
				</div><!-- end panel-body -->
			</div><!-- end services panel -->
			
			<div class="clearfix"></div>
		</div><!-- ======== end blok 3 =========== -->

		<!-- ======== start blok 4 =========== -->
		<div class="block-4 col-xs-12 col-md-4 pull-right-md">
			<div class="row reorder-xs">
				<?php if(isset($this->session->userdata['staff']['companies']) && $this->session->userdata['staff']['companies']) {?>
				<div class="col-sm-12">
					<div id="customer_list_panel" class="panel panel-default">
				  		<div class="panel-heading">
				  			<div data-toggle="collapse" data-parent="#customer_list_panel" data-target="#customer_list_group"><h4>Customer list</h4></div>
				  		</div>
    					<ul id="customer_list_group" class="list-group"><!-- panel-collapse collapse -->
    					<?php foreach ($this->session->userdata['staff']['companies'] as $comID => $comItem) {?>
    						<?php if ($comID == $current_customer['WebCustId']) {?>
						  		<li class="list-group-item list-group-item-success">
                               		<span class="badge"><?php echo (isset($current_customer['Groups_Info']['GroupDetails']['MemberAmount'])? (int)$current_customer['Groups_Info']['GroupDetails']['MemberAmount']: '0');?></span>
							    <?php echo $comItem;?>
							</li>
							<?php } else {?>
					  		<li class="list-group-item" data-webcustid="<?php echo $comID;?>">
							    <span class="badge m-count">0</span>
								<a href="<?php echo site_url('home/?CustId='.$comID)?>"><?php echo $comItem;?></a>
							</li>
							<?php }?>
						<?php }?>
						</ul>
	  				</div>
	  			</div>
		  		<?php }?>
  				<div class="col-sm-12">
		  			<div class="well well-sm"> 
						<ul class="nav nav-stacked" id="sidebar">
			            	<?php if($this->acl->can_read(null, 16 /*Self Bills*/)) {?>
		                  	<li><a href="" class="bills_history_button" data-webcustid="<?php echo $current_customer['WebCustId'];?>" data-billtype="2" data-anchor-icon="new-window" data-anchor-position="right">Bills</a></li>
		                  	<?php }?>
		                  	<?php if($this->acl->can_read(null, 17 /*Master Bills*/)) {?>
		                  	<li><a href="" class="bills_history_button" data-webcustid="<?php echo $current_customer['WebCustId'];?>" data-billtype="3" data-anchor-icon="new-window" data-anchor-position="right">Master Invoiece</a></li>
		                  	<?php }?>
		                  	<?php if($this->acl->can_read(null, 35 /*35 CDR */)) {?>
		                  	<li><a id="cdr_loader" data-webcustid="<?php echo $current_customer['WebCustId'];?>"  href="<?php echo site_url('home/ajax_cdr_details/1')?>" data-anchor-icon="new-window" data-anchor-position="right">CDR</a></li>
		                  	<?php }?>
		                  	<?php if($this->acl->can_read(null, 14 /*Corporate Sales Contacts*/)) {?>
		                  	<li><a id="cust_kam_details" href="" data-anchor-icon="new-window" data-anchor-position="right">Corporate Sales Contacts</a></li>
		                  	<?php }?>
						</ul>
					</div>
  				</div>
  				<div class="col-xs-10 col-xs-offset-1">
		  			<a class="btn btn-success btn-block" href="<?php echo site_url('servicetickets/index')?>">Service Ticket System</a>
	  			</div>
  			</div><!-- end reorder-xs -->
  			<div class="clearfix"></div>
  			<div class="visible-xs-block">&nbsp;</div>
		</div><!-- ======== end blok 4 =========== -->

      	<!-- ======== start blok 5 =========== -->
      	<div class="block-5 col-xs-12 col-md-8">
 			<div class="panel panel-default panel-rel-sim">
					<div class="panel-heading"><h4><i class="icomoon icon-users"></i> List of Phone numbers assigned to the Customer</h4></div>
					<div class="panel-body">
      				<?php if($this->acl->can_read(null, 9 /*Search subscribers*/)) {
							if(isset($current_customer['Groups_Info']) && $current_customer['Groups_Info']){?>
				    		<div class="row">
					    		<div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-6">
					    			<?php echo form_open('/search/', array('id'=>'frm_subs_search','name'=>'frm_subs_search','class'=>'form-horizontal frm_subs_search', 'role'=>'search')); ?>
									<div class="input-group">
										<input type="text" class="form-control" name="searchServiceNumber" placeholder="Phone number" maxlength="11" />
										<div class="input-group-btn">
											<button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span> Search</button>
										</div>
									</div>
									<?php echo form_close(); ?>
								</div>
							</div>
			    			<div class="row">
					            <div id="members_list" class="col-xs-12">
					            	<?php $this->load->view('search/ajax_members', array(
					            			'MemberSubscriberList' => $current_customer['Groups_Info']['MemberSubscriberList'], 
					            			'CurrentServiceNumber' => $current_subscriber['Subscriber_Info']['PhoneNo'],
					            			'MemberAmount' => $current_customer['Groups_Info']['GroupDetails']['MemberAmount'],
					            			'page' => $current_memberpage
					            	));?>
					            </div>
					        </div>
						<?php 
						} //end Groups_Info
					} //end acl
					?>
				</div><!-- end panel body-->
			</div><!-- end panel panel-rel-sim -->
			<div class="clearfix"></div>
		</div><!-- end block 5 -->
		
  	</div><!-- end container row -->
</div><!-- end container -->

<div class="clearfix"></div>
<span id="top-link-block" class="hidden">
    <a href="#top" class="well well-sm"  onclick="$('html,body').animate({scrollTop:0},'slow');return false;">
        <i class="glyphicon glyphicon-chevron-up"></i> <span class="hidden-xs">Back to Top</span>
    </a>
</span><!-- /top-link-block -->

<script type="text/javascript">
$(document).ready(function(e) {

	/*========== member list with paginations =============*/
	$("#members_list").on('click', '.paging-holder ul li a', function(e) {
		e.preventDefault();

// 		console.log($(this).attr("href"));return;
		
		$('#ajax-preloader_member').show();
		//search/ajax_members/3
		$.get($(this).attr("href"),
			{'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}, 
			function(response) {
				$('#ajax-preloader_member').hide();
				$("#members_list").html(response);
				
		}).error(function(x, status, error) {console.log(error);});
	});

	/*======= CDR ==========*/
	$(document).on('click', '#cdr_loader, .paging-holder ul.pagination li.page a, a#submit_cdr', function(e) {
		e.preventDefault();
		if ($(this).attr('id') == 'cdr_loader') {
			generalPopUp.show(true);
		} else {
			generalPopUp.show();
			$('#ajax_modal_loading').show();
		}
		var subwebcustid = $(this).data('webcustid');
		var date_from = $('form#frm_cdr_search input[name="date_from"]').val();
		var date_to = $('form#frm_cdr_search input[name="date_to"]').val();

		$.post($(this).attr("href"),
			{"WebCustId": subwebcustid, "date_from": date_from,"date_to": date_to, '<?php  echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}, 
			function(response) {
				$('#ajax_modal_loading').hide();
				generalPopUp.set('View Traffic details and Charges', response, "lg");
		}).error(function(x, status, error) {});
	});

	/*======= PIC details =============*/
	$(document).on('click', '.pic-item', function(e) {
		e.preventDefault();
		var staff_id = $(this).data('staffid');
		generalPopUp.show(true);
		$.post("<?php echo site_url('home/ajax_pic_details/')?>",
			{"staff_id": staff_id, '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}, 
			function(response) {
				generalPopUp.set('Person in Charge details', response);
		}).error(function(x, status, error) {});
	});

	/*========== KAM details ==============*/
	$(document).on('click', '.kam-item, #cust_kam_details', function(e) {
		e.preventDefault();
		var staffid = $(this).data('staffid');
		generalPopUp.show(true);
		$.post("<?php echo site_url('home/ajax_kam_details/')?>",
			{"user_id": staffid,'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}, 
			function(response) {
				generalPopUp.set('Key Account Manager', response);
		}).error(function(x, status, error) {});
	});
	
	/*======= Customer details =============*/
	$(document).on('click', 'a.customer-info', function(e) {
		e.preventDefault();
		var staff_id = $(this).data('staffid');
		generalPopUp.show(true);
		$.post($(this).attr('href'),
			{'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}, 
			function(response) {
				generalPopUp.set('Customer details', response);
		}).error(function(x, status, error) {});
	});

	/* show detail offer information about selected offer from popup or sub ino block */
	$(document).on('click', '.tariff-plan a.offer-name, #current_sub_pp', function(e) {
		e.preventDefault();
		generalPopUp.show(true);
		var webofferid = $(this).data('webofferid');
		if (!webofferid) {alert('No data');return false;}
		$.get("<?php echo site_url('home/ajax_offer_details/')?>",
			{"webofferid": webofferid, '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}, 
			function(response) {
				generalPopUp.set('Offer details', response);		
		}).error(function(x, status, error) {console.log(error);});
	});

	$(document).on('click', 'a.pp-addition', function(e) {
		e.preventDefault();
		generalPopUp.show(true);
		var groupid = $(this).data('groupid');
		
		$.get("<?php echo site_url('home/ajax_offer_groupdetails/')?>",
			{"groupid": groupid, '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}, 
			function(response) {
				generalPopUp.set('Offer details', response);
		}).error(function(x, status, error) {console.log(error);});
	});
	
	
	$('.close').click(function(e) {
		$(this).closest(".ot-dialog").hide();
	});

	$('[data-toggle="tooltip"]').tooltip(); //{"placement":"top",delay: { show: 400, hide: 200 }}

	<?php if($this->session->userdata('msg')){?>
			bootbox.alert("<?php echo $this->session->userdata('msg');?>",'OK');
			//bootbox.dialog("<?php echo $this->session->userdata('msg')?>", [{"label" : "OK", "class" : "btn-danger btn-mini"}]);
	<?php $this->session->unset_userdata('msg');}?>

		//document.oncontextmenu = document.body.oncontextmenu = function () { return false; }
			/* $('.ot-section a, .ot-user-pay a, .ot-dialog a, .ot-panel-nav a').click(function(e) {
				return false;
			}); */

	/*Get list of customers group counter */
	if ($('ul#customer_list_group').length > 0) {			
		ajax_gmem_counter();
	}
			
	
			
	$(document).on('click', '#outstanding_bill, .bills_history_button', function(e) {
		e.preventDefault();
		var subwebcustid = $(this).data('webcustid');
		var billtype = $(this).data('billtype');
		generalPopUp.show(true);
		$.post("<?php echo site_url('home/ajax_bills_details/')?>",
			{"WebCustId": subwebcustid, billtype: billtype,'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}, 
			function(response) {
				if (billtype == 1) {
					generalPopUp.set('Outstanding Bill', response);
				} else if (billtype == 2) {
					generalPopUp.set('Bill history', response);
				} else if (billtype == 3) {
					generalPopUp.set('Master Invoiece', response);
				}
		}).error(function(x, status, error) {console.log(error);});
	});

	
			
			$('a.direct-url').click(function(e) {
				window.location=$(this).attr('href');
				return true;
			});
			$('a.direct-url-new-window').click(function(e) {
				window.open($(this).attr('href'),'_new');
				return true;
			});
            // ot-dialog-help
			$('.ot-link-feedback').click(function() {
				$('.ot-feedback').show();
			});

			$( ".ot-dialog-CDR a" ).click(function() {
				$('.ot-dialog-CDR').dialog("option", "position", "center");
			});
			$( ".ot-dialog-help a" ).click(function() {
				$( ".ot-feedback" ).hide();
			});
			// ot-dialog-payment-history
			$('.ot-link-payment-list').click(function() {
				var sub_number="<?php if(isset($default_subs['SubscriberNumber'])) echo $default_subs['SubscriberNumber']; else echo ''?>";
				load_tdr(1,1,sub_number);
				$('.ot-dialog-payment-history').show();
			});
			$( ".ot-dialog-payment-history a" ).click(function() {
				$( ".ot-dialog-payment-history" ).hide();
			});
			// ot-dialog-operations-history
			$('.ot-link-operations-history').click(function() {
				$('.ot-dialog-operations-history').show();
			});
			$( ".ot-dialog-operations-history .close" ).click(function() {
				$( ".ot-dialog-operations-history" ).hide();
			}); 
			$('.tdr').change(function(e) {
				var sub_number="<?php if(isset($default_subs['SubscriberNumber'])) echo $default_subs['SubscriberNumber']; else echo ''?>";
                load_tdr($(this).val(),1,sub_number);
            });
			$('.due-detail').click(function(e) {
				var cct = $.cookie('csrf_cookie_name');
			    var top=$(this).position().top;
				var left=$(this).position().left-20;
				$('#due_detail').css('top',top);
				$('#due_detail').css('left',left);
				$.post('<?php echo base_url('home/get_due_detail')?>',{'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}, function(html) {
					$('#due_detail table tbody').html(html);
					$('#due_detail').show();
				});
				e.preventDefault();
            });
			$('#due_detail .ot-dialog-close').click(function(e) {
				$('#due_detail').hide();
			});
			

			$('.ot-btn-update-balance').click(function(e) {
                $('.ot-dialog-topup').show();
				return false;
            });
			//check_current_pp();
			// payment date tooltip
			var t;
			$('.ot-date-cont').hover(function() {
				clearTimeout(t);
				var cct = $.cookie('csrf_cookie_name');
				$.post('<?php echo base_url('home/get_due_detail')?>',{'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}, function(html) {
					$('.ot-tooltip-payment-date table tbody').html(html);
				});
				$('.ot-tooltip-payment-date').show();
			}, function() {
				t = setTimeout(function() {$('.ot-tooltip-payment-date').hide();}, 20);
			});
			
			$('#ot-btn-tariff-1').click(function(e) {
				var cct = $.cookie('csrf_cookie_name');
				var start_date=$('#hd_pdr_from').val();
				var to_date=$('#hd_pdr_to').val();
				var subnumber=$('#pdr_subnumber').val();
				var opt=$('[name="type-operations[]"]:checked').val();
				//alert(opt);
				$.post('<?php echo base_url('home/get_pdr')?>',{'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>',"start_date":start_date,"end_date":to_date,"page_num":"1","sub_number":subnumber}, function(html) {
					$('#pdr_list tbody').html(html);
					//alert(html);
				});
				return false;
			});
			$('#lnk_readmore').click(function(e) {
				$('#ot_news_detail').show();
			});
			$('.close').click(function(e) {
				$(this).closest(".ot-dialog").hide();
			});
			$('.coperate-subscriber').change(function(e) {
				if($(this).val()==-1) return;
				var cct = $.cookie('csrf_cookie_name');
                //alert($(this).val());
				$.post('<?php echo base_url('home/customer_selection')?>',{'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>',"cust_number":$(this).val()}, function(data) {
					if(data){
						location.reload(true);
					}
				},'json');
            });

			/*----- smart functions -------*/

//			$('[data-toggle="tooltip"]').tooltip(); //{"placement":"top",delay: { show: 400, hide: 200 }}

			<?php if($this->session->userdata('msg')){?>
					bootbox.alert("<?php echo $this->session->userdata('msg');?>",'OK');
					//bootbox.dialog("<?php echo $this->session->userdata('msg')?>", [{"label" : "OK", "class" : "btn-danger btn-mini"}]);
			<?php $this->session->unset_userdata('msg');}?>
			
        }); //end ready()
        
		
		function check_current_pp(){
			var cost=$('#current_pp_cost').text();
			if (cost.indexOf("-") >= 0){
				$('#current_pp_cost').parent().addClass('remak');
			}
		}

function ajax_gmem_counter()
{
	var cct = $.cookie('csrf_cookie_name');
	$.get('<?php echo base_url('home/ajax_gmem_counter')?>',{'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}, function(response) {
		$.each(response, function (key, data) {
			$('ul#customer_list_group li[data-webcustid="'+ key +'"] .m-count').html(data);
		});
	},"json");
}
</script>

<?php $this->view('layout/footer_area');?>