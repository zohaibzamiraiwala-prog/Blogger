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
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete'])) {
        $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        echo '<script>window.location.href = "index.php";</script>';
        exit;
    } else {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $excerpt = substr(strip_tags($content), 0, 200) . '...';
        $author = $_POST['author'];
        $category = $_POST['category'];
 
        $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ?, excerpt = ?, author = ?, category = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $title, $content, $excerpt, $author, $category, $id);
        $stmt->execute();
        $stmt->close();
        echo '<script>window.location.href = "view.php?id=' . $id . '";</script>';
        exit;
    }
}
 
$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();
$stmt->close();
if (!$post) {
    die('Post not found');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Blog Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Edit Post</h1>
        <form method="post">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($post['title']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="author" class="form-label">Author</label>
                <input type="text" id="author" name="author" class="form-control" value="<?php echo htmlspecialchars($post['author']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select id="category" name="category" class="form-control">
                    <option value="Technology" <?php if ($post['category'] == 'Technology') echo 'selected'; ?>>Technology</option>
                    <option value="Lifestyle" <?php if ($post['category'] == 'Lifestyle') echo 'selected'; ?>>Lifestyle</option>
                    <option value="Business" <?php if ($post['category'] == 'Business') echo 'selected'; ?>>Business</option>
                    <option value="Travel" <?php if ($post['category'] == 'Travel') echo 'selected'; ?>>Travel</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="editor" class="form-label">Content</label>
                <textarea id="editor" name="content"><?php echo htmlspecialchars($post['content']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <button type="submit" name="delete" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this post?');">Delete</button>
        </form>
    </div>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
<?php
$conn->close();
?>
