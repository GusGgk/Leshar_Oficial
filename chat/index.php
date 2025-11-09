<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    
    $current = $_SERVER['REQUEST_URI'];
    header('Location: ../login/index.html?redirect=' . urlencode($current));
    exit;
}


$remetente_id = (int) $_SESSION['user_id'];

$destinatario_id = isset($_GET['chat_with']) ? (int) $_GET['chat_with'] : 2;


if ($remetente_id == $destinatario_id) {
    echo "Você não pode conversar consigo mesmo!";
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Chat Privado PHP + MySQL</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 20px auto; }
        #chat-window { border: 1px solid #ccc; height: 300px; overflow-y: scroll; padding: 10px; margin-bottom: 10px; background-color: #f9f9f9; }
        .message-box { margin-bottom: 5px; padding: 5px; border-radius: 5px; clear: both; }
        
        .my-message { background-color: #d1e7dd; float: right; max-width: 70%; }
        
        .other-message { background-color: #f8d7da; float: left; max-width: 70%; }
        .message-box::after { content: ""; display: table; clear: both; }
    </style>
</head>
<body>

    <h2>Chat Privado (ID <?php echo $remetente_id; ?> conversando com ID <?php echo $destinatario_id; ?>)</h2>

    <div id="chat-window">
        </div>

    <form id="chat-form">
        <input type="hidden" id="remetente_id" value="<?php echo $remetente_id; ?>">
        <input type="hidden" id="destinatario_id" value="<?php echo $destinatario_id; ?>">
        <input type="text" id="message" placeholder="Digite sua mensagem..." style="width: 80%;" required>
        <button type="submit">Enviar</button>
    </form>

    <script>
        const chatWindow = document.getElementById('chat-window');
        const chatForm = document.getElementById('chat-form');
        const remetenteId = document.getElementById('remetente_id').value;
        const destinatarioId = document.getElementById('destinatario_id').value;
        const messageInput = document.getElementById('message');

        function scrollToBottom() {
            chatWindow.scrollTop = chatWindow.scrollHeight;
        }

        
        async function fetchMessages() {
            try {
                
                const response = await fetch(`chat_handler.php?action=get&remetente=${remetenteId}&destinatario=${destinatarioId}`, {
                    method: 'GET'
                });
                const messages = await response.json();

                chatWindow.innerHTML = '';
                messages.forEach(msg => {
                    const msgDiv = document.createElement('div');
                    msgDiv.classList.add('message-box');
                    
                    
                    if (msg.remetente_id == remetenteId) {
                        msgDiv.classList.add('my-message');
                    } else {
                        msgDiv.classList.add('other-message');
                    }
                    
                    const time = new Date(msg.data_envio).toLocaleTimeString();
                    msgDiv.innerHTML = `<strong>(${msg.remetente_nome} - ${time})</strong><br>${msg.mensagem}`;
                    
                    chatWindow.appendChild(msgDiv);
                });

                scrollToBottom();

            } catch (error) {
                console.error('Erro ao buscar mensagens:', error);
            }
        }

        
        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const text = messageInput.value.trim();

            if (!text) return;

            try {
                
                await fetch('chat_handler.php?action=send', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `remetente_id=${remetenteId}&destinatario_id=${destinatarioId}&text=${encodeURIComponent(text)}`
                });

                messageInput.value = '';
                fetchMessages(); 

            } catch (error) {
                console.error('Erro ao enviar mensagem:', error);
            }
        });

        
        setInterval(fetchMessages, 2000); 
        fetchMessages(); 
    </script>
</body>
</html>