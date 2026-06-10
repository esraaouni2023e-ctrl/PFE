// server.js
const http = require('http');
const WebSocket = require('ws');
const { Server } = require('socket.io');

const PORT = 3000;

// Create HTTP server
const server = http.createServer((req, res) => {
    console.log(`[HTTP] ${req.method} ${req.url}`);
    
    // Handle Laravel broadcasting events (Pusher REST API)
    if (req.method === 'POST' && req.url.includes('/events')) {
        let body = '';
        req.on('data', chunk => { body += chunk; });
        req.on('end', () => {
            try {
                const payload = JSON.parse(body);
                console.log('[Pusher-Mock] Event received from Laravel:', payload.name, 'on channels:', payload.channels);
                
                // Broadcast Pusher event to WebSocket clients
                broadcastPusherEvent(payload);
                
                res.writeHead(200, { 'Content-Type': 'application/json' });
                res.end(JSON.stringify({}));
            } catch (err) {
                console.error('[Pusher-Mock] Error parsing Laravel post:', err);
                res.writeHead(400);
                res.end();
            }
        });
    } else {
        res.writeHead(200, { 'Content-Type': 'text/plain' });
        res.end('CapAvenir Signaling Server is running.');
    }
});

// --- WebRTC Signaling with Socket.io ---
const io = new Server(server, {
    cors: { origin: "*" }
});

io.on('connection', (socket) => {
    console.log(`[WebRTC] Client connected: ${socket.id}`);
    
    // Join a meeting room (based on student ID)
    socket.on('join-room', (roomId) => {
        socket.join(roomId);
        console.log(`[WebRTC] Client ${socket.id} joined room ${roomId}`);
        socket.to(roomId).emit('peer-joined', socket.id);
    });

    // Relay WebRTC Offer
    socket.on('offer', (data) => {
        console.log(`[WebRTC] Relay offer from ${socket.id} to room ${data.roomId}`);
        socket.to(data.roomId).emit('offer', {
            sdp: data.sdp,
            senderId: socket.id
        });
    });

    // Relay WebRTC Answer
    socket.on('answer', (data) => {
        console.log(`[WebRTC] Relay answer from ${socket.id} to room ${data.roomId}`);
        socket.to(data.roomId).emit('answer', {
            sdp: data.sdp,
            senderId: socket.id
        });
    });

    // Relay ICE Candidates
    socket.on('candidate', (data) => {
        console.log(`[WebRTC] Relay ICE candidate from ${socket.id} to room ${data.roomId}`);
        socket.to(data.roomId).emit('candidate', {
            candidate: data.candidate,
            senderId: socket.id
        });
    });

    socket.on('disconnect', () => {
        console.log(`[WebRTC] Client disconnected: ${socket.id}`);
        // Notify others
        io.emit('peer-disconnected', socket.id);
    });
});


// --- Local Pusher-Compatible WebSocket Server ---
const wss = new WebSocket.Server({ noServer: true });
const pusherClients = new Set();

server.on('upgrade', (request, socket, head) => {
    // Check if the path corresponds to Pusher client handshakes
    if (request.url.startsWith('/app/')) {
        wss.handleUpgrade(request, socket, head, (ws) => {
            wss.emit('connection', ws, request);
        });
    }
});

wss.on('connection', (ws, req) => {
    const socketId = `${Math.random().toString(36).substr(2, 9)}.${Math.random().toString(36).substr(2, 9)}`;
    ws.socketId = socketId;
    ws.subscribedChannels = new Set();
    pusherClients.add(ws);
    
    console.log(`[Pusher-Mock] Client connected. Assigned Socket ID: ${socketId}`);

    // Send connection established handshake frame
    ws.send(JSON.stringify({
        event: 'pusher:connection_established',
        data: JSON.stringify({
            socket_id: socketId,
            activity_timeout: 120
        })
    }));

    ws.on('message', (message) => {
        try {
            const msg = JSON.parse(message);
            console.log('[Pusher-Mock] Message received from client:', msg.event);
            
            if (msg.event === 'pusher:subscribe') {
                const channel = msg.data.channel;
                ws.subscribedChannels.add(channel);
                console.log(`[Pusher-Mock] Socket ${socketId} subscribed to channel: ${channel}`);
                
                // Confirm subscription
                ws.send(JSON.stringify({
                    event: 'pusher_internal:subscription_succeeded',
                    channel: channel
                }));
            } else if (msg.event === 'pusher:ping') {
                ws.send(JSON.stringify({ event: 'pusher:pong' }));
            }
        } catch (err) {
            console.error('[Pusher-Mock] WS message error:', err);
        }
    });

    ws.on('close', () => {
        console.log(`[Pusher-Mock] Client disconnected. Socket ID: ${socketId}`);
        pusherClients.delete(ws);
    });
});

// Broadcast Pusher Event from Laravel
function broadcastPusherEvent(payload) {
    const eventName = payload.name;
    const channels = payload.channels || [];
    const eventData = payload.data;
    
    pusherClients.forEach(client => {
        channels.forEach(channel => {
            if (client.subscribedChannels.has(channel)) {
                console.log(`[Pusher-Mock] Relaying event ${eventName} to client ${client.socketId} on channel ${channel}`);
                client.send(JSON.stringify({
                    event: eventName,
                    channel: channel,
                    data: eventData // Already a stringified JSON string from Laravel
                }));
            }
        });
    });
}

// Start Server
server.listen(PORT, '0.0.0.0', () => {
    console.log(`===================================================`);
    console.log(`  CAPAVENIR REAL-TIME SERVER RUNNING ON PORT ${PORT}`);
    console.log(`  - WebRTC signaling (Socket.io) active`);
    console.log(`  - Local Pusher WebSocket active`);
    console.log(`===================================================`);
});
