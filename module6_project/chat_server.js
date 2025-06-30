
const http = require("http"),
    fs = require("fs");
const port = 3456;
const file = "client.html";

const server = http.createServer(function (req, res) {
    fs.readFile(file, function (err, data) {
        if (err) return res.writeHead(500);
        res.writeHead(200);
        res.end(data);
    });
});

server.listen(port);

const socketio = require("socket.io")(http, {
    wsEngine: 'ws'
});

const io = socketio.listen(server);

let users = {};
const userArr = [];
let userid = {};
let banUser = {} 

let rooms = ['lobby'];
let private = {}; 
let privateArr = [];
let owners = {};

//connected
io.sockets.on("connection", function (socket) {

    //inital start, make user
    socket.on('defineUser', function(name){
        let id = socket.id;
       
        userArr.push(name);

        users[name] = {
            user: name,
            room: ""
        };

        userid[name] = {
            ID: id
        };

        users[name]["room"] = "lobby";
        socket.user = name;
        socket.room = "lobby";
        socket.join("lobby");
        socket.emit('update_room', {rooms : rooms, privateArr : privateArr});
        io.sockets.in("lobby").emit("clear", name + " joined " + socket.room);
        
        let user = socket.user;
       
        users[user]["name"] = user;
        let listofUsers = "";
       
        for (let i in users){
            if(users[i]["room"] == "lobby"){
                if(i != null){
                    listofUsers += i + " "; 
                }
            }
        }
        io.in("lobby").emit("update_user", listofUsers);
    });

    //chekck password for private room
    socket.on('password_check', function(data){
        let user = socket.user;
        let isbanned=false;

        let banneduser=""; let banneduserid = "";
        for (let i in banUser[data["roomname"]]) {
            if (banUser[data["roomname"]][i]===user) {
                banneduser= banUser[data["roomname"]][i];
                banneduserid = userid[banneduser]["ID"];
                isbanned = true;
            }
        }
       
        //make sure user is not banned
        if (isbanned===false) {
        if (data['pwd']===private[data['roomname']]) {
            let user = socket.user;
            socket.leave(socket. room);
            socket.join(data["roomname"]);
            users[user]["name"] = user;
            users[user]["room"] = data["roomname"];
           
            let listofUsers = "";
            let prevUsers = "";
            for (let i in users){
                if(users[i]["room"] == data["roomname"]){
                    if(i != null){
                        listofUsers += i + " "; 
                    }
                }
                else if(users[i]["room"] == socket.room){
                    prevUsers += i + " ";
            
                }
            }
            let id = userid[socket.user]["ID"];
           
            io.to(id).emit("change_room", data["roomname"]);
            io.in(data["roomname"]).emit("clear", user + " joined " + data["roomname"]);
            io.in(socket.room).emit("clear", user + " left " + socket.room);
            io.sockets.in(socket.room).emit("update_user", prevUsers);
            socket.room = data["roomname"];
            io.sockets.in(data["roomname"]).emit("update_user", listofUsers);
        } else {
            io.emit("incorrect_password");
        }} else {
            io.to(banneduserid).emit("client_ban_join", {rn: data["roomname"]});
        }
    });

    socket.on('server_private_message', function(data) {
        let pm_to = data["pm_to"];
        let pm = data["pm"];
        let pm_from = socket.user;
        let id = userid[pm_to]["ID"];
        let id2 = userid[pm_from]["ID"];

        io.to(id).emit("client_private_message", {pm_to: pm_to, pm: pm, pm_from: pm_from});
        io.to(id2).emit("client_private_message", {pm_to: pm_to, pm: pm, pm_from: pm_from});
    }) 

    socket.on('server_message', function (data) {
        let user = socket.user;
        io.sockets.in(socket.room).emit("client_message", { user: user, message: data["message"] })
    });

    socket.on('server_anonymous', function (data) {
        let user = socket.user;
        io.sockets.in(socket.room).emit("client_anonymous", {message: data["message"] }) 
    });

    socket.on('server_joinRoom', function(data){ //join room
        let user = socket.user;
        let listofUsers = "";
        let prevUsers = "";
        let isbanned=false;

        let banneduser=""; let id = "";
        for (let i in banUser[data["roomname"]]) {
            if (banUser[data["roomname"]][i]===user) {
                banneduser= banUser[data["roomname"]][i];
                id = userid[banneduser]["ID"];
                isbanned = true;
            }
        }
       
       
        if (isbanned===false) {
        socket.leave(socket.room);
        socket.join(data["roomname"]);
        users[user]["name"] = user;
        users[user]["room"] = data["roomname"];


        for (let i in users){
            if(users[i]["room"] == data["roomname"]){
                if(i != null){
                    listofUsers += i + " "; 
                }
            }
            else if(users[i]["room"] == socket.room){
                prevUsers += i + " ";
        
            }
        }

        let id = userid[socket.user]["ID"];

        io.to(id).emit("change_room", data["roomname"]);
        io.in(data["roomname"]).emit("clear", user + " joined " + data["roomname"]);
        io.in(socket.room).emit("clear", user + " left " + socket.room);
        io.sockets.in(socket.room).emit("update_user", prevUsers);
        socket.room = data["roomname"];
        io.sockets.in(data["roomname"]).emit("update_user", listofUsers);
        } else {
            io.to(id).emit("client_ban_join", {rn: data["roomname"]});
        }
    });

    socket.on('server_invite', function(data) { //send invites to another user 
        let to = data["to"];
        let room = data["room"];
        let from = socket.user;
        let id = userid[to]["ID"];
        let cur_room = socket.room;

        io.to(id).emit("client_invite", {to: to, room: room, from: from, cur_room: cur_room});
    })

    socket.on('server_roomname', function (data) {
        let rn = data["roomname"];
        let user = socket.user;
        
        data['creator'] = user;
        rooms.push(rn);
        owners[rn] = user;
        banUser[rn] = new Array(""); 
        io.emit('room_list', rooms); //updadte public room list
    });

    socket.on('server_privateroom', function (data) { 
        let privrn = data["privroomname"];
        let pwd = data["privpwd"];
        let user =socket.user;
        private[privrn] = pwd;

        owners[privrn] = user
        banUser[privrn] = new Array([data["user_to_ban"]]);
        privateArr.push(privrn);
        io.emit('private_room_list', privateArr); //update private room list
    });

 
    socket.on('kickuser', function (data) { //kick user
        let sendto = "lobby";
        let kickeduser = data["kickeduser"];
        let curroom = data['room'];
        let id = userid[kickeduser]["ID"];
        let from = socket.user;
        let roomcreator = owners[curroom];
    
        io.to(id).emit("client_kick", {sendto: sendto, room: curroom, from: from, creator: roomcreator});
    });
   

    socket.on('banuser', function (data) { //ban user
        let banneduser = data["banneduser"];
        let room = data["room"];
        let roomcreator = owners[room];
        let from = socket.user;
        let id = userid[banneduser]["ID"]

        if (from === roomcreator) {
            if (from == banneduser) {
                io.to(userid[socket.user]["ID"]).emit("self_ban", {rn: room});
            } else{
                banUser[room].push(banneduser);
                io.to(id).emit("client_ban", {sendto: "lobby", room: room, from: from, creator: roomcreator});

            }
        } else {
            io.to(userid[socket.user]["ID"]).emit("owner_only", {rn: room});
        }
    });
});

