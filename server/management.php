<?php

namespace MyApp;

class Borrow_book {
    private $_db;
    private $fetch_book_state_query =
        "SELECT
            title,
            books.id AS book_id,
            histories.id AS history_id,
            user_id,
            date AS borrow_date,
            can_borrow
        FROM books
        LEFT JOIN (
            SELECT main.*
            FROM borrowing_histories AS main
            LEFT JOIN borrowing_histories AS sub
            ON main.book_id = sub.book_id AND main.date < sub.date
            WHERE sub.date IS NULL
        ) AS histories
        ON books.id = histories.book_id";
        

    public function __construct() {
        try {
            $this->_db = new \PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
            $this->_db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            echo $e->getMessage();
        exit;
        }
    }

    public function fetchLatestBooks() {
        try {
            $stmt = $this->_db->query($this->fetch_book_state_query);    
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo $e->getMessage();
        exit;
        }
    }

    public function loadAllUsers(){
        try {
            $sql_query = 
                "SELECT *
                FROM users";
            $stmt = $this->_db->query($sql_query);
            return $stmt->fetchALL(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo $e->getMessage();
        exit;
        }
    }

    public function loadAllBooks(){
        try {
            $sql_query = 
                "SELECT *
                FROM books";
            $stmt = $this->_db->query($sql_query);
            return $stmt->fetchALL(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo $e->getMessage();
        exit;
        }
    }

    public function borrowBook($book_id,$user_id){
        try {
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
        } catch (\PDOException $e) {
            echo $e->getMessage();
        exit;
        }
    }

    public function returnBook($book_id){
        try {
            $this->_db->beginTransaction();
            $sql_query =
                "UPDATE borrowing_histories 
                SET can_borrow = 1
                WHERE book_id = :book_id";
            $stmt = $this->_db->prepare($sql_query);
            $stmt->execute([':book_id' => $book_id]);
            $this->_db->commit();    
        } catch (\PDOException $e) {
            echo $e->getMessage();
        exit;
        }
    }

    public function searchBook($search_query){
        try {
            $sql_query = $this->fetch_book_state_query . " WHERE title LIKE :query";
            $stmt = $this->_db->prepare($sql_query);
            if($stmt->execute([':query' => '%' . $search_query . '%'])){
                return $stmt->fetchALL(\PDO::FETCH_ASSOC);
        }else{
            return [];
        }  
        } catch (PDOException $e){
            echo $e->getMessage();
            exit;
        }
    }

    public function addUser($name){
        try {
            $this->_db->beginTransaction();
            $sql_query =
                "INSERT INTO users (name) VALUES (:user_name)";
            $stmt = $this->_db->prepare($sql_query);
            $stmt->execute([':user_name' => $name]);
            $this->_db->commit();    
        } catch (\PDOException $e) {
            echo $e->getMessage();
        exit;
        }
    }

    public function addBook($title){
        try {
            $this->_db->beginTransaction();
            $sql_query =
                "INSERT INTO books (title) VALUES (:book_title)";
            $stmt = $this->_db->prepare($sql_query);
            $stmt->execute([':book_title' => $title]);
            $this->_db->commit();    
        } catch (\PDOException $e) {
            echo $e->getMessage();
        exit;
        }
    }

    public function deleteUser($id){
        try {
            $this->_db->beginTransaction();
            $sql_query =
                "DELETE FROM users WHERE id = (:user_id)";
            $stmt = $this->_db->prepare($sql_query);
            $stmt->execute([':user_id' => $id]);
            $this->_db->commit();    
        } catch (\PDOException $e) {
            echo $e->getMessage();
        exit;
        }
    }

    public function deleteBook($id){
        try {
            $this->_db->beginTransaction();
            $sql_query =
                "DELETE FROM books WHERE id = (:book_id)";
            $stmt = $this->_db->prepare($sql_query);
            $stmt->execute([':book_id' => $id]);
            $this->_db->commit();
    
        } catch (\PDOException $e) {
            echo $e->getMessage();
        exit;
        }
    }
}