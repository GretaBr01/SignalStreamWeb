@extends('workspace.master_workspace')

@section('title', 'Live EMG & IMU')

@section('main_content')
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

{{-- Modal ingrandimento --}}
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
                const time = performance.now() / 1000; // timestamp in secondi
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

    // Inizializza i grafici
    chartRefs['emg0'] = createRealtimeChart('emg0Chart', 'EMG 0', ['red']);
    chartRefs['emg1'] = createRealtimeChart('emg1Chart', 'EMG 1', ['green']);
    chartRefs['emg2'] = createRealtimeChart('emg2Chart', 'EMG 2', ['blue']);
    chartRefs['emg3'] = createRealtimeChart('emg3Chart', 'EMG 3', ['orange']);
    chartRefs['accelerometro'] = createRealtimeChart('accChart', 'Accelerometro', ['#FF6384', '#36A2EB', '#FFCE56']);
    chartRefs['giroscopio'] = createRealtimeChart('gyrChart', 'Giroscopio', ['#4BC0C0', '#9966FF', '#FF9F40']);

    // Simula dati real-time (sostituisci con WebSocket/SSE/polling)
    setInterval(() => {
        chartRefs['emg0'].pushData([Math.random()]);
        chartRefs['emg1'].pushData([Math.random()]);
        chartRefs['emg2'].pushData([Math.random()]);
        chartRefs['emg3'].pushData([Math.random()]);

        const accX = Math.random() * 2 - 1;
        const accY = Math.random() * 2 - 1;
        const accZ = Math.random() * 2 - 1;
        chartRefs['accelerometro'].pushData([accX, accY, accZ]);

        const gyrX = Math.random() * 180 - 90;
        const gyrY = Math.random() * 180 - 90;
        const gyrZ = Math.random() * 180 - 90;
        chartRefs['giroscopio'].pushData([gyrX, gyrY, gyrZ]);
    }, 100); // aggiorna ogni 100ms (~10 Hz)
</script>
@endsection










    {{-- <h2>Connetti al Server Node.js</h2>
    <input type="text" id="serverIp" placeholder="IP del server Node.js (es. 192.168.1.14)" />
    <button onclick="connect()">Connetti</button>
    <button onclick="stopStream()">Stop & Salva CSV</button>

    <h3>Dati IMU</h3>
    <canvas id="imuChart"></canvas>

    <h3>Dati EMG</h3>
    <canvas id="emgChart"></canvas>

    <script>
        let socket;
        let imuChart, emgChart;
        let imuData = [], emgData = [];

        const imuCtx = document.getElementById('imuChart').getContext('2d');
        const emgCtx = document.getElementById('emgChart').getContext('2d');

        // Setup charts
        imuChart = new Chart(imuCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [
                    { label: 'acc_x', data: [], borderColor: 'red' },
                    { label: 'acc_y', data: [], borderColor: 'green' },
                    { label: 'acc_z', data: [], borderColor: 'blue' },
                ]
            },
            options: { responsive: true }
        });

        emgChart = new Chart(emgCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [
                    { label: 'emg0', data: [], borderColor: 'purple' },
                    { label: 'emg1', data: [], borderColor: 'orange' },
                ]
            },
            options: { responsive: true }
        });

        function connect() {
            const ip = document.getElementById('serverIp').value;
            const wsUrl = `ws://${ip}:8080`;
            socket = new WebSocket(wsUrl);

            socket.onopen = () => console.log('✅ Connesso a Node.js');

            socket.onmessage = (event) => {
                const message = JSON.parse(event.data);
                if (message.type === "packet") {
                    handleImu(message.imu);
                    handleEmg(message.emg);
                }
            };

            socket.onerror = (err) => console.error("Errore WebSocket:", err);
        }

        function handleImu(data) {
            data.forEach(d => {
                imuData.push(d);
                imuChart.data.labels.push(d.timestamp_imu);
                imuChart.data.datasets[0].data.push(d.acc_x);
                imuChart.data.datasets[1].data.push(d.acc_y);
                imuChart.data.datasets[2].data.push(d.acc_z);
            });
            imuChart.update();
        }

        function handleEmg(data) {
            data.forEach(d => {
                emgData.push(d);
                emgChart.data.labels.push(d.timestamp_emg);
                emgChart.data.datasets[0].data.push(d.emg0);
                emgChart.data.datasets[1].data.push(d.emg1);
            });
            emgChart.update();
        }

        function stopStream() {
            if (socket) socket.close();

            // Salva i CSV localmente
            saveCsv("imu_data.csv", imuData, ["timestamp_imu", "gyr_x", "gyr_y", "gyr_z", "acc_x", "acc_y", "acc_z", "pkt_time_ns"]);
            saveCsv("emg_data.csv", emgData, ["timestamp_emg", "emg0", "emg1", "emg2", "emg3", "pkt_time_ns"]);

            alert("Stream fermato e dati salvati!");
        }

        function saveCsv(filename, dataArray, headers) {
            const csvRows = [headers.join(",")];
            for (let row of dataArray) {
                const values = headers.map(h => row[h]);
                csvRows.push(values.join(","));
            }
            const csvData = new Blob([csvRows.join("\n")], { type: 'text/csv' });
            const url = window.URL.createObjectURL(csvData);

            const a = document.createElement("a");
            a.setAttribute("hidden", "");
            a.setAttribute("href", url);
            a.setAttribute("download", filename);
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
    </script> --}}

