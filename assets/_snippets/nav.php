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
            <img class="icon icon--phone nav-contact-btn-mobile" onclick="" src="<?php echo esc_url( get_template_directory_uri() . '/assets/icons/anfrage-blue.svg' ); ?>" alt="" aria-hidden="true">
            <button class="btn light nav-contact-btn">
                <span class="btn__border" aria-hidden="true">
                    <svg class="btn__svg" viewBox="0 0 100 40" preserveAspectRatio="none">
                        <path class="btn__path" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
                        <path class="btn__seg btn__seg--1" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
                        <path class="btn__seg btn__seg--2" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
                        <path class="btn__seg btn__seg--3" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
                        <path class="btn__seg btn__seg--4" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
                    </svg>
                </span>
                <p><?php echo wwd_inline_svg( 'arrow-white.svg', array( 'class' => 'icon--arrow-white', 'aria_hidden' => true ) ); ?>Anfrage</p>
            </button>
        </div>

        <div class="nav-contact-holder">
            <div class="nav-contact">
                <div class="nav-contact-close-wrapper">
                    <div class="nav-contact-close">
                        <span></span>
                        <span></span>
                    </div>
                </div>
                <div class="img-holder">
                    <img class="nav-contact-img" src="http://localhost/wwd-redesign/wp-content/uploads/2026/02/16-10-Waben.jpg" alt="">
                    <div class="img-transition-bottom"></div>
                </div>
                <ul class="nav-contacts">
                    <li onclick="window.location.href='<?php echo esc_url( home_url( '/kontakt/' ) ); ?>'">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/icons/form-blue.svg' ); ?>" alt="">
                        <p class="light mini-heading" href="">Kontaktformular</p>
                    </li>
                    <li>
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/icons/mail-thick-blue.svg' ); ?>" alt="">
                        <p class="light mini-heading" href="">office@wendlandwebdesign.de</p>
                    </li>
                    <li onclick="window.location.href='tel:+4915238976827'">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/icons/phone-thin-blue.svg' ); ?>" alt="">
                        <p class="light mini-heading" href="">0152 389 768 27</p>
                    </li>
                </ul>
            </div>
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
                    <li onclick="window.location.href='<?php echo esc_url( home_url( '/' ) ); ?>'" class="list-left-item <?php echo ( is_front_page() ) ? 'akt' : ''; ?>"><p class="light" href="">Home</p></li>
                    <li class="list-left-item expand-right <?php echo ( is_page('dienstleistungen') ) ? 'akt' : ''; ?>" data-nav-target="dienstleistungen">
                        <p class="light"><?php echo wwd_inline_svg( 'arrow-white.svg', array( 'class' => 'arrow', 'aria_hidden' => true ) ); ?>Dienstleistungen</p>
                        <?php echo wwd_inline_svg( 'mini-arrow.svg', array( 'class' => 'mini-arrow', 'aria_hidden' => true ) ); ?>
                    </li>
                    <li class="list-left-item expand-right <?php echo ( is_page('referenzen') ) ? 'akt' : ''; ?>" data-nav-target="referenzen">
                        <p class="light"><?php echo wwd_inline_svg( 'arrow-white.svg', array( 'class' => 'arrow', 'aria_hidden' => true ) ); ?>Referenzen</p>
                        <?php echo wwd_inline_svg( 'mini-arrow.svg', array( 'class' => 'mini-arrow', 'aria_hidden' => true ) ); ?>
                    </li>
                    <li onclick="window.location.href='<?php echo esc_url( home_url( '/ki-integration/' ) ); ?>'" class="list-left-item <?php echo ( is_page('ki-integration') ) ? 'akt' : ''; ?>"><p class="light" href="">KI Integration</p></li>
                    <li onclick="window.location.href='<?php echo esc_url( home_url( '/website-check/' ) ); ?>'" class="list-left-item <?php echo ( is_page('website-check') ) ? 'akt' : ''; ?>"><p class="light" href="">kostenloser Websitecheck</p></li>
                    <li class="list-left-item expand-right <?php echo ( is_page('news') ) ? 'akt' : ''; ?>" data-nav-target="news">
                        <p class="light"><?php echo wwd_inline_svg( 'arrow-white.svg', array( 'class' => 'arrow', 'aria_hidden' => true ) ); ?>News</p>
                        <?php echo wwd_inline_svg( 'mini-arrow.svg', array( 'class' => 'mini-arrow', 'aria_hidden' => true ) ); ?>
                    </li>
                <!--    <li onclick="window.location.href='<?php echo esc_url( home_url( '/ueber-uns/' ) ); ?>'" class="list-left-item <?php echo ( is_page('ueber-uns') ) ? 'akt' : ''; ?>"><p class="light" href="">Über uns</p></li>    -->
                    <li onclick="window.location.href='<?php echo esc_url( home_url( '/kontakt/' ) ); ?>'" class="list-left-item <?php echo ( is_page('kontakt') ) ? 'akt' : ''; ?>"><p class="light" href="">Kontakt</p></li>
                </ul>


                <div class="list-right-wrapper">
                    <div class="list-right">
                        <div class="list-right-content" data-nav-panel="dienstleistungen">
                            <div class="top-bar">
                                <div class="back-btn-wrapper">
                                    <?php echo wwd_inline_svg( 'arrow-white.svg', array( 'class' => 'back-btn', 'aria_hidden' => true ) ); ?>
                                </div>
                            </div>
                            <div class="list-right-content-wrapper">
                                <div class="right-buttons">
                                    <a class="light js-right-cta" href="<?php echo esc_url( home_url( '/dienstleistungen/' ) ); ?>">Alle Dienstleistungen <?php echo wwd_inline_svg( 'corner-arrow-light.svg', array( 'class' => 'corner-arrow', 'aria_hidden' => true ) ); ?></a>
                                </div>
                                <div class="right-cards">
                                    <?php
                                    $dienstleistungen_query = wwd_get_nav_dienstleistungen_query();
                                    if ( $dienstleistungen_query->have_posts() ) :
                                        while ( $dienstleistungen_query->have_posts() ) :
                                            $dienstleistungen_query->the_post();
                                            $card_link = get_post_meta( get_the_ID(), '_nav_card_link', true );
                                            $onclick_attr = '';
                                            if ( ! empty( $card_link ) ) {
                                                $onclick_attr = ' onclick="window.location.href=\'' . esc_url( $card_link ) . '\';"';
                                            }
                                            ?>
                                            <div class="nav-card nav-card--overlay nav-card--clickable nav-card--dienstleistungen js-right-card"<?php echo $onclick_attr; ?> role="link" tabindex="0">
                                                <div class="card-img-holder">
                                                    <?php
                                                    if ( has_post_thumbnail() ) {
                                                        echo wp_get_attachment_image( get_post_thumbnail_id(), 'medium', false, array( 'class' => 'card-img' ) );
                                                    } else {
                                                        ?>
                                                        <div class="nav-card__placeholder"></div>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                                <div class="nav-card__overlay nav-card__overlay--bottom-left">
                                                    <p class="nav-card__text-row"><?php echo esc_html( get_the_title() ); ?> <?php echo wwd_inline_svg( 'corner-arrow-light.svg', array( 'class' => 'corner-arrow', 'aria_hidden' => true ) ); ?></p>
                                                </div>
                                            </div>
                                            <?php
                                        endwhile;
                                        wp_reset_postdata();
                                    endif;
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="list-right-content" data-nav-panel="referenzen">
                            <div class="top-bar">
                                <div class="back-btn-wrapper">
                                    <?php echo wwd_inline_svg( 'arrow-white.svg', array( 'class' => 'back-btn', 'aria_hidden' => true ) ); ?>
                                </div>
                            </div>
                            <div class="list-right-content-wrapper">
                                <div class="right-buttons">
                                    <a class="light js-right-cta" href="<?php echo esc_url( home_url( '/referenzen/' ) ); ?>">Alle Referenzen <?php echo wwd_inline_svg( 'corner-arrow-light.svg', array( 'class' => 'corner-arrow', 'aria_hidden' => true ) ); ?></a>
                                </div>
                                <div class="right-cards">
                                    <?php
                                    $referenzen_query = new WP_Query(
                                        array(
                                            'post_type'      => 'referenzen',
                                            'posts_per_page' => 4,
                                            'orderby'        => 'date',
                                            'order'          => 'DESC',
                                        )
                                    );
                                    if ( $referenzen_query->have_posts() ) :
                                        while ( $referenzen_query->have_posts() ) :
                                            $referenzen_query->the_post();
                                            $card_link = get_post_meta( get_the_ID(), '_nav_card_link', true );
                                            $onclick_attr = '';
                                            if ( ! empty( $card_link ) ) {
                                                $onclick_attr = ' onclick="window.location.href=\'' . esc_url( $card_link ) . '\';"';
                                            }
                                            ?>
                                            <div class="nav-card nav-card--clickable js-right-card"<?php echo $onclick_attr; ?> role="link" tabindex="0">
                                                <div class="card-img-holder">
                                                    <?php
                                                    if ( has_post_thumbnail() ) {
                                                        echo wp_get_attachment_image( get_post_thumbnail_id(), 'medium', false, array( 'class' => 'card-img' ) );
                                                    } else {
                                                        ?>
                                                        <div class="nav-card__placeholder nav-card__placeholder--light"></div>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                                <p class="light nav-card__text-row"><?php echo esc_html( get_the_title() ); ?> <?php echo wwd_inline_svg( 'corner-arrow-light.svg', array( 'class' => 'corner-arrow', 'aria_hidden' => true ) ); ?></p>
                                            </div>
                                            <?php
                                        endwhile;
                                        wp_reset_postdata();
                                    endif;
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="list-right-content" data-nav-panel="news">
                            <div class="top-bar">
                                <div class="back-btn-wrapper">
                                    <?php echo wwd_inline_svg( 'arrow-white.svg', array( 'class' => 'back-btn', 'aria_hidden' => true ) ); ?>
                                </div>
                            </div>
                            <div class="list-right-content-wrapper">
                                <div class="right-buttons">
                                    <a class="light js-right-cta" href="<?php echo esc_url( home_url( '/news/' ) ); ?>">Alle News <?php echo wwd_inline_svg( 'corner-arrow-light.svg', array( 'class' => 'corner-arrow', 'aria_hidden' => true ) ); ?></a>
                                </div>
                                <div class="right-cards">
                                    <?php
                                    $news_query = wwd_get_news_query();
                                    if ( $news_query->have_posts() ) :
                                        while ( $news_query->have_posts() ) :
                                            $news_query->the_post();
                                            ?>
                                            <div class="nav-card nav-card--clickable js-right-card" onclick="window.location.href='<?php echo esc_url( get_permalink() ); ?>';" role="link" tabindex="0">
                                                <div class="card-img-holder">
                                                    <?php
                                                    if ( has_post_thumbnail() ) {
                                                        echo wp_get_attachment_image( get_post_thumbnail_id(), 'medium', false, array( 'class' => 'card-img' ) );
                                                    } else {
                                                        ?>
                                                        <div class="nav-card__placeholder"></div>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                                <p class="light nav-card__text-row"><?php echo esc_html( get_the_title() ); ?> <?php echo wwd_inline_svg( 'corner-arrow-light.svg', array( 'class' => 'corner-arrow', 'aria_hidden' => true ) ); ?></p>
                                            </div>
                                            <?php
                                        endwhile;
                                        wp_reset_postdata();
                                    endif;
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>


