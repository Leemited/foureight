
var port = 3000;
var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);

var socket_ids = [];
var pd_ids = [];
var pd_ids_cnt = [];
var pd_id;

io.on('connection', function(socket){

    socket_ids.push(socket.id);

    socket.on('add connect', function(data){
        console.log(data.pd_id);
        pd_id = "'"+data.pd_id+"'";
        dis_pd_id = data.pd_id;
        if(pd_ids.indexOf(data.pd_id)!=-1){
            console.log("find");
            if(pd_ids_cnt[pd_id]<0){
                console.log("find2");
                pd_ids_cnt[pd_id] = 0;
            }
            pd_ids_cnt[pd_id]++;
        }else{
            console.log("nofind");
            pd_ids.push(data.pd_id);
            pd_ids_cnt[pd_id] = 1;
        }

        io.to(socket.id).emit('view connect', pd_ids_cnt[pd_id]);
        socket.broadcast.emit('insert connect', pd_ids_cnt[pd_id]);
    });

    socket.on('disconnect', function(){
        pd_ids_cnt[pd_id]--;
        if(pd_ids_cnt[pd_id]<=0){
            pd_ids_cnt[pd_id] = 0;
        }
        io.to(socket.id).emit('view connect', pd_ids_cnt[pd_id]);
        socket.broadcast.emit('insert connect', pd_ids_cnt[pd_id]);
    });
});

http.listen(port, function(){
    console.log('listening on : ' + port);
});