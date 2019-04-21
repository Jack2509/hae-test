<?php

namespace Hea\Models;

use Hea\Models\Model;

class CommentModel extends Model
{
    // table name
    private $tableName = 'comments';
    private $request;

    // table columns
    public $id;
    public $user_id;
    public $content;
    public $deleted_flag;
    public $created_at;
    public $updated_at;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function create($user_id, $content)
    {
        $query = "INSERT INTO " . $this->tableName . " (user_id, content) VALUES (?, ?)";
        $stmt = self::connect()->prepare($query);
        $stmt->bindValue(1, $user_id, \PDO::PARAM_STR);
        $stmt->bindValue(2, $content, \PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function read($id = null)
    {
        $query = "SELECT id, user_id, content, deleted_flag, created_at, updated_at FROM" . " " . $this->tableName . " " . "WHERE deleted_flag !=1";
        $query .= $id == null ? '' : ' and id=:id';
        $arrayExecute = $id == null ? [] : ['id' => $id];

        $stmt = self::connect()->prepare($query);
        $stmt->execute($arrayExecute);
        return $stmt;
    }

    public function update($id, $newMessage)
    {
        $query = "UPDATE " . $this->tableName . " SET content=:content WHERE id=:id";
        $stmt = self::connect()->prepare($query);
        return $stmt->execute(['content' => $newMessage, 'id' => $id]);
    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->tableName . " WHERE id=:id";
        $stmt = self::connect()->prepare($query);
        return $stmt->execute(['id' => $id]);
    }
}