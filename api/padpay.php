<?php
    include '../modal/padpay.php';
    
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $padpay = new Padpay;
        $input = json_decode(file_get_contents("php://input"), true);
        $padpay->padpay($input);
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