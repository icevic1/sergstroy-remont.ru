<div class="box">
	<div class="box-header well" data-original-title>
    	<h2><i class="cus-house"></i>Dashboard</h2>
    </div>
    <div class="box-content">
    	<div class="row-fluid">		
            <div class="span12">
                <?php
                    $tt=sizeof($menus_page);
                    $num_col=6;
                    $mod=$tt%$num_col;
                    $trow=intval($tt/$num_col);
                    $mrow=$mod>0?1:0;
                    $r=1;
                    $scol=0;
                    while($r<=($trow+$mrow)){
                        echo '<div class="sortable row-fluid thumnail">';
                        for($j=$scol;$j<$scol+$num_col;$j++){
                            if($j<$tt){
                                ?>
                                 <a data-rel="tooltip" title="<?php echo $menus_page[$j]->page_name?>" class="well span2" href="<?php echo base_url($menus_page[$j]->page_url); ?>">
                                    <i class="icon-32 <?php echo $menus_page[$j]->page_icon?>"></i><br/>
                                    <div><?php echo $menus_page[$j]->page_name?></div>
                                </a>                                                
                                <?php
                            }
                        }				
                        echo '</div>';
                        $scol+=6;
                        $r++;
                    }			
                ?>
            </div>
        </div>
    </div>
</div>