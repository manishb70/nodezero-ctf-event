<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - NodeZer0</title>
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

    <main class="container mx-auto max-w-6xl p-4 sm:p-6 md:p-8">
        <header class="mb-8">
            <h1 class="text-4xl md:text-5xl font-bold text-red-500 font-mono">Admin Dashboard</h1>
        </header>

        <!-- Tab Navigation -->
        <div class="mb-8 border-b border-gray-700">
            <nav class="flex space-x-8" aria-label="Tabs">
                <button class="tab-btn active" data-tab="overview">Overview</button>
                <button class="tab-btn" data-tab="events">Manage Events</button>
                <button class="tab-btn" data-tab="labs">Manage Labs</button>
                <button class="tab-btn" data-tab="users">Manage Users</button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div>
            <!-- Overview Tab -->
            <div id="overview-tab">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-center">
                    <div class="bg-gray-800 p-6 rounded-lg"><p class="text-sm font-mono text-gray-400">Total Users</p><p id="stat-users" class="text-4xl font-bold text-orange-400 font-mono">0</p></div>
                    <div class="bg-gray-800 p-6 rounded-lg"><p class="text-sm font-mono text-gray-400">Total Events</p><p id="stat-events" class="text-4xl font-bold text-orange-400 font-mono">0</p></div>
                    <div class="bg-gray-800 p-6 rounded-lg"><p class="text-sm font-mono text-gray-400">Total Labs</p><p id="stat-labs" class="text-4xl font-bold text-orange-400 font-mono">0</p></div>
                    <div class="bg-gray-800 p-6 rounded-lg"><p class="text-sm font-mono text-gray-400">Submissions (24h)</p><p id="stat-submissions" class="text-4xl font-bold text-orange-400 font-mono">0</p></div>
                </div>
            </div>
            <!-- Events Tab -->
            <div id="events-tab" class="hidden">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2">
                        <h2 class="text-2xl font-bold text-white font-mono mb-4">Existing Events</h2>
                        <div id="events-list" class="bg-gray-800 rounded-lg p-4 overflow-x-auto"></div>
                    </div>
                    <div>
                        <div class="bg-gray-800 rounded-lg p-6 sticky top-24">
                            <h2 class="text-2xl font-bold text-white font-mono mb-4">Create New Event</h2>
                            <form id="event-form" class="space-y-4">
                                <input type="text" id="event-name" placeholder="Event Name" class="w-full input-style" required>
                                <textarea id="event-desc" placeholder="Event Description" class="w-full input-style" required></textarea>
                                <div class="flex gap-4">
                                    <input type="datetime-local" id="event-start" class="w-full input-style" required>
                                    <input type="datetime-local" id="event-end" class="w-full input-style" required>
                                </div>
                                <button type="submit" class="w-full btn-submit">Create Event</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Labs Tab -->
            <div id="labs-tab" class="hidden">
                 <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2">
                        <h2 class="text-2xl font-bold text-white font-mono mb-4">Existing Labs</h2>
                        <div id="labs-list" class="bg-gray-800 rounded-lg p-4 overflow-x-auto"></div>
                    </div>
                    <div>
                        <div class="bg-gray-800 rounded-lg p-6 sticky top-24">
                            <h2 class="text-2xl font-bold text-white font-mono mb-4">Create New Lab</h2>
                            <form id="lab-form" class="space-y-4">
                                <select id="lab-event-select" class="w-full input-style"><option>Select Event</option></select>
                                <input type="text" id="lab-title" placeholder="Lab Title" class="w-full input-style" required>
                                <textarea id="lab-desc" placeholder="Lab Description" class="w-full input-style"></textarea>
                                <input type="text" id="lab-link" placeholder="Target Link" class="w-full input-style">
                                <input type="text" id="lab-flag" placeholder="Flag (e.g., NodeZer0{...})" class="w-full input-style" required>
                                <input type="number" id="lab-points" placeholder="Points" class="w-full input-style" required>
                                <button type="submit" class="w-full btn-submit">Create Lab</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Users Tab -->
            <div id="users-tab" class="hidden">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-white font-mono">Registered Users</h2>
                    <a href="./api/export_users.php" class="bg-gray-600 text-white font-bold py-2 px-5 rounded-md hover:bg-gray-700">Export as CSV</a>
                </div>
                <div id="users-list" class="bg-gray-800 rounded-lg p-4 overflow-x-auto"></div>
            </div>
        </div>
    </main>

    <!-- Modals -->
    <div id="edit-event-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75 hidden">
        <div class="bg-gray-800 border border-red-500/30 shadow-lg rounded-2xl p-8 w-full max-w-lg">
             <h2 class="text-2xl font-bold text-white font-mono mb-4">Edit Event</h2>
             <form id="edit-event-form" class="space-y-4">
                <input type="hidden" id="edit-event-id">
                <input type="text" id="edit-event-name" class="w-full input-style" required>
                <textarea id="edit-event-desc" class="w-full input-style" required></textarea>
                <div class="flex gap-4">
                    <input type="datetime-local" id="edit-event-start" class="w-full input-style" required>
                    <input type="datetime-local" id="edit-event-end" class="w-full input-style" required>
                </div>
                <div class="flex gap-4"><button type="button" class="cancel-btn w-full bg-gray-600 text-white py-2 rounded-lg">Cancel</button><button type="submit" class="w-full btn-submit py-2">Save Changes</button></div>
             </form>
        </div>
    </div>
    <div id="edit-lab-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75 hidden">
        <div class="bg-gray-800 border border-red-500/30 shadow-lg rounded-2xl p-8 w-full max-w-lg">
             <h2 class="text-2xl font-bold text-white font-mono mb-4">Edit Lab</h2>
             <form id="edit-lab-form" class="space-y-4">
                <input type="hidden" id="edit-lab-id">
                <select id="edit-lab-event-select" class="w-full input-style" required></select>
                <input type="text" id="edit-lab-title" class="w-full input-style" required>
                <textarea id="edit-lab-desc" class="w-full input-style"></textarea>
                <input type="text" id="edit-lab-link" class="w-full input-style">
                <input type="text" id="edit-lab-flag" class="w-full input-style" required>
                <input type="number" id="edit-lab-points" class="w-full input-style" required>
                <div class="flex gap-4"><button type="button" class="cancel-btn w-full bg-gray-600 text-white py-2 rounded-lg">Cancel</button><button type="submit" class="w-full btn-submit py-2">Save Changes</button></div>
             </form>
        </div>
    </div>
    <div id="edit-user-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75 hidden">
        <div class="bg-gray-800 border border-red-500/30 shadow-lg rounded-2xl p-8 w-full max-w-lg">
             <h2 class="text-2xl font-bold text-white font-mono mb-4">Edit User</h2>
             <form id="edit-user-form" class="space-y-4">
                <input type="hidden" id="edit-user-id">
                <input type="text" id="edit-user-username" class="w-full input-style" required>
                <input type="email" id="edit-user-email" class="w-full input-style" required>
                <input type="text" id="edit-user-college" class="w-full input-style">
                <input type="text" id="edit-user-course" class="w-full input-style">
                <div class="flex items-center justify-between">
                    <label class="flex items-center space-x-3"><input type="checkbox" id="edit-user-is-admin" class="h-5 w-5"><span>Admin</span></label>
                    <label class="flex items-center space-x-3"><input type="checkbox" id="edit-user-is-active" class="h-5 w-5"><span>Active</span></label>
                </div>
                <div class="flex gap-4"><button type="button" class="cancel-btn w-full bg-gray-600 text-white py-2 rounded-lg">Cancel</button><button type="submit" class="w-full btn-submit py-2">Save Changes</button></div>
             </form>
        </div>
    </div>

    <style>.input-style { background-color: #374151; border: 1px solid #4B5563; color: #D1D5DB; border-radius: 0.5rem; padding: 0.75rem 1rem; } .btn-submit { background-color: #DC2626; color: white; font-weight: bold; padding: 0.75rem; border-radius: 0.5rem; } .tab-btn { color: #9CA3AF; padding: 1rem 0.25rem; border-bottom-width: 2px; border-color: transparent; font-weight: 500; font-size: 0.875rem; }</style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(function() {
            // --- General Setup ---
            const eventListContainer = $('#events-list');
            const labListContainer = $('#labs-list');
            const userListContainer = $('#users-list');

            // --- Tab Switching ---
            $('.tab-btn').on('click', function() {
                const tabId = $(this).data('tab');
                $('.tab-btn').removeClass('active');
                $(this).addClass('active');
                $('#overview-tab, #events-tab, #labs-tab, #users-tab').addClass('hidden');
                $(`#${tabId}-tab`).removeClass('hidden');
            });

            // --- API Functions ---
            function apiCall(url, method, data, callback) {
                $.ajax({
                    url: url, type: method, contentType: 'application/json',
                    data: JSON.stringify(data), success: callback
                });
            }

            // --- Load Functions ---
            function loadStats() {
                $.getJSON('./api/admin_stats.php', data => {
                    if (data.status === 'success') {
                        $('#stat-users').text(data.stats.total_users);
                        $('#stat-events').text(data.stats.total_events);
                        $('#stat-labs').text(data.stats.total_labs);
                        $('#stat-submissions').text(data.stats.recent_submissions);
                    }
                });
            }

            function loadEvents() {
                $.getJSON('./api/manage_events.php', data => {
                    eventListContainer.empty();
                    if (data.status === 'success' && data.events.length > 0) {
                        const table = $('<table class="w-full text-left"><thead><tr class="font-mono text-sm text-red-400"><th>Name</th><th>Start Time</th><th>Actions</th></tr></thead></table>');
                        const tbody = $('<tbody></tbody>');
                        data.events.forEach(event => {
                            tbody.append(`
                                <tr class="border-t border-gray-700">
                                    <td class="py-3">${event.name}</td>
                                    <td class="py-3">${new Date(event.start_time).toLocaleString()}</td>
                                    <td class="py-3 space-x-2"><button class="edit-event-btn text-sm text-orange-400" data-event='${JSON.stringify(event)}'>Edit</button><button class="delete-event-btn text-sm text-gray-400" data-id="${event.id}">Delete</button></td>
                                </tr>`);
                        });
                        table.append(tbody);
                        eventListContainer.append(table);
                    } else { eventListContainer.html('<p class="text-gray-500">No events found.</p>'); }
                });
            }

            function loadLabs() {
                $.getJSON('./api/manage_labs.php', data => {
                    labListContainer.empty();
                    if (data.status === 'success' && data.labs.length > 0) {
                        const table = $('<table class="w-full text-left"><thead><tr class="font-mono text-sm text-red-400"><th>Title</th><th>Event</th><th>Points</th><th>Actions</th></tr></thead></table>');
                        const tbody = $('<tbody></tbody>');
                        data.labs.forEach(lab => {
                            tbody.append(`
                                <tr class="border-t border-gray-700">
                                    <td class="py-3">${lab.title}</td><td class="py-3">${lab.event_name}</td><td class="py-3">${lab.points}</td>
                                    <td class="py-3 space-x-2"><button class="edit-lab-btn text-sm text-orange-400" data-lab='${JSON.stringify(lab)}'>Edit</button><button class="delete-lab-btn text-sm text-gray-400" data-id="${lab.id}">Delete</button></td>
                                </tr>`);
                        });
                        table.append(tbody);
                        labListContainer.append(table);
                    } else { labListContainer.html('<p class="text-gray-500">No labs found.</p>'); }
                });
            }

            function loadUsers() {
                $.getJSON('./api/manage_users.php', data => {
                    userListContainer.empty();
                    if (data.status === 'success' && data.users.length > 0) {
                        const table = $('<table class="w-full text-left min-w-[600px]"><thead><tr class="font-mono text-sm text-red-400"><th>Username</th><th>Email</th><th>Status</th><th>Actions</th></tr></thead></table>');
                        const tbody = $('<tbody></tbody>');
                        data.users.forEach(user => {
                            const statusBadge = user.is_active == 1 ? '<span class="text-green-400">Active</span>' : '<span class="text-gray-500">Inactive</span>';
                            tbody.append(`
                                <tr class="border-t border-gray-700">
                                    <td class="py-3 pr-4">${user.username}</td><td class="py-3 pr-4">${user.email}</td><td class="py-3 pr-4">${statusBadge}</td>
                                    <td class="py-3 space-x-2"><button class="edit-user-btn text-sm text-orange-400" data-user='${JSON.stringify(user)}'>Edit</button><button class="delete-user-btn text-sm text-gray-400" data-id="${user.id}">Delete</button></td>
                                </tr>`);
                        });
                        table.append(tbody);
                        userListContainer.append(table);
                    } else { userListContainer.html('<p class="text-gray-500">No users found.</p>'); }
                });
            }
            
            function loadEventsForDropdowns() {
                 $.getJSON('./api/get_all_events.php', data => {
                    const selects = $('#lab-event-select, #edit-lab-event-select');
                    selects.empty().append('<option value="">-- Select Event --</option>');
                    if (data.status === 'success') {
                        data.events.forEach(event => selects.append(`<option value="${event.id}">${event.name}</option>`));
                    }
                });
            }

            // --- Event Handlers ---
            $('#event-form').on('submit', function(e) { e.preventDefault(); apiCall('./api/manage_events.php', 'POST', { name: $('#event-name').val(), description: $('#event-desc').val(), start_time: $('#event-start').val(), end_time: $('#event-end').val() }, () => { loadEvents(); this.reset(); }); });
            $('#lab-form').on('submit', function(e) { e.preventDefault(); apiCall('./api/manage_labs.php', 'POST', { event_id: $('#lab-event-select').val(), title: $('#lab-title').val(), description: $('#lab-desc').val(), link: $('#lab-link').val(), flag: $('#lab-flag').val(), points: $('#lab-points').val() }, () => { loadLabs(); this.reset(); }); });
            
            eventListContainer.on('click', '.delete-event-btn', function() { if (confirm('Delete this event?')) apiCall('./api/manage_events.php', 'DELETE', {id: $(this).data('id')}, loadEvents); });
            labListContainer.on('click', '.delete-lab-btn', function() { if (confirm('Delete this lab?')) apiCall('./api/manage_labs.php', 'DELETE', {id: $(this).data('id')}, loadLabs); });
            userListContainer.on('click', '.delete-user-btn', function() { if (confirm('Delete this user?')) apiCall('./api/manage_users.php', 'DELETE', {id: $(this).data('id')}, loadUsers); });

            eventListContainer.on('click', '.edit-event-btn', function() {
                const event = $(this).data('event');
                $('#edit-event-id').val(event.id); $('#edit-event-name').val(event.name); $('#edit-event-desc').val(event.description);
                $('#edit-event-start').val(event.start_time.slice(0, 16)); $('#edit-event-end').val(event.end_time.slice(0, 16));
                $('#edit-event-modal').removeClass('hidden');
            });

            labListContainer.on('click', '.edit-lab-btn', function() {
                const lab = $(this).data('lab');
                $('#edit-lab-id').val(lab.id); $('#edit-lab-event-select').val(lab.event_id); $('#edit-lab-title').val(lab.title);
                $('#edit-lab-desc').val(lab.description); $('#edit-lab-link').val(lab.link); $('#edit-lab-flag').val(lab.flag); $('#edit-lab-points').val(lab.points);
                $('#edit-lab-modal').removeClass('hidden');
            });
            
            userListContainer.on('click', '.edit-user-btn', function() {
                const user = $(this).data('user');
                $('#edit-user-id').val(user.id); $('#edit-user-username').val(user.username); $('#edit-user-email').val(user.email);
                $('#edit-user-college').val(user.college_name); $('#edit-user-course').val(user.course);
                $('#edit-user-is-admin').prop('checked', user.is_admin == 1);
                $('#edit-user-is-active').prop('checked', user.is_active == 1);
                $('#edit-user-modal').removeClass('hidden');
            });

            $('.cancel-btn').on('click', () => $('#edit-event-modal, #edit-lab-modal, #edit-user-modal').addClass('hidden'));

            $('#edit-event-form').on('submit', function(e) { e.preventDefault(); apiCall('./api/manage_events.php', 'PUT', { id: $('#edit-event-id').val(), name: $('#edit-event-name').val(), description: $('#edit-event-desc').val(), start_time: $('#edit-event-start').val(), end_time: $('#edit-event-end').val() }, () => { $('#edit-event-modal').addClass('hidden'); loadEvents(); }); });
            $('#edit-lab-form').on('submit', function(e) { e.preventDefault(); apiCall('./api/manage_labs.php', 'PUT', { id: $('#edit-lab-id').val(), event_id: $('#edit-lab-event-select').val(), title: $('#edit-lab-title').val(), description: $('#edit-lab-desc').val(), link: $('#edit-lab-link').val(), flag: $('#edit-lab-flag').val(), points: $('#edit-lab-points').val() }, () => { $('#edit-lab-modal').addClass('hidden'); loadLabs(); }); });
            $('#edit-user-form').on('submit', function(e) { e.preventDefault(); apiCall('./api/manage_users.php', 'PUT', { id: $('#edit-user-id').val(), username: $('#edit-user-username').val(), email: $('#edit-user-email').val(), college_name: $('#edit-user-college').val(), course: $('#edit-user-course').val(), is_admin: $('#edit-user-is-admin').is(':checked') ? 1 : 0, is_active: $('#edit-user-is-active').is(':checked') ? 1 : 0 }, () => { $('#edit-user-modal').addClass('hidden'); loadUsers(); }); });

            // --- Initial Load ---
            loadStats();
            loadEvents();
            loadLabs();
            loadUsers();
            loadEventsForDropdowns();
        });
    </script>
</body>
</html>
