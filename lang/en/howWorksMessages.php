<?php

return [
    'title' => 'How the site works',
    'intro' => 'Welcome to our real-time <strong>EMG and IMU signal acquisition and visualization system</strong>. This platform is designed for users collecting biomedical data for research, analysis, or gesture classification, especially in cyclist-related scenarios.',
    'acquisition' => '📡 Real-time acquisition',
    'acquisition_description' => 'You can connect to a device (like an EMG/IMU sensor) by specifying its IP address. Once connected, you will start receiving real-time data displayed on dynamic charts:',
    'emg' => '<strong>EMG (electromyography):</strong> up to 4 channels, acquired from muscles like biceps and triceps',
    'imu' => '<strong>IMU (inertial measurement unit):</strong> accelerometer ±4g and gyroscope ±2000 dps',
    'hardware_note' => 'The system is based on <strong>Arduino Nano RP2040 Connect</strong> for acquisition and <strong>Raspberry Pi 3</strong> for real-time processing and classification.',

    'saving' => '💾 Data saving',
    'saving_description' => 'During acquisition, you can stop the session at any time. If data has been collected, you will be prompted to:',
    'gesture_category' => 'Select the <strong>gesture category</strong> performed',
    'personal_notes' => 'Add optional <strong>personal notes</strong>',
    'saving_format' => 'EMG and IMU data are saved in CSV format and linked to your user profile.',

    'history' => '📂 Series history',
    'history_description' => 'In the "My Series" section you can:',
    'history_items' => [
        'View all saved series',
        'Filter by category or user (if admin)',
        'Download data CSV files',
        'Edit associated notes',
        'Delete unused series',
    ],

    'recognition' => '🤖 Gesture recognition',
    'recognition_description' => 'The system can recognize <strong>common arm gestures used by cyclists</strong> to signal maneuvers such as:',
    'gestures' => [
        'Turn right / left',
        'Stop or slow down',
        'Obstacle warning (potholes, debris)',
        'Incoming danger',
    ],
    'recognition_accuracy' => 'Classification is performed using <strong>machine learning</strong> models (TensorFlow Lite) trained on a custom dataset. The system achieves <strong>96% accuracy</strong> using only EMG signals from biceps and triceps.',

    'who' => '👤 Who can use the site?',
    'who_description' => 'The site is accessible only via authentication. There are two roles:',
    'roles' => [
        '<strong>User:</strong> can record, save, and view their own series',
        '<strong>Admin:</strong> can view and manage all users and series',
    ],

    'hardware' => '⚙️ Hardware components',
    'hardware_list' => [
        '<strong>Arduino Nano RP2040 Connect</strong>: MCU with Wi-Fi, Bluetooth, and integrated IMU',
        '<strong>Olimex Shield EKG/EMG</strong>: adapter for electrodes and filtering',
        '<strong>Raspberry Pi 3 Model B</strong>: processing and classification unit',
    ],
    'hardware_note_2' => 'The system is fully wireless and battery-powered for portability and ease of use while in motion.',

    'support' => '📞 Support',
    'support_text' => 'For assistance or information, please contact the system administrator.',

    'architecture' => '🖼️ System architecture',
    'architecture_description' => 'The EMGesture system consists of multiple wearable modules: an acquisition unit with Arduino Nano RP2040, EMG Olimex board, IMU module, and a Raspberry Pi 3 for processing.',
    'architecture_caption' => 'Figure: EMGesture system architecture (source: I2MTC 2025 poster)',
    'architecture_cta' => 'You can explore the code, technical documentation, and dataset examples in the official repository:',
    'github_button' => '🔗 Go to GitHub Documentation',
];
