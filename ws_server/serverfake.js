const WebSocket = require('ws');

// Crea il server WebSocket
const PORT = 8080; // Porta del server WebSocket
const wss = new WebSocket.Server({ port: PORT });

console.log(`WebSocket server in ascolto su ws://localhost:${PORT}`);

// Funzione per generare dati simulati IMU
function generateIMUData() {
  return {
    timestamp: Date.now(),
    gyr_x: Math.floor(Math.random() * 201 - 100),
    gyr_y: Math.floor(Math.random() * 201 - 100),
    gyr_z: Math.floor(Math.random() * 201 - 100),
    acc_x: Math.floor(Math.random() * 401),
    acc_y: Math.floor(Math.random() * 401),
    acc_z: Math.floor(Math.random() * 401),
    pkt_time_ns: Date.now() * 1_000_000
  };
}

// Funzione per generare dati simulati EMG
function generateEMGData() {
  return {
    timestamp: Date.now(),
    emg0: Math.floor(Math.random() * 1024),
    emg1: Math.floor(Math.random() * 1024),
    emg2: Math.floor(Math.random() * 1024),
    emg3: Math.floor(Math.random() * 1024),
    pkt_time_ns: Date.now() * 1_000_000
  };
}

// Gestione connessioni
wss.on('connection', function connection(ws) {
  console.log('Nuovo client connesso');

  ws.on('message', function incoming(message) {
    console.log('Ricevuto dal client:', message.toString());
  });

  // Invio dati JSON simulati ogni secondo
  const interval = setInterval(() => {
    const data = {
      type: "packet",
      imu: [generateIMUData(), generateIMUData()],
      emg: [generateEMGData(), generateEMGData()]
    };

    ws.send(JSON.stringify(data));
  }, 100);

  ws.on('close', () => {
    console.log('Client disconnesso');
    clearInterval(interval);
  });
});
