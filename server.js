const socket = require('socket.io');
const io = socket.listen(8080);

io.sockets.on('connection',function(socket){
   console.log('user logon');

   socket.on('send',function(data){
      console.log('메세지',data.msg);
   });
   soket.on('disconnect',function(){
      console.log("user logoff");
   });
});
