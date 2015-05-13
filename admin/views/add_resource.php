<?php
define('START', true);
require_once(dirname(dirname(dirname(__FILE__))) . '/helpers/class_loader.php');

//Specify the encoding
header('Content-Type: text/html; charset=utf-8');


class ViewAddResource {
    private $database;
    public $resource_types = array();
    public $author_types = array();
    
    public function __construct(Database $Database) {
        $this->database = $Database;
        $this->resource_types = $Database->GetTypes('resource_type', 'name');
        $this->author_types = $Database->GetTypes('author_type', 'name');
    }
}

$ViewAddResource = new ViewAddResource($Database);
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
                                } ?>
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
