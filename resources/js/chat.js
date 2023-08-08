const form = document.querySelector(".typing-area"),
  incoming_id = form.querySelector(".incoming_id").value,
  inputField = form.querySelector(".input-field"),
  sendBtn = form.querySelector("button"),
  chatBox = document.querySelector(".chat-box");

form.onsubmit = (e) => {
  e.preventDefault();
};

inputField.focus();
inputField.onkeyup = () => {
  if (inputField.value != "") {
    sendBtn.classList.add("active");
  } else {
    sendBtn.classList.remove("active");
  }
};

sendBtn.onclick = () => {
  (async () => {
    const formData = new FormData(form);

    try {
      const response = await fetch("/Chatbox/chat/insert", {
        method: "POST",
        body: formData,
      });

      if (response.ok) {
        inputField.value = "";
        scrollToBottom();
      }
    } catch (error) {
      console.error("An error occurred:", error);
    }
  })();
};
chatBox.onmouseenter = () => {
  chatBox.classList.add("active");
};

chatBox.onmouseleave = () => {
  chatBox.classList.remove("active");
};

setInterval(() => {
  (async () => {
    try {
      const formData = new FormData(form);

      const response = await fetch("http://localhost/Chatbox/chat/getAll", {
        method: "POST",
        body: formData,
      });

      if (response.ok) {
        const data = await response.text();

        chatBox.innerHTML = data;

        if (!chatBox.classList.contains("active")) {
          scrollToBottom();
        }
      }
    } catch (error) {
      console.error("An error occurred:", error);
    }
  })();
}, 2000);

function scrollToBottom() {
  chatBox.scrollTop = chatBox.scrollHeight;
}
