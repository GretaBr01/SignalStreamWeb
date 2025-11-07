# SignalStreamWeb

Web platform for real-time acquisition, analysis, and storage of EMG & IMU signals.  
Users can stream sensor data via WebSocket and manage recorded signal series with categories and notes.

---

## 📌 Table of Contents

- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [Architecture](#-architecture)
- [Installation](#-installation)
- [Usage](#-usage)

---

## 🎯 Features

- **Real-time data streaming**  
  Establishes connection to a sensor via WebSocket and displays live EMG (4 channels) & IMU (accelerometer + gyroscope) charts using Chart.js.

- **Stream recording**  
  Session data can be stopped, saved as CSV, tagged with categories, and annotated with user notes.

- **Series management**  
  Users can browse, filter, download, edit notes, and delete their recorded series. Admins can manage all users’ series and categories.

- **Category administration**  
  Admins can create, edit, or delete categories (title, description, and an optional image).

- **Role-based access**  
  Two roles supported:  
  - *User*: create, view, and manage their own series  
  - *Admin*: full control including user series and category management

---

## 🧰 Tech Stack

| Component      | Framework/Tool                |
|----------------|-------------------------------|
| **Backend**    | Laravel 10 (PHP)              |
| **Frontend**   | Blade, Bootstrap 5, Chart.js  |
| **Real-time**  | Node.js WebSocket server      |
| **Data ingest**| Python TCP bridge             |
| **Database**   | MySQL / SQLite (Eloquent ORM) |
| **Storage**    | Laravel storage (private CSV) |

---

## 🏗️ Architecture

1. **Microcontroller → Python TCP Bridge**  
   Listens on a socket, parses raw sensor packets, and forwards them as JSON to the Node WebSocket server.

2. **Node.js WebSocket Server**  
   Broadcasts the incoming sensor data to connected Laravel clients.

3. **Laravel Client**  
   Connects to the WebSocket server (using IP provided by the user), visualizes real-time charts, and buffers data locally.

4. **Laravel API**  
   Handles CRUD for series: create, retrieve, update notes, download CSV, delete.

---

## ⚙️ Installation

### 1. Clone the repository

```bash
git clone https://github.com/GretaBr01/SignalStreamWeb.git
cd SignalStreamWeb/laravel
composer install
cd SignalStreamWeb/ws_server
npm install
```

### 2. Environment setup

Copy `.env.example` and configure database and WebSocket:

```bash
cp .env.example .env
```

Edit `.env`:
```
DB_CONNECTION=mysql
DB_DATABASE=your_db
DB_USERNAME=your_user
DB_PASSWORD=your_password

WEBSOCKET_SERVER_URL=ws://192.168.xxx.xxx:8080
```

### 3. Key and migrations

```bash
php artisan key:generate
php artisan migrate
```

### 5. Start services

```bash
php artisan serve          # Laravel backend
node websocket-server.js   # Node.js WebSocket server
python tcp_bridge.py       # Python TCP → WebSocket bridge
```

---

## 🚀 Usage

1. Visit [http://127.0.0.1:8000](http://127.0.0.1:8000)
2. Register or log in
3. Go to **Real-time Acquisition**
4. Enter your WebSocket server IP and click **Connect**
5. View EMG/IMU live graphs and click **Stop** to end session
6. Fill in notes/category and click **Save**
7. Browse **My Series** to:
   - Filter
   - View series
   - Download EMG/IMU CSV
   - Edit notes
   - Delete series
8. Admins have access to:
   - All users’ series
   - Category creation/editing/deletion
(versione PHP 8.2.14)
