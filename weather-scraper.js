$(document).ready(function () {
    $('#city').keypress(function (e) {
        if (e.keyCode == 13)
            $('#submit-btn').click();
    });

    $("#submit-btn").click(function () {
        $("#summary").removeClass().addClass("alert").html("").hide();
        var city = $("#city").val();
        city = formatCity(city);

        if (validateText(city)) {
            $.ajax({
                // felparaméterezi az url-t a GET-hez
                url: "weather-scraper.php?city=" + city,
                dataType: "json",
                success: function (data) {
                    if (data.error) {
                        $("#summary").addClass("alert-danger").html("Server error, try again later!").show();
                    }
                    else if (data)
                        $("#summary").addClass("alert-success").html(data).show();
                    else
                        $("#summary").addClass("alert-danger").html("Unknown city.").show();
                },
                error: function (data) {
                    $("#summary").addClass("alert-danger").html("Server error, try again later!").show();
                }
            });
        }
        else {
            $("#summary").addClass("alert-danger").html("City field is required!").show();
        }
    });
});

function validateText(text) {
    return (text.trim() != "");
}

// szóközöket "-"-re cseréli, ékezetmentesít, kezdőbetűket nagyra cseréli, ilyen formában teszi bele az URL-be a GET-tel
// weather-forecast ilyen formában teszi bele a saját GET kérésével az URL-be

function formatCity(text) {
    text = text.trim().replace(/ /g, "-").toLowerCase();
    var dict = { "á": "a", "é": "e", "í": "i", "ó": "o", "ö": "o", "ő": "o", "ü": "u", "ű": "u", "ú": "u" };

    text = text.replace(/[^\w ]/g, function (char) {
        return dict[char] || char;
    });

    text = text[0].toUpperCase() + text.substr(1);
    for (var i = 1; i < text.length; i++) {
        if (text[i] === "-")
        text = text.substr(0, i+1) + text[i+1].toUpperCase() + text.substr(i + 2);
    }
    return text;
}
