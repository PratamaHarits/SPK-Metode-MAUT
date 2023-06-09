<?php
// koneksi
include '../tools/connection.php';

// header
include '../blade/header.php';
?>

<div class="row">
    <div class="col-lg-1"></div>
    <div class="col-lg-10">

        <!-- kop surat -->
        <p class="text-center fw-bold m-0">PT KARET ABC</p>
        <p class="text-center m-0">Jl. Maju Mundur No. 1 Kota Padang Telepon (0751) 11111</p>
        <p class="text-center m-0">Email : karet@abc.com</p>
        <hr>

        <!-- isi surat -->
        <p class="text-center fw-bold">Laporan Rekomendasi Getah Karet Terbaik</p>
        <p class="text-justify">Berdasarkan hasil pengolahan data dengan menggunakan beberapa kriteria yang sudah ditentukan dan dengan mengimplementasikan metode Multi-Attribute Utility Theory (MAUT), maka menghasilkan tiga rangking teratas sebagai berikut : </p>

        <?php $ranks = array(); ?>

        <!-- <p class="text-center fw-bold">Tabel Konversi Nilai</p>
        <table class="table table-striped table-bordered">
            <thead>
                <tr class="table-info">
                    <th rowspan="2">No</th>
                    <th rowspan="2">Nama Alternatif</th>
                    <?php
                    $data = $conn->query("SELECT * FROM ta_kriteria");
                    $kriteriaRows = mysqli_num_rows($data);
                    ?>
                    <th colspan="<?= $kriteriaRows; ?>">Nama Kriteria</th>
                </tr>
                <tr class="table-info">

                    <?php
                    $data = $conn->query("SELECT * FROM ta_kriteria");
                    while ($kriteria = $data->fetch_assoc()) { ?>
                        <td><?= $kriteria['kriteria_nama']; ?></td>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $data = $conn->query("SELECT * FROM ta_alternatif ORDER BY alternatif_kode");
                $no = 1;
                while ($alternatif = $data->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $alternatif['alternatif_nama'] ?></td>
                        <?php
                        $alternatifKode = $alternatif['alternatif_kode'];
                        $sql = $conn->query("SELECT * FROM tb_nilai WHERE alternatif_kode='$alternatifKode' ORDER BY kriteria_kode");
                        while ($data_nilai = $sql->fetch_assoc()) { ?>
                            <td><?= $data_nilai['nilai_faktor'] ?></td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <p class="text-center fw-bold">Tabel Normalisasi</p>
        <table class="table table-striped table-bordered">
            <thead>
                <tr class="table-info">
                    <th rowspan="2">No</th>
                    <th rowspan="2">Nama Alternatif</th>
                    <?php
                    $data = $conn->query("SELECT * FROM ta_kriteria");
                    $kriteriaRows = mysqli_num_rows($data);
                    ?>
                    <th colspan="<?= $kriteriaRows; ?>">Nama Kriteria</th>
                </tr>
                <tr class="table-info">
                    <?php
                    $data = $conn->query("SELECT * FROM ta_kriteria");
                    while ($kriteria = $data->fetch_assoc()) { ?>
                        <td><?= $kriteria['kriteria_nama']; ?></td>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $data = $conn->query("SELECT * FROM ta_alternatif ORDER BY alternatif_kode");
                $no = 1;
                while ($alternatif = $data->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $alternatif['alternatif_nama'] ?></td>
                        <?php
                        $alternatifKode = $alternatif['alternatif_kode'];
                        $sql = $conn->query("SELECT * FROM tb_nilai WHERE alternatif_kode='$alternatifKode' ORDER BY kriteria_kode");
                        while ($data_nilai = $sql->fetch_assoc()) { ?>
                            <?php
                            $kriteriaKode = $data_nilai['kriteria_kode'];
                            $sqli = $conn->query("SELECT * FROM ta_kriteria WHERE kriteria_kode='$kriteriaKode' ORDER BY kriteria_kode");
                            while ($kriteria = $sqli->fetch_assoc()) {
                            ?>
                                <?php if ($kriteria['kriteria_kategori'] == "benefit") { ?>
                                    <?php
                                    // nilai tertinggi
                                    $sqlMax =  $conn->query("SELECT kriteria_kode, MAX(nilai_faktor) AS max FROM tb_nilai WHERE kriteria_kode='$kriteriaKode' GROUP BY kriteria_kode");
                                    while ($nilai_Max = $sqlMax->fetch_assoc()) {
                                    ?>
                                        <?php $nilai_tertinggi = $nilai_Max['max']; ?>
                                    <?php } ?>
                                    <?php
                                    // nilai terrendah
                                    $sqlMin =  $conn->query("SELECT kriteria_kode, MIN(nilai_faktor) AS min FROM tb_nilai WHERE kriteria_kode='$kriteriaKode' GROUP BY kriteria_kode");
                                    while ($nilai_Min = $sqlMin->fetch_assoc()) {
                                    ?>
                                        <?php $nilai_terrendah = $nilai_Min['min']; ?>
                                    <?php } ?>
                                    <td><?= number_format($hasil_normalisasi = ($data_nilai['nilai_faktor'] - $nilai_terrendah) / ($nilai_tertinggi - $nilai_terrendah), 2); ?></td>

                                <?php } elseif ($kriteria['kriteria_kategori'] == "cost") { ?>
                                    <?php
                                    // nilai tertinggi
                                    $sqlMax =  $conn->query("SELECT kriteria_kode, MAX(nilai_faktor) AS max FROM tb_nilai WHERE kriteria_kode='$kriteriaKode' GROUP BY kriteria_kode");
                                    while ($nilai_Max = $sqlMax->fetch_assoc()) {
                                    ?>
                                        <?php $nilai_tertinggi = $nilai_Max['max'];
                                        ?>
                                    <?php } ?>
                                    <?php
                                    // nilai terrendah
                                    $sqlMin =  $conn->query("SELECT kriteria_kode, MIN(nilai_faktor) AS min FROM tb_nilai WHERE kriteria_kode='$kriteriaKode' GROUP BY kriteria_kode");
                                    while ($nilai_Min = $sqlMin->fetch_assoc()) {
                                    ?>
                                        <?php $nilai_terrendah = $nilai_Min['min']; ?>
                                    <?php } ?>
                                    <td><?= number_format($hasil_normalisasi = 1 + ($nilai_terrendah - $data_nilai['nilai_faktor']) / ($nilai_tertinggi - $nilai_terrendah), 2); ?></td>

                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <p class="text-center fw-bold">Tabel Hasil Preferensi</p>
        <table class="table table-striped table-bordered">
            <thead>
                <tr class="table-info">
                    <th rowspan="2">No</th>
                    <th rowspan="2">Nama Alternatif</th>
                    <?php
                    $data = $conn->query("SELECT * FROM ta_kriteria");
                    $kriteriaRows = mysqli_num_rows($data);
                    ?>
                    <th colspan="<?= $kriteriaRows; ?>">Nama Kriteria</th>
                    <th rowspan="2">Nilai Akhir</th>

                </tr>
                <tr class="table-info">
                    <?php
                    $data = $conn->query("SELECT * FROM ta_kriteria");
                    while ($kriteria = $data->fetch_assoc()) { ?>
                        <td><?= $kriteria['kriteria_nama']; ?></td>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $data = $conn->query("SELECT * FROM ta_alternatif ORDER BY alternatif_kode");
                $no = 1;
                while ($alternatif = $data->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $alternatif['alternatif_nama'] ?></td>
                        <?php $hasil_preferensi = 0; //variabel hasil_preferensi untuk proses sum nanti
                        ?>

                        <?php
                        $alternatifKode = $alternatif['alternatif_kode'];
                        $sql = $conn->query("SELECT * FROM tb_nilai WHERE alternatif_kode='$alternatifKode' ORDER BY kriteria_kode");
                        while ($data_nilai = $sql->fetch_assoc()) { ?>
                            <?php
                            $kriteriaKode = $data_nilai['kriteria_kode'];
                            $sqli = $conn->query("SELECT * FROM ta_kriteria WHERE kriteria_kode='$kriteriaKode' ORDER BY kriteria_kode");
                            while ($kriteria = $sqli->fetch_assoc()) {
                            ?>
                                <?php if ($kriteria['kriteria_kategori'] == "benefit") { ?>
                                    <?php
                                    // nilai tertinggi
                                    $sqlMax =  $conn->query("SELECT kriteria_kode, MAX(nilai_faktor) AS max FROM tb_nilai WHERE kriteria_kode='$kriteriaKode' GROUP BY kriteria_kode");
                                    while ($nilai_Max = $sqlMax->fetch_assoc()) {
                                    ?>
                                        <?php $nilai_tertinggi = $nilai_Max['max']; ?>
                                    <?php } ?>
                                    <?php
                                    // nilai terrendah
                                    $sqlMin =  $conn->query("SELECT kriteria_kode, MIN(nilai_faktor) AS min FROM tb_nilai WHERE kriteria_kode='$kriteriaKode' GROUP BY kriteria_kode");
                                    while ($nilai_Min = $sqlMin->fetch_assoc()) {
                                    ?>
                                        <?php $nilai_terrendah = $nilai_Min['min']; ?>
                                    <?php } ?>

                                    <?php number_format($hasil_normalisasi = ($data_nilai['nilai_faktor'] - $nilai_terrendah) / ($nilai_tertinggi - $nilai_terrendah), 2); ?>

                                    <td><?= number_format($nilai_preferensi = $hasil_normalisasi * $kriteria['kriteria_bobot'], 2); ?></td>
                                    <?php $hasil_preferensi = $hasil_preferensi + $nilai_preferensi; ?>
                                <?php } elseif ($kriteria['kriteria_kategori'] == "cost") { ?>
                                    <?php
                                    // nilai tertinggi
                                    $sqlMax =  $conn->query("SELECT kriteria_kode, MAX(nilai_faktor) AS max FROM tb_nilai WHERE kriteria_kode='$kriteriaKode' GROUP BY kriteria_kode");
                                    while ($nilai_Max = $sqlMax->fetch_assoc()) {
                                    ?>
                                        <?php $nilai_tertinggi = $nilai_Max['max'];
                                        ?>
                                    <?php } ?>
                                    <?php
                                    // nilai terrendah
                                    $sqlMin =  $conn->query("SELECT kriteria_kode, MIN(nilai_faktor) AS min FROM tb_nilai WHERE kriteria_kode='$kriteriaKode' GROUP BY kriteria_kode");
                                    while ($nilai_Min = $sqlMin->fetch_assoc()) {
                                    ?>
                                        <?php $nilai_terrendah = $nilai_Min['min']; ?>
                                    <?php } ?>

                                    <?php number_format($hasil_normalisasi = 1 + ($nilai_terrendah - $data_nilai['nilai_faktor']) / ($nilai_tertinggi - $nilai_terrendah), 2); ?>

                                    <td><?= number_format($nilai_preferensi = $hasil_normalisasi * $kriteria['kriteria_bobot'], 2); ?></td>

                                    <?php $hasil_preferensi = $hasil_preferensi + $nilai_preferensi; ?>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>

                        <td><?= number_format($hasil_preferensi, 2);  //tampilkan hasil_preferensi
                            ?></td>

                        <?php
                        //masukan  nilai hasil-sum, nama-alternatif, kode-alternatif ke dalam variabel $ranks(baris 26)
                        $rank['hasil_preferensi'] = $hasil_preferensi;
                        $rank['alternatifNama'] = $alternatif['alternatif_nama'];
                        $rank['alternatifKode'] = $alternatif['alternatif_kode'];
                        array_push($ranks, $rank);
                        ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table> -->

        <div class="row mt-3">
            <div class="col-1"></div>
            <div class="col-10">
                <table class="table table-bordered">
                    <thead>
                        <tr">
                            <th>Ranking</th>
                            <th>Kode Alternatif</th>
                            <th>Nama Alternatif</th>
                            <th>Nilai MAUT</th>
                            <th>Keputusan</th>
                            </tr>
                    </thead>
                    <tbody>
                        <?php
                        $ranking = 1;
                        rsort($ranks);
                        foreach ($ranks as $r) {
                        ?>
                            <tr>
                                <td><?= $ranking++; ?></td>
                                <td><?= $r['alternatifKode']; ?></td>
                                <td><?= $r['alternatifNama']; ?></td>
                                <td><?= number_format($r['hasil_preferensi'], 2); ?></td>
                                <td><?= ($ranking <= 4) ? 'Direkomendasikan' : 'Tidak Direkomendasikan'; ?></td>
                            </tr>
                        <?php
                            //jika hanya menampilkan 3 nilai teratas
                            if ($ranking > 3) {
                                break;
                            }
                        } ?>
                    </tbody>
                </table>
            </div>
            <div class="col-1"></div>
        </div>

        <p class="text-justify">Demikian surat ini kami sampaikan atas perhatian bapak / ibu / saudara, kami ucapkan terimakasih</p>

        <br><br>

        <p style=" text-align: right;">Padang, <?php echo date("d/m/Y") ?></p><br><br>
        <p style=" text-align: right;">PT Karet ABC Kota Padang</p>

    </div>
    <div class="col-lg-1"></div>
</div>

<script>
    window.print();
</script>