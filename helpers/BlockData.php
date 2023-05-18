<?php

class BlockData {
    private array $block;
    private array $options;
    private array $dataTypesSelection;

    // Functions relevant to availabale ACF fields
    private $dataTypes = [
        "id" => "getHtmlID",
        "class" => "getCssClass",
        "paddingWP" => "getSpacing",
        "marginWP" => "getSpacing",
        "backgroundWP" => "getBackground",
        "textColorWP" => "getTextColor",
    ];

    // Default values
    public $data = [
        "id",
        "class",
        "spacingWP" => [
            "padding" => "",
            "margin" => ""
        ],
        "backgroundWP",
        "textColorWP",
    ];

    

    function __construct($block, $options) {
        // Neccessary args
        $this->block = $block;

        // Set options 
        $this->options = $options["options"];
        $this->dataTypesSelection = $options["options"]["data"];

        $this->setBlockData();
    }

    private function setBlockData() {
        // Default for every block
        $this->data["id"] = $this->getHtmlID();
        $this->data["class"] = $this->getCssClass($this->options["class"]);

        // For every option specified, set data
        foreach ($this->dataTypesSelection as $key => $value) {
            $field = is_array($value) ? $key : $value;
            $args = is_array($value) ? $value : null;

            if($field == "paddingWP" || $field == "marginWP") {
                $field = str_replace("WP", "", $field);
                $this->data["spacingWP"][$field] = $this->getSpacing($field);
                continue;
            }

            // Call specified function, additionaly add $args if necessary
            $this->data[$field] = $args === null ? 
            call_user_func([$this, $this->dataTypes[$field]]) : 
            call_user_func([$this, $this->dataTypes[$field]], $args);
        }
    }

    /**
     * getHtmlID
     *
     * @param  mixed $block
     * @return string
     */
    private function getHtmlID() : string {
        $block = $this->block;

        // Create id attribute allowing for custom "anchor" value.
        $id = $block['id'];
        if( !empty($block['anchor']) ) {
            $id = $block['anchor'];
        }

        return esc_attr($id);
    }
    
    /**
     * getCssClass
     *
     * @param  mixed $block
     * @param  mixed $additionalCssClass
     * @return string
     */
    private function getCssClass($additionalCssClass) : string {
        $block = $this->block;

        // Create class attribute allowing for custom "className" and "align" values.
        $className = "argo22-block " . $additionalCssClass;
        if( !empty($block['className']) ) {
            $className .= ' ' . $block['className'];
        }
        if( !empty($block['align']) ) {
            $className .= ' align' . $block['align'];
        }

        return esc_attr($className);
    }

    /**
     * getSpacing
     *
     * @param  mixed $block
     * @param  mixed $spacingType
     * @return array
     */
    private function getSpacing($type) : string {
        $block = $this->block;
        $sides = ["top", "right", "bottom", "left"];

        // If this block has enabled spacing in editor
        if( isset($block["style"]) && is_array($block["style"])
            && isset($block["style"]["spacing"][$type])) {

            $spacing = $block["style"]["spacing"][$type];

            $css = "";
            foreach ($sides as $side) {
                if(!empty($spacing[$side])) {
                    // For predefined presets in editor
                    if(str_contains($spacing[$side], "var:")) 
                        $css .= "$type-$side: " . $this->convertSpacingPresetVars($spacing[$side]) . ";";
                    // Classic values
                    else
                        $css .= "$type-$side: {$spacing[$side]};";
                }
            }

            return trim($css);
        }
        else {
            return "$type: 0;";
        }
    }

    /**
     * getBackground
     *
     * @return string
     */
    private function getBackground() : string {
        $block = $this->block;

        // Solid colors in palette and predefined solid colors (vivid-red etc.)
        if(isset($block["backgroundColor"])) {
            return "var(--wp--preset--color--" . $block["backgroundColor"] . ")";
        }

        // Predefined gradients
        if(isset($block["gradient"])) {
            return "var(--wp--preset--gradient--" . $block["gradient"] . ")";
        }


        // Custom colors and gradients
        if( isset($block["style"]) && is_array($block["style"])) {
            if(isset($block["style"]["color"]["gradient"])) {
                return $block["style"]["color"]["gradient"];
            }
            else if(isset($block["style"]["color"]["background"])) {
                return $block["style"]["color"]["background"];
            }
            else {
                return "transparent";
            }
        }
        else {
            return "transparent";
        }

    }

    /**
     * getTextColor
     *
     * @return string
     */
    private function getTextColor() : string {
        $block = $this->block;

        // Solid colors in palette and predefined solid colors (vivid-red etc.)
        if(isset($block["textColor"])) {
            return "var(--wp--preset--color--" . $block["textColor"] . ")";
        }

        if( isset($block["style"]) && is_array($block["style"]) &&
            isset($block["style"]["color"]["text"]))
            {
            return $block["style"]["color"]["text"];
        }
        else {
            return "black";
        }
    }




    /*--------------------------------
	    Additional functions
    --------------------------------*/
    /**
     * convertSpacingPresetVars
     *
     * @param  mixed $block
     * @return string
     */
    private function convertSpacingPresetVars($preset) : string {
        /* 
            this function converts strings like "var:preset|spacing|30" 
            to "var(--wp--preset--spacing--30)"
        */

        // remove "var:"
        $cutPreset = str_replace("var:", "", $preset);

        // Divide into parts by "|" char
        $parts = explode("|", $cutPreset);

        // Setup result by combining parts and wrapping it in "var()"
        $res = "var(--wp";
        foreach ($parts as $part) {
            $res .= "--" . $part;
        }
        $res .= ")";

        return $res;

    }
}