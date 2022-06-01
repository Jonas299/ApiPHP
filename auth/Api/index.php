<?php
require_once __DIR__ . "../../../Controllers/ContactController.php";
require_once __DIR__ . "../../../App/Functions.php";

use Controllers\ContactController;
use App\Functions;

$Contact = new ContactController;

header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');
/*============| CONSULTAS |============*/
$response['error'] = 0;

#POST
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'create') {
        $response = Functions::verifyRequest($_POST, "name|string,lastname|string,email|email,phones|string|required");
        if ($response['error'] != 1) {
            $name = $_POST['name'];
            $lastname = $_POST['lastname'];
            $email = $_POST['email'];
            //Verificar si es solo uno o una coleccion
            $phones = $_POST['phones'];
            if (!is_array($phones)) {
                $phones = [$phones];
            }
            //Add contact
            $resp = $Contact->create($name, $lastname, $email, $phones);
            if (is_bool($resp) && $resp) {
                http_response_code(200);
                echo json_encode(["success" => true]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => true, "message" => $resp]);
            }
        } else {
            http_response_code(500);
            echo json_encode($response);
        }
    } elseif ($_POST['action'] == 'update') {

        $response = Functions::verifyRequest($_POST, "id|number,name|string,lastname|string,email|email");
        if ($response['error'] != 1) {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $lastname = $_POST['lastname'];
            $email = $_POST['email'];

            //Update contact
            $resp = $Contact->update($id, $name, $lastname, $email);
            if (is_bool($resp) && $resp) {
                http_response_code(200);
                echo json_encode(["success" => true]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => true, "message" => $resp]);
            }
        } else {
            http_response_code(500);
            echo json_encode($response);
        }
    } elseif ($_POST['action'] == 'updatePhone') {

        $response = Functions::verifyRequest($_POST, "id|number,phone|string");
        if ($response['error'] != 1) {
            $id = $_POST['id'];
            $phone = $_POST['phone'];

            //Update contact
            $resp = $Contact->updatePhone($id, $phone);
            if (is_bool($resp) && $resp) {
                http_response_code(200);
                echo json_encode(["success" => true]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => true, "message" => $resp]);
            }
        } else {
            http_response_code(500);
            echo json_encode($response);
        }
    } elseif ($_POST['action'] == 'delete') {

        $response = Functions::verifyRequest($_POST, "id|number");
        if ($response['error'] != 1) {
            $id = $_POST['id'];
            $resp = $Contact->destroy($id);
            if (is_bool($resp) && $resp) {
                http_response_code(200);
                echo json_encode(["success" => true]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => true, "message" => $resp]);
            }
        } else {
            http_response_code(500);
            echo json_encode($response);
        }
    } else {
        http_response_code(500);
        echo json_encode(["error" => "No se ha establecido ningun parametro"]);
    }
} 
#GET
else if (isset($_GET['action'])) { //GET REQUEST
    if ($_GET['action'] == 'show') {
        echo json_encode($Contact->show());
    }
} else {
    http_response_code(500);
    echo json_encode(["error" => "No se ha establecido ningun parametro"]);
}
