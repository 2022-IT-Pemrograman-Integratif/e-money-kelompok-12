<?php
    include '../modal/payphone.php';
    
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $payphone = new Payphone;
        $input = json_decode(file_get_contents("php://input"), true);
        $payphone->payphone($input);
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