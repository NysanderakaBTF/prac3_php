<?php

require_once 'Api.php';
class productAPI extends Api
{

    public $apiName = 'product';

    public function indexAction()
    {
        $mysqli = new mysqli("db", "user", "password", "appDB");
        $stmt = $mysqli->query("SELECT *  FROM product");
        if ($stmt) {
            return $this->response($stmt, 200);
        }
        $stmt->close();
        return $this->response("404", 404);
    }

    public function viewAction()
    {
        $mysqli = new mysqli("db", "user", "password", "appDB");
        if (isset($this->requestParams['id'])) {
            $id = $this->requestParams['id'];
        } else {
            $id = null;
        }
        if ($id != null) {
            $stmt = $mysqli->prepare("SELECT * FROM product where id=?");
            $stmt->bind_param("s", $id);
            $res = $stmt->execute();
            $arr = array();
            if ($res) {
                foreach ($stmt->get_result() as $item) {
                    array_push($arr, $item);
                }
                $stmt->close();
                return $this->response($arr, 200);
            } else {
                $stmt->close();
                return $this->response($stmt->error_list, 500);
            }
        } else {
            $mysqli = new mysqli("db", "user", "password", "appDB");
            $stmt = $mysqli->query("SELECT *  FROM product");
            if ($stmt) {
                $arr = array();
                foreach ($stmt as $item) {
                    array_push($arr, $item);
                }

                $stmt->close();
                return $this->response($arr, 200);
            }
            $stmt->close();
            return $this->response("404", 404);
        }
        $stmt->close();
        return $this->response('Data not found', 404);
    }

    public function createAction()
    {
        $mysqli = new mysqli("db_pr3_php", "user", "password", "appDB");
        $titile = isset($this->requestParams['title']) ? $this->requestParams['title'] : '';
        $description = isset($this->requestParams['description']) ? $this->requestParams['description'] : 'not provided';
        $image_url = isset($this->requestParams['image_url']) ? $this->requestParams['image_url'] : '';
        $user_id = isset($this->requestParams['user_id']) ? $this->requestParams['user_id'] : '';
        if ($titile && $image_url && $user_id) {

            $stmt = $mysqli->prepare("
            INSERT INTO product(title, description, image_url, user_id) VALUES (?, ?, ?, ?)
            ");
            $stmt->bind_param("ssss", $titile, $description, $image_url, $user_id);
            if ($stmt->execute()) {
                $stmt->close();
                return $this->response('Data saved.', 200);
            }
            $stmt->close();
        }

        return $this->response("Saving error", 500);
    }

    public function updateAction()
    {
        $userId = isset($this->requestParams['id']) ? $this->requestParams['id'] : null;

        $mysqli = new mysqli("db", "user", "password", "appDB");

        $ssss = $mysqli->prepare("SELECT * FROM product WHERE id=?");
        $ssss->bind_param("s", $userId);
        $user = $ssss->execute();

        $ssss->close();


        if (!$userId || !$user) {
            return $this->response("User with id=$userId not found", 404);
        }

        $titile = isset($this->requestParams['title']) ? $this->requestParams['title'] : '';
        $description = isset($this->requestParams['description']) ? $this->requestParams['description'] : '';
        $image_url = isset($this->requestParams['image_url']) ? $this->requestParams['image_url'] : '';
        $user_id = isset($this->requestParams['user_id']) ? $this->requestParams['user_id'] : '';

        if ($titile && $image_url && $user_id) {
            $updated = $mysqli->prepare("UPDATE product SET title=?, description=?, image_url=?, user_id=?
                  WHERE id=?");
            $updated->bind_param("sssss", $titile, $description, $image_url, $user_id, $userId);
            if ($updated->execute()) {
                $updated->close();
                return $this->response('Data updated.', 200);
            }
        }
        return $this->response("Update error", 400);
    }

    public function deleteAction()
    {
        $mysqli = new mysqli("db", "user", "password", "appDB");
        $userId = isset($this->requestParams['id']) ? $this->requestParams['id'] : null;

        $user = $mysqli->prepare("SELECT * FROM product WHERE id=?");
        $user->bind_param("s", $userId);
        $user->execute();

        $user->close();


        if (!$userId || !$user) {
            return $this->response("User with id=$userId not found", 404);
        }

        $deleted = $mysqli->prepare("DELETE FROM product WHERE id=?");
        $deleted->bind_param("s", $userId);
        $deleted->execute();


        if ($deleted) {
            $deleted->close();
            return $this->response('Data deleted.', 200);
        }
        $deleted->close();
        return $this->response("Delete error", 500);
    }
}