/* https server working code */
var fs    = require('fs');
var app   = require('express')();
var privateKey  = fs.readFileSync('/etc/letsencrypt/live/dev02.huetten-holiday.de/privkey.pem', 'utf8');
var certificate = fs.readFileSync('/etc/letsencrypt/live/dev02.huetten-holiday.de/fullchain.pem', 'utf8');
var credentials = {key: privateKey, cert: certificate};
var https  = require('https').createServer(credentials, app);
var io    = require('socket.io')(https);
var redis = require('redis');


io.on('connection', function(socket){
    console.log('a user connected');

    var redisClient = redis.createClient();

    /* Realtime message subscribe */
    redisClient.subscribe('message');
    redisClient.on('message', function(channel, message){
        /*console.log('new message in queue', channel, message);*/
        socket.emit(channel, message);
    });

    /* Realtime inquiry subscribe */
    redisClient.subscribe('inquiryCount');
    redisClient.on('inquiryCount', function(channel, message){
        /*console.log('new inquiry in queue', channel, message);*/
        socket.emit(channel, message);
    });

    socket.on('disconnect', function(){
        redisClient.quit();
        console.log('user disconnected');
    });
});


https.listen(3000, function(){
    console.log('listening on *:3000');
});

/* Local machine working code */
/*var app   = require('express')();
var http  = require('http').Server(app);
var io    = require('socket.io')(http);
var redis = require('redis');

io.on('connection', function(socket){
    console.log('a user connected');

    var redisClient = redis.createClient();

    /!* Realtime message subscribe *!/
    redisClient.subscribe('message');
    redisClient.on('message', function(channel, message){
        /!*console.log('new message in queue', channel, message);*!/
        socket.emit(channel, message);
    });

    /!* Realtime inquiry subscribe *!/
    redisClient.subscribe('inquiryCount');
    redisClient.on('inquiryCount', function(channel, message){
        console.log('new inquiry in queue', channel, message);
        socket.emit(channel, message);
    });

    socket.on('disconnect', function(){
        redisClient.quit();
        console.log('user disconnected');
    });
});


http.listen(3000, function(){
    console.log('listening on *:3000');
});*/
