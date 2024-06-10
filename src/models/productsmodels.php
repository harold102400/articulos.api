<?php

namespace Api\models;

use Api\database\ConnectDb;
use \PDO;

class ProductsModels {

    public $tableName = "articulos";
    public $conn;

    public function __construct()
    {
        $instance = ConnectDb::getInstance();
        $this->conn = $instance->getConnection();
    }

    public function getAllProducts(){
        $sql = "SELECT * FROM $this->tableName";
        $result = $this->conn->query($sql);
        if ($result) {
            $data = $result->fetchAll(PDO::FETCH_ASSOC);
            $countSql = "SELECT COUNT(*) as total FROM $this->tableName";
            $result = $this->conn->query($countSql);
            $totalRecords = $result->fetch(PDO::FETCH_ASSOC)['total'];
            return [
                'data' => $data,
                'totalRecords' => $totalRecords
            ];
        }
    }
///Modificada funcion de crear para que devuelva el codigo que se ha creado
    public function create($data)
    {
        $sql = "INSERT INTO $this->tableName(codigo, nombre, tipo, marca, precio) VALUES (:codigo, :nombre, :tipo, :marca, :precio)";
        $result = $this->conn->prepare($sql);
        if ($result) {
            $result->execute([
                ":codigo" => $data["codigo"],
                ":nombre" => $data["nombre"],
                ":tipo" => $data["tipo"],
                ":marca" => $data["marca"],
                ":precio" => $data["precio"]
            ]);
            return $this->getCodeFromDB($data["codigo"]);
        } else {
            echo "err";
        }
    }

    public function getCodeFromDB(string $code)
    {
        $sql = "SELECT * FROM $this->tableName WHERE codigo = :codigo";
        $result = $this->conn->prepare($sql);
        $result->execute([":codigo" => $code]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function getProduct($id)
    {
        $sql = "SELECT * FROM $this->tableName WHERE id=:id";
        $result = $this->conn->prepare($sql);
        if ($result) {
            $result->execute([":id" => $id]);
            $data = $result->fetch(PDO::FETCH_ASSOC);
            return $data;
        } else {
            echo "err";
        }
    }

    public function update($data)
    {
        $sql = "UPDATE $this->tableName SET codigo=:codigo, nombre=:nombre, tipo=:tipo, marca=:marca, precio=:precio WHERE id=:id";
        $result = $this->conn->prepare($sql);
        if ($result) {
            $result->execute([
                "id"=> $data["id"],
                ":codigo" => $data["codigo"],
                ":nombre" => $data["nombre"],
                ":tipo" => $data["tipo"],
                ":marca" => $data["marca"],
                ":precio" => $data["precio"]
            ]);
        }else {
            echo "err";
        }
    }

    public function delete($id)
    {
        $sql = "DELETE FROM $this->tableName WHERE id=:id";
        $result = $this->conn->prepare($sql);
        if ($result) {
            $result->execute([
                "id" => $id
            ]);
        }else {
            echo "err";
        }
    }
}