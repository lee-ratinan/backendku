<?php
$layout = getenv('LAYOUT_FILE_OFFICE');
$layout = (!empty($layout) ? $layout : 'system/_layout_office');
$this->extend($layout);
?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="pagetitle">
        <h1><?= $page_title ?></h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url($session->locale . '/office/dashboard') ?>"><?= lang('System.dashboard.page_title') ?></a></li>
                <li class="breadcrumb-item active"><?= $page_title ?></li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Resume Generation</h5>
                        <form method="POST" action="<?= base_url($session->locale . '/office/profile/resume/builder') ?>" target="_blank">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="job_title" name="job_title" value="Technical Lead" required>
                                <label for="job_title">Job Title</label>
                            </div>
                            <?php
                            $experience_year = date('Y') - 2012;
                            $fields = [
                                [
                                    'Summary',
                                    'summary',
                                    'Experienced [JOB-TITLE] with ' . $experience_year . '+ years in software development, cloud architecture, and Agile leadership. Expertise in optimising system performance, leading cross-functional teams, and scaling high-impact solutions. Proven track record of accelerating project completion by 20% and enhancing system capabilities 10x in e-commerce and FinTech sectors. Passionate about innovation, automation, and mentoring engineering teams to drive business success.',
                                ],
                                [
                                    'Moolahgo',
                                    'experience[moolahgo]',
                                    '- Designed scalable financial architectures, expanding services to new markets and driving <b>300% business growth in 6 months</b>.
- Facilitated backlog refinement and removed team impediments, ensuring seamless Agile delivery across <b>3 product domains</b>.
- Implemented optimised testing and deployment methodologies, increasing project completion rates by <b>20% in 3 months</b>.
- Managed a team of <b>6 engineers</b>, ensuring on-time delivery and regulatory compliance for remittance, KYC, and fraud detection features.',
                                    100
                                ],
                                [
                                    'Irvins',
                                    'experience[irvins]',
                                    '- Spearheaded the development of an integrated back-office system for multiple Shopify stores, improving operational efficiency by <b>80%</b>.
- Implemented fraud prevention policies, reducing international chargeback cases by <b>90%</b>.
- Led a team of developers to deliver a robust back-office system, optimising stakeholder processes and cutting infrastructure costs by <b>50%</b>.',
                                    100
                                ],
                                [
                                    'Secretlab',
                                    'experience[secretlab]',
                                    '- Developed a backend system that streamlined internal operations, freeing up <b>2+ hours</b> daily for logistics teams.
- Enabled the company to scale to <b>1000%+ order growth worldwide</b> in 1 year by optimising backend processes.
- Led a backend team to migrate systems across multiple availability zones, ensuring reliability and scalability.',
                                    100
                                ],
                                [
                                    'BuzzCity',
                                    'experience[buzzcity]',
                                    '- Enhanced the publisher’s payout system, improving system performance and <b>accelerating downstream processes by 80%</b>.
- Designed and implemented a new payout recording system, resolving inefficiencies and improving transaction reliability.
- Provided mentorship to junior team members, fostering a strong engineering culture.',
                                    100
                                ],
                                [
                                    'DST',
                                    'experience[dst]',
                                    '- Optimised fund redemption calculations, reducing processing time by <b>50%</b> through performance engineering.
- Introduced Scrum and Kanban methodologies, increasing team productivity by <b>30%</b>.
- Led multiple projects, ensuring timely delivery of financial reporting and transaction processing solutions.',
                                    100
                                ],
                            ];
                            foreach ($fields as $field) {
                                echo '<div class="form-floating mb-3">';
                                echo '<textarea id="' . $field[1] . '" name="' . $field[1] . '" class="form-control" style="height:' . ($field[3] ?? 100) . 'px;">' . $field[2] . '</textarea>';
                                echo '<label for="' . $field[1] . '">' . $field[0] . '</label>';
                                echo '</div>';
                            }
                            ?>
                            <div class="form-floating mb-3">
                                <select class="form-select" id="return" name="return">
                                    <option value="pdf">PDF</option>
                                    <option value="html">HTML</option>
                                </select>
                                <label for="return">Return Format</label>
                            </div>
                            <div class="text-end">
                                <input class="btn btn-outline-primary btn-sm" type="submit" value="Generate Resume" />
                            </div>
                        </form>
                        <hr />
                        <h5 class="card-title">Cover Letter</h5>
                        <form method="POST" action="<?= base_url($session->locale . '/office/profile/resume/cover-letter') ?>" target="_blank">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Company Name" required>
                                <label for="company_name">Company Name</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="hiring_manager" name="hiring_manager" value="Hiring Manager" required>
                                <label for="hiring_manager">Hiring Manager</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="position" name="position" placeholder="Position" required>
                                <label for="position">Position</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="company_industry" name="company_industry" placeholder="Company Industry" required>
                                <label for="company_industry">Company Industry [COMPANY]’s commitment to innovations and [COMPANY_INDUSTRY] aligns perfectly ...</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="prev_expertise" name="prev_expertise" placeholder="Previous Expertise" required>
                                <label for="prev_expertise">Previous Expertise (With a proven track record in [PREV_EXPERTISE] and ...)</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="prev_company" name="prev_company" placeholder="Previous Company" required>
                                <label for="prev_company">Previous Company (My previous experience at [PREVIOUS_COMPANY] has ...)</label>
                            </div>


                            COMPANY_INDUSTRY
                            <?php
                            $fields = [
                                [
                                    'Paragraph 1',
                                    'paragraph-1',
                                    'I am writing to express my keen interest in the [POSITION] position at [COMPANY], which was advertised on LinkedIn. With a proven track record in [PREV_EXPERTISE] and a passion for driving innovative solutions, I am confident that I can contribute significantly to your team.',
                                    100
                                ],
                                [
                                    'Paragraph 2',
                                    'paragraph-2',
                                    '[COMPANY]’s commitment to innovations and [COMPANY_INDUSTRY] aligns perfectly with my professional goals. My previous experience at [PREVIOUS_COMPANY] has equipped me with a deep understanding of the unique challenges and opportunities within the consumer product development sector.',
                                    100
                                ],
                                [
                                    'Paragraph 3',
                                    'paragraph-3',
                                    'As a [POSITION], I am adept at bridging the gap between business requirements, available products, and technology with a high satisfaction rate.',
                                    100
                                ],
                                [
                                    'Paragraph 4',
                                    'paragraph-4',
                                    'I am excited about the prospect of joining a dynamic team and contributing to [COMPANY]’s continued growth. I have attached my resume for your review, detailing my qualifications and experience. Thank you for your time and consideration.',
                                    100
                                ],
                            ];
                            foreach ($fields as $field) {
                                echo '<div class="form-floating mb-3">';
                                echo '<textarea id="' . $field[1] . '" name="' . $field[1] . '" class="form-control" style="height:' . ($field[3] ?? 100) . 'px;">' . $field[2] . '</textarea>';
                                echo '<label for="' . $field[1] . '">' . $field[0] . '</label>';
                                echo '</div>';
                            }
                            ?>
                            <div class="form-floating mb-3">
                                <select class="form-select" id="return" name="return">
                                    <option value="pdf">PDF</option>
                                    <option value="html">HTML</option>
                                </select>
                                <label for="return">Return Format</label>
                            </div>
                            <div class="text-end">
                                <input class="btn btn-outline-primary btn-sm" type="submit" value="Generate Cover Letter" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let replace_text = function (target, value) {
                let ids = ['#paragraph-1', '#paragraph-2', '#paragraph-3', '#paragraph-4'];
                $.each(ids, function (i, id) {
                    let text = $(id).val();
                    $(id).val(text.replace(target, value));
                });
            };
            $('#company_name').change(function () {
                replace_text('[COMPANY]', $(this).val());
            });
            $('#position').change(function () {
                replace_text('[POSITION]', $(this).val());
            });
            $('#prev_expertise').change(function () {
                replace_text('[PREV_EXPERTISE]', $(this).val());
            });
            $('#prev_company').change(function () {
                replace_text('[PREVIOUS_COMPANY]', $(this).val());
            });
            $('#company_industry').change(function () {
                replace_text('[COMPANY_INDUSTRY]', $(this).val());
            });
        });
    </script>
<?php $this->endSection() ?>