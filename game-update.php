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

    if (isset($_POST["match_id"]) && !empty($_POST["match_id"]) && isset($_POST["box_id"]) 
    && isset($_POST["user_id"]) && !empty($_POST["user_id"]) && isset($_POST["match_status"]) && !empty($_POST["match_status"]) 
    && isset($_POST["match_win_by"])) { 
        
        $box_id=$_POST["box_id"];
        $stmt="UPDATE `games` SET box$box_id=?,current_move=?,match_status=?,match_win_by=?,updated_at=?
        WHERE games.id=(?) and games.deleted_at IS NULL limit 1";
        $sql=mysqli_prepare($conn, $stmt);
        mysqli_stmt_bind_param($sql,"iiiisi",$user_id,$user_id,$match_status,$match_win_by,$updated_at,$match_id);

        $updated_at = date('Y-m-d H:i:s');
        $match_id=$_POST["match_id"];
        $user_id=$_POST["user_id"];
        $match_status=$_POST["match_status"];
        $match_win_by=$_POST["match_win_by"];

        $result=mysqli_stmt_execute($sql);

        if(!$result){
            $data = "Something went wrong.";
            mysqli_stmt_close($sql);
            mysqli_close($conn); 

            http_response_code(500);
            echo json_encode($data);
            exit;
        }

        $data = "Successfully updated";
        mysqli_stmt_close($sql);
        mysqli_close($conn);  
        http_response_code(200);
        echo json_encode($data);
        exit;
    }
    else{
        $msg="Some important data is missing !";
        http_response_code(500);
        echo json_encode($msg);
        exit;
    }
      
    
?>