<?php
function get_ccy($currency): string
{
    $ccy = [
        'SGD' => 'S$',
        'THB' => '฿',
        'AUD' => 'A$',
        'USD' => 'US$'
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
        $str[] = get_ccy($currency) . ' ' . number_format($total, 2);
    }
    return implode('<br>', $str);
}
?>
<h3>Contents</h3>
<ul>
    <li><a href="#JLPT">日本語能力試験</a></li>
    <li><a href="#AGSM">MBA @ AGSM (UNSW)</a></li>
    <li><a href="#Otternaut">Otternaut</a></li>
</ul>
<h3 id="JLPT">🗻 <ruby>日本<rt>にほん</rt>語<rt>ご</rt>能力<rt>のうりょく</rt>試験<rt>しけん</rt></ruby></h3>
<?php
$japanese_courses = [
    'SGD' => 0,
    'THB' => 0
];
?>
<div class="table-responsive">
    <h4>Schedule</h4>
    <table class="table table-sm table-striped table-hover">
        <tbody>
        <tr>
            <td rowspan="2"><a href="https://www.tomo-japanese.com/schedule" target="_blank">TOMO Japanese School</a></td>
            <td>２０２５年７月〜９月</td>
            <td>🇯🇵 Beginner’s Course</td>
            <td>
                Textbook: S$30.00<br>
                Course fee: S$450.00<br>
                +9% GST
            </td>
            <td class="text-end"><?= add_amount(523.2, 'SGD', $japanese_courses) ?></td>
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
            <td rowspan="2"><h4>N5</h4><a href="https://www.jcss.org.sg/japanese-language-proficiency-test-jlpt/" target="_blank">JCSS</a></td>
            <td>２０２６年８月</td>
            <td><span class="badge bg-danger">JLPT: N5</span> 日本語能力試験：<ruby>願書<rt>がんしょ</rt></ruby></td>
            <td></td>
            <td class="text-end"><?= add_amount(100, 'SGD', $japanese_courses) ?></td>
        </tr>
        <tr>
            <td>２０２６年１２月（第２回）</td>
            <td><span class="badge bg-danger">JLPT: N5</span> 日本語能力試験：試験を受うける</td>
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
            <td rowspan="2"><h4>N4</h4>Thailand or Australia</td>
            <td>２０２７〜８年</td>
            <td><span class="badge bg-danger">JLPT: N4</span> 日本語能力試験：<ruby>願書<rt>がんしょ</rt></ruby></td>
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
            <td><h4>N3-N1</h4></td>
            <td colspan="4">
                <span class="badge bg-danger">JLPT: N3</span> ２０２９年 Proceed after MBA<br>
                <span class="badge bg-danger">JLPT: N2</span> ２０３０年 If want to proceed<br>
                <span class="badge bg-danger">JLPT: N1</span> ２０３２年 If want to proceed<br>
            </td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
            <th colspan="4" class="text-end">TOTAL</th>
            <th class="text-end"><?= print_total($japanese_courses) ?></th>
        </tr>
        </tfoot>
    </table>
    <h4>JLPT Levels</h4>
    <table class="table table-sm table-striped table-hover">
        <thead>
        <tr>
            <th>JLPT</th>
            <th>Details</th>
            <th>CEFR</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><span class="badge bg-danger">JLPT: N1</span></td>
            <td>The ability to understand Japanese used in a variety of circumstances.<br>
                👁️ Reading: One is able to read writings with logical complexity and/or abstract writings on a variety of topics, such as newspaper editorials and critiques, and comprehend both their structures and contents. One is also able to read written materials with profound contents on various topics and follow their narratives as well as understand the intent of the writers comprehensively.<br>
                👂🏼 Listening: One is able to comprehend orally presented materials such as coherent conversations, news reports, and lectures, spoken at natural speed in a broad variety of settings, and is able to follow their ideas and comprehend their contents comprehensively. One is also able to understand the details of the presented materials such as the relationships among the people involved, the logical structures, and the essential points.</td>
            <td><span class="badge bg-primary">CEFR: C1</span> (142+)<hr><span class="badge bg-primary">CEFR: B2</span> (100+)</td>
        </tr>
        <tr>
            <td><span class="badge bg-danger">JLPT: N2</span></td>
            <td>The ability to understand Japanese used in everyday situations, and in a variety of circumstances to a certain degree.<br>
                👁️ Reading: One is able to read materials written clearly on a variety of topics, such as articles and commentaries in newspapers and magazines as well as simple critiques, and comprehend their contents. One is also able to read written materials on general topics and follow their narratives as well as understand the intent of the writers.<br>
                👂🏼 Listening: One is able to comprehend orally presented materials such as coherent conversations and news reports, spoken at nearly natural speed in everyday situations as well as in a variety of settings, and is able to follow their ideas and comprehend their contents. One is also able to understand the relationships among the people involved and the essential points of the presented materials.</td>
            <td><span class="badge bg-primary">CEFR: B2</span> (112+)<hr><span class="badge bg-primary">CEFR: B1</span> (90+)</td>
        </tr>
        <tr>
            <td><span class="badge bg-danger">JLPT: N3</span></td>
            <td>"The ability to understand Japanese used in everyday situations to a certain degree.<br>
                👁️ Reading: One is able to read and understand written materials with specific contents concerning everyday topics. One is also able to grasp summary information such as newspaper headlines. In addition, one is also able to read slightly difficult writings encountered in everyday situations and understand the main points of the content if some alternative phrases are available to aid one’s understanding.<br>
                👂🏼 Listening: One is able to listen and comprehend coherent conversations in everyday situations, spoken at near-natural speed, and is generally able to follow their contents as well as grasp the relationships among the people involved."</td>
            <td><span class="badge bg-primary">CEFR: B1</span> (104+)<hr><span class="badge bg-primary">CEFR: A2</span> (95+)</td>
        </tr>
        <tr>
            <td><span class="badge bg-danger">JLPT: N4</span></td>
            <td>"The ability to understand basic Japanese.<br>
                👁️ Reading: One is able to read and understand passages on familiar daily topics written in basic vocabulary and kanji.<br>
                👂🏼 Listening: One is able to listen and comprehend conversations encountered in daily life and generally follow their contents, provided that they are spoken slowly."</td>
            <td><span class="badge bg-primary">CEFR: A2</span> (90+)</td>
        </tr>
        <tr>
            <td><span class="badge bg-danger">JLPT: N5</span></td>
            <td>"The ability to understand some basic Japanese.<br>
                👁️ Reading: One is able to read and understand typical expressions and sentences written in hiragana, katakana, and basic kanji.<br>
                👂🏼 Listening: One is able to listen and comprehend conversations about topics regularly encountered in daily life and classroom situations, and is able to pick up necessary information from short conversations spoken slowly."</td>
            <td><span class="badge bg-primary">CEFR: A1</span> (80+)</td>
        </tr>
        </tbody>
    </table>
</div>
<h3 id="AGSM">Master of Business Administration (MBA)</h3>
<p>Australian Graduate School of Management (AGSM)<br>University of New South Wales (UNSW)</p>
<?php
$mba_costs = [
    'AUD' => 0,
    'THB' => 0
];
?>
<div class="table-responsive">
    <h4>Entry Requirements</h4>
    <table class="table table-sm table-striped table-hover">
        <tbody>
        <tr>
            <td>✅</td>
            <td>An undergraduate degree</td>
            <td>I even got another Master Degree</td>
        </tr>
        <tr>
            <td>✅</td>
            <td>Demonstrated academic excellence</td>
            <td>I got BSc and MSc already (AQF9)</td>
        </tr>
        <tr>
            <td>✅</td>
            <td>Minimum 2 years experience<br>Take the letters from some companies, like Secretlab or Moolahgo</td>
            <td>Already have 10 years (Leading a team since 2018; 2018 - 2027 = 10 years)</td>
        </tr>
        <tr>
            <td>✅</td>
            <td>English language requirements<br>- Residency in English speaking country (use Singapore PR to apply)</td>
            <td>Apply for an English waiver</td>
        </tr>
        <tr>
            <td>❓</td>
            <td>GMAT or GRE</td>
            <td>May not be needed</td>
        </tr>
        <tr>
            <td></td>
            <td>Resume</td>
            <td>to be updated</td>
        </tr>
        <tr>
            <td></td>
            <td>Essay</td>
            <td>to be written</td>
        </tr>
        <tr>
            <td></td>
            <td>Letter of recommendation</td>
            <td>1 or 2, to be asked for (Clifford and ?)</td>
        </tr>
        <tr>
            <td></td>
            <td>Visa<br><em>Eligible for Temporary Graduate Visa (Subclass 485)</em></td>
            <td>Need Subclass 500 Student Visa</td>
        </tr>
        <tr>
            <td></td>
            <td>Aim for scholarship!</td>
            <td></td>
        </tr>
        </tbody>
    </table>
    <h4>Costs</h4>
    <table class="table table-sm table-striped table-hover">
        <thead>
        <tr>
            <th>Item</th>
            <th>Amount</th>
            <th>Notes</th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td>Full-time MBA at AGSM</td>
                <td class="text-end"><?= add_amount(100000, 'AUD', $mba_costs) ?></td>
                <td>A$88,000 in 2025, fees adjusted yearly</td>
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
                <td class="text-end>TOTAL</th>
                <th class="text-end"><?= print_total($mba_costs) ?></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
    <p><a href="https://www.unsw.edu.au/business/our-schools/agsm/learn-with-us/agsm-programs/program-fees" target="_blank">See more</a></p>
    <h4>Budget</h4>
    <p>TBC</p>
</div>
<h3 id="Otternaut">Otternaut</h3>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras bibendum pellentesque dictum. Maecenas sed volutpat est, id sagittis lectus. Praesent eu dui porta, lobortis mi nec, tempus turpis. Sed egestas velit id tristique rutrum. Praesent viverra gravida scelerisque. Nam vitae ornare urna, at sagittis est. Suspendisse at laoreet erat, eu tincidunt dolor. Ut luctus lobortis mi sed lobortis.
    Etiam tristique, magna sit amet elementum venenatis, justo purus auctor ligula, sit amet faucibus urna felis at nisl. Praesent molestie, sapien at malesuada condimentum, lorem lacus porttitor nulla, nec dictum turpis augue ut dui. Proin laoreet ornare elementum. Sed ultricies efficitur neque, in tincidunt quam imperdiet et. Pellentesque id semper erat, nec pharetra nibh. Mauris id rutrum purus. Nunc pharetra sapien a sodales convallis. Aenean vulputate arcu leo, vitae maximus magna varius ac.
    Proin et volutpat orci. Aliquam tellus turpis, interdum sit amet nibh non, egestas lobortis eros. Curabitur vitae velit semper, euismod erat quis, condimentum felis. Etiam vestibulum commodo cursus. Proin semper orci ac neque mollis, eu fringilla nulla varius. Mauris venenatis facilisis odio, vestibulum laoreet libero. Sed in porta ligula, nec sodales magna. Ut luctus, ipsum a vehicula pretium, nunc turpis placerat odio, ac rutrum risus mauris ornare nisi. Nulla auctor tellus id velit mattis, et feugiat ligula scelerisque. Maecenas congue ut nisi nec rutrum. Etiam ut erat id lacus pellentesque ullamcorper. Aenean a malesuada tellus. Donec laoreet, diam vitae condimentum blandit, nisl leo tincidunt mi, id ultricies neque risus ac ipsum.
    Sed eget semper nisi. Integer id nunc finibus, dapibus felis posuere, blandit tortor. Maecenas porta erat vel feugiat pulvinar. In sollicitudin auctor turpis, vel fermentum ante tincidunt vel. Praesent quis imperdiet arcu. Maecenas sed felis eleifend, semper lacus quis, commodo mi. Nullam justo mauris, aliquam ut felis non, faucibus posuere urna. Curabitur varius ante imperdiet justo tincidunt ullamcorper quis non arcu. Duis justo libero, posuere in auctor quis, aliquam sed dui. Sed euismod turpis sed augue dapibus commodo. Vivamus sagittis placerat libero eu imperdiet. Ut sit amet nunc congue est lobortis consequat vel ac lectus. Nunc non ultricies metus.
    Cras finibus urna ac leo vulputate posuere. Morbi viverra ultrices arcu, eu lobortis turpis interdum at. Etiam a augue at lorem pellentesque luctus sit amet ut metus. Suspendisse luctus est et felis laoreet, et auctor tellus convallis. Praesent tincidunt ligula ac lacus pretium ultricies a nec nunc. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Sed eu turpis et nisl consectetur facilisis. In in aliquam neque. Fusce eu lectus vel sem dignissim ornare vitae at est. Pellentesque in imperdiet turpis. Praesent finibus leo nec arcu congue sagittis nec et mauris.</p>

