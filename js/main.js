$(function(){
    $("#add_another_author").click(function() {
        var oAuthorTypeFieldClone;
        var oAdditionalAuthors;
        var oAdditionalAuthors = $("#additional_authors");
        var sAuthorTypeFieldCloneId;
        var iChildCount;


        oAuthorTypeFieldClone = $("#author_0_type").clone();

        //update the child count
        iChildCount = oAdditionalAuthors.children().length;
        sAuthorTypeFieldCloneId = oAuthorTypeFieldClone[0].id;
        oAuthorTypeFieldClone[0].id = IncrementId(sAuthorTypeFieldCloneId, iChildCount);

        oAdditionalAuthors.append(oAuthorTypeFieldClone[0]);


        function IncrementId(sAuthorTypeFieldCloneId, iChildCount) {
            var sNextId;
            var aAuthorTypeIdParts;
            aAuthorTypeIdParts = sAuthorTypeFieldCloneId.split("_");
            aAuthorTypeIdParts[1] = iChildCount + 1;
            sNextId = aAuthorTypeIdParts.join("_");
            return sNextId;
        }
    });
});

