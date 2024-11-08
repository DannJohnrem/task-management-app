import './bootstrap';
import Alpine from 'alpinejs';
import $ from 'jquery';
import 'datatables.net';
import 'datatables.net-dt/css/dataTables.dataTables.css';

window.Alpine = Alpine;
Alpine.start();

$(document).ready(function () {
    // Initialize DataTable
    const table = $('#tasksTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/task',
            data: function (d) {
                d.status = $('#statusFilter').val();  // Get selected status filter
                d.search = $('#tasksTable_filter input').val();  // Get search input value
                d.order_by = $('#orderBy').val();  // Get selected order by field
                d.direction = $('#orderDirection').val();  // Get selected order direction
            }
        },
        columns: [
            { data: 'title', name: 'title' },
            { data: 'content', name: 'content' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    $('#statusFilter, #orderBy, #orderDirection').on('change keyup', function () {
        table.ajax.reload();  // Reload the table data
    });

    // Handle Edit button click
    $('body').on('click', '.edit-btn', function () {
        const taskId = $(this).data('task');
        console.log('Edit task ID:', taskId);
        // Send an AJAX request to get the task details for editing or navigate to edit page
        $.ajax({
            url: `/task/${taskId}/edit`,
            method: 'GET',
            success: function (response) {
                // Handle the response and populate the edit form (or show a modal)
                console.log('Task data for editing:', response);
                // Example: Populate the form with the task data
                // $('#editTitle').val(response.title);
                // $('#editContent').val(response.content);
            },
            error: function () {
                alert('Failed to load task details.');
            }
        });
    });

    // Handle Delete button click
    $('body').on('click', '.delete-btn', function () {
        const taskId = $(this).data('task');
        if (confirm('Are you sure you want to delete this task?')) {
            // Send an AJAX request to delete the task
            $.ajax({
                url: `/task/${taskId}`,
                method: 'DELETE',
                success: function (response) {
                    alert('Task deleted successfully');
                    // Optionally, reload the table or remove the row from the table
                    table.ajax.reload(); // Reload the table data
                },
                error: function () {
                    alert('Failed to delete task.');
                }
            });
        }
    });
});

