const { Client, LocalAuth, MessageMedia } = require('whatsapp-web.js');
const express = require('express');
const fileUpload = require('express-fileupload');
const rateLimit = require('express-rate-limit');
const path = require('path');
const fs = require('fs');
const qrcode = require('qrcode-terminal');

const app = express();

// Configurações principais
const PORT = process.env.PORT || 3000;
const API_KEY = "5e_Z.4y5Zo$$"; // <-- altera para a tua chave!
const ALLOWED_IPS = ['94.46.183.41']; // <-- coloca aqui o IP do teu servidor PHP

// Middleware
app.use(express.json());
app.use(fileUpload());

// Middleware para validar IP
// app.use((req, res, next) => {
//     const ip = req.ip || req.connection.remoteAddress;
//     const ipLimpo = ip.replace('::ffff:', '');
//     if (!ALLOWED_IPS.includes(ipLimpo)) {
//         console.log(`Tentativa de IP não autorizado: ${ipLimpo}`);
//         return res.status(403).json({ error: 'IP não autorizado.' });
//     }
//     next();
// });

// Inicializar WhatsApp Client
const client = new Client({
    authStrategy: new LocalAuth()
});

client.on('qr', (qr) => {
    console.log('📱 Escaneia o QR abaixo para entrar no WhatsApp:');
    qrcode.generate(qr, { small: true });
});

client.on('ready', () => {
    console.log('✅ Cliente WhatsApp pronto!');
});

client.initialize();

// Enviar mensagem com ou sem ficheiro
app.post('/enviarMensagem', async (req, res) => {
    const { number, message, apiKey } = req.body;

    // if (!apiKey || apiKey !== API_KEY) {
    //     return res.status(403).json({ error: 'Acesso proibido: Token inválido.' });
    // }

    if (!number || !message) {
        return res.status(400).json({ error: 'Número e mensagem são obrigatórios.' });
    }

    const formattedNumber = `${number}@c.us`;

    try {
        // Se vier ficheiro
        if (req.files && req.files.file) {
            const file = req.files.file;

            const filePath = path.join(__dirname, 'uploads', file.name);

            // Garantir que a pasta uploads existe
            if (!fs.existsSync(path.join(__dirname, 'uploads'))) {
                fs.mkdirSync(path.join(__dirname, 'uploads'));
            }

            // Guardar ficheiro no servidor
            await file.mv(filePath);

            const media = MessageMedia.fromFilePath(filePath);
            await client.sendMessage(formattedNumber, media, { caption: message });

            // Apagar o ficheiro depois de enviar
            fs.unlinkSync(filePath);

            res.json({ success: true, message: 'Mensagem com ficheiro enviada!' });

        } else {
            // Mensagem simples
            await client.sendMessage(formattedNumber, message);
            res.json({ success: true, message: 'Mensagem enviada!' });
        }
    } catch (error) {
        console.error(error);
        res.status(500).json({ error: 'Erro ao enviar mensagem.' });
    }
});

// Arrancar o servidor
app.listen(PORT, '0.0.0.0', () => {
    console.log(`🚀 API WhatsApp a correr em http://localhost:${PORT}`);
});