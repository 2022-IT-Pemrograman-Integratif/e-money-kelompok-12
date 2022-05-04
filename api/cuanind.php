<?php
    include '../modal/cuanind.php';
    
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $cuanind = new Cuanind;
        $input = json_decode(file_get_contents("php://input"), true);
        $cuanind->cuanind($input);
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