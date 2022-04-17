<?php 
    include '../modal/login.php';
    
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $login = new Login;
        $input = json_decode(file_get_contents("php://input"), true);
        $login->login($input);
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