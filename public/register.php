<?php
declare(strict_types=1);
require dirname(__DIR__) . '/src/core/Session.php';
require dirname(__DIR__) . '/src/core/Csrf.php';
use Tayo\Core\Session;
use Tayo\Core\Csrf;
Session::start();
$csrfToken = Csrf::token();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create your account | TAYO-TECH</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/auth.css">
</head>
<body>

<div class="auth-page">

  <!-- Side panel -->
  <aside class="auth-side">
    <div class="brand">
      <img src="assets/img/logo.png" alt="TAYO-TECH">
      <span>TAYO-TECH</span>
    </div>
    <div>
      <h2>Join Tanzania's youth-tech community.</h2>
      <p>One profile connects you to jobs, training, mentorship and funding opportunities across the country.</p>
      <ul class="auth-points">
        <li><span class="dot">✓</span> Apply to verified jobs &amp; internships</li>
        <li><span class="dot">✓</span> Get matched with mentors in your field</li>
        <li><span class="dot">✓</span> Access training, grants and events first</li>
      </ul>
    </div>
    <p style="color:#94a3b8;font-size:0.8rem;">&copy; <?= date('Y') ?> TAYO-TECH — Tanzania Youth-Tech Forum</p>
  </aside>

  <!-- Main form column -->
  <main class="auth-main">
    <div class="auth-topbar">
      <a href="index.php"><img src="assets/img/logo.png" alt="TAYO-TECH">TAYO-TECH</a>
      <a href="login.php" class="auth-topbar-link">Already have an account? Log in</a>
    </div>

    <div class="auth-card">
      <h1>Create your TAYO-TECH account</h1>
      <p class="auth-sub">It takes about 5 minutes. You can review everything before submitting.</p>

      <!-- Stepper -->
      <div class="stepper" id="stepper">
        <div class="step-item active" data-step="1">
          <div class="step-circle">1</div>
          <div class="step-label">Personal &amp; Contact</div>
        </div>
        <div class="step-line"></div>
        <div class="step-item" data-step="2">
          <div class="step-circle">2</div>
          <div class="step-label">Career &amp; Education</div>
        </div>
        <div class="step-line"></div>
        <div class="step-item" data-step="3">
          <div class="step-circle">3</div>
          <div class="step-label">Account &amp; Documents</div>
        </div>
      </div>

      <div id="formBanner"></div>

      <form id="regForm" novalidate autocomplete="off">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

        <!-- ======================= STEP 1 ======================= -->
        <section class="wizard-step" data-step="1">

          <div class="form-section-title">A. Personal Information</div>
          <div class="form-grid">
            <div class="field"><label for="first_name">First Name</label>
              <input id="first_name" name="first_name" required maxlength="100">
              <div class="field-error" data-error-for="first_name"></div></div>

            <div class="field"><label for="middle_name">Middle Name <span class="optional">(Optional)</span></label>
              <input id="middle_name" name="middle_name" maxlength="100">
              <div class="field-error" data-error-for="middle_name"></div></div>

            <div class="field"><label for="last_name">Last Name</label>
              <input id="last_name" name="last_name" required maxlength="100">
              <div class="field-error" data-error-for="last_name"></div></div>

            <div class="field"><label for="gender">Gender</label>
              <select id="gender" name="gender" required>
                <option value="">Select…</option>
                <option value="female">Female</option>
                <option value="male">Male</option>
                <option value="other">Other</option>
                <option value="prefer_not_to_say">Prefer not to say</option>
              </select>
              <div class="field-error" data-error-for="gender"></div></div>

            <div class="field"><label for="date_of_birth">Date of Birth</label>
              <input id="date_of_birth" name="date_of_birth" type="date" required>
              <div class="field-error" data-error-for="date_of_birth"></div></div>

            <div class="field"><label for="nationality">Nationality</label>
              <input id="nationality" name="nationality" required value="Tanzanian" maxlength="100">
              <div class="field-error" data-error-for="nationality"></div></div>

            <div class="field"><label for="national_id">National ID (NIDA) <span class="optional">(Optional)</span></label>
              <input id="national_id" name="national_id" maxlength="30">
              <div class="field-error" data-error-for="national_id"></div></div>

            <div class="field"><label for="passport_number">Passport Number <span class="optional">(For non-Tanzanians)</span></label>
              <input id="passport_number" name="passport_number" maxlength="30">
              <div class="field-error" data-error-for="passport_number"></div></div>
          </div>

          <div class="form-section-title">B. Contact Information</div>
          <div class="form-grid">
            <div class="field"><label for="mobile_phone">Mobile Phone Number</label>
              <input id="mobile_phone" name="mobile_phone" required placeholder="+255 7XX XXX XXX">
              <div class="field-error" data-error-for="mobile_phone"></div></div>

            <div class="field"><label for="alt_phone">Alternative Phone <span class="optional">(Optional)</span></label>
              <input id="alt_phone" name="alt_phone" placeholder="+255 7XX XXX XXX">
              <div class="field-error" data-error-for="alt_phone"></div></div>

            <div class="field full"><label for="email">Email Address</label>
              <input id="email" name="email" type="email" required>
              <div class="field-error" data-error-for="email"></div></div>

            <div class="field"><label for="country">Country</label>
              <input id="country" name="country" value="Tanzania" required>
              <div class="field-error" data-error-for="country"></div></div>

            <div class="field"><label for="region">Region</label>
              <input id="region" name="region" required placeholder="e.g. Dar es Salaam">
              <div class="field-error" data-error-for="region"></div></div>

            <div class="field"><label for="district">District</label>
              <input id="district" name="district" required placeholder="e.g. Kinondoni">
              <div class="field-error" data-error-for="district"></div></div>

            <div class="field"><label for="ward">Ward</label>
              <input id="ward" name="ward" required placeholder="e.g. Msasani">
              <div class="field-error" data-error-for="ward"></div></div>

            <div class="field"><label for="street_village">Street / Village</label>
              <input id="street_village" name="street_village" required>
              <div class="field-error" data-error-for="street_village"></div></div>

            <div class="field"><label for="house_number">House Number <span class="optional">(Optional)</span></label>
              <input id="house_number" name="house_number">
              <div class="field-error" data-error-for="house_number"></div></div>

            <div class="field"><label for="postal_address">Postal Address <span class="optional">(Optional)</span></label>
              <input id="postal_address" name="postal_address">
              <div class="field-error" data-error-for="postal_address"></div></div>

            <div class="field"><label for="zip_code">ZIP / Postal Code <span class="optional">(Optional)</span></label>
              <input id="zip_code" name="zip_code">
              <div class="field-error" data-error-for="zip_code"></div></div>
          </div>

          <div class="wizard-actions">
            <span class="spacer"></span>
            <button type="button" class="btn btn-primary" data-next>Continue</button>
          </div>
        </section>

        <!-- ======================= STEP 2 ======================= -->
        <section class="wizard-step" data-step="2" hidden>

          <div class="form-section-title">C. Occupation &amp; Professional Information</div>
          <div class="form-grid">
            <div class="field"><label for="occupation">Occupation <span class="field-hint">— what you do now</span></label>
              <input id="occupation" name="occupation" required placeholder="e.g. Student, Software Developer">
              <div class="field-error" data-error-for="occupation"></div></div>

            <div class="field"><label for="profession">Profession <span class="field-hint">— what you trained for</span></label>
              <input id="profession" name="profession" placeholder="e.g. Computer Engineering">
              <div class="field-error" data-error-for="profession"></div></div>

            <div class="field full"><label>Employment Status</label>
              <div class="radio-group" id="employmentStatusGroup">
                <label class="radio-pill"><input type="radio" name="employment_status" value="student"><span>Student</span></label>
                <label class="radio-pill"><input type="radio" name="employment_status" value="employed"><span>Employed</span></label>
                <label class="radio-pill"><input type="radio" name="employment_status" value="self_employed"><span>Self-employed</span></label>
                <label class="radio-pill"><input type="radio" name="employment_status" value="unemployed"><span>Unemployed</span></label>
                <label class="radio-pill"><input type="radio" name="employment_status" value="freelancer"><span>Freelancer</span></label>
              </div>
              <div class="field-error" data-error-for="employment_status"></div></div>

            <div class="field"><label for="organization_name">Organization/Company <span class="optional">(Optional)</span></label>
              <input id="organization_name" name="organization_name">
              <div class="field-error" data-error-for="organization_name"></div></div>

            <div class="field"><label for="job_title">Job Title / Position</label>
              <input id="job_title" name="job_title">
              <div class="field-error" data-error-for="job_title"></div></div>

            <div class="field"><label for="years_experience">Years of Experience</label>
              <input id="years_experience" name="years_experience" type="number" min="0" max="60" value="0">
              <div class="field-error" data-error-for="years_experience"></div></div>
          </div>

          <div class="form-section-title">D. Education Information</div>
          <div class="form-grid">
            <div class="field"><label for="education_level">Highest Education Level</label>
              <select id="education_level" name="education_level" required>
                <option value="">Select…</option>
                <option value="primary">Primary</option>
                <option value="secondary">Secondary</option>
                <option value="certificate">Certificate</option>
                <option value="diploma">Diploma</option>
                <option value="bachelor">Bachelor's Degree</option>
                <option value="master">Master's Degree</option>
                <option value="phd">PhD</option>
                <option value="other">Other</option>
              </select>
              <div class="field-error" data-error-for="education_level"></div></div>

            <div class="field"><label for="institution_name">Institution Name</label>
              <input id="institution_name" name="institution_name" required>
              <div class="field-error" data-error-for="institution_name"></div></div>

            <div class="field"><label for="programme_course">Programme / Course</label>
              <input id="programme_course" name="programme_course">
              <div class="field-error" data-error-for="programme_course"></div></div>

            <div class="field"><label for="specialization">Specialization</label>
              <input id="specialization" name="specialization">
              <div class="field-error" data-error-for="specialization"></div></div>

            <div class="field"><label for="graduation_year">Graduation Year</label>
              <input id="graduation_year" name="graduation_year" type="number" min="1950" max="2100" placeholder="2025">
              <div class="field-error" data-error-for="graduation_year"></div></div>

            <div class="field"><label for="certifications">Professional Certifications <span class="optional">(Optional, comma-separated)</span></label>
              <input id="certifications" name="certifications" placeholder="AWS Cloud Practitioner, PMP">
              <div class="field-error" data-error-for="certifications"></div></div>
          </div>

          <div class="form-section-title">E. Skills &amp; Career Information</div>
          <div class="form-grid">
            <div class="field"><label for="technical_skills">Technical Skills <span class="field-hint">(comma-separated)</span></label>
              <input id="technical_skills" name="technical_skills" placeholder="JavaScript, PHP, Data Analysis">
              <div class="field-error" data-error-for="technical_skills"></div></div>

            <div class="field"><label for="soft_skills">Soft Skills <span class="field-hint">(comma-separated)</span></label>
              <input id="soft_skills" name="soft_skills" placeholder="Communication, Teamwork">
              <div class="field-error" data-error-for="soft_skills"></div></div>

            <div class="field"><label for="languages_spoken">Languages Spoken <span class="field-hint">(comma-separated)</span></label>
              <input id="languages_spoken" name="languages_spoken" placeholder="Swahili, English">
              <div class="field-error" data-error-for="languages_spoken"></div></div>

            <div class="field"><label for="career_interests">Career Interests <span class="field-hint">(comma-separated)</span></label>
              <input id="career_interests" name="career_interests" placeholder="Software Development, Data Science">
              <div class="field-error" data-error-for="career_interests"></div></div>

            <div class="field full"><label for="preferred_job_category">Preferred Job Category</label>
              <input id="preferred_job_category" name="preferred_job_category" placeholder="e.g. Software Engineering">
              <div class="field-error" data-error-for="preferred_job_category"></div></div>
          </div>

          <div class="form-section-title">F. Entrepreneurship Information <span class="optional">(Optional)</span></div>
          <div class="form-grid">
            <div class="field full"><label>Are you a business owner?</label>
              <div class="radio-group">
                <label class="radio-pill"><input type="radio" name="is_business_owner" value="1"><span>Yes</span></label>
                <label class="radio-pill checked"><input type="radio" name="is_business_owner" value="0" checked><span>No</span></label>
              </div>
            </div>
            <div class="field" id="businessNameField" hidden><label for="business_name">Business Name</label>
              <input id="business_name" name="business_name">
              <div class="field-error" data-error-for="business_name"></div></div>
            <div class="field" id="businessSectorField" hidden><label for="business_sector">Business Sector</label>
              <input id="business_sector" name="business_sector">
              <div class="field-error" data-error-for="business_sector"></div></div>
            <div class="field full" id="businessRegField" hidden><label for="business_registration_number">Business Registration Number <span class="optional">(Optional)</span></label>
              <input id="business_registration_number" name="business_registration_number">
              <div class="field-error" data-error-for="business_registration_number"></div></div>
          </div>

          <div class="wizard-actions">
            <button type="button" class="btn btn-outline" data-back>Back</button>
            <span class="spacer"></span>
            <button type="button" class="btn btn-primary" data-next>Continue</button>
          </div>
        </section>

        <!-- ======================= STEP 3 ======================= -->
        <section class="wizard-step" data-step="3" hidden>

          <div class="form-section-title">G. Account Information</div>
          <div class="form-grid">
            <div class="field"><label for="username">Username</label>
              <input id="username" name="username" required maxlength="30" autocomplete="off">
              <div class="field-error" data-error-for="username"></div></div>

            <div class="field"><label for="security_question">Security Question</label>
              <input id="security_question" name="security_question" required placeholder="e.g. Your first pet's name">
              <div class="field-error" data-error-for="security_question"></div></div>

            <div class="field"><label for="password">Password</label>
              <input id="password" name="password" type="password" required minlength="8">
              <div class="password-strength"><span id="pwStrengthBar"></span></div>
              <div class="field-error" data-error-for="password"></div></div>

            <div class="field"><label for="confirm_password">Confirm Password</label>
              <input id="confirm_password" name="confirm_password" type="password" required minlength="8">
              <div class="field-error" data-error-for="confirm_password"></div></div>

            <div class="field full"><label for="security_answer">Security Answer</label>
              <input id="security_answer" name="security_answer" required>
              <div class="field-error" data-error-for="security_answer"></div></div>
          </div>

          <div class="form-section-title">H. Upload Documents</div>
          <div class="form-grid">
            <div class="field full">
              <label for="passport_photo">Passport Size Photo</label>
              <label class="file-drop" for="passport_photo">
                <span class="file-icon">📷</span>
                <span class="file-meta"><strong id="passportPhotoName">Click to upload photo</strong>JPG or PNG, up to 5MB</span>
              </label>
              <input type="file" id="passport_photo" name="passport_photo" accept="image/jpeg,image/png" style="display:none" required>
              <div class="field-error" data-error-for="passport_photo"></div>
            </div>

            <div class="field full">
              <label for="cv">CV / Resume</label>
              <label class="file-drop" for="cv">
                <span class="file-icon">📄</span>
                <span class="file-meta"><strong id="cvName">Click to upload CV</strong>PDF or DOCX, up to 5MB</span>
              </label>
              <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx" style="display:none" required>
              <div class="field-error" data-error-for="cv"></div>
            </div>

            <div class="field full">
              <label for="academic_certificates">Academic Certificates <span class="optional">(Optional)</span></label>
              <label class="file-drop" for="academic_certificates">
                <span class="file-icon">🎓</span>
                <span class="file-meta"><strong id="academicName">Click to upload certificates</strong>PDF, multiple files allowed</span>
              </label>
              <input type="file" id="academic_certificates" name="academic_certificates[]" accept=".pdf,image/jpeg,image/png" multiple style="display:none">
            </div>

            <div class="field full">
              <label for="professional_certificates">Professional Certificates <span class="optional">(Optional)</span></label>
              <label class="file-drop" for="professional_certificates">
                <span class="file-icon">🏅</span>
                <span class="file-meta"><strong id="professionalName">Click to upload certificates</strong>PDF, multiple files allowed</span>
              </label>
              <input type="file" id="professional_certificates" name="professional_certificates[]" accept=".pdf,image/jpeg,image/png" multiple style="display:none">
            </div>
          </div>

          <div class="form-section-title">I. Verification</div>
          <div class="form-grid">
            <div class="field full checkbox-row">
              <input type="checkbox" id="agree_terms" name="agree_terms" value="1" required>
              <label for="agree_terms" style="font-weight:400;">I agree to the <a href="#" target="_blank">Terms &amp; Conditions</a></label>
            </div>
            <div class="field full checkbox-row">
              <input type="checkbox" id="agree_privacy" name="agree_privacy" value="1" required>
              <label for="agree_privacy" style="font-weight:400;">I agree to the <a href="#" target="_blank">Privacy Policy</a></label>
            </div>
            <div class="field full">
              <div class="captcha-box">
                <input type="checkbox" id="captcha_checkbox" required>
                <label for="captcha_checkbox" style="font-weight:600;">I'm not a robot</label>
              </div>
              <input type="hidden" id="captcha_token" name="captcha_token" value="">
              <div class="field-error" data-error-for="captcha_token"></div>
            </div>
          </div>

          <div class="wizard-actions">
            <button type="button" class="btn btn-outline" data-back>Back</button>
            <span class="spacer"></span>
            <button type="reset" class="btn btn-ghost">Reset</button>
            <button type="submit" class="btn btn-primary" id="submitBtn">Register</button>
          </div>
        </section>
      </form>

      <p class="auth-footer-link">Already registered? <a href="login.php">Back to Login</a></p>
    </div>
  </main>
</div>

<script src="assets/js/register-wizard.js"></script>
</body>
</html>
