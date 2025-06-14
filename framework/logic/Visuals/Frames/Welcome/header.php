<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?=$title?></title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(to right, #eef2f3, #8e9eab);
      color: #222;
    }

    .welcome-container {
      text-align: center;
      animation: fadeIn 2s ease-out;
    }

    h1 {
      font-size: 3rem;
      margin-bottom: 1rem;
    }

    i {
      font-style: italic;
      color: #0066cc;
    }

    b {
      font-weight: bold;
      color: #28a745;
    }

    .tagline {
      font-size: 1.2rem;
      color: #555;
      letter-spacing: 1px;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
