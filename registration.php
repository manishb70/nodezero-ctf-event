<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - NodeZer0</title>
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
<body class="bg-gray-900 text-gray-300 flex items-center justify-center min-h-screen py-12">

    <div class="bg-gray-800 border border-red-500/30 shadow-lg rounded-2xl p-8 w-96">
        <h2 class="text-2xl font-bold mb-6 text-center text-red-500 font-mono">🚀 Create Account</h2>
        
        <!-- Message area for success or error feedback -->
        <div id="message-area" class="mb-4 text-center text-sm"></div>

        <form id="register-form" class="space-y-4">
            <div>
                <label for="username" class="text-sm font-mono text-gray-400">Username</label>
                <input type="text" id="username" name="username" placeholder="Choose a username"
                       class="w-full mt-1 px-4 py-3 bg-gray-700 border border-gray-600 text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
            </div>
            <div>
                <label for="email" class="text-sm font-mono text-gray-400">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email"
                       class="w-full mt-1 px-4 py-3 bg-gray-700 border border-gray-600 text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
            </div>
            <div>
                <label for="college" class="text-sm font-mono text-gray-400">College Name</label>
                <input type="text" id="college" name="college" placeholder="Enter your college name"
                       class="w-full mt-1 px-4 py-3 bg-gray-700 border border-gray-600 text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
            </div>
            <div>
                <label for="course" class="text-sm font-mono text-gray-400">Course</label>
                <input type="text" id="course" name="course" placeholder="Enter your course"
                       class="w-full mt-1 px-4 py-3 bg-gray-700 border border-gray-600 text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
            </div>
            <div>
                <label for="password" class="text-sm font-mono text-gray-400">Password</label>
                <input type="password" id="password" name="password" placeholder="Create a password"
                       class="w-full mt-1 px-4 py-3 bg-gray-700 border border-gray-600 text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
            </div>
            <div>
                <label for="confirm-password" class="text-sm font-mono text-gray-400">Confirm Password</label>
                <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm your password"
                       class="w-full mt-1 px-4 py-3 bg-gray-700 border border-gray-600 text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
            </div>
            <button type="submit"
                    class="w-full bg-red-600 text-white py-3 rounded-lg hover:bg-red-700 transition font-semibold !mt-6">
                Register
            </button>
        </form>
        
        <p class="text-center text-sm text-gray-500 mt-6">
            Already have an account? <a href="./login.php" class="text-orange-400 hover:underline">Login here</a>.
        </p>
    </div>

</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function() {
    $('#register-form').on('submit', function(e) {
        // Prevent the default form submission which causes the page to refresh
        e.preventDefault();

        const messageArea = $('#message-area');
        
        // Collect all the data from the form fields
        const formData = {
            username: $('#username').val(),
            email: $('#email').val(),
            college_name: $('#college').val(),
            course: $('#course').val(),
            password: $('#password').val(),
            confirm_password: $('#confirm-password').val()
        };

        // Perform the AJAX request to the registration API
        $.ajax({
            url: './api/register.php', // The path to your PHP registration script
            type: 'POST',
            contentType: 'application/json', // Let the server know we're sending JSON
            data: JSON.stringify(formData), // Convert the data object to a JSON string
            dataType: 'json', // Expect a JSON response from the server
            success: function(response) {
                // Handle the response from the server
                if (response.status === 'success') {
                    messageArea.text(response.message).css('color', 'lightgreen');
                    // Redirect to the login page after a short delay
                    setTimeout(function() {
                        window.location.href = 'login.php';
                    }, 2000); // 2-second delay
                } else {
                    messageArea.text(response.message || 'Registration failed.').css('color', 'tomato');
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
