const express = require('express');
const http = require('http');  // Use the 'http' module instead of 'https'
const socketIO = require('socket.io');

const app = express();
const server = http.createServer(app);  // Create an HTTP server

const io = socketIO(server);

// Enable CORS for all routes
app.use((req, res, next) => {
  res.header('Access-Control-Allow-Origin', '*'); // Update with your domain or '*'
  res.header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept');
  next();
});

// Serve static files (e.g., HTML, CSS) from the 'public' directory
app.use(express.static(__dirname + '/public'));

// Set up WebSocket server
io.on('connection', (socket) => {
  console.log(`User connected: ${socket.id}`);

  // Listen for messages from clients
  socket.on('message', (data) => {
    console.log(`Received message from ${socket.id}: ${data}`);

    // Echo the message back to the client
    socket.send(`Server: ${data}`);
  });

  // Listen for disconnect event
  socket.on('disconnect', () => {
    console.log(`User disconnected: ${socket.id}`);
  });
});

// Start the HTTP server on port 3000
server.listen(3000, () => {
  console.log('Server is running on port 3000');
});
