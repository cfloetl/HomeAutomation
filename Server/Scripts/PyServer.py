#!/usr/bin/env python
import threading
import SocketServer
import time
import json
import MySQLdb


class ThreadedTCPRequestHandler(SocketServer.BaseRequestHandler):
    def handle(self):
        received_data = self.request.recv(1024)
        decoded_data = json.loads(received_data)
        relay_id = decoded_data["Relay"][0]["Id"]
        relay_state = decoded_data["Relay"][0]["State"]

        change_relay_state(relay_id, relay_state)


class ThreadedTCPServer(SocketServer.ThreadingMixIn, SocketServer.TCPServer):
    pass


def change_relay_state(relay_id, relay_state):
    log_relay_state(relay_id, relay_state)


def log_relay_state(relay_id, relay_state):
    cursor = connection.cursor()
    args = (relay_id, relay_state)
    cursor.callproc('Relay_LogState', args)
    cursor.close()
    connection.commit()
    print('Relay ID: ' + str(id) + ' Set to ' + str(relay_state))

if __name__ == "__main__":
    HOST, PORT = "localhost", 2453

    server = ThreadedTCPServer((HOST, PORT), ThreadedTCPRequestHandler)
    ip, port = server.server_address

    # Start a thread with the server -- that thread will then start one
    # more thread for each request
    server_thread = threading.Thread(target=server.serve_forever)
    # Exit the server thread when the main thread terminates
    server_thread.daemon = True
    server_thread.start()
    print("Server Started:")

    connection = MySQLdb.connect(hos='localhost',
                                 user='WebControl',
                                 passwd='WebControler1234',
                                 db='HOME')

    print("MySQL Connected")

    while 1 == 1:
        time.sleep(2)

    connection.close()