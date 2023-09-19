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
      const results = await response.text();

      usersList.innerHTML = results;
    };
    searchUser();
  } catch (error) {
    console.error("Fetch error:", error);
  }
};

async function fetchUsers() {
  try {
    const response = await fetch("/Chatbox/users");

    if (!response.ok) {
      throw new Error("Network response was not ok");
    }

    const users = await response.text();

    if (!searchBar.classList.contains("active")) {
      usersList.innerHTML = users;
    }
  } catch (error) {
    console.error("Fetch error:", error);
  }
}

fetchUsers();

conn.onmessage = function (e) {
  const data = JSON.parse(e.data);
  console.log(data);
  const userId = data.uniqueId;
  const status = data.userStatus;

  if (usersList.childElementCount > 0) {
    const userEntries = document.querySelectorAll(".user");

    userEntries.forEach((userEntry) => {
      const uniqueId = Number(userEntry.dataset.id);
      if (uniqueId === userId) {
        const statusDot = userEntry.querySelector(".status-dot");
        if (status === "Active now") {
          statusDot.classList.remove("offline");
        } else {
          statusDot.classList.add("offline");
        }
      }
    });
  }
};
