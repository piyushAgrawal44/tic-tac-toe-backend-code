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

    if (isset($_GET["match_id"]) && !empty($_GET["match_id"])) { 
        $stmt="SELECT users.id,users.name,users.username FROM `usergames` 
        INNER JOIN users on users.id=usergames.user_id  WHERE usergames.game_id=(?)";
        $sql=mysqli_prepare($conn, $stmt);
        mysqli_stmt_bind_param($sql,"i",$match_id);
        $match_id=$_GET["match_id"];
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
    }
    else{
        $msg="Match Id is missing !";
        mysqli_stmt_close($sql);
        mysqli_close($conn); 

        http_response_code(500);
        echo json_encode($msg);
        exit;
    }
      
    
?>