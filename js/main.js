$(function(){

    /* --- Event handlers --- */

    $("#add_another_author").click(function() {

        /* Declare local variables */

        var iAdditionalAuthorCount;
        var oAdditionalAuthors;
        var oAuthorTypeFieldClone;
        var sAuthorTypeNameAttribute;
        var sNewNameAttribute;

        /* Initialize local variables */

        // (DOM) Get divs by id
        oAdditionalAuthors = $("#additional_authors");
        oAuthorTypeFieldClone = $("#author_type").clone();

        /* Execute */

        // Get the ID of the first author
        sAuthorTypeNameAttribute = oAuthorTypeFieldClone[0].name;

        // Get count of additional authors already added
        iAdditionalAuthorCount = oAdditionalAuthors.children().length;

        // Create new ID based on how many additional authors already exist
        sNewNameAttribute = IncrementName(sAuthorTypeNameAttribute, 1, iAdditionalAuthorCount);

        // Assign new ID to the clone
        oAuthorTypeFieldClone[0].name = sNewNameAttribute;

        // Insert clone into group of additional authors
        oAdditionalAuthors.append(oAuthorTypeFieldClone[0]);

        /* Final */

        // Return nothing
        return;

        /* --- Local Functions --- */

        function IncrementName(sName, iElementIndex, iCount) {
            
            /* Declare local variables */
            
            var aNameParts;
            var sNewNameAttribute;

            /* Execute */

            // Split ID string up into an array, for easier manipulation
            aNameParts = sName.split("_");

            // Do some arithmetic, and replace the specified element in the array
            aNameParts[iElementIndex] = iCount + 1;

            // Reconstruct string with new ID
            sNewNameAttribute = aNameParts.join("_");

            /* Final */

            // Return the new ID
            return sNewNameAttribute;
        }
    });
});
