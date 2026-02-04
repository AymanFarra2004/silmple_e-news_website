<?php
// article.php

require_once 'includes/db_connect.php';

$article_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($article_id <= 0) {
    echo "<p>Invalid article ID.</p>";
    exit;
}

$sql = "SELECT a.*, c.category_name, u.username FROM articles a
        JOIN categories c ON a.category_id = c.category_id
        LEFT JOIN users u ON a.author_id = u.user_id
        WHERE a.article_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $article_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    echo '<h1>' . $row['title'] . '</h1>';
    echo '<p class="article-meta">By ' . $row['username'] . ' | ' . date('F j, Y', strtotime($row['published_date'])) . '</p>';
    echo '<img src="' . $row['image_url'] . '" alt="Article Image" style="max-width:100%; margin-bottom: 1rem;">';
    echo '<div>' . nl2br($row['content']) . '</div>';
} else {
    echo "<p>Article not found.</p>";
}

$stmt->close();
$conn->close();
?>
