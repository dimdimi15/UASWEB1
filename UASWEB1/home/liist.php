<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>

    <link rel="stylesheet" type="text/css" href="../style/tampilan.css">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">List News</h2>
        <button class="btn btn-primary" onclick="redirectToEditPage()">Tambah Data</button>
        <button class="btn btn-primary" onclick="exportToPDF()">PDF</button>
        <button class="btn btn-primary" onclick="exportToExcel()">Excel</button>
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
        // import {
        //     jsPDF
        // } from "jspdf";

        function redirectToEditPage() {
            window.location.href = 'tambah.php';
        }

        function hapusListItem(id) {
            var formData = new FormData();
            formData.append('id', id);
            if (confirm("Apakah anda yakin ingin menghapus item?")) {
                axios.post('https://client-server-dimas.000webhostapp.com/deletenews.php', formData, {
                        header: {
                            'Content-Type': 'multipart/form-data',
                        },
                    })
                    .then(function(response) {
                        if (response.status === 'success') {
                            alert(response.message);
                        }
                    });
            }
        }

        function exportToPDF() {
            var element = document.getElementById('newsTable');
            html2pdf()
                .from(element)
                .save('news_list.pdf');
        }



        function exportToExcel() {
            var tableData = $('#newsTable').DataTable().data().toArray();
            var ws = XLSX.utils.json_to_sheet(tableData);
            var wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'News List');
            XLSX.writeFile(wb, 'news_list.xlsx');
        }


        $(document).ready(function() {
            var table = $('#newsTable').DataTable({
                "processing": true,
                "serverSide": true,
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
                        return '<img src="upload/' + row.img + '" alt="image" style="max-width: 100px; max-height: 100px;">';
                    }
                }, {
                    "data": null,
                    "render": function(data, type, row) {
                        return '<form action="edit.php" method="post">' +
                            '<input type="hidden" name="id" value="' + row.id + '">' +
                            '<button type="submit" class="btn btn-primary btn-sm mb-2">Edit</button>' +
                            '<button type="button" class="btn btn-danger btn-sm">Hapus</button>' +
                            '</form>';
                    }
                }]
            });
            $('#newsTable tbody').on('click', 'button.btn-danger', function() {
                var data = table.row($(this).parents('tr')).data();
                console.log(`ini id : ${data.id}`);
                hapusListItem(data.id);
            });
        });
        
    </script>
</body>

</html>