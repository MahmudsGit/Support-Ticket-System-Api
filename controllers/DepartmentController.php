<?php
require_once 'models/Department.php';
require_once 'helpers/Response.php';
require_once 'helpers/Auth.php';

class DepartmentController {
    public static function index() {
        $departments = Department::getAll();
        
        return Response::json($departments);
    }

    public static function create() {
        $data = json_decode(file_get_contents("php://input"), true);
    
        if (empty($data['name'])) {
            return Response::json(['error' => 'Department name is required'], 400);
        }
    
        $result = Department::create($data['name']);
        if (!$result['success']) {
            return Response::json(['error' => $result['error'] ?? 'Failed to create department'], 400);
        }
    
        return Response::json($result['department'], 201);
    }
    
    public static function update($id) {
        $data = json_decode(file_get_contents("php://input"), true);
    
        if (empty($data['name'])) {
            return Response::json(['error' => 'Department name is required'], 400);
        }
    
        $result = Department::update($id, $data['name']);
        if (!$result['success']) {
            return Response::json(['error' => 'Department not found or no changes made'], 404);
        }
    
        return Response::json($result['department'], 200);
    }  

    public static function delete($id) {
        $result = Department::delete($id);

        return Response::json($result, $result['success'] ? 200 : 404);
    }
}
