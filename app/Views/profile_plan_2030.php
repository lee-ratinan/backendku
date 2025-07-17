<?php
function get_ccy($currency): string
{
    $ccy = [
        'SGD' => 'S$',
        'THB' => '฿',
        'AUD' => 'A$',
        'USD' => 'US$',
        'IDR' => 'Rp'
    ];
    return $ccy[$currency];
}
function add_amount($number, $currency, &$amount): string
{
    $amount[$currency] += $number;
    return get_ccy($currency) . ' ' . number_format($number, 2);
}
function print_total($totals): string
{
    $str = [];
    foreach ($totals as $currency => $total) {
        if (0 < $total) {
            $str[] = '<span class="badge bg-success mb-1" style="font-size:1.1em">💵 ' . get_ccy($currency) . ' ' . number_format($total, 2) . '</span>';
        }
    }
    return implode('<br>', $str);
}
?>
<h2>Contents</h2>
<ul>
    <li><a href="#JLPT">日本語能力試験</a></li>
    <li><a href="#AGSM">MBA @ AGSM (UNSW)</a></li>
    <li><a href="#Otternaut">Otternaut</a></li>
</ul>
<hr class="my-5">
<img class="float-end" src="<?= base_url('assets/img/plan_jlpt.png') ?>" alt="JLPT" style="height:80px;background-color:#fff"/>
<h2 id="JLPT">🇯🇵 <ruby>日本<rt>にほん</rt>語<rt>ご</rt>能力<rt>のうりょく</rt>試験<rt>しけん</rt></ruby></h2>
<p>Japanese Language Proficiency Test (JLPT)<br>A plan to get some JLPT up to N3 or N2 level.</p>
<?php
$japanese_courses = [
    'SGD' => 0,
    'THB' => 0
];
?>
<div class="table-responsive">
    <h3>Schedule</h3>
    <table class="table table-sm table-striped table-hover">
        <tbody>
        <tr>
            <td style="min-width:150px" rowspan="2"><a href="https://www.tomo-japanese.com/schedule" target="_blank">TOMO Japanese School</a></td>
            <td style="min-width:200px">２０２５<ruby>年<rt>ねん</rt></ruby>７<ruby>月<rt>がつ</rt></ruby>〜９月</td>
            <td style="min-width:400px">🇯🇵 Beginner’s Course</td>
            <td style="min-width:150px">
                Textbook: S$30.00<br>
                Course fee: S$450.00<br>
                +9% GST
            </td>
            <td style="min-width:150px" class="text-end"><?= add_amount(523.2, 'SGD', $japanese_courses) ?></td>
        </tr>
        <tr>
            <td>２０２５年９月〜２０２６年</td>
            <td>🇯🇵 Basic Course １〜６</td>
            <td>
                Course fee: S$450x6<br>
                Textbook: S$35<br>
                + 9% GST
            </td>
            <td class="text-end"><?= add_amount(2981.15, 'SGD', $japanese_courses) ?></td>
        </tr>
        <tr>
            <td rowspan="2"><h3>N5</h3><a href="https://www.jcss.org.sg/japanese-language-proficiency-test-jlpt/" target="_blank">JCSS</a></td>
            <td>２０２６年８月</td>
            <td><span class="badge bg-danger">JLPT: N5</span> 日本語能力試験：<ruby>願書<rt>がんしょ</rt></ruby></td>
            <td></td>
            <td class="text-end"><?= add_amount(100, 'SGD', $japanese_courses) ?></td>
        </tr>
        <tr>
            <td>２０２６年１２月（<ruby>第<rt>だい</rt></ruby>２<ruby>回<rt>かい</rt></ruby>）</td>
            <td><span class="badge bg-danger">JLPT: N5</span> 日本語能力試験：<ruby>試験<rt>しけん</rt></ruby>を<ruby>受ける<rt>うける</rt></ruby></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td><a href="https://waseda.ac.th/" target="_blank">WASEDA</a></td>
            <td>２０２７年</td>
            <td>Consider moving to Upper-elementary course at WASEDA</td>
            <td>฿7,000.00x6 *</td>
            <td class="text-end"><?= add_amount(42000, 'THB', $japanese_courses) ?></td>
        </tr>
        <tr>
            <td rowspan="2"><h3>N4</h3>Thailand or Australia</td>
            <td>２０２７〜８年</td>
            <td><span class="badge bg-danger">JLPT: N4</span> 日本語能力試験：願書</td>
            <td></td>
            <td class="text-end">?</td>
        </tr>
        <tr>
            <td>２０２７〜８年</td>
            <td><span class="badge bg-danger">JLPT: N4</span> 日本語能力試験：試験を受うける</td>
            <td></td>
            <td class="text-end">?</td>
        </tr>
        <tr>
            <td>-</td>
            <td>２０２８年</td>
            <td colspan="3">Skip for MBA</td>
        </tr>
        <tr>
            <td><h3>N3</h3></td>
            <td>２０２９年１２月（第２回）</td>
            <td colspan="3"><span class="badge bg-danger">JLPT: N3</span> Proceed after MBA</td>
        </tr>
        <tr>
            <td><h3>N2</h3></td>
            <td>２０３０年１２月（第２回）</td>
            <td colspan="3"><span class="badge bg-danger">JLPT: N2</span> If want to proceed</td>
        </tr>
        <tr>
            <td><h3>N1</h3></td>
            <td>２０３２年７月（第１回）</td>
            <td colspan="3"><span class="badge bg-danger">JLPT: N1</span> If want to proceed<br></td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
            <th colspan="4" class="text-end">TOTAL</th>
            <th class="text-end"><?= print_total($japanese_courses) ?></th>
        </tr>
        </tfoot>
    </table>
    <h3>JLPT Levels</h3>
    <table class="table table-sm table-striped table-hover">
        <thead>
        <tr>
            <th style="min-width:80px">JLPT</th>
            <th style="min-width:600px">Details</th>
            <th style="min-width:120px">CEFR</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="text-center"><span class="badge bg-danger">JLPT: N1</span></td>
            <td>The ability to understand Japanese used in a variety of circumstances.<br>
                <span class="badge bg-danger">👁️ Reading:</span> One is able to read writings with logical complexity and/or abstract writings on a variety of topics, such as newspaper editorials and critiques, and comprehend both their structures and contents. One is also able to read written materials with profound contents on various topics and follow their narratives as well as understand the intent of the writers comprehensively.<br>
                <span class="badge bg-danger">👂🏼 Listening:</span> One is able to comprehend orally presented materials such as coherent conversations, news reports, and lectures, spoken at natural speed in a broad variety of settings, and is able to follow their ideas and comprehend their contents comprehensively. One is also able to understand the details of the presented materials such as the relationships among the people involved, the logical structures, and the essential points.</td>
            <td class="text-center"><span class="badge bg-primary">CEFR: C1</span><br>142+<hr><span class="badge bg-primary">CEFR: B2</span><br>100+</td>
        </tr>
        <tr>
            <td class="text-center"><span class="badge bg-danger">JLPT: N2</span></td>
            <td>The ability to understand Japanese used in everyday situations, and in a variety of circumstances to a certain degree.<br>
                <span class="badge bg-danger">👁️ Reading:</span> One is able to read materials written clearly on a variety of topics, such as articles and commentaries in newspapers and magazines as well as simple critiques, and comprehend their contents. One is also able to read written materials on general topics and follow their narratives as well as understand the intent of the writers.<br>
                <span class="badge bg-danger">👂🏼 Listening:</span> One is able to comprehend orally presented materials such as coherent conversations and news reports, spoken at nearly natural speed in everyday situations as well as in a variety of settings, and is able to follow their ideas and comprehend their contents. One is also able to understand the relationships among the people involved and the essential points of the presented materials.</td>
            <td class="text-center"><span class="badge bg-primary">CEFR: B2</span><br>112+<hr><span class="badge bg-primary">CEFR: B1</span><br>90+</td>
        </tr>
        <tr>
            <td class="text-center"><span class="badge bg-danger">JLPT: N3</span></td>
            <td>The ability to understand Japanese used in everyday situations to a certain degree.<br>
                <span class="badge bg-danger">👁️ Reading:</span> One is able to read and understand written materials with specific contents concerning everyday topics. One is also able to grasp summary information such as newspaper headlines. In addition, one is also able to read slightly difficult writings encountered in everyday situations and understand the main points of the content if some alternative phrases are available to aid one’s understanding.<br>
                <span class="badge bg-danger">👂🏼 Listening:</span> One is able to listen and comprehend coherent conversations in everyday situations, spoken at near-natural speed, and is generally able to follow their contents as well as grasp the relationships among the people involved.</td>
            <td class="text-center"><span class="badge bg-primary">CEFR: B1</span><br>104+<hr><span class="badge bg-primary">CEFR: A2</span><br>95+</td>
        </tr>
        <tr>
            <td class="text-center"><span class="badge bg-danger">JLPT: N4</span></td>
            <td>The ability to understand basic Japanese.<br>
                <span class="badge bg-danger">👁️ Reading:</span> One is able to read and understand passages on familiar daily topics written in basic vocabulary and kanji.<br>
                <span class="badge bg-danger">👂🏼 Listening:</span> One is able to listen and comprehend conversations encountered in daily life and generally follow their contents, provided that they are spoken slowly.</td>
            <td class="text-center"><span class="badge bg-primary">CEFR: A2</span><br>90+</td>
        </tr>
        <tr>
            <td class="text-center"><span class="badge bg-danger">JLPT: N5</span></td>
            <td>The ability to understand some basic Japanese.<br>
                <span class="badge bg-danger">👁️ Reading:</span> One is able to read and understand typical expressions and sentences written in hiragana, katakana, and basic kanji.<br>
                <span class="badge bg-danger">👂🏼 Listening:</span> One is able to listen and comprehend conversations about topics regularly encountered in daily life and classroom situations, and is able to pick up necessary information from short conversations spoken slowly.</td>
            <td class="text-center"><span class="badge bg-primary">CEFR: A1</span><br>80+</td>
        </tr>
        </tbody>
    </table>
</div>
<hr class="my-5">
<img class="float-end p-1" src="<?= base_url('assets/img/plan_unsw.png') ?>" alt="UNSW" style="height:80px;background-color:#fff"/>
<h2 id="AGSM">📚 Master of Business Administration (MBA)</h2>
<p>Australian Graduate School of Management (AGSM)<br>University of New South Wales (UNSW)<br><b>2028</b></p>
<?php
$mba_costs = [
    'AUD' => 0,
    'THB' => 0
];
?>
<div class="table-responsive">
    <h3>Entry Requirements</h3>
    <table class="table table-sm table-striped table-hover">
        <tbody>
        <tr>
            <td style="min-width:30px" class="text-center">✅</td>
            <td style="min-width:250px">An undergraduate degree</td>
            <td style="min-width:250px">I even got another Master Degree (AQF9)</td>
        </tr>
        <tr>
            <td class="text-center">✅</td>
            <td>Demonstrated academic excellence</td>
            <td>I got BSc and MSc already (AQF9)</td>
        </tr>
        <tr>
            <td class="text-center">✅</td>
            <td>Minimum 2 years experience<br>Take the letters from some companies, like Secretlab or Moolahgo</td>
            <td>Already have 10+ years (Leading a team since 2018; 2018 - 2027 = 10 years)</td>
        </tr>
        <tr>
            <td class="text-center">✅</td>
            <td>English language requirements<br>- Residency in English speaking country (use Singapore PR to apply)</td>
            <td>Apply for an English waiver</td>
        </tr>
        <tr>
            <td class="text-center">❓</td>
            <td>GMAT or GRE</td>
            <td>May not be needed</td>
        </tr>
        <tr>
            <td class="text-center">-</td>
            <td>Resume</td>
            <td>to be updated</td>
        </tr>
        <tr>
            <td class="text-center">-</td>
            <td>Essay</td>
            <td>to be written</td>
        </tr>
        <tr>
            <td class="text-center">-</td>
            <td>Letter of recommendation</td>
            <td>1 or 2, to be asked for (Clifford and ?)</td>
        </tr>
        <tr>
            <td class="text-center">-</td>
            <td>Visa<br><em>Eligible for Temporary Graduate Visa (Subclass 485)</em></td>
            <td>Need Subclass 500 Student Visa</td>
        </tr>
        <tr>
            <td class="text-center">-</td>
            <td>Aim for scholarship!</td>
            <td></td>
        </tr>
        </tbody>
    </table>
    <h3>Costs</h3>
    <table class="table table-sm table-striped table-hover">
        <thead>
        <tr>
            <th style="min-width:200px">Item</th>
            <th class="text-end" style="min-width:100px">Amount</th>
            <th style="min-width:200px">Notes</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Full-time MBA at AGSM</td>
            <td class="text-end"><?= add_amount(100000, 'AUD', $mba_costs) ?></td>
            <td>A$88,000 in 2025 (<a
                        href="https://www.unsw.edu.au/business/our-schools/agsm/learn-with-us/agsm-programs/program-fees"
                        target="_blank">see more</a>), fees adjusted yearly
            </td>
        </tr>
        <tr>
            <td>Student Visa (Subclass 500)</td>
            <td class="text-end"><?= add_amount(710, 'AUD', $mba_costs) ?></td>
            <td>price as of 2025</td>
        </tr>
        <tr>
            <td>Overseas Student Health Cover (OSHC)</td>
            <td class="text-end"><?= add_amount(1200, 'AUD', $mba_costs) ?></td>
            <td>Estimated A$1,000-1,300 as of 2025</td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
            <th class="text-end">TOTAL</th>
            <th class="text-end"><?= print_total($mba_costs) ?></th>
            <th></th>
        </tr>
        </tfoot>
    </table>
    <table class="table table-sm table-striped table-hover">
        <thead>
        <tr>
            <th style="min-width:300px">Item</th>
            <th class="text-end" style="max-width:120px">Monthly</th>
            <th class="text-end" style="max-width:180px">Yearly</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $mba_living_costs = [
                ['Rental (1-bedroom apartment) at 1,400 – 2,800', 2200],
                ['Grocery at 700 - 1,200', 800],
                ['Utilities at 160 – 640', 300],
                ['Transport at 160 – 275', 200],
                ['Entertainment at 400 - 600', 400],
        ];
        $sum = ['monthly' => 0, 'yearly' => 0];
        foreach ($mba_living_costs as $row) {
            $yearly = $row[1] * 12;
            $sum['monthly'] += $row[1];
            $sum['yearly'] += $yearly;
            echo '<tr>';
            echo '<td>' . $row[0] . '</td>';
            echo '<td class="text-end">A$ ' . number_format($row[1], 2) . '</td>';
            echo '<td class="text-end">' . add_amount($yearly, 'AUD', $mba_costs) . '</td>';
            echo '</tr>';
        }
        ?>
        </tbody>
        <tfoot>
        <tr>
            <th class="text-end">TOTAL</th>
            <th class="text-end">A$ <?= number_format($sum['monthly'], 2) ?></th>
            <th class="text-end">A$ <?= number_format($sum['yearly'], 2) ?></th>
        </tr>
        <tr>
            <th class="text-end">GRAND TOTAL</th>
            <th class="text-end">-</th>
            <th class="text-end"><?= print_total($mba_costs) ?></th>
        </tr>
        </tfoot>
    </table>
    <h3>Scholarship</h3>
    <p>There are many scholarships to grab, as a new startup, I should be highly eligible. <a href="https://www.unsw.edu.au/business/our-schools/agsm/agsm-scholarships/full-time-mba-scholarships" target="_blank">See more about the scholarship.</a></p>
    <p>AGSM Global Reach Scholarship:</p>
    <ul>
        <li>Automatically considered once applied for the MBA program</li>
        <li>Selection criteria
        <ul>
            <li>GMAT or other academic results</li>
            <li>Leadership potential</li>
            <li>Career progression</li>
            <li>Global citizenship</li>
        </ul>
        </li>
    </ul>
    <h3>Flights</h3>
    <p>Price is estimated, searched from SkyScanner as of 2025 in A$.</p>
    <table class="table table-sm table-striped table-hover">
        <thead>
        <tr>
            <th colspan="2">Routes</th>
            <th>One-Way</th>
            <th>Return</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="min-width:100px;">BKK ↔️ SYD</td>
            <td style="min-width:400px;">Bangkok Suvarnabhumi to/from Sydney Kingsford Smith</td>
            <td style="min-width:100px;">200 - 800</td>
            <td style="min-width:100px;">600 - 1,400</td>
        </tr>
        <tr>
            <td>SIN ↔️ SYD</td>
            <td>Singapore Changi to/from Sydney Kingsford Smith</td>
            <td>200 - 600</td>
            <td>600 - 1,200</td>
        </tr>
        </tbody>
    </table>
</div>
<hr class="my-5">
<img class="float-end p-1" src="<?= base_url('assets/img/plan_otternaut.png') ?>" alt="Otternaut" style="height:80px;background-color:#fff"/>
<h2 id="Otternaut">💼 Otternaut</h2>
<p>Plan for the SaaS platform startup.<br>RULES: No CHINA/CHINESE, must be International-focused, seriously no-toxic folks around.</p>
<div class="table-responsive">
    <h3>Pre-Incorporation (Upfront Payments)</h3>
    <?php $otternaut_pre_incorporation = ['IDR' => 0, 'MYR' => 0, 'SGD' => 0, 'THB' => 0, 'USD' => 0]; ?>
    <table class="table table-sm table-striped table-hover">
        <thead>
        <tr>
            <th style="min-width:240px">Description</th>
            <th style="min-width:120px" class="text-end">Amount</th>
            <th style="min-width:240px">Notes</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Theme for website’s frontend</td>
            <td class="text-end"><?= add_amount(49, 'USD', $otternaut_pre_incorporation) ?></td>
            <td rowspan="2"><a href="https://themes.getbootstrap.com/" target="_blank">Bootstrap Themes</a> licenses are generally almost the same price:<br>- US$49 for standard license<br>- US$449 for extended license</td>
        </tr>
        <tr>
            <td>Theme for generic website’s backend (admin)</td>
            <td class="text-end"><?= add_amount(49, 'USD', $otternaut_pre_incorporation) ?></td>
        </tr>
        <tr>
            <td>Hosting (Cloud VPS at HostAtom)</td>
            <td class="text-end"><?= add_amount(2978.88, 'THB', $otternaut_pre_incorporation) ?></td>
            <td>
                <b>20GB SSD</b><br>฿ 290/m = ฿ 3,480/y<br>-20% discount = ฿ 2,784/y<br>+7% VAT = ฿ 2,978.88/y<br>
                <em>This is to be upgraded when ready to go public.</em>
            </td>
        </tr>
        <tr>
            <td>Internal emails: Nat and Jate</td>
            <td class="text-end"><?= add_amount(3300, 'THB', $otternaut_pre_incorporation) ?></td>
            <td>HostAtom’s new user package offers it at ฿1,650/user/year for the first year (฿3,300 second year onwards)</td>
        </tr><tr>
            <td>Atlassian systems:<br>
                - Bitbucket (hosting source code)<br>
                - JIRA (managing tasks)<br>
                - Confluence (storing documents)</td>
            <td class="text-end"><?= add_amount(0, 'USD', $otternaut_pre_incorporation) ?></td>
            <td></td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
            <th class="text-end">TOTAL</th>
            <th class="text-end"><?= print_total($otternaut_pre_incorporation) ?></th>
            <th></th>
        </tr>
        </tfoot>
    </table>
    <?php $otternaut_deferred = ['IDR' => 0, 'MYR' => 0, 'SGD' => 0, 'THB' => 0, 'USD' => 0]; ?>
    <h3>Deferred Payment - Pre-Incorporation</h3>
    <table class="table table-sm table-striped table-hover">
        <thead>
        <tr>
            <th style="min-width:240px">Description</th>
            <th style="min-width:120px" class="text-end">Amount</th>
            <th style="min-width:240px">Notes</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Fauzi</td>
            <td class="text-end"><?= add_amount(60000000, 'IDR', $otternaut_deferred) ?></td>
            <td rowspan="2">Budget allocated for developers who work before the incorporation. The payment will be deferred until cash is ready.</td>
        </tr>
        <tr>
            <td>Pajar</td>
            <td class="text-end"><?= add_amount(60000000, 'IDR', $otternaut_deferred) ?></td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
            <th class="text-end">TOTAL</th>
            <th class="text-end"><?= print_total($otternaut_deferred) ?></th>
            <th></th>
        </tr>
        </tfoot>
    </table>
    <?php $otternaut_annual_budget = ['IDR' => 0, 'MYR' => 0, 'SGD' => 0, 'THB' => 0, 'USD' => 0]; ?>
    <h3>Annual Company’s Spending</h3>
    <table class="table table-sm table-striped table-hover">
        <thead>
        <tr>
            <th style="min-width:240px">Description</th>
            <th style="min-width:120px" class="text-end">Amount</th>
            <th style="min-width:240px">Notes</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
        <tr>
            <th class="text-end">TOTAL</th>
            <th class="text-end"><?= print_total($otternaut_annual_budget) ?></th>
            <th></th>
        </tr>
        </tfoot>
    </table>
</div>