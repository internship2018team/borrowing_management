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
    $sql_query = 
    "SELECT
        title,
        books.id AS book_id,
        bh.id AS history_id,
        bh.user_id AS user_id,
        bh.date AS borrow_date,
        CASE
        WHEN can_borrow IS NULL THEN 1
        ELSE can_borrow
        END AS can_borrow
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
    ON bh.book_id = books.id";
    $stmt = $this->db->query($sql_query);
        
    return $stmt->fetchAll(\PDO::FETCH_OBJ);
  }

  public function getUser(){
    $sql_query = "SELECT * FROM users";
    $stmt = $this->db->query($sql_query);
    return $stmt->fetchALL(\PDO::FETCH_ASSOC);
  }

  public function BookBorrow($book_id,$user_id){
    $this->db->beginTransaction();
    $stmt = $this->db->prepare("INSERT INTO borrowing_histories (book_id, user_id, can_borrow) VALUES (:book_id, :user_id, :can_borrow)");
    $stmt->execute([
        ':book_id' => $book_id,
        ':user_id' => $user_id,
        ':can_borrow' => 0]);
    $this->db->commit();
  }

  public function BookReturn($history_id){
    $this->db->beginTransaction();
    // ここにborrow_historiesのupdateを書く
    $stmt = $this->db->prepare("UPDATE borrowing_histories SET can_borrow = 1 WHERE id = :history_id");
    $stmt->execute([':history_id' => $history_id]);
    $this->db->commit();
  }

}