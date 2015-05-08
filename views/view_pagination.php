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
                        if($i == $this->CPagination->current_page) {
                            echo $i . " ";
                        }
                        else {
                            echo "<a href='index.php?page={$i}'>{$i}</a> ";
                        }
                    } ?>
        </span>
        <span class="pagination_right">
            <a href="index.php?page=<?php echo $this->CPagination->next_page; ?>"> Next Page &gt;</a>
            <span class="last_page">
                <a href="index.php?page=<?php echo $this->CPagination->total_pages; ?>"> Last Page &gt;&gt;</a>
            </span>
        </span>
<?php
    }  //end of DisplayPagination()
} //end of class