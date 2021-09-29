					<!-- end:: Content -->
					</div>

					<!-- begin:: Footer -->
					<?PHP $this->load->view('/theme/metronic/footerpage'); ?>
					<!-- end:: Footer -->
				</div>
			</div>
		</div>

		<!-- end:: Page -->
<?PHP
$urlmenuact	= $this->uri->uri_string();

if (strpos($urlmenuact, "docdetail")!== false) {
	echo '';
} else if (strpos($urlmenuact, "docanswerdetail")!== false) {
	echo '';
} else if (strpos($urlmenuact, "detailkontak")!== false) {
	echo '';
} else if (strpos($urlmenuact, "detailpengiriman")!== false) {
	echo '';
} else {
	echo "
	<script>
	var aksesUpdate = '".getRoleUpdate($akses,'update',$userid)."';
	var aksesDelete = '".getRoleDelete($akses,'delete',$userid)."';
	</script>
	";
}
?>