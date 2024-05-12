//userData from updateUserData.blade

// Function to fill out form fields with userData
function fillFormFields(userData) {
    // Iterate over the keys of userData
    for (var key in userData) {
        // Check if the element with id equal to the key exists
        var element = document.getElementById(key);
        if (element) {
            // If the element exists, set its value to the corresponding value in userData
            element.value = userData[key];
            // Add a class to the filled out field
            element.classList.add('filled-out');
        }
    }
}

// Call fillFormFields function with userData
fillFormFields(userData); 


