<?php 
    // Load WP native values
    $bD = new BlockData( 
        $block,
        [
            "options" => [
                "class" => "team-member-detail",
                "data" => [
                    "paddingWP", 
                    "marginWP", 
                    "backgroundWP",
                    "textColorWP"
                ]
            ]
        ]
    );


    // If not in preview, prepare spacing
    $spacingCSS = $is_preview ?
                    "" : 
                    "{$bD->data['spacingWP']['padding']}
                    {$bD->data['spacingWP']['margin']}";

    
                    // Create style, WP native values
    echo "
        <style>
            [data-block-id='{$block['id']}'] {
                background: {$bD->data['backgroundWP']};
                color: {$bD->data['textColorWP']};
                
                $spacingCSS
            }

            [data-block-id='{$block['id']}'] a {
                color: {$bD->data['textColorWP']};
            }
        </style>
    ";

    // Get values
    $member = get_field("team_member");
    $enabledValues = get_field("show_values")["values"];
?>

<!-- Render block -->
<div id="<?php echo $bD->data['id'] ?>" data-block-id="<?php echo $block["id"]; ?>" class="<?php echo $bD->data['class']; ?> w-100 container-fluid" >  
    
    <div class="row">
        <!-- Thumbnail -->
        <?php 
            if(in_array("thumbnail", $enabledValues)) {
                ?>
                    <div class="col-12 col-lg mb-5 mb-lg-0">
                        <?php echo get_the_post_thumbnail($member->ID, "large", ['class' => 'member-thumbnail' ]);?>
                    </div>
                <?php
            }
        ?>

        <!-- Name, position, desc & contact details -->
        <div class="col-12 col-lg d-flex flex-column justify-content-center">
            <?php 
                if(in_array("title", $enabledValues)) {
                    ?>
                        <h3 class="member-name h2 mb-1">
                            <?php echo $member->post_title; ?>
                        </h3>
                    <?php
                }

                if(in_array("position", $enabledValues)) {
                    ?>
                        <div class="member-position">
                            <span class="h5">
                                <?php echo get_field("position", $member); ?>
                            </span>
                        </div>
                    <?php
                }

                if(in_array("description", $enabledValues)) {
                    ?>
                        <div class="member-description mt-4">
                            <span>
                                <?php echo wp_trim_words($member->post_content, 20); ?>
                            </span>
                        </div>
                    <?php
                }
            ?>

            <!-- Contact details -->
            <div class="mt-4">
                <?php 
                    if(in_array("phone_number", $enabledValues)) {
                        $telLink = str_replace(' ', '', htmlspecialchars(get_field("phone_number", $member)));
                        ?>
                            <div class="my-2">
                                <a href="tel:<?php echo $telLink; ?>">
                                    <svg class="me-3" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
                                    </svg><?php echo get_field("phone_number", $member); ?>
                                </a>
                            </div>
                        <?php
                    }
                ?>

                <?php 
                    if(in_array("e-mail", $enabledValues)) {
                        $emailLink = htmlspecialchars(get_field("phone_number", $member));
                        ?>
                            <div class="my-2">
                                <a href="mailto:<?php echo $emailLink; ?>">
                                    <svg class="me-3" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-at-fill" viewBox="0 0 16 16">
                                        <path d="M2 2A2 2 0 0 0 .05 3.555L8 8.414l7.95-4.859A2 2 0 0 0 14 2H2Zm-2 9.8V4.698l5.803 3.546L0 11.801Zm6.761-2.97-6.57 4.026A2 2 0 0 0 2 14h6.256A4.493 4.493 0 0 1 8 12.5a4.49 4.49 0 0 1 1.606-3.446l-.367-.225L8 9.586l-1.239-.757ZM16 9.671V4.697l-5.803 3.546.338.208A4.482 4.482 0 0 1 12.5 8c1.414 0 2.675.652 3.5 1.671Z"/>
                                        <path d="M15.834 12.244c0 1.168-.577 2.025-1.587 2.025-.503 0-1.002-.228-1.12-.648h-.043c-.118.416-.543.643-1.015.643-.77 0-1.259-.542-1.259-1.434v-.529c0-.844.481-1.4 1.26-1.4.585 0 .87.333.953.63h.03v-.568h.905v2.19c0 .272.18.42.411.42.315 0 .639-.415.639-1.39v-.118c0-1.277-.95-2.326-2.484-2.326h-.04c-1.582 0-2.64 1.067-2.64 2.724v.157c0 1.867 1.237 2.654 2.57 2.654h.045c.507 0 .935-.07 1.18-.18v.731c-.219.1-.643.175-1.237.175h-.044C10.438 16 9 14.82 9 12.646v-.214C9 10.36 10.421 9 12.485 9h.035c2.12 0 3.314 1.43 3.314 3.034v.21Zm-4.04.21v.227c0 .586.227.8.581.8.31 0 .564-.17.564-.743v-.367c0-.516-.275-.708-.572-.708-.346 0-.573.245-.573.791Z"/>
                                    </svg><?php echo get_field("e-mail", $member); ?>
                                </a>
                            </div>
                        <?php
                    }
                ?>
            </div>
        </div>
    </div>
</div>