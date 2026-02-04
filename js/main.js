document.addEventListener("DOMContentLoaded", () => {
  const path = window.location.pathname;
  const urlParams = new URLSearchParams(window.location.search);

  // Helper to fetch and inject HTML
  const fetchHTML = async (url, targetSelector) => {
    const target = document.querySelector(targetSelector);
    if (!target) return;

    try {
      const response = await fetch(url);
      if (!response.ok) throw new Error("Network response was not ok");
      const html = await response.text();
      target.innerHTML = html;
    } catch (error) {
      console.error("Fetch error:", error);
      target.innerHTML = "<p>Error loading content.</p>";
    }
  };

  // 1. Article Page
  if (path.includes("article.html")) {
    const id = urlParams.get("id");
    if (id) {
      // Fetch article content
      // The API returns the whole article block.
      // However, the HTML has specific placeholders like #article-title.
      // The PHP API returns: <h1>title</h1><p meta>...</p><img src>...<div>content</div>
      // So we should target the parent container `article.full-article` or `#article-content`?
      // The PHP output seems to replace the internal structure of the article.
      // Let's replace the content of `article.full-article` entirely if possible,
      // or just inject into `#article-content` if the PHP only returns content?
      // Checking api/article.php: It echoes H1, P(meta), IMG, DIV(content).
      // So it reproduces the structure of what is inside `article.full-article` (roughly).
      // The HTML has: h1#article-title, p.article-meta, img#article-image, div#article-content.
      // So if we inject the PHP response into `article.full-article`, it will replace the placeholders.
      fetchHTML(`/api/article.php?id=${id}`, ".full-article");
    }
  }

  // 2. Category Page
  if (path.includes("category.html")) {
    const cat = urlParams.get("cat");
    if (cat) {
      document.getElementById("category-title").textContent = cat;
      fetchHTML(
        `/api/get_articles.php?category=${encodeURIComponent(cat)}`,
        "#articles-container",
      );
    }
  }

  // 3. Home Page
  if (path.includes("index.html") || path.endsWith("/")) {
    // Fetch specific categories or latest
    // For simplicity, let's fill "Latest News" or "Featured"
    // targeting .article-grid (Featured)
    fetchHTML("/api/get_articles.php", ".article-grid");

    // Also fill "Latest News" list?
    fetchHTML("/api/get_articles.php?limit=5", ".latest-list");

    // Technology section
    fetchHTML(
      "/api/get_articles.php?category=Technology",
      ".category-articles",
    );
  }

  // 4. Search
  const searchInput = document.getElementById("search-bar");
  if (searchInput) {
    searchInput.addEventListener("keypress", (e) => {
      if (e.key === "Enter") {
        const query = searchInput.value;
        if (query) {
          // Redirect to a search page? The project has search.php api but no search.html
          // We can reuse category.html?
          // Or create a simple search result handling if we are on a page with a container.
          // For now, let's just log it or alert, as there is no search.html page.
          alert(
            "Search functionality requires a dedicated search page or overlay.",
          );
        }
      }
    });
  }
});
