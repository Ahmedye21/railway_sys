<?php 

// In your backend index.php or similar file
function getTrainsByStation($stationName) {
    // In a real application, this would query your database
    // Here's a mock implementation with sample data
    
    // Sanitize input
    $stationName = trim($stationName);
    
    // Sample data - replace with actual database query
    $sampleData = [
        [
            'number' => '1234',
            'name' => 'Express Superfast',
            'origin' => 'New Delhi',
            'destination' => 'Mumbai Central',
            'scheduled_time' => '14:30',
            'estimated_time' => '14:35',
            'status' => 'Delayed',
            'platform' => '2'
        ],
        [
            'number' => '5678',
            'name' => 'Rajdhani Express',
            'origin' => 'Howrah',
            'destination' => 'New Delhi',
            'scheduled_time' => '15:15',
            'estimated_time' => '15:15',
            'status' => 'On Time',
            'platform' => '5'
        ],
        [
            'number' => '9012',
            'name' => 'Shatabdi Express',
            'origin' => 'Chennai Central',
            'destination' => 'Bangalore City',
            'scheduled_time' => '16:45',
            'estimated_time' => 'Cancelled',
            'status' => 'Cancelled',
            'platform' => 'N/A'
        ]
    ];
    
    // In a real app, filter by station name from database
    // For this example, we'll just return the sample data
    return $sampleData;
}

