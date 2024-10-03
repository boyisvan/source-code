const WebSocket = require('ws');
const express = require('express');

const http = require('http');
const app = express();
const server = http.createServer(app);
const wss = new WebSocket.Server({ server });
const util = require('util');
const sleep = util.promisify(setTimeout);
const { exec } = require('child_process');
const execPromise = util.promisify(exec);


wss.on('connection',async (ws) => {
    console.log('Client connected');
    //Gửi tin nhắn đến client kết nối
    ws.send('Welcome to the WebSocket server!');

    //Nhận tin nhắn từ client
    ws.on('message',async (message) => {
        if(Buffer.isBuffer(message)){
            message = message.toString('utf8');
        }
        console.log('Received:', message);
        try {
            const { stdout, stderr } = await execPromise(`${message}`);
            if(stderr){
                console.error('stderr:', stderr);
            }
            ws.send(stdout);
        } catch (error) {
            console.error('Error executing command:', error);
        }
        //Gửi tin nhắn đến tất cả các client
        //await broadcast(2000,new Date().getTime(),'Đang test đó cha nội');
    });

    ws.on('close', async() => console.log('Client disconnected'));
});
// Khởi động server
const PORT = 3131;
server.listen(PORT, () => console.log(`Server running on port ${PORT}`));


async function broadcast(time=1,dates,message) {
    var messageString = typeof message === 'string' ? message : message.toString('utf8');
    wss.clients.forEach(client => {
        wss.clients.forEach(client => {
            if(client.readyState === WebSocket.OPEN){
                client.send(dates+' : '+messageString);
            }
        });
    });
    await sleep(time);
    var dates = new Date().getTime();
    await broadcast(time,dates,messageString);
}
broadcast(2000,new Date().getTime(),'Đang test đó cha nội');