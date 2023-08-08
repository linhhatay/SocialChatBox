const searchBar = document.querySelector(".search input"),
  searchIcon = document.querySelector(".search button"),
  usersList = document.querySelector(".users-list");

searchIcon.onclick = () => {
  searchBar.classList.toggle("show");
  searchIcon.classList.toggle("active");
  searchBar.focus();
  if (searchBar.classList.contains("active")) {
    searchBar.value = "";
    searchBar.classList.remove("active");
  }
};

searchBar.onkeyup = () => {
  let searchTerm = searchBar.value;
  if (searchTerm != "") {
    searchBar.classList.add("active");
  } else {
    searchBar.classList.remove("active");
  }
  try {
    const searchUser = async () => {
      const data = new FormData();
      data.append("key", searchTerm);
      const response = await fetch("/Chatbox/search", {
        method: "POST",
        body: data,
      });

      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      const results = await response.json();

      let html = results;
      if (Array.isArray(results)) {
        html = results
          .map(
            (user) => `
       <a href="/Chatbox/chat?user_id=${user.unique_id}">
         <div class="content">
           <img
             src="https://t4.ftcdn.net/jpg/05/49/98/39/360_F_549983970_bRCkYfk0P6PP5fKbMhZMIb07mCJ6esXL.jpg"
             alt=""
           />
           <div class="details">
             <span>${user.fname} ${user.lname}</span>
             <p>${user.status}</p>
           </div>
         </div>
         <div class="status-dot '. $offline .'">
           <i class="fas fa-circle"></i>
         </div>
       </a>
     `
          )
          .join("");
      }
      usersList.innerHTML = html;
    };
    searchUser();
  } catch (error) {
    console.error("Fetch error:", error);
  }
};

// setInterval(() => {
//   let xhr = new XMLHttpRequest();
//   xhr.open("GET", "php/users.php", true);
//   xhr.onload = () => {
//     if (xhr.readyState === XMLHttpRequest.DONE) {
//       if (xhr.status === 200) {
//         let data = xhr.response;
//         if (!searchBar.classList.contains("active")) {
//           usersList.innerHTML = data;
//         }
//       }
//     }
//   };
//   xhr.send();
// }, 500);

async function fetchUsers() {
  try {
    const response = await fetch("/Chatbox/users");

    if (!response.ok) {
      throw new Error("Network response was not ok");
    }

    const users = await response.json();

    if (!searchBar.classList.contains("active")) {
      const html = users
        .map(
          (user) => `
          <a href="/Chatbox/chat/${user.unique_id}">
            <div class="content">
              <img
                src="https://t4.ftcdn.net/jpg/05/49/98/39/360_F_549983970_bRCkYfk0P6PP5fKbMhZMIb07mCJ6esXL.jpg"
                alt=""
              />
              <div class="details">
                <span>${user.fname} ${user.lname}</span>
                <p>${user.status}</p>
              </div>
            </div>
            <div class="status-dot '. $offline .'">
              <i class="fas fa-circle"></i>
            </div>
          </a>
        `
        )
        .join("");
      usersList.innerHTML = html;
    }
  } catch (error) {
    console.error("Fetch error:", error);
  }
}

fetchUsers();

// setInterval(() => {
//   fetchUsers();
// }, 500);
