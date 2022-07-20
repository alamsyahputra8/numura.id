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
echo "
<script>
var aksesUpdate = '".getRoleUpdate($akses,'update',$userid)."';
var aksesDelete = '".getRoleDelete($akses,'delete',$userid)."';
</script>
";
?>