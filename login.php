<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 86400');

include("./config.php");
function trim_input_value($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
if (isset($_POST["username"]) && !empty($_POST["username"]) && isset($_POST["password"]) && !empty($_POST["password"])) {
    
            
    $stmt="SELECT id,name,password FROM `users` WHERE username=(?) AND deleted_at IS NULL LIMIT 1";
    $sql=mysqli_prepare($conn, $stmt);
    mysqli_stmt_bind_param($sql,"s",$username);
    
    $username=trim_input_value($_POST['username']);

    $password=trim($_POST["password"]);
  
    $result=mysqli_stmt_execute($sql);

    if(!$result){
        $data = "Something went wrong.";
        mysqli_stmt_close($sql);
        mysqli_close($conn); 

        http_response_code(500);
        echo json_encode($data);
        exit;
    }

    $data= mysqli_stmt_get_result($sql);
    if ($data->num_rows <= 0){
        $data = "Wrong Email id or Password";
        mysqli_stmt_close($sql);
        mysqli_close($conn); 

        http_response_code(500);
        echo json_encode($data);
        exit;
    }


    $row=mysqli_fetch_array($data);

    $user_id=$row['id'];
    $password=$password."1salt1";

    if (!password_verify($password, $row['password'])) 
    {
        $data = "Wrong Email id or Password";
        mysqli_stmt_close($sql);
        mysqli_close($conn); 

        http_response_code(500);
        echo json_encode($data);
        exit;
    }

    // if($row['user_block'] == 1){
    //     echo "<script>alert('Account is deactivated !! Please contact +91 0000000000 to activate your account.');
    //     window.location.href='../../frontend/login.php';
    //     </script>";
    //     exit;
    // }

    // if ($row["verified"]==0) {
       

        
    //     echo "<script>
    //                 window.location.href='../../frontend/verify_account.php';
    //         </script>";
    //     exit;
    // }

    mysqli_stmt_close($sql);
    mysqli_close($conn);
    $logged=true;   

    $data=[
        "user_id"=>$row["id"],
        "username"=>$username,
        "message"=>"Successfully account created",
        "name"=>$row["name"],
        "status"=>true
    ];
    http_response_code(200);
    echo json_encode($data);
    exit;
} 
else {


    $data = "Please enter all details";
    mysqli_stmt_close($sql);
    mysqli_close($conn); 

    http_response_code(500);
    echo json_encode($data);
    exit;
}

?>