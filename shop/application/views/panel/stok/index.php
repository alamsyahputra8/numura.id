<?PHP
$userdata 	= $this->session->userdata('sesspwt'); 
$userid 	= $userdata['userid'];
$role 		= $userdata['id_role'];

$activepage	= $this->uri->uri_string();
$q 			= "select title_page,icon from menu where url='$activepage'";
$getMenu 	= $this->query->getDatabyQ($q);
$dataMenu	= array_shift($getMenu);

$getUser 	= $this->db->query("
			SELECT * from user order by name
			")->result_array();
$getType 	= $this->db->query("
			SELECT * from payment_type order by 1
			")->result_array();

$getColor 	= $this->db->query("
			SELECT * from color where type=1 order by 1
			")->result_array();

$getColor2 	= $this->db->query("
			SELECT * from color where type!=1 order by 1
			")->result_array();

$getSize 	= $this->db->query("
			SELECT * from size order by sort
			")->result_array();

$getSup 	= $this->db->query("
			SELECT * from suplier order by is_default desc
			")->result_array();
?>
<!-- <script src="<?PHP echo base_url(); ?>assets/zxcvbn.js"></script> -->
<style>
.modal-open .modal { overflow: scroll!important; }
.dataTables_wrapper table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > td:first-child:before {
	top: 25px!important;
}
table.table-bordered.dataTable tbody td {
	vertical-align: top!important;
	font-size: 11px!important;
}
table.table-bordered.dataTable tbody th {
	vertical-align: top!important;
	font-size: 12px!important;
}
meter {
    /* Reset the default appearance */
    -webkit-appearance: none;
       -moz-appearance: none;
            appearance: none;
            
    margin: 0 auto 1em;
    width: 100%;
    height: .5em;
    
    /* Applicable only to Firefox */
    background: none;
    background-color: rgba(0,0,0,0.1);
}

meter::-webkit-meter-bar {
    background: none;
    background-color: rgba(0,0,0,0.1);
}

meter[value="1"]::-webkit-meter-optimum-value { background: red; }
meter[value="2"]::-webkit-meter-optimum-value { background: yellow; }
meter[value="3"]::-webkit-meter-optimum-value { background: orange; }
meter[value="4"]::-webkit-meter-optimum-value { background: green; }

meter[value="1"]::-moz-meter-bar { background: red; }
meter[value="2"]::-moz-meter-bar { background: yellow; }
meter[value="3"]::-moz-meter-bar { background: orange; }
meter[value="4"]::-moz-meter-bar { background: green; }

.feedback {
    color: #9ab;
    font-size: 90%;
    padding: 0 .25em;
    padding-left: 0em;
    margin-top: 1em;
}
/*#availstock table {
   border-collapse: collapse;
   overflow: hidden;
}
#availstock td, th {
   padding: 10px;
   position: relative;
}
#availstock tr:hover{
   background-color: rgba(247, 247, 247, 0.5);
}

#availstock td:hover::after,#availstock th:hover::after { 
   background-color: rgba(247, 247, 247, 0.5);
   content: '\00a0';  
   height: 10000px;    
   left: 0;
   position: absolute;  
   top: -5000px;
   width: 100%; 
}*/
</style>
<!-- begin:: Content -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
	<div id="gagalinsert" class="alert alert-warning alert-elevate kt-hidden" role="alert">
		<div class="alert-icon"><i class="flaticon-warning"></i></div>
		<div class="alert-text">
			<strong>Gagal!</strong> silahkan periksa kembali data Anda. Hubungi Admin jika butuh bantuan lebih lanjut.
		</div>
		<div class="alert-close">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true"><i class="la la-close"></i></span>
			</button>
		</div>
	</div>

	<div id="suksesinsert" class="alert alert-success fade show kt-hidden" role="alert">
		<div class="alert-icon"><i class="flaticon-black"></i></div>
		<div class="alert-text">Berhasil!</div>
		<div class="alert-close">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true"><i class="la la-close"></i></span>
			</button>
		</div>
	</div>

	<div id="suksesdelete" class="alert alert-secondary fade show kt-hidden" role="alert">
		<div class="alert-icon"><i class="flaticon-questions-circular-button"></i></div>
		<div class="alert-text">Data berhasil dihapus!</div>
		<div class="alert-close">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true"><i class="la la-close"></i></span>
			</button>
		</div>
	</div>

	<div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__head kt-portlet__head--lg">
			<div class="kt-portlet__head-label">
				<span class="kt-portlet__head-icon">
					<?PHP echo $dataMenu['icon']; ?>
				</span>
				<h3 class="kt-portlet__head-title">
					<?PHP echo $dataMenu['title_page']; ?>
				</h3>
			</div>
			<div class="kt-portlet__head-toolbar">
				<div class="kt-portlet__head-wrapper">
					<div class="kt-portlet__head-actions text-right">
						<!--a href="#" class="btn btn-default btn-icon-sm">
							<i class="la la-download"></i> Export
						</a-->
						&nbsp;
						<?PHP 
						// echo getBtnAction($akses,'print','Print Pesanan','print','btn-default btnPrint','la la-print',$userid);

						$cekprint 		= getRoleAction($akses,'print','print',$userid); 
						if ($cekprint=='ada') {
						?>
						<div class="dropdown dropdown-inline">
							<button type="button" class="btn btn-default btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="la la-print"></i> Print
							</button>
							<div class="dropdown-menu dropdown-menu-right" style="width: 25rem;">
								<ul class="kt-nav">
									<li class="kt-nav__section kt-nav__section--first">
										<span class="kt-nav__section-text">Print Pesanan</span>
									</li>
									<li class="kt-nav__item" data-toggle="kt-tooltip" title="" data-html="true">
										<a href="<?PHP echo base_url(); ?>printpesanan/pending" class="kt-nav__link" id="" target="_blank">
											<i class="kt-nav__link-icon flaticon-clock-1"></i>
											<span class="kt-nav__link-text">Pesanan Perlu Diproses</span>
										</a>
									</li>
									<li class="kt-nav__item" data-toggle="kt-tooltip" title="" data-html="true">
										<a href="<?PHP echo base_url(); ?>printpesanan/proses" class="kt-nav__link" id="" target="_blank">
											<i class="kt-nav__link-icon flaticon2-checkmark"></i>
											<span class="kt-nav__link-text">Pesanan Sedang Diproses</span>
										</a>
									</li>
									<li class="kt-nav__item" data-toggle="kt-tooltip" title="" data-html="true">
										<a href="<?PHP echo base_url(); ?>printpesanan/send" class="kt-nav__link" id="" target="_blank">
											<i class="kt-nav__link-icon flaticon-paper-plane"></i>
											<span class="kt-nav__link-text">Pesanan Dikirim</span>
										</a>
									</li>
									<li class="kt-nav__section kt-nav__section--first">
										<span class="kt-nav__section-text">Print Item</span>
									</li>
									<li class="kt-nav__item" data-toggle="kt-tooltip" title="" data-html="true">
										<a href="<?PHP echo base_url(); ?>printcard/proses" class="kt-nav__link" id="" target="_blank">
											<i class="kt-nav__link-icon flaticon2-browser"></i>
											<span class="kt-nav__link-text">Thankyou Card</span>
										</a>
									</li>
								</ul>
							</div>
						</div>
						<?PHP } ?>

						<?PHP echo getRoleInsert($akses,'addnewfac','Buat Pesanan Stok');?>
					</div>
				</div>
			</div>
		</div>

		<!-- MODAL INSERT -->
		<div class="modal fade" id="addnewfac" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<!--div class="modal-header">
						<h5 class="modal-title" id="exampleModalLongTitle"></h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						</button>
					</div-->

					<form class="kt-form kt-form--label-right" id="forminsert" enctype="multipart/form-data" method="POST">
						<div class="modal-body kt-portlet kt-portlet--tabs" style="margin-bottom: 0px;">
							<div class="kt-portlet__head">
								<div class="kt-portlet__head-label">
									<h3 class="kt-portlet__head-title">Buat Pesanan Stok</h3>
								</div>
								<div class="kt-portlet__head-toolbar">
								</div>
							</div>
							<div class="kt-portlet__body">
								<div class="form-group row">
									<label for="role" class="col-lg-2 col-sm-12 col-form-label">Suplier</label>
									<div class="col-lg-10 col-sm-12">
										<div class='input-group'>
											<select name="suplier" class="form-control kt_select2norm" id="suplier" placeholder="Pilih Suplier" style="width: 100%;">
												<?PHP foreach ($getSup as $sup) { ?>
												<option value="<?PHP echo $sup['id']; ?>"><?PHP echo $sup['nama_suplier']; ?></option>
												<?PHP } ?>
											</select>
										</div>
									</div>
								</div>

								<div class="form-group row">
									<label for="role" class="col-lg-2 col-sm-12 col-form-label">Tgl. Transaksi</label>
									<div class="col-lg-10 col-sm-12">
										<div class='input-group'>
											<input type="text" name="tgl" class="form-control dp" id="tgl" placeholder="Tgl. Transaksi">
										</div>
									</div>
								</div>

								<div class="form-group row">
									<label for="role" class="col-lg-2 col-sm-12 col-form-label">Keterangan</label>
									<div class="col-lg-10 col-sm-12">
										<div class='input-group'>
											<div class='input-group'>
												<input type="text" name="label" class="form-control" id="label" placeholder="Keterangan">
											</div>
										</div>
									</div>
								</div>
								<!-- <div class="kt-separator kt-separator--space-sm kt-separator--border-dashed"></div> -->

								<div id="bgdetstokins">
									<table class="table table-striped- table-bordered table-hover table-sm">
										<thead>
											<tr>
												<th style="width: 150px!important;">WARNA</th>
												<?PHP foreach($getSize as $size) { ?>
												<th style="max-width: 50px!important;" class="text-center"><?PHP echo $size['label']; ?></th>
												<?PHP } ?>
											</tr>
										</thead>
										<tbody>
											<?PHP
											foreach ($getColor as $color) {
												$gDefPS = array_shift($getSup);
												$defids = $gDefPS['id'];

												$colid 	= $color['id'];
											?>
												<tr>
													<td><?PHP echo $color['label']; ?></td>
													<?PHP
													foreach($getSize as $size) {
														$sizeid 	= $size['id_size']; 
														if (($size['id_size']!=5 and $size['id_size']!=6 and $size['id_size']!=7) and ($color['id']==1 or $color['id']==2)) {
															$value 	= '1';
														} else if (($size['id_size']!=5 and $size['id_size']!=6 and $size['id_size']!=7) and ($color['id']!=1 and $color['id']!=2)) {
															$value 	= '0';
														} else if (($size['id_size']==5 or $size['id_size']==6 or $size['id_size']==7) and ($color['id']==1 or $color['id']==2)) {
															$value 	= '1';
														} else {
															$value 	= '0';
														}

														$getPrice 	= $this->db->query("
																	SELECT * from suplier_harga where id_suplier='$defids' and id_size='$sizeid'
																	")->result_array();
														$sprice 	= array_shift($getPrice);
														// $prc 	= $size['hpp'];
														$prc 		= $sprice['harga'];
														@$tjml 		+= $value;
														@$tprc 		+= $value*$prc;
													?>
													<td class="text-center">
														<input type="number" name="pcscol<?PHP echo $colid; ?>siz<?PHP echo $sizeid; ?>" class="form-control pcsval" id="pcs" placeholder="0" style="width: 100%;" value="<?PHP echo $value; ?>">
													</td>
													<?PHP } ?>
												</tr>
											<?PHP } ?>
										</tbody>
									</table>
								</div>

								<div class="form-group row">
									<label for="role" class="col-lg-2 col-sm-12 col-form-label">Total Barang</label>
									<div class="col-lg-10 col-sm-12">
										<div class='input-group'>
											<div class='input-group'>
												<input type="number" name="totalpcs" class="form-control" id="totalpcs" placeholder="0" readonly value="<?PHP echo $tjml; ?>">
											</div>
										</div>
									</div>
								</div>

								<div class="form-group row">
									<label for="role" class="col-lg-2 col-sm-12 col-form-label">Total Pembayaran</label>
									<div class="col-lg-10 col-sm-12">
										<div class='input-group'>
											<div class='input-group'>
												<input type="number" name="total" class="form-control" id="total" placeholder="0" readonly value="<?PHP echo $tprc; ?>">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
							<!-- <button type="submit" id="saveandcreate" class="btn btn-primary">Simpan & Buat Pembayaran Baru</button> -->
							<button type="submit" id="saveinsert" class="btn btn-success">Simpan & Selesai</button>
						</div>
					</form>

				</div>
			</div>
		</div>
		<!-- END MODAL INSERT -->

		<div class="kt-portlet__body">
			<!-- <div>
				Perhatian! Setelah membuat pesanan, mohon buat juga data pengiriman pada menu Pengiriman, atau bisa klik 
				<b><a href="<?PHP echo base_url(); ?>pengiriman">disini</a></b>.
			</div>
			<div class="kt-separator kt-separator--space-lg kt-separator--border-dashed"></div> -->
			<div class="tab-content">
				<ul class="nav nav-tabs  nav-tabs-line nav-tabs-line-primary" role="tablist" style="margin-top: -15px!important;">
					<li class="nav-item">
						<a class="nav-link active" data-toggle="tab" href="#dataorder" role="tab">
							<i class="flaticon-time-1"></i> Order Stock
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#availstock" role="tab">
							<i class="fas fa-tshirt"></i> Available Stock 
						</a>
					</li>
				</ul>
				<div class="tab-pane active" id="dataorder" role="tabpanel">
					<!--begin: Datatable -->
					<table class="table table-striped- table-bordered table-hover table-checkable" id="tabledata">
						<thead>
							<tr>
								<th>KETERANGAN</th>
								<th>SUPLIER</th>
								<th>JUMLAH</th>
								<th>TOTAL</th>
								<th>BAYAR</th>
								<th>TANGGAL BELI</th>
								<th>STATUS</th>
								<th>ACTIONS</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>KETERANGAN</th>
								<th>SUPLIER</th>
								<th>JUMLAH</th>
								<th>TOTAL</th>
								<th>BAYAR</th>
								<th>TANGGAL BELI</th>
								<th>STATUS</th>
								<th>ACTIONS</th>
							</tr>
						</tfoot>
					</table>
					<!--end: Datatable -->
				</div>

				<div class="tab-pane" id="availstock" role="tabpanel">
					<div class="col-12"><h3><i class="fas fa-tshirt"></i> Stok Warna Utama</h3></div><br>
					<!--begin: Datatable -->
					<table class="table table-striped- table-bordered table-hover table-checkable">
						<thead>
							<tr>
								<th style="width: 150px!important;">UKURAN</th>
								<?PHP foreach($getColor as $color) { ?>
								<th style="max-width: 50px!important;" class="text-center"><i class="fa fa-circle" style="color: <?PHP echo $color['code_color']; ?>;"></i> <?PHP echo $color['label']; ?></th>
								<?PHP } ?>
							</tr>
						</thead>
						<tbody>
							<?PHP
							foreach ($getSize as $size) {
								$sizeid = $size['id_size'];
							?>
								<tr>
									<td><?PHP echo $size['label']; ?></td>
									<?PHP
									foreach($getColor as $color) {
										$colid 		= $color['id']; 
										
										$getJml 	= $this->db->query("
													SELECT sum(jml_order) jml_order FROM stok_order_detail a left join stok_order b
													on a.id_order=b.id_order
													where a.size='$sizeid' and a.color='$colid' and a.type='1' and b.is_finish=1
													")->result_array();
										$dJml 		= array_shift($getJml);
										$jml 		= $this->formula->rupiah3($dJml['jml_order']);

										$qJml 		= "
													SELECT * FROM pesanan where status not in (3) and ukuran='$sizeid' 
													and warna='$colid' and kaos_type='1'
													";
										$cekJml 	= $this->db->query($qJml)->num_rows();

										$qSend 		= "
													SELECT * from pesanan where kaos_type='1' and id_pesanan in (
														select id_pesanan from pengiriman_detail where id_pengiriman in (
													    	select id_pengiriman from pengiriman where id_pengiriman in (
													            select data from data_log where menu='pengiriman' and activity='send' and date_time>='2020-10-25'
													        )
													    )
													) and ukuran='$sizeid' and warna='$colid'
													";
										$cekSend 	= $this->db->query($qSend)->num_rows();

										$sisastokfin = ($jml-$cekJml)-$cekSend;
										if ($sisastokfin<1) {
											$colte	= 'style="color: #bdbcbc;"';	
										} else if ($sisastokfin>0 and $sisastokfin<5) {
											$colte	= 'style="color: #cf5555;"';	
										} else {
											$colte	= '';
										}
									?>
									<td class="text-center" <?PHP echo $colte; ?>>
										<b><?PHP echo $sisastokfin; ?></b> pcs
									</td>
									<?PHP } ?>
								</tr>
							<?PHP } ?>
						</tbody>
					</table>
					<!--end: Datatable -->
					<div class="kt-separator kt-separator--space-sm kt-separator--border-dashed"></div>

					<div class="col-12"><h3><i class="fas fa-tshirt"></i> Stok Warna Lama</h3></div><br>
					<!--begin: Datatable -->
					<table class="table table-striped- table-bordered table-hover table-checkable">
						<thead>
							<tr>
								<th style="width: 150px!important;">UKURAN</th>
								<?PHP foreach($getColor2 as $color) { ?>
								<th style="max-width: 50px!important;" class="text-center"><i class="fa fa-circle" style="color: <?PHP echo $color['code_color']; ?>;"></i> <?PHP echo $color['label']; ?></th>
								<?PHP } ?>
							</tr>
						</thead>
						<tbody>
							<?PHP
							foreach ($getSize as $size) {
								$sizeid = $size['id_size'];
							?>
								<tr>
									<th><?PHP echo $size['label']; ?></th>
									<?PHP
									foreach($getColor2 as $color) {
										$colid 		= $color['id']; 
										
										$getJml 	= $this->db->query("
													SELECT sum(jml_order) jml_order FROM stok_order_detail a left join stok_order b
													on a.id_order=b.id_order
													where a.size='$sizeid' and a.color='$colid' and a.type='1' and b.is_finish=1
													")->result_array();
										$dJml 		= array_shift($getJml);
										$jml 		= $this->formula->rupiah3($dJml['jml_order']);

										$qJml 		= "
													SELECT * FROM pesanan where status not in (3) and ukuran='$sizeid' 
													and warna='$colid' and kaos_type='1'
													";
										$cekJml 	= $this->db->query($qJml)->num_rows();

										$qSend 		= "
													SELECT * from pesanan where kaos_type='1' and id_pesanan in (
														select id_pesanan from pengiriman_detail where id_pengiriman in (
													    	select id_pengiriman from pengiriman where id_pengiriman in (
													            select data from data_log where menu='pengiriman' and activity='send' and date_time>='2020-10-25'
													        )
													    )
													) and ukuran='$sizeid' and warna='$colid'
													";
										$cekSend 	= $this->db->query($qSend)->num_rows();

										$sisastokfin = ($jml-$cekJml)-$cekSend;
										if ($sisastokfin<1) {
											$colte	= 'style="color: #bdbcbc;"';	
										} else if ($sisastokfin>0 and $sisastokfin<5) {
											$colte	= 'style="color: #cf5555;"';
										} else {
											$colte	= '';
										}
									?>
									<td class="text-center" <?PHP echo $colte; ?>>
										<b><?PHP echo $sisastokfin; ?></b> pcs
									</td>
									<?PHP } ?>
								</tr>
							<?PHP } ?>
						</tbody>
					</table>
					<!--end: Datatable -->
					<div class="kt-separator kt-separator--space-sm kt-separator--border-dashed"></div>
				</div>
			</div>

			<!-- MODAL UPDATE -->
			<div class="modal fade" id="update" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<!--div class="modal-header">
							<h5 class="modal-title" id="exampleModalLongTitle">Update Data : <b id="nameroles"></b></h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							</button>
						</div-->

						<form class="kt-form kt-form--label-left" id="formupdate" enctype="multipart/form-data" method="POST">
							<div class="modal-body kt-portlet kt-portlet--tabs" style="margin-bottom: 0px;">
								<div class="kt-portlet__head">
									<div class="kt-portlet__head-label">
										<h3 class="kt-portlet__head-title">Update Data : <b id="namedata"></b></h3>
									</div>
									<div class="kt-portlet__head-toolbar">
									</div>
								</div>
								<div class="kt-portlet__body">

									<div class="form-group row">
										<label for="role" class="col-lg-2 col-sm-12 col-form-label">Suplier</label>
										<div class="col-lg-10 col-sm-12">
											<div class='input-group'>
												<input type="hidden" name="ed_suplier" id="ed_suplier">
												<select name="ed_supliersel" class="form-control" id="ed_supliersel" placeholder="Pilih Suplier" required disabled>
													<?PHP 
													$getSupX 	= $this->db->query("
																SELECT * from suplier order by is_default desc
																")->result_array();
													foreach ($getSupX as $sup) { 
													?>
													<option value="<?PHP echo $sup['id']; ?>"><?PHP echo $sup['nama_suplier']; ?></option>
													<?PHP } ?>
												</select>
											</div>
										</div>
									</div>

									<div class="form-group row">
										<label for="role" class="col-lg-2 col-sm-12 col-form-label">Tgl. Transaksi</label>
										<div class="col-lg-10 col-sm-12">
											<div class='input-group'>
												<input type="hidden" name="ed_id" id="ed_id">
												<input type="text" name="ed_tgl" class="form-control dp" id="ed_tgl" placeholder="Tgl. Transaksi">
											</div>
										</div>
									</div>

									<div class="form-group row">
										<label for="role" class="col-lg-2 col-sm-12 col-form-label">Keterangan</label>
										<div class="col-lg-10 col-sm-12">
											<div class='input-group'>
												<div class='input-group'>
													<input type="text" name="ed_label" class="form-control" id="ed_label" placeholder="Keterangan">
												</div>
											</div>
										</div>
									</div>
									<!-- <div class="kt-separator kt-separator--space-sm kt-separator--border-dashed"></div> -->
									
									<div id="bgdetailstok"></div>

									<div class="form-group row">
										<label for="role" class="col-lg-2 col-sm-12 col-form-label">Total Barang</label>
										<div class="col-lg-10 col-sm-12">
											<div class='input-group'>
												<div class='input-group'>
													<input type="number" name="ed_totalpcs" class="form-control" id="ed_totalpcs" placeholder="0" readonly>
												</div>
											</div>
										</div>
									</div>

									<div class="form-group row">
										<label for="role" class="col-lg-2 col-sm-12 col-form-label">Total Pembayaran</label>
										<div class="col-lg-10 col-sm-12">
											<div class='input-group'>
												<div class='input-group'>
													<input type="number" name="ed_total" class="form-control" id="ed_total" placeholder="0" readonly>
												</div>
											</div>
										</div>
									</div>

								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								<button type="submit" id="saveupdate" class="btn btn-primary">Save</button>
							</div>
						</form>

					</div>
				</div>
			</div>
			<!-- END MODAL UPDATE -->

			<!-- MODAL DETAIL -->
			<div class="modal fade" id="detailmod" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						
						<form class="kt-form kt-form--label-left" id="formdetail" enctype="multipart/form-data" method="POST">
							<div class="modal-body kt-portlet kt-portlet--tabs" style="margin-bottom: 0px;">
								<div class="kt-portlet__head">
									<div class="kt-portlet__head-label">
										<h3 class="kt-portlet__head-title">Detail Data : <b id="namedatad"></b></h3>
									</div>
									<div class="kt-portlet__head-toolbar">
									</div>
								</div>
								<div class="kt-portlet__body">
									<div id="bgdetailstokdet"></div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							</div>
						</form>

					</div>
				</div>
			</div>
			<!-- END MODAL DETAIL -->

			<!-- MODAL DELETE -->
			<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-sm" role="document">
					<div class="modal-content">
						<div aria-labelledby="swal2-title" aria-describedby="swal2-content" class="swal2-popup swal2-modal swal2-show" style="display: flex;">
							<div class="swal2-header">
								<div class="swal2-icon swal2-warning swal2-animate-warning-icon" style="display: flex;"></div>
								<h2 class="swal2-title" id="swal2-title" style="display: flex;">Are you sure?</h2>
							</div>
							<div class="swal2-content">
								<div id="swal2-content" style="display: block;">You won't be able to revert this!</div>
							</div>
							<div class="swal2-actions" style="display: flex;">
								<form method="POST">
								<input type="hidden" name="iddel" id="iddel" value="">
								<center>
								<button type="button" id="deleteBtn" class="swal2-confirm swal2-styled" aria-label="" style="border-left-color: rgb(48, 133, 214); border-right-color: rgb(48, 133, 214);">
									Yes, delete it!
								</button>
								<button type="button" class="swal2-cancel swal2-styled" data-dismiss="modal">Cancel</button>
								</center>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- END MODAL DELETE -->

			<!-- MODAL PAYMENT -->
			<div class="modal fade" id="bayar" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-sm" role="document">
					<div class="modal-content">
						<div aria-labelledby="swal2-title" aria-describedby="swal2-content" class="swal2-popup swal2-modal swal2-show" style="display: flex;">
							<div class="swal2-header">
								<div class="swal2-icon swal2-warning swal2-animate-warning-icon" style="display: flex;"></div>
								<h2 class="swal2-title" id="swal2-title" style="display: flex;">Are you sure?</h2>
							</div>
							<div class="swal2-content">
								<div id="swal2-content" style="display: block;">You won't be able to revert this!</div>
							</div>
							<div class="swal2-actions" style="display: flex;">
								<form id="formbayar" method="POST">
								<input type="hidden" name="idorder" id="idorder" value="">
								<input type="text" name="tglbayar" class="form-control dp" id="tglbayar" placeholder="Tgl. Bayar">
								<input type="number" class="form-control" name="jmlbayar" id="jmlbayar" placeholder="Jumlah Bayar">
								<center>
								<button type="submit" id="paymentBtn" class="swal2-confirm swal2-styled" aria-label="" style="border-left-color: rgb(48, 133, 214); border-right-color: rgb(48, 133, 214); float:left;">
									Bayar
								</button>
								<button type="button" class="swal2-cancel swal2-styled" data-dismiss="modal">Cancel</button>
								</center>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- END MODAL PAYMENT -->

			<!-- MODAL FINISH -->
			<div class="modal fade" id="finish" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-sm" role="document">
					<div class="modal-content">
						<div aria-labelledby="swal2-title" aria-describedby="swal2-content" class="swal2-popup swal2-modal swal2-show" style="display: flex;">
							<div class="swal2-header">
								<div class="swal2-icon swal2-warning swal2-animate-warning-icon" style="display: flex;"></div>
								<h2 class="swal2-title" id="swal2-title" style="display: flex;">Are you sure?</h2>
							</div>
							<div class="swal2-content">
								<div id="swal2-content" style="display: block;">You won't be able to revert this!</div>
							</div>
							<div class="swal2-actions" style="display: flex;">
								<form id="formfinish" method="POST">
								<input type="hidden" name="idorderf" id="idorderf" value="">
								<center>
								<button type="submit" id="finishBtn" class="swal2-confirm swal2-styled" aria-label="" style="border-left-color: rgb(48, 133, 214); border-right-color: rgb(48, 133, 214);">
									Selesai
								</button>
								<button type="button" class="swal2-cancel swal2-styled" data-dismiss="modal">Cancel</button>
								</center>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- END MODAL FINISH -->

			<!-- MODAL PRINT -->
			<div class="modal fade" id="print" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-sm" role="document">
					<div class="modal-content">
						<div aria-labelledby="swal2-title" aria-describedby="swal2-content" class="swal2-popup swal2-modal swal2-show" style="display: flex;">
							<div class="swal2-header">
								<div class="swal2-icon swal2-warning swal2-animate-warning-icon" style="display: flex;"></div>
								<h2 class="swal2-title" id="swal2-title" style="display: flex;">Print data Pesanan?</h2>
							</div>
							<div class="swal2-content">
								<!-- <div id="swal2-content" style="display: block;">You won't be able to revert this!</div> -->
							</div>
							<div class="swal2-actions" style="display: flex;">
								<center>
								<a href="<?PHP echo base_url(); ?>printpesanan/pending" target="_blank" type="button" id="printfile" class="swal2-confirm swal2-styled" aria-label="" style="border-left-color: rgb(48, 133, 214); border-right-color: rgb(48, 133, 214);">
									Ya, print pesanan!
								</a>
								<button type="button" class="swal2-cancel swal2-styled" data-dismiss="modal">Cancel</button>
								</center>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- END MODAL PRINT -->

			<!-- MODAL APPROVE -->
			<div class="modal fade" id="proses" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-sm" role="document">
					<div class="modal-content">
						<div aria-labelledby="swal2-title" aria-describedby="swal2-content" class="swal2-popup swal2-modal swal2-show" style="display: flex;">
							<div class="swal2-header">
								<div class="swal2-icon swal2-warning swal2-animate-warning-icon" style="display: flex;"></div>
								<h2 class="swal2-title" id="swal2-title" style="display: flex;">Proses pesanan sekarang?</h2>
							</div>
							<div class="swal2-content">
								<div id="swal2-content" style="display: block;">You won't be able to revert this!</div>
							</div>
							<div class="swal2-actions" style="display: flex;">
								<form method="POST">
								<input type="hidden" name="idapp" id="idapp" value="">
								<center>
								<button type="button" id="prosesBtn" class="swal2-confirm swal2-styled" aria-label="" style="border-left-color: rgb(48, 133, 214); border-right-color: rgb(48, 133, 214);">
									Ya, proses sekarang!
								</button>
								<button type="button" class="swal2-cancel swal2-styled" data-dismiss="modal">Tidak</button>
								</center>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- END MODAL APPROVE -->
		</div>
	</div>
</div>
<!-- end:: Content -->
<script>