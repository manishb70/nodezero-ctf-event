<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - NodeZer0</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-mono { font-family: 'Roboto Mono', monospace; }
        .tab-btn.active {
            border-color: #EF4444; /* border-red-500 */
            color: #EF4444; /* text-red-500 */
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-300">
    <?php include 'navbar.php'; ?>

    <main class="container mx-auto max-w-4xl p-4 sm:p-6 md:p-8">
        <!-- Profile Header -->
        <div class="flex items-center gap-6 mb-8">
            <div class="w-24 h-24 bg-gray-700 rounded-full flex items-center justify-center border-2 border-red-500/50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                </svg>
            </div>
            <div>
                <h1 id="header-username" class="text-3xl font-bold text-white font-mono">Loading...</h1>
                <p id="header-email" class="text-gray-400"></p>
            </div>
        </div>

        <!-- Stats Bar -->
        <div class="grid grid-cols-3 gap-4 text-center bg-gray-800 p-4 rounded-lg mb-12">
            <div>
                <p class="text-sm font-mono text-gray-400">Total Score</p>
                <p id="stat-score" class="text-2xl font-bold text-orange-400 font-mono">0</p>
            </div>
            <div>
                <p class="text-sm font-mono text-gray-400">Labs Solved</p>
                <p id="stat-labs" class="text-2xl font-bold text-orange-400 font-mono">0</p>
            </div>
            <div>
                <p class="text-sm font-mono text-gray-400">Global Rank</p>
                <p id="stat-rank" class="text-2xl font-bold text-orange-400 font-mono">#--</p>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="mb-8 border-b border-gray-700">
            <nav class="flex space-x-8" aria-label="Tabs">
                <button class="tab-btn active whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="details">
                    Account Details
                </button>
                <button class="tab-btn text-gray-400 hover:text-red-500 whitespace-nowrap py-4 px-1 border-b-2 border-transparent font-medium text-sm" data-tab="security">
                    Security
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div>
            <!-- Account Details Tab -->
            <div id="details-tab">
                <div class="bg-gray-800 rounded-lg p-8">
                    <div id="message-area" class="mb-6 text-center text-sm"></div>
                    <div id="view-mode">
                        <div class="space-y-4">
                            <div><label class="text-sm font-mono text-gray-400">Username</label><p id="view-username" class="text-lg text-white"></p></div>
                            <div><label class="text-sm font-mono text-gray-400">Email</label><p id="view-email" class="text-lg text-white"></p></div>
                            <div><label class="text-sm font-mono text-gray-400">College Name</label><p id="view-college" class="text-lg text-white"></p></div>
                            <div><label class="text-sm font-mono text-gray-400">Course</label><p id="view-course" class="text-lg text-white"></p></div>
                        </div>
                        <button id="edit-btn" class="w-full mt-6 bg-gray-600 text-white py-3 rounded-lg hover:bg-gray-700 transition font-semibold">Edit Profile</button>
                    </div>
                    <form id="profile-form" class="space-y-4 hidden">
                        <div><label for="username" class="text-sm font-mono text-gray-400">Username</label><input type="text" id="username" class="w-full mt-1 px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required></div>
                        <div><label for="email" class="text-sm font-mono text-gray-400">Email</label><input type="email" id="email" class="w-full mt-1 px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required></div>
                        <div><label for="college" class="text-sm font-mono text-gray-400">College Name</label><input type="text" id="college" class="w-full mt-1 px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required></div>
                        <div><label for="course" class="text-sm font-mono text-gray-400">Course</label><input type="text" id="course" class="w-full mt-1 px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required></div>
                        <div class="flex gap-4 !mt-6"><button type="button" id="cancel-btn" class="w-full bg-gray-600 text-white py-3 rounded-lg hover:bg-gray-700 transition font-semibold">Cancel</button><button type="submit" class="w-full bg-red-600 text-white py-3 rounded-lg hover:bg-red-700 transition font-semibold">Save Changes</button></div>
                    </form>
                </div>
            </div>
            <!-- Security Tab -->
            <div id="security-tab" class="hidden">
                 <div class="bg-gray-800 rounded-lg p-8">
                    <h2 class="text-2xl font-bold text-white font-mono mb-4">Change Password</h2>
                    <div id="password-message-area" class="mb-6 text-center text-sm"></div>
                    <form id="password-form" class="space-y-4">
                        <div><label for="current-password" class="text-sm font-mono text-gray-400">Current Password</label><input type="password" id="current-password" class="w-full mt-1 px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required></div>
                        <div><label for="new-password" class="text-sm font-mono text-gray-400">New Password</label><input type="password" id="new-password" class="w-full mt-1 px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required></div>
                        <div><label for="confirm-password" class="text-sm font-mono text-gray-400">Confirm New Password</label><input type="password" id="confirm-password" class="w-full mt-1 px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required></div>
                        <button type="submit" class="w-full bg-red-600 text-white py-3 rounded-lg hover:bg-red-700 transition font-semibold !mt-6">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(function() {
            const messageArea = $('#message-area');
            const passwordMessageArea = $('#password-message-area');
            let currentUserData = {};

            // Fetch profile and stats data simultaneously
            const profileRequest = $.ajax({ url: './api/get_profile.php', dataType: 'json' });
            const statsRequest = $.ajax({ url: './api/get_user_stats.php', dataType: 'json' });

            $.when(profileRequest, statsRequest).done(function(profileRes, statsRes) {
                const profileResponse = profileRes[0];
                const statsResponse = statsRes[0];

                if (profileResponse.status === 'success') {
                    currentUserData = profileResponse.user;
                    $('#header-username, #view-username').text(currentUserData.username);
                    $('#header-email, #view-email').text(currentUserData.email);
                    $('#view-college').text(currentUserData.college_name);
                    $('#view-course').text(currentUserData.course);
                } else {
                    $('main').html(`<p class="text-center text-red-500">${profileResponse.message}</p>`);
                }

                if (statsResponse.status === 'success') {
                    $('#stat-score').text(statsResponse.stats.total_score);
                    $('#stat-labs').text(statsResponse.stats.labs_solved);
                }

            }).fail(function() {
                 $('main').html('<p class="text-center text-red-500">Failed to load profile data.</p>');
            });

            // Tab switching logic
            $('.tab-btn').on('click', function() {
                const tabId = $(this).data('tab');
                $('.tab-btn').removeClass('active text-red-500').addClass('text-gray-400');
                $(this).addClass('active text-red-500').removeClass('text-gray-400');
                
                $('#details-tab, #security-tab').addClass('hidden');
                $(`#${tabId}-tab`).removeClass('hidden');
            });

            // Edit/Cancel button logic
            $('#edit-btn').on('click', function() {
                $('#username').val(currentUserData.username);
                $('#email').val(currentUserData.email);
                $('#college').val(currentUserData.college_name);
                $('#course').val(currentUserData.course);
                $('#view-mode').hide();
                $('#profile-form').removeClass('hidden');
            });

            $('#cancel-btn').on('click', function() {
                $('#profile-form').addClass('hidden');
                $('#view-mode').show();
            });

            // Form submission logic (profile and password)
            $('#profile-form').on('submit', function(e) {
                e.preventDefault();
                const formData = {
                    username: $('#username').val(), email: $('#email').val(),
                    college_name: $('#college').val(), course: $('#course').val()
                };
                $.ajax({
                    url: './api/update_profile.php', type: 'POST', contentType: 'application/json',
                    data: JSON.stringify(formData), dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            messageArea.text(response.message).css('color', 'lightgreen');
                            setTimeout(() => location.reload(), 1500);
                        } else { messageArea.text(response.message).css('color', 'tomato'); }
                    },
                    error: function() { messageArea.text('An error occurred.').css('color', 'tomato'); }
                });
            });

            $('#password-form').on('submit', function(e) {
                e.preventDefault();
                const passwordData = {
                    current_password: $('#current-password').val(),
                    new_password: $('#new-password').val(), confirm_password: $('#confirm-password').val()
                };
                $.ajax({
                    url: './api/change_password.php', type: 'POST', contentType: 'application/json',
                    data: JSON.stringify(passwordData), dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            passwordMessageArea.text(response.message).css('color', 'lightgreen');
                            $('#password-form')[0].reset();
                        } else { passwordMessageArea.text(response.message).css('color', 'tomato'); }
                    },
                    error: function() { passwordMessageArea.text('An error occurred.').css('color', 'tomato'); }
                });
            });
        });
    </script>
</body>
</html>
