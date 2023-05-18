<?php 
    // Load WP native values
    $bD = new BlockData( 
        $block,
        [
            "options" => [
                "class" => "team-member-grid",
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

    // Prepare values
    $members = get_field("members");

    $columnGrid = get_field("number_of_columns");
    $columnGrid = $columnGrid == null ? "col-12 col-sm-6" : $columnGrid; // If not value selected, set it to 2 column grid

    $titleSizes = [
        "col-12 col-sm-6" => "h3",
        "col-12 col-sm-6 col-xl-4" => "h4",
        "col-12 col-sm-6 col-xl-3" => "h5",
    ];
    $titleSize = $titleSizes[$columnGrid];

    $showMemberPosition = get_field("show_members_position");

?>

<!-- Render block -->
<div id="<?php echo $bD->data['id'] ?>" data-block-id="<?php echo $block["id"]; ?>" class="<?php echo $bD->data['class']; ?> w-100 container-fluid" >  

    <div class="row">
        <?php
            foreach ($members as $member) {
                ?>
                    <div class="<?php echo $columnGrid; ?> text-center my-3">
                        <div class="thumb-wrap mb-4">
                            <?php 
                                if(has_post_thumbnail($member)) {
                                    echo get_the_post_thumbnail($member->ID, "large");
                                }
                                else {
                                    ?>
                                        <img src="<?php echo get_template_directory_uri() . "/assets/img/default-img.jpg" ?>" alt="Default Image" loading="lazy">
                                    <?php
                                }
                            ?>

                        </div>

                        <h3 class="<?php echo $titleSize; ?> px-2">
                            <?php echo $member->post_title; ?>
                        </h3>

                        <div class="mt-2">
                            <span>
                                <?php echo get_field("position", $member); ?>
                            </span>
                        </div>
                    </div>
                <?php
            }
        ?>
    </div>

</div>