@extends('workspace.master_workspace')

@section('title', 'Live EMG & IMU')

@section('main_content')
<div class="mb-4">
    <div class="input-group">
        <span class="input-group-text">Indirizzo WebSocket</span>
        <input type="text" class="form-control" id="wsAddress" placeholder="ws://127.0.0.1:8080">
        <button class="btn btn-success" id="connectBtn">Connetti</button>
        <button class="btn btn-danger" id="disconnectBtn" disabled>Disconnetti</button>
    </div>
    <div class="mt-2">
        <span id="wsStatus" class="badge bg-secondary">Stato: Disconnesso</span>
    </div>
</div>


<div class="row g-4">
    @foreach (range(0, 3) as $i)
    <div class="col-md-6">
        <div class="chart-box" data-chart="emg{{ $i }}">
            <h5 class="text-purple">EMG Canale {{ $i }}</h5>
            <canvas id="emg{{ $i }}Chart"></canvas>
        </div>
    </div>
    @endforeach

    <div class="col-md-6">
        <div class="chart-box" data-chart="accelerometro">
            <h5 class="text-purple">Accelerometro</h5>
            <canvas id="accChart"></canvas>
        </div>
    </div>

    <div class="col-md-6">
        <div class="chart-box" data-chart="giroscopio">
            <h5 class="text-purple">Giroscopio</h5>
            <canvas id="gyrChart"></canvas>
        </div>
    </div>
</div>

<div class="modal fade" id="chartModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content position-relative">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
            <canvas id="modalChartCanvas"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const chartRefs = {};
    let socket = null;
    let emgData = [];
    let imuData = [];

    function updateStatus(text, color = 'secondary') {
        const status = document.getElementById('wsStatus');
        status.textContent = `Stato: ${text}`;
        status.className = `badge bg-${color}`;
    }

    function createRealtimeChart(id, label, colors, maxPoints = 500) {
        const ctx = document.getElementById(id).getContext('2d');
        const datasets = colors.map((color, i) => ({
            label: `Serie ${i + 1}`,
            data: [],
            borderColor: color,
            fill: false,
            pointRadius: 0,
        }));

        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: datasets
            },
            options: {
                responsive: true,
                animation: false,
                plugins: {
                    title: { display: true, text: label },
                    legend: {
                        display: true,
                        labels: { usePointStyle: true, pointStyle: 'line' }
                    }
                },
                scales: {
                    x: {
                        type: 'linear',
                        title: { display: true, text: 'Tempo (s)' }
                    },
                    y: { display: true }
                }
            }
        });

        return {
            chart,
            pushData(newValues) {
                const time = performance.now() / 1000;
                chart.data.labels.push(time);
                chart.data.datasets.forEach((ds, i) => {
                    ds.data.push({ x: time, y: newValues[i] });
                    if (ds.data.length > maxPoints) ds.data.shift();
                });
                chart.data.labels = chart.data.datasets[0].data.map(d => d.x);
                chart.update('none');
            }
        };
    }

    // Inizializza tutti i grafici
    chartRefs['emg0'] = createRealtimeChart('emg0Chart', 'EMG 0', ['red']);
    chartRefs['emg1'] = createRealtimeChart('emg1Chart', 'EMG 1', ['green']);
    chartRefs['emg2'] = createRealtimeChart('emg2Chart', 'EMG 2', ['blue']);
    chartRefs['emg3'] = createRealtimeChart('emg3Chart', 'EMG 3', ['orange']);
    chartRefs['accelerometro'] = createRealtimeChart('accChart', 'Accelerometro', ['#FF6384', '#36A2EB', '#FFCE56']);
    chartRefs['giroscopio'] = createRealtimeChart('gyrChart', 'Giroscopio', ['#4BC0C0', '#9966FF', '#FF9F40']);

    // Gestione WebSocket
    function connectWebSocket(url) {
        socket = new WebSocket(url);

        socket.onopen = () => {
            console.log("Connesso al WebSocket");
            updateStatus("Connesso", "success");
            document.getElementById('connectBtn').disabled = true;
            document.getElementById('disconnectBtn').disabled = false;
        };

        socket.onmessage = (event) => {
            try {
                const msg = JSON.parse(event.data);

                if (msg.type === 'packet') {
                    if (Array.isArray(msg.emg)) {
                        emgData.push(...msg.emg);
                        msg.emg.forEach(sample => {
                            chartRefs['emg0'].pushData([sample.emg0]);
                            chartRefs['emg1'].pushData([sample.emg1]);
                            chartRefs['emg2'].pushData([sample.emg2]);
                            chartRefs['emg3'].pushData([sample.emg3]);
                        });
                    }

                    if (Array.isArray(msg.imu)) {
                        imuData.push(...msg.imu);
                        msg.imu.forEach(sample => {
                            chartRefs['accelerometro'].pushData([sample.acc_x, sample.acc_y, sample.acc_z]);
                            chartRefs['giroscopio'].pushData([sample.gyr_x, sample.gyr_y, sample.gyr_z]);
                        });
                    }
                }
            } catch (e) {
                console.error("Errore parsing messaggio:", e);
            }
        };

        socket.onerror = (error) => {
            console.error("Errore WebSocket", error);
            updateStatus("Errore di connessione", "danger");
        };

        socket.onclose = () => {
            console.log("Connessione chiusa");
            updateStatus("Disconnesso", "secondary");
            document.getElementById('connectBtn').disabled = false;
            document.getElementById('disconnectBtn').disabled = true;
        };
    }

    function disconnectWebSocket() {
        if (socket && socket.readyState === WebSocket.OPEN) {
            socket.close();
        }
    }

    // Pulsanti
    document.getElementById('connectBtn').addEventListener('click', () => {
        const ip = document.getElementById('wsAddress').value.trim();
        if (ip.startsWith("ws://") || ip.startsWith("wss://")) {
            connectWebSocket(ip);
        } else {
            alert("Inserisci un indirizzo WebSocket valido, es: ws://localhost:8080");
        }
    });

    document.getElementById('disconnectBtn').addEventListener('click', () => {
        disconnectWebSocket();

        if (emgData.length || imuData.length) {
            // salva in localStorage
            localStorage.setItem("emgData", JSON.stringify(emgData));
            localStorage.setItem("imuData", JSON.stringify(imuData));

            // reindirizza alla view per salvataggio
            window.location.href = "/review-series";
        }
    });
</script>
@endsection
