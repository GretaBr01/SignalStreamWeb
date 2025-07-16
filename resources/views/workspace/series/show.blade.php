@extends('workspace.master_workspace')

@section('title', 'Le mie serie')

@section('main_content')
  <div class="mb-4">
    <button onclick="window.history.back()" class="btn btn-outline-purple rounded-pill px-4">
      ← Torna indietro
    </button>
  </div>
  <div class="row g-4">
    <div class="col-md-6">
      <div class="chart-box" data-chart="emg0">
        <h5 class="text-purple">EMG Canale 0</h5>
        <canvas id="emg0Chart"></canvas>
      </div>
    </div>
    <div class="col-md-6">
      <div class="chart-box" data-chart="emg1">
        <h5 class="text-purple">EMG Canale 1</h5>
        <canvas id="emg1Chart"></canvas>
      </div>
    </div>
    <div class="col-md-6">
      <div class="chart-box" data-chart="emg2">
        <h5 class="text-purple">EMG Canale 2</h5>
        <canvas id="emg2Chart"></canvas>
      </div>
    </div>
    <div class="col-md-6">
      <div class="chart-box" data-chart="emg3">
        <h5 class="text-purple">EMG Canale 3</h5>
        <canvas id="emg3Chart"></canvas>
      </div>
    </div>
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
  const chartRefs = {}; // Per salvare i riferimenti dei grafici

  function fetchAndParseCsv(url, callback) {
    fetch(url)
      .then(res => res.text())
      .then(csv => {
        const lines = csv.split('\n').filter(Boolean);
        const headers = lines[0].split(',');
        const data = lines.slice(1).map(line => {
          const parts = line.split(',');
          const row = {};
          headers.forEach((h, i) => row[h.trim()] = parseFloat(parts[i]));
          return row;
      });
      callback(data);
    });
  }

  // Modifica plotChart per restituire il chart creato
  function plotChart(id, label, keys, data, freq, colors) {
    let second = 1/freq;
    const time = data.map((_, i) => Number((i * second).toFixed(2)));
    const extract = key => data.map(row => row[key]);
    const datasets = keys.map((key, i) => ({
      label: key,
      data: extract(key),
      borderColor: colors[i],
      fill: false,
      pointRadius: 0,
    }));

    const chart = new Chart(document.getElementById(id), {
      type: 'line',
      data: {
        labels: time,
        datasets: datasets
      },
      options: {
        responsive: true,
        plugins: {
          title: { display: true, text: label },
          legend: {
              display: true,
              labels: {
                  usePointStyle: true,
                  pointStyle: 'line'
              }
          }
        },
        scales: {
          x: { display: true, title: { display: true, text: 'Time (second)' } },
          y: { display: true }
        }
      }
    });
    return chart;
  }

  // Inizializza i grafici e salva i riferimenti
  fetchAndParseCsv("/ajax/series/{{ $serie->id }}/emg", function(data) {
      chartRefs['emg0'] = plotChart('emg0Chart', 'EMG 0', ['emg0'], data, 1000, ['red']);
      chartRefs['emg1'] = plotChart('emg1Chart', 'EMG 1', ['emg1'], data, 1000, ['green']);
      chartRefs['emg2'] = plotChart('emg2Chart', 'EMG 2', ['emg2'], data, 1000, ['blue']);
      chartRefs['emg3'] = plotChart('emg3Chart', 'EMG 3', ['emg3'], data, 1000, ['orange']);
  });

  fetchAndParseCsv("/ajax/series/{{ $serie->id }}/imu", function(data) {
      chartRefs['accelerometro'] = plotChart('accChart', 'Accelerometro (acc)', ['acc_x', 'acc_y', 'acc_z'], data, 208, ['#FF6384', '#36A2EB', '#FFCE56']);
      chartRefs['giroscopio'] = plotChart('gyrChart', 'Giroscopio (gyr)', ['gyr_x', 'gyr_y', 'gyr_z'], data, 208, ['#4BC0C0', '#9966FF', '#FF9F40']);
  });

  // Modal Chart.js setup
  let modalChart;
  const modalCanvas = document.getElementById('modalChartCanvas');
  const modal = new bootstrap.Modal(document.getElementById('chartModal'));

  // Apri modal e crea grafico ingrandito al click
  document.querySelectorAll('.chart-box').forEach(box => {
    box.addEventListener('click', () => {
      const chartId = box.getAttribute('data-chart');
      const originalChart = chartRefs[chartId];
      if (!originalChart) return;

      if (modalChart) modalChart.destroy();

      const originalTitle = originalChart.options.plugins?.title?.text;
      const titleText = (typeof originalTitle === 'string' ? originalTitle : '');

      // Copia pulita delle scale
      const originalScales = originalChart.options.scales || {};
      function safeText(text) {
        return (typeof text === 'string') ? text : '';
      }

      const newScales = {};
      if (originalScales.x) {
        newScales.x = {
          display: originalScales.x.display ?? true,
          title: {
            display: originalScales.x.title?.display ?? false,
            text: safeText(originalScales.x.title?.text)
          }
        };
      }
      if (originalScales.y) {
        newScales.y = {
          display: originalScales.y.display ?? true,
          beginAtZero: originalScales.y.beginAtZero ?? false,
          title: {
            display: originalScales.y.title?.display ?? false,
            text: safeText(originalScales.y.title?.text)
          }
        };
      }

      // Copia pulita della legenda
      const originalLegend = originalChart.options.plugins.legend || {};
      const newLegend = {
        display: originalLegend.display ?? true,
        labels: {
          usePointStyle: originalLegend.labels?.usePointStyle ?? true,
          pointStyle: originalLegend.labels?.pointStyle ?? 'line'
        }
      };

      // Clona dati
      const clonedData = {
        labels: [...originalChart.data.labels],
        datasets: originalChart.data.datasets.map(ds => ({
          ...ds,
          data: [...ds.data]
        }))
      };

      modalChart = new Chart(modalCanvas.getContext('2d'), {
        type: originalChart.config.type,
        data: clonedData,
        options: {
          ...originalChart.options,
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            title: { display: true, text: titleText },
            legend: newLegend,
          },
          scales: newScales
        }
      });

      modal.show();
    });
  });
</script>

@endsection