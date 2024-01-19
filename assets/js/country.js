
    // Country data with corresponding phone codes
    const countries = {
      'Select your Country': '',
      'Sri Lanka': '+94',
      'Afghanistan': '+93',
      'Bahrain': '+973',
      'Bangladesh': '+880',
      'Bhutan': '+975',
      'Brunei': '+673',
      'Cambodia': '+855',
      'China': '+86',
      'India': '+91',
      'Indonesia': '+62',
      'Iran': '+98',
      'Iraq': '+964',
      'Israel': '+972',
      'Japan': '+81',
      'Jordan': '+962',
      'Kazakhstan': '+7',
      'Kuwait': '+965',
      'Kyrgyzstan': '+996',
      'Laos': '+856',
      'Lebanon': '+961',
      'Malaysia': '+60',
      'Maldives': '+960',
      'Mongolia': '+976',
      'Myanmar (Burma)': '+95',
      'Nepal': '+977',
      'North Korea': '+850',
      'Oman': '+968',
      'Pakistan': '+92',
      'Palestine': '+970',
      'Philippines': '+63',
      'Qatar': '+974',
      'Saudi Arabia': '+966',
      'Singapore': '+65',
      'South Korea': '+82',
      'Syria': '+963',
      'Taiwan': '+886',
      'Tajikistan': '+992',
      'Thailand': '+66',
      'Timor-Leste': '+670',
      'Turkey': '+90',
      'Turkmenistan': '+993',
      'United Arab Emirates': '+971',
      'Uzbekistan': '+998',
      'Vietnam': '+84',
      'Yemen': '+967',
    };    

    // Function to populate the country dropdown
    function populateCountries() {
      const selectCountry = document.getElementById('selectCountry');

      for (const country in countries) {
        const option = document.createElement('option');
        option.value = country;
        option.textContent = country;
        selectCountry.appendChild(option);
      }
    }

    // Function to update the phone code and enable/disable the phone number field
    function updatePhoneCode() {
      const selectCountry = document.getElementById('selectCountry');
      const inputPhone = document.getElementById('inputPhone');

      const selectedCountry = selectCountry.value;
      const phoneCode = countries[selectedCountry];

      // Update the phone number field with the selected country's phone code
      inputPhone.value = phoneCode;
      
      // Enable or disable the phone number field based on the selected country
      if (selectedCountry === 'Select your Country' || selectedCountry === '') {
        disablePhoneNumberInput();
      } else {
        enablePhoneNumberInput();
      }
    //   inputPhone.disabled = (selectedCountry === 'Select your Country');
    }

    // Call the function to populate the country dropdown on page load
    populateCountries();

    disablePhoneNumberInput();

    function disablePhoneNumberInput() {
        const inputPhone = document.getElementById('inputPhone');
        inputPhone.disabled = true;
    }

    function enablePhoneNumberInput() {
        const inputPhone = document.getElementById('inputPhone');
        inputPhone.disabled = false;
    }