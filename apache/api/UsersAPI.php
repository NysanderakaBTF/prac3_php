<?php

require_once 'Api.php';

class UsersAPI extends Api
{
    public $apiName = 'users';

    public function indexAction()
    {
        $mysqli = new mysqli("db", "user", "password", "appDB");
        $stmt = $mysqli->query("SELECT *  FROM users");
        if ($stmt) {
            return $this->response($stmt, 200);
        }
        $stmt->close();
        return $this->response("404", 404);
    }

    public function viewAction()
    {
        $mysqli = new mysqli("db_pr3_php", "user", "password", "appDB");
        if (isset($this->requestParams['id'])) {
            $id = $this->requestParams['id'];
        } else {
            $id = null;
        }
        if ($id != null) {
            $stmt = $mysqli->prepare("SELECT users.id , users.username, product.title 
                FROM users LEFT JOIN product ON users.id=product.user_id where users.id=?");
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
            $stmt = $mysqli->query("SELECT *  FROM users");
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
        return $this->response('Data not found', 404);
    }

    public function createAction()
    {
        $mysqli = new mysqli("db", "user", "password", "appDB");
        $password = isset($this->requestParams['password']) ? $this->requestParams['password'] : '';
        $username = isset($this->requestParams['username']) ? $this->requestParams['username'] : '';
        if ($password && $username) {

            $stmt = $mysqli->prepare("
            INSERT INTO users(username, password) VALUES (?, ?)
            ");
            $stmt->bind_param("ss", $username, $password);
            if ($stmt->execute()) {
                $stmt->close();
                return $this->response('Data saved.', 200);
            }
        }
        return $this->response("Saving error", 500);
    }

    public function updateAction()
    {
        $userId = isset($this->requestParams['id']) ? $this->requestParams['id'] : null;

        $mysqli = new mysqli("db", "user", "password", "appDB");

        $ssss = $mysqli->prepare("SELECT * FROM users WHERE id=?");
        $ssss->bind_param("s", $userId);
        $user = $ssss->execute();

        $ssss->close();


        if (!$userId || !$user) {
            return $this->response("User with id=$userId not found", 404);
        }

        $username = isset($this->requestParams['username']) ? $this->requestParams['username'] : '';
        $password = isset($this->requestParams['password']) ? $this->requestParams['password'] : '';

        if ($username && $password) {
            $updated = $mysqli->prepare("UPDATE users SET username=?, password=? WHERE id=?");
            $updated->bind_param("sss", $username, $password, $userId);
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
        $userId =  isset($this->requestParams['id']) ? $this->requestParams['id'] : null;

        $user = $mysqli->prepare("SELECT * FROM users WHERE id=?");
        $user->bind_param("s", $userId);
        $user->execute();

        $user->close();



        if (!$userId || !$user) {
            return $this->response("User with id=$userId not found", 404);
        }

        $deleted = $mysqli->prepare("DELETE FROM users WHERE id=?");
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