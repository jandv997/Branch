<?php
$pageTitle = 'Team | Quantum Scalp AI';
$pageDescription = 'The people building Quantum Scalp. Verified biographies published by administrators — we never fabricate professional histories, credentials, or achievements.';
$currentPage = 'team';
include 'inc/public-start.php';
include 'header.php';

$photoPlaceholder = '<svg viewBox="0 0 24 24" width="56" height="56" fill="none" stroke="currentColor" stroke-width="1.4"><circle cx="12" cy="8" r="3.2"/><path d="M5.5 19c1.4-3.2 3.7-4.8 6.5-4.8S16.6 15.8 18 19"/></svg>';

$members = array(
    array(
        'name' => 'Peter Larsen',
        'role' => 'Chief Executive Officer',
        'photo' => 'assets/img/team/peter-larsen.jpg',
        'focus' => 'Company strategy, vision, and executive leadership.',
        'bio' => array(
            'Peter Larsen brings more than 25 years of experience across financial markets, quantitative trading, and technology. His career spans the early development of electronic markets and quantitative approaches to trading, giving him a long-term perspective on how technology has transformed the financial industry.',
            'After spending more than two decades away from the public spotlight, Peter returned to the industry to help build Quantum Scalp, launched in 2025.',
            'As CEO, Peter focuses on keeping the business disciplined and well-structured, supporting the team, and ensuring the company remains focused on its long-term objectives.',
        ),
    ),
    array(
        'name' => 'Leland Melvin',
        'role' => 'Chief Marketing Officer',
        'photo' => '',
        'focus' => 'Brand, marketing campaigns, and customer communications.',
        'bio' => array(
            'Leland has served as Chief Marketing Officer since 2025, leading the company’s brand, marketing campaigns, and customer communications.',
            'His role is focused on making the company’s message clear and accessible, while developing marketing strategies that effectively connect the business with its audience.',
            'Leland works closely with the team to ensure the brand remains consistent, professional, and focused as the company continues to grow.',
        ),
    ),
    array(
        'name' => 'Lana Choi',
        'role' => 'Chief Operating Officer',
        'photo' => '',
        'focus' => 'Day-to-day operations, internal processes, and coordination across teams.',
        'bio' => array(
            'Lana serves as Chief Operating Officer, overseeing the company’s day-to-day operations, internal processes, and coordination across teams.',
            'With a background in logistics and supply chain, she brings a practical, process-driven approach to the role. Her focus is on keeping operations organized, ensuring teams have the resources they need, and helping the business execute efficiently as it grows.',
            'Lana works closely with leadership and the wider team to turn plans into effective day-to-day operations.',
        ),
    ),
    array(
        'name' => 'Austin',
        'role' => 'Chief Growth Officer',
        'photo' => 'assets/img/team/austin.jpg',
        'focus' => 'Growth opportunities, market expansion, and sustainable revenue.',
        'bio' => array(
            'Austin serves as Chief Growth Officer, focusing on identifying new opportunities for growth, expanding the company’s reach, and developing strategies that support sustainable revenue growth.',
            'With experience in sales and business development, Austin brings a strong understanding of what it takes to build relationships, convert opportunities, and scale a growing business.',
            'His focus is on turning growth opportunities into measurable results while helping the company expand into new markets.',
        ),
    ),
    array(
        'name' => 'Jan Devries',
        'role' => 'Chief Experience Officer',
        'photo' => '',
        'focus' => 'User and distributor experience across the platform.',
        'bio' => array(
            'Jan serves as Chief Experience Officer, bringing a unique combination of engineering experience and years of experience in network marketing.',
            'His background gives him a practical understanding of both technology and the people who use it. After transitioning from engineering into the network marketing industry, he spent years working in the field and developing a deep understanding of distributors, leaders, and community-driven growth.',
            'At Quantum Scalp, Jan focuses on shaping the overall user and distributor experience, helping ensure that the platform, communication, and business environment are practical, accessible, and built around the needs of its users.',
        ),
    ),
);
?>
<main>
    <section class="qs-hero qs-section--tight">
        <div class="qs-wrap">
            <p class="qs-eyebrow">Team</p>
            <h1 class="qs-h1">The people building Quantum Scalp.</h1>
            <p class="qs-lead qs-lead--wide">Verified biographies are added by administrators. We never fabricate professional histories, credentials, employment history, or achievements.</p>
        </div>
    </section>

    <section class="qs-section qs-section--tight">
        <div class="qs-wrap">
            <div class="qs-team">
                <?php foreach ($members as $member) { ?>
                    <article class="qs-team-card">
                        <div class="qs-team-photo<?php echo empty($member['photo']) ? ' qs-team-photo--empty' : ''; ?>">
                            <?php if (!empty($member['photo'])) { ?>
                                <img src="<?php echo htmlspecialchars($member['photo']); ?>" alt="<?php echo htmlspecialchars($member['name']); ?>">
                            <?php } else { ?>
                                <span aria-hidden="true"><?php echo $photoPlaceholder; ?></span>
                            <?php } ?>
                        </div>
                        <div class="qs-team-body">
                            <h2 class="qs-h3"><?php echo htmlspecialchars($member['name']); ?></h2>
                            <p class="qs-team-role"><?php echo htmlspecialchars($member['role']); ?></p>
                            <?php foreach ($member['bio'] as $para) { ?>
                                <p class="qs-team-bio"><?php echo htmlspecialchars($para); ?></p>
                            <?php } ?>
                            <p class="qs-team-focus"><?php echo htmlspecialchars($member['focus']); ?></p>
                        </div>
                    </article>
                <?php } ?>
            </div>
        </div>
    </section>
</main>
<?php include 'footer.php'; include 'inc/public-end.php'; ?>
