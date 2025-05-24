<?php
require_once 'models/User.php';
require_once 'helpers/Response.php';
require_once 'helpers/Auth.php';

class UserController {
    public static function register() {
        $data = json_decode(file_get_contents("php://input"), true);

        $requiredFields = ['name', 'email', 'password', 'role'];
        $missingFields = [];

        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                $missingFields[] = $field;
            }
        }

        if (!empty($missingFields)) {
            return Response::json([
                'error' => 'Missing required fields',
                'missing_fields' => $missingFields
            ], 400);
        }

        $result = User::create($data);
        
        return Response::json($result, $result['success'] ? 201 : 400);
    }

    public static function login() {
        $data = json_decode(file_get_contents("php://input"), true);
        $result = User::login($data);
        
        return Response::json($result, isset($result['token']) ? 200 : 401);
    }

}
