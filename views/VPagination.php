<?php

class VPagination {
    //Dependencies
    private $CPagination;
    
    public function __construct(CPagination $CPagination) {
        $this->CPagination = $CPagination;
    }
    
    public function DisplayPagination() { ?>
        <span class="pagination_left">
            
            <span class="first_page">
                <?php if($this->CPagination->current_page !== 1) : ?>
                    <a href="index.php?page=1">
                <?php endif; ?>
                    &lt;&lt; First Page
                <?php if($this->CPagination->current_page !== 1) : ?>
                    </a>
                <?php endif; ?>
            </span>
            
            <span class="previous_page">
                <?php if($this->CPagination->previous_page) : ?> 
                    <a href="index.php?page=<?php echo $this->CPagination->previous_page; ?>">
                <?php endif; ?>
                    &lt; Previous Page
                <?php if($this->CPagination->previous_page) : ?>
                    </a>
                <?php endif; ?>
            </span>
            
        </span>

        <span class="pagination_center"> Page
            <?php for($i = 1; $i <= $this->CPagination->total_pages; $i++) {
                
                        //Don't make a link the page that we're already on
                        if($i == $this->CPagination->current_page) {
                            echo $i . " ";
                        }
                        
                        //Make all other pages a link
                        else {
                            echo "<a href='index.php?page={$i}'>{$i}</a> ";
                        }
                    } ?>
        </span>

        <span class="pagination_right">
            
            <span class="next_page">
                <?php if($this->CPagination->next_page) : ?>
                    <a href="index.php?page=<?php echo $this->CPagination->next_page; ?>">
                <?php endif; ?>
                        Next Page &gt;
                <?php if($this->CPagination->next_page) : ?>
                    </a>
                <?php endif; ?>
            </span>
            
            <span class="last_page">
                <?php if($this->CPagination->total_pages > $this->CPagination->current_page) : ?>
                    <a href="index.php?page=<?php echo $this->CPagination->total_pages; ?>"> 
                <?php endif; ?>
                    Last Page &gt;&gt;
                <?php if($this->CPagination->total_pages > $this->CPagination->current_page) : ?>
                    </a>
                <?php endif; ?>
            </span>
            
            <!-- To be implemented
            <span class="rpp_select">Show
                <select>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </span>-->
            
        </span>
<?php
    }  //end of DisplayPagination()
} //end of class