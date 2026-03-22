<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        /* ===== RESET & BASE ===== */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #1a1a2e; line-height: 1.6; background: #ffffff; }

        /* ===== TRICOLOR BAR ===== */
        .tricolor-bar {
            width: 100%;
            height: 10px;
            background: linear-gradient(to right, #FF9933 33.33%, #ffffff 33.33%, #ffffff 66.66%, #138808 66.66%);
            border-bottom: 1px solid #dddddd;
        }
        .tricolor-bar-thin {
            width: 100%;
            height: 6px;
            background: linear-gradient(to right, #FF9933 33.33%, #ffffff 33.33%, #ffffff 66.66%, #138808 66.66%);
        }

        /* ===== COVER PAGE ===== */
        .cover-page {
            page-break-after: always;
            padding: 0;
            min-height: 270mm;
            position: relative;
            background: #ffffff;
        }
        .cover-top-bar {
            background: #0a1628;
            padding: 28px 40px 22px;
            text-align: center;
        }
        .cover-logo-text {
            font-size: 44px;
            font-weight: bold;
            color: #ffffff;
            letter-spacing: 10px;
            text-transform: uppercase;
        }
        .cover-logo-accent {
            color: #FF9933;
        }
        .chakra-line {
            display: block;
            width: 60px;
            height: 2px;
            background: #FF9933;
            margin: 6px auto 4px;
        }
        .cover-logo-tagline {
            font-size: 10px;
            color: #94a3b8;
            letter-spacing: 3px;
            text-transform: uppercase;
        }
        .cover-body {
            padding: 36px 48px 28px;
            text-align: center;
        }
        .cover-subtitle-label {
            font-size: 12px;
            color: #6b7280;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        .cover-subtitle-main {
            font-size: 18px;
            font-weight: bold;
            color: #0a1628;
            margin-bottom: 28px;
        }
        .cover-divider {
            width: 80px;
            height: 3px;
            background: linear-gradient(to right, #FF9933, #0a1628, #138808);
            margin: 0 auto 28px;
        }
        .cover-exam-name {
            font-size: 30px;
            font-weight: bold;
            color: #0a1628;
            margin-bottom: 6px;
            letter-spacing: 1px;
        }
        .cover-plan-label {
            font-size: 16px;
            color: #1d4ed8;
            font-weight: bold;
            margin-bottom: 32px;
        }

        /* Student card */
        .student-card {
            background: #f0f4ff;
            border: 1px solid #c7d2fe;
            border-radius: 8px;
            padding: 18px 24px;
            margin: 0 auto 28px;
            max-width: 420px;
            text-align: left;
        }
        .student-card-title {
            font-size: 10px;
            color: #6366f1;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 10px;
            border-bottom: 1px solid #c7d2fe;
            padding-bottom: 6px;
        }
        .student-card-row {
            display: block;
            font-size: 11px;
            color: #1e293b;
            padding: 3px 0;
        }
        .student-card-label {
            color: #6b7280;
            font-size: 10px;
        }
        .student-card-value {
            font-weight: bold;
            color: #0a1628;
        }

        /* Cover quote */
        .cover-quote-box {
            background: #fffbeb;
            border-left: 4px solid #f59e0b;
            border-radius: 0 6px 6px 0;
            padding: 14px 18px;
            margin: 0 auto 22px;
            max-width: 480px;
            text-align: center;
        }
        .cover-quote-hindi {
            font-size: 14px;
            font-weight: bold;
            color: #92400e;
            margin-bottom: 4px;
        }
        .cover-quote-english {
            font-size: 10px;
            color: #78350f;
            font-style: italic;
        }

        /* Confidential tag */
        .confidential-tag {
            font-size: 9px;
            color: #9ca3af;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-top: 10px;
        }

        /* ===== SUMMARY PAGE ===== */
        .summary-page {
            page-break-after: always;
            padding: 0 0 20px;
        }
        .section-header {
            background: #0a1628;
            color: #ffffff;
            padding: 14px 24px;
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }
        .section-header-accent {
            color: #FF9933;
        }
        .summary-body {
            padding: 0 24px;
        }

        /* Strategy card */
        .strategy-card {
            background: #eff6ff;
            border: 1.5px solid #1d4ed8;
            border-radius: 8px;
            padding: 16px 20px;
            margin-bottom: 20px;
        }
        .strategy-card-label {
            font-size: 10px;
            color: #1d4ed8;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .strategy-card-text {
            font-size: 11px;
            color: #1e293b;
            line-height: 1.7;
        }

        /* Quick stats grid */
        .stats-grid {
            margin-bottom: 20px;
        }
        .stats-grid-title {
            font-size: 10px;
            color: #374151;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .stats-row {
            width: 100%;
        }
        .stat-cell {
            display: inline-block;
            width: 30%;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 12px;
            margin-right: 2%;
            margin-bottom: 8px;
            vertical-align: top;
        }
        .stat-cell:nth-child(3n) { margin-right: 0; }
        .stat-icon { font-size: 16px; }
        .stat-label {
            font-size: 9px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 4px;
        }
        .stat-value {
            font-size: 13px;
            font-weight: bold;
            color: #0a1628;
            margin-top: 2px;
        }

        /* Weak areas */
        .weak-areas-box {
            background: #fff1f2;
            border: 1.5px solid #f43f5e;
            border-radius: 8px;
            padding: 14px 18px;
            margin-bottom: 20px;
        }
        .weak-areas-title {
            font-size: 10px;
            color: #e11d48;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .weak-area-pill {
            display: inline-block;
            background: #ffe4e6;
            color: #be123c;
            border: 1px solid #fda4af;
            border-radius: 20px;
            padding: 3px 12px;
            font-size: 10px;
            font-weight: bold;
            margin: 3px 4px 3px 0;
        }
        .weak-areas-note {
            font-size: 10px;
            color: #9f1239;
            margin-top: 8px;
            font-style: italic;
        }

        /* ===== WEEK HEADER ===== */
        .week-header {
            padding: 12px 24px;
            margin-bottom: 14px;
            border-radius: 0 6px 6px 0;
            color: #ffffff;
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .week-header-sub {
            font-size: 10px;
            font-weight: normal;
            opacity: 0.85;
            margin-top: 2px;
        }
        .week-1 { background: linear-gradient(135deg, #d97706, #f59e0b); border-left: 6px solid #92400e; }
        .week-2 { background: linear-gradient(135deg, #0a1628, #1d4ed8); border-left: 6px solid #1e3a8a; }
        .week-3 { background: linear-gradient(135deg, #065f46, #059669); border-left: 6px solid #064e3b; }
        .week-4plus { background: linear-gradient(135deg, #78350f, #b45309); border-left: 6px solid #451a03; }

        /* ===== DAY BLOCK ===== */
        .day-block {
            margin-bottom: 14px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
            page-break-inside: avoid;
        }
        .day-block-week1 { border-left: 5px solid #f59e0b; }
        .day-block-week2 { border-left: 5px solid #1d4ed8; }
        .day-block-week3 { border-left: 5px solid #059669; }
        .day-block-week4plus { border-left: 5px solid #b45309; }

        .day-header {
            background: #f8fafc;
            padding: 10px 14px;
            border-bottom: 1px solid #e2e8f0;
            display: block;
        }
        .day-checkbox {
            font-size: 13px;
            color: #6b7280;
            margin-right: 6px;
        }
        .day-number-badge {
            display: inline-block;
            background: #0a1628;
            color: #ffffff;
            padding: 2px 10px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            margin-right: 8px;
            letter-spacing: 1px;
        }
        .day-number-badge-week1 { background: #d97706; }
        .day-number-badge-week2 { background: #1d4ed8; }
        .day-number-badge-week3 { background: #059669; }
        .day-number-badge-week4plus { background: #b45309; }

        .day-title {
            font-weight: bold;
            font-size: 12px;
            color: #0a1628;
        }
        .day-body { padding: 12px 16px; }

        /* Subject pills + rows */
        .subject-row {
            padding: 7px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .subject-row:last-child { border-bottom: none; }
        .subject-pill {
            display: inline-block;
            background: #e0e7ff;
            color: #3730a3;
            border-radius: 4px;
            padding: 2px 10px;
            font-size: 10px;
            font-weight: bold;
            margin-right: 8px;
        }
        .subject-hours-badge {
            display: inline-block;
            background: #dbeafe;
            color: #1d4ed8;
            border-radius: 12px;
            padding: 1px 8px;
            font-size: 9px;
            font-weight: bold;
        }
        .topics-list {
            margin-top: 4px;
            padding-left: 14px;
        }
        .topic-item {
            font-size: 10px;
            color: #475569;
            padding: 1px 0;
            list-style: disc;
        }

        /* Tips box */
        .tips-box {
            background: #fffbeb;
            border: 1px solid #fcd34d;
            border-radius: 6px;
            padding: 8px 12px;
            margin-top: 10px;
            font-size: 10px;
            color: #78350f;
        }
        .tips-box-label {
            font-weight: bold;
            color: #b45309;
        }

        /* Resources */
        .resources-section { margin-top: 10px; }
        .resources-title {
            font-size: 9px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .resource-item {
            font-size: 10px;
            color: #374151;
            padding: 2px 0;
        }
        .resource-icon { margin-right: 4px; }
        .resource-type-label {
            display: inline-block;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 1px 6px;
            border-radius: 3px;
            margin-right: 4px;
        }
        .res-book   { background: #d1fae5; color: #065f46; }
        .res-practice { background: #dbeafe; color: #1e3a8a; }
        .res-pyq    { background: #fce7f3; color: #9d174d; }
        .res-default { background: #f3f4f6; color: #374151; }

        /* ===== WEEKLY MOTIVATION ===== */
        .motivation-box {
            background: #f0fdf4;
            border: 1.5px solid #16a34a;
            border-radius: 8px;
            padding: 16px 20px;
            margin: 16px 0;
            text-align: center;
            page-break-inside: avoid;
        }
        .motivation-star {
            font-size: 18px;
            margin-bottom: 6px;
        }
        .motivation-hindi {
            font-size: 14px;
            font-weight: bold;
            color: #14532d;
            margin-bottom: 4px;
        }
        .motivation-english {
            font-size: 11px;
            color: #166534;
            font-style: italic;
            margin-bottom: 4px;
        }
        .motivation-source {
            font-size: 9px;
            color: #4ade80;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* ===== FOOTER ===== */
        .page-footer {
            margin-top: 24px;
            padding-top: 8px;
        }
        .footer-content {
            text-align: center;
            color: #9ca3af;
            font-size: 9px;
            padding: 6px 0;
            border-top: 1px solid #e5e7eb;
        }
        .footer-brand { color: #1d4ed8; font-weight: bold; }

        /* ===== PAGE BREAKS ===== */
        .page-break { page-break-before: always; }

        /* ===== CONTENT PADDING ===== */
        .content-wrap { padding: 0 24px; }
    </style>
</head>
<body>

<?php
    // Helper: determine week class based on day number
    function getWeekClass(int $dayNum): string {
        $week = ceil($dayNum / 7);
        if ($week === 1) return 'week1';
        if ($week === 2) return 'week2';
        if ($week === 3) return 'week3';
        return 'week4plus';
    }

    function getWeekLabel(int $dayNum): string {
        $week = ceil($dayNum / 7);
        $labels = [
            1 => ['WEEK 1', 'Foundation Building'],
            2 => ['WEEK 2', 'Deep Practice'],
            3 => ['WEEK 3', 'Revision & Speed'],
        ];
        if (isset($labels[$week])) return implode('|', $labels[$week]);
        return 'WEEK ' . $week . '|Mastery & Mock Tests';
    }

    $weakSubjects = json_decode($blueprint['weak_subjects'] ?? '[]', true);
    if (!is_array($weakSubjects)) $weakSubjects = [];

    $totalDays = count($days);

    $motivationalQuotes = [
        ['hindi' => '"अनुशासन ही सफलता की कुंजी है।"', 'english' => '"Discipline is the key to success."'],
        ['hindi' => '"कठिन परिश्रम का कोई विकल्प नहीं है।"', 'english' => '"There is no substitute for hard work."'],
        ['hindi' => '"जो सोचता है वो करता है, जो करता है वो पाता है।"', 'english' => '"Those who plan, act; those who act, achieve."'],
        ['hindi' => '"हर दिन एक नई शुरुआत है।"', 'english' => '"Every day is a new beginning — make it count."'],
        ['hindi' => '"मंजिल उन्हीं को मिलती है जिनके सपनों में जान होती है।"', 'english' => '"Destination belongs to those whose dreams have life."'],
    ];
?>

<!-- ============================================================ -->
<!-- COVER PAGE                                                    -->
<!-- ============================================================ -->
<div class="cover-page">
    <div class="tricolor-bar"></div>

    <div class="cover-top-bar">
        <div class="cover-logo-text">
            <span class="cover-logo-accent">S</span>ARKARI
        </div>
        <span class="chakra-line"></span>
        <div class="cover-logo-tagline">India's AI-Powered Exam Preparation Platform</div>
    </div>

    <div class="cover-body">
        <div class="cover-subtitle-label">Your Personalized</div>
        <div class="cover-subtitle-main">Success Blueprint</div>
        <div class="cover-divider"></div>

        <div class="cover-exam-name"><?= htmlspecialchars($blueprint['exam_name']) ?></div>
        <div class="cover-plan-label"><?= $totalDays ?>-Day Personalized Study Plan</div>

        <!-- Student Card -->
        <div class="student-card">
            <div class="student-card-title">Candidate Profile</div>
            <div class="student-card-row">
                <span class="student-card-label">Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <span class="student-card-value"><?= htmlspecialchars($user['name']) ?></span>
            </div>
            <div class="student-card-row">
                <span class="student-card-label">Education&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <span class="student-card-value"><?= htmlspecialchars($blueprint['education']) ?></span>
            </div>
            <div class="student-card-row">
                <span class="student-card-label">Study Hours&nbsp;&nbsp;</span>
                <span class="student-card-value"><?= htmlspecialchars($blueprint['study_hours']) ?> hours/day</span>
            </div>
            <div class="student-card-row">
                <span class="student-card-label">Exam Date&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <span class="student-card-value"><?= date('d M Y', strtotime($blueprint['exam_date'])) ?></span>
            </div>
            <div class="student-card-row">
                <span class="student-card-label">Generated On&nbsp;</span>
                <span class="student-card-value"><?= date('d M Y') ?></span>
            </div>
        </div>

        <!-- Motivational Quote -->
        <div class="cover-quote-box">
            <div class="cover-quote-hindi">"सफलता उसे मिलती है जो योजना बनाता है।"</div>
            <div class="cover-quote-english">"Success belongs to those who plan." — Ancient Wisdom</div>
        </div>

        <div class="confidential-tag">
            Confidential &mdash; Prepared exclusively for <?= htmlspecialchars($user['name']) ?>
        </div>
    </div>

    <div class="tricolor-bar-thin"></div>
</div>


<!-- ============================================================ -->
<!-- SUMMARY PAGE                                                  -->
<!-- ============================================================ -->
<div class="summary-page">
    <div class="tricolor-bar"></div>

    <div class="section-header">
        <span class="section-header-accent">&#9654;</span> Study Strategy &amp; Overview
    </div>

    <div class="summary-body">

        <?php if (!empty($blueprint['summary'])): ?>
        <div class="strategy-card">
            <div class="strategy-card-label">&#128270; Strategy Overview</div>
            <div class="strategy-card-text"><?= htmlspecialchars($blueprint['summary']) ?></div>
        </div>
        <?php endif; ?>

        <!-- Quick Stats Grid -->
        <div class="stats-grid">
            <div class="stats-grid-title">&#9889; Quick Stats at a Glance</div>
            <div>
                <div class="stat-cell">
                    <div class="stat-icon">&#128197;</div>
                    <div class="stat-label">Total Days</div>
                    <div class="stat-value"><?= $totalDays ?></div>
                </div>
                <div class="stat-cell">
                    <div class="stat-icon">&#9200;</div>
                    <div class="stat-label">Hours / Day</div>
                    <div class="stat-value"><?= htmlspecialchars($blueprint['study_hours']) ?></div>
                </div>
                <div class="stat-cell">
                    <div class="stat-icon">&#127891;</div>
                    <div class="stat-label">Education</div>
                    <div class="stat-value"><?= htmlspecialchars($blueprint['education']) ?></div>
                </div>
                <div class="stat-cell">
                    <div class="stat-icon">&#127919;</div>
                    <div class="stat-label">Exam Date</div>
                    <div class="stat-value"><?= date('d M Y', strtotime($blueprint['exam_date'])) ?></div>
                </div>
                <div class="stat-cell">
                    <div class="stat-icon">&#128198;</div>
                    <div class="stat-label">Weeks Planned</div>
                    <div class="stat-value"><?= ceil($totalDays / 7) ?></div>
                </div>
                <div class="stat-cell">
                    <div class="stat-icon">&#128215;</div>
                    <div class="stat-label">Focus Areas</div>
                    <div class="stat-value"><?= count($weakSubjects) ?></div>
                </div>
            </div>
        </div>

        <!-- Weak Areas Spotlight -->
        <?php if (!empty($weakSubjects)): ?>
        <div class="weak-areas-box">
            <div class="weak-areas-title">&#9888; Your Focus Areas — Extra Attention Required</div>
            <?php foreach ($weakSubjects as $area): ?>
                <span class="weak-area-pill"><?= htmlspecialchars($area) ?></span>
            <?php endforeach; ?>
            <div class="weak-areas-note">These subjects have been given extra weight in your daily schedule. Do not skip sessions covering these topics.</div>
        </div>
        <?php endif; ?>

    </div>

    <div class="tricolor-bar-thin"></div>
</div>


<!-- ============================================================ -->
<!-- DAILY BLUEPRINT                                               -->
<!-- ============================================================ -->
<div class="page-break"></div>
<div class="tricolor-bar"></div>
<div class="section-header">
    <span class="section-header-accent">&#9654;</span> Your Day-by-Day Blueprint
</div>

<?php
$currentWeek = 0;
$motivationIndex = 0;

foreach ($days as $i => $day):
    $dayNum = (int)$day['day_number'];
    $week = (int) ceil($dayNum / 7);
    $wClass = getWeekClass($dayNum);

    // Page break every 3 days (but not at the very start)
    if ($i > 0 && $i % 3 === 0):
?>
        <div class="page-break"></div>
        <div class="tricolor-bar"></div>
        <div class="section-header">
            <span class="section-header-accent">&#9654;</span> Day-by-Day Blueprint (continued)
        </div>
<?php endif; ?>

<?php
    // Week header when week changes
    if ($week !== $currentWeek):
        $currentWeek = $week;
        $weekParts = explode('|', getWeekLabel($dayNum));
        $weekTitle = $weekParts[0];
        $weekSubtitle = $weekParts[1] ?? '';
?>
        <div class="content-wrap">
            <div class="week-header week-<?= $wClass ?>">
                <?= htmlspecialchars($weekTitle) ?>
                <div class="week-header-sub"><?= htmlspecialchars($weekSubtitle) ?></div>
            </div>
        </div>
<?php endif; ?>

    <!-- Day Block -->
    <div class="content-wrap">
        <div class="day-block day-block-<?= $wClass ?>">
            <div class="day-header">
                <span class="day-checkbox">&#9744;</span>
                <span class="day-number-badge day-number-badge-<?= $wClass ?>">DAY <?= $dayNum ?></span>
                <span class="day-title"><?= htmlspecialchars($day['title']) ?></span>
            </div>
            <div class="day-body">

                <?php
                    $subjects = json_decode($day['subjects_json'] ?? '[]', true);
                    if (!is_array($subjects)) $subjects = [];
                ?>

                <?php foreach ($subjects as $sub): ?>
                <div class="subject-row">
                    <span class="subject-pill"><?= htmlspecialchars($sub['subject'] ?? '') ?></span>
                    <span class="subject-hours-badge">&#9201; <?= htmlspecialchars($sub['hours'] ?? '0') ?> hrs</span>

                    <?php if (!empty($sub['topics'])): ?>
                    <ul class="topics-list">
                        <?php foreach ((array)$sub['topics'] as $topic): ?>
                            <li class="topic-item"><?= htmlspecialchars($topic) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>

                <?php if (!empty($day['tips'])): ?>
                <div class="tips-box">
                    <span class="tips-box-label">&#128161; Pro Tip: </span><?= htmlspecialchars($day['tips']) ?>
                </div>
                <?php endif; ?>

                <?php
                    $resources = json_decode($day['resources'] ?? '[]', true);
                    if (!is_array($resources)) $resources = [];
                ?>
                <?php if (!empty($resources)): ?>
                <div class="resources-section">
                    <div class="resources-title">Resources</div>
                    <?php foreach ($resources as $res):
                        $rtype = strtolower(trim($res['type'] ?? 'book'));
                        $icon = '&#128218;';
                        $labelClass = 'res-book';
                        if (strpos($rtype, 'practice') !== false || strpos($rtype, 'test') !== false) {
                            $icon = '&#128221;'; $labelClass = 'res-practice';
                        } elseif (strpos($rtype, 'pyq') !== false || strpos($rtype, 'previous') !== false) {
                            $icon = '&#127919;'; $labelClass = 'res-pyq';
                        }
                    ?>
                    <div class="resource-item">
                        <span class="resource-icon"><?= $icon ?></span>
                        <span class="resource-type-label <?= $labelClass ?>"><?= htmlspecialchars(strtoupper($rtype)) ?></span>
                        <?= htmlspecialchars($res['title'] ?? '') ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

<?php
    // Motivational quote after every 7 days
    if ($dayNum % 7 === 0 && $dayNum < $totalDays):
        $quote = $motivationalQuotes[$motivationIndex % count($motivationalQuotes)];
        $motivationIndex++;
?>
    <div class="content-wrap">
        <div class="motivation-box">
            <div class="motivation-star">&#11088;</div>
            <div class="motivation-hindi"><?= $quote['hindi'] ?></div>
            <div class="motivation-english"><?= $quote['english'] ?></div>
            <div class="motivation-source">Weekly Motivation &mdash; Sarkari Blueprint</div>
        </div>
    </div>
<?php
    endif;
endforeach;
?>

<!-- ============================================================ -->
<!-- FOOTER                                                        -->
<!-- ============================================================ -->
<div class="page-footer">
    <div class="tricolor-bar-thin"></div>
    <div class="footer-content">
        Generated by <span class="footer-brand">Sarkari AI</span>
        &nbsp;|&nbsp; sarkaariblueprint.in
        &nbsp;|&nbsp; Prepared exclusively for <?= htmlspecialchars($user['name']) ?>
        &nbsp;|&nbsp; <?= date('d M Y') ?>
    </div>
</div>

</body>
</html>
