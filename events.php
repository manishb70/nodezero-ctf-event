<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - NodeZer0</title>
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
<body class="bg-gray-900 text-gray-300">

    <?php
        // Always start the session at the very top of the script
        session_start();

        // Check if the user is logged in, otherwise default to 'Guest'
        $username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest';
    ?>

    <!-- Navigation Bar -->
    <?php include 'navbar.php'; ?>

    <main class="container mx-auto max-w-4xl p-4 sm:p-6 md:p-8">

        <header class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-red-500 font-mono">Today's Events</h1>
            <p class="text-gray-400 mt-2">
                Welcome, <span class="font-semibold text-red-500"><?php echo $username; ?></span> 🎉
            </p>
        </header>

        <!-- This container will be populated with events by the script below -->
        <div id="events-container" class="space-y-8">
            <!-- Loading message -->
            <p class="text-center text-gray-500">Loading events...</p>
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="bg-gray-800 border-t border-red-500/10 mt-12">
        <div class="container mx-auto max-w-6xl p-4 sm:p-6 md:p-8 text-center text-gray-500">
            <p>&copy; 2025 NodeZer0. All rights reserved.</p>
        </div>
    </footer>

</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function() {
    const eventsContainer = $('#events-container');

    // Fetch events from the API
    $.ajax({
        url: './api/getEvents.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            // Clear the loading message
            eventsContainer.empty();

            if (response.status === 'success' && response.events.length > 0) {
                // Loop through each event and create a card for it
                response.events.forEach(function(event) {
                    const now = new Date();
                    const startTime = new Date(event.start_time);
                    const endTime = new Date(event.end_time);

                    let statusBadge = '';
                    let cardBorder = 'border-gray-700';
                    let buttonClass = 'bg-gray-600 cursor-not-allowed';
                    let buttonText = 'JOIN Room';

                    // Determine the event's status
                    if (now >= startTime && now <= endTime) {
                        statusBadge = '<span class="bg-green-500/20 text-green-400 text-xs font-mono font-bold px-2.5 py-1 rounded-full">LIVE</span>';
                        cardBorder = 'border-red-500/20 hover:border-red-500';
                        buttonClass = 'bg-red-600 hover:bg-red-700';
                        buttonText = '🚀 JOIN Room';
                    } else if (now < startTime) {
                        statusBadge = '<span class="bg-yellow-500/20 text-yellow-400 text-xs font-mono font-bold px-2.5 py-1 rounded-full">UPCOMING</span>';
                    } else {
                        statusBadge = '<span class="bg-gray-600/20 text-gray-500 text-xs font-mono font-bold px-2.5 py-1 rounded-full">FINISHED</span>';
                    }

                    // Create the HTML for the event card
                    const eventCard = `
                        <div class="bg-gray-800 rounded-lg shadow-lg p-6 transition-all duration-300 ${cardBorder}">
                            <div class="flex justify-between items-start">
                                <h2 class="text-2xl font-bold text-red-500 mb-3 font-mono">${event.name}</h2>
                                ${statusBadge}
                            </div>
                            <p class="text-gray-400 mb-4">${event.description}</p>
                            <div class="flex items-center text-sm text-gray-500 font-mono mb-5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                <span><b>Start:</b> ${event.start_time}</span>
                                <span class="mx-2">|</span>
                                <span><b>End:</b> ${event.end_time}</span>
                            </div>
                            <a href="./room.php?event_id=${event.id}" class="inline-block text-white font-bold py-2 px-6 rounded-md transition-colors duration-300 ${buttonClass}">
                                ${buttonText}
                            </a>
                        </div>
                    `;
                    
                    // Add the new card to the container
                    eventsContainer.append(eventCard);
                });
            } else {
                // Show a message if there are no events
                eventsContainer.html('<p class="text-center text-gray-500">No events scheduled for today.</p>');
            }
        },
        error: function() {
            // Handle API errors
            eventsContainer.html('<p class="text-center text-red-500">Failed to load events. Please try again later.</p>');
        }
    });
});
</script>

</html>
