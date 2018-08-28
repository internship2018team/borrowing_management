<?php

namespace MyApp;

class Borrow_book {
  private $_db;
  private $query_get_book_state =
  "SELECT title, books.id AS book_id, bh.id AS history_id, bh.user_id AS user_id, bh.date AS borrow_date,
      CASE WHEN can_borrow IS NULL THEN 1 ELSE can_borrow END AS can_borrow
    FROM (
      SELECT *
      FROM borrowing_histories AS m
      WHERE NOT EXISTS (
        SELECT id
        FROM borrowing_histories AS s
        WHERE m.book_id = s.book_id
        AND m.date < s.date
      )
    )AS bh
  RIGHT JOIN books 
  ON bh.book_id = books.id";
    

  public function __construct() {
    try {
      $this->_db = new \PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
      $this->_db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    } catch (\PDOException $e) {
      echo $e->getMessage();
      exit;
    }
  }

  public function getLatestBooks() {
    $stmt = $this->_db->query($this->query_get_book_state);    
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function getUser(){
    $sql_query = 
    "SELECT *
    FROM users";
    $stmt = $this->_db->query($sql_query);
    return $stmt->fetchALL(\PDO::FETCH_ASSOC);
  }

  public function getBooks(){
    $sql_query = 
    "SELECT *
    FROM books";
    $stmt = $this->_db->query($sql_query);
    return $stmt->fetchALL(\PDO::FETCH_ASSOC);
  }

  public function BookBorrow($book_id,$user_id){
    $this->_db->beginTransaction();
    $sql_query =
    "INSERT INTO borrowing_histories (book_id, user_id, can_borrow) 
    VALUES (:book_id, :user_id, :can_borrow)";
    $stmt = $this->_db->prepare($sql_query);
    $stmt->execute([
      ':book_id' => $book_id,
      ':user_id' => $user_id,
      ':can_borrow' => 0
    ]);
    $this->_db->commit();
  }

  public function BookReturn($history_id){
    $this->_db->beginTransaction();
    $sql_query =
    "UPDATE borrowing_histories 
    SET can_borrow = 1 
    WHERE id = :history_id";
    $stmt = $this->_db->prepare($sql_query);
    $stmt->execute([':history_id' => $history_id]);
    $this->_db->commit();
  }
  public function BookSearch($query){
    try {
      $sql_query = $this->query_get_book_state . " WHERE title LIKE :query";
      $stmt = $this->_db->prepare($sql_query);
      if($stmt->execute([':query' => '%' . $query . '%'])){
        return $stmt->fetchALL(\PDO::FETCH_ASSOC);
      }else{
        return [];
      }  
    } catch (PDOException $e){
      print('Error:'.$e->getMessage());
      exit;
    }
  }

  public function addUser($name){
    $this->_db->beginTransaction();
    $sql_query =
    "INSERT INTO users (name) VALUES (:user_name)";
    $stmt = $this->_db->prepare($sql_query);
    $stmt->execute([':user_name' => $name]);
    $this->_db->commit();
  }

  public function addBook($title){
    $this->_db->beginTransaction();
    $sql_query =
    "INSERT INTO books (title) VALUES (:book_title)";
    $stmt = $this->_db->prepare($sql_query);
    $stmt->execute([':book_title' => $title]);
    $this->_db->commit();
  }

  public function deleteUser($id){
    $this->_db->beginTransaction();
    $sql_query =
    "DELETE FROM users WHERE id = (:user_id)";
    $stmt = $this->_db->prepare($sql_query);
    $stmt->execute([':user_id' => $id]);
    $this->_db->commit();
  }

  public function deleteBook($id){
    $this->_db->beginTransaction();
    $sql_query =
    "DELETE FROM books WHERE id = (:book_id)";
    $stmt = $this->_db->prepare($sql_query);
    $stmt->execute([':book_id' => $id]);
    $this->_db->commit();
  }
}