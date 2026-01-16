Mautic.uploadVBACSV = function () {
    var formData = new FormData();
    var file = mQuery('input[name=\'integration_details[featureSettings][import_file_vba_code]\']').prop('files')[0];
    var button = mQuery('#integration_details_featureSettings_start_vbacode');
    mQuery('#vbcode-info').remove();

    if (file === undefined) {
        button.html('Start');
        return;
    }

    formData.append('file', file);
    button.html('Uploading');
    button.prop('disabled', true);




    mQuery.ajax({
        url: `${mauticBaseUrl}s/icccustomimport/importfilevbcode`,
        type: "POST",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            mQuery('input[name=\'integration_details[featureSettings][import_file_vba_code]\']').val('');
            var element = document.createElement("div");
            element.setAttribute("id", "vbcode-info");
            element.appendChild(document.createTextNode('Die Datei wurde erfolgreich hochgeladen!\n'));
            mQuery('#integration_details_featureSettings_start_vbacode').after(element);
        },
        error: function (request, textStatus, errorThrown) {
            console.log(errorThrown);
            var element = document.createElement("div");
            element.setAttribute("id", "vbcode-info");
            element.appendChild(document.createTextNode('Es ist ein Fehler beim Hochladen der CSV aufgetreten!\n'));
            mQuery('#integration_details_featureSettings_start_vbacode').after(element);
        },
        complete: function () {
            button.prop('disabled', false);
            button.html('Start');
        }
    });
}


Mautic.uploadKundenumfrageCSV = function () {
    var formData = new FormData();
    var file = mQuery('input[name=\'integration_details[featureSettings][import_file_kundenumfrage]\']').prop('files')[0];
    var button = mQuery('#integration_details_featureSettings_start_kundenumfrage');
    mQuery('#kundenumfrage-info').remove();

    if (file === undefined) {
        button.html('Start');
        return;
    }

    formData.append('file', file);
    button.html('Uploading');
    button.prop('disabled', true);



    mQuery.ajax({
        url: `${mauticBaseUrl}s/icccustomimport/importfilekundenumfrage`,
        type: "POST",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            mQuery('input[name=\'integration_details[featureSettings][import_file_kundenumfrage]\']').val('');
            var element = document.createElement("div");
            element.setAttribute("id", "kundenumfrage-info");
            element.appendChild(document.createTextNode('Die Datei wurde erfolgreich hochgeladen!\n'));
            mQuery('#integration_details_featureSettings_start_kundenumfrage').after(element);
        },
        error: function (request, textStatus, errorThrown) {
            var element = document.createElement("div");
            element.setAttribute("id", "kundenumfrage-info");
            element.appendChild(document.createTextNode('Es ist ein Fehler beim Hochladen der CSV aufgetreten!\n'));
            mQuery('#integration_details_featureSettings_start_kundenumfrage').after(element);
        },
        complete: function () {
            button.prop('disabled', false);
            button.html('Start');
        }
    });
}