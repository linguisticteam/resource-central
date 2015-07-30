<?php
define('START', true);
require_once(dirname(dirname(dirname(__FILE__))) . '/helpers/class_loader.php');

//Load DICE and configure it to use only one instance of every object
$Dice = new \Dice\Dice;
$rule = new \Dice\Rule;
$rule->shared = true;
$Dice->addRule('*', $rule);

//Specify the encoding
header('Content-Type: text/html; charset=utf-8');


class ViewAddResource {
    private $MDatabase;
    public $resource_types = array();
    public $author_types = array();
    
    public function __construct(MDatabase $MDatabase) {
        $this->MDatabase = $MDatabase;
        $this->resource_types = $MDatabase->GetTypes('resource_type', 'name');
        $this->author_types = $MDatabase->GetTypes('author_type', 'name');
    }
}

$ViewAddResource = $Dice->create('ViewAddResource');
?>

<link rel="stylesheet" type="text/css" href="../../../css/main.css">
<h2>Add a new resource:</h2>
<form method="post" action="../controllers/form_processing.php" name="add_resource" accept-charset="utf-8">
    <div style="display:none;">Is this a resource with multiple elements? <br>
    <input type="radio" name="multi_element" value="yes"> Yes
    <input type="radio" name="multi_element" value="no"> No
    <hr>
    </div>
    <div style="float:left;">
    <em>[Single element case]</em><br><br>
    Resource Title*: <input type="text" name="title"><br><br>
    Resource Type*:  <select name="resource_type">
                                <option value="">Please Select</option>
                                <?php foreach($ViewAddResource->resource_types as $type) {
                                        echo '<option value="' . $type . '">' . ucwords(strtolower($type)) . '</option>';
                                    }  ?>
                            </select>
    <br><br>
    Resource URL*: <input type="text" name="url"><br><br>
    <div id="author_template" class="author_template">
        Resource Author: <input type="text" name="resource_author_0" class="resource_author">
        Author Type:  <select name="author_0_type" class="author_type">
                                    <option value="">Please Select</option>
                                <?php foreach($ViewAddResource->author_types as $type) {
                                    echo '<option value="' . $type . '">' . ucwords(strtolower($type)) . '</option>';
                                } ?>
                                </select>
    </div>
    <div id="additional_authors">
    </div>
    <br><br>
    <a href="#" id="add_another_author">+ Add another author</a>
    <br><br><br>
    Keywords*: <em>(separate them with commas)</em><br>
    <textarea name="keywords" rows="2" cols="50"></textarea>  <br><br>
     
    <div id="publishing_date">
        Publishing Date: <br>
        <input type="text" size="5" value="" name="publishing_year" placeholder="Year">
        
        <select name="publishing_month">
            <option value="" selected>Month</option>
            <option value="1">January</option>
            <option value="2">February</option>
            <option value="3">March</option>
            <option value="4">April</option>
            <option value="5">May</option>
            <option value="6">June</option>
            <option value="7">July</option>
            <option value="8">August</option>
            <option value="9">September</option>
            <option value="10">October</option>
            <option value="11">November</option>
            <option value="12">December</option>
        </select>
        <select name="publishing_day">
            <option value='' selected>Day</option>
            <?php 
                for($i = 1; $i <=31; $i++) {
                    echo "<option value='{$i}'>{$i}</option>";
                }
            ?>
        </select>
<!--        <select name="date11_month"></select>
        <select name="date11_date"></select>
        <input type="text" size="5" value="" name="date11_year">
        <a href="#" onclick="cal11.showCalendar('anchor11'); return false;" 
           title="cal11.showCalendar('anchor11'); return false;" 
           name="anchor11" id="anchor11">select</a>-->
    </div>
    <div><em>You can optionally enter the year (and/or month/day) of publication of this resource</em></div> <br><br> 
    Description*: <em>(you can use <a href="//help.github.com/articles/markdown-basics/" target="_blank">Github-flavoured markdown</a>)</em><br>
    <textarea name="description" rows="8" cols="50"></textarea>
    <br><br>
    <input type="submit" value="submit">
    <p>&nbsp;</p>
</form>
</div>
    
    
    
    
<div style='display:none; float:left; margin-left:60px;'>
<em>[Multiple elements case]</em>
<br><br>
    Resource Title*: <input type="text" name="title"><br><br>
    Resource Type*:  <select name="resource_type">
                                <option value="default">Please Select</option>
                                <option value="tutorial">Tutorial</option>
                                <option value="documentation">Documentation</option>
                            </select>
    <br><br>
    Keywords*: <em>(separate them with commas)</em><br>
    <textarea name="url" rows="2" cols="50"></textarea>  <br><br> 
    Description*: <br>
    <textarea name="description" rows="8" cols="50"></textarea>
    <br><br>
    What type of elements is this resource divided into?<br>
    Elements Type: <select name="element_type">
                                <option value="default">Please Select</option>
                                <option value="parts">Parts</option>
                                <option value="lessons">Lessons</option>
                                <option value="chapters">Chapters</option>
                            </select>
    <br><br>
    Part 1: [or lesson/chapter, depending on selection above]<br>
    Title: <input type="text" name="part1_title"><br><br>
    URL: <input type="text" name="part1_url"><br><br>
    Description <input type="text" name="element_description"><br><br>
    Author: <input type="text" name="url">
    Author Type:  <select name="author_type">
                                <option value="default">Please Select</option>
                                <option value="person">Person</option>
                                <option value="organization">Organization</option>
                            </select>
    <br><br>
    <a href="">+ Add another author</a>
    <br><br>
    <input type="submit" value="submit">
</div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="../../js/main.js" type="text/javascript"></script>
