var countries = [
    'Select Your Country','Sri Lanka', 'Albania', 'Algeria', 'Andorra', 'Angola',
    'Argentina', 'Armenia', 'Australia', 'Austria', 'Azerbaijan',
    'Bangladesh', 'Belarus', 'Belgium', 'Bolivia', 'Brazil',
    'Bulgaria', 'Cambodia', 'Canada', 'Chile', 'China',
    'Colombia', 'Costa Rica', 'Croatia', 'Cuba', 'Cyprus',
    'Czech Republic', 'Denmark', 'Dominican Republic', 'Ecuador', 'Egypt',
    'El Salvador', 'Estonia', 'Ethiopia', 'Finland', 'France',
    'Georgia', 'Germany', 'Greece', 'Guatemala', 'Haiti',
    'Honduras', 'Hungary', 'Iceland', 'India', 'Indonesia',
    'Iran', 'Iraq', 'Ireland', 'Israel', 'Italy',
    'Jamaica', 'Japan', 'Jordan', 'Kazakhstan', 'Kenya',
    'Kuwait', 'Kyrgyzstan', 'Laos', 'Latvia', 'Lebanon',
    'Libya', 'Lithuania', 'Luxembourg', 'Macedonia', 'Malaysia',
    'Maldives', 'Malta', 'Mexico', 'Moldova', 'Mongolia',
    'Morocco', 'Myanmar', 'Nepal', 'Netherlands', 'New Zealand',
    'Nicaragua', 'Nigeria', 'North Korea', 'Norway', 'Oman',
    'Pakistan', 'Panama', 'Paraguay', 'Peru', 'Philippines',
    'Poland', 'Portugal', 'Qatar', 'Romania', 'Russia',
    'Saudi Arabia', 'Senegal', 'Serbia', 'Singapore', 'Slovakia',
    'Slovenia', 'South Africa', 'South Korea', 'Spain', 'Sri Lanka',
    'Sudan', 'Sweden', 'Switzerland', 'Syria', 'Taiwan',
    'Tanzania', 'Thailand', 'Trinidad and Tobago', 'Tunisia', 'Turkey',
    'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United States', 'Uruguay',
    'Uzbekistan', 'Venezuela', 'Vietnam', 'Yemen', 'Zimbabwe'
    ];

    var select = document.getElementById("countrySelect");

    for (var i = 0; i < countries.length; i++) {
        var option = document.createElement("option");
        option.value = countries[i];
        option.text = countries[i];
        select.appendChild(option);
    }