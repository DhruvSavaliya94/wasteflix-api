<?php 
 
	require_once '../config/database.php';
	
	$response = array();
	
	if(isset($_GET['apicall'])){
		
		switch($_GET['apicall']){
			
			case 'register':
				if(isTheseParametersAvailable(array('uname','ucontact','uemail','urole','upass'))){
					$uname = $_POST['uname']; 
					$ucontact = $_POST['ucontact']; 
					$uemail = $_POST['uemail'];
					$urole = $_POST['urole'];
					$upass = $_POST['upass'];
					$stmt = $conn->prepare("SELECT uid FROM users WHERE email = ? OR contact = ?");
					$stmt->bind_param("ss", $uemail,$ucontact);
					$stmt->execute();
					$stmt->store_result();
					
					if($stmt->num_rows > 0){
						$response['error'] = true;
						$response['message'] = 'User already registered or change the details.';
						$stmt->close();
					}else{
						$stmt = $conn->prepare("INSERT INTO users (`name`, `contact`, `email`, `urole`, `password`) VALUES (?, ?, ?, ?, ?)");
						$stmt->bind_param("sssss", $uname, $ucontact, $uemail, $urole, $upass);
						if($stmt->execute()){
							$stmt = $conn->prepare("SELECT `uid`, `name`, `contact`, `email`, `urole`, `password` FROM users WHERE email = ? and contact = ?"); 
							$stmt->bind_param("ss",$uemail,$ucontact);
							$stmt->execute();
							$stmt->bind_result($uid, $name, $contact, $email, $urole,$upass);
							$stmt->fetch();
							
							$user = array(
								'uid'=>$uid, 
								'name'=>$name, 
								'contact'=>$contact,
								'email'=>$email,
								'urole'=>$urole,
							);
							
							$stmt->close();
							
							$response['error'] = false; 
							$response['message'] = 'User registered successfully'; 
							$response['user'] = $user; 
						}
					}
					
				}else{
					$response['error'] = true; 
					$response['message'] = 'required parameters are not available'; 
				}
				
            break;
            
            case 'login':
				if(isTheseParametersAvailable(array('uemail','upassword'))){
                    $uemail = $_POST['uemail']; 
                    $upassword = $_POST['upassword']; 
                    $stmt = $conn->prepare("SELECT uid,name,contact,email,urole FROM users WHERE email = ? AND password = ?");
                    $stmt->bind_param("ss",$uemail,$upassword);
                    $stmt->execute();
                    $stmt->bind_result($uid,$name,$contact,$email,$urole);
                    $stmt->fetch();
                    if($uid!=null){
						$user = array(
							'uid'=>$uid,
							'user_name'=>$name,
							'user_contact'=>$contact,
							'user_email'=>$email,
							'user_role' =>$urole						
						);
						
						$stmt->close();
						
						$response['error'] = false; 
						$response['message'] = 'User login successfully'; 
						$response['user'] = $user; 
					}
					else{
						$stmt->close();
						
						$response['error'] = true; 
						$response['message'] = 'Invalid User'; 
					}
                    
                }else{
					$response['error'] = true; 
					$response['message'] = 'required parameters are not available'; 
                }
            break;
			case 'getAllCategory':
				$stmt = $conn->prepare("SELECT * FROM `category`"); 
				$stmt->execute();
				$stmt->bind_result($cid, $name, $price);
				$cat = array();
				while($stmt->fetch()){
					$tmp = array(
						'cid'=>$cid, 
						'name'=>$name, 
						'price'=>$price,
					);
					array_push($cat,$tmp);
				}
							
				$stmt->close();
				$response['AllCategory'] = $cat;
			break;

			case 'addReq':
				
				if(isTheseParametersAvailable(array('wuid','wdisc','wcategory','wcity','wdate','wqnt','status'))){
					$wuid = $_POST['wuid']; 
					$wdisc = $_POST['wdisc']; 
					$wcategory = $_POST['wcategory'];
					$wcity = $_POST['wcity'];
					$wdate = $_POST['wdate'];
					$wqnt = $_POST['wqnt'];
					$status = $_POST['status'];

					$stmt = $conn->prepare("INSERT INTO request (`uid`, `description`, `category`, `city`, `date`, `qnty`, `status`) VALUES (?, ?, ?, ?, ?, ?, ?)");
						$stmt->bind_param("sssssss", $wuid, $wdisc, $wcategory, $wcity, $wdate, $wqnt, $status);
						if($stmt->execute()){
														
							$stmt->close();
							
							$response['error'] = false; 
							$response['message'] = 'Pickup request successfully'; 
						}
					
				}else{
					$response['error'] = true; 
					$response['message'] = 'required parameters are not available'; 
				}
			break;
			case 'getRequest':
				$uid=$_GET['uid'];
				$stmt = $conn->prepare("SELECT `rid`, `uid`, `description`, c.`name`, `city`, `date`, `qnty`, `status` FROM request as r,category as c WHERE r.category=c.cid and r.uid=".$uid." and r.status='Submitted.'"); 
				$stmt->execute();
				$stmt->bind_result($rid, $uid, $description, $name,$city, $date,$qnty,$status);
				$pro = array();
				while($stmt->fetch()){
					$tmp = array(
						'rid'=>$rid, 
						'uid'=>$uid, 
						'description'=>$description,
						'name'=>$name,
						'city'=>$city,
						'date'=>$date,
						'qnty'=>$qnty,
						'status'=>$status,

					);
					array_push($pro,$tmp);
				}
				if($pro==null){
					$response['Requests'] = $pro;
					$response['error'] = true; 
					$response['message'] = 'No item found';
				}else{
					$stmt->close();
					$response['Requests'] = $pro;
				}			
				
			break;
			case 'getAllSRequest':
				$stmt = $conn->prepare("SELECT `rid`, `uid`, `description`, c.`name`, `city`, `date`, `qnty`, `status` FROM request as r,category as c WHERE r.category=c.cid and r.status='Submitted.'"); 
				$stmt->execute();
				$stmt->bind_result($rid, $uid, $description, $name,$city, $date,$qnty,$status);
				$pro = array();
				while($stmt->fetch()){
					$tmp = array(
						'rid'=>$rid, 
						'uid'=>$uid, 
						'description'=>$description,
						'name'=>$name,
						'city'=>$city,
						'date'=>$date,
						'qnty'=>$qnty,
						'status'=>$status,

					);
					array_push($pro,$tmp);
				}
				if($pro==null){
					$response['Requests'] = $pro;
					$response['error'] = true; 
					$response['message'] = 'No item found';
				}else{
					$stmt->close();
					$response['Requests'] = $pro;
				}			
				
			break;
			case 'getAllRequest':
				$stmt = $conn->prepare("SELECT `rid`, `uid`, `description`, c.`name`, `city`, `date`, `qnty`, `status` FROM request as r,category as c WHERE r.category=c.cid"); 
				$stmt->execute();
				$stmt->bind_result($rid, $uid, $description, $name,$city, $date,$qnty,$status);
				$pro = array();
				while($stmt->fetch()){
					$tmp = array(
						'rid'=>$rid, 
						'uid'=>$uid, 
						'description'=>$description,
						'name'=>$name,
						'city'=>$city,
						'date'=>$date,
						'qnty'=>$qnty,
						'status'=>$status,

					);
					array_push($pro,$tmp);
				}
				if($pro==null){
					$response['Requests'] = $pro;
					$response['error'] = true; 
					$response['message'] = 'No item found';
				}else{
					$stmt->close();
					$response['Requests'] = $pro;
				}			
				
			break;
			case 'updateStatus':
					$rid = $_GET['rid'];
					$status = $_GET['status'];

					$stmt = $conn->prepare("UPDATE `request` SET `status`=? WHERE `rid`=?");
					$stmt->bind_param("ss",$status,$rid);
					if($stmt->execute()){
						$stmt = $conn->prepare("SELECT re.rid,re.uid,c.price,re.qnty from request as re,category as c WHERE c.cid=re.category AND re.rid=".$rid);
						$stmt->execute();
						$stmt->bind_result($rid, $uid, $price, $qnty);
						$pro = array();
						while($stmt->fetch()){
							$tmp = array(
								'rid'=>$rid, 
								'uid'=>$uid, 
								'price'=>$price,
								'qnty'=>$qnty		
							);
							array_push($pro,$tmp);
						}
						$stmt->close();						
						$response['error'] = false; 
						$response['message'] = 'Change status successfully';
						$response['requestId']=$rid;
						$response['requestDetails']=$pro; 
					}					
			break;

			case 'creditRewards':

				if(isTheseParametersAvailable(array('rid','uid','name','partner','vouc_code','offer'))){
					$rid = $_POST['rid']; 
					$uid = $_POST['uid']; 
					$name = $_POST['name'];
					$partner = $_POST['partner'];
					$vouc_code = $_POST['vouc_code'];
					$offer = $_POST['offer'];

					$stmt = $conn->prepare("INSERT INTO `rewards`(`rid`, `uid`, `name`, `partner`, `vouc_code`, `offer`) VALUES (?,?,?,?,?,?)");
					$stmt->bind_param("ssssss", $rid,$uid,$name,$partner,$vouc_code,$offer);
					if($stmt->execute()){
						$stmt->close();					
						$response['error'] = false; 
						$response['message'] = 'Rewards Credited'; 
					}
										
				}else{
					$response['error'] = true; 
					$response['message'] = 'required parameters are not available.'; 
				}
			break;
			case 'getRewards':
				$uid=$_GET['uid'];
				$stmt = $conn->prepare("SELECT re.`reid`, re.`rid`, us.`name`, re.`name`, re.`partner`, re.`vouc_code`, re.`offer` FROM `rewards` as re,`users` as us WHERE re.uid=us.uid"); 
				$stmt->execute();
				$stmt->bind_result($reid, $rid, $uname, $rname, $partner, $vouc_code, $offer);
				$pro = array();
				while($stmt->fetch()){
					$tmp = array(
						'reid'=>$reid, 
						'rid'=>$rid, 
						'uname'=>$uname,
						'rname'=>$rname,
						'partner'=>$partner,
						'vouc_code'=>$vouc_code,
						'offer'=>$offer,
					);
					array_push($pro,$tmp);
				}
				if($pro==null){
					$response['Requests'] = $pro;
					$response['error'] = true; 
					$response['message'] = 'No item found';
				}else{
					$stmt->close();
					$response['Requests'] = $pro;
				}			
				
			break;

			case 'getAllRewards':
				$stmt = $conn->prepare("SELECT re.`reid`, re.`rid`, us.`name`, re.`name`, re.`partner`, re.`vouc_code`, re.`offer` FROM `rewards` as re,`users` as us WHERE re.uid=us.uid"); 
				$stmt->execute();
				$stmt->bind_result($reid, $rid, $uname, $rname, $partner, $vouc_code, $offer);
				$pro = array();
				while($stmt->fetch()){
					$tmp = array(
						'reid'=>$reid, 
						'rid'=>$rid, 
						'uname'=>$uname,
						'rname'=>$rname,
						'partner'=>$partner,
						'vouc_code'=>$vouc_code,
						'offer'=>$offer,
					);
					array_push($pro,$tmp);
				}
				if($pro==null){
					$response['Requests'] = $pro;
					$response['error'] = true; 
					$response['message'] = 'No item found';
				}else{
					$stmt->close();
					$response['Requests'] = $pro;
				}		
				
			break;		
			case 'getRequestHistory':
				$uid=$_GET['uid'];
				$stmt = $conn->prepare("SELECT `rid`, `uid`, `description`, c.`name`, `city`, `date`, `qnty`, `status` FROM request as r,category as c WHERE r.category=c.cid and r.uid=".$uid." and r.status!='Submitted.'"); 
				$stmt->execute();
				$stmt->bind_result($rid, $uid, $description, $name,$city, $date,$qnty,$status);
				$pro = array();
				while($stmt->fetch()){
					$tmp = array(
						'rid'=>$rid, 
						'uid'=>$uid, 
						'description'=>$description,
						'name'=>$name,
						'city'=>$city,
						'date'=>$date,
						'qnty'=>$qnty,
						'status'=>$status,

					);
					array_push($pro,$tmp);
				}
				if($pro==null){
					$response['Requests'] = $pro;
					$response['error'] = true; 
					$response['message'] = 'No item found';
				}else{
					$stmt->close();
					$response['Requests'] = $pro;
				}			
				
			break;

			default: 
				$response['error'] = true; 
				$response['message'] = 'Invalid Operation Called';
		}
		
	}else{
		$response['error'] = true; 
		$response['message'] = 'Invalid API Call';
	}
	
	echo json_encode($response);
	
	function isTheseParametersAvailable($params){
		
		foreach($params as $param){
			if(!isset($_POST[$param])){
				return false; 
			}
		}
		return true; 
	}
?>