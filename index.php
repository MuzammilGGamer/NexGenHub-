<!-- <?php
if($_SERVER["REQUEST_METHOD"]=="POST"){
  $content = $_POST['postContent'];
  $file = $_POST['postFile'];
}



?> -->


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Facebook Clone</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div id="login-screen">
    <div class="login-box">
      <h2>Login</h2>
      <input type="text" id="username" placeholder="Username" />
      <input type="password" id="password" placeholder="Password" />
      <button onclick="login()">Login</button>
    </div>
  </div>

  <div id="main-app" style="display:none">
    <div class="navbar">
      <img src="fb.png" alt="Facebook Logo" class="logo" style="border-radius: 50%;"/>
      <input type="text" placeholder="Search Facebook" class="search-bar" />
      <div class="nav-links">
        <a href="#">Home</a>
        <a href="#">Friends</a>
        <a href="#">Videos</a>
        <a href="#">Marketplace</a>
        <a href="#">Groups</a>
        <button onclick="logout()" class="logout-btn">Logout</button>
      </div>
    </div>

    <div class="main-content">
      <div class="sidebar">
        <ul>
          <li>Profile</li>
          <li>Messages</li>
          <li>Events</li>
          <li>Saved</li>
          <li>Settings</li>
        </ul>
      </div>

      <div class="feed">
        <form class="create-post" id="createPostForm" enctype="multipart/form-data" method="POST" action="upload.php">
          <input type="text" id="postContent" name="postContent" placeholder="What's on your mind?" />
          <input type="file" id="postFile" name="postFile" accept="image/*,video/*" />
          <span id="fileName">No file selected</span>
          <button type="submit">Post</button>
        </form>
        <div id="postContainer"></div>
      </div>

      <div class="contacts">
        <h4>Contacts</h4>
        <ul>
          <li>Zeeshan</li>
          <li>Ali</li>
          <li>Zain</li>
          <li>Sain Ullah</li>
        </ul>
      </div>
    </div>

    <footer>
      <p>Facebook Clone &copy; 2025</p>
    </footer>
  </div>

  <script src="script.js"></script>
</body>
</html>
