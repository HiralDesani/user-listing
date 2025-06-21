<!DOCTYPE html>
<html>
<head>
    <title>User Listing</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        #user-info { border-left: 1px solid #ccc; padding-left: 20px; }
        .user-row { cursor: pointer; }
    </style>
</head>
<body>
    <input type="text" id="search" placeholder="Search user...">
    <div style="display: flex;">
        <div style="width: 70%;">
            <table border="1" width="100%">
                <thead><tr><th>Name</th><th>Email</th></tr></thead>
                <tbody id="user-list"></tbody>
            </table>
            <div id="pagination"></div>
        </div>
        <div id="user-info" style="width: 30%;"></div>
    </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let currentPage = 1;

function fetchUsers(page = 1, search = '') {
    $.get(`/api/users?page=${page}&search=${search}`, function(data) {
        let rows = '';
        data.data.forEach(user => {
            rows += `<tr class="user-row" data-id="${user.id}"><td>${user.name}</td><td>${user.email}</td></tr>`;
        });
        $('#user-list').html(rows);

        let pagination = '';
        for (let i = 1; i <= data.last_page; i++) {
            pagination += `<button onclick="fetchUsers(${i}, $('#search').val())">${i}</button> `;
        }
        $('#pagination').html(pagination);
    });
}

$(document).ready(function() {
    fetchUsers();

    $('#search').on('keyup', function() {
        fetchUsers(1, $(this).val());
    });

    $(document).on('click', '.user-row', function() {
        const userId = $(this).data('id');
        $.get(`/api/users/${userId}`, function(data) {
            $('#user-info').html(`<h3>${data.name}</h3><p>${data.email}</p><p>${data.phone}</p>`);
        });
    });

    // Auto update for latest record without refresh
    setInterval(() => {
        fetchUsers(currentPage, $('#search').val());
    }, 10000); // 10 seconds
});
</script>
</body>
</html>
