<?php

/**
 * *********************************************************************
 * THIS FILE IS SYSTEM HELPER, PLEASE REFRAIN FROM MAKING
 * ANY CHANGES TO THIS FILE UNLESS YOU KNOW WHAT YOU ARE DOING.
 * *********************************************************************
 */

/**
 * Generate any form field with floating label
 * @param string $id
 * @param array $configuration
 * @param int|string|array|null $current_value (optional)
 * @return void
 */
function generate_form_field(string $id, array $configuration, int|string|array $current_value = null): void
{
    $input_type = $configuration['type'];
    $required   = (@$configuration['required'] ? 'required' : '');
    $readonly   = (@$configuration['readonly'] ? 'readonly' : '');
    $disabled   = (@$configuration['disabled'] ? 'disabled' : '');
    $min        = (is_numeric(@$configuration['min']) ? "min='{$configuration['min']}'" : '');
    $max        = (is_numeric(@$configuration['max']) ? "max='{$configuration['max']}'" : '');
    $minlength  = (@$configuration['minlength'] ? "minlength='{$configuration['minlength']}'" : '');
    $maxlength  = (@$configuration['maxlength'] ? "maxlength='{$configuration['maxlength']}'" : '');
    $label      = (isset($configuration['label_key']) ? lang($configuration['label_key']) : (empty($configuration['label']) ? '' : $configuration['label']));
    if (in_array($input_type, ['text', 'email', 'password', 'number', 'date', 'time', 'datetime-local', 'month', 'week', 'url', 'search', 'color'])) {
        $placeholder = @$configuration['placeholder'] ?? '';
        $value = (!is_null($current_value) && '0000-00-00' != $current_value ? "value='{$current_value}'" : (!empty($configuration['default']) ? "value='{$configuration['default']}'" : ''));
        echo "<div class='form-floating mb-3'><input type='{$input_type}' class='form-control' id='{$id}' name='{$id}' placeholder='{$placeholder}' $value $required $readonly $disabled $min $minlength $max $maxlength><label for='{$id}'>" . $label . "</label>";
        if (!empty($configuration['details'])) {
            echo "<small class='form-text text-muted small'>" . lang($configuration['details']) . "</small>";
        }
        echo "</div>";
    } else if ('tel' == $input_type) {
        $country_codes = lang('ListCallingCode.codes');
        echo "<div class='input-group mb-3'><span class='input-group-text'>+</span>";
        echo "<div class='form-floating'><select class='form-select' id='{$configuration['country_code_field']}' name='{$configuration['country_code_field']}' $required $readonly $disabled>";
        echo "<option value=''></option>";
        foreach ($country_codes as $codes) {
            echo '<option value="' . $codes['code'] . '" ' . ($current_value[0] == $codes['code'] ? 'selected' : '') . '>' . $codes['label'] . ', ' . $codes['code_label'] . '</option>';
        }
        echo "</select><label for='{$configuration['country_code_field']}'>" . lang($configuration['country_code_label']) . "</label></div>";
        echo "<div class='form-floating'><input type='tel' class='form-control' id='{$configuration['phone_number_field']}' name='{$configuration['phone_number_field']}' placeholder='{$configuration['placeholder']}' value='{$current_value[1]}' $required $readonly $disabled $min $minlength $max $maxlength>";
        echo "<label for='{$configuration['phone_number_field']}'>" . lang($configuration['phone_number_label']) . "</label>";
        echo "</div></div>";
    } else if ('hidden' == $input_type) {
        echo '<input type="hidden" id="' . $id . '" name="' . $id . '" value="' . @$current_value . '">';
    } else if ('select' == $input_type) {
        $options = $configuration['options'];
        echo "<div class='form-floating mb-3'><select class='form-select' id='{$id}' name='{$id}' $required $readonly $disabled>";
        echo "<option value=''></option>";
        foreach ($options as $key => $value) {
            $selected  = ($current_value == $key ? 'selected' : '');
            $str_value = lang($value);
            echo "<option value='{$key}' $selected>" . $str_value . "</option>";
        }
        echo "</select><label for='{$id}'>" . $label . "</label></div>";
//    } else if ('textarea' == $input_type) {
//        $placeholder = @$configuration['placeholder'] ?? '';
//        echo "<div class='form-floating mb-3'><textarea class='form-control' id='{$id}' name='{$id}' placeholder='{$placeholder}' $required $readonly $disabled $min $minlength $max $maxlength>" . @$current_value . "</textarea><label for='{$id}'>" . lang($configuration['label_key']) . "</label></div>";
    }
}

/**
 * Generate the label with value in a .col div
 * @param string $label
 * @param string $value
 * @param string $type Either 'text' or 'datetime' for now
 * @return void
 */
function generate_label_column_from_field(string $label, string $value, string $type = 'text'): void
{
    echo "<div class='col'><small>{$label}</small><br>";
    if ('datetime' == $type) {
        if (empty(trim($value))) {
            echo '-';
        } else {
            $value = str_replace(' ', 'T', $value) . 'Z';
            echo "<span class='utc-to-local-time'>{$value}</span>";
        }
    } else {
        echo (empty(trim($value)) ? '-' : trim($value));
    }
    echo "</div>";
}

/**
 * Retrieve app logo or the styled app name
 * @param string $app_name
 * @return string
 */
function retrieve_app_logo(string $app_name): string
{
    $clean_name = preg_replace('/[^a-z0-9]/i', '', strtolower($app_name));
    $file_url   = base_url('file/logo_' . $clean_name . '.png');
    $file_path  = WRITEPATH . 'uploads/logo_' . $clean_name . '.png';
    if (file_exists($file_path)) {
        return '<img class="img-fluid" src="' . $file_url . '" alt="' . $app_name . '" />';
    }
    return '<span class="app-logo-text">' . $app_name . '</span>';
}

/**
 * Create avatar
 * @param string $email_address
 * @param string $first_name
 * @param string $last_name
 * @return string
 */
function retrieve_avatars(string $email_address, string $first_name, string $last_name): string
{
    $email_address  = preg_replace('/[^a-z0-9]/i', '', strtolower($email_address));
    $file_url       = base_url('file/profile_picture_' . $email_address . '.jpg');
    $file_path      = WRITEPATH . 'uploads/profile_pictures/profile_' . $email_address . '.jpg';
    if (file_exists($file_path)) {
        return "<img src='" . $file_url . "' class='avatar-img' title='$first_name $last_name' data-bs-toggle='tooltip' data-bs-placement='top'>";
    }
    $hash = hash('md5', $email_address . $first_name . $last_name);
    $color = '#' . substr($hash, 0, 6);
    $r = hexdec(substr($hash, 0, 2));
    $g = hexdec(substr($hash, 2, 2));
    $b = hexdec(substr($hash, 4, 2));
    $avg = (($r/255*100) + ($g/255*100) + ($b/255*100))/3;
    $text_color = '#fff';
    if ($avg > 50) {
        $text_color = '#000';
    }
    $initials = strtoupper(substr($first_name, 0, 1) . substr($last_name, 0, 1));
    return "<div class='avatar-txt' style='background-color:$color;color:$text_color' title='$first_name $last_name' data-bs-toggle='tooltip' data-bs-placement='top'>$initials</div>";
}

/**
 * Retrieve common password
 * @return string
 */
function retrieve_common_password(): string
{
    return '123|password|qwerty|111|letmein|1q2w3e|aaa|football|iloveyou|admin|princess|dragon|welcome|hello|world|master';
}

/**
 * Format phone numbers for some supported countries
 * @param string $country_code
 * @param string $phone_number
 * @return string
 */
function format_phone_number(string $country_code, string $phone_number): string
{
    if ('+1' == $country_code) {
        // United States
        return '+1 (' . substr($phone_number, 0, 3) . ') ' . substr($phone_number, 3, 3) . ' ' . substr($phone_number, 6);
    } else if ('+66' == $country_code) {
        // Thailand
        if (str_starts_with($phone_number, '0')) {
            $phone_number = substr($phone_number, 1);
        }
        if (8 == strlen($phone_number)) {
            return '+66-' . substr($phone_number, 0, 1) . '-' . substr($phone_number, 1, 3) . '-' . substr($phone_number, 4);
        }
        return '+66-' . substr($phone_number, 0, 2) . '-' . substr($phone_number, 2, 3) . '-' . substr($phone_number, 5);
    } else if ('+65' == $country_code) {
        // Singapore
        return '+65 ' . substr($phone_number, 0, 4) . ' ' . substr($phone_number, 4);
    }
    return $country_code . $phone_number;
}

/**
 * Status of the journey
 * @param string $status
 * @param string $start
 * @param string $end
 * @param string $today (optional)
 * @return string
 */
function translate_journey_status(string $status, string $start, string $end, string $today = ''): string
{
    if ('canceled' == $status) {
        return '<span class="badge bg-danger">Canceled</span>';
    }
    if (empty($today)) {
        $today = date('Y-m-d');
    }
    if ($today < $start) {
        return '<span class="badge bg-info">Upcoming</span>';
    } elseif (!empty($end) && $today > $end) {
        return '<span class="badge bg-success">Completed</span>';
    }
    return '<span class="badge bg-warning">Ongoing</span>';
}

/**
 * @param float $number
 * @return string
 */
function number_format_india(float $number): string
{
// Check negative
    $negative = FALSE;
    if (0 > $number)
    {
        $negative = TRUE;
    }
    $number = abs($number);
    // Check decimal point in $number
    $number_in_cents = $number * 100;
    $cents           = $number_in_cents % 100;
    // Make $number an integer then convert it to string
    $number     = round($number);
    $number     = (string) $number;
    // Get the last group of 3
    $last_group = substr($number, -3);
    // For the rest of the number, split them into the group of 2. If the length is odd, the first group will have 1 digit
    $rest       = substr($number, 0, -3);
    // Check $rest's length
    $rest_len   = strlen($rest);
    $str_amt    = $last_group . '.' . $cents;
    if (1 == $rest_len)
    {
        $str_amt = $rest . ',' . $str_amt;
    } else if (1 < $rest_len)
    {
        if (0 == $rest_len % 2)
        {
            $rest_len_split = str_split($rest, 2);
            $str_amt        = implode(',', $rest_len_split) . ',' . $str_amt;
        } else
        {
            $first_group    = substr($rest, 0, 1);
            $middle_group   = substr($rest, 1);
            $middle_group   = str_split($middle_group, 2);
            $str_amt        = $first_group . ',' . implode(',', $middle_group) . ',' . $str_amt;
        }
    }
    if ($negative)
    {
        return '-' . $str_amt;
    }
    return $str_amt;
}

/**
 * @param string $currency
 * @param float $amount
 * @return string
 */
function currency_format(string $currency_code, float $amount): string
{
    $currency_format = [
        // Americas
        'CAD' => 'C$ ###',
        'MXN' => 'Mex$ ###',
        'USD' => '$ ###',
        // East Asia
        'JPY' => '### 円',
        'KRW' => '### 원',
        'TWD' => '### 元',
        // SEA
        'BND' => 'B$ ###',
        'IDR' => 'Rp ###',
        'KHR' => '៛ ###',
        'LAK' => '₭ ###',
        'MMK' => 'K ###',
        'MYR' => '<small>RM</small> ###',
        'PHP' => '₱ ###',
        'SGD' => 'S$ ###',
        'THB' => '### ฿',
        'VND' => '### ₫',
        // Europe
        'CHF' => 'fr. ###',
        'EUR' => '€ ###',
        'GBP' => '£ ###',
        // Oceania
        'AUD' => 'A$ ###',
        'FJD' => 'FJ$ ###',
        'NZD' => 'NZ$ ###',
        'WST' => 'ST ###',
        // South Asia
        'BDT' => '৳ ###',
        'BTN' => 'Nu. ###',
        'INR' => '₹ ###',
        'LKR' => 'රු. ###',
        'MVR' => 'Rf ###',
        'NPR' => 'रू ###',
        'PKR' => 'Rs ###',
    ];
    // Check negative
    $negative = FALSE;
    if (0 > $amount)
    {
        $negative = TRUE;
    }
    $amount = abs($amount);
    // Check for support
    if ( ! isset($currency_format[$currency_code]))
    {
        // Not supported: $currency_code . ' ' . number_format($amount, 2)
        if ($negative)
        {
            return '<span class="text-danger">-' . $currency_code . ' ' . number_format($amount, 2) . '</span>';
        }
        return $currency_code . ' ' . number_format($amount, 2);
    }
    // Use another function for India
    if ('INR' == $currency_code)
    {
        $str_amount = number_format_india($amount);
        if ($negative)
        {
            return '<span class="text-danger">-' . str_replace('###', $str_amount, $currency_format[$currency_code]) . '</span>';
        }
        return str_replace('###', $str_amount, $currency_format[$currency_code]);
    }
    // Separators
    $currency_with_swap_dots = ['IDR', 'VND', 'EUR', 'CHF'];
    $thousand_separator = ',';
    $decimal_separator  = '.';
    if (in_array($currency_code, $currency_with_swap_dots))
    {
        $thousand_separator = '.';
        $decimal_separator  = ',';
    }
    // Decimals
    $decimals = 2;
    if (in_array($currency_code, ['IDR', 'JPY', 'KRW', 'VND']))
    {
        $decimals = 0;
    }
    $formatted_amount = '-';
    if (0 != $amount)
    {
        $formatted_amount = number_format($amount, $decimals, $decimal_separator, $thousand_separator);
    }
    if ($negative)
    {
        return '<span class="text-danger">-' . str_replace('###', $formatted_amount, $currency_format[$currency_code]) . '</span>';
    }
    return str_replace('###', $formatted_amount, $currency_format[$currency_code]);
}

/**
 * @param int $minutes
 * @return string
 */
function minute_format(int $minutes): string
{
    // split into days, hours, minutes
    $d = floor($minutes / 1440);
    $h = floor(($minutes - $d * 1440) / 60);
    $m = $minutes % 60;
    return ($d > 0 ? $d . 'd ' : '') . ($h > 0 ? $h . 'h ' : '') . ($m > 0 ? $m . 'm' : '');
}

/**
 * Get role icons
 * @param string $role
 * @param bool $show_role_name
 * @return string|array
 */
function get_role_icons(string $role = '', bool $show_role_name = FALSE): string|array
{
    $roles = [
        'super-admin'  => ['<i class="fa-solid fa-screwdriver-wrench fa-2x"></i>', 'Super Admin'],
        'master-admin' => ['<i class="fa-brands fa-black-tie fa-2x"></i>', 'Master Admin'],
        'finance'      => ['<i class="fa-solid fa-hand-holding-dollar fa-2x"></i>', 'Finance'],
        'health'       => ['<i class="fa-solid fa-heart-pulse fa-2x"></i>', 'Health'],
        'journey'      => ['<i class="fa-solid fa-person-walking-luggage fa-2x"></i>', 'Journey'],
        'migrate'      => ['<i class="fa-solid fa-plane fa-2x fa-rotate-by" style="--fa-rotate-angle: -45deg;"></i>', 'Migrate'],
        'profile'      => ['<i class="fa-regular fa-id-badge fa-2x"></i>', 'Profile'],
    ];
    if (empty($role)) {
        return $roles;
    }
    if ($show_role_name) {
        return ($roles[$role] ? $roles[$role][0] . '<br>' . $roles[$role][1] : $role);
    }
    return $roles[$role][0] ?? $role;
}