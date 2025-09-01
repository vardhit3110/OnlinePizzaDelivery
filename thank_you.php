<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background: linear-gradient(135deg,rgb(186, 186, 186),rgb(44, 136, 190)); /* Gradient background */
            color: #333; /* Dark gray text */
            font-family: 'Arial', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .thank-you-container {
            background: rgb(226, 248, 255);; /* White background */
            border-radius: 10px;
            padding: 40px;
            text-align: center;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 4px 10px rgba(102, 46, 181, 0.35); /* Subtle shadow */
            animation: fadeIn 1s ease-in-out;
        }

        .thank-you-container h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            font-weight: bold;
            color: #333; /* Dark gray heading */
        }

        .thank-you-container p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            color: #666; /* Medium gray text */
        }

        .thank-you-container .btn {
            background: #007bff; /* Bootstrap primary blue */
            border: none;
            padding: 10px 30px;
            font-size: 1rem;
            border-radius: 25px;
            transition: background 0.3s ease;
            color: #fff; /* White text */
        }

        .thank-you-container .btn:hover {
            background-color:rgb(17, 0, 173); /* Darker purple on hover */
            transform: translateY(-3px); /* Slight lift */
            box-shadow: 0 8px 25px rgba(108, 92, 231, 0.5);
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
    </style>
</head>
<body>
    <div class="thank-you-container">
        <h1>Thank You!</h1>
        <p>Your review has been submitted successfully. We appreciate your feedback and will use it to improve our services.</p>
        <a href="index.php" class="btn btn-primary">Return to Home</a>
    </div>
</body>
</html>