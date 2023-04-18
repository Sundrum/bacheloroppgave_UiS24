function showHelptext() {
    let helptext = document.getElementById(this.id + "_helptext");
    helptext.style.display = "inherit";
    if (this.value.length < 1) {
        helptext.style.display = "none";
    }
}

function setTitle(titleTop) {
    const title = document.getElementById('top-title');
    title.innerHTML = '';
    title.appendChild(document.createTextNode(titleTop));
}

function setName(text) {
    const name = document.getElementById('top-name');
    name.innerHTML = '';
    name.appendChild(document.createTextNode(text));
}

function setCustomer(text) {
    const customer = document.getElementById('top-customer');
    customer.innerHTML = '';
    customer.appendChild(document.createTextNode(text));
}

function errorMessage(text) {
    const infoMessage = document.createElement('div');
    infoMessage.className = "message";
    infoMessage.appendChild(document.createTextNode(text));
    
    const contentMain = document.getElementById("content-main");
    if (contentMain) { // make sure element exists before appending
        contentMain.appendChild(infoMessage);
        
        if ($) { // make sure jQuery is loaded
            $(".message")
                .fadeTo(4000, 0.8) // change to 0.8 -> 80%
                .slideUp(500, function() {
                    $(this).remove(); // use $(this) instead of $(".message")
                });
        }
    }
}

function successMessage(text) {
    const infoMessage = document.createElement('div');
    infoMessage.className = "message-g";
    infoMessage.appendChild(document.createTextNode(text));
    
    const contentMain = document.getElementById("content-main");
    if (contentMain) { // make sure element exists before appending
        contentMain.appendChild(infoMessage);
        
        if ($) { // make sure jQuery is loaded
            $(".message-g")
                .fadeTo(4000, 0.8) // change to 0.8 -> 80%
                .slideUp(500, function() {
                    $(this).remove(); // use $(this) instead of $(".message")
                });
        }
    }
}

function loadContent (url) {
    const infoMessage = document.createElement('div');
    infoMessage.className = "message-g";
    let spinner_container = document.createElement('div');
    spinner_container.innerHTML = '<div class="spinner-border text-7s" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only"></span></div>';
    infoMessage.appendChild(spinner_container);
    document.getElementById("content-main").appendChild(infoMessage);
    window.location = url;
}

function loadPage(url) {
    const infoMessage = document.createElement('div');
    infoMessage.className = "message";
    
    const spinner_container = document.createElement('div');
    spinner_container.innerHTML = '<div class="spinner-border text-7s" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only"></span></div>';
    
    infoMessage.appendChild(spinner_container);
    
    const contentMain = document.getElementById("content-main");
    if (contentMain) { // make sure element exists before appending
        contentMain.appendChild(infoMessage);
    }
    
    // use ajax to load the content asynchronously and update the URL
    $.ajax({
        url: url,
        success: function(data) {
            if ($) { // make sure jQuery is loaded
                $(".message")
                    .fadeTo(4000, 0.8)
                    .slideUp(500, function() {
                        $(this).remove();
                    });
            }
            $("#app").textContent = "";
            $("#app").html(data);
            history.pushState(null, null, url); // update URL without refreshing page
            $("body").scrollTop(0); // scroll to top of page after content loads

        },
        error: function() {
            if ($) { // make sure jQuery is loaded
                $(".message")
                    .fadeTo(4000, 0.8)
                    .slideUp(500, function() {
                        $(this).remove();
                    });
            }
            alert("There was an error loading the content.");
        }
    });
}

function rotateImg(obj) {
    let objectrotate = document.getElementById(obj);
    if (objectrotate.style.cssText) objectrotate.style.cssText = "";
    else objectrotate.style.cssText = "transform: rotate(-90deg);";
}

function addLoadingSpinner() {
    const infoMessage = document.createElement('div');
    infoMessage.className = "message-g";
    let spinner_container = document.createElement('div');
    spinner_container.innerHTML = '<div class="spinner-border text-7s" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only"></span></div>';
    infoMessage.appendChild(spinner_container);
    document.getElementById("content-main").appendChild(infoMessage);
}

function removeLoadingSpinner() {
    if ($) { // make sure jQuery is loaded
        $(".message-g")
            .fadeTo(4000, 0.8) // change to 0.8 -> 80%
            .slideUp(500, function() {
                $(this).remove(); // use $(this) instead of $(".message")
            });
    }
}