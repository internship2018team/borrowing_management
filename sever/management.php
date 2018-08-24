<?php

namespace MyApp;

class Management {
    private $_db;

    public function __construct() {
        try {
            $this->_db = new \PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
            $this->_db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public function get_latest_book_status(){
        $sql_query = 
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
            ON bh.book_id = books.id";
        $stmt = $this->_db->query($sql_query);
        return $stmt->fetchALL(\PDO::FETCH_ASSOC);
    }

    public function get_all_users(){
        $sql_query = "SELECT * FROM users";
        $stmt = $this->_db->query($sql_query);
        return $stmt->fetchALL(\PDO::FETCH_ASSOC);
    }

    public function borrow_book(){
        try {
            $this->_db->beginTransaction();
            $sql_query = "INSERT INTO borrowing_histories (book_id, user_id, borrowable) VALUES (:book_id, :user_id, :borrowable)";
            $stmt = $this->_db->prepare($sql_query);
            $stmt->execute([
                ':book_id' => $_POST["book_id"],
                ':user_id' => $_POST["user_id"],
                ':borrowable' => 0]);
            $this->_db->commit();    
        } catch(\PDOException $e){
            $this->_db->rollback();
            echo $e->getMessage();
            exit;
        }
    }

    public function return_book(){
        try {
            $this->_db->beginTransaction();
            $sql_query = "UPDATE borrowing_histories SET borrowable = 1 WHERE id = :history_id";
            $stmt = $this->_db->prepare($sql_query);
            $stmt->execute([':history_id' => $_POST["history_id"]]);
            $this->_db->commit();    
        } catch(\PDOException $e){
            $this->_db->rollback();
            echo $e->getMessage();
            exit;
        }
    }
}