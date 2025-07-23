<?php
header('Content-Type: application/json');
require_once '../includes/config.php';

// Fetch all buildings
$buildings = $pdo->query("SELECT * FROM buildings ORDER BY building_name")->fetchAll(PDO::FETCH_ASSOC);

$result = ['buildings' => []];

foreach ($buildings as $building) {
    // Fetch floors for this building
    $floors = $pdo->prepare("SELECT * FROM floors WHERE building_id = ? ORDER BY floor_number");
    $floors->execute([$building['building_id']]);
    $floors = $floors->fetchAll(PDO::FETCH_ASSOC);

    $floorArr = [];
    foreach ($floors as $floor) {
        // Fetch rooms for this floor
        $rooms = $pdo->prepare("SELECT * FROM rooms WHERE floor_id = ? ORDER BY room_number");
        $rooms->execute([$floor['floor_id']]);
        $rooms = $rooms->fetchAll(PDO::FETCH_ASSOC);

        $roomArr = [];
        if (count($rooms) === 0) {
            // Add a blank room box if no rooms
            $roomArr[] = [
                'number'      => '',
                'status'      => 'empty',
                'capacity'    => '',
                'occupied'    => '',
                'assigned_to' => null
            ];
        } else {
            foreach ($rooms as $room) {
                // Get allocation info if occupied
                $assigned_to = null;
                if ($room['status'] === 'occupied') {
                    $alloc = $pdo->prepare("
                        SELECT u.full_name 
                        FROM allocations a 
                        JOIN users u ON a.student_id = u.id 
                        WHERE a.room_id = ? AND a.status = 'active' LIMIT 1
                    ");
                    $alloc->execute([$room['room_id']]);
                    $assigned_to = $alloc->fetchColumn();
                }
                $roomArr[] = [
                    'number'      => $room['room_number'],
                    'status'      => $room['status'], // available, occupied, maintenance
                    'capacity'    => $room['capacity'],
                    'occupied'    => $room['occupied'],
                    'assigned_to' => $assigned_to
                ];
            }
        }
        $floorArr[] = [
            'number' => $floor['floor_number'],
            'rooms'  => $roomArr
        ];
    }
    // If no floors, add a blank floor box
    if (count($floors) === 0) {
        $floorArr[] = [
            'number' => '',
            'rooms'  => [[
                'number'      => '',
                'status'      => 'empty',
                'capacity'    => '',
                'occupied'    => '',
                'assigned_to' => null
            ]]
        ];
    }
    $result['buildings'][] = [
        'name'   => $building['building_name'],
        'floors' => $floorArr
    ];
}

// If no buildings, add a blank building box
if (count($buildings) === 0) {
    $result['buildings'][] = [
        'name'   => '',
        'floors' => [[
            'number' => '',
            'rooms'  => [[
                'number'      => '',
                'status'      => 'empty',
                'capacity'    => '',
                'occupied'    => '',
                'assigned_to' => null
            ]]
        ]]
    ];
}

echo json_encode($result);