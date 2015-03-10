$(function(){

    /* --- Event handlers --- */

    $("#add_another_author").click(function() {

        /* Define local enumerators */

        var AUTHOR_FIELD = {
            NAME: 0,
            TYPE: 1
        };

        /* Declare local variables */

        var iAdditionalAuthorCount;
        var oAdditionalAuthors;
        var oAuthorTemplateClone;
        var sAuthorTypeNameAttribute;
        var sNewAuthorTypeName;
        var sNewResourceAuthorName;
        var sResourceAuthorNameAttribute;

        /* Initialize local variables */

        // (DOM) Get divs by id
        oAdditionalAuthors = $("#additional_authors");
        oAuthorTemplateClone = $("#author_template").clone();
        
        /* Execute */

        // Get the Name attribute of the first author div
        sResourceAuthorNameAttribute = oAuthorTemplateClone[0].children[AUTHOR_FIELD.NAME].name
        sAuthorTypeNameAttribute = oAuthorTemplateClone[0].children[AUTHOR_FIELD.TYPE].name

        // Get count of additional author divs already added
        iAdditionalAuthorCount = oAdditionalAuthors.children().length;

        // Create new name attribute based on how many additional authors already exist
        sNewResourceAuthorName = IncrementNumeralInString(sResourceAuthorNameAttribute, 2, iAdditionalAuthorCount);
        sNewAuthorTypeName = IncrementNumeralInString(sAuthorTypeNameAttribute, 1, iAdditionalAuthorCount);

        // Assign new name attribute to the clone
        oAuthorTemplateClone[0].children[AUTHOR_FIELD.NAME].name = sNewResourceAuthorName;
        oAuthorTemplateClone[0].children[AUTHOR_FIELD.TYPE].name = sNewAuthorTypeName;

        // Remove the ID from the cloned div
        oAuthorTemplateClone[0].removeAttribute("id");
        
        // Insert clone into group of additional authors
        oAdditionalAuthors.append(oAuthorTemplateClone);

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
