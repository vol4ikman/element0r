var httpRequest;

function validateForm() {

    var userEmail = document.getElementById("user_email");
    var userPass  = document.getElementById("user_pass");
    var formErros = document.getElementById("formErrors");

    function checkUserEmail() {
        if ( userEmail.value == "" || !isNaN(userEmail.value) || !validateEmail(userEmail.value) ){
            userEmail.classList.add('error-field');
            proceedSubmit = false;
            console.log("Email is not valid");
        } else {
            document.getElementById("user_email").classList.remove('error-field');
            proceedSubmit = true;
            console.log("Thank You, Email is valid");
        }
    };

    function checkUserPass() {
        if ( userPass.value.length <= 5 ) {
            userPass.classList.add('error-field');
            proceedSubmit = false;
            console.log("Password minimum 6 characters");
        } else {
            userPass.classList.remove('error-field');
            proceedSubmit = true;
            console.log("Thank You");
        }
    };

    checkUserEmail();
    checkUserPass();

    if ( proceedSubmit ) {
        document.getElementById("submitForm").disabled = false;
        formErros.classList.remove('show');
    } else {
        document.getElementById("submitForm").disabled = true;
        formErros.classList.add('show');
    }

}

function validateEmail(email) {
    var re = /\S+@\S+\.\S+/;
    return re.test(email);
}

function loadUserData( user_id ) {

    httpRequest = new XMLHttpRequest();

    if ( ! httpRequest ) {
        alert('Giving up :( Cannot create an XMLHTTP instance');
        return false;
    }

    httpRequest.open( 'POST', 'ajax.php', true );
    httpRequest.setRequestHeader( "Content-Type", "application/x-www-form-urlencoded" );
    httpRequest.send( "user_id=" + user_id + "&action=loadUserData" );

    httpRequest.onreadystatechange = function(){
        if ( this.readyState == 4 && this.status == 200) {
            var response = JSON.parse(httpRequest.responseText);
            // console.log( response );
            if( response.popup_html ){
                var popup_wrapper = document.createElement("div");
                popup_wrapper.className = 'main-popup-wrapper';
                popup_wrapper.innerHTML = response.popup_html
                document.body.appendChild( popup_wrapper );
            }
        }
    };
}

function closePopup(){
    var elem = document.querySelector('.main-popup-wrapper');
    elem.parentNode.removeChild(elem);
}
