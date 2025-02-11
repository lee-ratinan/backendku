<html lang="en">
<head>
    <?php
    $experiences = [
        'moolahgo'  => [
            'title'            => 'Senior Technology Lead',
            'company'          => 'Moolahgo',
            'location'         => 'Singapore',
            'period'           => 'Jun 2021-Sep 2024',
            'responsibilities' => "Directed the company’s technical strategy and spearheaded the development of core remittance products, including the mobile application, client portal, API platform, and internal administration system.\nManaged and mentored development teams, providing training, support, and guidance to enhance team performance and individual growth.\nCollaborated closely with product designers and stakeholders, including clients, to understand requirements, address pain points, and ensure products met or exceeded expectations.",
            'achievements'     => "<em>Accelerated Business Growth:</em> Designed and implemented scalable, secure technical architectures and innovative financial solutions, enabling the company to expand services into new markets. Successfully launched the API platform, driving a <em>300% business growth within 6 months</em>.\n<em>Enhanced Operational Efficiency:</em> Optimised testing and deployment methodologies, achieving a <em>20% increase</em> in project completion rate and reducing testing errors by <em>90% within 3 months</em>.\n<em>Award-Winning Innovation:</em> Collaborated on an AI initiative that won the <em>Singapore FinTech AI Award</em> at the Singapore FinTech Festival 2023, earning the company significant media coverage and a feature in local newspaper headlines.",
        ],
        'irvins'    => [
            'title'            => 'Tech Lead',
            'company'          => 'Irvins Salted Egg',
            'location'         => 'Singapore',
            'period'           => 'Sep 2020-May 2021',
            'responsibilities' => "Directed the technical strategy for the company’s e-commerce platforms and led the development of a cutting-edge back-office system to support operations and finance teams.\nManaged and mentored development teams, fostering skill development and guiding team members toward achieving their full potential.",
            'achievements'     => "<em>Revolutionised Operations:</em> Designed and delivered a state-of-the-art back-office system that addressed key stakeholder pain points, enhancing operational efficiency at distribution centers by <em>80%</em> and reducing monthly infrastructure costs by <em>50%</em>.\n<em>Enhanced Fraud Prevention:</em> Implemented robust fraud detection and prevention policies, improving customer satisfaction and reducing chargeback cases from international customers by <em>90%</em>.",
        ],
        'secretlab' => [
            'title'            => 'IT and Backend Web Lead',
            'company'          => 'Secretlab',
            'location'         => 'Singapore',
            'period'           => 'Feb 2018-Aug 2020',
            'responsibilities' => "Led the development of a comprehensive back-office system integrating e-commerce platforms (Shopify), 3PL (third-party logistics) partners, marketing systems, and more.\nManaged and mentored development teams, fostering professional growth and ensuring project success.",
            'achievements'     => "<em>Boosted Efficiency and Scalability:</em> Successfully implemented a new back-office system that saved the local delivery team <em>2+ hours daily</em> and significantly streamlined operations across the company, enabling a <em>10x growth within 1 year</em>.\n<em>Ensured Reliability and Problem Resolution:</em> Provided technical guidance to address complex challenges, migrated the system to improve reliability across multiple availability zones, and resolved critical technical issues, enhancing system performance and stability for the entire company.",
        ],
        'buzzcity'  => [
            'title'            => 'Software Engineer',
            'company'          => 'BuzzCity, Mobads',
            'location'         => 'Singapore',
            'period'           => 'Jul 2015-Jun 2016, Jan-Jun 2017',
            'responsibilities' => "Developed and maintained the publisher platform for the company’s AdTech system, covering publisher onboarding, advertisement bidding, CPC/CPM payout calculation, payout mechanisms, and reporting.",
            'achievements'     => "<em>Optimised Payout System:</em> Improved the publisher payout process by implementing an efficient background processing system, enhancing overall system performance and increasing downstream processing speed by <em>80%</em>, which eliminated lagging issues in the Finance department and streamlined their operations.",
        ],
        'dst'       => [
            'title'            => 'Programmer',
            'company'          => 'DST Worldwide Services',
            'location'         => 'Bangkok, Thailand',
            'period'           => 'Jun 2012-Jul 2014',
            'responsibilities' => "Developed and maintained a mission-critical mutual fund system supporting the American financial sector, handling tasks such as unrealised dividend calculations, partial redemptions, financial reporting, and new mutual fund setup.\nEnsured the software’s reliability, scalability, and high performance to meet the demanding requirements of the US mutual fund market. Provided 24/7 production system support as part of the on-call rotation.",
            'achievements'     => "<em>Enhanced System Efficiency:</em> Refactored the complex fund redemption calculation code, reducing processing time by <em>over 50%</em>, significantly improving performance.\n<em>Streamlined Team Operations:</em> Successfully introduced and trained the team on Scrum with Kanban methodologies, boosting efficiency and productivity within the support team.",
        ],
    ];
    $education = [
        [
            'degree'   => 'Master of Science in Information Systems',
            'school'   => 'Nanyang Technological University',
            'location' => 'Singapore',
            'class_of' => '2015',
        ],
        [
            'degree'   => 'Bachelor of Science (First Class Honours) in Computer Science',
            'school'   => 'Thammasat University',
            'location' => 'Bangkok, Thailand',
            'class_of' => '2012',
        ],
    ];
    ?>
    <meta charset="UTF-8">
    <title><?= $job_title ?> - Ratinan L. - Resume</title>
    <style>
        body {
            color: #444 !important;
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0 auto;
            width: 700px;
        }

        table {
            width: 700px !important;
        }

        td, p {
            color: #444 !important;
            font-family: Arial, sans-serif;
            font-size: 12px;
            vertical-align: top;
        }

        h1 {
            font-size: 18px;
        }

        h2 {
            font-size: 16px;
        }

        h3 {
            font-size: 14px;
        }

        h1, h2 {
            margin: 0;
            text-align: center;
        }

        h3 {
            margin: 10px 0;
        }

        a {
            color: #444;
            text-decoration: none;
        }

        p {
            margin: 5px 0;
        }

        .center {
            text-align: center;
        }

        .m-0 {
            margin-bottom: 0;
        }

        ul {
            padding-left: 10px;
            margin: 0;
        }
    </style>
</head>
<body>
<h1 style="margin-top:10px">RATINAN “NAT” LEELA-NGAMWONGSA</h1>
<h2 style="margin-bottom:8px"><?= $job_title ?>, MSc, CSM, PSM, PSPO</h2>
<p class="center">
    <a href="https://wa.me/6597754577">+65 9775 4577</a> -
    <a href="mailto:lee@ratinan.com">lee@ratinan.com</a> -
    <a href="https://lee.ratinan.com" target="_blank">lee.ratinan.com</a> -
    <a href="https://www.google.com/maps/place/Singapore/" target="_blank">Singapore</a><br>
    <a href="https://www.linkedin.com/in/ratinanlee/" target="_blank">linkedin.com/in/ratinanlee</a> -
    <a href="https://github.com/lee-ratinan" target="_blank">github.com/lee-ratinan</a> -
    <a href="https://www.credly.com/users/ratinanlee" target="_blank">credly.com/users/ratinanlee</a>
</p>
<h3>AIM</h3>
<p>To work as a senior-level <?= strtolower($job_title) ?>.</p>
<h3>SUMMARY</h3>
<p><b><?= ucfirst($job_title) ?> with 12 years of experience (7 years in leadership roles) specialising in FinTech and e-commerce</b>. Proven expertise in managing end-to-end software development projects, from requirements gathering to deployment, ensuring successful delivery on time and within budget.</p>
<p>Detail-oriented and committed to optimising software performance, I design solutions that are efficient, cost-effective (minimising CPU usage), and user-friendly, delivering value to clients while reducing operational costs for the company.</p>
<p>Skilled in analysing complex requirements and translating them into robust, future-proof software specifications that are maintainable, scalable, and easy to understand.</p>
<p>Certified in <b>PSM I, PSM II, and PSPO I</b>, I excel in implementing Scrum and Agile methodologies to foster collaboration, streamline processes, and deliver high-quality results.</p>
<h3>EXPERIENCE</h3>
<?php
foreach ($experiences as $key => $values) {
    echo '<p><b>' . $values['title'] . ' - ' . $values['company'] . '</b><br>';
    echo $values['location'] . ' - ' . $values['period'] . '</p>';
    echo '<p class="m-0">Responsibilities:</p><ul><li>' . str_replace("\n", '</li><li>', $values['responsibilities']) . '</li></ul>';
    echo '<p class="m-0">Achievements and results:</p><ul><li>' . str_replace("\n", '</li><li>', $values['achievements']) . '</li></ul>';
}
?>
<h3>EDUCATION</h3>
<p>
    <?php
    foreach ($education as $values) {
        echo '<b>' . $values['degree'] . '</b>, ' . $values['school'] . ' - ' . $values['location'] . ' - ' . $values['class_of'] . '<br>';
    }
    ?>
</p>
<h3>LANGUAGES</h3>
<p><b>English</b> (full professional proficiency), <b>Thai</b> (native proficiency)</p>
<h3>TRAININGS AND CERTIFICATIONS</h3>
<p>
    <b>Professional Scrum Master I™ (PSM I)</b> - Scrum.org<br>
    <b>Professional Scrum Master II™ (PSM II)</b> - Scrum.org<br>
    <b>Professional Scrum Product Owner I™ (PSPO I)</b> - Scrum.org
</p>
<p>
    <b>AWS Cloud Practitioner Essentials course</b> - Coursera
</p>
<p>
    <b>Google Project Management Professional Certificate</b> - Coursera<br>
    <b>Google Data Analytics Professional Certificate</b> - Coursera<br>
    <b>Google UX Design Professional Certificate</b> - Coursera<br>
    <b>Google AI Essentials Certificate</b> - Coursera
</p>
<h3>AWARDS</h3>
<p>
    <b>Singapore FinTech AI Award</b> at Singapore FinTech Festival, Nov 2023<br>
</p>
<br>
</body>
</html>