<?php
require_once(dirname(dirname(__FILE__)) . '/helpers/class_loader.php');

class VDisplayResources {
    private $Database;
    private $CPagination;
    private $Parsedown;
    
    public function __construct(Database $Database, CPagination $CPagination, Parsedown $Parsedown) {
        $this->Database = $Database;
        $this->CPagination = $CPagination;
        $this->Parsedown = $Parsedown;
    }
    
    /* Get and display the resources */
    public function DisplayResources($resource_IDs) {
        
        //Variables for the pagination
        $limit_offset = $this->CPagination->limit_offset;
        $limit_maxNumRows = $this->CPagination->limit_maxNumRows;
        
        //Get resources
        $result = $this->Database->GetResources((int) $limit_offset, (int) $limit_maxNumRows, $resource_IDs);
        
        //If nothing is returned, say so and end here
        if(!$result) {
            echo 'No results.';
            return;
        }
        
        //Initiate the variable that will contain all the output
        $output = '';
        
        //Display the resources one by one
        while($row = $result->fetch_array()) {
            $output .= '<div class="resource">';
            
            $title = htmlspecialchars($row['title']);
            $resource_type = htmlspecialchars($row['resource_type']);
            $description = htmlspecialchars($row['description']);
            $publishing_date = htmlspecialchars($row['publishing_date']);
            
            $output .= "<h3>" . $title . "</h3>";
            $output .= "<table class='resource_info'><tbody>";
            $output .= "<tr>Type: " . ucwords(strtolower($resource_type)) . '</tr>';
            $output .= ' <tr>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" class="tooltip">'
                    . '<img src="img/information-icon-small.png">'
                    . '<span><img class="callout" src="img/callout_black.gif" />'
                    . '<strong>Description</strong><br />' . $this->Parsedown->text($description)
                    . '</span></a></tr>';
            
            /* Get and display the URLs */
            $output .= $this->DisplayURLs((int) $row['resource_id']);  
                    
            //Clear the float
            $output .= '<div class="clear"></div>';
            
            /* Display the publishing date, if it exists */
            $output .= $publishing_date ? "<tr>Publishing Date: {$publishing_date}</tr>" : '';
           
            /* Get and display the authors */
            $output .= $this->DisplayAuthors((int) $row['resource_id']);

            /* Get and display the keywords */
            $output .= $this->DisplayKeywords((int) $row['resource_id']);
            
            $output .= '</div>';  //close the class="resource" div
        }
        
        echo $output;
    }
    
    
    /* Get and display URLs for a single resource (this is to be expanded to more than just URLs in the future) */
    public function DisplayURLs($resource_id) {

        $urls_result = $this->Database->GetURLsForResource($resource_id);

        $output = "<tr>";
        $output .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

        while($urls_row = $urls_result->fetch_array()) {
            $url = htmlspecialchars($urls_row['url']);
            $output .= '<a href="' . $url . '" target="_blank">See it!</a>';
        }

        $output .= "</tr></tbody></table>";
        return $output;
    }
    
    /* Get and display the authors for a single resource */
    public function DisplayAuthors($resource_id) {
            
        $authors_result = $this->Database->GetAuthorsForResource($resource_id);
        $row_count = $authors_result->num_rows;

        $output = "<div class='authors'>Author(s): ";

        for($i = 0; $i < $row_count; $i++) {
            $authors_row = $authors_result->fetch_array();
            $author = htmlspecialchars($authors_row['full_name']);
            $author_type = htmlspecialchars($authors_row['author_type']);

            //Put a comma on every iteration after the first one
            if($i != 0) {$output .= ", ";}

            $output .= $author . ' (' . ucwords(strtolower($author_type)) . ')';
        }

        $output .= "</div>";
        
        return $output;
    }
        
     /* Get and display the keywords for a single resource */
    public function DisplayKeywords($resource_id) {
        $keywords_result = $this->Database->GetKeywordsForResource($resource_id);
        $row_count = $keywords_result->num_rows;

        $output = "<div class='keywords'>";
        $output .= "<ul class='tags-list'><span class='tags-text'>Keywords:</span>  ";

        for($i = 0; $i < $row_count; $i++) {
            $keywords_row = $keywords_result->fetch_array();
            $keyword = htmlspecialchars($keywords_row['keyword']);          

            $output .= "<li><a href='#' class='tag'>{$keyword}</a></li>";
        }

        $output .= "</ul>";
        $output .= "</div>";
        
        return $output;
    }
}