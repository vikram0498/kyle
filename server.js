const express = require('express');
const http = require('http');
const socketIo = require('socket.io');

const app = express();
const server = http.createServer(app);

const io = socketIo(server, {
    // cors: { origin: "http://localhost:8000" } // Ensure this matches your frontend URL
    // cors: { origin: "http://192.168.1.24:8000" } // Ensure this matches your frontend URL
    cors: {
        origin: "*", // Add all allowed origins
        methods: ["GET", "POST"],
        allowedHeaders: ["Content-Type"],
        credentials: true, // If credentials (cookies, headers) are needed
      },
});

let sockets = {};  // Store socket connections by channel

// Listen for connection events
io.on('connection', (socket) => {
    console.log('A user connected: ' + socket.id);

    // Store socket reference by channel
    socket.on('join-channel', (channel) => {
        console.log(`User joined channel: ${channel}`);
        if (!sockets[channel]) {
            sockets[channel] = [];
        }
        sockets[channel].push(socket);

        console.log(`User joined channel: ${channel}`);
    });

    socket.on('disconnect', () => {
        console.log('User disconnected: ' + socket.id);
        // Clean up the socket from channels
        for (let channel in sockets) {
            sockets[channel] = sockets[channel].filter(s => s.id !== socket.id);
        }
    });
});

// API to trigger message broadcasting
app.get('/broadcast', async (req, res) => {
    let params = req.query;
    console.log('message',params.message);
    console.log('channel',params.channel);
    if (params.channel && params.message) {
        let channel = params.channel;
        let message = params.message;
        let broadcastSuccess = false;

        if (sockets[channel]) {
            sockets[channel].forEach(socket => {
                socket.emit(channel, message, (acknowledgment) => {
                    if (acknowledgment) {
                        console.log(`Message sent to socket ${socket.id}`);
                        broadcastSuccess = true;
                    }
                });
            });
        }

        if (broadcastSuccess) {
            console.log('Broadcast success');
            return res.json({ status: true, message: 'Broadcast success' }).status(200);
        } else {
            console.log('No clients in the channel or message not sent.');
            return res.json({ status: false, message: 'No clients in the channel or message not sent.' }).status(404);
        }
    }

    return res.json({ status: false, message: 'Invalid Request' }).status(400);
});

const port = 3000;
server.listen(port, () => {
    console.log('Socket.IO server running on port ' + port);
});
