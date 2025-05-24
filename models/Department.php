<?php
require_once 'config/db.php';

class Department
{
    public static function getAll()
    {
        $pdo = getPDO();
        $stmt = $pdo->query("SELECT * FROM departments");

        return $stmt->fetchAll();
    }

    public static function create($name)
    {
        $pdo = getPDO();
        try {
            $stmt = $pdo->prepare("INSERT INTO departments (name) VALUES (?)");
            $stmt->execute([$name]);
            $id = $pdo->lastInsertId();

            //  new department row
            $stmt = $pdo->prepare("SELECT * FROM departments WHERE id = ?");
            $stmt->execute([$id]);
            $department = $stmt->fetch();

            return ['success' => true, 'department' => $department];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => 'Department may already exist'];
        }
    }

    public static function update($id, $name)
    {
        $pdo = getPDO();
        try {
            //  Check if  new name already exists
            $stmt = $pdo->prepare("SELECT id FROM departments WHERE name = ? AND id != ?");
            $stmt->execute([$name, $id]);
            if ($stmt->fetch()) {
                return ['success' => false, 'error' => 'Department name already exists'];
            }

            //  Proceed update
            $stmt = $pdo->prepare("UPDATE departments SET name = ? WHERE id = ?");
            $stmt->execute([$name, $id]);

            if ($stmt->rowCount() === 0) {
                return ['success' => false, 'error' => 'Department not found or no changes made'];
            }

            //  updated department
            $stmt = $pdo->prepare("SELECT * FROM departments WHERE id = ?");
            $stmt->execute([$id]);
            $department = $stmt->fetch();

            return ['success' => true, 'department' => $department];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => 'something wrong , Department not updated'];
        }
    }

    public static function delete($id)
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare("DELETE FROM departments WHERE id = ?");
        $stmt->execute([$id]);

        return ['success' => $stmt->rowCount() > 0];
    }
}
