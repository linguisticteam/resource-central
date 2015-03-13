$(function () {

    /* --- Event Listeners --- */
    
    $("#add_another_author").click(addAnotherAuthor);
    
    
    /* --- Event handlers --- */

    function addAnotherAuthor() {
        
        /* Abbreviations */
        
        // RAUTH = Resource Author field
        // ATYPE = Author Type field


        /* Define local enumerators */

        var AUTHOR_FIELD = {
            RAUTH: 0,
            ATYPE: 1
        };

        /* Declare local variables */

        var author = {
            clone: null,
            attr: {
                RAuthOldNameAttr: "",
                RAuthNewNameAttr: "",
                ATypeOldNameAttr: "",
                ATypeNewNameAttr: ""
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

        // Get the name attributes of the author template fields
        author.attr.RAuthOldNameAttr = author.clone[0].children[AUTHOR_FIELD.RAUTH].name;
        author.attr.ATypeOldNameAttr = author.clone[0].children[AUTHOR_FIELD.ATYPE].name;

        // Get count of additional author divs already added
        author.additional.count = author.additional.object.children().length;

        // Create new name attributes based on how many additional authors already exist
        author.attr.RAuthNewNameAttr = IncrementNumeralInString(author.attr.RAuthOldNameAttr, 2, author.additional.count);
        author.attr.ATypeNewNameAttr = IncrementNumeralInString(author.attr.ATypeOldNameAttr, 1, author.additional.count);

        // Assign new name attribute to the clone
        author.clone[0].children[AUTHOR_FIELD.RAUTH].name = author.attr.RAuthNewNameAttr;
        author.clone[0].children[AUTHOR_FIELD.ATYPE].name = author.attr.ATypeNewNameAttr;

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
    }  
});
