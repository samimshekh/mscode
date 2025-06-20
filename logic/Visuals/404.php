<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>404 - Page Not Found</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #0f1117;
      color: #ffffff;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      text-align: center;
      background: linear-gradient(135deg, #1f222a, #0f1117);
      animation: fadeIn 1s ease-in-out;
    }

    h1 {
      font-size: 8rem;
      color: #e94560;
      animation: pulse 2s infinite;
    }

    h2 {
      font-size: 2rem;
      margin-top: 0.5rem;
      color: #eeeeee;
    }

    p {
      font-size: 1.1rem;
      margin: 1rem 0 2rem;
      color: #cfcfcf;
    }

    a {
      display: inline-block;
      padding: 12px 24px;
      background-color: #e94560;
      color: #fff;
      border-radius: 8px;
      text-decoration: none;
      font-weight: bold;
      transition: background 0.3s ease;
    }

    a:hover {
      background-color: #d1364d;
    }

    @keyframes pulse {
      0%, 100% {
        transform: scale(1);
        opacity: 1;
      }
      50% {
        transform: scale(1.05);
        opacity: 0.8;
      }
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @media (max-width: 600px) {
      h1 {
        font-size: 5rem;
      }
      h2 {
        font-size: 1.5rem;
      }
    }
  </style>
</head>
<body>
  <h1>404</h1>
  <h2>Page Not Found</h2>
  <p>Sorry, the page you are looking for doesn't exist or has been moved.</p>
  <a href="/">Go Back Home</a>
</body>
</html>
