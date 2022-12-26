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

    

    if (checkReq($_POST)) {
        include('./config.php');
        // check weather game already exist
        $stmt="SELECT id FROM `games` WHERE player_one_id=(?) and player_two_id=(?) and match_status=(?) and deleted_at IS NULL";
        $sql=mysqli_prepare($conn, $stmt);

        //binding the parameters to prepard statement
        mysqli_stmt_bind_param($sql,"iii",$player_one_id,$player_two_id,$match_status);
        $created_at = date('Y-m-d H:i:s'); 
        
        $player_one_id=$_POST['player_one_id'];
        $player_two_id=$_POST['player_two_id'];
        $current_move=1;
        $match_status=1;
        $result=mysqli_stmt_execute($sql);
        $data= mysqli_stmt_store_result($sql);
        $no_of_row=mysqli_stmt_num_rows($sql);

        if($no_of_row>0){
            $data = "You have already started a match with this user !";
            mysqli_stmt_close($sql);
            mysqli_close($conn); 

            http_response_code(500);
            echo json_encode($data);
            exit;
        }
        mysqli_stmt_close($sql);
		
        

        
            
            $stmt="INSERT INTO `games` (player_one_id,player_two_id,current_move,match_status,created_at) VALUES (?,?,?,?,?)";
            $sql=mysqli_prepare($conn, $stmt);
        
            //binding the parameters to prepard statement
            mysqli_stmt_bind_param($sql,"iiiis",$player_one_id,$player_two_id,$current_move,$match_status,$created_at);
           
            $player_one_id=$_POST['player_one_id'];
            $player_two_id=$_POST['player_two_id'];
            $created_at = date('Y-m-d H:i:s'); 
            $current_move=$player_two_id;
            // current move means the player whom have just played
            $match_status=1;
            
            $result=mysqli_stmt_execute($sql);
            if (!$result){ 
             
                $data = "Something went wrong.";
                mysqli_stmt_close($sql);
                mysqli_close($conn); 

                http_response_code(500);
                echo json_encode($data);
                exit;
            }




            $data=[
                'match_id'=>$sql->insert_id,
                'success'=>true,
                'message'=>"Successfully game created"
            ];

            $game_id=$sql->insert_id;
            mysqli_stmt_close($sql); 

            $stmt="INSERT INTO `usergames` (user_id,game_id,created_at) VALUES (?,?,?)";
            $sql=mysqli_prepare($conn, $stmt);

            mysqli_stmt_bind_param($sql,"iis",$player_one_id,$game_id,$created_at);

            $result=mysqli_stmt_execute($sql);
            if (!$result){ 
             
                $data = "Something went wrong.";
                mysqli_stmt_close($sql);
                mysqli_close($conn); 

                http_response_code(500);
                echo json_encode($data);
                exit;
            }
            mysqli_stmt_close($sql);
            
            $stmt="INSERT INTO `usergames` (user_id,game_id,created_at) VALUES (?,?,?)";
            $sql=mysqli_prepare($conn, $stmt);

            mysqli_stmt_bind_param($sql,"iis",$player_two_id,$game_id,$created_at);

            $result=mysqli_stmt_execute($sql);
            if (!$result){ 
             
                $data = "Something went wrong.";
                mysqli_stmt_close($sql);
                mysqli_close($conn); 

                http_response_code(500);
                echo json_encode($data);
                exit;
            }
            mysqli_stmt_close($sql);
            mysqli_close($conn); 
            http_response_code(200);
            echo json_encode($data);
            exit;
        
    } 
    else {
        $data = "Please fill all the details.";
            
        http_response_code(500);
        echo json_encode($data);
        exit;
    }
?>