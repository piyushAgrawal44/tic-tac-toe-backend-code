<?php
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');
    
    function checkReq($request){
        foreach($request as $data){
            if(!$data || $data==""){
                return false;
            }
        }
        return true;
    }
    function trim_input_value($data) {
        $data = trim($data);
        $data = stripslashes($data);
        return $data;
    }
    
    

    if (checkReq($_POST)) {
        $user_details=[];

        $user_details["email"]=trim_input_value($_POST["email"]);
        $user_details["username"]=trim_input_value($_POST["username"]);

        
        if (!checkReq($user_details)) {
            $msg="Please fill all the details correctly!";
                http_response_code(500);
                echo json_encode($msg);
            exit;
        }

        include('./config.php');

        $stmt="SELECT id FROM `users` WHERE email=(?) and deleted_at IS NULL";
        $sql=mysqli_prepare($conn, $stmt);

        //binding the parameters to prepard statement
        mysqli_stmt_bind_param($sql,"s",$user_details["email"]);
        $result=mysqli_stmt_execute($sql);
        $data= mysqli_stmt_store_result($sql);
        $no_of_row=mysqli_stmt_num_rows($sql);
		
        if ($no_of_row>0){
            $email_registered=true;
        }
        else{
            $email_registered=false;
        }
            
        mysqli_stmt_close($sql);
        $stmt="SELECT id FROM `users` WHERE username=(?) and deleted_at IS NULL";
        $sql=mysqli_prepare($conn, $stmt);

        //binding the parameters to prepard statement
        mysqli_stmt_bind_param($sql,"s",$user_details["username"]);
        $result=mysqli_stmt_execute($sql);
        $data= mysqli_stmt_store_result($sql);
        $no_of_row=mysqli_stmt_num_rows($sql);
		
        if ($no_of_row>0){
            $username_registered=true;
        }
        else{
            $username_registered=false;
        }
        $message=[
            "email_registered"=>$email_registered,
            "username_registered"=>$username_registered
        ];  
        mysqli_stmt_close($sql); 
        mysqli_close($conn); 
        http_response_code(200);
        echo json_encode($message);
    } 
    else {
        $data = "Please fill email id and username";
        http_response_code(500);
        echo json_encode($data);
        exit;
    }
?>