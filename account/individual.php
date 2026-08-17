<?php
session_start();


//check if session id is set if it is redirect to dashboard
if(isset($_SESSION['id']) and (isset($_SESSION['2fa']) and $_SESSION['2fa'] == "yes")){
	
	header("location:dashboard");
}else{
     

    if(isset($_SESSION['2fa'])){

        if($_SESSION['2fa'] == "pending"){
        header("location:authenticate");
    }

    if($_SESSION['2fa'] == "no"){
        header("location:dashboard");
    }

    }

}


include('connection.php');

?>
<?php
$authPageTitle = 'Quantum Scalp | Sign Up';
$authTitle = 'Create your account';
$authSubtitle = 'Individual account — become a member and access Q-Core.';
$authWide = true;
include('inc/auth-head.php');
include('inc/auth-open.php');
if (isset($_GET['refer']) && $_GET['refer'] !== '') {
    echo '<div class="qs-ref-notice">You were invited with code <strong>' . htmlspecialchars($_GET['refer']) . '</strong></div>';
}
?>
<form class="needs-validation mt-4 pt-2" enctype="multipart/form-data"
                                          method="post">
  
                                          <input type="hidden" value="1" id="steps" />
  
  
                                          <div class="row">
  
                                              <div class="col-sm-12 mb-4">
  
                                                  <div class="position-relative m-4">
                                                      <div class="progress" style="height: 1px;">
                                                          <div class="progress-bar" id="progress" role="progressbar"
                                                              style="width: 20%;" aria-valuenow="20" aria-valuemin="0"
                                                              aria-valuemax="100">
                                                          </div>
                                                      </div>
                                                      <button type="button"
                                                          class="position-absolute top-0 start-0 translate-middle btn btn-sm btn-primary rounded-pill"
                                                          style="width: 2rem; height:2rem;">1</button>
                                                      <button type="button"
                                                          class="position-absolute top-0 start-50 translate-middle btn btn-sm btn-primary rounded-pill"
                                                          style="width: 2rem; height:2rem;">2</button>
                                                      <button type="button"
                                                          class="position-absolute top-0 start-100 translate-middle btn btn-sm btn-primary rounded-pill"
                                                          style="width: 2rem; height:2rem;">3</button>
  
                                                  </div>
  
                                              </div>
  
  
                                              <div class="col-sm-12">
  
  
                                                  <div class="mb-3  step step1 ">
  
                                                      <h5 class="mb-0">Lets get your account started </h5>
                                                      <p class="text-muted mt-2">Type in your email address. <br />This
                                                          will be used when you log into Quantum Group</p>
  
  
                                                      <label for="useremail" class="form-label">Email</label>
                                                      <input type="email" class="form-control" name="email"
                                                          placeholder="Enter email" required>
                                                      <div class="invalid-feedback">
                                                          Please Enter Email
                                                      </div>
  
  
                                                      <label for="useremail" class="form-label mt-3">Referral Code</label>
                                                      <input type="text" class="form-control" name="refer"
                                                          placeholder="Enter Referral Code" value="<?php if(isset($_GET['refer'])){ echo$_GET['refer']; } ?>" >
                                                      <div class="invalid-feedback">
                                                          Please Enter Code
                                                      </div>
  
                                                      <div class="mt-3">
                                                          <button onclick="next()"
                                                              class="btn btn-primary w-100 waves-effect waves-light"
                                                              type="button">Next</button>
                                                      </div>
  
                                                  </div>
  
  
                                                  <div class="mb-3 step step2" style="display:none">
  
                                                      <h5 class="mb-0">Create a secure password </h5>
                                                      <p class="text-muted mt-2">Password can contain symbols,space and
                                                          specail character for extra level of security.</p>
  
  
  
  
  
  
                                                      <label for="userpassword" class="form-label">Password</label>
                                                      <div class="input-group auth-pass-inputgroup">
                                                          <input type="password" id="password" class="form-control"
                                                              name="password" placeholder="Enter password"
                                                              aria-label="Password" required
                                                              aria-describedby="password-addon">
                                                          <button class="btn btn-light shadow-none ms-0" type="button"
                                                              id="password-addon"><i
                                                                  class="mdi mdi-eye-outline"></i></button>
                                                          <div class="invalid-feedback">
                                                              Please Enter Password
                                                          </div>
                                                      </div>
  
                                                      <br />
  
                                                      <label for="userpassword" class="form-label">Confirm
                                                          Password</label>
                                                      <div class="input-group auth-pass-inputgroup">
                                                          <input type="password" id="confirm" class="form-control"
                                                              name="password" placeholder="Enter password"
                                                              aria-label="Password" required
                                                              aria-describedby="password-addon">
                                                          <button class="btn btn-light shadow-none ms-0"
                                                              id="password-addon2" type="button"><i
                                                                  class=" mdi mdi-eye-outline"></i></button>
                                                          <div class="invalid-feedback">
                                                              Please Enter Password
                                                          </div>
                                                      </div>
  
  
                                                      <div class="mt-3 row">
                                                          <div class="col-6">
                                                              <button onclick="back()"
                                                                  class="btn btn-primary w-100 waves-effect waves-light"
                                                                  type="button">Back</button>
                                                          </div>
                                                          <div class="col-6">
                                                              <button onclick="next()"
                                                                  class="btn btn-primary w-100 waves-effect waves-light"
                                                                  type="button">Next</button>
                                                          </div>
                                                      </div>
  
  
                                                  </div>
  
  
  
                                                  <div class="mb-3 step step3" style="display:none">
  
                                                      <h5 class="mb-0">Almost there,</h5>
                                                      <p class="text-muted mt-2">
  
                                                          Please fill in your Goverment name as it appears on your
                                                          Identity Card.</p>
  
  
  
                                                      <label for="username" class="form-label">First Name</label>
                                                      <input type="text" class="form-control" name="firstname"
                                                          placeholder="Enter Firstname" required>
                                                      <div class="invalid-feedback">
                                                          Please Enter Firstname
                                                      </div>
                                                  </div>
  
  
                                                  <div class="mb-3 step step3" style="display:none">
                                                      <label for="username" class="form-label">Last Name</label>
                                                      <input type="text" class="form-control" name="lastname"
                                                          placeholder="Enter Lastname" required>
                                                      <div class="invalid-feedback">
                                                          Please Enter Lastname
                                                      </div>
  
  
  
  
                                                      <div class="mt-3 row">
                                                          <div class="col-6">
                                                              <button onclick="back()"
                                                                  class="btn btn-primary w-100 waves-effect waves-light"
                                                                  type="button">Back</button>
                                                          </div>
                                                          <div class="col-6">
                                                              <button onclick="next()"
                                                                  class="btn btn-primary w-100 waves-effect waves-light"
                                                                  type="button">Next</button>
                                                          </div>
                                                      </div>
  
  
  
                                                  </div>
  
  
  
  
  
  
  
  
                                                  <div class="mb-4" style="display:none">
  
                                                  </div>
  
                                              </div>
  
  
                                              <div class="col-sm-12">
  
  
  
                                                  <div class="mb-3 step step4" style="display:none">
  
                                                      <h5 class="mb-0">Contact Details</h5>
                                                      <p class="text-muted mt-2">We ask for phone number so as to
                                                    communicate to you and also for an extra layer of verification. we
                                                        will never spam you.</p>
  
  
                                                      <label for="username" class="form-label">Phone Number </label>
                                                      <input type="phone" class="form-control" name="phone"
                                                          placeholder="Enter Phone Number" required>
                                                      <div class="invalid-feedback">
                                                          Please Enter Phone
                                                      </div>
                                                  </div>
  
  
                                                  <div class="mb-3 step step4" style="display:none">
                                                      <label for="username" class="form-label">Country
                                                      </label>
                                                      <select class="form-control" name="country"
                                                          placeholder="Select Country" required>
                                                          <option value="">Choose Country</option>
  
                                                          <option value="Afghanistan">Afghanistan</option>
                                                          <option value="Albania">Albania</option>
                                                          <option value="Algeria">Algeria</option>
                                                          <option value="American Samoa">American Samoa</option>
                                                          <option value="Andorra">Andorra</option>
                                                          <option value="Angola">Angola</option>
                                                          <option value="Anguilla">Anguilla</option>
                                                          <option value="Antartica">Antarctica</option>
                                                          <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                                          <option value="Argentina">Argentina</option>
                                                          <option value="Armenia">Armenia</option>
                                                          <option value="Aruba">Aruba</option>
                                                          <option value="Australia">Australia</option>
                                                          <option value="Austria">Austria</option>
                                                          <option value="Azerbaijan">Azerbaijan</option>
                                                          <option value="Bahamas">Bahamas</option>
                                                          <option value="Bahrain">Bahrain</option>
                                                          <option value="Bangladesh">Bangladesh</option>
                                                          <option value="Barbados">Barbados</option>
                                                          <option value="Belarus">Belarus</option>
                                                          <option value="Belgium">Belgium</option>
                                                          <option value="Belize">Belize</option>
                                                          <option value="Benin">Benin</option>
                                                          <option value="Bermuda">Bermuda</option>
                                                          <option value="Bhutan">Bhutan</option>
                                                          <option value="Bolivia">Bolivia</option>
                                                          <option value="Bosnia and Herzegowina">Bosnia and Herzegowina
                                                          </option>
                                                          <option value="Botswana">Botswana</option>
                                                          <option value="Bouvet Island">Bouvet Island</option>
                                                          <option value="Brazil">Brazil</option>
                                                          <option value="British Indian Ocean Territory">British Indian
                                                              Ocean Territory</option>
                                                          <option value="Brunei Darussalam">Brunei Darussalam</option>
                                                          <option value="Bulgaria">Bulgaria</option>
                                                          <option value="Burkina Faso">Burkina Faso</option>
                                                          <option value="Burundi">Burundi</option>
                                                          <option value="Cambodia">Cambodia</option>
                                                          <option value="Cameroon">Cameroon</option>
                                                          <option value="Canada">Canada</option>
                                                          <option value="Cape Verde">Cape Verde</option>
                                                          <option value="Cayman Islands">Cayman Islands</option>
                                                          <option value="Central African Republic">Central African
                                                              Republic</option>
                                                          <option value="Chad">Chad</option>
                                                          <option value="Chile">Chile</option>
                                                          <option value="China">China</option>
                                                          <option value="Christmas Island">Christmas Island</option>
                                                          <option value="Cocos Islands">Cocos (Keeling) Islands</option>
                                                          <option value="Colombia">Colombia</option>
                                                          <option value="Comoros">Comoros</option>
                                                          <option value="Congo">Congo</option>
                                                          <option value="Congo">Congo, the Democratic Republic of the
                                                          </option>
                                                          <option value="Cook Islands">Cook Islands</option>
                                                          <option value="Costa Rica">Costa Rica</option>
                                                          <option value="Cota D'Ivoire">Cote d'Ivoire</option>
                                                          <option value="Croatia">Croatia (Hrvatska)</option>
                                                          <option value="Cuba">Cuba</option>
                                                          <option value="Cyprus">Cyprus</option>
                                                          <option value="Czech Republic">Czech Republic</option>
                                                          <option value="Denmark">Denmark</option>
                                                          <option value="Djibouti">Djibouti</option>
                                                          <option value="Dominica">Dominica</option>
                                                          <option value="Dominican Republic">Dominican Republic</option>
                                                          <option value="East Timor">East Timor</option>
                                                          <option value="Ecuador">Ecuador</option>
                                                          <option value="Egypt">Egypt</option>
                                                          <option value="El Salvador">El Salvador</option>
                                                          <option value="Equatorial Guinea">Equatorial Guinea</option>
                                                          <option value="Eritrea">Eritrea</option>
                                                          <option value="Estonia">Estonia</option>
                                                          <option value="Ethiopia">Ethiopia</option>
                                                          <option value="Falkland Islands">Falkland Islands (Malvinas)
                                                          </option>
                                                          <option value="Faroe Islands">Faroe Islands</option>
                                                          <option value="Fiji">Fiji</option>
                                                          <option value="Finland">Finland</option>
                                                          <option value="France">France</option>
                                                          <option value="France Metropolitan">France, Metropolitan
                                                          </option>
                                                          <option value="French Guiana">French Guiana</option>
                                                          <option value="French Polynesia">French Polynesia</option>
                                                          <option value="French Southern Territories">French Southern
                                                              Territories</option>
                                                          <option value="Gabon">Gabon</option>
                                                          <option value="Gambia">Gambia</option>
                                                          <option value="Georgia">Georgia</option>
                                                          <option value="Germany">Germany</option>
                                                          <option value="Ghana">Ghana</option>
                                                          <option value="Gibraltar">Gibraltar</option>
                                                          <option value="Greece">Greece</option>
                                                          <option value="Greenland">Greenland</option>
                                                          <option value="Grenada">Grenada</option>
                                                          <option value="Guadeloupe">Guadeloupe</option>
                                                          <option value="Guam">Guam</option>
                                                          <option value="Guatemala">Guatemala</option>
                                                          <option value="Guinea">Guinea</option>
                                                          <option value="Guinea-Bissau">Guinea-Bissau</option>
                                                          <option value="Guyana">Guyana</option>
                                                          <option value="Haiti">Haiti</option>
                                                          <option value="Heard and McDonald Islands">Heard and Mc Donald
                                                              Islands</option>
                                                          <option value="Holy See">Holy See (Vatican City State)</option>
                                                          <option value="Honduras">Honduras</option>
                                                          <option value="Hong Kong">Hong Kong</option>
                                                          <option value="Hungary">Hungary</option>
                                                          <option value="Iceland">Iceland</option>
                                                          <option value="India">India</option>
                                                          <option value="Indonesia">Indonesia</option>
                                                          <option value="Iran">Iran (Islamic Republic of)</option>
                                                          <option value="Iraq">Iraq</option>
                                                          <option value="Ireland">Ireland</option>
                                                          <option value="Israel">Israel</option>
                                                          <option value="Italy">Italy</option>
                                                          <option value="Jamaica">Jamaica</option>
                                                          <option value="Japan">Japan</option>
                                                          <option value="Jordan">Jordan</option>
                                                          <option value="Kazakhstan">Kazakhstan</option>
                                                          <option value="Kenya">Kenya</option>
                                                          <option value="Kiribati">Kiribati</option>
                                                          <option value="Democratic People's Republic of Korea">Korea,
                                                              Democratic People's Republic of</option>
                                                          <option value="Korea">Korea, Republic of</option>
                                                          <option value="Kuwait">Kuwait</option>
                                                          <option value="Kyrgyzstan">Kyrgyzstan</option>
                                                          <option value="Lao">Lao People's Democratic Republic</option>
                                                          <option value="Latvia">Latvia</option>
                                                          <option value="Lebanon">Lebanon</option>
                                                          <option value="Lesotho">Lesotho</option>
                                                          <option value="Liberia">Liberia</option>
                                                          <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya
                                                          </option>
                                                          <option value="Liechtenstein">Liechtenstein</option>
                                                          <option value="Lithuania">Lithuania</option>
                                                          <option value="Luxembourg">Luxembourg</option>
                                                          <option value="Macau">Macau</option>
                                                          <option value="Macedonia">Macedonia, The Former Yugoslav
                                                              Republic of</option>
                                                          <option value="Madagascar">Madagascar</option>
                                                          <option value="Malawi">Malawi</option>
                                                          <option value="Malaysia">Malaysia</option>
                                                          <option value="Maldives">Maldives</option>
                                                          <option value="Mali">Mali</option>
                                                          <option value="Malta">Malta</option>
                                                          <option value="Marshall Islands">Marshall Islands</option>
                                                          <option value="Martinique">Martinique</option>
                                                          <option value="Mauritania">Mauritania</option>
                                                          <option value="Mauritius">Mauritius</option>
                                                          <option value="Mayotte">Mayotte</option>
                                                          <option value="Mexico">Mexico</option>
                                                          <option value="Micronesia">Micronesia, Federated States of
                                                          </option>
                                                          <option value="Moldova">Moldova, Republic of</option>
                                                          <option value="Monaco">Monaco</option>
                                                          <option value="Mongolia">Mongolia</option>
                                                          <option value="Montserrat">Montserrat</option>
                                                          <option value="Morocco">Morocco</option>
                                                          <option value="Mozambique">Mozambique</option>
                                                          <option value="Myanmar">Myanmar</option>
                                                          <option value="Namibia">Namibia</option>
                                                          <option value="Nauru">Nauru</option>
                                                          <option value="Nepal">Nepal</option>
                                                          <option value="Netherlands">Netherlands</option>
                                                          <option value="Netherlands Antilles">Netherlands Antilles
                                                          </option>
                                                          <option value="New Caledonia">New Caledonia</option>
                                                          <option value="New Zealand">New Zealand</option>
                                                          <option value="Nicaragua">Nicaragua</option>
                                                          <option value="Niger">Niger</option>
                                                          <option value="Nigeria">Nigeria</option>
                                                          <option value="Niue">Niue</option>
                                                          <option value="Norfolk Island">Norfolk Island</option>
                                                          <option value="Northern Mariana Islands">Northern Mariana
                                                              Islands</option>
                                                          <option value="Norway">Norway</option>
                                                          <option value="Oman">Oman</option>
                                                          <option value="Pakistan">Pakistan</option>
                                                          <option value="Palau">Palau</option>
                                                          <option value="Panama">Panama</option>
                                                          <option value="Papua New Guinea">Papua New Guinea</option>
                                                          <option value="Paraguay">Paraguay</option>
                                                          <option value="Peru">Peru</option>
                                                          <option value="Philippines">Philippines</option>
                                                          <option value="Pitcairn">Pitcairn</option>
                                                          <option value="Poland">Poland</option>
                                                          <option value="Portugal">Portugal</option>
                                                          <option value="Puerto Rico">Puerto Rico</option>
                                                          <option value="Qatar">Qatar</option>
                                                          <option value="Reunion">Reunion</option>
                                                          <option value="Romania">Romania</option>
                                                          <option value="Russia">Russian Federation</option>
                                                          <option value="Rwanda">Rwanda</option>
                                                          <option value="Saint Kitts and Nevis">Saint Kitts and Nevis
                                                          </option>
                                                          <option value="Saint LUCIA">Saint LUCIA</option>
                                                          <option value="Saint Vincent">Saint Vincent and the Grenadines
                                                          </option>
                                                          <option value="Samoa">Samoa</option>
                                                          <option value="San Marino">San Marino</option>
                                                          <option value="Sao Tome and Principe">Sao Tome and Principe
                                                          </option>
                                                          <option value="Saudi Arabia">Saudi Arabia</option>
                                                          <option value="Senegal">Senegal</option>
                                                          <option value="Seychelles">Seychelles</option>
                                                          <option value="Sierra">Sierra Leone</option>
                                                          <option value="Singapore">Singapore</option>
                                                          <option value="Slovakia">Slovakia (Slovak Republic)</option>
                                                          <option value="Slovenia">Slovenia</option>
                                                          <option value="Solomon Islands">Solomon Islands</option>
                                                          <option value="Somalia">Somalia</option>
                                                          <option value="South Africa">South Africa</option>
                                                          <option value="South Georgia">South Georgia and the South
                                                              Sandwich Islands</option>
                                                          <option value="Span">Spain</option>
                                                          <option value="SriLanka">Sri Lanka</option>
                                                          <option value="St. Helena">St. Helena</option>
                                                          <option value="St. Pierre and Miguelon">St. Pierre and Miquelon
                                                          </option>
                                                          <option value="Sudan">Sudan</option>
                                                          <option value="Suriname">Suriname</option>
                                                          <option value="Svalbard">Svalbard and Jan Mayen Islands</option>
                                                          <option value="Swaziland">Swaziland</option>
                                                          <option value="Sweden">Sweden</option>
                                                          <option value="Switzerland">Switzerland</option>
                                                          <option value="Syria">Syrian Arab Republic</option>
                                                          <option value="Taiwan">Taiwan, Province of China</option>
                                                          <option value="Tajikistan">Tajikistan</option>
                                                          <option value="Tanzania">Tanzania, United Republic of</option>
                                                          <option value="Thailand">Thailand</option>
                                                          <option value="Togo">Togo</option>
                                                          <option value="Tokelau">Tokelau</option>
                                                          <option value="Tonga">Tonga</option>
                                                          <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                                          <option value="Tunisia">Tunisia</option>
                                                          <option value="Turkey">Turkey</option>
                                                          <option value="Turkmenistan">Turkmenistan</option>
                                                          <option value="Turks and Caicos">Turks and Caicos Islands
                                                          </option>
                                                          <option value="Tuvalu">Tuvalu</option>
                                                          <option value="Uganda">Uganda</option>
                                                          <option value="Ukraine">Ukraine</option>
                                                          <option value="United Arab Emirates">United Arab Emirates
                                                          </option>
                                                          <option value="United Kingdom">United Kingdom</option>
                                                          <option value="United States">United States</option>
                                                          <option value="United States Minor Outlying Islands">United
                                                              States Minor Outlying Islands</option>
                                                          <option value="Uruguay">Uruguay</option>
                                                          <option value="Uzbekistan">Uzbekistan</option>
                                                          <option value="Vanuatu">Vanuatu</option>
                                                          <option value="Venezuela">Venezuela</option>
                                                          <option value="Vietnam">Viet Nam</option>
                                                          <option value="Virgin Islands (British)">Virgin Islands
                                                              (British)</option>
                                                          <option value="Virgin Islands (U.S)">Virgin Islands (U.S.)
                                                          </option>
                                                          <option value="Wallis and Futana Islands">Wallis and Futuna
                                                              Islands</option>
                                                          <option value="Western Sahara">Western Sahara</option>
                                                          <option value="Yemen">Yemen</option>
                                                          <option value="Yugoslavia">Yugoslavia</option>
                                                          <option value="Zambia">Zambia</option>
                                                          <option value="Zimbabwe">Zimbabwe</option>
                                                      </select>
                                                      <div class="invalid-feedback">
                                                          Please Select Country
                                                      </div>
  
  
  
                                                      <div class="mt-3 row">
                                                          <div class="col-6">
                                                              <button onclick="back()"
                                                                  class="btn btn-primary w-100 waves-effect waves-light"
                                                                  type="button">Back</button>
                                                          </div>
                                                          <div class="col-6">
                                                              <button onclick="next()"
                                                                  class="btn btn-primary w-100 waves-effect waves-light"
                                                                  type="button">Next</button>
                                                          </div>
                                                      </div>
  
  
                                                  </div>
  
  
  
  
                                                  <div class="mt-3 mb-3 step step5" style="display:none">
  
                                                      <div class="form-check">
                                                          <input class="form-check-input" type="checkbox" required
                                                              id="remember-check">
                                                          <label class="form-check-label" for="remember-check">
                                                              Acknowledge that the information provided is valid.
                                                          </label>
                                                      </div>


                                                        <div class="form-check">
                                                          <input class="form-check-input" type="checkbox" required
                                                              id="remember-check-2">
                                                          <label class="form-check-label" for="remember-check-2">
                                                              By clicking this box, you agree to our <a href="../terms">terms and conditions</a>, <a href="../risk">risk disclosures</a>, and <a href="../privacy">privacy policy</a>.
                                                          </label>
                                                      </div>
  
  
  
                                                      <div class="mt-3 row">
                                                          <div class="col-6">
                                                              <button onclick="back()"
                                                                  class="btn btn-primary w-100 waves-effect waves-light"
                                                                  type="button">Back</button>
                                                          </div>
                                                          <div class="col-6">
                                                              <button
                                                                  class="btn btn-primary w-100 waves-effect waves-light"
                                                                  name="register" type="submit">Register</button>
                                                          </div>
                                                      </div>
  
                                                  </div>
  
  
  
  
                                              </div>
  
  
  
                                          </div>
  
  
  
                                      </form>

<p class="qs-auth-foot">Already a member? <a href="index">Sign in</a></p>
<p class="qs-auth-note">By creating an account you acknowledge that trading involves risk and that license fees do not represent an investment or guaranteed return.</p>
<?php
include('inc/auth-close.php');
include('inc/auth-scripts.php');
?>
<script>
    function next() {
        var step = $('#steps').val();

        var nextstep = parseInt(step) + 1 // parseInt(step)+1;

        console.log(nextstep);

        $('.step').hide();
        $('.step' + nextstep).show();

        $('#steps').val(nextstep);

        $('#progress').css('width', (nextstep * 20) + '%')


    }

    function back() {

        var step = $('#steps').val();

        var nextstep = parseInt(step) - 1;

        console.log(nextstep);

        $('.step').hide();
        $('.step' + nextstep).show();

        $('#steps').val(nextstep);

        $('#progress').css('width', (nextstep * 20) + '%')




    }




    $("#confirm, #password").keyup(function() {
        var password = $("#password").val();
        var confirm = $("#confirm").val();

        if (password != confirm || password == "") {
            $("#isconfirm").text("Passwords do not match");
            $("#password").css("border-color", "#9b1d1d");
            $("#confirm").css("border-color", "#9b1d1d");
        } else {
            $("#isconfirm").text("Passwords match");
            $("#password").css("border-color", "green");
            $("#confirm").css("border-color", "green");

        }


    });



    $("#password-addon2").on("click", function() {
        0 < $(this).siblings("input").length && ("password" == $(this).siblings("input").attr("type") ? $(this)
            .siblings("input").attr("type", "input") : $(this).siblings("input").attr("type", "password"))
    });



    //************************************************ */
    //get the content of image
    var input = document.querySelector('input[type=file]');

    input.onchange = function() {
        var file = input.files[0];

        //trying to validate image before upload


        img = new Image();
        var imgwidth = 0;
        var imgheight = 0;

        img.src = URL.createObjectURL(file);


        //function to prepare image
        drawOnCanvas(file);

        //function to display image
        displayAsImage(file);






    };




    function drawOnCanvas(file) {
        var reader = new FileReader();

        reader.onload = function(e) {

            var dataURL = e.target.result,
                c = document.querySelector('canvas'),
                ctx = c.getContext('2d'),
                img = new Image();

            img.onload = function() {

                c.width = img.width;
                c.height = img.height;
                ctx.drawImage(img, 0, 0);

            };

            img.src = dataURL;


        };


        reader.readAsDataURL(file);


    }


    function displayAsImage(file) {
        var imgURL = URL.createObjectURL(file),
            img = document.createElement('img');

        img.onload = function() {

            URL.revokeObjectURL(imgURL);
        };

        img.src = imgURL;
        img.width = "250";


        //adding the image into content for preview
        document.getElementById('content').innerHTML = "";

        document.getElementById('content').append(img);

    }
    </script>






    <?php

    include_once("email-handler.php");

if(isset($_POST['register'])){

//retrive all the input from user

$firstname =  mysqli_real_escape_string($mysqli,$_POST['firstname']);

$lastname =  mysqli_real_escape_string($mysqli,$_POST['lastname']);
$email = mysqli_real_escape_string($mysqli,$_POST['email']);

$email = filter_var($email, FILTER_SANITIZE_EMAIL);

$password = mysqli_real_escape_string($mysqli,$_POST['password']);

$country = mysqli_real_escape_string($mysqli,$_POST['country']);

$hashpassword = password_hash($password, PASSWORD_DEFAULT);

//set location that image should b uploaded to
// $target_locate = "img/idcard/";
	
// //the image with full path
// $idcard = $target_locate.basename($_FILES["idcard"]["name"]);
// $temp = explode(".", $_FILES["idcard"]["name"]);
// $idcard = $target_locate.round(microtime(true)) . '.' . end($temp);





if (filter_var($email, FILTER_VALIDATE_EMAIL)){


$phone = mysqli_real_escape_string($mysqli,$_POST['phone']);

$date= date("d")." ".date("F")." ".date("Y")." , ".date("h")." : ".date("i").date("a");

//check if email exist aready
$check_email = mysqli_query($mysqli,"SELECT id FROM users WHERE email='$email'");

//if zero does not exist proceed to register
if(mysqli_num_rows($check_email) < 1 ){

    //check if user was reffered
    $refer = "";
    if(!empty($_POST['refer'])){
        $refer = $_POST['refer'];

        //dere is a referre //update d refer dat his referree has registered
   //add to activity

 $date= date("d")." ".date("F")." ".date("Y")." , ".date("h")." : ".date("i").date("a");
 $action = "New Referral registered";
 $describe ="New referral registed as ".$firstname."  ";
 
 //check if email exist aready
$checkg = mysqli_query($mysqli,"SELECT id, email, firstname FROM users WHERE referal_link='$refer'");
$rr = mysqli_fetch_assoc($checkg);
 $userid = $rr['id'];

 $add = mysqli_query($mysqli,"INSERT INTO `activity`(`userid`, `action`, `describe`, `date`, `status`) VALUES('$userid', '$action', '$describe', '$date','Registered') ");
 




    /*
    
$curl = curl_init();
    
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.mailjet.com/v3.1/send",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>'{
    "SandboxMode": false,
    "Messages": [
        {
            "From": {
                "Email": "info@quantumscalp.io",
                "Name": "Quantum Scalp"
            },
            "To": [
                {
                    "Email": "'.$rr['email'].'",
                    "Name": ""
                }
            ],
            
            "Subject": "Congratulations, you have a new referral.",
            "TextPart": "",
            "HTMLPart": "",
           
            "TemplateLanguage": true,
          
            "TrackOpens": "account_default",
            "TrackClicks": "account_default"
            
        }
    ]
}',
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/json",
    "Authorization: Basic NjIwMjNlMDUxZDlhNzMzNzU4MGY1NWU5OGZiMjczM2E6MzRmZmNjZjgxZDhmMDFjNDcwNzE1NjMwYzMyODhiZjE="
  ),
));

$response = curl_exec($curl);

curl_close($curl);

*/






 
    }



    //generate referer code for this person
   $code ="";
                                            do{
                                        
                                                            //generate code for user verification
                                                $code = "QS-".mt_rand(138998, 999998);
                                                
                                                $search2 = mysqli_query($mysqli, "SELECT * FROM users WHERE referal_link='$code'");



                                                
                                            }while(mysqli_num_rows($search2) > 0);


//now generate 2 factor autherication code for this user
require_once("google_authenticator/index.php"); 

$g = new \Google\Authenticator\GoogleAuthenticator();

$secret = $g->generateSecret();

$two_fa_link = $g->getURL($email, 'quantumscalp.io', $secret);

//now we can insert /register the user
$idcard='';

$reg = mysqli_query($mysqli,"INSERT INTO `users`(firstname, lastname, email, `password`, phone, idcard, 2fa_key, 2fa_link, referal_link, referred, refer_date, country, img) VALUES('$firstname',  '$lastname', '$email', '$hashpassword', '$phone',  '$idcard', '$secret', '$two_fa_link', '$code', '$refer', '$date', '$country', 'img/profile.png' ) ");


if($reg ){



//email will be sent here to admin starting a new user just registtered
$name = $firstname." ".$lastname;

 //start email sending

  
    

$admins = [
    'quantumscalp@proton.me',

    'jiffy16@protonmail.com'
];

sendAdminNotificationRegistration($admins, $name, $email);

sendVerificationEmail(
    $email,

    $name,

    'https://quantumscalp.io/account/verify?email=' .($email) . '&token='
);
   



    //echo $response;





?>
    <script>
   
    notif({
		msg: "<b>Registration Successful</b> <br/> Check your email for verification link.",
		width: 250,
		position: "center",
		type: "success",
		fade: true
	});

    setTimeout(() => {
        // location = 'index';
    }, 5000);
    </script>

    <?php


}



}else{
//it exist stop and throw alert

?>
    <script>
  

    notif({
		msg: "<b>Email Already Exist</b> <br/> This email is registered with another account!",
		width: 250,
		position: "center",
		type: "error",
		fade: true
	});
    </script>

    <?php
}





}else{
    //its zero email does not exit show error
    
    ?>
     <script>
     
    
    notif({
		msg: "<b>Not a Valid Email Address</b> <br/> This is not a valid email Address!",
		width: 250,
		position: "center",
		type: "error",
		fade: true
	});



    
     </script>
    
     <?php
    
    
    }
  

  


}



?>



</body>

</html>