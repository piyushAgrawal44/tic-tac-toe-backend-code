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
      
    $stmt="SELECT id,email FROM `users` WHERE deleted_at IS NULL";
    $sql=mysqli_prepare($conn, $stmt);
  
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
    $i=0;
    $mydata[]=array();
    while($row=mysqli_fetch_array($data)){
        $mydata[$i]=$row;
        $i++;
    }

    mysqli_stmt_close($sql);
    mysqli_close($conn);  
    http_response_code(200);
    echo json_encode($mydata);
    exit;
?>