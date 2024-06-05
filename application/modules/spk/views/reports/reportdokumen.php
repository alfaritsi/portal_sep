<?php
$this->load->view('header')
?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title"><strong><?php echo $title; ?></strong></h3>

                    </div>
                    <div class="box-body">
                        <form name="filter" method="post">
                            <div class="row">
                                <div class="col-md-2 col-md-offset-6">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <label class="input-group-addon" for="id_plant">Plant</label>
                                            <select class="form-control select2" name="id_plant" id="id_plant"
                                                    data-placeholder="Pilih plant" data-allow-clear="true">
                                                <option></option>
                                                <?php foreach ($plants as $plant) : ?>
                                                    <option value="<?php echo $plant->id_pabrik ?>" <?php echo ($plant->id_pabrik == $id_plant) ? 'selected' : ''; ?>><?php echo $plant->plant ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="input-group input-daterange" id="filter-date">
                                            <label class="input-group-addon" for="tanggal-awal_filter">Tanggal Berlaku</label>
                                            <input type="text" id="tanggal_awal_filter" name="tanggal_awal"
                                                   value="<?php echo $this->generate->generateDateFormat($tanggal_awal); ?>"
                                                   class="form-control" autocomplete="off">
                                            <label class="input-group-addon" for="tanggal-awal_filter">-</label>
                                            <input type="text" id="tanggal_akhir_filter" name="tanggal_akhir"
                                                   value="<?php echo $this->generate->generateDateFormat($tanggal_akhir); ?>"
                                                   class="form-control" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <table class="table my-datatable-extends-order table-bordered table-striped" id="spk-table"
                               data-page-length="10" data-order="[]"
                               style="font-size: 14px;">
                            <thead>
                            <tr>
                                <th width="5%">Plant</th>
                                <th width="10%">Jenis SPK</th>
                                <th width="20%">Nama SPK</th>
                                <th width="10%">Nomor SPK</th>
                                <th width="10%">Perihal</th>
                                <th width="10%">Tanggal</th>
                                <th width="5%">Vendor</th>
                                <th width="5%">Status</th>
                                <th width="5%">Final Dokumen</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($list as $dt) {
                                $na = ($dt->na == 'n') ? "<i class='fa fa-check-square text-success'></i>" : "<i class='fa fa-minus-square text-danger'></i>";
                                echo "<tr>";
                                echo "<td>" . $dt->plant . "</td>";
                                echo "<td>" . $dt->jenis_spk . "</td>";
                                echo "<td>" . $dt->nama_spk . "</td>";
                                echo "<td>" . $dt->nomor_spk . "</td>";
                                echo "<td>" . $dt->perihal . "</td>";
                                echo "<td class='text-nowrap'>"
                                    . "<b>Perjanjian :</b> " . $this->generate->generateDateFormat($dt->tanggal_perjanjian) . "<br/>"
                                    . "<b>Berlaku :</b> " . $this->generate->generateDateFormat($dt->tanggal_berlaku_spk) . "<br/>"
                                    . "<b>Berakhir :</b> " . $this->generate->generateDateFormat($dt->tanggal_berakhir_spk) . "<br/>"
                                    . (isset($dt->tanggal_approve) ? "<b>Input :</b> " . $this->generate->generateDateFormat($dt->tanggal_approve) : '')
                                    . "</td>";
                                echo "<td>" . $dt->jenis_vendor . "</td>";
                                echo "<td><span class = 'badge " . $dt->warna . "'>" . ucfirst($dt->status) . "</span>&nbsp;";
                                echo "<a href='javascript:void(0)' data-toggle='collapse' data-target='#table-divisi-" . $dt->id_spk . "'><span class = 'badge bg-light-blue'><i class='fa fa-search'></i> Lihat approval</span></a><br/><br/>";
                                echo $dt->table_divisi;
                                echo "</td>";

                                $dt->final = null;
                                if (isset($dt->files)) {
                                    $dt->files = site_url('assets/' . $dt->files);
                                    $dt->final = "<a href='$dt->files' data-fancybox><span class='badge bg-red-gradient'><i class='fa fa-file-pdf-o'></i></span> </a>";
                                }

                                echo "<td>"
                                    . $dt->final
                                    . "</td>";
                                echo "</tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $this->load->view('footer') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/apps/css/spk/spk.global.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker3.min.css"/>
<script src="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/spk/spk.global.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/spk/report_dokumen.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>