<link rel="stylesheet" href="css/nav.css">
<nav>
    <div class="nav-holder mw">
        <div class="menu">
            <div class="burger">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
            <p class="light">Menü</p>
        </div>
        <a class="logo-holder" href="<?php echo esc_url( home_url( '/' ) ); ?>">
            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/icons/logo.svg' ); ?>" alt="Wendland Web Design">
        </a>
        <div class="nav-right">
            <img class="icon icon--phone" onclick="" src="<?php echo esc_url( get_template_directory_uri() . '/assets/icons/phone.svg' ); ?>" alt="" aria-hidden="true">
            <a class="btn light" href="<?php echo esc_url( home_url( '/kontakt/' ) ); ?>">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <p><?php echo wwd_inline_svg( 'arrow-white.svg', array( 'class' => 'icon--arrow-white', 'aria_hidden' => true ) ); ?>Kontakt</p>
            </a>
        </div>
        <div class="nav-list-wrapper">
            <div class="close-btn-wrapper">
                <div class="close-btn">
                    <span></span>
                    <span></span>
                </div>
            </div>

            <div class="nav-list">
                <ul class="list-left">
                    <li class="list-left-item expand-right" data-nav-target="dienstleistungen">
                        <p class="dark"><?php echo wwd_inline_svg( 'arrow-191919.svg', array( 'class' => 'arrow', 'aria_hidden' => true ) ); ?>Dienstleistungen</p>
                        <?php echo wwd_inline_svg( 'mini-arrow.svg', array( 'class' => 'mini-arrow', 'aria_hidden' => true ) ); ?>
                    </li>
                    <li class="list-left-item expand-right" data-nav-target="referenzen">
                        <p class="dark"><?php echo wwd_inline_svg( 'arrow-191919.svg', array( 'class' => 'arrow', 'aria_hidden' => true ) ); ?>Referenzen</p>
                        <?php echo wwd_inline_svg( 'mini-arrow.svg', array( 'class' => 'mini-arrow', 'aria_hidden' => true ) ); ?>
                    </li>
                    <li class="list-left-item"><a class="dark" href="<?php echo esc_url( home_url( '/ki-integration/' ) ); ?>">KI Integration</a></li>
                    <li class="list-left-item expand-right" data-nav-target="news">
                        <p class="dark"><?php echo wwd_inline_svg( 'arrow-191919.svg', array( 'class' => 'arrow', 'aria_hidden' => true ) ); ?>News</p>
                        <?php echo wwd_inline_svg( 'mini-arrow.svg', array( 'class' => 'mini-arrow', 'aria_hidden' => true ) ); ?>
                    </li>
                    <li class="list-left-item"><a class="dark" href="<?php echo esc_url( home_url( '/ueber-uns/' ) ); ?>">Über uns</a></li>
                    <li class="list-left-item"><a class="dark" href="<?php echo esc_url( home_url( '/kontakt/' ) ); ?>">Kontakt</a></li>
                </ul>


                <div class="list-right-wrapper">
                    <div class="list-right">
                        <div class="list-right-content" data-nav-panel="dienstleistungen">
                            <div class="top-bar">
                                <div class="back-btn-wrapper">
                                    <?php echo wwd_inline_svg( 'arrow-191919.svg', array( 'class' => 'back-btn', 'aria_hidden' => true ) ); ?>
                                </div>
                            </div>
                            <div class="list-right-content-wrapper">
                                <div class="right-buttons">
                                    <a class="dark" href="<?php echo esc_url( home_url( '/dienstleistungen/' ) ); ?>">Alle Dienstleistungen <?php echo wwd_inline_svg( 'corner-arrow-dark.svg', array( 'class' => 'corner-arrow', 'aria_hidden' => true ) ); ?></a>
                                </div>
                                <div class="right-cards">
                                    <div class="nav-card">
                                        <div class="card-img-holder">
                                            <img class="card-img" src="img/placeholder.jpg" alt="">
                                        </div>
                                        <p class="dark">Webangebote <?php echo wwd_inline_svg( 'corner-arrow-dark.svg', array( 'class' => 'corner-arrow', 'aria_hidden' => true ) ); ?></p>
                                    </div>
                                    <div class="nav-card">
                                        <div class="card-img-holder">
                                            <img class="card-img" src="img/placeholder.jpg" alt="">
                                        </div>
                                        <p class="dark">KI-Angebote <?php echo wwd_inline_svg( 'corner-arrow-dark.svg', array( 'class' => 'corner-arrow', 'aria_hidden' => true ) ); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="list-right-content" data-nav-panel="referenzen">
                            <div class="top-bar">
                                <div class="back-btn-wrapper">
                                    <?php echo wwd_inline_svg( 'arrow-191919.svg', array( 'class' => 'back-btn', 'aria_hidden' => true ) ); ?>
                                </div>
                            </div>
                            <div class="list-right-content-wrapper">
                                <div class="right-buttons">
                                    <a class="dark" href="<?php echo esc_url( home_url( '/referenzen/' ) ); ?>">Alle Referenzen <?php echo wwd_inline_svg( 'corner-arrow-dark.svg', array( 'class' => 'corner-arrow', 'aria_hidden' => true ) ); ?></a>
                                </div>
                                <div class="right-cards">
                                    <div class="nav-card">
                                        <div class="card-img-holder">
                                            <img class="card-img" src="img/placeholder.jpg" alt="">
                                        </div>
                                        <p class="dark">Hummeln <?php echo wwd_inline_svg( 'corner-arrow-dark.svg', array( 'class' => 'corner-arrow', 'aria_hidden' => true ) ); ?></p>
                                    </div>
                                    <div class="nav-card">
                                        <div class="card-img-holder">
                                            <img class="card-img" src="img/placeholder.jpg" alt="">
                                        </div>
                                        <p class="dark">Bienen <?php echo wwd_inline_svg( 'corner-arrow-dark.svg', array( 'class' => 'corner-arrow', 'aria_hidden' => true ) ); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="list-right-content" data-nav-panel="news">
                            <div class="top-bar">
                                <div class="back-btn-wrapper">
                                    <?php echo wwd_inline_svg( 'arrow-191919.svg', array( 'class' => 'back-btn', 'aria_hidden' => true ) ); ?>
                                </div>
                            </div>
                            <div class="list-right-content-wrapper">
                                <div class="right-buttons">
                                    <a class="dark" href="<?php echo esc_url( home_url( '/news/' ) ); ?>">Alle News <?php echo wwd_inline_svg( 'corner-arrow-dark.svg', array( 'class' => 'corner-arrow', 'aria_hidden' => true ) ); ?></a>
                                </div>
                                <div class="right-cards">
                                    <div class="nav-card">
                                        <div class="card-img-holder">
                                            <img class="card-img" src="img/placeholder.jpg" alt="">
                                        </div>
                                        <p class="dark">Nagetiere <?php echo wwd_inline_svg( 'corner-arrow-dark.svg', array( 'class' => 'corner-arrow', 'aria_hidden' => true ) ); ?></p>
                                    </div>
                                    <div class="nav-card">
                                        <div class="card-img-holder">
                                            <img class="card-img" src="img/placeholder.jpg" alt="">
                                        </div>
                                        <p class="dark">Hornissen <?php echo wwd_inline_svg( 'corner-arrow-dark.svg', array( 'class' => 'corner-arrow', 'aria_hidden' => true ) ); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
