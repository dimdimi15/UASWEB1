<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../style/tambahs.css">
    <title>Tambah</title>
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Tambah news Form</h2>
        <form id="addNewsForm">
            <div class="form-group">
                <label for="judul">title:</label>
                <input type="text" class="form-control" maxlength="50" id="judul" required>
            </div>

            <div class="form-group">
                <label for="deskripsi">content: </label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" required></textarea>
            </div>

            <div class="form-group">
                <label for="url_image">image</label>
                <input type="file" class="form-control" id="url_image" name="url_image" accept="image/*">
            </div>

            <button type="button" class="btn btn-primary" onclick="tambahdata()">Tambah news</button>

        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        function tambahdata() {
            const judul = document.getElementById('judul').value;
            const deskripsi = document.getElementById('deskripsi').value;
            const urlImageInput = document.getElementById('url_image');
            const url_image = urlImageInput.files[0];

            var formData = new FormData();
            formData.append('judul', judul);
            formData.append('deskripsi', deskripsi);

            if (urlImageInput.files.length > 0) {
                formData.append('url_image', url_image);
            }

            axios.post('https://client-server-dimas.000webhostapp.com/tambahnews.php', formData, {
                    header: {
                        'Content-Type': 'multipart/form-data',
                    },
                })
                .then(function(response) {
                    if (response.data.status === 'success') {
                        if (confirm("item berhasil di tambahkan")) {
                            window.location.href = 'list.php';
                        }
                    } else {
                        alert(response.data.message);
                    }
                });
        }
    </script>
</body>

</html>