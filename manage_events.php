<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events - NodeZer0</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-mono { font-family: 'Roboto Mono', monospace; }
        .input-style { background-color: #374151; border: 1px solid #4B5563; color: #D1D5DB; border-radius: 0.5rem; padding: 0.75rem 1rem; }
        .btn-submit { background-color: #DC2626; color: white; font-weight: bold; padding: 0.75rem; border-radius: 0.5rem; }
    </style>
</head>
<body class="bg-gray-900 text-gray-300">
    <?php include 'navbar.php'; ?>

    <main class="container mx-auto max-w-6xl p-4 sm:p-6 md:p-8">
        <header class="mb-8">
            <h1 class="text-4xl md:text-5xl font-bold text-red-500 font-mono">Manage Events</h1>
            <p class="text-gray-400 mt-2">Create, update, and delete CTF events from this panel.</p>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Existing Events List -->
            <div class="lg:col-span-2">
                <h2 class="text-2xl font-bold text-white font-mono mb-4">Existing Events</h2>
                <div id="events-list" class="bg-gray-800 rounded-lg p-4 overflow-x-auto">
                    <!-- Events table will be loaded here -->
                </div>
            </div>

            <!-- Right Column: Create New Event Form -->
            <div>
                <div class="bg-gray-800 rounded-lg p-6 sticky top-24">
                    <h2 class="text-2xl font-bold text-white font-mono mb-4">Create New Event</h2>
                    <div id="create-message-area" class="mb-4 text-sm text-center"></div>
                    <form id="event-form" class="space-y-4">
                        <input type="text" id="event-name" placeholder="Event Name" class="w-full input-style" required>
                        <textarea id="event-desc" placeholder="Event Description" class="w-full input-style" required></textarea>
                        <div>
                            <label for="event-start" class="text-xs font-mono text-gray-400">Start Time</label>
                            <input type="datetime-local" id="event-start" class="w-full input-style" required>
                        </div>
                        <div>
                            <label for="event-end" class="text-xs font-mono text-gray-400">End Time</label>
                            <input type="datetime-local" id="event-end" class="w-full input-style" required>
                        </div>
                        <button type="submit" class="w-full btn-submit">Create Event</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Edit Event Modal -->
    <div id="edit-event-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75 hidden">
        <div class="bg-gray-800 border border-red-500/30 shadow-lg rounded-2xl p-8 w-full max-w-lg">
             <h2 class="text-2xl font-bold text-white font-mono mb-4">Edit Event</h2>
             <div id="edit-message-area" class="mb-4 text-sm text-center"></div>
             <form id="edit-event-form" class="space-y-4">
                <input type="hidden" id="edit-event-id">
                <input type="text" id="edit-event-name" placeholder="Event Name" class="w-full input-style" required>
                <textarea id="edit-event-desc" placeholder="Event Description" class="w-full input-style" required></textarea>
                <div>
                    <label for="edit-event-start" class="text-xs font-mono text-gray-400">Start Time</label>
                    <input type="datetime-local" id="edit-event-start" class="w-full input-style" required>
                </div>
                <div>
                    <label for="edit-event-end" class="text-xs font-mono text-gray-400">End Time</label>
                    <input type="datetime-local" id="edit-event-end" class="w-full input-style" required>
                </div>
                <div class="flex gap-4">
                    <button type="button" id="cancel-edit" class="w-full bg-gray-600 text-white py-2 rounded-lg">Cancel</button>
                    <button type="submit" class="w-full btn-submit py-2">Save Changes</button>
                </div>
             </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(function() {
            const eventListContainer = $('#events-list');
            const createMessageArea = $('#create-message-area');
            const editMessageArea = $('#edit-message-area');

            function loadEvents() {
                eventListContainer.html('<p class="text-gray-500">Loading events...</p>');
                $.getJSON('./api/manage_events.php', function(data) {
                    eventListContainer.empty();
                    if (data.status === 'success' && data.events.length > 0) {
                        const table = $('<table class="w-full text-left min-w-[600px]"></table>');
                        table.append('<thead><tr class="font-mono text-sm text-red-400"><th>Name</th><th>Start Time</th><th>Actions</th></tr></thead>');
                        const tbody = $('<tbody></tbody>');
                        data.events.forEach(event => {
                            tbody.append(`
                                <tr class="border-t border-gray-700">
                                    <td class="py-3 pr-4">${event.name}</td>
                                    <td class="py-3 pr-4">${new Date(event.start_time).toLocaleString()}</td>
                                    <td class="py-3 space-x-2">
                                        <button class="edit-event-btn text-sm text-orange-400 hover:underline" data-event='${JSON.stringify(event)}'>Edit</button>
                                        <button class="delete-event-btn text-sm text-gray-400 hover:underline" data-id="${event.id}">Delete</button>
                                    </td>
                                </tr>
                            `);
                        });
                        table.append(tbody);
                        eventListContainer.append(table);
                    } else {
                        eventListContainer.html('<p class="text-gray-500">No events found.</p>');
                    }
                });
            }

            $('#event-form').on('submit', function(e) {
                e.preventDefault();
                const eventData = {
                    name: $('#event-name').val(), description: $('#event-desc').val(),
                    start_time: $('#event-start').val(), end_time: $('#event-end').val()
                };
                $.ajax({ url: './api/manage_events.php', type: 'POST', contentType: 'application/json', data: JSON.stringify(eventData), success: (res) => { 
                    createMessageArea.text(res.message).css('color', 'lightgreen');
                    loadEvents(); 
                    this.reset();
                    setTimeout(() => createMessageArea.empty(), 3000);
                }});
            });

            eventListContainer.on('click', '.delete-event-btn', function() {
                if (confirm('Are you sure you want to delete this event? This action cannot be undone.')) {
                    const id = $(this).data('id');
                    $.ajax({ url: './api/manage_events.php', type: 'DELETE', contentType: 'application/json', data: JSON.stringify({id: id}), success: loadEvents });
                }
            });

            eventListContainer.on('click', '.edit-event-btn', function() {
                const event = $(this).data('event');
                $('#edit-event-id').val(event.id);
                $('#edit-event-name').val(event.name);
                $('#edit-event-desc').val(event.description);
                $('#edit-event-start').val(event.start_time.slice(0, 16));
                $('#edit-event-end').val(event.end_time.slice(0, 16));
                $('#edit-event-modal').removeClass('hidden');
            });

            $('#cancel-edit').on('click', () => $('#edit-event-modal').addClass('hidden'));

            $('#edit-event-form').on('submit', function(e) {
                e.preventDefault();
                const eventData = {
                    id: $('#edit-event-id').val(), name: $('#edit-event-name').val(),
                    description: $('#edit-event-desc').val(), start_time: $('#edit-event-start').val(),
                    end_time: $('#edit-event-end').val()
                };
                $.ajax({ url: './api/manage_events.php', type: 'PUT', contentType: 'application/json', data: JSON.stringify(eventData), success: (res) => { 
                    editMessageArea.text(res.message).css('color', 'lightgreen');
                    setTimeout(() => {
                        $('#edit-event-modal').addClass('hidden');
                        editMessageArea.empty();
                        loadEvents(); 
                    }, 1500);
                }});
            });

            // Initial Load
            loadEvents();
        });
    </script>
</body>
</html>
