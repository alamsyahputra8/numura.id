<?PHP $logo = array_shift($getSiteData); ?>

<?PHP $this->load->view('theme/polo/plugin1'); ?>

        <?PHP $this->load->view('theme/polo/topbar'); ?>

        <?PHP $this->load->view('theme/polo/header'); ?>

        <!-- Revolution Slider -->   
        <section id="slider">

            <div id="rev_slider_24_1_wrapper" class="rev_slider_wrapper fullscreen-container" data-alias="website-intro" data-source="gallery" style="background:#000000;padding:0px;">
            <!-- START REVOLUTION SLIDER 5.4.1 fullscreen mode -->
                <div id="rev_slider_24_1" class="rev_slider fullscreenbanner tiny_bullet_slider" style="display:none;" data-version="5.4.1">
                <ul>    <!-- SLIDE  -->
                <?PHP
                $qSlides    = "select * from banner order by 1 desc";
                $getSlides  = $this->query->getDatabyQ($qSlides);
                foreach ($getSlides as $dataslides) {
                ?>
                <li data-index="rs-<?PHP echo $dataslides['id_banner']; ?>" data-transition="fade" data-slotamount="default" data-hideafterloop="0" data-hideslideonmobile="off"  data-easein="default" data-easeout="default" data-masterspeed="600"  data-thumb="<?PHP echo base_url(); ?>images/slides/<?PHP echo $dataslides['thumb']; ?>"  data-rotate="0"  data-saveperformance="off"  data-title="Slide" data-param1="" data-param2="" data-param3="" data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9="" data-param10="" data-description="" data-slicey_shadow="0px 0px 0px 0px transparent">
                    <!-- MAIN IMAGE -->
                    <img src="<?PHP echo base_url(); ?>images/slides/<?PHP echo $dataslides['img']; ?>"  alt=""  data-bgposition="center center" data-kenburns="on" data-duration="5000" data-ease="Power2.easeInOut" data-scalestart="100" data-scaleend="150" data-rotatestart="0" data-rotateend="0" data-blurstart="20" data-blurend="0" data-offsetstart="0 0" data-offsetend="0 0" class="rev-slidebg" data-no-retina>
                    <!-- LAYERS -->

                    <!-- LAYER NR. 1 -->
                    <div class="tp-caption tp-shape tp-shapewrapper tp-slicey  tp-resizeme" 
                         id="slide-<?PHP echo $dataslides['id_banner']; ?>-layer-9" 
                         data-x="['center','center','center','center']" data-hoffset="['-112','-43','-81','44']" 
                         data-y="['middle','middle','middle','middle']" data-voffset="['-219','-184','-185','182']" 
                        data-width="['250','250','150','150']"
                        data-height="['150','150','100','100']"
                        data-whitespace="nowrap"
             
                        data-type="shape" 
                        data-slicey_offset="250" 
                        data-slicey_blurstart="0" 
                        data-slicey_blurend="20" 
                        data-responsive_offset="on" 

                        data-frames='[{"delay":300,"speed":1000,"frame":"0","from":"rX:0deg;rY:0deg;rZ:0deg;sX:1;sY:1;opacity:0;fb:20px;","to":"o:1;fb:0;","ease":"Power3.easeInOut"},{"delay":"+3700","speed":300,"frame":"999","to":"opacity:0;fb:0;","ease":"Power3.easeInOut"}]'
                        data-textAlign="['inherit','inherit','inherit','inherit']"
                        data-paddingtop="[0,0,0,0]"
                        data-paddingright="[0,0,0,0]"
                        data-paddingbottom="[0,0,0,0]"
                        data-paddingleft="[0,0,0,0]"

                        style="z-index: 5;background-color:rgba(0, 0, 0, 0.5);"> </div>

                    <!-- LAYER NR. 2 -->
                    <div class="tp-caption tp-shape tp-shapewrapper tp-slicey  tp-resizeme" 
                         id="slide-<?PHP echo $dataslides['id_banner']; ?>-layer-10" 
                         data-x="['center','center','center','center']" data-hoffset="['151','228','224','117']" 
                         data-y="['middle','middle','middle','middle']" data-voffset="['-212','-159','71','-222']" 
                        data-width="['150','150','100','100']"
                        data-height="['200','150','150','150']"
                        data-whitespace="nowrap"
             
                        data-type="shape" 
                        data-slicey_offset="250" 
                        data-slicey_blurstart="0" 
                        data-slicey_blurend="20" 
                        data-responsive_offset="on" 

                        data-frames='[{"delay":350,"speed":1000,"frame":"0","from":"sX:1;sY:1;opacity:0;fb:20px;","to":"o:1;fb:0;","ease":"Power3.easeInOut"},{"delay":"+3650","speed":300,"frame":"999","to":"opacity:0;fb:0;","ease":"Power3.easeInOut"}]'
                        data-textAlign="['inherit','inherit','inherit','inherit']"
                        data-paddingtop="[0,0,0,0]"
                        data-paddingright="[0,0,0,0]"
                        data-paddingbottom="[0,0,0,0]"
                        data-paddingleft="[0,0,0,0]"

                        style="z-index: 6;background-color:rgba(0, 0, 0, 0.5);"> </div>

                    <!-- LAYER NR. 3 -->
                    <div class="tp-caption tp-shape tp-shapewrapper tp-slicey  tp-resizeme" 
                         id="slide-<?PHP echo $dataslides['id_banner']; ?>-layer-29" 
                         data-x="['center','center','center','center']" data-hoffset="['339','-442','104','-159']" 
                         data-y="['middle','middle','middle','middle']" data-voffset="['2','165','-172','219']" 
                        data-width="['250','250','150','150']"
                        data-height="['150','150','100','100']"
                        data-whitespace="nowrap"
             
                        data-type="shape" 
                        data-slicey_offset="250" 
                        data-slicey_blurstart="0" 
                        data-slicey_blurend="20" 
                        data-responsive_offset="on" 

                        data-frames='[{"delay":400,"speed":1000,"frame":"0","from":"sX:1;sY:1;opacity:0;fb:20px;","to":"o:1;fb:0;","ease":"Power3.easeInOut"},{"delay":"+3600","speed":300,"frame":"999","to":"opacity:0;fb:0;","ease":"Power3.easeInOut"}]'
                        data-textAlign="['inherit','inherit','inherit','inherit']"
                        data-paddingtop="[0,0,0,0]"
                        data-paddingright="[0,0,0,0]"
                        data-paddingbottom="[0,0,0,0]"
                        data-paddingleft="[0,0,0,0]"

                        style="z-index: 7;background-color:rgba(0, 0, 0, 0.5);"> </div>

                    <!-- LAYER NR. 4 -->
                    <div class="tp-caption tp-shape tp-shapewrapper tp-slicey  tp-resizeme" 
                         id="slide-<?PHP echo $dataslides['id_banner']; ?>-layer-12" 
                         data-x="['center','center','center','center']" data-hoffset="['162','216','-239','193']" 
                         data-y="['middle','middle','middle','middle']" data-voffset="['195','245','6','146']" 
                        data-width="['250','250','100','100']"
                        data-height="150"
                        data-whitespace="nowrap"
             
                        data-type="shape" 
                        data-slicey_offset="250" 
                        data-slicey_blurstart="0" 
                        data-slicey_blurend="20" 
                        data-responsive_offset="on" 

                        data-frames='[{"delay":450,"speed":1000,"frame":"0","from":"opacity:0;fb:20px;","to":"o:1;fb:0;","ease":"Power3.easeInOut"},{"delay":"+3550","speed":300,"frame":"999","to":"opacity:0;fb:0;","ease":"Power3.easeInOut"}]'
                        data-textAlign="['inherit','inherit','inherit','inherit']"
                        data-paddingtop="[0,0,0,0]"
                        data-paddingright="[0,0,0,0]"
                        data-paddingbottom="[0,0,0,0]"
                        data-paddingleft="[0,0,0,0]"

                        style="z-index: 8;background-color:rgba(0, 0, 0, 0.5);"> </div>

                    <!-- LAYER NR. 5 -->
                    <div class="tp-caption tp-shape tp-shapewrapper tp-slicey  tp-resizeme" 
                         id="slide-<?PHP echo $dataslides['id_banner']; ?>-layer-34" 
                         data-x="['center','center','center','center']" data-hoffset="['-186','-119','273','-223']" 
                         data-y="['middle','middle','middle','middle']" data-voffset="['269','217','-121','69']" 
                        data-width="['300','300','150','150']"
                        data-height="['200','200','150','150']"
                        data-whitespace="nowrap"
             
                        data-type="shape" 
                        data-slicey_offset="250" 
                        data-slicey_blurstart="0" 
                        data-slicey_blurend="20" 
                        data-responsive_offset="on" 

                        data-frames='[{"delay":500,"speed":1000,"frame":"0","from":"sX:1;sY:1;opacity:0;fb:20px;","to":"o:1;fb:0;","ease":"Power3.easeInOut"},{"delay":"+3500","speed":300,"frame":"999","to":"opacity:0;fb:0;","ease":"Power3.easeInOut"}]'
                        data-textAlign="['inherit','inherit','inherit','inherit']"
                        data-paddingtop="[0,0,0,0]"
                        data-paddingright="[0,0,0,0]"
                        data-paddingbottom="[0,0,0,0]"
                        data-paddingleft="[0,0,0,0]"

                        style="z-index: 9;background-color:rgba(0, 0, 0, 0.5);"> </div>

                    <!-- LAYER NR. 6 -->
                    <div class="tp-caption tp-shape tp-shapewrapper tp-slicey  tp-resizeme" 
                         id="slide-<?PHP echo $dataslides['id_banner']; ?>-layer-11" 
                         data-x="['center','center','center','center']" data-hoffset="['-325','292','162','-34']" 
                         data-y="['middle','middle','middle','middle']" data-voffset="['3','55','-275','-174']" 
                        data-width="150"
                        data-height="['250','150','50','50']"
                        data-whitespace="nowrap"
             
                        data-type="shape" 
                        data-slicey_offset="250" 
                        data-slicey_blurstart="0" 
                        data-slicey_blurend="20" 
                        data-responsive_offset="on" 

                        data-frames='[{"delay":550,"speed":1000,"frame":"0","from":"opacity:0;fb:20px;","to":"o:1;fb:0;","ease":"Power3.easeInOut"},{"delay":"+3450","speed":300,"frame":"999","to":"opacity:0;fb:0;","ease":"Power3.easeInOut"}]'
                        data-textAlign="['inherit','inherit','inherit','inherit']"
                        data-paddingtop="[0,0,0,0]"
                        data-paddingright="[0,0,0,0]"
                        data-paddingbottom="[0,0,0,0]"
                        data-paddingleft="[0,0,0,0]"

                        style="z-index: 10;background-color:rgba(0, 0, 0, 0.5);"> </div>

                    <!-- LAYER NR. 7 -->
                    <div class="tp-caption tp-shape tp-shapewrapper tp-slicey  tp-resizeme" 
                         id="slide-<?PHP echo $dataslides['id_banner']; ?>-layer-27" 
                         data-x="['center','center','center','center']" data-hoffset="['-429','523','-190','-306']" 
                         data-y="['middle','middle','middle','middle']" data-voffset="['-327','173','181','480']" 
                        data-width="['250','250','150','150']"
                        data-height="['300','300','150','150']"
                        data-whitespace="nowrap"
             
                        data-type="shape" 
                        data-slicey_offset="300" 
                        data-slicey_blurstart="0" 
                        data-slicey_blurend="20" 
                        data-responsive_offset="on" 

                        data-frames='[{"delay":320,"speed":1000,"frame":"0","from":"sX:1;sY:1;opacity:0;fb:20px;","to":"o:1;fb:0;","ease":"Power3.easeInOut"},{"delay":"+3680","speed":300,"frame":"999","to":"opacity:0;fb:0;","ease":"Power3.easeInOut"}]'
                        data-textAlign="['inherit','inherit','inherit','inherit']"
                        data-paddingtop="[0,0,0,0]"
                        data-paddingright="[0,0,0,0]"
                        data-paddingbottom="[0,0,0,0]"
                        data-paddingleft="[0,0,0,0]"

                        style="z-index: 11;background-color:rgba(0, 0, 0, 0.5);"> </div>

                    <!-- LAYER NR. 8 -->
                    <div class="tp-caption tp-shape tp-shapewrapper tp-slicey  tp-resizeme" 
                         id="slide-<?PHP echo $dataslides['id_banner']; ?>-layer-28" 
                         data-x="['center','center','center','center']" data-hoffset="['422','-409','208','225']" 
                         data-y="['middle','middle','middle','middle']" data-voffset="['-245','-72','294','-14']" 
                        data-width="['300','300','150','150']"
                        data-height="['250','250','100','100']"
                        data-whitespace="nowrap"
             
                        data-type="shape" 
                        data-slicey_offset="300" 
                        data-slicey_blurstart="0" 
                        data-slicey_blurend="20" 
                        data-responsive_offset="on" 

                        data-frames='[{"delay":360,"speed":1000,"frame":"0","from":"sX:1;sY:1;opacity:0;fb:20px;","to":"o:1;fb:0;","ease":"Power3.easeInOut"},{"delay":"+3640","speed":300,"frame":"999","to":"opacity:0;fb:0;","ease":"Power3.easeInOut"}]'
                        data-textAlign="['inherit','inherit','inherit','inherit']"
                        data-paddingtop="[0,0,0,0]"
                        data-paddingright="[0,0,0,0]"
                        data-paddingbottom="[0,0,0,0]"
                        data-paddingleft="[0,0,0,0]"

                        style="z-index: 12;background-color:rgba(0, 0, 0, 0.5);"> </div>

                    <!-- LAYER NR. 9 -->
                    <div class="tp-caption tp-shape tp-shapewrapper tp-slicey  tp-resizeme" 
                         id="slide-<?PHP echo $dataslides['id_banner']; ?>-layer-30" 
                         data-x="['center','center','center','center']" data-hoffset="['549','-445','28','58']" 
                         data-y="['middle','middle','middle','middle']" data-voffset="['236','400','316','287']" 
                        data-width="['300','300','150','200']"
                        data-height="['250','250','150','50']"
                        data-whitespace="nowrap"
             
                        data-type="shape" 
                        data-slicey_offset="300" 
                        data-slicey_blurstart="0" 
                        data-slicey_blurend="20" 
                        data-responsive_offset="on" 

                        data-frames='[{"delay":400,"speed":1000,"frame":"0","from":"sX:1;sY:1;opacity:0;fb:20px;","to":"o:1;fb:0;","ease":"Power3.easeInOut"},{"delay":"+3600","speed":300,"frame":"999","to":"opacity:0;fb:0;","ease":"Power3.easeInOut"}]'
                        data-textAlign="['inherit','inherit','inherit','inherit']"
                        data-paddingtop="[0,0,0,0]"
                        data-paddingright="[0,0,0,0]"
                        data-paddingbottom="[0,0,0,0]"
                        data-paddingleft="[0,0,0,0]"

                        style="z-index: 13;background-color:rgba(0, 0, 0, 0.5);"> </div>

                    <!-- LAYER NR. 10 -->
                    <div class="tp-caption tp-shape tp-shapewrapper tp-slicey  tp-resizeme" 
                         id="slide-<?PHP echo $dataslides['id_banner']; ?>-layer-31" 
                         data-x="['center','center','center','center']" data-hoffset="['-522','492','-151','262']" 
                         data-y="['middle','middle','middle','middle']" data-voffset="['339','-180','330','-141']" 
                        data-width="['300','300','150','150']"
                        data-height="['250','250','100','100']"
                        data-whitespace="nowrap"
             
                        data-type="shape" 
                        data-slicey_offset="300" 
                        data-slicey_blurstart="0" 
                        data-slicey_blurend="20" 
                        data-responsive_offset="on" 

                        data-frames='[{"delay":440,"speed":1000,"frame":"0","from":"sX:1;sY:1;opacity:0;fb:20px;","to":"o:1;fb:0;","ease":"Power3.easeInOut"},{"delay":"+3560","speed":300,"frame":"999","to":"opacity:0;fb:0;","ease":"Power3.easeInOut"}]'
                        data-textAlign="['inherit','inherit','inherit','inherit']"
                        data-paddingtop="[0,0,0,0]"
                        data-paddingright="[0,0,0,0]"
                        data-paddingbottom="[0,0,0,0]"
                        data-paddingleft="[0,0,0,0]"

                        style="z-index: 14;background-color:rgba(0, 0, 0, 0.5);"> </div>

                    <!-- LAYER NR. 11 -->
                    <div class="tp-caption tp-shape tp-shapewrapper tp-slicey  tp-resizeme" 
                         id="slide-<?PHP echo $dataslides['id_banner']; ?>-layer-32" 
                         data-x="['center','center','center','center']" data-hoffset="['-588','-375','-253','-207']" 
                         data-y="['middle','middle','middle','middle']" data-voffset="['72','-328','-172','-111']" 
                        data-width="['300','300','150','150']"
                        data-height="['200','200','150','150']"
                        data-whitespace="nowrap"
             
                        data-type="shape" 
                        data-slicey_offset="300" 
                        data-slicey_blurstart="0" 
                        data-slicey_blurend="20" 
                        data-responsive_offset="on" 

                        data-frames='[{"delay":480,"speed":1000,"frame":"0","from":"sX:1;sY:1;opacity:0;fb:20px;","to":"o:1;fb:0;","ease":"Power3.easeInOut"},{"delay":"+3520","speed":300,"frame":"999","to":"opacity:0;fb:0;","ease":"Power3.easeInOut"}]'
                        data-textAlign="['inherit','inherit','inherit','inherit']"
                        data-paddingtop="[0,0,0,0]"
                        data-paddingright="[0,0,0,0]"
                        data-paddingbottom="[0,0,0,0]"
                        data-paddingleft="[0,0,0,0]"

                        style="z-index: 15;background-color:rgba(0, 0, 0, 0.5);"> </div>

                    <!-- LAYER NR. 12 -->
                    <div class="tp-caption tp-shape tp-shapewrapper tp-slicey  tp-resizeme" 
                         id="slide-<?PHP echo $dataslides['id_banner']; ?>-layer-33" 
                         data-x="['center','center','center','center']" data-hoffset="['-37','73','-76','-100']" 
                         data-y="['middle','middle','middle','middle']" data-voffset="['-401','-340','-293','-246']" 
                        data-width="['450','400','250','250']"
                        data-height="['100','100','50','50']"
                        data-whitespace="nowrap"
             
                        data-type="shape" 
                        data-slicey_offset="250" 
                        data-slicey_blurstart="0" 
                        data-slicey_blurend="20" 
                        data-responsive_offset="on" 

                        data-frames='[{"delay":310,"speed":1000,"frame":"0","from":"sX:1;sY:1;opacity:0;fb:20px;","to":"o:1;fb:0;","ease":"Power3.easeInOut"},{"delay":"+3690","speed":300,"frame":"999","to":"opacity:0;fb:0;","ease":"Power3.easeInOut"}]'
                        data-textAlign="['inherit','inherit','inherit','inherit']"
                        data-paddingtop="[0,0,0,0]"
                        data-paddingright="[0,0,0,0]"
                        data-paddingbottom="[0,0,0,0]"
                        data-paddingleft="[0,0,0,0]"

                        style="z-index: 16;background-color:rgba(0, 0, 0, 0.5);"> </div>

                    <!-- LAYER NR. 13 -->
                    <div class="tp-caption tp-shape tp-shapewrapper tp-slicey  tp-resizeme" 
                         id="slide-<?PHP echo $dataslides['id_banner']; ?>-layer-35" 
                         data-x="['center','center','center','center']" data-hoffset="['186','38','116','17']" 
                         data-y="['middle','middle','middle','middle']" data-voffset="['363','402','190','395']" 
                        data-width="['350','400','250','250']"
                        data-height="['100','100','50','50']"
                        data-whitespace="nowrap"
             
                        data-type="shape" 
                        data-slicey_offset="250" 
                        data-slicey_blurstart="0" 
                        data-slicey_blurend="20" 
                        data-responsive_offset="on" 

                        data-frames='[{"delay":340,"speed":1000,"frame":"0","from":"sX:1;sY:1;opacity:0;fb:20px;","to":"o:1;fb:0;","ease":"Power3.easeInOut"},{"delay":"+3660","speed":300,"frame":"999","to":"opacity:0;fb:0;","ease":"Power3.easeInOut"}]'
                        data-textAlign="['inherit','inherit','inherit','inherit']"
                        data-paddingtop="[0,0,0,0]"
                        data-paddingright="[0,0,0,0]"
                        data-paddingbottom="[0,0,0,0]"
                        data-paddingleft="[0,0,0,0]"

                        style="z-index: 17;background-color:rgba(0, 0, 0, 0.5);"> </div>

                    <!-- LAYER NR. 14 -->
                    <div class="tp-caption tp-shape tp-shapewrapper " 
                         id="slide-<?PHP echo $dataslides['id_banner']; ?>-layer-1" 
                         data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']" 
                         data-y="['middle','middle','middle','middle']" data-voffset="['0','0','0','0']" 
                        data-width="full"
                        data-height="full"
                        data-whitespace="nowrap"
             
                        data-type="shape" 
                        data-basealign="slide" 
                        data-responsive_offset="off" 
                        data-responsive="off"
                        data-frames='[{"delay":10,"speed":500,"frame":"0","from":"opacity:0;","to":"o:1;","ease":"Power4.easeOut"},{"delay":"wait","speed":500,"frame":"999","to":"opacity:0;","ease":"Power4.easeOut"}]'
                        data-textAlign="['inherit','inherit','inherit','inherit']"
                        data-paddingtop="[0,0,0,0]"
                        data-paddingright="[0,0,0,0]"
                        data-paddingbottom="[0,0,0,0]"
                        data-paddingleft="[0,0,0,0]"

                        style="z-index: 18;background-color:rgba(0, 0, 0, 0.5);"> </div>

                    <!-- LAYER NR. 15 -->
                    <div class="tp-caption   tp-resizeme" 
                         id="slide-<?PHP echo $dataslides['id_banner']; ?>-layer-2" 
                         data-x="['center','center','center','center']" data-hoffset="['1','1','0','0']" 
                         data-y="['middle','middle','middle','middle']" data-voffset="['-70','-70','-70','-70']" 
                        data-fontsize="['70','70','50','30']"
                        data-lineheight="['70','70','50','30']"
                        data-width="['none','none','481','360']"
                        data-height="none"
                        data-whitespace="['nowrap','nowrap','normal','normal']"
             
                        data-type="text" 
                        data-responsive_offset="on" 

                        data-frames='[{"delay":1000,"speed":2000,"frame":"0","from":"sX:0.9;sY:0.9;opacity:0;fb:20px;","to":"o:1;fb:0;","ease":"Power3.easeInOut"},{"delay":"wait","speed":500,"frame":"999","to":"sX:0.9;sY:0.9;opacity:0;fb:20px;","ease":"Power3.easeInOut"}]'
                        data-textAlign="['center','center','center','center']"
                        data-paddingtop="[0,0,0,0]"
                        data-paddingright="[0,0,0,0]"
                        data-paddingbottom="[0,0,0,0]"
                        data-paddingleft="[0,0,0,0]"

                        style="z-index: 19; white-space: nowrap; font-size: 90px; line-height: 90px; font-weight: 500; color: #ffffff; letter-spacing: -5px;font-family:Rubik;"><?PHP echo $dataslides['title']; ?></div>

                    <!-- LAYER NR. 16 -->
                    <div class="tp-caption   tp-resizeme" 
                         id="slide-<?PHP echo $dataslides['id_banner']; ?>-layer-3" 
                         data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']" 
                         data-y="['middle','middle','middle','middle']" data-voffset="['90','90','60','30']" 
                        data-fontsize="['25','25','25','20']"
                        data-lineheight="['35','35','35','30']"
                        //data-width="['880','880','880','760']"
                        data-height="none"
                        data-whitespace="normal"
             
                        data-type="text" 
                        data-responsive_offset="on" 

                        data-frames='[{"delay":1000,"speed":2000,"frame":"0","from":"sX:0.9;sY:0.9;opacity:0;fb:20px;","to":"o:1;fb:0;","ease":"Power3.easeInOut"},{"delay":"wait","speed":500,"frame":"999","to":"sX:0.9;sY:0.9;opacity:0;fb:20px;","ease":"Power3.easeInOut"}]'
                        data-textAlign="['center','center','center','center']"
                        data-paddingtop="[0,0,0,0]"
                        data-paddingright="[0,0,0,0]"
                        data-paddingbottom="[0,0,0,0]"
                        data-paddingleft="[0,0,0,0]"

                        style="z-index: 20; min-width: 480px; max-width: 480px; white-space: normal; font-size: 25px; line-height: 35px; font-weight: 400; color: #ffffff; letter-spacing: 0px;font-family:Rubik;"><?PHP echo $dataslides['sub']; ?></div>

                    <!-- LAYER NR. 17 -->
                    <a class="tp-caption rev-btn  tp-resizeme" 
                        data-target="#modalshowreel"
                        data-toggle="modal"
                        id="slide-67-layer-7" 
                        data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']" 
                        data-y="['middle','middle','middle','middle']" data-voffset="['200','200','160','120']" 
                        data-width="250"
                        data-height="none"
                        data-whitespace="nowrap"
             
                        data-type="button" 
                        data-actions=''
                        data-responsive_offset="on" 

                        data-frames='[{"delay":1000,"speed":2000,"frame":"0","from":"sX:0.9;sY:0.9;opacity:0;fb:20px;","to":"o:1;fb:0;","ease":"Power3.easeInOut"},{"delay":"wait","speed":500,"frame":"999","to":"sX:0.9;sY:0.9;opacity:0;fb:20px;","ease":"Power3.easeInOut"},{"frame":"hover","speed":"0","ease":"Linear.easeNone","to":"o:1;rX:0;rY:0;rZ:0;z:0;fb:0;","style":"c:rgba(255,255,255,1);bs:solid;bw:0 0 0 0;"}]'
                        data-textAlign="['center','center','center','center']"
                        data-paddingtop="[0,0,0,0]"
                        data-paddingright="[0,0,0,0]"
                        data-paddingbottom="[0,0,0,0]"
                        data-paddingleft="[0,0,0,0]"

                        style="z-index: 21; min-width: 250px; max-width: 250px; white-space: nowrap; font-size: 18px; line-height: 60px; font-weight: 700; color: rgba(255,255,255,1); letter-spacing: px;font-family:Rubik;background-color:#27aae1;border-color:rgba(0,0,0,1);border-radius:30px 30px 30px 30px;outline:none;box-shadow:none;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;cursor:pointer;text-decoration: none;"><i style="font-size: 15px;" class="fa fa-play-circle"></i> WATCH SHOWREEL</a>
                </li>
                <?PHP } ?>
            </ul>
            <div class="tp-bannertimer tp-bottom" style="height: 5px; background: rgb(87,202,133);"></div>  </div>
            </div><!-- END REVOLUTION SLIDER -->
            <script type="text/javascript">
            
                var tpj=jQuery;
                var revapi24;
                tpj(document).ready(function() {
                    if(tpj("#rev_slider_24_1").revolution == undefined){
                        revslider_showDoubleJqueryError("#rev_slider_24_1");
                    }else{
                        revapi24 = tpj("#rev_slider_24_1").show().revolution({
                            sliderType:"standard",
                            jsFileLocation:"revolution/js/",
                            sliderLayout:"fullscreen",
                            dottedOverlay:"none",
                            delay:9000,
                            navigation: {
                                keyboardNavigation:"off",
                                keyboard_direction: "horizontal",
                                mouseScrollNavigation:"off",
                                mouseScrollReverse:"default",
                                onHoverStop:"off",
                                bullets: {
                                    enable:true,
                                    hide_onmobile:false,
                                    style:"bullet-bar",
                                    hide_onleave:false,
                                    direction:"horizontal",
                                    h_align:"center",
                                    v_align:"bottom",
                                    h_offset:0,
                                    v_offset:50,
                                    space:5,
                                    tmp:''
                                }
                            },
                            responsiveLevels:[1240,1024,778,480],
                            visibilityLevels:[1240,1024,778,480],
                            gridwidth:[1240,1024,778,480],
                            gridheight:[868,768,960,720],
                            lazyType:"none",
                            shadow:0,
                            spinner:"off",
                            stopLoop:"off",
                            stopAfterLoops:-1,
                            stopAtSlide:-1,
                            shuffle:"off",
                            autoHeight:"off",
                            fullScreenAutoWidth:"off",
                            fullScreenAlignForce:"off",
                            fullScreenOffsetContainer: "",
                            fullScreenOffset: "0px",
                            hideThumbsOnMobile:"off",
                            hideSliderAtLimit:0,
                            hideCaptionAtLimit:0,
                            hideAllCaptionAtLilmit:0,
                            debugMode:false,
                            fallbacks: {
                                simplifyAll:"off",
                                nextSlideOnWindowFocus:"off",
                                disableFocusListener:false,
                            }
                        });
                    }

                    if(revapi24) revapi24.revSliderSlicey();
                }); /*ready*/
            </script>
        </section>

        <!-- end: Revolution Slider-->

        <section id="page-content">
            <div class="container">
                <div class="heading-text heading-section text-white text-center">
                    <h2>Latest Works</h2>
                </div>
                <!-- Portfolio -->
                <?PHP
                $qWork      = "
                select
                    a.*,
                    (select menu from menu_site where id_menu=a.id_menu) as menu,
                    (SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
                    WHERE xa.menu in ('Manage Photos','Manage Videos') AND xa.data = a.id_file ORDER BY xa.date_time DESC limit 1)as update_by,
                    (SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
                    WHERE xa.menu in ('Manage Photos','Manage Videos') AND xa.data = a.id_file ORDER BY xa.date_time DESC limit 1)as last_update
                from
                (
                    select * from (
                        select id_video as id_file,id_menu, id_album, video as file, '2' as type from videos 
                        union 
                        select id_photo as id_file,id_menu, id_album, picture as file, '1' as type from photos
                    ) as base
                ) as a
                where 1=1
                order by id_file desc
                limit 6
                ";
                $cekWork        = $this->query->getNumRowsbyQ($qWork)->num_rows();
                if ($cekWork>0) {
                ?>
                <div id="portfolio" class="grid-layout portfolio-3-columns" data-margin="20">
                    <?PHP
                    $x = 0;
                    $gWork      = $this->query->getDatabyQ($qWork);
                    foreach($gWork as $data) { $x++;
                        $id         = $data['id_file'];
                        $file       = $data['file'];
                        $type       = $data['type'];

                        if ($x==1) { $size = 'large-width'; } else { $size = ''; }

                        if ($type=='1') {
                            echo '
                                <div class="portfolio-item '.$size.' img-zoom ct-foto">
                                    <div class="portfolio-item-wrap">
                                        <div class="portfolio-image">
                                            <a href="#"><img src="'.base_url().'images/gallery/'.$file.'" alt=""></a>
                                        </div>
                                        <div class="portfolio-description">
                                            <a title="Sample Photo" data-lightbox="image" href="'.base_url().'images/gallery/'.$file.'">
                                                <i class="fa fa-expand"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            ';
                        } else {
                            $embed      = str_replace('https://www.youtube.com/watch?v=','https://www.youtube.com/embed/',$file);

                            echo '
                                <div class="portfolio-item '.$size.' img-zoom ct-video">
                                   <div class="portfolio-item-wrap">
                                        <div class="portfolio-image">
                                            <a href="#">
                                                <iframe width="1280" height="720" src="'.$embed.'?rel=0&amp;showinfo=0" allowfullscreen></iframe>
                                            </a>
                                        </div>
                                        <div class="portfolio-description">
                                            <a title="Video Youtube" data-lightbox="iframe" href="'.$file.'"><i class="fa fa-play"></i></a>
                                            <a href="'.$file.'" target="_blank"><i class="fa fa-link"></i></a>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            ';
                        }
                    }
                    ?>
                </div>
                <?PHP
                } else {
                    echo '
                    <div><h3 class="text-center text-white">We are sorry, No data available.</h3></div>
                    ';
                }
                ?>
                <!-- end: Portfolio -->
                <!--div>
                    <center><button type="button" class="btn btn-rounded btn-dark">ALL WORKS</button></center>
                </div-->
            </div>
        </section>
        <!-- end: Content -->

        <!-- Our Services Carousel -->
        <style>
            
        </style>
        <section class="" id="services">
            <div class="container">
                <div class="heading-text heading-section text-white text-center text-center">
                    <h2>Our Services</h2>
                </div>
                <div class="carousel arrows-visibile testimonial testimonial-single testimonial-left" data-items="1" data-autoplay="true" data-loop="true" data-autoplay-timeout="3500">
                    <?PHP
                    $qServ  = "
                            select final.*, s.title, s.picture, s.headline, (case when parentid=0 then 0 else (select menu from menu_site where id_menu=final.parent) end) parent_name from (
                                select a.*, (select count(*) from menu_site where parent=a.id_menu) as jmlsub,
                                (select parent from menu_site where id_menu=a.parent) parentid
                                from menu_site a where parent!=0 and style='services'
                            ) as final 
                            left join services s
                            on final.id_menu=s.id_menu
                            where jmlsub<1
                            order by parent,sort
                            ";
                    $gServ  = $this->query->getDatabyQ($qServ);
                    foreach($gServ as $dataserv) {
                        if ($dataserv['parent_name']=='0') { $titleserv = $dataserv['menu']; } else { $titleserv = $dataserv['parent_name'].' - '.$dataserv['menu']; }
                    ?>
                    <!-- Item -->
                    <div class="testimonial-item">
                        <div class="row">
                            <div class="col-md-6 text-white">
                                <h3><?PHP echo $titleserv; ?></h3>
                                <p><?PHP echo $dataserv['headline']; ?>...</p>
                                <a href="<?PHP echo base_url().'page/'.$dataserv['link']; ?>" class="btn btn-inverted">Read More</a>
                            </div>
                            <div class="col-md-6">
                                <img src="<?PHP echo base_url(); ?>images/content/<?PHP echo $dataserv['picture']; ?>" alt="">
                            </div>
                        </div>
                    </div>
                    <!-- end: Item-->
                    <?PHP } ?>

                </div>
            </div>
        </section>
        <!-- end: Our Services Carousel -->

        <section id="page-content">
            <div class="container">
                <div class="heading-text heading-section text-center">
                    <h2 class="text-white">OUR BLOG</h2>
                </div>

                <div class="carousel" data-items="3">
                    <?PHP
                    $qBlog      = "select a.*, (select name from user where userid=a.create_by) as createdby from blog a order by id_blog desc limit 10";
                    $getBlog    = $this->query->getDatabyQ($qBlog);
                    foreach ($getBlog as $blog) {
                    ?>
                    <!-- Post item-->
                    <div class="post-item border">
                        <div class="post-item-wrap">
                            <div class="post-image">
                                <a href="<?PHP echo base_url(); ?>blog/<?PHP echo $blog['link']; ?>">
                                    <img src="<?PHP echo base_url(); ?>images/content/<?PHP echo $blog['picture']; ?>">
                                </a>
                                <!--span class="post-meta-category"><a href="">Lifestyle</a></span-->
                            </div>
                            <div class="post-item-description">
                                <span class="post-meta-date"><i class="fa fa-calendar-o"></i><?PHP echo $this->formula->TanggalIndo($blog['create_date']); ?></span>
                                <span class="post-meta-comments"><a href=""><i class="fa fa-user"></i><?PHP echo $blog['createdby']; ?></a></span>
                                <h2><a href="<?PHP echo base_url(); ?>blog/<?PHP echo $blog['link']; ?>"><?PHP echo $blog['title']; ?></a></h2>
                                <p><?PHP echo $blog['headline']; ?></p>

                                <a href="<?PHP echo base_url(); ?>blog/<?PHP echo $blog['link']; ?>" class="item-link">Read More <i class="fa fa-arrow-right"></i></a>

                            </div>
                        </div>
                    </div>
                    <!-- end: Post item-->
                    <?PHP } ?>
                </div>
                <!--end: Post Carousel -->
            </div>
        </section>    
    
        <!-- end: Revolution Slider-->

        <!-- CLIENTS -->
        <section class="p-t-60">
            <div class="container">
                <div class="heading-text heading-section text-center">
                    <h2 class="text-white">CLIENTS</h2>
                    <span class="text-white lead">Our awesome clients we've had the pleasure to work with! </span>
                </div>
                <div class="carousel" data-items="6" data-items-sm="4" data-items-xs="3" data-items-xxs="2" data-margin="20" data-arrows="false" data-autoplay="true" data-autoplay-timeout="3000" data-loop="true">
                    <?PHP
                    $qLink      = "select * from link order by id_link desc";
                    $getLink    = $this->query->getDatabyQ($qLink);
                    foreach ($getLink as $link) {
                    ?>
                    <div>
                        <a href="<?PHP echo $link['link']; ?>" title="<?PHP echo $link['title']; ?>" target="_blank"><img alt="" src="<?PHP echo base_url(); ?>images/link/<?PHP echo $link['picture']; ?>"> </a>
                    </div>
                    <?PHP } ?>
                </div>
            </div>

        </section>
        <!-- end: CLIENTS -->

        <!-- TEAM -->
        <section class="no-border">
            <div class="container">
                <div class="heading-text heading-section text-center">
                    <h2 class="text-white">Work With Us</h2>
                    <p class="text-white">
                        <a href="<?PHP echo base_url(); ?>page/contact">Drop us a call or an E-mail to start working on your creative projects.</a>
                    </p>
                </div>
            </div>
        </section>
        <!-- end: TEAM -->

        <style>
        .headershowreel {
            background: transparent;
            padding: 10px;
            border: none;
            z-index: 2;
        }
        .bodyshowreel {
            padding: 0px;
        }
        </style>

        <div class="modal fade show" id="modalshowreel" tabindex="-1" role="modal" aria-labelledby="modal-label-3">
            <div class="modal-dialog modal-lg" style="max-width: 80%;">
                <div class="modal-content">
                    <div class="modal-body bodyshowreel">
                        <div class="row">
                            <?PHP
                            $showreel   = str_replace('https://www.youtube.com/watch?v=','https://www.youtube.com/embed/',$logo['showreel']);
                            ?>
                            <iframe width="1280" height="720" src="<?PHP echo $showreel; ?>?rel=0&amp;showinfo=0" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>

       <?PHP $this->load->view('theme/polo/footer'); ?>