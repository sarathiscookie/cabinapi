var app   = require('express')();
var http  = require('http').Server(app);
var io    = require('socket.io')(http);
var Redis = require('ioredis');
var redis = new Redis();

redis.subscribe('channel-message');

redis.on('message', function(channel, message){
    message = JSON.parse(message);
    io.emit(channel + ':' + message.event, message.data);
});

http.listen(3000);


