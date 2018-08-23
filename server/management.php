<?php

namespace MyApp;

class Borrow_book {
 private $db;

  public function __construct() {
    try {
      $this->db = new \PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
      $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    } catch (\PDOException $e) {
      echo $e->getMessage();
      exit;
    }
  }

  public function getLatestBooks() {
    $stmt = $this->db->query(
        "SELECT
            title,
            books.id AS book_id,
            bh.id AS history_id,
            CASE
            WHEN borrowable IS NULL THEN 1
            ELSE borrowable
            END AS borrowable
        FROM (
            SELECT *
            FROM borrowing_histories AS m
            WHERE NOT EXISTS (
                SELECT id
                FROM borrowing_histories AS s
                WHERE m.book_id = s.book_id
                AND m.date < s.date
            ) )AS bh
        RIGHT JOIN books 
        ON bh.book_id = books.id");
    return $stmt->fetchAll(\PDO::FETCH_OBJ);
  }

  public function getUser(){
    $sql_query = "SELECT * FROM users";
    $stmt = $this->db->query($sql_query);
    return $stmt->fetchALL(\PDO::FETCH_ASSOC);
  }

  public function BookBorrow($book_id,$user_id){
    $stmt = $this->db->prepare("INSERT INTO borrowing_histories (book_id, user_id, borrowable) VALUES (:book_id, :user_id, :borrowable)");
    $stmt->execute([
        ':book_id' => $this->$book_id,
        ':user_id' => $this->$user_id,
        ':borrowable' => 0]);
  }

  public function BookReturn(){

  }

}