<?php
    include '../modal/users.php';
    
    if($_SERVER["REQUEST_METHOD"] == "GET") {
        $users = new Users;
        $input = json_decode(file_get_contents("php://input"), true);
        $users->users(isset($_GET['number']) ? ($_GET['number']) : NULL);
    }
    else {
        http_response_code(405);
        $res = [
            'status' => 405,
            'msg' => "Request method tidak ditersedia."
        ];
        echo json_encode($res);
    }
?> 