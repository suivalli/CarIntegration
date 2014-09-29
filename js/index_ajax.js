/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {
    $('#select-make').on('change', function () {

        //get selected value from category drop down
        var category = $(this).val();

        //select subcategory drop down
        var selectSubCat = $('#select-model');

        if ( $category != -1 ) {

            // ask server for sub-categories
            $.getJSON( "lib/getModel.php?category="+category)
            .done(function( result) {    
                // append each sub-category to second drop down   
                $.each(result, function(item) {
                    selectSubCat.append($("<option />").val(item.subCategory).text(item.subCategory));
                });
                // enable sub-category drop down
                selectSubCat.prop('disabled', false);                
            });

        } else {                
            // disable sub-category drop down
            selectSubCat.prop('disabled', 'disabled');
        }
    });    

});
