<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Room - NodeZer0</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            scroll-behavior: smooth;
        }
        .font-mono {
            font-family: 'Roboto Mono', monospace;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-300">
    <?php include 'navbar.php'; ?>

    <!-- Join Event Modal (Hidden by default) -->
    <div id="join-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75 hidden">
        <div class="bg-gray-800 border border-red-500/30 shadow-lg rounded-2xl p-8 w-96 text-center">
            <h2 class="text-2xl font-bold mb-4 text-white font-mono">Join Event?</h2>
            <p class="text-gray-400 mb-6">Do you want to join this event and start the challenges?</p>
            <div id="modal-message" class="mb-4 text-sm"></div>
            <div class="flex justify-center gap-4">
                <button id="decline-join" class="bg-gray-600 text-white font-bold py-2 px-6 rounded-md hover:bg-gray-700 transition-colors">Decline</button>
                <button id="accept-join" class="bg-red-600 text-white font-bold py-2 px-6 rounded-md hover:bg-red-700 transition-colors">Accept</button>
            </div>
        </div>
    </div>

    <!-- Main Content Area (Initially Hidden) -->
    <div id="main-content" class="container mx-auto max-w-6xl p-4 sm:p-6 md:p-8 hidden">
        <div class="flex flex-col lg:flex-row gap-8">

            <!-- Left Side: Sequential Lab Details -->
            <div class="w-full lg:w-2/3">
                <header class="mb-12 text-center lg:text-left">
                    <h1 class="text-4xl md:text-5xl font-bold text-red-500 font-mono" id="event-title">Event Labs</h1>
                    <p class="text-gray-400 mt-2">Complete the challenges below to earn points.</p>
                </header>
                
                <div id="labs-container" class="space-y-12">
                    <p class="text-center text-gray-500">Loading labs...</p>
                </div>
            </div>

            <!-- Right Side: Sidebar -->
            <div class="w-full lg:w-1/3">
                <div class="bg-gray-800 rounded-lg shadow-lg border border-gray-700 p-6 sticky top-24">
                    <div class="text-center border-b border-gray-700 pb-4">
                        <p class="text-sm text-gray-400 font-mono">Time Left</p>
                        <p id="countdown-timer" class="text-4xl font-bold text-orange-400 font-mono">--:--</p>
                    </div>
                    <div class="text-center border-b border-gray-700 py-4">
                        <p class="text-sm text-gray-400 font-mono">Your Rank</p>
                        <p id="user-rank" class="text-4xl font-bold text-red-500 font-mono">#--</p>
                    </div>
                    <div class="text-center pt-4">
                        <p class="text-sm text-gray-400 font-mono">Current Time</p>
                        <p id="current-time" class="text-2xl font-bold text-gray-400 font-mono">--:--:--</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Global variables
        let allLabsData = [];
        let completedLabs = [];

        // Function to handle flag submission
        function handleFlagSubmit(form, labId, nextLabId) {
            form.on('submit', function(e) {
                e.preventDefault();
                const flag = $(this).find('input[type="text"]').val();
                const messageArea = $(`#message-lab-${labId}`);
                const submitButton = $(this).find('button');

                const submissionData = {
                    lab_id: labId,
                    flag: flag
                };

                $.ajax({
                    url: './api/submit_flag.php',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(submissionData),
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            messageArea.text(response.message).css('color', 'lightgreen').removeClass('hidden');
                            form.find('input, button').prop('disabled', true);
                            submitButton.text('COMPLETED').addClass('bg-gray-600 hover:bg-gray-600 cursor-not-allowed');
                            
                            if (nextLabId) {
                                $(`#next-lab-${labId}`).removeClass('hidden');
                            } else {
                                messageArea.append('<p class="mt-2">You have completed all labs in this event!</p>');
                            }
                        } else {
                            messageArea.text(response.message || 'An error occurred.').css('color', 'tomato').removeClass('hidden');
                        }
                    },
                    error: function() {
                        messageArea.text('Could not connect to the server. Please try again.').css('color', 'tomato').removeClass('hidden');
                    }
                });
            });
        }
        
        // This function now contains all the logic to load and display labs
        function loadLabContent(eventId) {
            const labsContainer = $('#labs-container');

            // Fetch all labs and user's completed labs simultaneously
            const labsRequest = $.ajax({
                url: `./api/get_labs.php?event_id=${eventId}`,
                type: 'GET',
                dataType: 'json'
            });

            const submissionsRequest = $.ajax({
                url: `./api/get_submissions.php?event_id=${eventId}`,
                type: 'GET',
                dataType: 'json'
            });

            $.when(labsRequest, submissionsRequest).done(function(labsResponse, submissionsResponse) {
                labsContainer.empty();

                if (labsResponse[0].status === 'success' && Array.isArray(labsResponse[0].labs)) {
                    allLabsData = labsResponse[0].labs;
                } else {
                    labsContainer.html('<p class="text-red-500 text-center">Failed to load labs.</p>');
                    return;
                }

                if (submissionsResponse[0].status === 'success' && Array.isArray(submissionsResponse[0].solved_labs)) {
                    completedLabs = submissionsResponse[0].solved_labs.map(id => id.toString());
                }

                if (allLabsData.length > 0) {
                    allLabsData.forEach(function(lab, index) {
                        const isCompleted = completedLabs.includes(lab.id.toString());
                        const nextLabId = (index < allLabsData.length - 1) ? allLabsData[index + 1].id : null;
                        const labNumber = index + 1;
                        const totalLabs = allLabsData.length;

                        const labHtml = `
                            <div id="lab-${lab.id}">
                                <header class="mb-6">
                                    <h2 class="text-3xl font-bold text-white font-mono">
                                        LAB ${labNumber} / ${totalLabs}: ${lab.title}
                                    </h2>
                                </header>
                                <div class="bg-gray-800 rounded-lg shadow-lg border border-red-500/20 p-6">
                                    <div class="space-y-3 text-gray-400 mb-6">
                                        <p><strong class="text-gray-200">INFO:</strong> ${lab.description}</p>
                                        <p><strong class="text-gray-200">POINTS:</strong> <span class="text-orange-400 font-mono">${lab.points}</span></p>
                                        <p><strong class="text-gray-200">TARGET:</strong> <a href="${lab.link}" class="text-orange-400 hover:underline" target="_blank">${lab.link}</a></p>
                                    </div>
                                    <div id="message-lab-${lab.id}" class="text-center mb-4 hidden"></div>
                                    <form id="form-lab-${lab.id}" class="flex items-center space-x-4 mt-6 border-t border-gray-700 pt-6">
                                        <input type="text" class="flex-grow p-3 bg-gray-700 border border-gray-600 text-gray-200 rounded-md focus:ring-2 focus:ring-red-500 focus:border-transparent transition placeholder-gray-500" placeholder="NodeZer0{...flag...}" ${isCompleted ? 'disabled' : ''}>
                                        <button type="submit" class="bg-red-600 text-white font-bold py-3 px-6 rounded-md hover:bg-red-700 transition-colors duration-300 ${isCompleted ? 'cursor-not-allowed bg-gray-600 hover:bg-gray-600' : ''}" ${isCompleted ? 'disabled' : ''}>
                                            ${isCompleted ? 'COMPLETED' : 'SUBMIT'}
                                        </button>
                                    </form>
                                </div>
                                ${nextLabId ? `<div class="text-center mt-6"><a href="#lab-${nextLabId}" id="next-lab-${lab.id}" class="${isCompleted ? '' : 'hidden'} inline-block bg-gray-700 text-white font-bold py-2 px-8 rounded-md hover:bg-gray-600 transition-colors duration-300">Next Lab ↓</a></div>` : ''}
                            </div>
                        `;
                        labsContainer.append(labHtml);
                        
                        if (!isCompleted) {
                            handleFlagSubmit($(`#form-lab-${lab.id}`), lab.id, nextLabId);
                        }
                    });
                } else {
                    labsContainer.html('<p class="text-center text-gray-500">No labs found for this event.</p>');
                }

            }).fail(function() {
                labsContainer.html('<p class="text-red-500 text-center">An error occurred while loading lab data.</p>');
            });
        }

        $(function() {
            const urlParams = new URLSearchParams(window.location.search);
            const eventId = urlParams.get('event_id');
            const modalMessage = $('#modal-message');

            if (!eventId) {
                $('#join-modal').hide();
                $('#main-content').removeClass('hidden');
                $('#labs-container').html('<p class="text-red-500 text-center">No event ID found in URL.</p>');
                return;
            }

            $.ajax({
                url: `./api/check_join_status.php?event_id=${eventId}`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        if (response.is_joined) {
                            $('#main-content').removeClass('hidden');
                            loadLabContent(eventId);
                        } else {
                            $('#join-modal').removeClass('hidden');
                        }
                    } else {
                         $('#join-modal').removeClass('hidden').find('p').text(response.message);
                         $('#join-modal').find('button').prop('disabled', true);
                    }
                },
                error: function() {
                    $('#join-modal').removeClass('hidden').find('p').text('Could not verify event status.');
                    $('#join-modal').find('button').prop('disabled', true);
                }
            });

            $('#decline-join').on('click', function() {
                window.location.href = 'events.php';
            });

            $('#accept-join').on('click', function() {
                $.ajax({
                    url: './api/join_event.php',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({ event_id: eventId }),
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#join-modal').fadeOut(300, function() { $(this).remove(); });
                            $('#main-content').removeClass('hidden');
                            loadLabContent(eventId);
                        } else {
                            modalMessage.text(response.message).css('color', 'tomato');
                        }
                    },
                    error: function(jqXHR) {
                        const errorMsg = jqXHR.responseJSON ? jqXHR.responseJSON.message : 'An unknown error occurred.';
                        modalMessage.text(errorMsg).css('color', 'tomato');
                    }
                });
            });

            function updateClock() {
                const now = new Date();
                const timeString = now.toLocaleTimeString('en-US', { hour12: false });
                $('#current-time').text(timeString);
            }

            function startCountdown(durationInMinutes, elementId) {
                let timer = durationInMinutes * 60;
                const timerElement = $(`#${elementId}`);
                const interval = setInterval(() => {
                    const minutes = parseInt(timer / 60, 10);
                    const seconds = parseInt(timer % 60, 10);
                    const displayMinutes = minutes < 10 ? "0" + minutes : minutes;
                    const displaySeconds = seconds < 10 ? "0" + seconds : seconds;
                    timerElement.text(`${displayMinutes}:${displaySeconds}`);
                    if (--timer < 0) {
                        clearInterval(interval);
                        timerElement.text("00:00").removeClass('text-orange-400').addClass('text-red-500');
                    }
                }, 1000);
            }

            setInterval(updateClock, 1000);
            updateClock();
            startCountdown(30, 'countdown-timer');
        });
    </script>

</body>
</html>
