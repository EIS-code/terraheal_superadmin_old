// fixed header
$(window).scroll(function(){

    if ($(this).scrollTop() > 50) {

       $('.main-flex').addClass('fixed');

    } else {

       $('.main-flex').removeClass('fixed');

    }

});


//wow 

if ($('.wow').length > 0) {

        var wowSel = 'wow';

        var wow = new WOW({

            boxClass: wowSel,

            animateClass: 'animated',

            offset: 0,

            mobile: false,

            live: true,

            callback: function(box) {



            },

            scrollContainer: null

        });

    wow.init();

}



/* chart.js chart examples */



// chart colors

var colors = ['#4a9d77','#f0f2f7'];



/* Happy Clients */

var donutOptions = {

  cutoutPercentage: 85, 

  legend: {position:'bottom', padding:5, labels: {pointStyle:'circle', usePointStyle:true}}

};





// Happy Clients

var chDonutData3 = {

  labels: ['Happy Clients'],

    datasets: [

      {

        backgroundColor: colors.slice(0,3),

        borderWidth: 0,

        data: [60, 40]

      }

    ]

};

var chDonut3 = document.getElementById("chDonut3");

if (chDonut3) {

  new Chart(chDonut3, {

      type: 'pie',

      data: chDonutData3,

      options: donutOptions

  });

}





/* bar chart */

// chart colors

var colors = ['#007bff','#28a745'];



var chBar = document.getElementById("chBar");

if (chBar) {

  new Chart(chBar, {

  type: 'bar',

  data: {

    labels: ["Mon", "Tues", "Wed", "Thu", "Fri", "Sat", "Sun"],

    datasets: [{

      data: [0, 100, 200, 300, 400, 500, 600, 700],

      backgroundColor: colors[0]

    },

    ]

  },

  options: {

    legend: {

      display: false

    },

    scales: {

      xAxes: [{

        barPercentage: 0.4,

        categoryPercentage: 0.5

      }]

    }

  }

  });

}



//fileupload



//I added event handler for the file upload control to access the files properties.

document.addEventListener("DOMContentLoaded", init, false);



//To save an array of attachments

var AttachmentArray = [];



//counter for attachment array

var arrCounter = 0;



//to make sure the error message for number of files will be shown only one time.

var filesCounterAlertStatus = false;



//un ordered list to keep attachments thumbnails

var ul = document.createElement("ul");

ul.className = "thumb-Images";

ul.id = "imgList";



function init() {

  //add javascript handlers for the file upload event

  if (!document.querySelector("#files")) {
    return false;
  }

  document

    .querySelector("#files")

    .addEventListener("change", handleFileSelect, false);

}



//the handler for file upload event

function handleFileSelect(e) {

  //to make sure the user select file/files

  if (!e.target.files) return;



  //To obtaine a File reference

  var files = e.target.files;



  // Loop through the FileList and then to render image files as thumbnails.

  for (var i = 0, f; (f = files[i]); i++) {

    //instantiate a FileReader object to read its contents into memory

    var fileReader = new FileReader();



    // Closure to capture the file information and apply validation.

    fileReader.onload = (function(readerEvt) {

      return function(e) {

        //Apply the validation rules for attachments upload

        ApplyFileValidationRules(readerEvt);



        //Render attachments thumbnails.

        RenderThumbnail(e, readerEvt);



        //Fill the array of attachment

        FillAttachmentArray(e, readerEvt);

      };

    })(f);



    // Read in the image file as a data URL.

    // readAsDataURL: The result property will contain the file/blob's data encoded as a data URL.

    // More info about Data URI scheme https://en.wikipedia.org/wiki/Data_URI_scheme

    fileReader.readAsDataURL(f);

  }

  document

    .getElementById("files")

    .addEventListener("change", handleFileSelect, false);

}



//To remove attachment once user click on x button

jQuery(function($) {

  $("div").on("click", ".img-wrap .close", function() {

    var id = $(this)

      .closest(".img-wrap")

      .find("img")

      .data("id");



    //to remove the deleted item from array

    var elementPos = AttachmentArray.map(function(x) {

      return x.FileName;

    }).indexOf(id);

    if (elementPos !== -1) {

      AttachmentArray.splice(elementPos, 1);

    }



    //to remove image tag

    $(this)

      .parent()

      .find("img")

      .not()

      .remove();



    //to remove div tag that contain the image

    $(this)

      .parent()

      .find("div")

      .not()

      .remove();



    //to remove div tag that contain caption name

    $(this)

      .parent()

      .parent()

      .find("div")

      .not()

      .remove();



    //to remove li tag

    var lis = document.querySelectorAll("#imgList li");

    for (var i = 0; (li = lis[i]); i++) {

      if (li.innerHTML == "") {

        li.parentNode.removeChild(li);

      }

    }

  });

});



//Apply the validation rules for attachments upload

function ApplyFileValidationRules(readerEvt) {

  //To check file type according to upload conditions

  if (CheckFileType(readerEvt.type) == false) {

    alert(

      "The file (" +

        readerEvt.name +

        ") does not match the upload conditions, You can only upload jpg/png/gif files"

    );

    e.preventDefault();

    return;

  }



  //To check file Size according to upload conditions

  if (CheckFileSize(readerEvt.size) == false) {

    alert(

      "The file (" +

        readerEvt.name +

        ") does not match the upload conditions, The maximum file size for uploads should not exceed 300 KB"

    );

    e.preventDefault();

    return;

  }



  //To check files count according to upload conditions

  if (CheckFilesCount(AttachmentArray) == false) {

    if (!filesCounterAlertStatus) {

      filesCounterAlertStatus = true;

      alert(

        "You have added more than 10 files. According to upload conditions you can upload 10 files maximum"

      );

    }

    e.preventDefault();

    return;

  }

}



//To check file type according to upload conditions

function CheckFileType(fileType) {

  if (fileType == "image/jpeg") {

    return true;

  } else if (fileType == "image/png") {

    return true;

  } else if (fileType == "image/gif") {

    return true;

  } else {

    return false;

  }

  return true;

}



//To check file Size according to upload conditions

function CheckFileSize(fileSize) {

  if (fileSize < 600000) {

    return true;

  } else {

    return false;

  }

  return true;

}



//To check files count according to upload conditions

function CheckFilesCount(AttachmentArray) {

  //Since AttachmentArray.length return the next available index in the array,

  //I have used the loop to get the real length

  var len = 0;

  for (var i = 0; i < AttachmentArray.length; i++) {

    if (AttachmentArray[i] !== undefined) {

      len++;

    }

  }

  //To check the length does not exceed 10 files maximum

  if (len > 9) {

    return false;

  } else {

    return true;

  }

}



//Render attachments thumbnails.

function RenderThumbnail(e, readerEvt) {

  var li = document.createElement("li");

  ul.appendChild(li);

  li.innerHTML = [

    '<div class="img-wrap"> <span class="close">&times;</span>' +

      '<img class="thumb" src="',

    e.target.result,

    '" title="',

    escape(readerEvt.name),

    '" data-id="',

    readerEvt.name,

    '"/>' + "</div>"

  ].join("");



  var div = document.createElement("div");

  div.className = "FileNameCaptionStyle";

  li.appendChild(div);

  div.innerHTML = [readerEvt.name].join("");

  document.getElementById("Filelist").insertBefore(ul, null);

}





//Fill the array of attachment

function FillAttachmentArray(e, readerEvt) {

  AttachmentArray[arrCounter] = {

    AttachmentType: 1,

    ObjectType: 1,

    FileName: readerEvt.name,

    FileDescription: "Attachment",

    NoteText: "",

    MimeType: readerEvt.type,

    Content: e.target.result.split("base64,")[1],

    FileSizeInBytes: readerEvt.size

  };

  arrCounter = arrCounter + 1;

}

//end-file-upload


// bottom div open(add/edit) collapse

$(document).ready(function() {

        $('.add-d').click(function() {

      $(this).addClass('hide');

      $(this).addClass('hide');
      

      $(this).closest('.center-row').addClass('opened');

               $(this).parent().siblings('.center-bottom-sec').show();

        });

    });
  
  $(document).ready(function() {

        $('.edit-d').click(function() {

      $(this).addClass('hide');

      $(this).addClass('hide');
      $(this).closest('.center-row').addClass('opened');

               $(this).parent().siblings('.center-bottom-sec').show();

        });

    });
  

//active class for sidebar  

jQuery(function($) {
     var path = window.location.href; // because the 'href' property of the DOM element is the absolute path
     $('.main-menu a').each(function() {
      if (this.href === path) {
       $(this).addClass('active');
      }
     });
    });





// switch toggle

$('.btn-toggle').click(function() {

    $(this).find('.btn').toggleClass('active');

  $(this).find('.btn').toggleClass('inactive');  

    

    if ($(this).find('.btn-primary').length>0) {

      $(this).find('.btn').toggleClass('btn-primary');

    }

    if ($(this).find('.btn-danger').length>0) {

      $(this).find('.btn').toggleClass('btn-danger');

    }

    if ($(this).find('.btn-success').length>0) {

      $(this).find('.btn').toggleClass('btn-success');

    }

    if ($(this).find('.btn-info').length>0) {

      $(this).find('.btn').toggleClass('btn-info');

    }

    

    $(this).find('.btn').toggleClass('btn-default');

       

});



/*$('form').submit(function(){

  var radioValue = $("input[name='options']:checked").val();

  if(radioValue){

     alert("You selected - " + radioValue);

   };

    return false;

});*/



//calender


var sampleEvents = [

    {

      title: "Soulful sundays bay area",

      date: new Date().setDate(new Date().getDate() - 7), // last week

      link: "#"

    },

    {

      title: "London Comicon",

      date: new Date().getTime(), // today

      link: "#"

    },

    {

      title: "Youth Athletic Camp",

      date: new Date().setDate(new Date().getDate() + 31), // next month

      link: "#"

    }

];

/*$("#calendar").MEC({

  events: sampleEvents

});*/


$('#dynamicContent').prepend($('#cnt-tab-cont'));

// $('#element').donetyping(callback[, timeout=1000])
// Fires callback when a user has finished typing. This is determined by the time elapsed
// since the last keystroke and timeout parameter or the blur event--whichever comes first.
//   @callback: function to be called when even triggers
//   @timeout:  (default=1000) timeout, in ms, to to wait before triggering event if not
//              caused by blur.
// Requires jQuery 1.7+
//
;(function($){
    $.fn.extend({
        donetyping: function(callback,timeout){
            timeout = timeout || 1e3; // 1 second default timeout
            var timeoutReference,
                doneTyping = function(el){
                    if (!timeoutReference) return;
                    timeoutReference = null;
                    callback.call(el);
                };
            return this.each(function(i,el){
                var $el = $(el);
                // Chrome Fix (Use keyup over keypress to detect backspace)
                // thank you @palerdot
                $el.is(':input') && $el.on('keyup keypress paste',function(e){
                    // This catches the backspace button in chrome, but also prevents
                    // the event from triggering too preemptively. Without this line,
                    // using tab/shift+tab will make the focused element fire the callback.
                    if (e.type=='keyup' && e.keyCode!=8) return;
                    
                    // Check if timeout has been set. If it has, "reset" the clock and
                    // start over again.
                    if (timeoutReference) clearTimeout(timeoutReference);
                    timeoutReference = setTimeout(function(){
                        // if we made it here, our timeout has elapsed. Fire the
                        // callback
                        doneTyping(el);
                    }, timeout);
                }).on('blur',function(){
                    // If we can, fire the event since we're leaving the field
                    doneTyping(el);
                });
            });
        }
    });
})(jQuery);

// Read a page's GET URL variables and return them as an associative array.
function getUrlVars()
{
    let vars   = [], hash;
    let hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');

    for(var i = 0; i < hashes.length; i++) {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }

    return vars;
}

var searchFormRequest = null;
function submitSearchForm(form)
{
    if (form && form.length > 0) {
        let url  = form.attr('action'),
            type = form.attr('method');

        searchFormRequest = $.ajax(
            {
                url: url,
                type: type,
                data: form.serialize(),
                // dataType: 'json',
                beforeSend : function() {
                    if (searchFormRequest != null) {
                        searchFormRequest.abort();
                    }
                },
                success: function(response, textStatus, jqXHR) {
                    // console.log(response, textStatus, jqXHR);
                    if (response) {
                        let htmlElements = $(document).find("#list");

                        htmlElements.empty();

                        htmlElements.hide().html(response).fadeIn(200);

                        let newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?' + this.url.slice(this.url.indexOf('?') + 1);

                        window.history.pushState({path:newUrl}, '', newUrl);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // console.log(errorThrown, textStatus, jqXHR);
                }
            }
        );
    }
}

let searchForm = $(document).find('#search-form');

if (searchForm && searchForm.length > 0) {
    let inputs = searchForm.find(':input');

    if (inputs && inputs.length > 0) {
        inputs.each(function() {
            $(this).donetyping(function() {
                submitSearchForm(searchForm);
            });
        });

        inputs.filter("select").on('change', function() {
            submitSearchForm(searchForm);
        });
    }
}

var getProvinceRequest = null;
$(document).find("select[id^='countries']").on("change", function() {
    let self = $(this);
    if (self.data('is-get-provinces')) {
        let value         = self.val(),
            selectBox     = $(self.data("selectbox-province-id")),
            selectedId    = selectBox.data("selected-id"),
            defaultoption = selectBox.find("[data-is-default='true']").clone();

        let setEmpty = function() {
            selectBox.empty();

            selectBox.html(defaultoption);
        };

        if (value == "" || Number.isNaN(parseInt(value))) {
            if (selectBox) {
                setEmpty();
            }
        } else {
          getProvinceRequest = $.ajax(
              {
                  url: routeProvince,
                  type: "POST",
                  data: {"country_id": value},
                  beforeSend : function() {
                      if (getProvinceRequest != null) {
                          getProvinceRequest.abort();
                      }
                  },
                  success: function(response, textStatus, jqXHR) {
                      if (response) {
                          if (selectBox) {
                              setEmpty();

                              if (Object.keys(response.data).length > 0) {
                                  $.each(response.data, function(index, province) {
                                      let selected = (parseInt(selectedId) == province.id) ? "selected" : "";

                                      selectBox.append($('<option value="' + province.id + '" ' + selected + '>' + province.name + '</option>'));
                                  });
                              }
                          }
                      }
                  },
                  error: function(jqXHR, textStatus, errorThrown) {
                      // console.log(errorThrown, textStatus, jqXHR);
                  }
              }
          );
        }
    }
});

var getCityRequest = null;
$(document).find("select[id^='provinces']").on("change", function() {
    let self = $(this);
    if (self.data('is-get-cities')) {
        let value         = self.val(),
            selectBox     = $(self.data("selectbox-city-id")),
            selectedId    = selectBox.data("selected-id"),
            defaultoption = selectBox.find("[data-is-default='true']").clone();

        let setEmpty = function() {
            selectBox.empty();

            selectBox.html(defaultoption);
        };

        if (value == "" || Number.isNaN(parseInt(value))) {
            if (selectBox) {
                setEmpty();
            }
        } else {
          getCityRequest = $.ajax(
              {
                  url: routeCity,
                  type: "POST",
                  data: {"province_id": value},
                  beforeSend : function() {
                      if (getCityRequest != null) {
                          getCityRequest.abort();
                      }
                  },
                  success: function(response, textStatus, jqXHR) {
                      if (response) {
                          if (selectBox) {
                              setEmpty();

                              if (Object.keys(response.data).length > 0) {
                                  $.each(response.data, function(index, city) {
                                      let selected = (parseInt(selectedId) == city.id) ? "selected" : "";

                                      selectBox.append($('<option value="' + city.id + '" ' + selected + '>' + city.name + '</option>'));
                                  });
                              }
                          }
                      }
                  },
                  error: function(jqXHR, textStatus, errorThrown) {
                      // console.log(errorThrown, textStatus, jqXHR);
                  }
              }
          );
        }
    }
});

$(document).find(".radioToggle").on("click", function() {
    let self      = $(this),
        dataClass = self.data('class'),
        dataValue = self.data('value');

    if ($("." + dataClass)) {
        $("." + dataClass).each(function(e) {
            $(this).prop("checked", false);

            if (dataValue == $(this).val()) {
                $(this).prop("checked", true);
            }
        });
    }
});

// Blur lat/long.
var customLabel = {
    restaurant: {
        label: 'R'
    },
    bar: {
        label: 'B'
    }
};
function setMap(latitude, longitude, zoom)
{
    zoom = parseInt(zoom) || 15;

    var map = new google.maps.Map(document.getElementById('map'), {
        center  : new google.maps.LatLng(latitude, longitude),
        zoom    : zoom
    });

    var infowindow = new google.maps.InfoWindow();

    // Change this depending on the name of your PHP or XML file
    downloadUrl('https://storage.googleapis.com/mapsdevsite/json/mapmarkers2.xml', function(data) {
        var xml     = data.responseXML;
        var markers = xml.documentElement.getElementsByTagName('marker');

        Array.prototype.forEach.call(markers, function(markerElem) {
            var id      = markerElem.getAttribute('id');
            var name    = markerElem.getAttribute('name');
            var address = markerElem.getAttribute('address');
            var type    = markerElem.getAttribute('type');
            var point   = new google.maps.LatLng(
                parseFloat(markerElem.getAttribute('lat')),
                parseFloat(markerElem.getAttribute('lng'))
            );

            var infowincontent  = document.createElement('div');
            var strong          = document.createElement('strong');
            strong.textContent  = name;
            infowincontent.appendChild(strong);
            infowincontent.appendChild(document.createElement('br'));

            var text          = document.createElement('text');
            text.textContent  = address
            infowincontent.appendChild(text);

            var icon    = customLabel[type] || {};
            var marker  = new google.maps.Marker({
                map: map,
                position: point,
                label: icon.label
            });

            marker.addListener('click', function() {
                infoWindow.setContent(infowincontent);
                infoWindow.open(map, marker);
            });
        });
    });
}

function downloadUrl(url, callback) {
    var request = window.ActiveXObject ?
                  new ActiveXObject('Microsoft.XMLHTTP') :
                  new XMLHttpRequest;

    request.onreadystatechange = function() {
        if (request.readyState == 4) {
            request.onreadystatechange = doNothing;
            callback(request, request.status);
        }
    };

    request.open('GET', url, true);
    request.send(null);
}

function doNothing() {}

$(document).find("#latitude, #longitude, #zoom").on("blur", function() {
    let latitude  = $("#latitude"),
        longitude = $("#longitude"),
        zoom      = $("#zoom");

    if (latitude.val().length > 0 && longitude.val().length > 0) {
        setMap(latitude.val(), longitude.val(), zoom.val());
    }
});
