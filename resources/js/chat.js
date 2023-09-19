const form = document.querySelector(".typing-area"),
  incoming_id = form.querySelector(".incoming_id").value,
  inputField = form.querySelector(".input-field"),
  sendBtn = form.querySelector("button"),
  chatBox = document.querySelector(".chat-box");

const detailsEl = document.querySelector(".details");
const userStatus = document.querySelector(".details p");

const outgoingId = document.querySelector(".outgoing_id").value;
const incomingId = document.querySelector(".incoming_id").value;
const message = document.querySelector(".input-field").value;

function scrollToBottom() {
  chatBox.scrollTop = chatBox.scrollHeight;
}

conn.onmessage = function (e) {
  const data = JSON.parse(e.data);
  const userId = Number(detailsEl.dataset.id);
  console.log("Chat page");
  console.log(data);
  if (data.hasOwnProperty("userStatus")) {
    const uniqueId = data.uniqueId;
    const status = data.userStatus;

    if (uniqueId === userId) {
      userStatus.textContent = status;
    }

    return;
  }

  var chatDiv = document.createElement("div");
  var detailsDiv = document.createElement("div");
  var pElement = document.createElement("p");

  detailsDiv.className = "details";
  pElement.textContent = data.message;
  detailsDiv.appendChild(pElement);

  if (data.outgoing_msg_id !== outgoingId) {
    chatDiv.className = "chat incoming";
    var imgElement = document.createElement("img");
    imgElement.src = "http://localhost/Chatbox/" + data.img;
    imgElement.alt = "";
    chatDiv.appendChild(imgElement);
  } else {
    chatDiv.className = "chat outgoing";
  }

  chatDiv.appendChild(detailsDiv);
  chatBox.appendChild(chatDiv);
  scrollToBottom();
};

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

  var data = {
    outgoing_id: outgoingId,
    incoming_id: incomingId,
    message: inputField.value,
  };

  conn.send(JSON.stringify(data));
  var chatDiv = document.createElement("div");

  chatDiv.className = "chat outgoing";

  // Tạo một thẻ div có class "details"
  var detailsDiv = document.createElement("div");
  detailsDiv.className = "details";
  var pElement = document.createElement("p");
  pElement.textContent = data.message;

  // Thêm thẻ p vào thẻ detailsDiv
  detailsDiv.appendChild(pElement);

  // Thêm thẻ detailsDiv vào thẻ chatDiv
  chatDiv.appendChild(detailsDiv);
  chatBox.appendChild(chatDiv);

  inputField.value = "";
  scrollToBottom();
};
chatBox.onmouseenter = () => {
  chatBox.classList.add("active");
};

chatBox.onmouseleave = () => {
  chatBox.classList.remove("active");
};

async function getChatBox() {
  try {
    const formData = new FormData(form);

    const response = await fetch("http://localhost/Chatbox/chat/getAll", {
      method: "POST",
      body: formData,
    });

    if (response.ok) {
      const data = await response.text();

      chatBox.innerHTML = data;

      // if (!chatBox.classList.contains("active")) {
      //   scrollToBottom();
      // }
      scrollToBottom();
    }
  } catch (error) {
    console.error("An error occurred:", error);
  }
}

getChatBox();
