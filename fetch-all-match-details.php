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

    if (isset($_GET["player_one_id"]) && !empty($_GET["player_one_id"])) { 
        $stmt="SELECT users.name as player_two_name,users.username as player_two_username,games.id as match_id,games.current_move,games.match_status,games.match_win_by
        FROM `usergames` INNER JOIN games on games.id=usergames.game_id INNER JOIN users on users.id=games.player_two_id
        WHERE usergames.user_id=(?) and games.deleted_at IS NULL";
        $sql=mysqli_prepare($conn, $stmt);
        mysqli_stmt_bind_param($sql,"s",$player_one_id);
        $player_one_id=$_GET["player_one_id"];
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
        $msg="Player One Id is missing !";
        mysqli_stmt_close($sql);
        mysqli_close($conn); 

        http_response_code(500);
        echo json_encode($msg);
        exit;
    }
      
    
?>