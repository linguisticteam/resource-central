$(function(){

    /* --- Event handlers --- */

    $("#add_another_author").click(function() {

        /* Define local enumerators */

        var AUTHOR_FIELD = {
            NAME: 0,
            TYPE: 1
        };

        /* Declare local variables */

        var author = {
            clone: null,
            attr: {
                name_field_old_name: "",
                name_field_new_name: "",
                type_field_old_name: "",
                type_field_new_name: ""
            },
            additional: {
                object: null,
                count: 0
            }
        };

        /* Initialize local variables */

        // (DOM) Get divs by id
        author.additional.object = $("#additional_authors");
        author.clone = $("#author_template").clone();
        
        /* Execute */

        // Get the Name attribute of the first author div
        author.attr.name_field_old_name = author.clone[0].children[AUTHOR_FIELD.NAME].name
        author.attr.type_field_old_name = author.clone[0].children[AUTHOR_FIELD.TYPE].name

        // Get count of additional author divs already added
        author.additional.count = author.additional.object.children().length;

        // Create new name attribute based on how many additional authors already exist
        author.attr.name_field_new_name = IncrementNumeralInString(author.attr.name_field_old_name, 2, author.additional.count);
        author.attr.type_field_new_name = IncrementNumeralInString(author.attr.type_field_old_name, 1, author.additional.count);

        // Assign new name attribute to the clone
        author.clone[0].children[AUTHOR_FIELD.NAME].name = author.attr.name_field_new_name;
        author.clone[0].children[AUTHOR_FIELD.TYPE].name = author.attr.type_field_new_name;

        // Remove the ID from the cloned div
        author.clone[0].removeAttribute("id");
        
        // Insert clone into group of additional authors
        author.additional.object.append(author.clone);

        /* Final */

        // Return nothing
        return;

        /* --- Local Functions --- */
        
        function IncrementNumeralInString(sString, iElementIndex, iCount) {
            
            /* Declare local variables */
            
            var aStringParts;
            var sNewString;

            /* Execute */

            // Split string up into an array, for easier manipulation
            aStringParts = sString.split("_");

            // Do some arithmetic, and replace the specified element in the array
            aStringParts[iElementIndex] = iCount + 1;

            // Reconstruct new string from the altered array
            sNewString = aStringParts.join("_");

            /* Final */

            // Return the new string
            return sNewString;
        }
    });
});
