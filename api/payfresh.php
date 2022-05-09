<?php
    include '../modal/payfresh.php';
    
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $payfresh = new Payfresh;
        $input = json_decode(file_get_contents("php://input"), true);
        $payfresh->payfresh($input);
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