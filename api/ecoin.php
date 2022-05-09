<?php
    include '../modal/ecoin.php';
    
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $ecoin = new Ecoin;
        $input = json_decode(file_get_contents("php://input"), true);
        $ecoin->ecoin($input);
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