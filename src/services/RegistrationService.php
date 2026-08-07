<?php
declare(strict_types=1);

namespace Tayo\Services;

use Tayo\Core\Database;
use Tayo\Core\Session;
use Tayo\Core\Validator;
use Tayo\Models\Address;
use Tayo\Models\BusinessInfo;
use Tayo\Models\Document;
use Tayo\Models\EducationInfo;
use Tayo\Models\OccupationInfo;
use Tayo\Models\SkillsInfo;
use Tayo\Models\User;

/**
 * Owns the 3-step registration wizard. Steps 1-2 validate and stage data in
 * the PHP session (`reg_draft`); step 3 validates account/consent/docs and
 * writes everything in a single DB transaction. See docs/ARCHITECTURE.md §5
 * for why the draft is session-based rather than a DB table in the MVP.
 */
final class RegistrationService
{
    private const SESSION_KEY = 'reg_draft';

    /** @return array{errors: array} empty array on success */
    public static function step1(array $input): array
    {
        $v = new Validator($input);
        $v->required('first_name', 'First name')->string('first_name', 'First name', 1, 100)
          ->string('middle_name', 'Middle name', 0, 100)
          ->required('last_name', 'Last name')->string('last_name', 'Last name', 1, 100)
          ->required('gender', 'Gender')->in('gender', 'Gender', ['male', 'female', 'other', 'prefer_not_to_say'])
          ->required('date_of_birth', 'Date of birth')->date('date_of_birth', 'Date of birth')
          ->required('nationality', 'Nationality')->string('nationality', 'Nationality', 2, 100)
          ->required('mobile_phone', 'Mobile phone number')->phone('mobile_phone', 'Mobile phone number')
          ->phone('alt_phone', 'Alternative phone number')
          ->required('email', 'Email address')->email('email', 'Email address')
          ->required('region', 'Region')->required('district', 'District')
          ->required('ward', 'Ward')->required('street_village', 'Street/Village');

        $errors = $v->errors();

        if (empty($errors['email']) && !empty($input['email']) && User::emailExists($input['email'])) {
            $errors['email'] = 'This email address is already registered.';
        }
        if (empty($errors['mobile_phone']) && !empty($input['mobile_phone']) && User::phoneExists($input['mobile_phone'])) {
            $errors['mobile_phone'] = 'This phone number is already registered.';
        }
        // National ID / passport uniqueness is also enforced at the DB level
        // (UNIQUE constraint) and re-checked on step 3 as a final guard.

        if ($errors !== []) {
            return ['errors' => $errors];
        }

        $draft = Session::get(self::SESSION_KEY, []);
        $draft['personal'] = [
            'first_name' => trim($input['first_name']),
            'middle_name' => trim($input['middle_name'] ?? ''),
            'last_name' => trim($input['last_name']),
            'gender' => $input['gender'],
            'date_of_birth' => $input['date_of_birth'],
            'nationality' => trim($input['nationality']),
            'national_id' => trim($input['national_id'] ?? ''),
            'passport_number' => trim($input['passport_number'] ?? ''),
        ];
        $draft['contact'] = [
            'mobile_phone' => trim($input['mobile_phone']),
            'alt_phone' => trim($input['alt_phone'] ?? ''),
            'email' => strtolower(trim($input['email'])),
        ];
        $draft['address'] = [
            'country' => trim($input['country'] ?? 'Tanzania'),
            'region' => trim($input['region']),
            'district' => trim($input['district']),
            'ward' => trim($input['ward']),
            'street_village' => trim($input['street_village']),
            'house_number' => trim($input['house_number'] ?? ''),
            'postal_address' => trim($input['postal_address'] ?? ''),
            'zip_code' => trim($input['zip_code'] ?? ''),
        ];
        Session::put(self::SESSION_KEY, $draft);

        return ['errors' => []];
    }

    public static function step2(array $input): array
    {
        $v = new Validator($input);
        $v->required('occupation', 'Occupation')
          ->required('employment_status', 'Employment status')
          ->in('employment_status', 'Employment status', ['student', 'employed', 'self_employed', 'unemployed', 'freelancer'])
          ->required('education_level', 'Highest education level')
          ->in('education_level', 'Highest education level', ['primary', 'secondary', 'certificate', 'diploma', 'bachelor', 'master', 'phd', 'other'])
          ->required('institution_name', 'Institution name')
          ->integer('graduation_year', 'Graduation year', 1950, (int) date('Y') + 1)
          ->integer('years_experience', 'Years of experience', 0, 60);

        $isOwner = !empty($input['is_business_owner']) && $input['is_business_owner'] !== '0';
        if ($isOwner) {
            $v->required('business_name', 'Business name')->required('business_sector', 'Business sector');
        }

        if ($v->fails()) {
            return ['errors' => $v->errors()];
        }

        $draft = Session::get(self::SESSION_KEY, []);
        if (empty($draft['personal'])) {
            return ['errors' => ['_step' => 'Please complete step 1 first.']];
        }

        $draft['occupation'] = [
            'occupation' => trim($input['occupation']),
            'profession' => trim($input['profession'] ?? ''),
            'employment_status' => $input['employment_status'],
            'organization_name' => trim($input['organization_name'] ?? ''),
            'job_title' => trim($input['job_title'] ?? ''),
            'years_experience' => (int) ($input['years_experience'] ?? 0),
        ];
        $draft['education'] = [
            'education_level' => $input['education_level'],
            'institution_name' => trim($input['institution_name']),
            'programme_course' => trim($input['programme_course'] ?? ''),
            'specialization' => trim($input['specialization'] ?? ''),
            'graduation_year' => $input['graduation_year'] ?: null,
            'certifications' => array_values(array_filter(array_map('trim', (array) ($input['certifications'] ?? [])))),
        ];
        $draft['skills'] = [
            'technical_skills' => self::splitList($input['technical_skills'] ?? ''),
            'soft_skills' => self::splitList($input['soft_skills'] ?? ''),
            'languages_spoken' => self::splitList($input['languages_spoken'] ?? ''),
            'career_interests' => self::splitList($input['career_interests'] ?? ''),
            'preferred_job_category' => trim($input['preferred_job_category'] ?? ''),
        ];
        $draft['business'] = [
            'is_business_owner' => $isOwner,
            'business_name' => trim($input['business_name'] ?? ''),
            'business_sector' => trim($input['business_sector'] ?? ''),
            'business_registration_number' => trim($input['business_registration_number'] ?? ''),
        ];
        Session::put(self::SESSION_KEY, $draft);

        return ['errors' => []];
    }

    /**
     * @param array $files Raw $_FILES entries for passport_photo, cv,
     *                      academic_certificates[], professional_certificates[]
     * @return array{errors: array, user_id?: string}
     */
    public static function step3(array $input, array $files, array $config): array
    {
        $draft = Session::get(self::SESSION_KEY, []);
        if (empty($draft['personal']) || empty($draft['occupation'])) {
            return ['errors' => ['_step' => 'Your session expired — please start registration again.']];
        }

        $v = new Validator($input);
        $v->required('username', 'Username')->string('username', 'Username', 4, 30)
          ->required('password', 'Password')->password('password', 'Password')
          ->matches('confirm_password', 'password', 'Confirm password')
          ->required('security_question', 'Security question')
          ->required('security_answer', 'Security answer')
          ->boolTrue('agree_terms', 'the Terms & Conditions')
          ->boolTrue('agree_privacy', 'the Privacy Policy');

        $errors = $v->errors();
        if (empty($errors['username']) && !preg_match('/^[a-zA-Z0-9_.]{4,30}$/', (string) $input['username'])) {
            $errors['username'] = 'Username may only contain letters, numbers, dots and underscores.';
        }
        if (empty($errors['username']) && User::usernameExists($input['username'])) {
            $errors['username'] = 'This username is already taken.';
        }
        if (!Validator::verifyCaptcha($input['captcha_token'] ?? null)) {
            $errors['captcha_token'] = 'Please confirm you are not a robot.';
        }
        if (empty($files['passport_photo']) || ($files['passport_photo']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            $errors['passport_photo'] = 'Passport-size photo is required.';
        }
        if (empty($files['cv']) || ($files['cv']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            $errors['cv'] = 'CV/Resume is required.';
        }

        if ($errors !== []) {
            return ['errors' => $errors];
        }

        $pdo = Database::connection();
        $pdo->beginTransaction();
        try {
            $userId = User::create($pdo, $draft['personal'], $draft['contact'], [
                'username' => trim($input['username']),
                'password' => $input['password'],
                'security_question' => trim($input['security_question']),
                'security_answer' => $input['security_answer'],
            ]);

            Address::create($pdo, $userId, $draft['address']);
            OccupationInfo::create($pdo, $userId, $draft['occupation']);
            EducationInfo::create($pdo, $userId, $draft['education']);
            SkillsInfo::create($pdo, $userId, $draft['skills']);
            BusinessInfo::create($pdo, $userId, $draft['business']);

            self::storeDocument($pdo, $userId, $files['passport_photo'], 'photo', $config);
            self::storeDocument($pdo, $userId, $files['cv'], 'cv', $config);
            foreach (self::normalizedMulti($files['academic_certificates'] ?? null) as $f) {
                self::storeDocument($pdo, $userId, $f, 'academic_certificate', $config);
            }
            foreach (self::normalizedMulti($files['professional_certificates'] ?? null) as $f) {
                self::storeDocument($pdo, $userId, $f, 'professional_certificate', $config);
            }

            $audit = $pdo->prepare(
                "INSERT INTO audit_log (user_id, action, metadata, ip_address) VALUES (:uid, 'user.registered', '{}', :ip)"
            );
            $audit->execute(['uid' => $userId, 'ip' => $_SERVER['REMOTE_ADDR'] ?? null]);

            $pdo->commit();
        } catch (\Throwable $e) {
            $pdo->rollBack();
            error_log('[REGISTRATION ERROR] ' . $e->getMessage());
            return ['errors' => ['_server' => 'Something went wrong while creating your account. Please try again.']];
        }

        Session::forget(self::SESSION_KEY);
        return ['errors' => [], 'user_id' => $userId];
    }

    private static function storeDocument(\PDO $pdo, string $userId, array $file, string $docType, array $config): void
    {
        $stored = FileUploadService::store(
            $file,
            $userId,
            $config['upload']['allowed_mime'],
            $config['upload']['max_bytes']
        );
        Document::create($pdo, $userId, $docType, $stored['path'], $stored['name'], $stored['mime'], $stored['size']);
    }

    /** Normalizes the PHP multi-file $_FILES['field'][...] shape into a list of single-file arrays. */
    private static function normalizedMulti(?array $field): array
    {
        if ($field === null || !isset($field['name']) || !is_array($field['name'])) {
            return [];
        }
        $out = [];
        foreach ($field['name'] as $i => $name) {
            if ($name === '' || ($field['error'][$i] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
                continue;
            }
            $out[] = [
                'name' => $field['name'][$i],
                'type' => $field['type'][$i],
                'tmp_name' => $field['tmp_name'][$i],
                'error' => $field['error'][$i],
                'size' => $field['size'][$i],
            ];
        }
        return $out;
    }

    private static function splitList(string $csv): array
    {
        return array_values(array_filter(array_map('trim', explode(',', $csv))));
    }
}
