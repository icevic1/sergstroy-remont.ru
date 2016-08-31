<link href="<?php echo base_url('public/css/chat/chat.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('public/css/chat/screen.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('public/css/chat/screen_ie.css'); ?>" rel="stylesheet">
<style type="text/css">
	.customer-list{
		position:absolute;
		height:100%;
		right:0;
		border:1px solid #CCC;
		width:200px;
	}
	.customer-list .header{
		background-color:#ACC9FD;
		padding:10px 0;
		text-align:center;
		font-weight:bold;
		color:#ffffff;
	}
	.list{
		overflow-y:auto;
		height:600px;
	}
	.list ul{
		list-style:none;
		padding:0;
		margin:0;
	}
	.list ul li{
		padding:10px;
		border-bottom:1px dashed #CCCCCC;
	}
</style>
<div class="customer-list">
	<div class="header">Customers</div>
    <div class="list">
    	<input type="hidden" id="h_chat_id" value="<?php if(isset($chat_user['chat_id'])) echo $chat_user['chat_id']?>"/>
    	<ul>
        	<?php foreach($chat as $c):?>
            	<?php
                	$img="";
					if($c->is_online==1){
						$img=base_url('public/img/online.png');
					}else{
						$img=base_url('public/img/offline.png');
					}
				?>
            	<li>
                	<a href="javascript:void(0)" onclick="javascript:chatWith('<?php echo $c->chat_id;?>','<?php echo $c->chat_name;?>')"><?php echo $c->chat_name?><span style="padding-left:10px"><img src="<?php echo $img?>" border="0"/></span></a>
                </li>
            <?php endforeach?>
        </ul>
    </div>
</div>
<div>
	<div style="padding:10px">
       <a href="<?php echo base_url('admin/home')?>"><img src="<?php echo base_url('public/images/logo.png')?>" border="0"/></a>
    </div>
</div>
<script src="<?php echo base_url('public/js/jquery.cookie.js'); ?>"></script>
<script src="<?php echo base_url('public/js/chat.js')?>" type="text/javascript"></script>

<script type="text/javascript">
	$(document).ready(function(e) {
		//==============CHAT BLOCK==================//
		base_url='<?php echo base_url()?>';
		user1=$('#h_chat_id').val();
		
		originalTitle = document.title;
		startChatSession();
	
		$([window, document]).blur(function(){
			windowFocus = false;
		}).focus(function(){
			windowFocus = true;
			document.title = originalTitle;
		});
		//=============END CHAT BLOCK==================//
    });
</script>