import socketserver
import struct
import csv
import os
import time
from datetime import datetime
import websocket
import json
import threading
import socket

# CONFIGURAZIONE
IP = "192.168.1.14"
PORT = 6789
# WS_SERVER_URL = "ws://localhost:8080"  # modifica con l'indirizzo reale del server web

# FORMATI STRUTTURE DATI
IMU_STRUCT_FORMAT = "<I6s6s"
ADC_STRUCT_FORMAT = "<I6s"
NUM_STRUCT_FORMAT = "<I"

N_IMU_SAMPLE_PACKET = 12
N_ADC_SAMPLE_PACKET = 58

IMU_SAMPLE_SIZE = struct.calcsize(IMU_STRUCT_FORMAT)
ADC_SAMPLE_SIZE = struct.calcsize(ADC_STRUCT_FORMAT)
NUM_PKT_SIZE = struct.calcsize(NUM_STRUCT_FORMAT)

PAYLOAD_SIZE = (IMU_SAMPLE_SIZE * N_IMU_SAMPLE_PACKET) + (ADC_SAMPLE_SIZE * N_ADC_SAMPLE_PACKET)
TOTAL_PACKET_SIZE = NUM_PKT_SIZE + PAYLOAD_SIZE + NUM_PKT_SIZE

# # WEBSOCKET CLIENT
# class WebSocketClient:
#     def __init__(self, url):
#         self.url = url
#         self.ws = None
#         self.lock = threading.Lock()
#         self.connect()

#     def connect(self):
#         try:
#             self.ws = websocket.create_connection(self.url)
#             print(f"[WebSocket] Connesso a {self.url}")
#         except Exception as e:
#             print(f"[WebSocket] Errore di connessione: {e}")
#             self.ws = None

#     def send_json(self, data):
#         if not self.ws:
#             self.connect()
#         try:
#             with self.lock:
#                 if self.ws:
#                     self.ws.send(json.dumps(data))
#         except Exception as e:
#             print(f"[WebSocket] Errore invio: {e}")
#             self.ws = None

# ws_client = WebSocketClient(WS_SERVER_URL)

class TCPClient:
    def __init__(self, host, port):
        self.host = host
        self.port = port
        self.sock = None
        self.lock = threading.Lock()
        self.connect()

    def connect(self):
        try:
            self.sock = socket.create_connection((self.host, self.port))
            print(f"[TCP Client] Connesso a {self.host}:{self.port}")
        except Exception as e:
            print(f"[TCP Client] Connessione fallita: {e}")
            self.sock = None

    def send_json(self, data):
        if not self.sock:
            self.connect()
        try:
            with self.lock:
                if self.sock:
                    message = json.dumps(data).encode('utf-8') + b'\n'  # newline per separare i pacchetti
                    self.sock.sendall(message)
        except Exception as e:
            print(f"[TCP Client] Errore invio: {e}")
            self.sock = None



# UTILITY
def myget_time():
    return datetime.now().strftime('%Y%m%d_%H%M%S')

def get_timestamp():
    return time.time_ns()

def log_message(log_file, message):
    log_file.write(f"{myget_time()} ns - {message}\n")
    log_file.flush()

def decode_imu_sample(data):
    timestamp = struct.unpack("<I", data[:4])[0]
    gyr_x, gyr_y, gyr_z = struct.unpack("<HHH", data[4:10])
    acc_x, acc_y, acc_z = struct.unpack("<HHH", data[10:16])
    return (timestamp, gyr_x, gyr_y, gyr_z, acc_x, acc_y, acc_z)

def decode_adc_sample(data):
    timestamp, packed_adc = struct.unpack(ADC_STRUCT_FORMAT, data)
    b = list(packed_adc)
    adc0 = b[0] | ((b[1] & 0x0F) << 8)
    adc1 = ((b[1] >> 4) & 0x0F) | (b[2] << 4)
    adc2 = b[3] | ((b[4] & 0x0F) << 8)
    adc3 = ((b[4] >> 4) & 0x0F) | (b[5] << 4)
    return (timestamp, adc0, adc1, adc2, adc3)

# OUTPUT FILE
os.makedirs("datiServerTCP/dati_imu", exist_ok=True)
os.makedirs("datiServerTCP/dati_adc", exist_ok=True)
os.makedirs("datiServerTCP/packet_bits", exist_ok=True)
os.makedirs("datiServerTCP/logfile", exist_ok=True)

t = myget_time()
imu_file = open(f"datiServerTCP/dati_imu/imu_{t}.csv", "w", newline="")
adc_file = open(f"datiServerTCP/dati_adc/adc_{t}.csv", "w", newline="")
pkt_file = open(f"datiServerTCP/packet_bits/packet_bits_{t}.txt", "w")
log_file = open(f"datiServerTCP/logfile/log_{t}.log", "w")

imu_writer = csv.writer(imu_file)
adc_writer = csv.writer(adc_file)
imu_writer.writerow(["timestamp", "gyr_x", "gyr_y", "gyr_z", "acc_x", "acc_y", "acc_z", "pkt_time_ns"])
adc_writer.writerow(["timestamp", "adc0", "adc1", "adc2", "adc3", "pkt_time_ns"])

# TCP HANDLER
class MyTCPHandler(socketserver.BaseRequestHandler):
    def handle(self):
        buffer = b""
        log_message(log_file, f"Connessione da {self.client_address[0]}")
        try:
            while True:
                chunk = self.request.recv(NUM_PKT_SIZE*2)
                if not chunk:
                    log_message(log_file, "Pacchetto vuoto, connessione interrotta.")
                    break
                buffer += chunk
                while len(buffer) >= TOTAL_PACKET_SIZE:
                    block = buffer[:TOTAL_PACKET_SIZE]
                    num_pkt = struct.unpack(NUM_STRUCT_FORMAT, block[:NUM_PKT_SIZE])[0]
                    num_pkt_neg = struct.unpack(NUM_STRUCT_FORMAT, block[-NUM_PKT_SIZE:])[0]
                    if num_pkt_neg != (~num_pkt & 0xFFFFFFFF):
                        log_message(log_file, f"Pacchetto scartato: num_pkt={num_pkt}, negato={num_pkt_neg}")
                        buffer = buffer[1:]
                        continue
                    packet_data = block[NUM_PKT_SIZE:-NUM_PKT_SIZE]
                    buffer = buffer[TOTAL_PACKET_SIZE:]
                    pkt_file.write(''.join(f"{byte:08b}" for byte in packet_data) + '\n')
                    pkt_file.flush()
                    time_pkt = get_timestamp()

                    try:
                        imu_list = []
                        for i in range(N_IMU_SAMPLE_PACKET):
                            offset = i * IMU_SAMPLE_SIZE
                            imu_data = decode_imu_sample(packet_data[offset:offset + IMU_SAMPLE_SIZE])
                            imu_writer.writerow([*imu_data, time_pkt])
                            imu_list.append({
                                "timestamp_imu": imu_data[0],
                                "gyr_x": imu_data[1],
                                "gyr_y": imu_data[2],
                                "gyr_z": imu_data[3],
                                "acc_x": imu_data[4],
                                "acc_y": imu_data[5],
                                "acc_z": imu_data[6],
                                "pkt_time_ns": time_pkt
                            })
                            if i == 0:
                                time_imu = imu_data[0]
                        # ws_client.send_json({"type": "imu", "data": imu_list})
                    except Exception as e:
                        log_message(log_file, f"Errore decoding IMU: {e}")

                    try:
                        adc_list = []
                        adc_offset_start = N_IMU_SAMPLE_PACKET * IMU_SAMPLE_SIZE
                        for i in range(N_ADC_SAMPLE_PACKET):
                            offset = adc_offset_start + i * ADC_SAMPLE_SIZE
                            adc_data = decode_adc_sample(packet_data[offset:offset + ADC_SAMPLE_SIZE])
                            adc_writer.writerow([*adc_data, time_pkt])
                            adc_list.append({
                                "timestamp_emg": adc_data[0],
                                "emg0": adc_data[1],
                                "emg1": adc_data[2],
                                "emg2": adc_data[3],
                                "emg3": adc_data[4],
                                "pkt_time_ns": time_pkt
                            })
                            if i == 0:
                                time_adc = adc_data[0]
                        # ws_client.send_json({"type": "adc", "data": adc_list})
                    except Exception as e:
                        log_message(log_file, f"Errore decoding ADC: {e}")

                    tcp_client.send_json({
                        "type": "packet",
                        "imu": imu_list,
                        "emg": adc_list
                    })
                    log_message(log_file, f"Pacchetto valido ricevuto. Size={len(packet_data)}, num_pkt={num_pkt}, t_Rpi={time_pkt}, t_IMU={time_imu}, t_ADC={time_adc}")
                    print(f"Pacchetto valido ricevuto. Size={len(packet_data)}, num_pkt={num_pkt}")

        except Exception as e:
            log_message(log_file, f"Errore generico: {e}")

# AVVIO SERVER
if __name__ == "__main__":
    # Inizializza TCP Client verso server Node.js
    tcp_client = TCPClient("192.168.1.14", 9000)
    with socketserver.TCPServer((IP, PORT), MyTCPHandler) as server:
        log_message(log_file, f"Server avviato su {IP}:{PORT}")
        try:
            server.serve_forever()
        except KeyboardInterrupt:
            log_message(log_file, "Interruzione manuale (Ctrl+C)")
        finally:
            log_file.close()
            imu_file.close()
            adc_file.close()
            pkt_file.close()
            server.server_close()
