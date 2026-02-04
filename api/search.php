<?php
// search.php

require_once 'includes/db_connect.php';

$keyword = isset($_GET['q']) ? '%' . $_GET['q'] . '%' : '';

if (!$keyword || strlen(trim($_GET['q'])) === 0) {
    echo '<p>Please enter a search term.</p>';
    exit;
}

$sql = "SELECT a.*, c.category_name, u.username FROM articles a
        JOIN categories c ON a.category_id = c.category_id
        LEFT JOIN users u ON a.author_id = u.user_id
        WHERE a.title LIKE ? OR a.content LIKE ?
        ORDER BY published_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $keyword, $keyword);
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
    echo '<p>No results found for your search.</p>';
}

$stmt->close();
$conn->close();
?>
