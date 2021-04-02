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
                    $stmt = $conn->prepare("SELECT uid,name,contact,email FROM users WHERE email = ? AND password = ?");
                    $stmt->bind_param("ss",$uemail,$upassword);
                    $stmt->execute();
                    $stmt->bind_result($uid,$name,$contact,$email);
                    $stmt->fetch();
                    
                    $user = array(
                        'uid'=>$uid,
                        'user_name'=>$name,
                        'user_email'=>$contact,
                        'user_contact'=>$email,						
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