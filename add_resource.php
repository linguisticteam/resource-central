<?php
define('START', true);

require_once('lib/config.php');
require_once('/lib/database.php');
?>
<h2>Add a new resource:</h2>
<form method="post" action="lib/add_entry.php" name="add_resource">
    Resource Title: <input type="text" name="title"><br><br>
    Resource Type:  <select name="resource_type">
                                <option value="tutorial">Tutorial</option>
                                <option value="documentation">Documentation</option>
                            </select>
    <br><br>
    Resource Description: <br>
    <textarea name="description"></textarea>
    <br><br>
    <input type="submit" value="submit">
</form>