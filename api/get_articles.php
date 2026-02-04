<?php
// get_articles.php

require_once 'includes/db_connect.php';

$category = isset($_GET['category']) ? $_GET['category'] : null;

$sql = "SELECT a.*, c.category_name, u.username FROM articles a
        JOIN categories c ON a.category_id = c.category_id
        LEFT JOIN users u ON a.author_id = u.user_id";

if ($category) {
    $sql .= " WHERE c.category_name = ? ORDER BY published_date DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $category);
} else {
    $sql .= " ORDER BY published_date DESC LIMIT 10";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="article-card">';
        echo '<img src="' . $row['image_url'] . '" alt="Thumbnail">';
        echo '<h3><a href="article.html?id=' . $row['article_id'] . '">' . $row['title'] . '</a></h3>';
        echo '<p>' . substr(strip_tags($row['content']), 0, 120) . '...</p>';
        echo '<span class="meta">By ' . $row['username'] . ' | ' . date('F j, Y', strtotime($row['published_date'])) . '</span>';
        echo '</div>';
    }
} else {
    echo "<p>No articles found.</p>";
}

$stmt->close();
$conn->close();
?>
