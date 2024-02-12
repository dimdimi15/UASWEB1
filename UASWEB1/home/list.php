<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Tombol Prev dan Next */
        .dataTables_paginate .paginate_button.previous,
        .dataTables_paginate .paginate_button.next {
            background-color: #007bff;
            /* Warna latar belakang */
            color: #fff;
            /* Warna teks */
            padding: 5px 10px;
            /* Padding tombol */
            border-radius: 5px;
            /* Sudut border */
            border: none;
            /* Hapus border */
            cursor: pointer;
            /* Ubah kursor saat dihover */
        }

        /* Tombol Prev dan Next saat dihover */
        .dataTables_paginate .paginate_button.previous:hover,
        .dataTables_paginate .paginate_button.next:hover {
            background-color: #0056b3;
            /* Warna latar belakang saat dihover */
        }

        /* Tombol Prev dan Next saat dinonaktifkan */
        .dataTables_paginate .paginate_button.disabled {
            background-color: #f8f9fa;
            /* Warna latar belakang saat dinonaktifkan */
            color: #6c757d;
            /* Warna teks saat dinonaktifkan */
            cursor: not-allowed;
            /* Kursor tidak diizinkan saat dinonaktifkan */
        }

        /* Halaman */
        .dataTables_paginate .paginate_button {
            background-color: #f8f9fa;
            /* Warna latar belakang */
            color: #007bff;
            /* Warna teks */
            padding: 5px 10px;
            /* Padding tombol */
            border-radius: 5px;
            /* Sudut border */
            border: 1px solid #007bff;
            /* Border */
            cursor: pointer;
            /* Ubah kursor saat dihover */
        }

        /* Halaman saat dihover */
        .dataTables_paginate .paginate_button:hover {
            background-color: #007bff;
            /* Warna latar belakang saat dihover */
            color: #fff;
            /* Warna teks saat dihover */
        }
        
        footer {
            background-color: #000080;
            color: #ffffff;
            text-align: center;
            padding: 10px;
            margin-top: 20px; /* Mengatur margin atas */
        }
    </style>
</head>

<body>

    <div class="container">
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="list.php">Navbar</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                username
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end me-5">
                                <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                                <li><a class="dropdown-item" onclick="logout()">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <div class="container mt-5">
        <h2 class="mb-4">List News</h2>
        <button class="btn btn-primary" onclick="redirectToTambahPage()">Tambah Data</button>
        <button class="btn btn-primary" onclick="exportToPDF()">PDF</button>
        <button class="btn btn-primary" onclick="exportToExcel()">Excel</button>
        <table id="newsTable" class="table table-bordered">
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
    <footer style="background-color: #000080; color: #ffffff; text-align: center; padding: 10px;">
    &copy;Copyright by 21552011451_Dimas Anggara_TIFRP221PB_UASWEB1
    </footer>

    <script>
        var sessionToken = localStorage.getItem('session_token');
        var currentPage = 1;
        var totalRecords = 0;

        function prevPage() {
            if (currentPage > 1) {
                currentPage--;
                updateTableData();
            }
        }

        function nextPage() {
            if ((currentPage * 10) < totalRecords) {
                currentPage++;
                updateTableData();
            }
        }

        function updateTableData() {
            var table = $('#newsTable').DataTable();
            table.ajax.url('https://client-server-dimas.000webhostapp.com/listnews.php?start=' + (currentPage - 1) * 10).load();
            $('#prevButton').prop('disabled', currentPage === 1);
            $('#nextButton').prop('disabled', (currentPage * 10) >= totalRecords);
        }


        function logout() {
            var formData = new FormData();
            formData.append('session_token', sessionToken);
            if (sessionToken) {
                // Kirim permintaan POST ke URL logout
                axios.post('https://client-server-dimas.000webhostapp.com/logout.php', formData)
                    .then(function(response) {
                        // Handle response
                        if (response.data.status === 'success') {
                            // Hapus session token dari localStorage
                            localStorage.removeItem('session_token');
                            // Redirect ke halaman login atau halaman lainnya
                            window.location.href = 'index.php'; // Ganti dengan halaman login atau halaman lain yang sesuai
                        } else {
                            // Jika ada kesalahan, tampilkan pesan kesalahan
                            alert(response.data.message);
                        }
                    })
                    .catch(function(error) {
                        // Handle error
                        console.error(error);
                        // Tampilkan pesan kesalahan jika terjadi kesalahan saat mengirim permintaan
                        alert('Terjadi kesalahan saat melakukan logout.');
                    });
            } else {
                // Jika session_token kosong, tampilkan pesan kesalahan
                alert('Session token tidak ditemukan. Silakan login kembali.');
            }
        }

        function redirectToTambahPage() {
            window.location.href = 'tambah.php';
        }

        function redirectToEditPage() {
            window.location.href = 'edit.php';
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
                "ajax": {
                    "url": 'https://client-server-dimas.000webhostapp.com/listnews.php',
                    "dataSrc": "data"
                },
                "columns": [{
                        "data": null,
                        "render": function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        "data": "title"
                    },
                    {
                        "data": "desc"
                    },
                    {
                        "data": "img",
                        "render": function(data, type, row) {
                            return '<img src="upload/' + data + '" alt="image" style="max-width: 100px; max-height: 100px;">';
                        }
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return '<button type="button" class="btn btn-primary" onclick="redirectToEditPage()">Edit</button>' +
                                '<button type="button" class="btn btn-danger" onclick="hapusListItem(' + row.id + ')">Hapus</button>';
                        }
                    }
                ],
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ], // Konfigurasi untuk Show entries
                "searching": true, // Mengaktifkan fungsi pencarian
                "initComplete": function(settings, json) {
                    $('#prevButton').prop('disabled', true);
                    $('#nextButton').prop('disabled', (currentPage * 10) >= totalRecords);
                }
            });

            table.on('order.dt search.dt', function() {
                table.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        });

        var fd = new FormData();
        fd.append('session_token', sessionToken);
        axios.post('https://client-server-dimas.000webhostapp.com/profiles.php', fd)
            .then(function(response) {
                if (response.data.status === 'success') {
                    var user = response.data.user_profile;
                    var username = user.username;
                    document.getElementById('navbarDropdown').innerText = username;
                } else {
                    console.error('Gagal mengambil data profil');
                }
            })
            .catch(function(error) {
                // Handle error
                console.error(error);
            });
    </script>
</body>

</html>