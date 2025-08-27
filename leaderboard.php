<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard - NodeZer0</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Roboto+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-mono { font-family: 'Roboto Mono', monospace; }
    </style>
</head>
<body class="bg-gray-900 text-gray-300">

    <?php include 'navbar.php'; ?>

    <main class="container mx-auto max-w-4xl p-4 sm:p-6 md:p-8">
        <header class="text-center mb-8">
            <h1 class="text-4xl md:text-5xl font-bold text-red-500 font-mono">Leaderboard</h1>
            <p class="text-gray-400 mt-2">See who's leading the pack in each event.</p>
        </header>

        <!-- Event Filter -->
        <div class="mb-8 flex items-center justify-between">
            <div class="w-full md:w-1/2">
                <label for="event-select" class="text-sm font-mono text-gray-400">Select Event</label>
                <select id="event-select" class="w-full mt-1 px-4 py-3 bg-gray-700 border border-gray-600 text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option>Loading events...</option>
                </select>
            </div>
            <div id="live-indicator" class="hidden items-center gap-2 bg-green-500/20 text-green-400 font-mono font-bold px-4 py-2 rounded-full">
                <span class="relative flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                </span>
                LIVE
            </div>
        </div>

        <!-- Leaderboard Container -->
        <div id="leaderboard-container" class="space-y-4">
            <p class="text-center text-gray-500">Please select an event to view the leaderboard.</p>
        </div>
    </main>
    
    <footer class="bg-gray-800 border-t border-red-500/10 mt-16">
        <div class="container mx-auto max-w-6xl p-4 sm:p-6 md:p-8 text-center text-gray-500">
            <p>&copy; 2025 NodeZer0. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(function() {
            const eventSelect = $('#event-select');
            const leaderboardContainer = $('#leaderboard-container');
            const liveIndicator = $('#live-indicator');

            // Function to fetch and display the leaderboard for a given event
            function loadLeaderboard(eventId) {
                if (!eventId) {
                    leaderboardContainer.html('<p class="text-center text-gray-500">Please select an event.</p>');
                    liveIndicator.addClass('hidden');
                    return;
                }

                leaderboardContainer.html('<p class="text-center text-gray-500">Loading leaderboard...</p>');

                $.ajax({
                    url: `./api/get_leaderboard.php?event_id=${eventId}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        leaderboardContainer.empty();
                        
                        if (response.is_live) {
                            liveIndicator.removeClass('hidden').addClass('flex');
                        } else {
                            liveIndicator.addClass('hidden');
                        }

                        if (response.status === 'success' && response.leaderboard.length > 0) {
                            const topScore = response.leaderboard[0].score;

                            response.leaderboard.forEach(function(player, index) {
                                const rank = index + 1;
                                const scorePercentage = topScore > 0 ? (player.score / topScore) * 100 : 0;

                                const userBar = `
                                    <div class="relative bg-gray-800 rounded-lg shadow-lg border ${rank === 1 ? 'border-red-500/30' : 'border-gray-700'} overflow-hidden">
                                        <div class="absolute top-0 left-0 h-full bg-red-500/20" style="width: ${scorePercentage}%;"></div>
                                        <div class="relative flex items-center justify-between p-4">
                                            <div class="flex items-center">
                                                <span class="text-xl font-bold text-gray-400 w-12">${rank}</span>
                                                <span class="font-medium text-white text-lg">${player.username}</span>
                                            </div>
                                            <span class="font-mono font-bold text-orange-400 text-lg">${player.score}</span>
                                        </div>
                                    </div>
                                `;
                                leaderboardContainer.append(userBar);
                            });
                        } else {
                            leaderboardContainer.html('<p class="text-center text-gray-500">No scores recorded for this event yet.</p>');
                        }
                    },
                    error: function() {
                        leaderboardContainer.html('<p class="text-center text-red-500">Failed to load leaderboard.</p>');
                    }
                });
            }

            // 1. Fetch all events to populate the dropdown
            $.ajax({
                url: './api/get_all_events.php',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    eventSelect.empty();
                    if (response.status === 'success' && response.events.length > 0) {
                        eventSelect.append('<option value="">-- Select an Event --</option>');
                        response.events.forEach(function(event) {
                            eventSelect.append(`<option value="${event.id}">${event.name}</option>`);
                        });
                    } else {
                        eventSelect.append('<option>No events found</option>');
                    }
                },
                error: function() {
                    eventSelect.empty().append('<option>Failed to load events</option>');
                }
            });

            // 2. Add event listener to the dropdown
            eventSelect.on('change', function() {
                const selectedEventId = $(this).val();
                loadLeaderboard(selectedEventId);
            });
        });
    </script>
</body>
</html>
