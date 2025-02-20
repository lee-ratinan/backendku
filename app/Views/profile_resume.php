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
                            $fields = [
                                [
                                    'Summary',
                                    'summary',
                                    '[JOB-TITLE] with over 11 years of experience driving software development with data-driven decision-making skills. Achievements include a 20% increase in project completion rates and an over 10-time increase in system capability for the e-commerce platform. Skilled in Agile leadership, system design, and cloud architecture.'
                                ],
                                [
                                    'Soft Skills',
                                    'skills[soft]',
                                    'Scrum Master/Project Management (CSM, PSM I-II Certified), Product Management (PSPO I-II Certified), Leadership Skills, Analytical and Strategical Problem-Solving Skills, Mentorship, Coaching, Collaboration, Business Strategies, Eﬀective Communication, Teamwork, Time Management, Adaptability'
                                ],
                                [
                                    'Technical Skills',
                                    'skills[technical]',
                                    'Agile/Scrum, Kanban, Cloud Architecture/Infrastructure (AWS, VPS), Cloud Computing, UX/UI Design, Software Design and Analysis, Database (SQL, MySQL, PostgreSQL, RDS, NoSQL), Software Optimisation, Performance Engineering, Code Review and Quality Assurance, Data Analysis/Data Science, Database Design, API Development, Artificial Intelligence, Machine Learning, CI/CD'
                                ],
                                [
                                    'Tech Stack',
                                    'skills[tech-stack]',
                                    'PHP , Laravel, CodeIgniter (CI), SQL, AWS, EC2, S3, RDS, Lambda, Git, BitBucket, CI/CD, HTML, CSS, JS, jQuery, DataTables, Python, Microservice, API, Docker'
                                ],
                                [
                                    'Project Management',
                                    'skills[project-management]',
                                    'Jira, Asana, ClickUp, Trello, Gantt Chart, Kanban Board, Product Backlog, Market Research, Sprint Planning, Release Planning, Risk Management, Stakeholder Management, Project Documentation, Project Scheduling, Project Budgeting, Project Evaluation, Project Reporting'
                                ],
                                [
                                    'Moolahgo',
                                    'experience[moolahgo]',
                                    '- Designed and implemented scalable and secure technical architectures and innovative financial solutions to support the company’s growing business needs and expanded the company’s services to new markets. Helped the team remove impediments, refined product backlog, etc., for seamless project delivery across 3 main product domains, significantly increasing financial activities, and the innovative solutions drove business growth by 300% in 6 months.
- Implemented process improvements by inspecting the current process and adapting to new and innovative solutions, such as optimised testing and deployment methodologies, resulting in a 20% increase in the project completion rate in 3 months.
- Led a team of 6 software engineers, mentored their software development, and ensured efficient collaboration for timely project delivery while maintaining quality standards and compliance. Designed and implemented high-performance backend systems to meet stringent regulatory requirements, including remittance, KYC, and fraud detection features.',
                                    200
                                ],
                                [
                                    'Irvins',
                                    'experience[irvins]',
                                    '- Led the development team on the state-of-the-art back office system that integrated multiple Shopify stores that addressed the pain points experienced by the stakeholders, accelerated the efficiency and operations process at the distribution centers by 80%, and slashed monthly infrastructure bills by 50%.
- Implemented effective fraud prevention and detection policies, improved customer satisfaction, and significantly reduced chargeback cases from international customers by 90%.
- Led a team of developers to successfully implement a new back office system, ensuring on-time delivery and adherence to project timelines while maintaining high-quality standards.',
                                    200
                                ],
                                [
                                    'Secretlab',
                                    'experience[secretlab]',
                                    '- Successfully managed the implementation of a new backend system, ensuring on-time delivery and adherence to project timelines while maintaining high-quality standards, freeing up 2+ hours daily for the local delivery team alone and countless more man-hours for other departments downstream; it enabled the company to handle more than 1000+% growth of orders worldwide within 1 year.
- Provided technical guidance and problem-solving expertise to address complex challenges and ensure the system’s reliability and performance, migrated the system to improve reliability across multiple availability zones, and rectified various technical problems for the whole company.
- Effectively led a small backend team, fostering a collaborative and innovative work environment to deliver high-quality systems that met the needs of the business on time.',
                                    200
                                ],
                                [
                                    'BuzzCity',
                                    'experience[buzzcity]',
                                    '- Enhanced the publisher’s payout system by implementing a background process, optimising system performance, and improving overall efficiency, accelerated the downstream systems by 80%.
- Implemented a new robust payout recording system to replace the broken one by re-analysing all requirements and adopting new design ideas that fit the solutions, demonstrating strong technical skills and problem-solving abilities.
- Received training and mentorship to develop leadership skills, positioning me for future leadership roles within the team. Provided guidance and support to junior team members, fostering a collaborative and innovative work environment.',
                                    175
                                ],
                                [
                                    'DST',
                                    'experience[dst]',
                                    '- Optimised the complex fund redemption calculation, which reduced processing time by over 50% through code refactoring, complexity analysis, and performance engineering.
- Successfully implemented and trained the team on Scrum and Kanban methodologies, improved the work processes for the team, and increased the team’s productivity by 30% after adopting the Kanban board.
- Successfully managed multiple projects, including dividend processing, redemption calculation, and report generation, ensuring timely delivery and adherence to project timelines.',
                                    175
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
                                <select class="form-select" id="template" name="template">
                                    <option value="generic">Generic</option>
                                    <option value="europass">EUROPASS</option>
                                </select>
                                <label for="return">Return Format</label>
                            </div>
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
                        <h5 class="card-title">Resume 2</h5>
                        <form method="GET" action="<?= base_url($session->locale . '/office/profile/resume/builder2') ?>" target="_blank">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="job_title" name="job_title" value="Technical Lead" required>
                                <label for="job_title">Job Title</label>
                            </div>
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