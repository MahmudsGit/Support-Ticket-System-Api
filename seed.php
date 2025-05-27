<?php
require_once 'config/db.php';

$pdo = getPDO();
try {
    // Insert users
    $users = [
        ['Alice Admin', 'alice@company.com', password_hash('admin123', PASSWORD_DEFAULT), 'admin'],
        ['Bob Agent', 'bob@company.com', password_hash('agent123', PASSWORD_DEFAULT), 'agent'],
        ['Charlie Client', 'charlie@company.com', password_hash('client123', PASSWORD_DEFAULT), 'agent']
    ];

    foreach ($users as $u) {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)");
        $stmt->execute($u);
    }

    // Insert departments
    $departments = ['IT Support', 'Customer Service', 'Billing'];

    foreach ($departments as $d) {
        $stmt = $pdo->prepare("INSERT INTO departments (name) VALUES (?)");
        $stmt->execute([$d]);
    }

    // Insert sample ticket
    $stmt = $pdo->prepare("INSERT INTO tickets (title, description, status, user_id, department_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        'Cannot access email',
        'My corporate email account is locked.',
        'open',
        3, // Charlie Client
        1  // IT Support
    ]);

    // Insert a ticket note
    $stmt = $pdo->prepare("INSERT INTO ticket_notes (user_id, ticket_id, note) VALUES (?, ?, ?)");
    $stmt->execute([
        2, // Bob Agent
        1, // First Ticket
        'Looking into this issue. Awaiting user response.'
    ]);

    echo "Database seeded successfully.\n";
} catch (PDOException $e) {
    echo "Seeding failed: " . $e->getMessage() . "\n";
}


