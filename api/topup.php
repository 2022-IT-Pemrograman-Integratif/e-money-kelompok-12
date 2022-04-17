<?php 
    include '../modal/topup.php';
    
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $topup = new Topup;
        $input = json_decode(file_get_contents("php://input"), true);
        $topup->topup($input);
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