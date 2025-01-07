require('dotenv').config();

const express = require('express');
const https = require('https');
const socketIo = require('socket.io');
const axios = require('axios');
const fs =  require('fs');

// Load SSL certificates
const options = {
    key: fs.readFileSync("./storage/ssl/private.key"),
    cert: fs.readFileSync("./storage/ssl/certificate.crt"),
    ca: fs.readFileSync("./storage/ssl/ca_bundle.crt"), // If you have a CA bundle
};

const server = https.createServer(options);
const io = socketIo(server);
const userSocketMap = {};
const apiUrl = process.env.APP_URL;

const setupSocket = (io) => {
    io.on("connection", (socket) => {
        const userId = socket.handshake.query.userId;
        console.log('Connected user ID:', userId);

        if (!userId) {
            console.error("User ID is required for socket connection.");
            socket.disconnect();
            return;
        }

        userSocketMap[userId] = socket.id;
        console.log('userSocketMap:', userSocketMap);

        socket.on("sendMessage", async (payloadData) => {
            console.log('Send Message Payload:', payloadData);

            const headers = {
                Accept: "application/json",
                Authorization: payloadData.access_token,
            };

            try {
                const payload = {
                    recipient_id: payloadData.recipient_id,
                    content: payloadData.content,
                    type: payloadData.type,
                };

                const response = await axios.post(
                    `${apiUrl}/api/send-message`,
                    payload,
                    { headers }
                );

                console.log("send-message-response:", response.data);

                const receiverSocketId = userSocketMap[response.data.data.receiver_id];
                if (receiverSocketId && (!response.data.data.sender_user.is_block)) {
                    console.log("Receiver Socket ID:", receiverSocketId);

                    io.to(receiverSocketId).emit("receiveMessage",response.data.data
                        /*{
                            channel: payloadData.recipient_id,
                            message: response.data.data.content,
                        }*/
                    );
                }
            } catch (error) {
                console.error("Error saving message:", error.message);
                if (error.response) {
                    console.error("Response data:", error.response.data);
                    console.error("Response status:", error.response.status);
                }
            }
        });

        socket.on("disconnect", () => {
            console.log(`User disconnected: ${userId}`);
            delete userSocketMap[userId];
        });
    });
};

const emitEvent = (io, event, receiver_id, data) => {
    const usersSocket =  userSocketMap[receiver_id];
    io.to(usersSocket).emit(event, data);
};

module.exports = {setupSocket, userSocketMap, emitEvent};

// Call setupSocket and start the server here
setupSocket(io);

// Start the server
const port = 3000;
server.listen(port, () => {
    console.log('Socket.IO server running on port ' + port);
});