<section class="bg-login-opacity cookie" id="cookies">
    <div class="row justify-content-center my-md-4 my-1">
        <div class="row">
            <div class="col-12">
                <p class="text-white text-center"> We use essential cookies to make our site work. By clicking "Accept" or "Login", you agree to our website's cookie use as described in our <a href="#" class="text-white"> <u>Cookie Policy</u></a>.</p>
            </div>
            <div class="col-12 text-center">
                <button type="button" onclick="removeCookies()" class="btn-7g">Accept</button>
                <button type="button" onclick="showCookie()" class="btn-7r">Decline</button>
            </div>
        </div>
    </div>
</section>

<script>
    function removeCookies() {
        document.getElementById('cookies').remove();
        document.getElementById('loginbutton').style="display: inline;";
        if(document.getElementById('declinecookie')) {
            document.getElementById('declinecookie').remove();
        }
        console.log('accept');
    }

    function showCookie() {
        const infoMessage = document.createElement('div');
        infoMessage.className = "message-r";
        infoMessage.id ="declinecookie"
        infoMessage.appendChild(document.createTextNode('Because you declined our Cookie Policy, you will be unable to access our web application.'));
        document.getElementById("main").appendChild(infoMessage);
        console.log('decline');
    }

</script>