<?php
    include '../modal/talangin.php';
    
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $talangin = new Talangin;
        $input = json_decode(file_get_contents("php://input"), true);
        $talangin->talangin($input);
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