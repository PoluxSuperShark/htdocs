// Chatbox
const chatBox = document.getElementById("chatBox");
const input = document.getElementById("messageInput");

// JS : Fetch msg
function fetchMessages() {
    fetch("chat.php?fetch=1")
        .then(response => response.json())
        .then(data => {

            chatBox.innerHTML = "";

            data.forEach(msg => {

                const div = document.createElement("div");
                div.classList.add("message");

                const usernameClass = (msg.role === "admin")
                    ? "username-admin"
                    : "username-user";

                div.innerHTML =
                    `<span class="${usernameClass}">
                        ${escapeHtml(msg.username)}
                    </span> :
                    ${escapeHtml(msg.message)}`;

                chatBox.appendChild(div);
            });

            chatBox.scrollTop = chatBox.scrollHeight;
        });
}

// Send msg
function sendMessage() {

    const message = input.value.trim();
    if (message === "") return;

    fetch("chat.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "message=" + encodeURIComponent(message)
    })
    .then(() => {
        input.value = "";
        fetchMessages();
    });
}

// Block the XSS
function escapeHtml(text) {
    const div = document.createElement("div");
    div.innerText = text;
    return div.innerHTML;
}

// Events
input.addEventListener("keypress", function(e) {
    if (e.key === "Enter") {
        sendMessage();
    }
});

/* Refresh toutes les 2 sec */
setInterval(fetchMessages, 2000);
fetchMessages();