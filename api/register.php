<?php 
    include '../modal/register.php';
    
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $register = new Register;
        $input = json_decode(file_get_contents("php://input"), true);
        $register->register($input);
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