<style>
.video-opt-2 .caption_gallery_img {
	padding: 10px;
	position: relative;
	top: -10px;
	margin-bottom: 15px;
}
.video-opt-2 iframe{
	border:0px;
	outline: 0px;
}
div#tabbib ol li {
text-align:left;
}
div#tabMahavir2 {
text-align:left;
}
.folio-image a {
    background: #ccc;
    display: flex;
    border: 1px solid #eee;
    position: static;
}

#artlisttable .folio-image {
    height: 250px;
    position: relative;
}

.img_cntnt_wrap {
    margin-bottom: 15px;
}

.folio-image p {
    font-size: 12px;
}

.flip-box-front .img-responsive {
    max-width: 100%;
    max-height: 250px;
    width: auto;
    margin: 0 auto;
}
.scrtabs-tabs-fixed-container ul.nav-tabs > li h4 {
font-size:1.3rem;
}

.scrtabs-tabs-fixed-container ul.nav-tabs {
    display: flex;
}
.scrtabs-tab-container {
    display: flex;
}
</style>
<?php //include('header.php'); ?>

<link href="<?php echo CSS_PATH; ?>crd-src.css" rel="stylesheet" type="text/css">
<?php  if($awardeedetails[0]->topbanner!='') { ?>
<header class="bg-primary text-white inner_page_head" style="background-image: url(<?php echo DOCS_PATH; ?><?php echo $awardeedetails[0]->topbanner; ?>)">
    <div class="container text-center">

    </div>
</header>
<?php } else { ?>
<header class="bg-primary text-white inner_page_head" >
    <div class="container text-center">

    </div>
</header>
<?php } ?>
<section class="breadcrum">
    <div class="container-fluid">
        <div class="row">

            <div class="col-sm-12">
                <ul>
                    <li><a href="<?php echo BASE_PATH; ?>"><?php echo $this->lang->line('home'); ?></a></li>
                    <li><a class=" " href="<?php echo BASE_PATH; ?>awards"> <?php echo $this->lang->line('awardees'); ?> </a></li>
                    <li><a class="active" href="#"> <?php echo $awardeedetails[0]->title; ?> </a></li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="indivisual_awardees_sec">
    <div class=" ">
        <div class="col-md-4">
            <div class="card card-icon lift lift-sm mb-4">
                <div class="row no-gutters">
                    <div class="col-auto card-icon-aside bg-primary">
                    <?php
                     
                 $fpath = './assets/uploads/'. 'styles/awardee_img/public/sites/default/files/' . $awardeedetails[0]->filename;
                    if (file_exists($fpath)=='') {
                        $filename = "image_notyetavailable.jpg";
                    } elseif ($awardeedetails[0]->filename=='') {
                    $filename = "image_notyetavailable.jpg";
                    } else {
                        $filename = $awardeedetails[0]->filename;
                    }
                    ?>
                        <img class="img-account-profile mb-2" src="<?php echo DOCS_PATH; ?>styles/awardee_img/public/sites/default/files/<?php echo $filename; ?>" alt="">
                    </div>
                    <div class="col">
                        <div class="card_body_custum py-5">
                            <h5 class="card-title text-primary mb-2"><?php echo $awardeedetails[0]->a_rank; ?> <?php echo $awardeedetails[0]->title; ?></h5>
                            <p class="card-text mb-1"><?php 
							echo ($awardeedetails[0]->chakra_2)?$awardeedetails[0]->chakra_2.', ':'';
							echo $awardeedetails[0]->chakra_title; ?></p>
                            <!--<a href="<?php echo BASE_PATH;?>tribute" class="btn btn-primary" data-toggle="modal" data-target="#give_tribute_modal" 
							> Give Tribute </a>-->
                            <!-- <div class="small text-muted">5 articles in this category</div> -->

                        </div>
                    </div>
                </div>
            </div>

        </div>
    
             
                  
        
                
                    <?php  if($awardeedetails[0]->rightbanner!='') { ?>
<div class="col-md-4">


            <div class="project-container text-center" id="portfolio_2">
                <div class="recent-project-carousel owl-carousel owl-theme popup-gallery">
                    <div class="item recent-project wow fadeIn" data-wow-offset="10"  style="background-image: url(<?php echo DOCS_PATH; ?><?php echo $awardeedetails[0]->rightbanner; ?>)">
                        
                            <a href="<?php echo DOCS_PATH; ?><?php echo $awardeedetails[0]->rightbanner; ?>" data-lightbox="portfolio_2">
                            <img src="<?php echo DOCS_PATH; ?><?php echo $awardeedetails[0]->rightbanner; ?>" alt="">
                        </a>
                    </div>
                      </div>
</div>

        </div>
                    <?php } ?>
                                  
              


            


    </div>
</section>

<!-- Modal -->




</div>

<section id="upcoming-events" class="indivisual_awardees_tab_sec">
    <div class="container-fluid">
        <div class="text-center upcoming_events">
            <div class="row">
                <div class="col-sm-12">
                    <div class="wow fadeInDown d-flex flex-column align-items-start" data-wow-duration="1000ms" data-wow-delay="300ms">


                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" 	<?php  if($awardeedetails[0]->unprofile=='') { echo 'class="active"';	}?>>
                                <a href="#tabAllChakra0" role="tab" data-toggle="tab"> 
                                    <h4 class="event_day">  <?php echo $this->lang->line('details'); ?></h4>

                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#tabParam1" role="tab" data-toggle="tab"> 
                                    <h4 class="event_day"> <?php echo $this->lang->line('citation'); ?> </h4>

                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#ci" role="tab" data-toggle="tab"> 
                                    <h4 class="event_day"> Citation (PDF) </h4>

                                </a>
                            </li>
							 <li role="presentation" <?php  if($awardeedetails[0]->unprofile!='') { echo 'class="active"';	}?>>
                                <a href="#tabProfile" role="tab" data-toggle="tab">
                                    <h4 class="event_day">Profile</h4>
                                </a>
                            </li>
							 <li role="presentation">
                                <a href="#tabphoto" role="tab" data-toggle="tab">
                                    <h4 class="event_day">Photos</h4>
                                </a>
                            </li>
							 <li role="presentation">
                                <a href="#tabvideos" role="tab" data-toggle="tab">
                                    <h4 class="event_day">Videos</h4>
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#tabMahavir2" role="tab" data-toggle="tab">
                                    <h4 class="event_day">Memorial </h4>
                                </a>
                            </li>
                            
							 <li role="presentation">
                                <a href="#tabRememberance" role="tab" data-toggle="tab">
                                    <h4 class="event_day">Rememberance</h4>
                                </a>
                            </li>
                           
                            <li role="presentation">
                                <a href="#tabbib" role="tab" data-toggle="tab">
                                    <h4 class="event_day">BIBLIOGRAPHY</h4>
                                </a>
                            </li>
                             <li role="presentation">
                                <a href="#tabParam2" role="tab" data-toggle="tab">
                                    <h4 class="event_day">Postal stamp</h4>
                                </a>
                            </li>
                        </ul>
                        <!-- Tab panes -->

                        <div class="filter_tabCotent_graph_div">

                            <div class="filter_tab_content_wrap">

                                <div class="tab-content">

                                    <div role="tabpanel" class="tab-pane <?php  if($awardeedetails[0]->unprofile=='') { echo 'active';	}?>" id="tabAllChakra0">
                                        <div class=" ">

                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td> <?php //echo $this->lang->line('award'); ?>Award / Date of Action </td>
                                                        <td> <?php 
														echo ($awardeedetails[0]->chakra_2)?$awardeedetails[0]->chakra_2.', ':'';
														
														echo ($awardeedetails[0]->chakra_title) ? ($awardeedetails[0]->chakra_title) : ('N/A'); ?>
                                                                                                                                 <?php if ($awardeedetails[0]->a_posthumous == 1) { ?> (Posthumous)<?php } ?>
                                                                                                                               <?php
															echo ($awardeedetails[0]->award_year_2)?explode('-',$awardeedetails[0]->award_year_2)[0].', ':'';
                                                             $exp = explode('-', $awardeedetails[0]->a_award_year);
                                                            echo ($exp[0]) ? (", ".substr($exp[2],0,2)."-".$exp[1]."-".$exp[0]) : ('N/A');
													
                                                            ?> 
                          
                            </td>

                                                    </tr>
                                                   
                                                    <tr>
                                                        <td> Service </td>
                                                        <td><?php echo ($awardeedetails[0]->category) ? ($awardeedetails[0]->category) : ('N/A'); ?> </td>
                                                    </tr>
                                                    <tr>
                                                        <td> <?php //echo $this->lang->line('service-no'); ?>Service Number </td>
                                                        <td><?php echo ($awardeedetails[0]->a_service_no) ? ($awardeedetails[0]->a_service_no) : ('N/A'); ?> </td>
                                                    </tr>
                                                    <tr>
                                                        <td> <?php ///echo $this->lang->line('rank-at-time-of-award'); ?>Rank</td>
                                                        <td> <?php echo ($awardeedetails[0]->rank_2)?$awardeedetails[0]->rank_2.', ':''; echo ($awardeedetails[0]->a_rank) ? ($awardeedetails[0]->a_rank) : ('N/A'); ?> </td>
                                                    </tr>
                                                    <tr>
                                                        <td> <?php //echo $this->lang->line('unit'); ?> Unit/Regiments/Corps</td>
                                                        <td><?php echo ($awardeedetails[0]->unit_2)?$awardeedetails[0]->unit_2.', ':''; echo ($awardeedetails[0]->a_unit) ? ($awardeedetails[0]->a_unit) : ('N/A'); ?>  </td>
                                                    </tr>
                                                    <tr>
                                                        <td> <?php //echo $this->lang->line('father_name'); ?>Son Of </td>
                                                        <td> <?php echo ($awardeedetails[0]->a_father_name) ? ($awardeedetails[0]->a_father_name) : ('N/A'); ?> </td>
                                                    </tr>
                                                    <tr>
                                                        <td> <?php echo $this->lang->line('mother_name'); ?> </td>
                                                        <td>  <?php echo ($awardeedetails[0]->a_mother) ? ($awardeedetails[0]->a_mother) : ('N/A'); ?>  </td>
                                                    </tr>
													<?php //if($awardeedetails[0]->a_domicile!="") { ?>
                                                    <tr>
                                                        <td> <?php //echo $this->lang->line('domicile'); ?> Resident Of (Village/District/State/Domicile) </td>
                                                        <td> <?php echo ($awardeedetails[0]->a_domicile) ? ($awardeedetails[0]->a_domicile) : ('N/A'); ?> </td>
                                                    </tr>
													<?php //} ?>
													<tr>
                                                        <td> <?php //echo $this->lang->line('domicile'); ?> War/Operation/Battle </td>
                                                        <td> <?php echo $wartitle[0]->war_title; ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>

                                    
                                    
                                    
                                          <div role="tabpanel" class="tab-pane" id="ci">
                                         
                                          <div class="container-fluid">
    <div class="row">
     <?php if($awardeedetails[0]->ci_english!='') { ?>
        <div class="col-lg-6 col-md-6 col-sm-6">
       <a href ="<?php echo DOCS_PATH;?>styles/awardee_img/public/sites/default/files/Citation.docx" download>Download Citation</a>
       <iframe src="<?php echo ASSETS_PATH; ?>citation/<?php echo $awardeedetails[0]->ci_english; ?>" width="100%"  height="600px"></iframe>
            
        </div>
        <?php }  if($awardeedetails[0]->ci_hindi!='') { ?>
        <div class="col-lg-6 col-md-6 col-sm-6">
        <a href ="<?php echo DOCS_PATH;?>styles/awardee_img/public/sites/default/files/Citation.docx" download>Download Citation</a>
           <iframe src="<?php echo ASSETS_PATH; ?>citation/<?php echo $awardeedetails[0]->ci_hindi; ?>" width="100%"  height="600px"></iframe>
        </div>
        <?php } ?>
         <?php if($awardeedetails[0]->ci_english=='' && $awardeedetails[0]->ci_hindi=='') { echo "No Data Found!"; }?>
    </div>
</div>
                                          </div>
                                          
                                          
                                          
                                          
                                    <div role="tabpanel" class="tab-pane" id="tabParam1">
                                        <div class="container-fluid">


                                            <?php foreach ($citationimage as $ckey => $cvalue) {
                                            if($cvalue->filename){
                                                ?>
                                                <div class="col-lg-6 col-md-6 col-sm-12 left responsive_img_100 mb_10px py_10px px_10px">
                                                    <?php if($lang =='hindi') { ?>
												<img src="<?php echo DOCS_PATH . '' . $cvalue->filename_hindi; ?>" alt="Citation" class="img-citation img-responsive">
												<?php } else { ?>
												<img src="<?php echo DOCS_PATH . '' . $cvalue->filename; ?>" alt="Citation" class="img-citation img-responsive">
												<?php } ?>
                                                </div>
                                            <?php 
                                            }
                                            } ?>

                                        </div>

                                    </div>
                                  <div role="tabpanel" class="tab-pane" id="tabMahavir2">
										<?php if($war[0]->description_english!='') { ?>
                                        <div class="container-fluid">
                                            <p> <?php echo $war[0]->description_english; ?> </p>
											<?php if($war[0]->main_image!='') { ?>
											<img src="<?php echo ASSETS_PATH; ?>wars/<?php echo $war[0]->main_image; ?>" >
											<?php }?>
                                        </div>
										<?php } else { ?>
										No Data Found!
										<?php }?>

                                    </div>
                                     <div role="tabpanel" class="tab-pane" id="tabParam2">
										<?php if($postal_stamp[0]->title_english!='') { ?>
                                        <div class="container-fluid">
                                            <p> <?php echo $postal_stamp[0]->title_english; ?> </p>
											<?php if($postal_stamp[0]->main_image!='') { ?>
											<img src="<?php echo ASSETS_PATH; ?>front/images/postal/<?php echo $postal_stamp[0]->main_image; ?>" >
											<?php }?>
                                        </div>
										<?php } else { ?>
										No Data Found!
										<?php }?>

                                    </div>

  <div role="tabpanel" class="tab-pane" id="tabRememberance">
										<div class="panel-group" id="accordionMahavir" role="tablist" aria-multiselectable="true">
											<div class="panel panel-default"><div class="container-fluid">
											<?php  if(count($rem)>0) { 
										foreach($rem as $rem) {
											?>
											<div class="col-sm-4" id="artlisttable">
							<div class="img_cntnt_wrap" onclick=" "> 
								<div class="flip-box-front">
									
										<div class="folio-image">
											<a href="<?php echo ASSETS_PATH; ?>uploads/rememberance/<?php echo $rem->file; ?>" data-lightbox="portfolio_1"><img class="img-responsive" src="<?php echo ASSETS_PATH; ?>uploads/rememberance/<?php echo $rem->file; ?>"></a>
											<div class="caption_gallery_img text-center px-1"><p><?php echo $rem->caption; ?></p></div>
										</div>
									
									
								</div>
							</div></div>
										<?php } } else { echo "Records Not Found"; }?>
							</div></div></div>

                                    </div>
                 <div role="tabpanel" class="tab-pane <?php  if($awardeedetails[0]->unprofile!='') { echo 'active';	}?>" id="tabProfile">
										<?php 
                    
                    if($awardeedetails[0]->unprofile!='') {
                                          
											?>
                                        <div class="container-fluid">
                                           
											
											<iframe src="<?php echo DOCS_PATH; ?>styles/awardee_img/public/sites/default/files/<?php echo $awardeedetails[0]->unprofile; ?>" width="100%"  height="600px"></iframe>


											
                                        </div>
										<?php  } else { ?>
										No Data Found!
										<?php }?>

                                    </div>			
									
									
									
									<div role="tabpanel" class="tab-pane" id="tabphoto">
										
									<div class="panel-group" id="accordionMahavir" role="tablist" aria-multiselectable="true">
											<div class="panel panel-default"><div class="container-fluid">
											<?php  if(count($photo)>0) { 
										foreach($photo as $photo) {
											?>
											<div class="col-sm-4" id="artlisttable">
							<div class="img_cntnt_wrap" onclick=" "> 
								<div class="flip-box-front">
									
										<div class="folio-image">
											<a href="<?php echo ASSETS_PATH; ?>photos/<?php echo $photo->photo; ?>" data-lightbox="portfolio_1"><img class="img-responsive" src="<?php echo ASSETS_PATH; ?>photos/<?php echo $photo->photo; ?>"></a>
											<div class="caption_gallery_img text-center px-1"><p><?php echo $photo->caption; ?></p></div>
										</div>
									
									
								</div>
							</div></div>
										<?php } } else { echo "Records Not Found"; }?>
							</div></div></div>
										

                                    </div>
									
									<div role="tabpanel" class="tab-pane" id="tabbib">
								<?php  if(count($bib)>0) { ?>
	<ol>
									<?php	foreach($bib as $bib) { 	?>
									<li><?php echo $bib->title; ?></li>
                                           
	<?php } } else { echo "Records Not Found"; }?>
                                    </div>
									
									
									<div role="tabpanel" class="tab-pane" id="tabvideos">
                                                                       
                                                                 <!-- Second Option -->
		<div class="row video-opt-2">
			
       <?php  if($awardeedetails[0]->video!='') { ?>
       <div class="col-md-4">
				<iframe width="100%" height="200" src="<?php echo DOCS_PATH; ?><?php echo $awardeedetails[0]->video; ?>">
				</iframe>
				<div class="caption_gallery_img text-center px-1">
					<p><?php echo $awardeedetails[0]->a_video_caption; ?></p>
				</div>
			</div>
              <?php } ?>
				<?php  if(count($video)>0) { 
										foreach($video as $video) {
											$video1 = $video->video_link;
							$video_id = explode("?v=", $video1);
							$video_id = $video_id[1];
							$thumbnail="https://img.youtube.com/vi/".$video_id."/0.jpg";
											?>
			<div class="col-md-4">
				<iframe width="100%" height="200" src="https://www.youtube.com/embed/<?php echo $video_id; ?>">
				</iframe>
				<div class="caption_gallery_img text-center px-1">
					<p><?php echo $video->video_caption; ?></p>
				</div>
			</div>
		<?php } } else { echo "Records Not Found"; }?>
		</div>
		<!-- End Second Option -->
	
		<div class="row">
			<div class="col-md-12 text-left">
				<h4>DISCLAIMER:</h4>
				<p>Photos, links to publications and videos presented here are not intended to serve as a substitute for
					consultation and should only be used at the user’s own risk. These are primarily shared because of the
					extensive coverage available on the subject. No copyright infringement is intended and it is not
					intended to hurt anyone or make sales of any sort. All copyright belongs solely to the relevant
					owners/creators. Usage here is purely for Fair Usage in accordance with the Indian Copyright Act 1957.
				</p>
	
			</div>
		</div>      
                                                                       
                                                                       
                  

                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

 <?php if(count($stories)>0) {?>
   <section id="upcoming-events" class="about_us_page_sec" style="display:none">
   <div class="container">
	  <div class="heading wow fadeInUp" data-wow-duration="1000ms" data-wow-delay="300ms">
        <div class="row">
          <div class="text-center col-md-12 col-sm-12 col-xs-12">
            <h2 class="crd_h2"> Stories</h2>
          </div>
        </div> 
      </div>
	</div>
    <div class="container">
      
      <div class="text-center stories-sec">
        <div class="row">
			<div class="col-sm-12">
			  <div class="wow fadeInDown d-flex flex-column align-items-start" data-wow-duration="1000ms" data-wow-delay="300ms">


				<!-- Nav tabs -->
				
			 <!-- Tab panes -->
			 <?php foreach($stories as $key=>$val) {?>
				<div class="tab-content">
				  <div role="tabpanel" class="tab-pane active" id="tabParam1">
					<div class="container-fluid">
					  <div class="timeline">
						<div class="pane-group" id="accordionParam" role="tablist" aria-multiselectable="true">
						  <div class="col-md-12">
							<h3><?php echo $val->title; ?> </h3>
						  </div>
						  <div class="panel panel-default">
							
							<div id="collapseParam1" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingParam1">
							  <div class="pane-body">
								<p><?php echo $val->post; ?></p>
								
							  </div>
							</div>
						  </div>
						  </div>
						</div>
					</div>
				  </div>
				  <p class="story-btm">Contributed by <?php echo $val->name; ?>  on GallantryPedia on <?php echo $val->created_date; ?></p>
				  </div>
			 <?php } ?>
			
			  </div>
			</div>
		  </div>
		</div>
	</div>
  </section><!--/#about-us-->
  <?php } ?>
  <?php if(count($photopost)>0) { ?>
  <section id="message_sec"  style="display:none">
  
	<div class="container">
	  <div class="wow fadeInUp" data-wow-duration="1000ms" data-wow-delay="300ms">
        <div class="row">
          <div class="text-center col-md-12 col-sm-12 col-xs-12">
            <h2 class="crd_h2"> Photo  Gallery</h2>
          </div>
        </div> 
      </div>
	</div>
  
	<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div id="photo-slider" class="owl-carousel">
	  <?php foreach($photopost as $pkey=>$pvalue) {?>
        <div class="pic-slide">
          <div class="pic-img">
            <?php if($pvalue->media){?>
		  <img src="<?php echo ASSETS_PATH; ?>images/crowd_source/profile/<?php echo $pvalue->media; ?>" alt="">
		  <a href="<?php echo ASSETS_PATH; ?>images/crowd_source/profile/<?php echo $pvalue->media; ?>" class="over-layer" data-lightbox="portfolio_1"><i class="fa fa-link"></i></a>
		  <?php  } else { ?>
            <img src="<?php echo ASSETS_PATH; ?>images/cpic-gal1.jpg" alt="">
		  <?php } ?>
                      </div>
          <p class="pic-description"><?php echo $pvalue->post; ?></p> 
               
            <h3 class="post-title">
              Message by1 <?php echo $pvalue->name; ?>  on GallantryPedia on <?php echo $pvalue->created_date; ?>
            </h3>
                 
          </div>
	  <?php } ?>
        </div>
		
		
	  </div>
    </div>
  </div>


  </section><?php } ?>
 <?php if(count($videopost)>0) {?>
 <section id="message_sec"  style="display:none">
  
	<div class="container">
	  <div class="heading wow fadeInUp" data-wow-duration="1000ms" data-wow-delay="300ms">
        <div class="row">
          <div class="text-center col-md-12 col-sm-12 col-xs-12">
            <h2 class="crd_h2"> Video  Gallery</h2>
          </div>
        </div> 
      </div>
	</div>
  
	<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div id="video-slider" class="owl-carousel">
	  <?php foreach($videopost as $vkey=>$vvalue) {?>
        <div class="pic-slide">
          <div class="pic-img">
		 <?php if($vvalue->thumbnail){?>
		  <img src="<?php echo ASSETS_PATH; ?>images/crowd_source/profile/<?php echo $vvalue->thumbnail; ?>" alt="">
		  <?php  } else { ?>
            <img src="<?php echo ASSETS_PATH; ?>images/cpic-gal1.jpg" alt="">
		  <?php } ?>
            
            <a href="<?php echo $vvalue->media; ?>" class="over-layer"><i class="fa fa-link"></i></a>
           <h3 class="post-title">
              Message by <?php echo $vvalue->name; ?>  on GallantryPedia on <?php echo $vvalue->created_date; ?>
            </h3>
             </div>
              
           
                 
          </div>
	  <?php } ?>
        
        
        </div>
		
		
	  </div>
    </div>
  </div>


</section>
 <?php } ?>
<?php if(count($podcastpost)>0) {?>
 <section id="message_sec"  style="display:none">
  
	<div class="container">
	  <div class="heading wow fadeInUp" data-wow-duration="1000ms" data-wow-delay="300ms">
        <div class="row">
          <div class="text-center col-md-12 col-sm-12 col-xs-12">
            <h2 class="crd_h2"> Poadcast</h2>
          </div>
        </div> 
      </div>
	</div>
  
	<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div id="podcast-slider" class="owl-carousel">
	   <?php foreach($podcastpost as $pkey=>$pval){ ?>
        <div class="pic-slide">
          <div class="pod-img">
		  <?php if($pval->thumbnail){?>
		  <img src="<?php echo ASSETS_PATH; ?>images/crowd_source/profile/<?php echo $pval->thumbnail; ?>" alt="">
		  <?php  } else { ?>
            <img src="<?php echo ASSETS_PATH; ?>images/cpic-gal1.jpg" alt="">
		  <?php } ?>
		   <a href="<?php echo $pval->media; ?>" class="over-layer"><i class="fa fa-link"></i></a>
           <h3 class="post-title">
              Message by <?php echo $pval->name; ?>  on GallantryPedia on <?php echo $pval->created_date; ?>
            </h3>
             </div>
              
          </div>
	   <?php  } ?>
        
        
         
        
      
        </div>
		
		
	  </div>
    </div>
  </div>


</section>
  <?php } ?>
  

<!--/#about-us-->
<script>
/*function givetribure(id,name)
{

	var redirecturl='<?php echo BASE_PATH."tribute"; ?>';
	
	 $.ajax({
            url: "<?php echo BASE_PATH; ?>ajax_data/gettribute",
            method: "POST",
            data: {id: id,name:name},
            success: function (data) {

               window.location.replace(redirecturl);

            }
        });
}*/
</script>


<?php //echo modules::run("header/front_header/footer"); ?>
<?php include('footer.php'); ?>
</body>
<html>