const WebSocket = require('ws');
const net = require('net');

// === CONFIGURAZIONE ===
const WS_PORT = 8080;   // Porta WebSocket
const TCP_PORT = 9000;  // Porta TCP per ricevere dal Python

// === SERVER WEBSOCKET ===
const wss = new WebSocket.Server({ port: WS_PORT });
console.log(`WebSocket server in ascolto su ws://localhost:${WS_PORT}`);

wss.on('connection', (ws) => {
    console.log('Nuovo client WebSocket connesso');

    ws.on('message', (message) => {
        console.log('Messaggio ricevuto dal client WS:', message.toString());
    });

    ws.on('close', () => {
        console.log('Client WebSocket disconnesso');
    });
});

// === FUNZIONE DI BROADCAST ===
function broadcastToWebSocketClients(data) {
    const jsonData = typeof data === 'string' ? data : JSON.stringify(data);
    wss.clients.forEach((client) => {
        if (client.readyState === WebSocket.OPEN) {
            client.send(jsonData);
        }
    });
}

// === SERVER TCP (per ricevere da Python) ===
const tcpServer = net.createServer((socket) => {
    console.log('Client TCP connesso (Python)');

    let buffer = '';

    socket.on('data', (chunk) => {
        buffer += chunk.toString();

        // Gestione pacchetti separati da newline
        let lines = buffer.split('\n');
        buffer = lines.pop(); // tiene da parte l'ultima riga incompleta

        for (let line of lines) {
            if (line.trim() === '') continue;

            try {
                const data = JSON.parse(line);
                console.log('Ricevuto dal TCP:', data);
                broadcastToWebSocketClients(data);
            } catch (e) {
                console.error('Errore parsing JSON:', e.message);
            }
        }
    });

    socket.on('end', () => {
        console.log('Client TCP disconnesso');
    });

    socket.on('error', (err) => {
        console.error('Errore TCP:', err.message);
    });
});

tcpServer.listen(TCP_PORT, () => {
    console.log(`Server TCP in ascolto su porta ${TCP_PORT}`);
});
