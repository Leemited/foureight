var app = require('http').createServer(handler);
var io = require('socket.io').listen(app);
var fs = require('fs');

var counter = 0;

app.listen(3000);

function handler (req,res){
   fs.readFile('./test.html',
   function (err, data){
      if(err){
         res.writeHead(500);
         return res.end('Error loading test.html');
      }
      res.writeHead(200);
      res.end(data);

   })
}

io.sockets.on('connection',function(socket){
   socket.on('addme',function(username){
      socket.name = username;
      console.log(socket.id);
      socket.emit('chat','SERVER','You have connected');
      socket.broadcast.emit('chat','SERVER',username+' is on deck');
   });
   socket.on('sendchat',function(){
      io.sockets.emit('chat',socket.username,data);
   });
});
