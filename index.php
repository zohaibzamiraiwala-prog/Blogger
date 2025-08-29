<?php
$db_host = 'localhost';
$db_user = 'unkuodtm3putf';
$db_pass = 'htk2glkxl4n4';
$db_name = 'dblhtkckbipyma';
 
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
 
$stmt = $conn->prepare("SELECT * FROM posts ORDER BY publish_date DESC");
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Homepage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .post-card {
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .post-card:hover {
            transform: translateY(-5px);
        }
        h1, h2, h3 {
            color: #333;
        }
        a {
            text-decoration: none;
        }
        .list-group-item a {
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">My Blog</h1>
        <a href="create.php" class="btn btn-primary mb-3">Create New Post</a>
        <form action="search.php" method="get" class="mb-3">
            <input type="text" name="q" class="form-control" placeholder="Search posts..." required>
        </form>
        <h3>Categories</h3>
        <ul class="list-group mb-4">
            <li class="list-group-item"><a href="category.php?cat=Technology">Technology</a></li>
            <li class="list-group-item"><a href="category.php?cat=Lifestyle">Lifestyle</a></li>
            <li class="list-group-item"><a href="category.php?cat=Business">Business</a></li>
            <li class="list-group-item"><a href="category.php?cat=Travel">Travel</a></li>
        </ul>
        <h2>Recent Posts</h2>
        <?php while ($post = $result->fetch_assoc()) { ?>
            <div class="card post-card">
                <div class="card-body">
                    <h5 class="card-title"><a href="view.php?id=<?php echo $post['id']; ?>"><?php echo htmlspecialchars($post['title']); ?></a></h5>
                    <p class="card-text"><?php echo htmlspecialchars($post['excerpt']); ?></p>
                    <p class="card-text"><small class="text-muted">By <?php echo htmlspecialchars($post['author']); ?> on <?php echo $post['publish_date']; ?></small></p>
                </div>
            </div>
        <?php } ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
