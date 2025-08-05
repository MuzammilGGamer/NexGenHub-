<?php
header('Content-Type: application/json');
$response = ["success" => false];

$content = isset($_POST['postContent']) ? trim($_POST['postContent']) : '';
$fileUrl = '';
$fileType = '';
$timestamp = date("Y-m-d H:i:s");

$uploadDir = __DIR__ . '/uploads/';
$postsFile = __DIR__ . '/posts.json';

// Check permissions before doing anything
if (!is_writable(dirname($postsFile))) {
    echo json_encode(["success" => false, "error" => "Upload directory is not writable by Apache."]);
    exit;
}
if (file_exists($postsFile) && !is_writable($postsFile)) {
    echo json_encode(["success" => false, "error" => "posts.json is not writable by Apache."]);
    exit;
}
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0777, true)) {
        echo json_encode(["success" => false, "error" => "Failed to create uploads directory."]);
        exit;
    }
}
if (!is_writable($uploadDir)) {
    echo json_encode(["success" => false, "error" => "uploads directory is not writable by Apache."]);
    exit;
}

// Handle file upload if exists
if (isset($_FILES['postFile']) && $_FILES['postFile']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['postFile']['tmp_name'];
    $fileName = basename($_FILES['postFile']['name']);
    $fileType = $_FILES['postFile']['type'];
    $uniqueName = uniqid() . '_' . $fileName;
    $destPath = $uploadDir . $uniqueName;
    if (move_uploaded_file($fileTmpPath, $destPath)) {
        // Make fileUrl relative for web access
        $fileUrl = './uploads/' . $uniqueName;
    } else {
        $response['error'] = 'File upload failed. Check permissions.';
        echo json_encode($response);
        exit;
    }
}

// If there's a post (content or file), save it
if ($content || $fileUrl) {
    $newPost = [
        "id" => uniqid("post_"),
        "content" => htmlspecialchars($content),
        "fileUrl" => $fileUrl,
        "fileType" => $fileType,
        "timestamp" => $timestamp
    ];

    $posts = [];
    if (file_exists($postsFile)) {
        $json = file_get_contents($postsFile);
        $posts = json_decode($json, true) ?? [];
    }
    $posts[] = $newPost;
    if (file_put_contents($postsFile, json_encode($posts, JSON_PRETTY_PRINT))) {
        $response = [
            "success" => true,
            "content" => $newPost['content'],
            "fileUrl" => $newPost['fileUrl'],
            "fileType" => $newPost['fileType'],
            "timestamp" => $newPost['timestamp']
        ];
    } else {
        $response['error'] = 'Failed to write post to file.';
    }
} else {
    $response['error'] = 'No content or file.';
}

echo json_encode($response);
header("location: index.php");
?>
