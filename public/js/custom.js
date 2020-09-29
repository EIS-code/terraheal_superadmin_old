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



