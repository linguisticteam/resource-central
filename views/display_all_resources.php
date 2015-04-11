<?php
require_once(dirname(dirname(__FILE__)) . '/helpers/class_loader.php');

class ViewDisplayAllResources {
    public $Database;
    
    public function __construct(Database $Database) {
        $this->Database = $Database;
    }
    
    public function DisplayAll() {
        
        /* Get and display the resources */
        
        $result = $this->Database->GetResources();
        $output = '';
        
        while($row = $result->fetch_array()) {
            $title = htmlspecialchars($row['title']);
            $resource_type = htmlspecialchars($row['resource_type']);
            $description = htmlspecialchars($row['description']);
            $output .= "<h3>" . $title . "</h3>";
            $output .= "<div>Type: " . ucwords(strtolower($resource_type));
            $output .= ", Description: " . $description . "</div>";
            
            
            /* Get and display the keywords */
            
            $keywords_result = $this->Database->GetKeywords((int) $row['resource_id']);
            $row_count = $keywords_result->num_rows;
            
            $output .= "<div>Keywords: ";
            
            for($i = 0; $i < $row_count; $i++) {
                $keywords_row = $keywords_result->fetch_array();
                $keyword = htmlspecialchars($keywords_row['keyword']);
               
                //Put a comma on every iteration after the first one
                if($i != 0) {$output .= ", ";}
                $output .= $keyword;
            }
            
            $output .= "</div>";
        
        
            /* Get and display URLs (this is to be expanded to more than just URLs in the future) */

            $urls_result = $this->Database->GetResourceURLs((int) $row['resource_id']);

            $output .= "<div>URL: ";

            while($urls_row = $urls_result->fetch_array()) {
                $url = htmlspecialchars($urls_row['url']);
                $output .= $url;
            }

            $output .= "</div>";

            
        }
        
        echo $output;
    }
}

$ViewDisplayAllResources = new ViewDisplayAllResources($Database);
?>

