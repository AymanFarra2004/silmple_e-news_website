# Global News Network - Vercel Deployment

This project is configured for deployment on Vercel using the `vercel-php` runtime.

## Deployment Steps

1.  **Install Vercel CLI** (optional, if deploying from terminal):

    ```bash
    npm i -g vercel
    ```

2.  **Deploy**:
    Run the following command in the project root:

    ```bash
    vercel
    ```

    Or connect this repository to your Vercel account via dashboard.

3.  **Environment Variables**:
    **CRITICAL**: You must set the following environment variables in your Vercel Project Settings for the database connection to work. Vercel is serverless, so your database must be hosted remotely (e.g., Aiven, PlanetScale, AWS RDS, or a VPS).
    - `DB_HOST`: The hostname of your database.
    - `DB_USER`: Your database username.
    - `DB_PASS`: Your database password.
    - `DB_NAME`: The name of your database (default code uses `news_db` if not set, but you should set it).
    - `DB_PORT`: Database port (default `3306`).

## Project Structure

- `api/`: Contains PHP backend files (Serverless Functions).
- `js/`: Contains `main.js` which fetches data from the API.
- `*.html`: Frontend pages.
- `vercel.json`: Configuration for PHP runtime on Vercel.

## Note on Database

Since this is a serverless deployment, ensure your database allows external connections from Vercel's IP addresses (or allow 0.0.0.0/0 if testing).
