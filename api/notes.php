<?php
class Note
{
  private $conn;
  private $table_name = "notes";

  public $id;
  public $title;
  public $description;

  public function __construct($db)
  {
    $this->conn = $db;
  }

  function read()
  {
    $query = "SELECT * FROM " . $this->table_name;
    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    return $stmt;
  }

  function read_single()
  {
    $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $this->id);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $this->id = $row['id'];
    $this->title = $row['title'];
    $this->description = $row['description'];
    // return $row;
  }

  function create()
  {
    $query = "INSERT INTO " . $this->table_name . " SET title=:title, description=:description";
    $stmt = $this->conn->prepare($query);

    $this->title = htmlspecialchars(strip_tags($this->title));
    $this->description = htmlspecialchars(strip_tags($this->description));

    $stmt->bindParam(":title", $this->title);
    $stmt->bindParam(":description", $this->description);

    if ($stmt->execute()) {
      return true;
    }

    return false;
  }

  function update()
  {
    $query = "UPDATE " . $this->table_name . " SET title=:title, description=:description WHERE id=:id";
    $stmt = $this->conn->prepare($query);

    $this->title = htmlspecialchars(strip_tags($this->title));
    $this->description = htmlspecialchars(strip_tags($this->description));
    $this->id = htmlspecialchars(strip_tags($this->id));

    $stmt->bindParam(":title", $this->title);
    $stmt->bindParam(":description", $this->description);
    $stmt->bindParam(":id", $this->id);

    if ($stmt->execute()) {
      return true;
    }

    return false;
  }

  function delete()
  {
    $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $this->id);

    if ($stmt->execute()) {
      return true;
    }

    return false;
  }
}