<?php
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');
    $request=$_POST;
    // password salt 1salt1
    function checkReq($request){
        foreach($request as $data){
            if(!$data || $data==""){
                return false;
            }
        }
        return true;
    }

    function checkPassword($password){
        $len=strlen($password);

        if($len<6){
            return false;
        }
        return true;
    }

    function trim_input_value($data) {
        $data = trim($data);
        $data = stripslashes($data);
        return $data;
    }
    
    

    if (checkReq($_POST)) {
        $business_details=[];

        $business_details["name"]=trim_input_value($_POST["name"]);
        $business_details["email"]=trim_input_value($_POST["email"]);
        $business_details["username"]=trim_input_value($_POST["username"]);
        $business_details["password"]=trim_input_value($_POST["password"]);
        
        if (!checkReq($business_details)) {
            $msg="Please fill all the details correctly!";
            mysqli_stmt_close($sql);
            mysqli_close($conn); 

            http_response_code(500);
            echo json_encode($msg);
            exit;
        }

        if (!checkPassword($business_details["password"])) {
            $msg="Password must be of 6 digits !";
            mysqli_stmt_close($sql);
                mysqli_close($conn); 

                http_response_code(500);
                echo json_encode($msg);
            exit;
        }



        include('./config.php');
            
            $stmt="INSERT INTO `users` (name,email,username,password,verification_code,created_at) VALUES (?,?,?,?,?,?)";
            $sql=mysqli_prepare($conn, $stmt);
        
            //binding the parameters to prepard statement
            mysqli_stmt_bind_param($sql,"ssssis",$business_details["name"],$business_details["email"],$business_details["username"],$pass,$code,$created_at);
            $pasw_with_salt=$business_details["password"]."1salt1";
            $pass=password_hash($pasw_with_salt, PASSWORD_DEFAULT);
            $digits=4;
            $code=rand(pow(10, $digits-1), pow(10, $digits)-1);
            $created_at = date('Y-m-d H:i:s'); 

            if($code==null || $code==0){
                $code=1021;
            }
            $result=mysqli_stmt_execute($sql);
            if (!$result){ 
             
                $data = "Something went wrong.";
                mysqli_stmt_close($sql);
                mysqli_close($conn); 

                http_response_code(500);
                echo json_encode($data);
                exit;
            }


            $message="Successfully account created";
            mysqli_stmt_close($sql); 
            mysqli_close($conn); 
            http_response_code(200);
            echo json_encode($message);
            exit;
        
    } 
    else {
        $data = "Please fill all the details.";
            
                http_response_code(500);
                echo json_encode($data);
        exit;
    }
?>