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
            $output .= '<div class="resource">';
            
            $title = htmlspecialchars($row['title']);
            $resource_type = htmlspecialchars($row['resource_type']);
            $description = htmlspecialchars($row['description']);
            $output .= "<h3>" . $title . "</h3>";
            $output .= "<table class='resource_info'><tbody><tr>Type: " . ucwords(strtolower($resource_type)) . '</tr>';
            $output .= ' <tr>&nbsp;&nbsp;&nbsp;<a href="#" class="tooltip">'
                    . '<img src="img/information-icon-small.png">'
                    . '<span><img class="callout" src="img/callout_black.gif" />'
                    . '<strong>Description</strong><br />' . $description
                    . '</span></a></tr>';
            
                        
            /* Get and display URLs (this is to be expanded to more than just URLs in the future) */

            $urls_result = $this->Database->GetResourceURLs((int) $row['resource_id']);

            $output .= "<tr>";
            $output .= "&nbsp;&nbsp;&nbsp;";

            while($urls_row = $urls_result->fetch_array()) {
                $url = htmlspecialchars($urls_row['url']);
                $output .= '<a href="' . $url . ' target="_blank">See it!</a>';
            }

            $output .= "</tr></tbody></table>";
            
            //Clear the float
            $output .= '<div class="clear"></div>';

            
            /* Get and display the authors */
            
            $authors_result = $this->Database->GetAuthors((int) $row['resource_id']);
            $row_count = $authors_result->num_rows;
            
            $output .= "<div class='authors'>Author(s): ";
            
            for($i = 0; $i < $row_count; $i++) {
                $authors_row = $authors_result->fetch_array();
                $author = htmlspecialchars($authors_row['full_name']);
                $author_type = htmlspecialchars($authors_row['author_type']);
                
                //Put a comma on every iteration after the first one
                if($i != 0) {$output .= ", ";}
                
                $output .= $author . ' (' . ucwords(strtolower($author_type)) . ')';
            }
            
            $output .= "</div>";
            
            
            /* Get and display the keywords */
            
            $keywords_result = $this->Database->GetKeywords((int) $row['resource_id']);
            $row_count = $keywords_result->num_rows;
            
            $output .= "<div class='keywords'>Keywords: ";
            
            for($i = 0; $i < $row_count; $i++) {
                $keywords_row = $keywords_result->fetch_array();
                $keyword = htmlspecialchars($keywords_row['keyword']);
               
                //Put a comma on every iteration after the first one
                if($i != 0) {$output .= ", ";}
                
                $output .= $keyword;
            }
            
            $output .= "</div>";
            
            
            $output .= '</div>';  //close the class="resource" div
        }
        
        echo $output;
    }
}

$ViewDisplayAllResources = new ViewDisplayAllResources($Database);
?>