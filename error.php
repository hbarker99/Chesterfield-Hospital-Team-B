<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Page</title>
    <link rel="stylesheet" href="style.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">


    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .error-container {
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .error-icon {
            font-size: 48px;
            color: #e74c3c;
        }

        .error-message {
            font-size: 18px;
            margin-top: 10px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">⚠</div>
        <div class="error-message">Oops! Something went wrong.</div>
        <br>
        <button onclick="window.location.href = '/';" class="btn btn-primary">Choose a route</button>

        <!-- You can add more details or instructions here -->
    </div>
</body>
</html>
