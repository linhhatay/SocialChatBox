const form = document.querySelector(".typing-area"),
  incoming_id = form.querySelector(".incoming_id").value,
  inputField = form.querySelector(".input-field"),
  sendBtn = form.querySelector("button"),
  chatBox = document.querySelector(".chat-box");

var outgoingId = document.querySelector(".outgoing_id").value;
var incomingId = document.querySelector(".incoming_id").value;
var message = document.querySelector(".input-field").value;

var conn = new WebSocket("ws://localhost:8080");
conn.onopen = function (e) {
  console.log("Connection established!");
};

conn.onmessage = function (e) {
  var data = JSON.parse(e.data);
  console.log(data);
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

// setInterval(() => {
//   (async () => {
//     try {
//       const formData = new FormData(form);

//       const response = await fetch("http://localhost/Chatbox/chat/getAll", {
//         method: "POST",
//         body: formData,
//       });

//       if (response.ok) {
//         const data = await response.text();

//         chatBox.innerHTML = data;

//         if (!chatBox.classList.contains("active")) {
//           scrollToBottom();
//         }
//       }
//     } catch (error) {
//       console.error("An error occurred:", error);
//     }
//   })();
// }, 500);

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

      if (!chatBox.classList.contains("active")) {
        scrollToBottom();
      }
    }
  } catch (error) {
    console.error("An error occurred:", error);
  }
}
getChatBox();

function scrollToBottom() {
  chatBox.scrollTop = chatBox.scrollHeight;
}
