<?php
    include '../modal/moneyz.php';
    
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $moneyz = new Moneyz;
        $input = json_decode(file_get_contents("php://input"), true);
        $moneyz->moneyz($input);
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