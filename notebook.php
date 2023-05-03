<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once 'config/database.php';
include_once 'api/notes.php';

$database = new Database();
$db = $database->getConnection();

$note = new Note($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
  case 'GET':
    if (isset($_GET['id'])) {
      $note->id = $_GET['id'];
      $note->read_single();
      $note_arr = array(
        'id' => $note->id,
        'title' => $note->title,
        'description' => $note->description
      );
      http_response_code(200);
      echo json_encode($note_arr);
    } else {

      $stmt = $note->read();
      $num = $stmt->rowCount();

      if ($num > 0) {
        $note_arr = array();
        $note_arr['data'] = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          extract($row);
          $note_item = array(
            'id' => $id,
            'title' => $title,
            'description' => html_entity_decode($description)
          );
          array_push($note_arr['data'], $note_item);
        }

        http_response_code(200);
        echo json_encode($note_arr);
      } else {
        http_response_code(404);
        echo json_encode(array('message' => 'No notes found.'));
      }
    }
    break;

  case 'POST':

    $data = json_decode(file_get_contents("php://input"));
    $note->title = $data->title;
    $note->description = $data->description;

    if ($note->create()) {
      http_response_code(201);
      echo json_encode([
        'message' => 'Note created',
        'title' => $note->title,
        'description' => $note->description
      ]);
    } else {
      http_response_code(503);
      echo json_encode(array('message' => 'Unable to create note.'));
    }
    break;

  case 'PUT':
    $data = json_decode(file_get_contents("php://input"));

    $note->id = $data->id;
    $note->title = $data->title;
    $note->description = $data->description;

    if ($note->update()) {
      http_response_code(200);
      echo json_encode(array('message' => 'Note updated.'));
    } else {
      http_response_code(503);
      echo json_encode(array('message' => 'Unable to update note.'));
    }
    break;

  case 'DELETE':
    //This is the way to get the data from body
    // $data = json_decode(file_get_contents("php://input"));
// $note->id = $data->id;

    //This is another way to get the data from body
    // $url = $_SERVER['REQUEST_URI'];
    // $segments = explode('/', $url);
    // $id = end($segments);
    // $note->id = $id;

    $note->id = $_GET['id'];
    if ($note->delete()) {
      http_response_code(200);
      echo json_encode(array('message' => 'Note deleted.'));
    } else {
      http_response_code(503);
      echo json_encode(array('message' => 'Unable to delete note.'));
    }
    break;

  default:
    http_response_code(405);
    echo json_encode(array('message' => 'Invalid request method.'));
    break;
}