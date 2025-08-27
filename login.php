<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - NodeZer0</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .font-mono {
            font-family: 'Roboto Mono', monospace;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-300 flex items-center justify-center min-h-screen">

    <div class="bg-gray-800 border border-red-500/30 shadow-lg rounded-2xl p-8 w-96">
        <h2 class="text-2xl font-bold mb-6 text-center text-red-500 font-mono">🔐 Secure Login</h2>
        
        <!-- Message area for success or error feedback -->
        <div id="message-area" class="mb-4 text-center text-sm"></div>
        
        <form id="login-form" class="space-y-4">
            <div>
                <label for="username" class="text-sm font-mono text-gray-400">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter Username"
                       class="w-full mt-1 px-4 py-3 bg-gray-700 border border-gray-600 text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
            </div>
            <div>
                <label for="password" class="text-sm font-mono text-gray-400">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter Password"
                       class="w-full mt-1 px-4 py-3 bg-gray-700 border border-gray-600 text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
            </div>
            <button type="submit"
                    class="w-full bg-red-600 text-white py-3 rounded-lg hover:bg-red-700 transition font-semibold !mt-6">
                Login
            </button>
        </form>
        
        <p class="text-center text-sm text-gray-500 mt-6">
            Don't have an account? <a href="./register.php" class="text-orange-400 hover:underline">Register here</a>.
        </p>
    </div>

</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function() {
    $('#login-form').on('submit', function(e) {
        // Prevent the default form submission which causes the page to refresh
        e.preventDefault();

        const messageArea = $('#message-area');
        
        // Collect the data from the form fields
        const formData = {
            username: $('#username').val(),
            password: $('#password').val()
        };

        // Perform the AJAX request to the login API
        $.ajax({
            url: './api/login.php', // The path to your PHP login script
            type: 'POST',
            contentType: 'application/json', // Let the server know we're sending JSON
            data: JSON.stringify(formData), // Convert the data object to a JSON string
            dataType: 'json', // Expect a JSON response from the server
            success: function(response) {
                // Handle the response from the server
                if (response.status === 'success') {
                    messageArea.text(response.message).css('color', 'lightgreen');
                    // Redirect to the events page after a successful login
                    setTimeout(function() {
                        window.location.href = 'events.php';
                    }, 1500); // 1.5-second delay
                } else {
                    messageArea.text(response.message || 'Login failed.').css('color', 'tomato');
                }
            },
            error: function() {
                // Handle server errors
                messageArea.text('An error occurred. Please try again.').css('color', 'tomato');
            }
        });
    });
});
</script>

</html>
