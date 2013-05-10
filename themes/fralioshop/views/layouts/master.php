<!DOCTYPE html>
<html lang="nl">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Fralioshop</title>

        <!-- Framework CSS -->
        <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/style.css" type="text/css" media="screen, projection">

        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/images/favicon.ico" type="image/x-icon" rel="icon" />
        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/images/favicon.ico" type="image/x-icon" rel="shortcut icon" />

        <!-- Import fancy-type plugin for the sample page. -->
        <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/aw-showcase.css" type="text/css" media="screen, projection">

        <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/general.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.tools.min.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.easing.1.3.js"></script>

        <script type="text/javascript">

            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-9467643-2']);
            _gaq.push(['_trackPageview']);

            (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();

        </script>
    </head>
    <body>
        <div class="body_wrap">

            <div class="header">   

                <div class="header_top">
                    <div class="container">
                        <div class="logo"><a href="<?php echo Yii::app()->homeUrl; ?>"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/logo.png" alt="Fralioshop" /></a></div>
                        <div class="fixed_bar">
                        <ul class="header_bar">
                            <li class="user_menu">
                                <a href="#" class="toggle"><?php echo (Yii::app()->customer->isGuest) ? "Inloggen" : Yii::app()->customer->name; ?></a>
                                <div class="toggle_content user_menu_content widget_login">

                                    <?php $this->widget('application.modules.sales.widgets.LoginMenu'); ?>

                                </div>
                            </li>

                            <li class="cart_menu">
                                <a href="#" class="toggle">Winkelwagen: <?php echo Yii::app()->shoppingCart->count; ?></a>
                                <div class="toggle_content">
                                    <div style="background-color: white; border: 2px solid #D0D0D0; width: 220px; height: 250px; overflow-y: scroll;">
                                        <?php $this->widget('application.modules.sales.widgets.cart.CartMenu',array('htmlOptions'=>array('id'=>"cart-menu"))); ?>
                                    </div>
                                    <a class="button_link btn_grey" href="<?php echo $this->createUrl('/sales/account/cart'); ?>"><span>Bekijk winkelwagen</span></a>
                                </div>
                            </li>

                        </ul>
                        </div>


                    </div>
                </div>

                <div class="header_menu">
                    <div class="container">
                        <div class="topmenu">
                            <ul class="dropdown">

                                <li class="menu-item-home parent <?php echo (Yii::app()->controller->route == 'fralio/index') ? "current-menu-item" : ""; ?>"><a href="<?php echo Yii::app()->homeUrl; ?>"><span>Home</span></a></li>

                                <?php foreach (ProductCategory::model()->active()->getTree() as $category): ?>
                                    <li <?php echo (isset($_GET['alias']) && $_GET['alias'] == $category->alias) ? "class=current-menu-item " : ""; ?>><a href="<?php echo $category->getUrl(); ?>">
                                            <span><?php echo $category->name; ?></span>
                                        </a>
                                        <ul>
                                            <?php foreach ($category->children as $subcategory): ?>
                                                <li <?php echo (isset($_GET['alias']) && $_GET['alias'] == $subcategory->alias) ? "class=current-menu-item " : ""; ?>><a href="<?php echo $subcategory->getUrl(); ?>">
                                                        <span><?php echo $subcategory->name; ?></span>
                                                    </a></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </li>
                                <?php endforeach; ?>

                            </ul>
                        </div>
                    </div>
                </div>


                <div class="submenu_bar">
                    <div class="container">
                        <div class="breadcrumbs">
                            <?php
                            $this->widget('zii.widgets.CBreadcrumbs', array(
                                'homeLink' => CHtml::link('Home', Yii::app()->homeUrl),
                                'links' => $this->breadcrumbs,
                            ));
                            ?><!-- breadcrumbs -->
                        </div>
                        <div class="searchbar">
													<?php echo CHtml::beginForm(array('/fralio/search'), 'get'); ?>
                            <?php echo CHtml::textField('q', isset($_GET['q']) ? $_GET['q'] : '', array('class'=>'inputField')); ?>
                            <?php echo CHtml::submitButton("", array('class'=>'btn-submit')); ?>
                          <?php echo CHtml::endForm(); ?>

                        </div>
                    </div>
                </div>



            </div>
            <!--/ header -->

            <!-- middle -->

            <?php echo $content; ?>


            <div class="middle">



                <div class="container_16">

                    <div class="row">

                        <div class="col col_1_3 alpha">

                        </div>
                    </div>
                    <!--/ 3 cols -->


                    <div class="divider_space"></div>

                    <div class="box_light_gray box_border2">
                        <div class="banner_bar" style="text-align: center;">
                            <a href="http://www.thuiswinkelkeurmerk.info"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/partners/thuiswinkel.gif" alt="" /></a>
                            <a href="#"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/partners/ideal.gif" alt="" /></a>
                            <a href="#"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/partners/ssl.gif" alt="" /></a>
                            <a href="#"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/partners/overboeking.gif" alt="" /></a>
                        </div>

                        <div class="clear"></div>
                    </div>


                    <div class="divider_space"></div>

						  
                    
                        <div class="product-category voordelen" style="width:927px;"> 
                            <h2>Voordelen Fralioshop</h2>
                            <ul>
                                <li>Veilig betalen:<br /><span>iDeal en overboeking</span></li>
                                <li>Privacy gewaarborgd<br /><span>SSL gecertificeerd</span></li>
                                
                            </ul>
                            <ul>
                                <li>Snelle communicatie<br /><span>24/7 contact</span></li>
                                <li>Scherpe verzendtarieven<br /><span>Verzending vanaf € 4,95</span></li>
                            </ul>
														<ul>
                                <li>Kopen met bedenktijd<br /><span>7 dagen bedenktijd</span></li>
                            </ul>
                        </div>


                    <!-- <div class="col col_1_3">


                        <div class="newsletterBox">    
                            <div class="inner">
                                <div class="ribbon"></div>
                                <h2>Aanmelden nieuwsbrief</h2>
                                <div class="before-text">Meld u hier aan voor de maandelijkse nieuwebrief met nieuwe aanbiedingen:</div>
                                <form action="" method="post">
                                    <input type="text" value="" name="" class="inputField" />
                                    <input type="submit" value="" name="" class="btn-submit" />

                                    <div class="clear"></div>

                                </form>
                            </div>
                        </div> 

                    </div> -->




                    <div class="clear"></div>            
                </div>  
            </div>
            <div class="middle_bot"></div>

            <!--/ middle -->    	

            <!-- footer --> 
            <div class="footer">
                <div class="container_16">

                    <div class="grid_4">
                        <h3>Algemeen</h3>
                            <?php $this->widget('CategoryMenu', array('alias'=>'algemeen')); ?>
                    </div>

                    <div class="grid_4">
                        <h3>Informatie</h3>
                        <ul>
                            <li><?php echo CHtml::link('Home', Yii::app()->homeUrl); ?></li>
                            <li><?php echo CHtml::link('Contact', Yii::app()->controller->createUrl('/fralio/contact') ); ?></li>
                            <li><?php echo CHtml::link('Sitemap', Yii::app()->controller->createUrl('/fralio/sitemap') ); ?></li>
                        </ul>
                    </div>

                    <div class="grid_4">
                        <h3>Service</h3>
                        <?php $this->widget('CategoryMenu', array('alias'=>'service')); ?>
                    </div>

                    <div class="grid_4">
                        <div class="text-center">
                            <p><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/logo_footer.png" width="200" height="34" alt="" /></p>
                            <br />

                            <p><a href="mailto:<?php echo Yii::app()->administration->email; ?>"><?php echo Yii::app()->administration->email; ?></a> <br /> <?php echo Yii::app()->administration->phone_nb; ?></p>
                        </div>
                    </div>

                    <div class="clear"></div>

                    <div class="copyright">Alle rechten voorbehouden &copy; Fralioshop.nl 2010 - <?php echo date('Y'); ?></div>            

                </div>     	
            </div> 


        </div>
    </body>
</html>
