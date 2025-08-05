function loadPosts() {
  fetch("load_posts.php")
    .then(res => res.json())
    .then(posts => {
      const postContainer = document.getElementById("postContainer");
      if (postContainer) postContainer.innerHTML = "";
      posts.reverse().forEach(post => {
        addPostToFeed(post.content, post.fileUrl, post.fileType);
      });
    });
}

function login() {
  const user = document.getElementById("username").value.trim();
  const pass = document.getElementById("password").value.trim();
  if (user && pass) {
    // Save credentials for next time
    localStorage.setItem("fb_username", user);
    localStorage.setItem("fb_password", pass);
    document.getElementById("login-screen").style.display = "none";
    document.getElementById("main-app").style.display = "block";
    loadPosts();
  } else {
    alert("Please enter both username and password.");
  }
}

window.addEventListener("DOMContentLoaded", function() {
  // Auto-login if credentials are saved
  const savedUser = localStorage.getItem("fb_username");
  const savedPass = localStorage.getItem("fb_password");
  if (savedUser && savedPass) {
    document.getElementById("login-screen").style.display = "none";
    document.getElementById("main-app").style.display = "block";
    loadPosts();
    return;
  }
  const username = document.getElementById("username");
  const password = document.getElementById("password");
  if (username && password) {
    username.addEventListener("keydown", function(e) {
      if (e.key === "Enter") login();
    });
    password.addEventListener("keydown", function(e) {
      if (e.key === "Enter") login();
    });
  }


  const postForm = document.getElementById("createPostForm");
  if (postForm) {
    postForm.addEventListener("submit", function(e) {
      e.preventDefault();
      const formData = new FormData(postForm);
      fetch("upload/upload.php", {
        method: "POST",
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          addPostToFeed(data.content, data.fileUrl, data.fileType);
          postForm.reset();
        } else {
          alert(data.error || "Upload failed.");
        }
      })
      .catch(() => alert("Upload error."));
    });
  }

  // Auto-load posts on page load
  if (document.getElementById("main-app").style.display !== "none") {
    loadPosts();
  }
});

function addPostToFeed(content, fileUrl, fileType) {
  const postContainer = document.getElementById("postContainer");
  const post = document.createElement("div");
  post.className = "post";
  let mediaHtml = "";
  if (fileUrl && fileType) {
    if (fileType.startsWith("image/")) {
      mediaHtml = `<img src="${fileUrl}" alt="Image" style="max-width:100%;margin-top:8px;" />`;
    } else if (fileType.startsWith("video/")) {
      mediaHtml = `<video controls style="max-width:100%;margin-top:8px;"><source src="${fileUrl}" type="${fileType}"></video>`;
    }
  }
  post.innerHTML = `<h3>You</h3>${content ? `<p>${content}</p>` : ""}${mediaHtml}`;
  postContainer.prepend(post);
}

function logout() {
  document.getElementById("main-app").style.display = "none";
  document.getElementById("login-screen").style.display = "flex";
  document.getElementById("username").value = "";
  document.getElementById("password").value = "";
  localStorage.removeItem("fb_username");
  localStorage.removeItem("fb_password");
}

function createPost() {
  const content = document.getElementById("postContent").value.trim();
  const fileInput = document.getElementById("postFile");
  const file = fileInput && fileInput.files[0];
  if (!content && !file) return;

  const postContainer = document.getElementById("postContainer");
  const post = document.createElement("div");
  post.className = "post";
  let mediaHtml = "";
  if (file) {
    const url = URL.createObjectURL(file);
    if (file.type.startsWith("image/")) {
      mediaHtml = `<img src="${url}" alt="Image" style="max-width:100%;margin-top:8px;" />`;
    } else if (file.type.startsWith("video/")) {
      mediaHtml = `<video controls style="max-width:100%;margin-top:8px;"><source src="${url}" type="${file.type}"></video>`;
    }
  }
  post.innerHTML = `<h3>You</h3>${content ? `<p>${content}</p>` : ""}${mediaHtml}`;
  postContainer.prepend(post);

  document.getElementById("postContent").value = "";
  if (fileInput) fileInput.value = "";
}
document.getElementById("postFile").addEventListener("change", function() {
  const fileName = this.files[0] ? this.files[0].name : "";
  document.getElementById("fileName").textContent = fileName;
});
document.getElementById("fileName").textContent = "No file selected";
document.getElementById("postFile").addEventListener("change", function() {
  const fileName = this.files[0] ? this.files[0].name : "No file selected";
  document.getElementById("fileName").textContent = fileName;
});
// ...existing code...
