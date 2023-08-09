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

// setInterval(() => {
//   fetchUsers();
// }, 500);
