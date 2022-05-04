<?php
    include '../modal/buskidicoin.php';
    
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $buskidicoin = new Buskidicoin;
        $input = json_decode(file_get_contents("php://input"), true);
        $buskidicoin->buskidicoin($input);
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