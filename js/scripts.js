$(document).ready(function() {
    textUpdate();

    function deleteCookie(name) {
        document.cookie = name + '="";-1; path=/';
    }

    function geturl() {
		const currentUrl = window.location.href;
		console.log(currentUrl);
		
        $.ajax({
            url: "geturl.php",
            method: "post",
            success: function(response) {
                var url = response;
                $("#flyer-url-container, #copy-iframe-code-button").css("visibility", "visible");
                $("#flyer-url").attr("href", currentUrl + "flyer/" + url).text(currentUrl + "flyer/" + url);
            }
        });
		
    }

    function getFlyers() {
        $.ajax({
            url: "load.php",
            method: "GET",
            dataType: "json",
            success: function(response) {
                $("tbody").hide().html(response).fadeIn();
            }
        });
    }

    if ($(".table-window table > tbody > tr").length == 0) {
        $(".saved-flyers").css("display", "none");
    }

    function textUpdate() {
        if ($("#header-text").val().length === 0) {
            $("#header").css({
                "height": "0"
            });
        } else {
            $("#header").css({
                "height": "100px"
            });
        }
        var header_text = "<span>" + $("#header-text").val() + "</span>";
        $("#header").html(header_text);
        $("#header").textfill({
            maxFontPixels: 60
        });
    }

    // resize header whenever it is edited
    $("#header-text").keyup(textUpdate);

    // resize header on load
    textUpdate();

    function applyFont(element, fontSpec) {
        // Split font into family and weight/style
        const tmp = fontSpec.split(":"),
            family = tmp[0],
            variant = tmp[1] || "400",
            weight = parseInt(variant, 10),
            italic = /i$/.test(variant);

        // Apply selected font to element
        const css = {
            fontFamily: "'" + family + "'",
            fontWeight: weight,
            fontStyle: italic ? "italic" : "normal"
        };
        $(element).css(css);
        textUpdate();
    }

    function red_line() {
        $("#sortable li").css({
            "margin-top": "0",
            "opacity": "1"
        }).removeClass("noprint noinstagram");
        var list_items = $("#sortable li").length;
        if (list_items > 20) {
            $("#sortable li").slice(20, 25).css({
                "margin-top": "20px",
            });
            $("#sortable li").slice(20).css({
                "opacity": "0.3"
            }).addClass("noprint");
        }
        if (list_items > 15) {
            $("#sortable li").slice(15).addClass("noinstagram");
        }        
    }

    /* run on load */
    red_line();

    const localFonts = {
        "Arial": {
            "category": "sans-serif",
            "variants": "400,400i,600,600i"
        },
        "Georgia": {
            "category": "serif",
            "variants": "400,400i,600,600i"
        },
        "Times New Roman": {
            "category": "serif",
            "variants": "400,400i,600,600i"
        },
        "Verdana": {
            "category": "sans-serif",
            "variants": "400,400i,600,600i",
        },
        "Bauer": {
            "category": "display",
            "variants": "400,600",
            "subsets": "latin-ext,latin"
        },
        "Bubble": {
            "category": "display",
            "variants": "400,400i,600,600i",
            "subsets": "latin-ext,latin"
        },
        "TrajanPro-Regular": {
            "category": "display"
        },
        "TrajanPro-Bold": {
            "category": "display"
        },
        "AGaramondPro-Bold": {
            "category": "serif"
        },
        "AGaramondPro-Regular": {
            "category": "serif"
        },
        "AmericanTypewriter-Bold": {
            "category": "serif"
        },
        "Futura-CondensedExtraBold": {
            "category": "display"
        },
        "UniversLTStd-BoldCn": {
            "category": "sans-serif"
        }
    };

    $("#font-picker").fontpicker({
            localFontsUrl: "fonts/", // End with a slash!
            localFonts: localFonts,
            showClear: false,
            googleFonts: [
                "Abril Fatface",
                "Alfa Slab One",
                "Anton",
                "Architects Daughter",
                "Averia Libre",
                "Barlow",
                "Berkshire Swash",
                "Bitter",
                "Black Ops One",
                "Bungee Shade",
                "Cabin",
                "Cabin Sketch",
                "Cambay",
                "Chivo",
                "Creepster",
                "Crimson Text",
                "Emilys Candy",
                "Ewert",
                "Exo 2",
                "Fira Sans",
                "Fredoka One",
                "Glass Antiqua",
                "Graduate",
                "IM Fell English SC",
                "Josefin Slab",
                "Karla",
                "Lalezar",
                "Lato",
                "Libre Baskerville",
                "Libre Franklin",
                "Lobster",
                "Londrina Solid",
                "Lora",
                "Luckiest Guy",
                "Martel Sans",
                "Merienda",
                "Merriweather",
                "Modak",
                "Molle",
                "Montserrat",
                "Muli",
                "Noto Sans",
                "Nunito",
                "Nunito Sans",
                "Open Sans",
                "Oswald",
                "Overpass",
                "Permanent Marker",
                "Playfair Display",
                "Poppins",
                "Press Start 2P",
                "PT Serif",
                "Quicksand",
                "Raleway",
                "Righteous",
                "Roboto",
                "Roboto Condensed",
                "Roboto Mono",
                "Roboto Slab",
                "Rubik",
                "Slabo 27px",
                "Source Sans Pro",
                "Staatliches",
                "Work Sans"
            ]
        })
        .on("change", function() {
            applyFont("#header", this.value);
        });


    $("ul#sortable").on("click", "li", function() {
        $(this).remove();
        red_line();
    });

    $("#sortable").sortable({
        animation: 500,
        chosenClass: 'chosen',
        fallbackTolerance: 3,
    });        

    $("#name").on("keyup", function() {
        let empty = false;
        empty = $("#name").val().trim().length == 0;
        if (empty) {
            $("#save-button").prop("disabled", true);
        } else {
            $("#save-button").prop("disabled", false);
        }
    });

    // add ISBNs
    $("#add-button").on("click", function(e) {
        e.preventDefault();
        var covers = 0;
        if ($("#sortable li").length > 0) {
            covers = $("#sortable li").length + 1;
        }
        var lines = $("#isbns").val().split(/\n/);
        for (var i = 0; i < lines.length; i++) {
            // only add this line if it contains a non whitespace character
            if (/\S/.test(lines[i])) {
                raw_isbn = lines[i];
                isbn = raw_isbn.replace(/[\W_]+/g, "");
                link = "https://secure.syndetics.com/index.aspx?type=xw12&client=MVLC&upc=&ocls=&isbn=" + isbn + "/LC.JPG";                
                $('#sortable').append("<li class='ui-state-default' data-id='" + (i + covers) + "' id=" + $.trim(lines[i]) + "><img src=" + link + "></li>");

            }
        }
        red_line();
        $("#isbns").val("");
    });

    // get ISBNs
    var counter_new_titles = 0;
    var counter_teen_room_titles = 0;
    var counter_custom_catalog_url = 0;
    $("#custom-url").on("keyup change", function() {
        if ($(this).val() != "") {
            $("#custom-catalog-url").attr("disabled", false).css("pointer-events", "auto").text("Load");
        } else {
            $("#custom-catalog-url").attr("disabled", true).css("pointer-events", "none").text("Load");
        }
    });
    $(".load-button button").click(function() {
        id = $(this).attr("id");
        switch (id) {
            case "new-titles":
                link = "https://mvlc.ent.sirsi.net/client/en_US/andover/search/results?qu=MANNEWBKS&";
                counter_new_titles++;
                active_counter = counter_new_titles;
                break;
            case "teen-room-titles":
                link = "https://mvlc.ent.sirsi.net/client/en_US/andover/search/results?qu=MANNEWYA&";
                counter_teen_room_titles++;
                active_counter = counter_teen_room_titles;
                break;
            case "custom-catalog-url":
                link = $("#custom-url").val();
                link = link + "&";
                counter_custom_catalog_url++;
                active_counter = counter_custom_catalog_url;
                break;
        }
        var more = new Boolean(true);
        $.ajax({
            url: "scrape.php",
            method: "post",
            dataType: "JSON",
            data: {
                set: active_counter,
                link: link
            },
            beforeSend: function() {
                // Show image container
                $("#" + id).next("span").css("visibility", "visible");
            },
            success: function(response) {
                var len = response.length;
                more = new Boolean(true);
                var covers = 0;
                if ($("#sortable li").length > 0) {
                    covers = $("#sortable li").length + 1;
                }
                for (var i = 0; i < len; i++) {
                    if (response[i] == "end") {
                        more = undefined;
                        delete window.more;
                        if (len == 1) {
                            if (active_counter == 1) {
                                // no results at all
                                $("#no-results").dialog({
                                    resizable: false,
                                    height: "auto",
                                    width: 300,
                                    modal: true,
                                    show: {
                                        effect: "fade",
                                        duration: 200
                                    },
                                    close: function() {
                                        $("#custom-url").select();
                                    }
                                });
                            } else {
                                $("#no-more-results").dialog({
                                    resizable: false,
                                    height: "auto",
                                    width: 300,
                                    modal: true,
                                    show: {
                                        effect: "fade",
                                        duration: 200
                                    }
                                });
                            }
                        }
                        if (len > 1) {
                            // probably should wrap this in a function
                            // we have hits, but no more after this
                            $("#no-more-results").dialog({
                                resizable: false,
                                height: "auto",
                                width: 300,
                                modal: true,
                                show: {
                                    effect: "fade",
                                    duration: 200
                                }
                            });
                        }
                        break;
                    }
                    if (Boolean(response[i])) { // get rid of "false" objects in the array
                        isbn = response[i];
                        link = "https://secure.syndetics.com/index.aspx?type=xw12&client=MVLC&upc=&ocls=&isbn=" + isbn + "/LC.JPG";                        
                        $("#sortable").append('<li class="ui-state-default" data-id="' + (i + covers) + '" id=' + isbn + '><img src=' + link + '></li>');
                    }
                }
                red_line();
            },
            complete: function(data) {
                // Hide image container
                $("#" + id).next("span").css("visibility", "hidden");
                buttonText = $("button#" + id).text();
                if (active_counter > 0 && buttonText == "Load" && typeof more !== "undefined") {
                    $("button#" + id).fadeOut("fast", function() {
                        $(this).text("Load more").fadeIn("fast");
                        $("#" + id).parent().children(".load-reset").css({
                            "visibility": "visible",
                            "opacity": "1"
                        }).hide().fadeIn("fast");
                    });
                }
                if (more == undefined) {
                    $("#" + id).prop("disabled", true).css("pointer-events", "none").parent().attr("title", "no more results");
                }
            }
        });
        console.log("counter is " + active_counter);
    });

    // Save function
    function save(type, id, name, webpage, header, isbns, imageids, header_css, html) {
        $.ajax({
            url: "insert.php",
            method: "post",
            data: {
                id: id,
                name: name,
                webpage,
                header: header,
                isbns: isbns,
                imageids: imageids,
                header_css,
                html
            },
            success: function(response) {
                switch (type) {
                    case "update":
                        console.log("updated");
                        $("#flyer-saved").dialog({
                            classes: {
                                "ui-dialog-titlebar": "hidden"
                            },
                            width: 500,
                            modal: true,
                            position: {
                                my: "center",
                                at: "top+25%",
                                of: window
                            },
                            open: function() {
                                $(this).html("<p>Flyer <b>" + name + "</b> has been updated</p>");
                            },
                            hide: {
                                effect: "fadeOut",
                                duration: 400
                            }
                        }, setTimeout(function() {
                            $("#flyer-saved").dialog("close");
                        }, 1500)).css("font-size", "48px");
                        break;
                    case "save":
                        console.log("saved");
                        $("#flyer-saved").dialog({
                            classes: {
                                "ui-dialog-titlebar": "hidden"
                            },
                            width: 500,
                            modal: true,
                            position: {
                                my: "center",
                                at: "top+25%",
                                of: window
                            },
                            open: function() {
                                $(this).html("<p><b>" + name + "</b> has been saved</p>");
                            },
                            hide: {
                                effect: "fadeOut",
                                duration: 400
                            }
                        }, setTimeout(function() {
                            $("#flyer-saved").dialog("close");
                        }, 1500)).css("font-size", "48px");
                        $(".saved-flyers").fadeIn();
                        break;
                }
                $("#isbns").val("");
                getFlyers();
            }
        });
    }

    $("#help-button").click(function(e) {
		e.preventDefault();
        $("#help").dialog({
            resizable: false,
            height: "auto",
            width: 1000,
            modal: true,
            show: {
                effect: "fade"
            },
        });
    });

    // Save
    $("#save-button").click(function(e) {
        e.preventDefault();
        let checkState = 1;
        if ($("#sortable li").length == 0) { // nothing to save!
            $("#no-isbns").dialog({
                resizable: false,
                height: "auto",
                width: 300,
                modal: true,
                show: {
                    effect: "fade",
                    duration: 200
                }
            });
            $("#save-button").prop("disabled", true);
            $("#name").val("");
            return;
        }
        var isbns_arr = [];
        var imageids_arr = [];
        $("#sortable li").each(function() {
            var isbn = $(this).attr("id");
            var id = $(this).data("id");
            isbns_arr.push(isbn);
            imageids_arr.push(id);
        });
        var header_css = $(".fp-fontspec").text();
        if (header_css == "Select a font") {
            header_css = "";
        }
        var html = $("#book").html();
        var flyer_id = "";
        /* if a flyer has already been loaded, we're just updating */
        if ($("input#flyer-id").val().length > 0) {
            flyer_id = $("input#flyer-id").val();
            let checkState = 1;
            save("update", flyer_id, $("#name").val().trim(), checkState, $("#header-text").val(), isbns_arr, imageids_arr, header_css, html);
            console.log("updated");
        } else {
            var existing_names = [];
            $("td.flyer-name").each(function() {
                existing_names.push($(this).text());
            });
            if (jQuery.inArray($("#name").val(), existing_names) !== -1) {
                var flyer_name = $("#name").val();
                var tr_id = $("td").filter(function() {
                    return $(this).text() == flyer_name;
                }).closest("tr").attr("id");
                flyer_id = tr_id;
                let checkState = 1;
            } else {
                // else statement?
            }
            if (flyer_id.length > 0) {
                $("#dialog-confirm-overwrite").dialog({
                    resizable: false,
                    height: "auto",
                    width: 400,
                    modal: true,
                    show: {
                        effect: "fade",
                        duration: 200
                    },
                    buttons: {
                        "Overwrite": function() {
                            console.log("confirmed");
                            $(this).dialog("close");
                            save("update", flyer_id, $("#name").val().trim(), checkState, $("#header-text").val(), isbns_arr, imageids_arr, header_css, html);
                        },
                        Cancel: function() {
                            console.log("canceled");
                            $(this).dialog("close");
                        }
                    }
                });
            } else {
                save("save", flyer_id, $("#name").val().trim(), checkState, $("#header-text").val(), isbns_arr, imageids_arr, header_css, html);
                var timeDelay = 3000;
                setTimeout(geturl, timeDelay); // give mySQL a chance to register the saved flyer
            }
        }
    });

    // Download
    $("#download-image-button").click(function() {
        $("li").each(function() {
            if ($(this).css("opacity") == 0.3) {
                $(this).addClass("save").hide(); // remove the overflow covers
            }
        });
        // var book_width = $("#book").width();
        var filename = "";
        if ($("#header-text").val().length === 0) {
            filename = "image.png";
        } else {
            var s = $("#header-text").val();
            filename = s.replace(/[^a-z0-9]/gi, "_").toLowerCase() + ".png";
        }
        // console.log(book_width);
        var book_height = $("#book").height();
        window.scrollTo(0, 0);
        html2canvas(document.getElementById("book"), {
            height: book_height,
            proxy: "proxy2.php"
        }).then(function(canvas) {
            canvas.toBlob(function(blob) {
                saveAs(blob, filename);
            });
        });
        $("li.save").show();
    });
    
    // Download for Instagram
    $("#download-image-button-instagram").click(function() {
        var list_items = $("#sortable li").length;
        if (list_items < 15) {
          alert ("Please make sure there are at least 15 covers.");
          return;
        }
        $("li").each(function() {
            if ($(this).css("opacity") == 0.3) {
                $(this).addClass("save").hide(); // remove the overflow covers
            }
        });
        $("li.noinstagram").each(function() {
            $(this).addClass("save").hide(); // remove the overflow covers
        });
        var filename = "";
        if ($("#header-text").val().length === 0) {
            filename = "image.png";
        } else {
            var s = $("#header-text").val();
            filename = s.replace(/[^a-z0-9]/gi, "_").toLowerCase() + ".png";
        }
        window.scrollTo(0, 0);
        html2canvas(document.getElementById("book"), {
            proxy: "proxy2.php"
        }).then(function(canvas) {
                var extra_canvas = document.createElement("canvas");
                extra_canvas.setAttribute('width',1080);
                extra_canvas.setAttribute('height',1080);
                var ctx = extra_canvas.getContext('2d');
                ctx.drawImage(canvas,0,0,canvas.width, canvas.height,0,0,1080,1080);
                extra_canvas.toBlob(function(blob) {
                    saveAs(blob, filename);
                });
        });
        $("li.save").show();
    });    

    // Edit
    $("tbody").on("click", "button.edit", function() {
        // reset the fontpicker
        if ($(this).val().trim().length != 0) {
            $("#save-button").prop("disabled", true);
        } else {
            $("#save-button").prop("disabled", false);
        }
        $("ul#sortable").empty();
        edit_id = $(this).data("id");
        $("td.flyer-name").css({
            "color": "black",
            "font-weight": "normal"
        }); // reset all in case one was already selected
        $(this).parent().siblings(":first").css({
            "color": "#009879",
            "font-weight": "bold"
        });
        $.ajax({
            url: "getflyer.php",
            method: "post",
            dataType: "JSON",
            data: {
                flyer: edit_id
            },
            success: function(response) {
                const currentUrl = window.location.href;
                console.log(currentUrl);
                var header_text = response[0].header;
                var name_text = response[0].name;
                var has_url = response[0].has_url;
                var url = response[0].url;
                if (has_url == 1) {
                    var iframe_value = '<iframe height=1800px width=100% style="border: none; width: 100% !important; display: block !important; margin: 0 !important;" src="https://mhl.org/covers/flyer/'+ url + '"></iframe>';
                    $("#flyer-url-container, #copy-iframe-code-button").css("visibility", "visible");
                    // $("#flyer-url").attr("href", "https://mhl.org/temp/covers/flyer/" + url).text("https://mhl.org/temp/covers/flyer/" + url);
                    $("#flyer-url").attr("href", window.location.href + "flyer/" + url).text(window.location.href + "flyer/" + url);
                    $("#copy-iframe-code-button").click(function (event) {
                       event.preventDefault();
                       navigator.clipboard.writeText(iframe_value);


                      var notificationTag = $("div.copy-notification");
                      var notificationText = "iframe code copied to clipboard!"
                      if (notificationTag.length == 0) {
                           notificationTag = $("<div/>", { "class": "copy-notification", text: notificationText });
                           $("body").append(notificationTag);

                           notificationTag.fadeIn("slow", function () {
                           setTimeout(function () {
                              notificationTag.fadeOut("slow", function () {
                                  notificationTag.remove();
                              });
                          }, 1000);
                        });
                      }


                    });
                } else {
                    $("#flyer-url").text("");
                    $("#flyer-url-container, #copy-iframe-code-button").css("visibility", "hidden");
                }

                textUpdate();
                red_line();
            }
        });
    });

    // Delete
    $("tbody").on("click", "button.delete", function() {
        event.preventDefault();
        del_id = $(this).data("id");
        name = $(this).parent().siblings(":first").text();
        $("#dialog-confirm-operation").dialog({
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            open: function() {
                $(this).html("<p>Are you sure you want to delete flyer <b>" + name + "</b>?</p>");
            },
            show: {
                effect: "fade",
                duration: 200
            },
            buttons: {
                "Delete": function() {
                    console.log("confirmed");
                    $(this).dialog("close");
                    $.ajax({
                        url: "delete.php",
                        method: "post",
                        data: {
                            id: del_id
                        },
                        success: function(response) {
                            getFlyers();
                            $("ul#sortable").empty();
                            $("#isbns").val("");
                            $("#my-form")[0].reset();
                            $("input#font-picker").appendTo("textarea#header-text");
                            if ($(".table-window table > tbody > tr").length == 1) { // if this is the last one
                                $(".saved-flyers").fadeOut();
                            }
                            $("#my-form")[0].reset(); // location.reload() takes care of it for Chrome; this is needed for FF
                            location.reload(); // if we do this, we probably don't need some of the stuff above
                        }
                    });
                },
                Cancel: function() {
                    console.log("canceled");
                    $(this).dialog("close");
                }
            }
        });
    });

    // load resets
    $("#new-titles-reset").on("click", function(e) {
        e.preventDefault();		
        counter_new_titles = 0;
        $("button#new-titles").text("Load");
        $(this).fadeTo(500, 0, function() {
            $(this).css("visibility", "hidden");
        });
    });

    $("#teen-room-titles-reset").on("click", function(e) {
		e.preventDefault();
        counter_teen_room_titles = 0;
        $("button#teen-room-titles").text("Load");
        $(this).fadeTo(500, 0, function() {
            $(this).css("visibility", "hidden");
        });
    });

    $("#custom-catalog-url-reset").on("click", function(e) {
	    e.preventDefault();
        counter_custom_catalog_url = 0;
        $("button#custom-catalog-url").text("Load");
        $(this).fadeTo(500, 0, function() {
            $(this).css("visibility", "hidden");
        });
        $("#custom-url").val("");
        $("#custom-catalog-url").prop("disabled", true);
    });

    // Clear All button
    $("#cancel-button").click(function() {
        $("#my-form")[0].reset(); // location.reload() takes care of it for Chrome; this is needed for FF
        location.reload();
    });

    // Clear Covers button
    $("#clear-button").click(function(e) {
        e.preventDefault();
        $("ul#sortable").empty();
        $("#isbns").val("");
    });

    // print it
    $("#print-button").click(function() {
        window.print();
        return false;
    });

    // clear the cookie
    $("#clear-cookie").click(function(e) {
        e.preventDefault();
        deleteCookie("jqfs");
        location.reload();
    });
});
