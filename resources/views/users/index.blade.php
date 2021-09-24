@extends('index')

@section('content')
    <nav class='mt-3' aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Users</li>
        </ol>
    </nav>

    @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif

    <a href="{{ route('users.create') }}" class="btn btn-info mb-3" role="button">Create user</a>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th scope="col" role='button' data-for='email' onclick="sort(this)">Email <i class="fas fa-sort"></i></th>
            <th scope="col" role='button' data-for='name' onclick="sort(this)">Name <i class="fas fa-sort"></i></th>
            <th scope="col">Role</th>
            <th scope="col">Action</th>
        </tr>
        </thead>
        <tbody id="users"></tbody>
    </table>
    </div>

    <script>
        let order = {};

        document.addEventListener("DOMContentLoaded", function(event) {
            loadUsersTable(order);
        });

        function sort(column) {
            let by = column.getAttribute("data-for");

            if (order[by] && order[by] == 'asc') {
                order[by] = 'desc';
                column.children[0].className = "fas fa-sort-down";
            } else if (order[by] && order[by] == 'desc') {
                delete order[by];
                column.children[0].className = "fas fa-sort";
            } else {
                order[by] = 'asc';
                column.children[0].className += "-up";
            }

            loadUsersTable(order);
        }

        function request(type, url, data, callback) {
            let xmlhttp = new XMLHttpRequest();

            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == XMLHttpRequest.DONE) {
                    if (xmlhttp.status == 200) {
                        callback(JSON.parse(xmlhttp.responseText));
                    } else {
                        alert('There was an error ' + xmlhttp.status);
                    }
                }
            };

            xmlhttp.open(type, url, true);
            xmlhttp.setRequestHeader('Content-type', 'application/json');
            xmlhttp.send(JSON.stringify(data));
        }

        function loadUsersTable(orderBy) {
            let url = '{{ route('api.users.index') }}';

            if (Object.keys(orderBy).length > 0) {
                let esc = encodeURIComponent;
                url += '?' + Object.keys(orderBy)
                    .map(k => 'orderBy[' + esc(k) + ']=' + esc(orderBy[k]))
                    .join('&');
            }

            request('GET', url, orderBy, function (result) {
                let data = result.data;
                let html = '';

                if (data.length == 0) {
                    html += '<tr>No users in database.</tr>';
                } else

                for (let i in data) {
                    html += '<tr>';
                    for (let attr in data[i]) {
                        if (attr == 'id') {
                            html += '<td><a href="{{ route('users.index') }}/' + data[i][attr] + '/edit" class="btn btn-info" role="button"><i class="far fa-edit"></i></a></td>';
                        } else {
                            html += '<td class="align-middle">' + data[i][attr] + '</td>';
                        }
                    }
                    html += '</tr>';
                }

                document.getElementById("users").innerHTML = html;
            });
        }
    </script>
@endsection