/* news poster js
 */

jQuery("document").ready(function ($) {
    //jQuery("#tabs").tabs();
    jQuery('#tabs').tabs({
        activate: function (event, ui) {
            //console.log(event);
            if (ui.newTab.index() == 2) {
                do_ajax('load_campaign');
            }
        }
    });
});

function delete_campaign(id) {
    var data = {
        action: 'shedied_ajax',
        id: id,
        todo: 'delete_campaign'
    };

    jQuery.post(ajaxurl, data, function (response) {
        do_ajax('load_campaign')
    });
}
function do_ajax(todo) {
    jQuery("body").addClass("loading");

    if (todo == 'load_campaign') {
        var data = {
            action: 'shedied_ajax',
            todo: todo
        };
    }

    if (todo == 'delete_campaign') {
        var data = {
            action: 'shedied_ajax',
            todo: todo
        };
    }

    if (todo == 'add_campaign') {
        src = jQuery('#set_news_src').val()
        cat = jQuery('#set_category').val()
        author = jQuery('#set_author').val()
        var data = {
            action: 'shedied_ajax',
            news_src: src,
            category: cat,
            author: author,
            todo: todo
        };
    }

    if (todo == 'save_setting') {
        firstPara = jQuery('#firstPara').val();
        lastPara = jQuery('#lastPara').val();
        isAutopost = jQuery('#ckAutoPost').is(':checked');
        isRewrite = jQuery('#ckRewrite').is(':checked');
        isTitleRewrite = jQuery('#ckTitleRewrite').is(':checked');
        isFullSource = jQuery('#ckFullSource').is(':checked');
        isRemoveLink = jQuery('#ckRemoveLink').is(':checked');
        var data = {
            action: 'shedied_ajax',
            firstPara: firstPara,
            lastPara: lastPara,
            isAutopost: isAutopost,
            isRewrite: isRewrite,
            isFullSource: isFullSource,
            isTitleRewrite: isTitleRewrite,
            isRemoveLink: isRemoveLink,
            todo: todo
        };

    }



    jQuery.post(ajaxurl, data, function (response) {
        if (todo == 'add_campaign') {
            do_ajax('load_campaign')
        }
        if (todo == 'save_setting') {
            alert(response)
        }
        if (todo == 'load_campaign') {
            arr = JSON.parse(response);
            var campaign = "<br><table class='wp-list-table fixed striped' width='400px'>" +
                    "<thead><tr><th>Source</th><th>Category</th><th>Author</th><th>Action</th></tr></thead><tbody> ";
            for (i = 0; i < arr.length; i++) {
                arr2 = arr[i].split(",");
                src = $("#set_news_src option[value='" + arr2[1] + "']").text()
                cat = $("#set_category option[value='" + arr2[2] + "']").text()
                author = $("#set_author option[value='" + arr2[3] + "']").text()
                campaign += "<tr><td>" + src + "</td><td>" + cat + "</td><td>" + author +
                        "</td><td><a href=# onclick='delete_campaign(\"" + arr2[0] + "\")'>Delete</a></td></tr>";
            }
            campaign += "</tbody><table>"
            jQuery("#z_campaign").html(campaign);
        }

        jQuery("body").removeClass("loading");

    });

}