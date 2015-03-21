<?php
define('START', true);

require_once('lib/config.php');
require_once('/lib/database.php');
?>
<h2>Add a new resource element:</h2>
<form method="post" action="lib/add_entry.php" name="add_resource_element">
    Element Title: <input type="text" name="title"><br><br>
    Element Type: <select name="element_type">
                                <option value="primary">Primary</option>
                                <option value="part">Part</option>
                                <option value="lesson">Lesson</option>
                                <option value="chapter">Chapter</option>
                            </select>
    <br><br>
    Element URL: <input type="text" name="url"><br><br>
    Element Author: <input type="text" name="author"><br><br>
    Author Type: <select name="element_type">
                                <option value="person">Person</option>
                                <option value="organization">Organization</option>
                            </select>    
    <br><br>
    <input type="submit" value="submit">
</form>
