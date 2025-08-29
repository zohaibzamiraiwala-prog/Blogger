<?php
$db_host = 'localhost';
$db_user = 'unkuodtm3putf';
$db_pass = 'htk2glkxl4n4';
$db_name = 'dblhtkckbipyma';
 
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
 
if (!isset($_GET['id'])) {
    die('No post ID provided');
}
$id = intval($_GET['id']);
 
$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();
$stmt->close();
if (!$post) {
    die('Post not found');
}
 
$comm_stmt = $conn->prepare("SELECT * FROM comments WHERE post_id = ? ORDER BY date DESC");
$comm_stmt->bind_param("i", $id);
$comm_stmt->execute();
$comm_result = $comm_stmt->get_result();
 
$rel_stmt = $conn->prepare("SELECT * FROM posts WHERE category = ? AND id != ? ORDER BY publish_date DESC LIMIT 3");
$rel_stmt->bind_param("si", $post['category'], $id);
$rel_stmt->execute();
$rel_result = $rel_stmt->get_result();
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $author = $_POST['author'];
    $content = $_POST['content'];
    $date = date('Y-m-d H:i:s');
 
    $ins_stmt = $conn->prepare("INSERT INTO comments (post_id, author, content, date) VALUES (?, ?, ?, ?)");
    $ins_stmt->bind_param("isss", $id, $author, $content, $date);
    $ins_stmt->execute();
    $ins_stmt->close();
 
    echo '<script>window.location.href = "view.php?id=' . $id . '";</script>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .post-content {
            line-height: 1.8;
            font-size: 1.1rem;
        }
        .card {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($post['title']); ?></h1>
        <p class="text-muted">By <?php echo htmlspecialchars($post['author']); ?> on <?php echo $post['publish_date']; ?> in <?php echo htmlspecialchars($post['category']); ?></p>
        <div class="post-content mb-4"><?php echo $post['content']; ?></div>
        <a href="edit.php?id=<?php echo $id; ?>" class="btn btn-warning mb-4">Edit Post</a>
        <h3>Related Posts</h3>
        <ul class="list-group mb-4">
            <?php while ($rel = $rel_result->fetch_assoc()) { ?>
                <li class="list-group-item"><a href="view.php?id=<?php echo $rel['id']; ?>"><?php echo htmlspecialchars($rel['title']); ?></a></li>
            <?php } ?>
        </ul>
        <h3>Comments</h3>
        <?php while ($comm = $comm_result->fetch_assoc()) { ?>
            <div class="card mb-2">
                <div class="card-body">
                    <p><?php echo nl2br(htmlspecialchars($comm['content'])); ?></p>
                    <small class="text-muted">By <?php echo htmlspecialchars($comm['author']); ?> on <?php echo $comm['date']; ?></small>
                </div>
            </div>
        <?php } ?>
        <h4 class="mt-4">Add a Comment</h4>
        <form method="post">
            <div class="mb-3">
                <label for="author" class="form-label">Author</label>
                <input type="text" id="author" name="author" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Comment</label>
                <textarea id="content" name="content" class="form-control" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit Comment</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
<?php
$comm_stmt->close();
$rel_stmt->close();
$conn->close();
?>
