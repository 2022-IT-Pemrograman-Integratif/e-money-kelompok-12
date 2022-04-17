<?php 
    include '../modal/transfer.php';
    
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $transfer = new Transfer;
        $input = json_decode(file_get_contents("php://input"), true);
        $transfer->transfer($input);
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