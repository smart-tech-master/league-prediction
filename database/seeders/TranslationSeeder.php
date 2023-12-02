<?php

namespace Database\Seeders;

use App\Models\Locale;
use App\Models\Translation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;

class TranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $translations = [];

        $langs = collect([
            'auth' => File::getRequire(lang_path('en/auth.php')),
            'pagination' => File::getRequire(lang_path('en/pagination.php')),
            'passwords' => File::getRequire(lang_path('en/passwords.php')),
            'validation' => File::getRequire(lang_path('en/validation.php'))
        ])
            ->all();

        foreach ($langs as $file => $values) {

            foreach ($values as $key => $value) {
                if (!in_array($key, ['custom', 'attributes'])) { // validation.php
                    if (is_array($value)) {
                        foreach ($value as $key2 => $value2) {
                            $data = [];
                            foreach (Locale::all() as $locale) {
                                $data = array_merge($data, [$locale->code => $value2]);
                            }
                            $translations[$file . '.' . $key . '.' . $key2] = $data;
                        }
                    } else {
                        $data = [];
                        foreach (Locale::all() as $locale) {
                            $data = array_merge($data, [$locale->code => $value]);
                        }
                        $translations[$file . '.' . $key] = $data;
                    }
                }
            }
        }

        $translations = array_merge($translations, [
            "others.welcome_back" => [
                "en" => "Welcome Back",
                "ar" => "مرحبًا بعودتك"
            ],
            "others. login_with" => [
                "en" => "Username/Email",
                "ar" => "اسم المستخدم / البريد الإلكتروني"
            ],
            "others.password" => [
                "en" => "Password",
                "ar" => "كلمه السر"
            ],
            "others.forgot_password" => [
                "en" => "Forgot Password?",
                "ar" => "هل نسيت كلمة السر؟"
            ],
            "others.signin" => [
                "en" => "SIGN IN",
                "ar" => "تسجيل الدخول"
            ],
            "others.google" => [
                "en" => "Google",
                "ar" => "غوغل"
            ],
            "others.apple" => [
                "en" => "Apple",
                "ar" => "تفاحة"
            ],
            "others.dont_account" => [
                "en" => "Don't have an account yet?",
                "ar" => "لا تملك حسابا حتى الآن؟"
            ],
            "others.create_account" => [
                "en" => "CREATE AN ACCOUNT",
                "ar" => "انشئ حساب"
            ],
            "others.skip" => [
                "en" => "SKIP",
                "ar" => "يتخطى"
            ],
            "others.invalid_email_or_username" => [
                "en" => "Please provide email/username",
                "ar" => "يرجى تقديم البريد الإلكتروني / اسم المستخدم"
            ],
            "others.empty_password" => [
                "en" => "Password field is required",
                "ar" => "حقل كلمة المرور مطلوب"
            ],
            "others.password_invalid" => [
                "en" => "Password cannot be smaller than 6 character",
                "ar" => "لا يمكن أن تكون كلمة المرور أصغر من 6 أحرف"
            ],
            "others.new_notification" => [
                "en" => "New notification from Prediction",
                "ar" => "إشعار جديد من التنبؤ"
            ],
            "others.press_to_exit" => [
                "en" => "Press Back button again to Exit",
                "ar" => "اضغط على الزر \"رجوع\" مرة أخرى للخروج"
            ],
            "others.home" => [
                "en" => "Home",
                "ar" => "الصفحة الرئيسية"
            ],
            "others.rank" => [
                "en" => "Rank",
                "ar" => "الرتب"
            ],
            "others.my_leagues" => [
                "en" => "My Leagues",
                "ar" => "بطولاتي"
            ],
            "others.my_profile" => [
                "en" => "My Profile",
                "ar" => "ملفي"
            ],
            "others.settings" => [
                "en" => "Settings",
                "ar" => "إعدادات"
            ],
            "others.create_cup" => [
                "en" => "Create Cup",
                "ar" => "صنع الكأس"
            ],
            "others.select_type" => [
                "en" => "Select Type",
                "ar" => "اختر صنف"
            ],
            "others.cup_title" => [
                "en" => "Cup Title",
                "ar" => "عنوان الكأس"
            ],
            "others.type" => [
                "en" => "Type",
                "ar" => "يكتب"
            ],
            "others.select_start_round" => [
                "en" => "Select Start Round",
                "ar" => "حدد بدء الجولة"
            ],
            "others.select" => [
                "en" => "Select",
                "ar" => "يختار"
            ],
            "others.no_of_participation" => [
                "en" => "No Of Participants",
                "ar" => "عدد المشاركين"
            ],
            "others.type_of_competition" => [
                "en" => "Type Of Competition",
                "ar" => "نوع المنافسة"
            ],
            "others.description_optional" => [
                "en" => "Description(Optional)",
                "ar" => "وصف (اختياري)"
            ],
            "others.play_for" => [
                "en" => "Play For?",
                "ar" => "اللعب لصالح؟"
            ],
            "others.phone_number" => [
                "en" => "Add Phone Number Or Email",
                "ar" => "أضف رقم الهاتف أو البريد الإلكتروني"
            ],
            "others.create_cup_upper" => [
                "en" => "CREATE CUP",
                "ar" => "اصنع الكأس"
            ],
            "others.cup_details" => [
                "en" => "Cup Details",
                "ar" => "تفاصيل الكأس"
            ],
            "others.east" => [
                "en" => "EAST",
                "ar" => "الشرق"
            ],
            "others.pts" => [
                "en" => "Pts",
                "ar" => "نقاط"
            ],
            "others.vs" => [
                "en" => "VS",
                "ar" => "ضد"
            ],
            "others.west" => [
                "en" => "WEST",
                "ar" => "الغرب"
            ],
            "others.yes" => [
                "en" => "Yes",
                "ar" => "نعم"
            ],
            "others.cancel" => [
                "en" => "Cancel",
                "ar" => "يلغي"
            ],
            "others.login_first" => [
                "en" => "Please log in first",
                "ar" => "الرجاء تسجيل الدخول أولا"
            ],
            "others.alart" => [
                "en" => "Alart",
                "ar" => "انذار"
            ],
            "others.subscription_failed" => [
                "en" => "Subscription failed!",
                "ar" => "فشل الاشتراك!"
            ],
            "others.wait" => [
                "en" => "Please wait...",
                "ar" => "أرجو الإنتظار..."
            ],
            "others.subscribe" => [
                "en" => "Subscribe League",
                "ar" => "اشترك في الدوري"
            ],
            "others.subscribebody" => [
                "en" => "Are you sure want to subscribe to this league?",
                "ar" => "هل أنت متأكد أنك تريد الاشتراك في هذا الدوري؟"
            ],
            "others.startprediction" => [
                "en" => "START PREDICTION",
                "ar" => "بدء التنبؤ"
            ],
            "others.create_league" => [
                "en" => "Create League",
                "ar" => "أنشئ دوري"
            ],
            "others.league_title" => [
                "en" => "League Title",
                "ar" => "لقب الدوري"
            ],
            "others.please_select" => [
                "en" => "Please select the type of the league",
                "ar" => "الرجاء تحديد نوع الدوري"
            ],
            "others.please_title" => [
                "en" => "Please enter league title",
                "ar" => "الرجاء إدخال لقب الدوري"
            ],
            "others.please_play_for" => [
                "en" => "Please choose play for?",
                "ar" => "الرجاء اختيار اللعب؟"
            ],
            "others.please_phone" => [
                "en" => "Please enter phone number or email",
                "ar" => "الرجاء إدخال رقم الهاتف أو البريد الإلكتروني"
            ],
            "others.please_general" => [
                "en" => "Please select General or Private",
                "ar" => "الرجاء تحديد عام أو خاص"
            ],
            "others.please_participants" => [
                "en" => "Please select number of participants",
                "ar" => "الرجاء تحديد عدد المشاركين"
            ],
            "others.league_successfull" => [
                "en" => "Private League created successfully",
                "ar" => "تم إنشاء الدوري الخاص بنجاح"
            ],
            "others.league_create_upper" => [
                "en" => "CREATE LEAGUE",
                "ar" => "إنشاء الدوري"
            ],
            "others.league_details" => [
                "en" => "League Details",
                "ar" => "تفاصيل الدوري"
            ],
            "others.owner" => [
                "en" => "Owner",
                "ar" => "صاحب"
            ],
            "others.joined" => [
                "en" => "Joined",
                "ar" => "انضم"
            ],
            "others.your_ranked" => [
                "en" => "Your Rank",
                "ar" => "رتبتك"
            ],
            "others.winner" => [
                "en" => "Winner",
                "ar" => "الفائز"
            ],
            "others.status" => [
                "en" => "Status",
                "ar" => "حالة"
            ],
            "others.username" => [
                "en" => "Username",
                "ar" => "اسم المستخدم"
            ],
            "others.round_point" => [
                "en" => "Round Points",
                "ar" => "نقاط الجولة"
            ],
            "others.total_points" => [
                "en" => "Total Points",
                "ar" => "مجمل النقاط"
            ],
            "others.search" => [
                "en" => "Search",
                "ar" => "يبحث"
            ],
            "others.full_name" => [
                "en" => "Full Name",
                "ar" => "الاسم الكامل"
            ],
            "others.country" => [
                "en" => "Country",
                "ar" => "دولة"
            ],
            "others.sign_up" => [
                "en" => "SIGN UP",
                "ar" => "اشتراك"
            ],
            "others.sign_up_success" => [
                "en" => "Successfully Registered",
                "ar" => "سجلت بنجاح"
            ],
            "others.sign_up_success_body" => [
                "en" => "You are successfully registered! We sent an email on your email address which you entered here!",
                "ar" => "لقد تم تسجيلك بنجاح! لقد أرسلنا بريدًا إلكترونيًا على عنوان بريدك الإلكتروني الذي أدخلته هنا!"
            ],
            "others.ok" => [
                "en" => "OK",
                "ar" => "نعم"
            ],
            "others.notification" => [
                "en" => "Notification",
                "ar" => "تنبيه"
            ],
            "others.joining_app" => [
                "en" => "Joined the App",
                "ar" => "انضم إلى التطبيق"
            ],
            "others.league" => [
                "en" => "Leagues",
                "ar" => "الدوريات"
            ],
            "others.private_league" => [
                "en" => "Private Leagues",
                "ar" => "البطولات الخاصة"
            ],
            "others.private_cup" => [
                "en" => "Private Cups",
                "ar" => "كؤوس خاصة"
            ],
            "others.likes" => [
                "en" => "Likes",
                "ar" => "الإعجابات"
            ],
            "others.bio" => [
                "en" => "Bio",
                "ar" => "السيرة الذاتية"
            ],
            "others.edit" => [
                "en" => "Edit",
                "ar" => "يحرر"
            ],
            "others.profile_details" => [
                "en" => "Profile Details and Information",
                "ar" => "تفاصيل الملف الشخصي والمعلومات"
            ],
            "others.change_password" => [
                "en" => "Change Password",
                "ar" => "غير كلمة السر"
            ],
            "others.point" => [
                "en" => "Points",
                "ar" => "نقاط"
            ],
            "others.earned" => [
                "en" => "You Earned",
                "ar" => "لقد حصلت على"
            ],
            "others.total_user" => [
                "en" => "Total User",
                "ar" => "إجمالي المستخدم"
            ],
            "others.your_point" => [
                "en" => "Your Point",
                "ar" => "وجهة نظرك"
            ],
            "others.top" => [
                "en" => "Top",
                "ar" => "قمة"
            ],
            "others.contact_us" => [
                "en" => "Contact Us",
                "ar" => "اتصل بنا"
            ],
            "others.get_in_touch" => [
                "en" => "Get In Touch",
                "ar" => "ابقى على تواصل"
            ],
            "others.follow_us" => [
                "en" => "FOLLOW US",
                "ar" => "تابعنا"
            ],
            "others.drop_us_a_message" => [
                "en" => "DROP US A MESSAGE",
                "ar" => "اترك لنا رسالة"
            ],
            "others.name" => [
                "en" => "Name*",
                "ar" => "اسم*"
            ],
            "others.phone" => [
                "en" => "Phone*",
                "ar" => "هاتف*"
            ],
            "others.email" => [
                "en" => "Email*",
                "ar" => "البريد الإلكتروني*"
            ],
            "others.your_message" => [
                "en" => "Your Message*",
                "ar" => "رسالتك*"
            ],
            "others.send" => [
                "en" => "SEND",
                "ar" => "إرسال"
            ],
            "others.privacy_policy" => [
                "en" => "Privacy Policy",
                "ar" => "سياسة الخصوصية"
            ],
            "others.language" => [
                "en" => "Language",
                "ar" => "لغة"
            ],
            "others.terms_and_conditions" => [
                "en" => "Terms and Conditions",
                "ar" => "الأحكام والشروط"
            ],
            "others.about_us" => [
                "en" => "About Us",
                "ar" => "معلومات عنا"
            ],
            "others.logout" => [
                "en" => "Logout",
                "ar" => "تسجيل خروج"
            ],
            "others.version" => [
                "en" => "Version 1.0.0",
                "ar" => "الإصدار 1.0.0"
            ],
            "others.empty_username" => [
                "en" => "Username field is required!",
                "ar" => "مطلوب حقل اسم المستخدم!"
            ],
            "others.invalid_username" => [
                "en" => "Username is invalid!",
                "ar" => "إسم المستخدم غير صحيح!"
            ],
            "others.empty_confirm_password" => [
                "en" => "Confirm Password field is required!",
                "ar" => "مطلوب حقل تأكيد كلمة المرور!"
            ],
            "others.confirm_not_matched" => [
                "en" => "Confirm password not matched",
                "ar" => "تأكيد كلمة المرور غير متطابقة"
            ],
            "others.empty_fullname" => [
                "en" => "Please enter full name",
                "ar" => "الرجاء إدخال الاسم الكامل"
            ],
            "others.invalid_fullname" => [
                "en" => "Please enter valid full name",
                "ar" => "الرجاء إدخال الاسم الكامل الصحيح"
            ],
            "others.empty_email" => [
                "en" => "Email field is required!",
                "ar" => "حقل البريد الإلكتروني مطلوب!"
            ],
            "others.invalid_email" => [
                "en" => "Email is invalid!",
                "ar" => "البريد الإلكتروني غير صالح!"
            ],
            "others.empty_date" => [
                "en" => "Date of birth field is required!",
                "ar" => "تاريخ الميلاد الحقل مطلوب!"
            ],
            "others.empty_country" => [
                "en" => "Country field is required!",
                "ar" => "حقل البلد مطلوب!"
            ],
            "others.confirm_password" => [
                "en" => "Confirm Password",
                "ar" => "تأكيد كلمة المرور"
            ],
            "others.by" => [
                "en" => "By creating an account you agree to our ",
                "ar" => "من خلال إنشاء حساب فإنك توافق على"
            ],
            "others.and" => [
                "en" => " and ",
                "ar" => "و"
            ],
            "others.submit_prediction" => [
                "en" => "SUBMIT PREDICTION",
                "ar" => "إرسال التنبؤ"
            ],
        ]);


        foreach ($translations as $key => $value) {
            $translation = new Translation();
            $translation->key = $key;
            foreach (Locale::all() as $locale) {
                $translation->setTranslation('text', $locale->code, $value[$locale->code] ?? null);
            }
            $translation->save();
        }
    }
}
