$(document).ready(function() {
    $('#reloadPageButton').on('click', function(e) {
        e.preventDefault();
        location.reload();
    });

    // Auto reload after 5 minutes of inactivity
    let inactivityTime = 5 * 60 * 1000; // 5 minutes
    let timeout;
    function resetTimer() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            location.reload();
        }, inactivityTime);
    }
    // Detect user activity (mouse move, key press, scroll, click)
    $(document).on('mousemove keydown scroll click', resetTimer);
    resetTimer();

    // DataTables
    $('.datatable').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": true,
        "responsive": true,
        "lengthMenu": [10, 25, 50, 100, 200],
        "pageLength": 50,
        "order": []
    });
});