<?php
$id = isset($_POST['id']) ? $_POST['id'] : null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" type="text/css" href="../style/edits.css"> <!-- Sesuaikan path sesuai dengan struktur direktori proyek Anda -->
</head>

<body> <!-- Tambahkan tag body -->
    <div class="container mt-5">
        <h2 class="mb-4">Edit news </h2>
        <form id="addNewsForm">
            <input type="hidden" class="form-control" maxlength="50" id="id" value="<?php echo $id; ?>">
            <input type="hidden" name="previewValue" id="previewValue">
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
                <input type="file" class="form-control" id="url_image" name="url_image" accept="image/*" required>
            </div>

            <button type="button" class="btn btn-primary" onclick="editnews()">edit news</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Pastikan untuk menginisialisasi fungsi getData() setelah elemen telah dimuat ke dalam DOM
        document.addEventListener('DOMContentLoaded', function () {
            getData();
        });

        function getData() {
            const newId = document.getElementById('id').value;
            var formData = new FormData();
            formData.append('id', newId)
            axios.post('https://client-server-dimas.000webhostapp.com/selecdata.php', formData)
                .then(function (response) {
                    document.getElementById('judul').value = response.data.title;
                    document.getElementById('deskripsi').value = response.data.desc;
                    document.getElementById('previewValue').value = response.data.img;
                })
                .catch(function () {
                    console.error(error);
                    alert('error news data');
                });

        }

        function editnews() {
            const newsId = document.getElementById('id').value;
            const judul = document.getElementById('judul').value;
            const deskripsi = document.getElementById('deskripsi').value;
            const urlImageInput = document.getElementById('url_image');
            const prevImg = document.getElementById('previewValue').value;
            const url_image = urlImageInput.files[0];

            var formData = new FormData();
            formData.append('id', newsId); // Ubah 'idnews' menjadi 'id'
            formData.append('judul', judul);
            formData.append('deskripsi', deskripsi);
            formData.append('prevImg', prevImg);

            if (urlImageInput.files.length > 0) {
                formData.append('url_image', url_image);
            } else {
                formData.append('url_image', null);
            }

            axios.post('https://client-server-dimas.000webhostapp.com/editnews.php', formData, {
                header: {
                    'Content-Type': 'multipart/form-data',
                },
            })
                .then(function (response) {
                    console.log(response.data);
                    alert(response.data);
                    window.location.href = 'list.php';
                })
                .catch(function (response) {
                    console.error(error);
                    alert('error editing ');
                });
        }
    </script>
</body>

</html>
