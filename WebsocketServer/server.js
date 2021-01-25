/*jshint esversion: 6 */

var app = require('http').createServer();

// Se tiverem problemas com "same-origin policy" deverão activar o CORS.

// Aqui, temos um exemplo de código que ativa o CORS (alterar o url base) 

// var app = require('http').createServer(function(req,res){
// Set CORS headers
//  res.setHeader('Access-Control-Allow-Origin', 'http://---your-base-url---');
//  res.setHeader('Access-Control-Request-Method', '*');
//  res.setHeader('Access-Control-Allow-Methods', 'UPGRADE, OPTIONS, GET');
//  res.setHeader('Access-Control-Allow-Credentials', true);
//  res.setHeader('Access-Control-Allow-Headers', req.header.origin);
//  if ( req.method === 'OPTIONS' || req.method === 'UPGRADE' ) {
//      res.writeHead(200);
//      res.end();
//      return;
//  }
// });

// NOTA: A solução correta depende da configuração do próprio servidor, 
// e alguns casos do próprio browser.
// Assim sendo, não se garante que a solução anterior funcione.
// Caso não funcione é necessário procurar/investigar soluções alternativas

var io = require('socket.io')(app);

var LoggedUsers = require('./loggedusers.js');

app.listen(8080, function(){
    console.log('listening on *:8080');
});

// ------------------------
// Estrutura dados - server
// ------------------------

// loggedUsers = the list (map) of logged users. 
// Each list element has the information about the user and the socket id
// Check loggedusers.js file

let loggedUsers = new LoggedUsers();

io.on('connection', function (socket) {
    console.log('client has connected (socket ID = '+socket.id+')' );

    // Emit message to the same cliente 
    //socket.emit('my_active_games_changed');

    // Handle message sent from the client to the server
    // socket.on('messageType_from_client_to_server', function (data){

    // });

});

io.on('connection', function (socket) {
    socket.on("user_enter", function(user) {
        if (user) {
            loggedUsers.addUserInfo(user, socket.id);
        }
    });
    socket.on("user_exit", function(id) {
        if (id) {
            loggedUsers.removeUserInfoByID(id);
        }
    });

    socket.on("income_movement_added", function(destUser, specialUser) {
        console.log(destUser);
        console.log(loggedUsers);
        let userInfo = loggedUsers.userInfoByID(destUser);
        let socket_id = userInfo !== undefined ? userInfo.socketID : null;
        if (socket_id === null) {
            //mandar por email
            socket.emit("not_logged_in");
        } else {
            socket.emit("logged_in");
            io.to(socket_id).emit("income_movement_added");
        }
    }); 

    socket.on("income_movement_added_special", function(destUser) {
        console.log(destUser);
        console.log(loggedUsers);
        let userInfo = loggedUsers.userInfoByID(destUser);
        let socket_id = userInfo !== undefined ? userInfo.socketID : null;
        if (socket_id === null) {
            //mandar por email
           // socket.emit("not_logged_in_special");
        } else {
          //  socket.emit("logged_in_special");
            io.to(socket_id).emit("income_movement_added_special");
        }
    }); 

    socket.on("specialUser", function(specialUser) {
        let userInfoSpecial = loggedUsers.userInfoByID(specialUser);
        let socket_idSpecial = userInfoSpecial !== undefined ? userInfoSpecial.socketID : null;

        if (socket_idSpecial === null) {
            //mandar por email
            socket.emit("not_logged_in_special");
        } else {
            socket.emit("logged_in_special");
            io.to(socket_idSpecial).emit("movement_added_special", specialUser);
        }
    }); 

    socket.on("specialUserIncome", function(specialUser) {
        let userInfoSpecial = loggedUsers.userInfoByID(specialUser);
        let socket_idSpecial = userInfoSpecial !== undefined ? userInfoSpecial.socketID : null;

        if (socket_idSpecial === null) {
            //mandar por email
            socket.emit("not_logged_in_special");
        } else {
            socket.emit("logged_in_special");
            io.to(socket_idSpecial).emit("income_movement_added_special", specialUser);
        }
    }); 

    socket.on("movement_added", function(destUser, fromUser) {
        let userInfo = loggedUsers.userInfoByID(destUser);
        let socket_id = userInfo !== undefined ? userInfo.socketID : null;
        console.log(socket_id);
        if (socket_id === null) {
            //mandar por email
            socket.emit("not_logged_in_transfer", destUser);
        } else {
            socket.emit("logged_in_transfer");
            io.to(socket_id).emit("movement_added", fromUser);
        }
    }); 
});
