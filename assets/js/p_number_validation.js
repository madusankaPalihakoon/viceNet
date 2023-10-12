var country =document.getElementById("countrySelect");
    var phone_number = document.getElementById("phoneNumberInput");
                
    phone_number.disabled = true;

    country.addEventListener("change", function () {
        var select_country = country.value;
        if (select_country === 'Select Your Country' || select_country === ""){
                phone_number.disabled = true;
            }
            else{
                phone_number.disabled = false;
            }
    });