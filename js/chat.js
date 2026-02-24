// TODO : Don't care about errs in the two vars
let currentUserId = <?= json_encode($_SESSION['user_id']) ?>;
let currentUserRole = <?= json_encode($currentUserRole) ?>;

function fetchMessages() {
    fetch("chat.php?fetch=1")
    .then(res => res.json())
    .then(data => {
        let chatBox = document.getElementById("chatBox");
        chatBox.innerHTML = "";

        data.forEach(msg => {
            let div = document.createElement("div");
            div.classList.add("message");
            let roleClass = (msg.role === "admin") ? "username-admin" : "username-user";
            div.innerHTML = `<span class="${roleClass}">${msg.username}</span>: ${msg.message}`;

            // Supprimer message
            if (msg.user_id === currentUserId || currentUserRole === "admin") {
                let btn = document.createElement("span");
                btn.className = "delete-btn";
                btn.textContent = "✖";
                btn.onclick = () => deleteMessage(msg.id);
                div.appendChild(btn);
            }

            // Bannir utilisateur (admins seulement)
            if (currentUserRole === "admin" && msg.user_id !== currentUserId) {
                let btnBan = document.createElement("span");
                btnBan.className = "ban-btn";
                btnBan.textContent = "🚫";
                btnBan.onclick = () => banUser(msg.user_id);
                div.appendChild(btnBan);
            }

            chatBox.appendChild(div);
        });

        chatBox.scrollTop = chatBox.scrollHeight;
    });
}

function sendMessage() {
    let input = document.getElementById("messageInput");
    let message = input.value.trim();
    if (!message) return;

    fetch("chat.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "message=" + encodeURIComponent(message)
    }).then(()=> { input.value=""; fetchMessages(); });
}

function deleteMessage(id) {
    fetch("chat.php", {
        method:"POST",
        headers: {"Content-Type":"application/x-www-form-urlencoded"},
        body:"delete_id=" + encodeURIComponent(id)
    }).then(()=>fetchMessages());
}

function banUser(userId) {
    if (!confirm("Voulez-vous vraiment bannir cet utilisateur ?")) return;
    fetch("chat.php", {
        method:"POST",
        headers: {"Content-Type":"application/x-www-form-urlencoded"},
        body:"ban_user_id=" + encodeURIComponent(userId)
    }).then(()=>fetchMessages());
}

setInterval(fetchMessages,2000);
fetchMessages();

document.getElementById("messageInput").addEventListener("keypress", e=>{
    if(e.key==="Enter") sendMessage();
});