<?php

namespace Hea\Controllers\Api;

use Hea\Controllers\Controller;
use Hea\Router\Request;
use Hea\Models\CommentModel;
use Hea\Models\Model;

class CommentController extends Controller
{
    public function read(Request $request)
    {
        $paramsRequest = $request->getBody();
        //receive all comments
        $commentModel = new CommentModel($paramsRequest);
        if (!empty($paramsRequest['id']) && is_int_number($paramsRequest['id'])) {
            $comments = $commentModel->read($paramsRequest['id']);
        } else {
            $comments = $commentModel->read();
        }

        $commentsArr = [];
        $commentsArr["records"] = [];
        // check if more than 0 record found
        if ($comments->rowCount() > 0) {
            // retrieve our table contents
            while ($row = $comments->fetch(\PDO::FETCH_ASSOC)) {
                // extract row
                // this will make $row['name'] to just $name only
                extract($row);

                $commentsItem = [
                    "id" => $id,
                    "user_id" => $user_id,
                    "content" => html_entity_decode($content),
                    "updated_at" => $updated_at,
                    "created_at" => $created_at,
                    "deleted_flag" => $deleted_flag
                ];

                array_push($commentsArr["records"], $commentsItem);
            }

            // required headers
            header("Access-Control-Allow-Origin: *");
            header("Content-Type: application/json; charset=UTF-8");

            // set response code - 200 OK
            http_response_code(200);

            // show comments data in json format
            echo json_encode($commentsArr);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Unable to load comment."]);
        }
    }

    public function create(Request $request)
    {
        // required headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        $paramsRequest = $request->getBody();

        // make sure post data is not empty and email is valid form
        if (
            !empty($paramsRequest['name']) &&
            !empty($paramsRequest['email']) &&
            !empty($paramsRequest['message']) &&
            filter_var($paramsRequest['email'], FILTER_VALIDATE_EMAIL)
        ) {
            // set comment property values
            $commentModel = new CommentModel($paramsRequest);

            // check if new or exist user base on email
            $stmt = Model::connect()->prepare("select id from users where email like ? limit 1");
            $stmt->bindValue(1, "%" . $paramsRequest['email'] . "%", \PDO::PARAM_STR);
            $stmt->execute();

            // create the comment
            if ($stmt->rowCount()) {
                $user = $stmt->fetch(\PDO::FETCH_ASSOC);
                $this->createNewComment($commentModel, $user, $paramsRequest);
            } else {
                $stmt = Model::connect()->prepare("
                INSERT INTO 
                users (name, email, password, role) 
                VALUES (:name, :email, :password, :role)");

                if ($stmt->execute(['name' => $paramsRequest['name'], 'email' => $paramsRequest['email'], 'password' => null, 'role' => 1])) {

                    // check if new or exist user base on email
                    $stmt = Model::connect()->prepare("select id from users where email like ? limit 1");
                    $stmt->bindValue(1, "%" . $paramsRequest['email'] . "%", \PDO::PARAM_STR);
                    $stmt->execute();
                    $user = $stmt->fetch(\PDO::FETCH_ASSOC);
                    $this->createNewComment($commentModel, $user, $paramsRequest);
                } else {// if unable to create the new user

                    // set response code - 503 service unavailable
                    http_response_code(503);

                    // tell the user
                    echo json_encode(["user" => "Unable to create a new user."]);
                }
            }

        } else {// tell the user data is incomplete

            // set response code - 400 bad request
            http_response_code(400);

            // tell the user
            echo json_encode(["message" => "Unable to create a new Comment. Data is incomplete."]);
        }
    }

    public function update(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        $paramsRequest = $request->getBody();
        // update the comment
        if (
            !empty($paramsRequest['update_message']) &&
            !empty($paramsRequest['message_id']) &&
            isset($_SESSION['login_flag']) && $_SESSION['login_flag']
        ) {
            // set comment property values
            $commentModel = new CommentModel($paramsRequest);
            if ($commentModel->update($paramsRequest['message_id'], $paramsRequest['update_message'])) {
                // set response code - 200 ok
                http_response_code(200);

                // tell the user
                echo json_encode(array("message" => "Comment was updated."));
            } else {
                // set response code - 503 service unavailable
                http_response_code(503);

                // tell the user
                echo json_encode(array("message" => "Unable to update comment."));
            }
        } else {// tell the user data is incomplete

            // set response code - 400 bad request
            http_response_code(400);

            // tell the user
            echo json_encode(["message" => "Unable to update a Comment. Data is incomplete or fail in authentication"]);
        }
    }

    public function delete(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        $paramsRequest = $request->getBody();

        // delete comment
        if (
            !empty($paramsRequest['id']) &&
            isset($_SESSION['login_flag']) && $_SESSION['login_flag']
        ) {
            // set comment property values
            $commentModel = new CommentModel($paramsRequest);
            if ($commentModel->delete($paramsRequest['id'])) {
                // set response code - 200 ok
                http_response_code(200);

                // tell the user
                echo json_encode(array("message" => "Comment was deleted."));
            } else {
                // set response code - 503 service unavailable
                http_response_code(503);

                // tell the user
                echo json_encode(array("message" => "Unable to delete comment."));
            }
        } else {

            // set response code - 400 bad request
            http_response_code(400);

            // tell the user
            echo json_encode(["message" => "Unable to delete a Comment. Data is incomplete or fail in authentication"]);
        }
    }

    private function createNewComment(CommentModel $commentModel, $user, $paramsRequest)
    {
        if ($commentModel->create($user['id'], $paramsRequest['message'])) {
            // set response code - 201 created
            http_response_code(201);

            // tell the user
            echo json_encode(["message" => "new Comment was created."]);

        } else {// if unable to create the comment, tell the user

            // set response code - 503 service unavailable
            http_response_code(503);

            // tell the user
            echo json_encode(["message" => "Unable to create a new Comment."]);
        }
    }
}