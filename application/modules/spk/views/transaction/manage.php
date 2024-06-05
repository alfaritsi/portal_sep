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
                        <?php if (in_array($leg_level_id, array(1, 2))) : ?>
                            <div class="btn-group btn-group-sm pull-right">
                                <a href="javascript:void(0)" class="btn btn-sm btn-success" id="add-spk">
                                    <i class="fa fa-plus-square"></i> &nbsp Add SPK
                                </a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-success" id="download-template">
                                    <i class="fa fa-download"></i> &nbsp Download Template
                                </a>
                            </div>
                        <?php endif; ?> 
                    </div>
	          		<!-- /.box-header -->
		          	<div class="box-body">
						<form name="filter-data-spk" method="post">
							<div class="row">
								<div class="col-sm-2">
									<div class="form-group">
										<label> Plant: </label>
										<select class="form-control select2" multiple="multiple" id="filter_plant" name="filter_plant[]" data-placeholder="Pilih Plant">
											<?php
											$arr_plant	= (empty($_POST['filter_plant']))? NULL : $_POST['filter_plant'];
											foreach ($filter_plant as $j) :	
												$selected = (in_array($j->plant, $arr_plant))?"selected='selected'":"";	
												echo "<option value='".$j->plant."' ".$selected.">".$j->plant."</option>";
											endforeach;
											?>
										</select>
										<!--<select class="form-control select2" multiple="multiple" id="filter_plant" name="filter_plant[]" style="width: 100%;" data-placeholder="Pilih Plant"></select>-->
										<!--<select class="form-control select2" id="filter_plant" name="filter_plant" style="width: 100%;" data-placeholder="Pilih Plant"></select>-->
									</div>
								</div>
								<div class="col-sm-3">
									<div class="form-group">
										<label> Jenis SPK: </label>
										<select class="form-control select2" multiple="multiple" id="filter_jenis" name="filter_jenis[]" data-placeholder="Pilih Jenis SPK">
											<?php
											$arr_jenis	= (empty($_POST['filter_jenis']))? NULL : $_POST['filter_jenis'];
											foreach ($filter_jenis as $j) :	
												$selected = (in_array($j->id_jenis_spk, $arr_jenis))?"selected='selected'":"";	
												echo "<option value='".$j->id_jenis_spk."' ".$selected.">".$j->jenis_spk."</option>";
											endforeach;
											?>
										</select>
									</div>
								</div>
								<div class="col-sm-2">
									<div class="form-group">
										<label> Tgl Berlaku: </label>
										<div class="input-group input-daterange" id="filter-date">
											<?php 
											$filter_tanggal_berlaku_awal	= (empty($_POST['filter_tanggal_berlaku_awal']))? NULL : $_POST['filter_tanggal_berlaku_awal'];
											$filter_tanggal_berlaku_akhir	= (empty($_POST['filter_tanggal_berlaku_akhir']))? NULL : $_POST['filter_tanggal_berlaku_akhir'];
											?>
                                            <input type="text" id="tanggal_awal_filter" name="filter_tanggal_berlaku_awal" value="<?php echo $filter_tanggal_berlaku_awal;?>"  class="form-control" autocomplete="off">
                                            <label class="input-group-addon" for="tanggal-awal">-</label>
                                            <input type="text" id="tanggal_awal_filter" name="filter_tanggal_berlaku_akhir" value="<?php echo $filter_tanggal_berlaku_akhir;?>"  class="form-control" autocomplete="off">
										</div>
									</div>
								</div>
								<div class="col-sm-2">
									<div class="form-group">
										<label> Tgl Berakhir: </label>
										<div class="input-group input-daterange" id="filter-date">
											<?php 
											$filter_tanggal_berakhir_awal	= (empty($_POST['filter_tanggal_berakhir_awal']))? NULL : $_POST['filter_tanggal_berakhir_awal'];
											$filter_tanggal_berakhir_akhir	= (empty($_POST['filter_tanggal_berakhir_akhir']))? NULL : $_POST['filter_tanggal_berakhir_akhir'];
											?>
                                            <input type="text" id="tanggal_awal_filter" name="filter_tanggal_berakhir_awal" value="<?php echo $filter_tanggal_berakhir_awal;?>" class="form-control" autocomplete="off">
                                            <label class="input-group-addon" for="tanggal-awal">-</label>
											<input type="text" id="tanggal_awal_filter" name="filter_tanggal_berakhir_akhir" value="<?php echo $filter_tanggal_berakhir_akhir;?>" class="form-control" autocomplete="off">
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="form-group">
										<label> Status: </label>
										<select class="form-control select2" multiple="multiple" id="filter_status" name="filter_status[]" data-placeholder="Pilih Status SPK">
											<?php
											$arr_status	= (empty($_POST['filter_status']))? NULL : $_POST['filter_status'];
											foreach ($filter_status as $j) :	
												$selected = (in_array($j->id_status, $arr_status))?"selected='selected'":"";	
												echo "<option value='".$j->id_status."' ".$selected.">".$j->status."</option>";
											endforeach;
											?>
										</select>
									</div>
								</div>
							</div>
						</form>
		            </div>					
					<!-- /.box-filter -->					
                    <div class="box-body">
                        <table class="table my-datatable-extends-order table-bordered table-striped" id="spk-table"
                               data-page-length="10" data-order="[]"
                               style="font-size: 12px;">
                            <thead>
                            <tr>
                                <th width="2%" data-searchable="false">&nbsp;</th>
                                <th width="5%">Plant</th>
                                <th width="10%">SPK</th>
                                <th width="20%">Dokumen</th>
                                <th width="10%">Tanggal</th>
                                <th width="10%">Vendor</th>
                                <th width="5%">Status</th>
                                <th width="5%">Final SPK</th>
                                <th width="5%">Resi</th>
                                <th data-orderable="false" data-searchable="false" width="5%"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($list as $i => $dt) { 
                                $na = ($dt->na == 'n') ? "<i class='fa fa-check-square text-success'></i>" : "<i class='fa fa-minus-square text-danger'></i>";
                                echo "<tr>";
                                echo "<td>" . ($i + 1) . "</td>"; 
                                echo "<td>" . $dt->plant . "</td>";  
                                echo "<td>"    
                                    . "<b>Jenis SPK :</b>" . $dt->jenis_spk . "<br/>" 
                                    . "<b>Nama SPK :</b>" . $dt->nama_spk . "<br/>"
                                    . "<b>SPPKP :</b> " . $dt->SPPKP . "<br/>"  
                                    . "<b>Perihal :</b>" . $dt->perihal . "<br/>"
                                    . "</td>";
                                echo "<td>";
                                echo "	<a href='javascript:void(0)' class='spk-attachments' data-tipe='template' data-id_spk='" . $dt->id_spk . "'><span class = 'badge bg-light-blue'><i class='fa fa-files-o'></i> Lihat file template</span></a><br/>";
                                if($dt->id_kualifikasi!=null){
									echo "	<a href='javascript:void(0)' class='spk-attachments' data-tipe='vendor_dokumen' data-id_spk='" . $dt->id_spk . "'><span class = 'badge bg-light-blue'><i class='fa fa-files-o'></i> Lihat file vendor</span></a><br/>";	
									echo "	<a href='javascript:void(0)' class='spk-attachments' data-tipe='vendor_kualifikasi' data-id_spk='" . $dt->id_spk . "' data-id_jenis_spk='" . $dt->id_jenis_spk . "'><span class = 'badge bg-light-blue'><i class='fa fa-files-o'></i> Lihat file kualifikasi</span></a><br/>";	
								}else{
									echo "	<a href='javascript:void(0)' class='spk-attachments' data-tipe='vendor' data-id_spk='" . $dt->id_spk . "'><span class = 'badge bg-light-blue'><i class='fa fa-files-o'></i> Lihat file vendor</span></a><br/>";	
								}
								if(ucfirst($dt->status)=='Dropped'){
									$link_file = site_url('spk/view_file?file=' . $dt->file_cancel);
									echo "	<a href='" . $link_file . "' data-fancybox><span class = 'badge bg-light-blue'><i class='fa fa-files-o'></i> Lihat file berita acara</span></a><br/>";
								}
                                echo "</td>";
                                echo "<td class='text-nowrap'>"
                                    . "<b>Perjanjian :</b> " . $this->generate->generateDateFormat($dt->tanggal_perjanjian) . "<br/>"
                                    . "<b>Berlaku :</b> " . $this->generate->generateDateFormat($dt->tanggal_berlaku_spk) . "<br/>"
                                    . "<b>Berakhir :</b> " . $this->generate->generateDateFormat($dt->tanggal_berakhir_spk) . "<br/>"
                                    . (isset($dt->tanggal_buat) ? "<b>Submit :</b> " . $this->generate->generateDateFormat($dt->tanggal_buat) : ''). "<br/>"
                                    . (isset($dt->tanggal_approve) ? "<b>Approve :</b> " . $this->generate->generateDateFormat($dt->tanggal_approve) : ''). "<br/>"
									. (isset($dt->tanggal_final) ? "<b>Final Draft :</b> " . $this->generate->generateDateFormat($dt->tanggal_final) : '')
                                    . "</td>";
                                echo "<td>" 
                                    . $dt->jenis_vendor . "<br/>"
                                    . "<i class='fa fa-user-circle'></i>&nbsp;&nbsp;" . $dt->nama_vendor
                                    . "</td>"; 
                                echo "<td>";
								echo "<span class = 'badge " . $dt->warna . "'>" . ucfirst($dt->status) . "</span>&nbsp;";
								if(ucfirst($dt->status)=='Cancelled'){
									echo "<br/><b>Status Akhir : </b>".$dt->status_akhir."<br/>";
									echo "<b>Alasan : </b>".$dt->alasan_cancel."<br/>";
									echo "<b>Keterangan : </b>".$dt->keterangan_cancel."<br/>";
								}
                                echo "<a href='javascript:void(0)' data-toggle='collapse' data-target='#table-divisi-" . $dt->id_spk . "'><span class = 'badge bg-light-blue'><i class='fa fa-search'></i> Lihat approval</span></a><br/><br/>";
                                echo $dt->table_divisi;
                                echo "</td>";
                                echo "<td>$dt->final</td>";
                                echo "<td>"
                                    . (!empty($dt->tanggal_kirim) ? "<span class='badge bg-green'>Tanggal kirim : " . $this->generate->generateDateFormat($dt->tanggal_kirim) . "</span>" : '')
                                    . (!empty($dt->no_resi) ? "</br><span class='badge bg-black'>No.Resi : " . $dt->no_resi . "</span>" : '')
                                    . "&nbsp;</td>";
                                echo "<td>
				                          <div class='input-group-btn'>
				                            <a type='button' class='btn btn-app dropdown-toggle' data-toggle='dropdown'>";
												if($dt->jumlah_komentar!=0){
													echo"<span class='badge bg-yellow'>".$dt->jumlah_komentar."</span>";	
												}
								echo"						
												<i class='fa fa-th-large'></i>
												Action
											</a> 
				                            <ul class='dropdown-menu pull-right'>";
                                echo $dt->links;
                                echo "    </ul>  
				                          </div>
				                        </td>"; 
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
   
<?php $this->load->view('transaction/includes/modal_attachments') ?>
<?php $this->load->view('transaction/includes/modal_spk', compact('plant')) ?>
<?php $this->load->view('transaction/includes/modal_upload') ?>
<?php $this->load->view('transaction/includes/modal_review') ?>
<?php $this->load->view('transaction/includes/modal_final_draft') ?>
<?php $this->load->view('transaction/includes/modal_final') ?>
<?php $this->load->view('transaction/includes/modal_komentar') ?>
<?php $this->load->view('transaction/includes/modal_download') ?> 
<?php $this->load->view('transaction/includes/modal_cancel') ?> 
<?php $this->load->view('footer') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/apps/css/spk/spk.global.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker3.min.css"/>
<script src="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/spk/spk.global.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/spk/spk_manage.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>