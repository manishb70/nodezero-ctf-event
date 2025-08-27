<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Labs - NodeZer0</title>
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
            <h1 class="text-4xl md:text-5xl font-bold text-red-500 font-mono">Manage Labs</h1>
            <p class="text-gray-400 mt-2">Create, update, and delete challenges for your events.</p>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Existing Labs List -->
            <div class="lg:col-span-2">
                <h2 class="text-2xl font-bold text-white font-mono mb-4">Existing Labs</h2>
                <div id="labs-list" class="bg-gray-800 rounded-lg p-4 overflow-x-auto">
                    <!-- Labs table will be loaded here -->
                </div>
            </div>

            <!-- Right Column: Create New Lab Form -->
            <div>
                <div class="bg-gray-800 rounded-lg p-6 sticky top-24">
                    <h2 class="text-2xl font-bold text-white font-mono mb-4">Create New Lab</h2>
                    <div id="create-message-area" class="mb-4 text-sm text-center"></div>
                    <form id="lab-form" class="space-y-4">
                        <select id="lab-event-select" class="w-full input-style" required><option value="">-- Select Event --</option></select>
                        <input type="text" id="lab-title" placeholder="Lab Title" class="w-full input-style" required>
                        <textarea id="lab-desc" placeholder="Lab Description" class="w-full input-style"></textarea>
                        <input type="text" id="lab-link" placeholder="Target Link (e.g., http://...)" class="w-full input-style">
                        <input type="text" id="lab-flag" placeholder="Flag (e.g., NodeZer0{...})" class="w-full input-style" required>
                        <input type="number" id="lab-points" placeholder="Points" class="w-full input-style" required>
                        <button type="submit" class="w-full btn-submit">Create Lab</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Edit Lab Modal -->
    <div id="edit-lab-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75 hidden">
        <div class="bg-gray-800 border border-red-500/30 shadow-lg rounded-2xl p-8 w-full max-w-lg">
             <h2 class="text-2xl font-bold text-white font-mono mb-4">Edit Lab</h2>
             <div id="edit-message-area" class="mb-4 text-sm text-center"></div>
             <form id="edit-lab-form" class="space-y-4">
                <input type="hidden" id="edit-lab-id">
                <select id="edit-lab-event-select" class="w-full input-style" required><option value="">-- Select Event --</option></select>
                <input type="text" id="edit-lab-title" placeholder="Lab Title" class="w-full input-style" required>
                <textarea id="edit-lab-desc" placeholder="Lab Description" class="w-full input-style"></textarea>
                <input type="text" id="edit-lab-link" placeholder="Target Link" class="w-full input-style">
                <input type="text" id="edit-lab-flag" placeholder="Flag" class="w-full input-style" required>
                <input type="number" id="edit-lab-points" placeholder="Points" class="w-full input-style" required>
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
            const labListContainer = $('#labs-list');
            const createMessageArea = $('#create-message-area');
            const editMessageArea = $('#edit-message-area');

            function loadLabs() {
                labListContainer.html('<p class="text-gray-500">Loading labs...</p>');
                $.getJSON('./api/manage_labs.php', function(data) {
                    labListContainer.empty();
                    if (data.status === 'success' && data.labs.length > 0) {
                        const table = $('<table class="w-full text-left min-w-[600px]"></table>');
                        table.append('<thead><tr class="font-mono text-sm text-red-400"><th>Title</th><th>Points</th><th>Actions</th></tr></thead>');
                        const tbody = $('<tbody></tbody>');
                        data.labs.forEach(lab => {
                            tbody.append(`
                                <tr class="border-t border-gray-700">
                                    <td class="py-3 pr-4">${lab.title}</td>
                                    <td class="py-3 pr-4">${lab.points}</td>
                                    <td class="py-3 space-x-2">
                                        <button class="edit-lab-btn text-sm text-orange-400 hover:underline" data-lab='${JSON.stringify(lab)}'>Edit</button>
                                        <button class="delete-lab-btn text-sm text-gray-400 hover:underline" data-id="${lab.id}">Delete</button>
                                    </td>
                                </tr>
                            `);
                        });
                        table.append(tbody);
                        labListContainer.append(table);
                    } else {
                        labListContainer.html('<p class="text-gray-500">No labs found.</p>');
                    }
                });
            }

            function loadEventsForDropdowns() {
                $.getJSON('./api/get_all_events.php', function(data) {
                    const createSelect = $('#lab-event-select');
                    const editSelect = $('#edit-lab-event-select');
                    $('select[id$="-event-select"]').empty().append('<option value="">-- Select Event --</option>');
                    if (data.status === 'success') {
                        data.events.forEach(event => {
                            const option = `<option value="${event.id}">${event.name}</option>`;
                            createSelect.append(option);
                            editSelect.append(option);
                        });
                    }
                });
            }

            $('#lab-form').on('submit', function(e) {
                e.preventDefault();
                const labData = {
                    event_id: $('#lab-event-select').val(), title: $('#lab-title').val(),
                    description: $('#lab-desc').val(), link: $('#lab-link').val(),
                    flag: $('#lab-flag').val(), points: $('#lab-points').val()
                };
                $.ajax({ url: './api/manage_labs.php', type: 'POST', contentType: 'application/json', data: JSON.stringify(labData), success: (res) => { 
                    createMessageArea.text(res.message).css('color', 'lightgreen');
                    loadLabs(); 
                    this.reset();
                    setTimeout(() => createMessageArea.empty(), 3000);
                }});
            });

            labListContainer.on('click', '.delete-lab-btn', function() {
                if (confirm('Are you sure you want to delete this lab?')) {
                    const id = $(this).data('id');
                    $.ajax({ url: './api/manage_labs.php', type: 'DELETE', contentType: 'application/json', data: JSON.stringify({id: id}), success: loadLabs });
                }
            });

            labListContainer.on('click', '.edit-lab-btn', function() {
                const lab = $(this).data('lab');
                $('#edit-lab-id').val(lab.id);
                $('#edit-lab-event-select').val(lab.event_id);
                $('#edit-lab-title').val(lab.title);
                $('#edit-lab-desc').val(lab.description);
                $('#edit-lab-link').val(lab.link);
                $('#edit-lab-flag').val(lab.flag);
                $('#edit-lab-points').val(lab.points);
                $('#edit-lab-modal').removeClass('hidden');
            });

            $('#cancel-edit').on('click', () => $('#edit-lab-modal').addClass('hidden'));

            $('#edit-lab-form').on('submit', function(e) {
                e.preventDefault();
                const labData = {
                    id: $('#edit-lab-id').val(), event_id: $('#edit-lab-event-select').val(),
                    title: $('#edit-lab-title').val(), description: $('#edit-lab-desc').val(),
                    link: $('#edit-lab-link').val(), flag: $('#edit-lab-flag').val(),
                    points: $('#edit-lab-points').val()
                };
                $.ajax({ url: './api/manage_labs.php', type: 'PUT', contentType: 'application/json', data: JSON.stringify(labData), success: (res) => { 
                    editMessageArea.text(res.message).css('color', 'lightgreen');
                    setTimeout(() => {
                        $('#edit-lab-modal').addClass('hidden');
                        editMessageArea.empty();
                        loadLabs(); 
                    }, 1500);
                }});
            });

            // Initial Load
            loadLabs();
            loadEventsForDropdowns();
        });
    </script>
</body>
</html>
