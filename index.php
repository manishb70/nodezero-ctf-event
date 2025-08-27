<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to NodeZer0</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Roboto+Mono:wght@400;500;700&display=swap" rel="stylesheet">
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

    <?php include 'navbar.php'; ?>

    <!-- Hero Section -->
    <main class="container mx-auto max-w-6xl p-4 sm:p-6 md:p-8">
        <div class="text-center py-16 md:py-24">
            <h1 class="text-4xl md:text-6xl font-extrabold text-white font-mono leading-tight">
                Welcome to <span class="text-red-500">NodeZer0</span>
            </h1>
            <p class="mt-4 max-w-2xl mx-auto text-lg text-gray-400">
                Your ultimate training ground for cybersecurity. Sharpen your skills, compete in challenges, and become a security expert.
            </p>
            <div class="mt-8 flex justify-center gap-4">
                <a href="./register.php" class="inline-block bg-red-600 text-white font-bold py-3 px-8 rounded-md hover:bg-red-700 transition-colors duration-300">
                    Get Started
                </a>
                <a href="./labs.php" class="inline-block bg-gray-700 text-white font-bold py-3 px-8 rounded-md hover:bg-gray-600 transition-colors duration-300">
                    View Labs
                </a>
            </div>
        </div>

        <!-- Features Section -->
        <div class="py-16 md:py-24 border-t border-red-500/10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div class="bg-gray-800 p-8 rounded-lg border border-gray-700">
                    <div class="text-orange-400 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold font-mono text-white">Real-world Labs</h3>
                    <p class="mt-2 text-gray-400">Practice with hands-on labs that simulate real-world security scenarios and vulnerabilities.</p>
                </div>
                <div class="bg-gray-800 p-8 rounded-lg border border-gray-700">
                    <div class="text-orange-400 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold font-mono text-white">Skill Development</h3>
                    <p class="mt-2 text-gray-400">Track your progress and develop in-demand cybersecurity skills from beginner to expert.</p>
                </div>
                <div class="bg-gray-800 p-8 rounded-lg border border-gray-700">
                    <div class="text-orange-400 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold font-mono text-white">Community Events</h3>
                    <p class="mt-2 text-gray-400">Join live events, workshops, and competitions. Learn with others and test your skills.</p>
                </div>
            </div>
        </div>

        <!-- Upcoming Events Section -->
        <div class="py-16 md:py-24 border-t border-red-500/10">
            <h2 class="text-3xl font-bold text-center text-white font-mono mb-12">Upcoming Events</h2>
            <div id="upcoming-events-container" class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Events will be loaded here -->
                <p class="text-center text-gray-500 md:col-span-3">Loading upcoming events...</p>
            </div>
        </div>
        
        <!-- Top Players Section -->
        <div class="py-16 md:py-24 border-t border-red-500/10">
            <h2 class="text-3xl font-bold text-center text-white font-mono mb-12">Top Players</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <!-- Player 2 -->
                <div class="bg-gray-800 p-8 rounded-lg border-2 border-gray-600 mt-8 md:mt-0">
                    <p class="text-4xl mb-2">🥈</p>
                    <h3 class="text-xl font-bold font-mono text-white">Bob</h3>
                    <p class="text-orange-400 font-mono">1450 Points</p>
                </div>
                <!-- Player 1 -->
                <div class="bg-gray-800 p-8 rounded-lg border-2 border-yellow-400 transform md:-translate-y-8">
                    <p class="text-5xl mb-2">🥇</p>
                    <h3 class="text-2xl font-bold font-mono text-yellow-400">Alice</h3>
                    <p class="text-orange-400 font-mono">1500 Points</p>
                </div>
                <!-- Player 3 -->
                <div class="bg-gray-800 p-8 rounded-lg border-2 border-yellow-700 mt-8 md:mt-0">
                     <p class="text-4xl mb-2">🥉</p>
                    <h3 class="text-xl font-bold font-mono text-white">Charlie</h3>
                    <p class="text-orange-400 font-mono">1300 Points</p>
                </div>
            </div>
        </div>

        <!-- Call to Action Section -->
        <div class="text-center py-16">
            <h2 class="text-3xl font-bold text-white font-mono">Ready to Start?</h2>
            <p class="mt-4 max-w-2xl mx-auto text-lg text-gray-400">
                Create an account today and join the NodeZer0 community. The next challenge awaits.
            </p>
            <div class="mt-8">
                <a href="./register.php" class="inline-block bg-red-600 text-white font-bold py-3 px-8 rounded-md hover:bg-red-700 transition-colors duration-300">
                    Join Now
                </a>
            </div>
        </div>
    </main>
    
    <?php include 'footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(function() {
            const eventsContainer = $('#upcoming-events-container');

            $.ajax({
                url: './api/get_upcoming_events.php',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    eventsContainer.empty();
                    if (response.status === 'success' && response.events.length > 0) {
                        response.events.forEach(function(event) {
                            const eventDate = new Date(event.start_time);
                            const formattedDate = eventDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                            const formattedTime = eventDate.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });

                            const eventCard = `
                                <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 flex flex-col">
                                    <div class="flex-grow">
                                        <p class="text-sm font-mono text-red-400">${formattedDate} at ${formattedTime}</p>
                                        <h3 class="text-xl font-bold font-mono text-white mt-2">${event.name}</h3>
                                        <p class="mt-2 text-gray-400 text-sm">${event.description.substring(0, 100)}...</p>
                                    </div>
                                    <div class="mt-4">
                                        <a href="./events.php" class="inline-block bg-gray-700 text-white font-bold py-2 px-4 rounded-md text-sm hover:bg-gray-600">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            `;
                            eventsContainer.append(eventCard);
                        });
                    } else {
                        eventsContainer.html('<p class="text-center text-gray-500 md:col-span-3">No upcoming events scheduled at this time.</p>');
                    }
                },
                error: function() {
                    eventsContainer.html('<p class="text-center text-red-500 md:col-span-3">Could not load upcoming events.</p>');
                }
            });
        });
    </script>

</body>
</html>
