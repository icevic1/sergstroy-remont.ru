<?php
	require_once('nusoap.php');
	header('Content-type: text/html; charset=utf-8');
	//=============RETRIEVE DATA FROM OTHER SERVER=============//
	/*$conn1=mysql_connect("localhost","root","");
	$conn2=mysqli_connect("localhost","root","","selfcare");
	mysql_set_charset('utf8',$conn1);
	mysqli_set_charset($conn2, "utf8");
	$log.='';
	if($conn1){
		$log.='con_oracle=true,';
		mysql_select_db("ws_server_test",$conn1);
		//CHECK TABLE CONTROL
		$qry11="SELECT * FROM ot_control";
		$res11=mysql_query($qry11,$conn1);
		while($row11=mysql_fetch_assoc($res11)){
			$label1='['.$row11['rec_date'].','.$row11['rec_status'].']';
			//==============LOAD OR RELOAD TABLE TRAFIC========//
			if(strtolower($row11['rec_status'])=='new' || strtolower($row11['rec_status'])=='reload'){
				$values='';
				$res12=mysql_query("SELECT * FROM ot_traffic WHERE rec_date='".$row11['rec_date']."'",$conn1);
				if($res12){
					while($row12 = mysql_fetch_assoc($res12)){
						$values.="(''".$row12['SubsNumber']."'',''".$row12['TrafficDate']."'',''".$row12['Outgoing']."'',''".$row12['Incoming']."'',''".$row12['Total']."''),";
					}
					$values=rtrim($values,',');
					if($values){
						if($conn2){
							$log.="con_selfcare=true,";
							$qry21="CALL sp_backup_subscriber_tafic ('".$row11['rec_date']."','".$row11['rec_status']."',' VALUES ".$values."')";
							$res21=mysqli_query($conn2,$qry21);
							if($res21){
								$log.=$label1." save success,";
								$qry13="UPDATE ot_control SET rec_status='uploaded' WHERE rec_date='".$row11['rec_date']."'";
								if(mysql_query($qry13,$conn1)){
									$log.=$label1." update status success,";
								}else{
									$log.=$label1." update status fail.,";
								}
							}else{
								$log.=$label1." save fail,";
							}
						}else{
							$log.="con_selfcare=false,";
						}
					}
				}else{
					$log.=$label1.' no trafic records,';
				}
			}
			//==================END LOAD DATA=============//
		}
	}else{
		$log.='con_oracle=false,';
	}
	$log=rtrim($log,',');
	$qry22="INSERT INTO sc_download_log VALUES(NOW(),'".$log."')";
	mysqli_query($conn2,$qry22);
	mysql_close($conn1);
	mysqli_close($conn2);*/
	//if($_POST['usr']=='0$n0v@' && $_POST['pwd']=='0$n0v@'){
		//$conn2=mysqli_connect("localhost","selfcare","$e1fc@re","selfcare");
		$conn2=mysqli_connect("localhost","root","","selfcare");
		mysqli_set_charset($conn2, "utf8");
		//$qry22="INSERT INTO sc_download_log VALUES(NOW(),'test')";
		$qry1="CALL sp_backup_traffic(NOW(),'New','VALUES(''".date('Y-m-d')."'',''0000006'',1,1,1,1,1,1,1,1)')";
		mysqli_query($conn2,$qry1);
		$qry2="CALL sp_backup_recommendation(NOW(),'New','VALUES(''".date('Y-m-d')."'',''0000006'','''',1,''<a href=\"#\">Recommendation 1</a>'',1,'''',0,'''')')";
		mysqli_query($conn2,$qry2);
		mysqli_close($conn2);
		echo 'Done';
	//}
	
?>