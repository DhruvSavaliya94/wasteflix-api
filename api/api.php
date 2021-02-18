<?php 
 
	require_once '../config/database.php';
	
	$response = array();
	
	if(isset($_GET['apicall'])){
		
		switch($_GET['apicall']){
			
			case 'register':
				if(isTheseParametersAvailable(array('uname','ucontact','uemail','udob','urole'))){
					$uname = $_POST['uname']; 
					$ucontact = $_POST['ucontact']; 
					$uemail = $_POST['uemail'];
                    $udob = $_POST['udob'];
					$urole = $_POST['urole'];
					$stmt = $conn->prepare("SELECT uid FROM users WHERE name = ? OR contact = ?");
					$stmt->bind_param("ss", $uname,$ucontact);
					$stmt->execute();
					$stmt->store_result();
					
					if($stmt->num_rows > 0){
						$response['error'] = true;
						$response['message'] = 'User already registered or change the number.';
						$stmt->close();
					}else{
						$stmt = $conn->prepare("INSERT INTO users (`name`, `contact`, `email`, `dob`, `urole`) VALUES (?, ?, ?, ?, ?)");
						$stmt->bind_param("sssss", $uname, $ucontact, $uemail, $udob, $urole);
 
						if($stmt->execute()){
							$stmt = $conn->prepare("SELECT `uid`, `name`, `contact`, `email`, `dob`, `urole` FROM users WHERE name = ?"); 
							$stmt->bind_param("s",$uname);
							$stmt->execute();
							$stmt->bind_result($uid, $name, $contact, $email, $dob, $urole);
							$stmt->fetch();
							
							$user = array(
								'uid'=>$uid, 
								'name'=>$name, 
								'contact'=>$contact,
								'email'=>$email,
                                'dob'=>$dob,
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
				if(isTheseParametersAvailable(array('uid','password'))){
                    $uid = $_POST['uid']; 
                    $password = $_POST['password']; 
                    $stmt = $conn->prepare("SELECT uid FROM credentials WHERE uid = ? AND password = ?");
                    $stmt->bind_param("ss",$uid,$password);
                    $stmt->execute();
                    $stmt->bind_result($uid);
                    $stmt->fetch();
                    
                    $user = array(
                        'uid'=>$uid,
                    );
                    
                    $stmt->close();
                    
                    $response['error'] = false; 
                    $response['message'] = 'User login successfully'; 
                    $response['user'] = $user; 
                }else{
					$response['error'] = true; 
					$response['message'] = 'required parameters are not available'; 
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