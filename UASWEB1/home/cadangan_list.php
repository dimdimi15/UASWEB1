<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">List News</h2>
        <button onclick="redirectToEditPage()">Tambah Data</button>
        <table id="newsTable" class="table table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Title</th>
                    <th>Deskripsi</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <script>
        function redirectToEditPage() {
            window.location.href = 'tambah.php';
        }

        $(document).ready(function() {
            var table = $('#newsTable').DataTable({
                "processing": true,
                "serverSide": true, // Corrected spelling
                "ajax": function(data, callback, settings) {
                    axios.get('https://client-server-dimas.000webhostapp.com/listnews.php', {
                            params: {
                                key: data.search.value
                            }
                        })
                        .then(function(response) {
                            response.data.forEach(function(row, index) {
                                row.no = index + 1;
                            });

                            callback({
                                draw: data.draw,
                                recordsTotal: response.data.length,
                                recordsFiltered: response.data.length,
                                data: response.data
                            });
                        });
                },
                "columns": [{
                    "data": "no"
                }, {
                    "data": "title"
                }, {
                    "data": "desc"
                }, {
                    "data": "img",
                    "render": function(data, type, row) {
                        return '<img src="upload/' + row.img + '" alt="image" style="max-width: 100px; max-height: 100px;"> ' +
                            '<form action="edit.php" method="post">' +
                            '<input type="hidden" name="id" value="' + row.id + '">' +
                            '<button type="submit" class="btn btn-primary btn-sm">Edit</button>' +
                            '</form>';
                    }
                }]
            });
        });
    </script>
</body>

</html>