$(function(){

    /* --- Event handlers --- */

    $("#add_another_author").click(function() {

        /* Declare local variables */

        var iAdditionalAuthorCount;
        var oAdditionalAuthors;
        var oAuthorTemplateClone;
        var sAuthorTypeNameAttribute;
        var sNewAuthorTypeName;
        var sNewResourceAuthorName;
        var oAuthorTemplateChildren;
        var sResourceAuthorNameAttribute;

        /* Initialize local variables */

        // (DOM) Get divs by id
        oAdditionalAuthors = $("#additional_authors");
        oAuthorTemplateClone = $("#author_template").clone();
        
        oAuthorTemplateChildren = oAuthorTemplateClone.children();
        
        /* Execute */
        

        // Get the Name attribute of the first author
        sResourceAuthorNameAttribute = oAuthorTemplateChildren[0].name;
        sAuthorTypeNameAttribute = oAuthorTemplateChildren[1].name;

        // Get count of additional authors already added
        iAdditionalAuthorCount = oAdditionalAuthors.children().length;

        // Create new name attribute based on how many additional authors already exist
        sNewResourceAuthorName = IncrementName(sResourceAuthorNameAttribute, 2, iAdditionalAuthorCount);
        sNewAuthorTypeName = IncrementName(sAuthorTypeNameAttribute, 1, iAdditionalAuthorCount);

        // Assign new name attribute to the clone
        oAuthorTemplateClone[0].children[0].name = sNewResourceAuthorName;
        oAuthorTemplateClone[0].children[1].name = sNewAuthorTypeName;

        // Insert clone into group of additional authors
        oAdditionalAuthors.append(oAuthorTemplateClone);

        /* Final */

        // Return nothing
        return;

        /* --- Local Functions --- */
        
       // function ExtractNameAttribute()

        function IncrementName(sName, iElementIndex, iCount) {
            
            /* Declare local variables */
            
            var aNameParts;
            var sNewAuthorTypeName;

            /* Execute */

            // Split ID string up into an array, for easier manipulation
            aNameParts = sName.split("_");

            // Do some arithmetic, and replace the specified element in the array
            aNameParts[iElementIndex] = iCount + 1;

            // Reconstruct string with new ID
            sNewAuthorTypeName = aNameParts.join("_");

            /* Final */

            // Return the new ID
            return sNewAuthorTypeName;
        }
    });
});
