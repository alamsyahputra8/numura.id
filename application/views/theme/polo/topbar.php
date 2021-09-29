<?PHP 
$getSiteData    = $this->query->getData('configsite','*',"");
$site           = array_shift($getSiteData); 

$getMailData    = $this->query->getData('mail_site','*',"WHERE status='1'");
$mail           = array_shift($getMailData); 

$activepage = $this->uri->uri_string();

//if ($activepage=='page/blog') {
if (strpos( $activepage, 'blog' ) !== false) {
    $transparent    = '';
} else {
    //$transparent    = '';
    $transparent    = 'topbar-transparent';
}
?>
<!-- Topbar -->
<div id="topbar" class="<?PHP echo $transparent; ?> topbar-fullwidth dark d-none d-xl-block d-lg-block">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <ul class="top-menu">
                    <li><a href="#">Phone: <?PHP echo $site['phone']; ?></a></li>
                    <li><a href="#">Email: <?PHP echo $mail['email']; ?></a></li>
                </ul>
            </div>
            <div class="col-md-6 d-none d-sm-block">
                <div class="social-icons social-icons-colored-hover">
                    <ul>
                        <?PHP if ($site['facebook']!=='') { ?>
                        <li class="social-facebook"><a href="<?PHP echo $site['facebook']; ?>"><i class="fab fa-facebook-f"></i></a></li>
                        <?PHP } ?>

                        <?PHP if ($site['twitter']!=='') { ?>
                        <li class="social-twitter"><a href="<?PHP echo $site['twitter']; ?>"><i class="fab fa-twitter"></i></a></li>
                        <?PHP } ?>

                        <?PHP if ($site['instagram']!=='') { ?>
                        <li class="social-instagram"><a href="<?PHP echo $site['instagram']; ?>"><i class="fab fa-instagram"></i></a></li>
                        <?PHP } ?>

                        <?PHP if ($site['youtube']!=='') { ?>
                        <li class="social-youtube"><a href="<?PHP echo $site['youtube']; ?>"><i class="fab fa-youtube"></i></a></li>
                        <?PHP } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end: Topbar -->