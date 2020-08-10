<?php
   /*
   Template Name: New-home 
   */
   
   get_header('new');
    ?>
<!--div id="primary" class="content-area fullwidth"-->
<main id="main" class="site-main" role="main">
   <div class="container">
      <!--product category section start-->
      <section class="product_categ">
         <h2>Product Categories</h2>
         <div class="row">
            <?php
               $prod_cat_args = array(
               'taxonomy'     => 'product_cat',
                        'orderby'      => 'name',
                         'posts_per_page' => 1,
                       );
               
                       $all_cat = get_categories( $prod_cat_args );
                       foreach ( $all_cat as $get_all_cat ) {
                        $term_link = get_term_link( $get_all_cat );
               
                        ?>
            <div class="col-md-4">
               <?php
                  echo 
                    '<a class="shopping-now" href="' . esc_url( $term_link ) . '">'."<h3>GSB</h3>"
                    	."<img src='http://www.gridironstuds.com/blog/wp-content/uploads/GISlogo452-1.png'>". 
                    $get_all_cat->name . '</a>'
                  ?>
            </div>
            <?php
               }	
               ?>
         </div>
      </section>
   </div>
   <!--Youth Football Playbooks section start-->
   <section class="fotbal_products">
      <div class="container">
         
         <div class="row">
         	<h2>Youth Football Playbooks</h2>
            <div class="col-md-12">
               <div class="woocommerce columns-4 ">
                  <ul class="products">
                     <?php
                        $args = array(
                        	'post_type' => 'product',
                        	'posts_per_page' => 8
                        	);
                        $loop = new WP_Query( $args );
                        if ( $loop->have_posts() ) {
                        	while ( $loop->have_posts() ) : $loop->the_post();
                        		wc_get_template_part( 'content', 'product' );
                        	endwhile;
                        } else {
                        	echo __( 'No products found' );
                        }
                        wp_reset_postdata();
                        ?>
                  </ul>
                  <!--/.products-->
                  
               </div>
            </div>
            <div class="col-md-12">
                 	 <a href="/blog/shop" class="btn btn-danger all-product">See all product</a>
                 </div>
         </div>
      </div>
   </section>
   <!--article section start-->
   <section class="home-tabbed-articles">
      <div class="container">
         <div class="product-tabs">
            <input checked="checked" id="tab1" type="radio" name="pct" />
            <input id="tab2" type="radio" name="pct" />
            <input id="tab3" type="radio" name="pct" />
            <input id="tab4" type="radio" name="pct" />
            <nav>
               <ul class="nav nav-tabs nav-justified">
                  <li class="tab1">
                     <label for="tab1">Feature artical</label>
                  </li>
                  <li class="tab2">
                     <label for="tab2">recent artical</label>
                  </li>
                  <li class="tab3">
                     <label for="tab3">free plays</label>
                  </li>
               
               </ul>
            </nav>
            <div class="article_tab_content">
               <div class="tab1">
                 <?php
					$taxArg = array(
						'post_type' => 'mt_destinations',
						'posts_per_page' => 4,
						'tax_query' => array(
							array(
							  'taxonomy' => 'destination_category',
							  'field' => 'slug',
							  'terms' => 'feature-artical'
							)
						),
					);

					$taxPosts = new Wp_Query($taxArg);
						while ($taxPosts->have_posts() ) {
							$taxPosts->the_post();
							 $term_link = get_term_link( $taxPosts );

							?>
							<div class="col-md-4">
								<?php the_post_thumbnail('thumbnail');?>

							</div>

							<div class="col-md-8">
			                     <a href="<?php the_permalink() ?>">
			                     <h1><?php the_title();?></h1>
			                     <p><?php the_content();?></p>
			                  </a>
							</div>

							<?php	
						}
					?>
					<a href="/mt_destinations/" class="btn btn-danger">See All Articles</a>

               </div>
               <div class="tab2">
                   <?php
					$recent_post = array(
						'post_type' => 'mt_destinations',
						'posts_per_page' => 4,
						'order'    => 'ASC'
					
					);

					$recent_posts = new Wp_Query($recent_post);
						while ($recent_posts->have_posts() ) {
							$recent_posts->the_post();

							?>
							<div class="col-md-4">
								<?php the_post_thumbnail('thumbnail');?>

							</div>

							<div class="col-md-8">
			                     <a href="<?php the_permalink() ?>">
			                     <h1><?php the_title();?></h1>
			                     <p><?php the_content();?></p>
			                  </a>
							</div>

							<?php	
						}
					?>
					<a href="/mt_destinations/" class="btn btn-danger">See All Articles</a>
               </div>
               <div class="tab3">
                  <h2>Coming Soon</h2>
               </div>
              
            </div>
         </div>
      </div>
   </section>
   <!-- 
      <?php while ( have_posts() ) : the_post(); ?>
      
      	<?php the_content(); ?>
      
      <?php endwhile; // end of the loop. ?>
      -->
</main>
<!-- #main -->
<!--/div><! #primary -->
<?php get_footer('new'); ?>