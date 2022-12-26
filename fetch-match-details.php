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

    if (isset($_GET["id"]) && !empty($_GET["id"])) { 
        $stmt="SELECT users.name as player_two_name,users.username as player_two_username,games.player_two_id,games.current_move,games.match_status,games.match_win_by,
        games.box0,games.box1,games.box2,games.box3,games.box4,games.box5,games.box6,games.box7,games.box8 FROM `games` 
        INNER JOIN users on users.id=games.player_two_id  WHERE games.id=(?) and games.deleted_at IS NULL limit 1";
        $sql=mysqli_prepare($conn, $stmt);
        mysqli_stmt_bind_param($sql,"s",$id);
        $id=$_GET["id"];
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