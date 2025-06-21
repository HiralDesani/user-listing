<!DOCTYPE html>
<html>
<head>
    <title>User Listing</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .user-row:hover {
            background-color: #f1f1f1;
        }
        #user-info {
            border-left: 1px solid #ddd;
            padding-left: 20px;
        }
        .pagination button {
            margin: 2px;
        }
        .table-container {
            max-height: 600px;
            overflow-y: auto;
        }
    </style>
</head>
<body class="bg-light">
<div class="container my-4">
    <h2 class="mb-4 text-center">📋 User Listing</h2>

    <div class="mb-3">
        <input type="text" id="search" class="form-control" placeholder="🔍 Search users by name or email...">
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="table-container shadow-sm bg-white rounded p-3">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody id="user-list"></tbody>
                </table>
                <div id="pagination" class="pagination d-flex flex-wrap"></div>
            </div>
        </div>
        <div class="col-md-4">
            <div id="user-info" class="bg-white p-4 shadow-sm rounded">
                <h5 class="text-muted">Click a user to see details</h5>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let currentPage = 1;

function fetchUsers(page = 1, search = '') {
    currentPage = page;
    $.get(`/api/users?page=${page}&search=${search}`, function(data) {
        let rows = '';
        data.data.forEach((user,index) => {
            const serial = (data.current_page - 1) * data.per_page + index + 1;
            rows += `<tr class="user-row" data-id="${user.id}">
                        <td>${serial}</td>
                        <td>${user.name}</td>
                        <td>${user.email}</td>
                    </tr>`;
        });
        $('#user-list').html(rows);

        let pagination = '';
        for (let i = 1; i <= data.last_page; i++) {
            pagination += `<button class="btn btn-sm btn-outline-primary ${i === page ? 'active' : ''}" onclick="fetchUsers(${i}, $('#search').val())">${i}</button>`;
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
            $('#user-info').html(`
                <h4 class="mb-3">${data.name}</h4>
                <p><strong>Email:</strong> ${data.email}</p>
            `);
        });
    });

    setInterval(() => {
        fetchUsers(currentPage, $('#search').val());
    }, 10000);
});
</script>
</body>
</html>
