<?php
    include '../modal/padpay.php';
    
    http_response_code(503);
    $res = [
        "status" => 503,
        "msg" =>  "Sistem Padpay belum terintegrasi."
    ];
    echo json_encode($res);
    exit;
    
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