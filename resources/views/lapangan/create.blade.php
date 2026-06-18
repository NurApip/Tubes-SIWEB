<h1>Tambah Lapangan Baru</h1>
<form action="/lapangan" method="POST">
    @csrf
    <input type="text" name="nama_lapangan" placeholder="Nama Lapangan" required><br>
    <input type="text" name="tipe_rumput" placeholder="Tipe Rumput" required><br>
    <input type="number" name="harga_per_jam" placeholder="Harga per Jam" required><br>
    <input type="text" name="lokasi_id" placeholder="ID Lokasi (1)" required><br>
    <textarea name="fasilitas" placeholder="Fasilitas"></textarea><br>
    <textarea name="deskripsi" placeholder="Deskripsi"></textarea><br>
    <button type="submit">Simpan Data</button>
</form>