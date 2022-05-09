<?php
    include '../modal/kcnpay.php';
    
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $kcnpay = new Kcnpay;
        $input = json_decode(file_get_contents("php://input"), true);
        $kcnpay->kcnpay($input);
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