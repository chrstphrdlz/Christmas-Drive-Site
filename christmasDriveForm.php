<!DOCTYPE html>
<html>
  <head>
    <title>Place Autocomplete Address Form</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <style>
      html, body, #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
      }
    </style>
    <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500">
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>
    <script>
// This example displays an address form, using the autocomplete feature
// of the Google Places API to help users fill in the information.

var placeSearch, autocomplete;
var componentForm = {
  street_number: 'short_name',
  route: 'long_name',
  locality: 'long_name',
  administrative_area_level_1: 'short_name',
  country: 'long_name',
  postal_code: 'short_name'
};

function initialize() {
  // Create the autocomplete object, restricting the search
  // to geographical location types.
  autocomplete = new google.maps.places.Autocomplete(
      /** @type {HTMLInputElement} */(document.getElementById('autocomplete')),
      { types: ['geocode'] });
  // When the user selects an address from the dropdown,
  // populate the address fields in the form.
  google.maps.event.addListener(autocomplete, 'place_changed', function() {
    fillInAddress();
  });
}

// [START region_fillform]
function fillInAddress() {
  // Get the place details from the autocomplete object.
  var place = autocomplete.getPlace();
  
  var all_fields = document.getElementsByClassName('field');
  for (var i = 0; i < all_fields.length; ++i) {
    var item = all_fields[i];  
    item.setAttribute("readonly", false);
  }

  for (var component in componentForm) {
    document.getElementById(component).value = '';
    document.getElementById(component).disabled = false;
  }

  // Get each component of the address from the place details
  // and fill the corresponding field on the form.
  for (var i = 0; i < place.address_components.length; i++) {
    var addressType = place.address_components[i].types[0];
    if (componentForm[addressType]) {
      var val = place.address_components[i][componentForm[addressType]];
      document.getElementById(addressType).value = val;
    }
  }
  
  // Makes the final address uneditable by the user
  var all_fields = document.getElementsByClassName('field');
  for (var i = 0; i < all_fields.length; ++i) {
    var item = all_fields[i];  
    item.setAttribute("readonly", true);
  }
}
// [END region_fillform]

// [START region_geolocation]
// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
function geolocate() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var geolocation = new google.maps.LatLng(
          position.coords.latitude, position.coords.longitude);
      autocomplete.setBounds(new google.maps.LatLngBounds(geolocation,
          geolocation));
    });
  }
}

//beginning of my script
//need to cleanup
//validates first and last name, can only have spaces, dashes, periods, and letters
function validateName(inputString)
{
    var regexUnwantedCharacters = /[^A-Za-z -.]+/i;
    var neededCharacters = /[A-Z|a-z]/i;
    
    if(!inputString || inputString.length < 1)
        return false;
        
    return !regexUnwantedCharacters.test(inputString) && neededCharacters.test(inputString);
}

//should make sure email address is in the correct format
function validateEmail(testEmailAddress)
{
    var regex = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/i;
    return regex.test(testEmailAddress);
}

function changeElementColor(element, color)
{
    element.style.backgroundColor = color;
}

//validates email text box, will reset color if blank (may change)
function highlightEmailBoxIfNotValidated(idString)
{
    var element = document.getElementById(idString);
    
    if(!validateEmail(element.value) && element.value.length > 0) changeElementColor(element, 'red');
    else changeElementColor(element, 'white');
}

//validates first and last name text box, will reset color if blank (may change)
function highlightNameBoxIfNotValidated(idString)
{
    var element = document.getElementById(idString);
    
    if(!validateName(element.value) && element.value.length > 0) changeElementColor(element, 'red');
    else changeElementColor(element, 'white');
}

function addTextBoxIfUnselected(item)
{
    if(item.value == "other")
    {
        document.getElementById("otherLanguageDiv").style.display ="inline";
    }
    else 
    {
        document.getElementById("otherLanguageDiv").style.display ="none";
    }
}

function setAction(element, action)
{
    element.action = action;
}

function chooseOption()
{
    var selection = document.getElementById("selectNameAction").value;
    var formElement = document.getElementById("selectPerson");
    if(selection == "makeClothingOrToyOrder")
    {
        setAction(formElement, "addToyOrClothingOrder.php");
    }
    if(selection == "makeFoodOrder")
    {
        setAction(formElement, "addFoodOrder.php");
    }
    else if(selection == "editInfo")
    {
        setAction(formElement, "editInfo.php");
    }
}

function disselectAddress()
{
    var elementOfAddressSelect = document.getElementById("addressSearchContainer");
    var apartmentInfoDiv = document.getElementById("apartmentAndBuildingNumber");
    
    if(document.getElementById("addressType").value == "house")
	{
		elementOfAddressSelect.style.display = "inline";
		apartmentInfoDiv.style.display = "none";
	} 
	else if(document.getElementById("addressType").value == "apartment")
	{
	    elementOfAddressSelect.style.display = "inline";
	    apartmentInfoDiv.style.display = "inline";
	}
	else
	{
	    elementOfAddressSelect.style.display = "none";
		apartmentInfoDiv.style.display = "none";
	}
	
}






// [END region_geolocation]

    </script>

    <style>
      #locationField, #controls {
        position: relative;
        width: 480px;
      }
      #autocomplete {
        position: absolute;
        top: 0px;
        left: 0px;
        width: 99%;
      }
      .label {
        text-align: right;
        font-weight: bold;
        width: 100px;
        color: #303030;
      }
      #address {
        border: 1px solid #000090;
        background-color: #f0f0ff;
        width: 480px;
        padding-right: 2px;
      }
      #address td {
        font-size: 10pt;
      }
      .field {
        width: 99%;
      }
      .slimField {
        width: 80px;
      }
      .wideField {
        width: 200px;
      }
      #locationField {
        height: 20px;
        margin-bottom: 2px;
      }
    </style>
  </head>
   <body onload="initialize()">
      <form name="addressBar" action="inputAllInformation.php" method="POST" onsubmit="return false;">
         <div id="residenceVerification" name="residenceVerification">
            Residence Verification<br>
            <input type="radio" name="residency" value="Yes">Yes<br>
            <input type="radio" name="residency" value="No">No<br>
         </div>
         <div id="householdStatusDiv" name="householdStatusDiv">
            Type of Household<br>
            <select name="householdStatus">
               <option value=1>Single household</option>
               <!-- selected="selected" if value == correct value-->
               <option value=2>Combined household</option>
            </select>
            <br>
         </div>
         <div id="firstNameDiv" name="firstNameDiv">
            First Name <input type="text" id="firstName" name="firstName" onkeyup="highlightNameBoxIfNotValidated('firstName')"><br>
         </div>
         <div id="lastNameDiv" name="lastNameDiv">
            Last Name <input type="text" id="lastName" name="lastName" onkeyup="highlightNameBoxIfNotValidated('lastName')"><br>
         </div>
         <div id="emailDiv" name="emailDiv">
            Email <input type="text" id="email" name="email" onkeyup="highlightEmailBoxIfNotValidated('email')"><br>
         </div>
         <!--phone number stuff-->
         <div id="primaryPhoneNumDiv" name="primaryPhoneNumDiv">
            Primary Phone Number:
            <input type="text" id="primaryPhoneNum" name="primaryPhoneNum"><br>
         </div>
         <div id="primaryPhoneDiv">
            Primary Phone Type<br>
            <input type="radio" name="primaryPhone" value=1>Home<br>
            <input type="radio" name="primaryPhone" value=2>Cell<br>
            <input type="radio" name="primaryPhone" value=3>Work<br>
            <input type="radio" name="primaryPhone" value=4>Other:<br>
            <input type="text" id="primaryPhoneType" name="primaryPhoneType"><br>
         </div>
         <!--other description box stuff-->
         <div id="secondaryPhoneNumDiv" name="secondaryPhoneNumDiv">
            Secondary Phone Number:
            <input type="text" name="secondaryPhoneNum">
         </div>
         <!--phone number stuff-->
         <div id="secondaryPhoneDiv" name="secondaryPhoneDiv">
            Phone Type<br>
            <input type="radio" name="secondaryPhone" value=1>Home<br>
            <input type="radio" name="secondaryPhone" value=2>Cell<br>
            <input type="radio" name="secondaryPhone" value=3>Work<br>
            <input type="radio" name="secondaryPhone" value=4>Other:<br>
            <input type="text" id="secondaryPhoneType" name="secondaryPhoneType"><br>
         </div>
         <!--other description box stuff-->
         <!--Number of family members-->
         <div id="languagesSpokenDiv" name="languagesSpokenDiv">
            Languages Spoken<br>
            <select id="languagesSpoken" name="languagesSpoken" onChange="addTextBoxIfUnselected(this)">
               <?php
                  require 'globalClasses.php';
                  
                  $dba = new databaseAcessor();
                  $languages = $dba->getLanguages();
                  foreach($languages as $language)
                  {
                      if($language->languageName == "English")
                        echo "<option selected='selected' value=";
                      else
                        echo "<option value=";
                      echo $language->id;
                      echo ">";
                      echo $language->languageName;
                      echo "</option>";
                  }
                  
                  ?>
               <!--need to add way to add another language-->
               <option value="other">Other</option>
            </select>
            <br>
            <div id="otherLanguageDiv" name="otherLanguageDiv" style="height:100px;width:300px;border:1px;display:none;">
               <input type='text' id='otherLanguage' name='otherLanguage'><br>
            </div>
         </div>
         <div id="deleiveryDiv" name="deleiveryDiv">
            Delivery (special request only)<br>
            <input type="radio" name="deleivery" value="Yes">Yes<br>
            <input type="radio" name="deleivery" value="No">No<br>
         </div>
         <div id="foodOrClothingDiv" name="foodOrClothingDiv">
            Christmas Store selection<br>
            <input type="radio" name="foodOrClothing" value="food">Food<br>
            <input type="radio" name="foodOrClothing" value="clothingAndToys">Clothing and Toys<br>
         </div>
         <div id="howDidYouKnowDiv" name="howDidYouKnowDiv">
            How did you learn about the Stores?<br>
            <select id="howDidYouKnow" name="howDidYouKnow">
               <option>Previous Customer</option>
               <option>Flyer</option>
               <option>School</option>
               <option>Word of Mouth</option>
               <option>Other</option>
            </select>
            <br>
         </div>
         <div id="canWeReachYouDiv" name="canWeReachYouDiv">
            Can a member of the St. Margaret Mary Church and Community Organization call you after the holidays to talk more about the needs and concerns of you and your family?<br>
            <input type="radio" name="canWeReachYou" value="Yes">Yes<br>
            <input type="radio" name="canWeReachYou" value="No">No<br>
         </div>
         <div id="notesDiv" name="notesDiv">
            Notes<br>
            <input type="text" id="notes" name="notes"><br>
         </div>
         
         Number of Family Members<br>
         <input type="text" id="numberOfFamilyMembers" name="numberOfFamilyMembers"><br>
         
         <div id="addressChoiceDiv">
            Type of Housing:<br>
            <select id="addressType" name="addressType" onChange="disselectAddress()">
               <option style="display: none;"></option>
               <option value="house">House</option>
               <option value="apartment">Apartment</option>
            </select>
         </div>
         
         <div id="addressSearchContainer" style="display:none;">
             <div id="apartmentAndBuildingNumber" style="display:none;">
             Building:<br>
                <input type="text" id="buildingNumber" name="buildingNumber"><br>
             Apartment<br>
                <input type="text" id="apartmentNumber" name="apartmentNumber"><br>
             </div>
             <div id="locationField">
                <input id="autocomplete" placeholder="Enter your address"
                   onFocus="geolocate()" type="text"></input>
             </div>
             <table id="address">
                <tr>
                   <td class="label" name="address">Street address</td>
                   <td class="slimField"><input class="field" id="street_number" name="street_number"
                      disabled="true"></input></td>
                   <td class="wideField" colspan="2"><input class="field" id="route" name="route"
                      disabled="true"></input></td>
                </tr>
                <tr>
                   <td class="label">City</td>
                   <td class="wideField" colspan="3"><input class="field" id="locality" name="locality"
                      disabled="true"></input></td>
                </tr>
                <tr>
                   <td class="label">State</td>
                   <td class="slimField"><input class="field"
                      id="administrative_area_level_1" name="state" disabled="true"></input></td>
                   <td class="label">Zip code</td>
                   <td class="wideField"><input class="field" id="postal_code" name="postal_code"
                      disabled="true"></input></td>
                </tr>
                <tr>
                   <td class="label">Country</td>
                   <td class="wideField" colspan="3"><input class="field"
                      id="country"  name="country" disabled="true"></input></td>
                </tr>
             </table>
             <input type="button" value="submit" onclick="this.parentNode.parentNode.submit();">
         </div>
      </form>
   </body>
</html>