<?php
$pageTitle = 'Partner Program | Quantum Scalp AI';
$pageDescription = 'Qualified partners can introduce users to the platform and participate in our approved referral/affiliate program.';
$currentPage = 'partners';

require_once __DIR__ . '/account/connection.php';
require_once __DIR__ . '/inc/partner-applications.php';

qs_ensure_partner_applications_table($mysqli);

$formNotice = '';
$formOk = false;
$formValues = array(
	'full_name' => '',
	'email' => '',
	'country' => '',
	'phone' => '',
	'program_type' => 'Partner',
	'experience' => '',
	'message' => '',
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	foreach ($formValues as $key => $default) {
		$formValues[$key] = isset($_POST[$key]) ? (string) $_POST[$key] : $default;
	}
	$result = qs_save_partner_application($mysqli, $_POST);
	$formOk = !empty($result['ok']);
	$formNotice = isset($result['message']) ? $result['message'] : '';
	if ($formOk) {
		$formValues = array(
			'full_name' => '',
			'email' => '',
			'country' => '',
			'phone' => '',
			'program_type' => 'Partner',
			'experience' => '',
			'message' => '',
		);
	}
}

include 'inc/public-start.php';
include 'header.php';

$emphasize = array(
	array('Technology Adoption', 'Introduce users to sophisticated arbitrage technology.', 'laptop'),
	array('Customer Education', 'Help members understand the technology, architecture, and risks.', 'grad'),
	array('Community Building', 'Build informed, engaged communities around the platform.', 'group'),
	array('Leadership & Training', 'Develop leadership through training and product awareness.', 'handshake'),
);

$compensation = array(
	array('Direct Referral', 'Commission for directly introduced members.'),
	array('Multi-Level Commission', 'Structured commission across approved levels.'),
	array('Team Volume Bonus', 'Bonuses based on qualified team volume.'),
	array('Leadership Bonus', 'Recognition and bonuses for leadership ranks.'),
	array('Global Overrides', 'Overrides for qualified senior leaders.'),
	array('Rank Advancement', 'Advancement incentives across ranks.'),
);

function qs_partner_icon($name) {
	$icons = array(
		'laptop' => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="4" width="18" height="12" rx="2"/><path d="M2 19h20M8 19v1h8v-1"/></svg>',
		'grad' => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M3 9l9-5 9 5-9 5-9-5z"/><path d="M7 11.5v4.2c0 .7 2.2 2.3 5 2.3s5-1.6 5-2.3v-4.2"/><path d="M21 9v6"/></svg>',
		'group' => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="9" cy="8" r="3"/><circle cx="17" cy="9" r="2.4"/><path d="M3.5 19c.8-3.2 3.2-5 5.5-5s4.7 1.8 5.5 5"/><path d="M14.5 14.2c2 .3 3.8 1.7 4.5 4.3"/></svg>',
		'handshake' => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M8 12l3 3 2.2-2.2a2 2 0 0 1 2.8 0L18 14.5"/><path d="M3 10.5L8 6l3.5 3.5"/><path d="M21 10.5L16 6l-2 2"/><path d="M8 12l-2.5 2.5a2 2 0 0 0 0 2.8L8 19.8"/></svg>',
	);
	return isset($icons[$name]) ? $icons[$name] : '';
}

$programs = array('Partner', 'Affiliate', 'Regional Partner');
?>
<main>
    <section class="qs-hero qs-section--tight">
        <div class="qs-wrap">
            <p class="qs-eyebrow">Partner Program</p>
            <h1 class="qs-h1">Partner With Quantum Scalp.</h1>
            <p class="qs-lead qs-lead--wide">Qualified partners can introduce users to the platform and participate in our approved referral/affiliate program — emphasizing technology adoption, education, and community, not recruitment.</p>
        </div>
    </section>

    <section class="qs-section qs-section--tight">
        <div class="qs-wrap">
            <p class="qs-eyebrow">What We Emphasize</p>
            <h2 class="qs-h2">Build understanding, not hype.</h2>
            <div class="qs-grid-4" style="margin-top:36px;">
                <?php foreach ($emphasize as $item) { ?>
                    <article class="qs-card">
                        <span class="qs-cap-icon"><?php echo qs_partner_icon($item[2]); ?></span>
                        <h3 class="qs-h3"><?php echo htmlspecialchars($item[0]); ?></h3>
                        <p class="qs-muted"><?php echo htmlspecialchars($item[1]); ?></p>
                    </article>
                <?php } ?>
            </div>
        </div>
    </section>

    <section class="qs-section">
        <div class="qs-wrap">
            <p class="qs-eyebrow">Compensation Structure</p>
            <h2 class="qs-h2">A flexible, configurable plan.</h2>
            <p class="qs-lead qs-lead--wide">Values are configurable and subject to the official, legally approved compensation plan.</p>
            <div class="qs-grid-3" style="margin-top:36px;">
                <?php foreach ($compensation as $item) { ?>
                    <article class="qs-card">
                        <h3 class="qs-h3"><?php echo htmlspecialchars($item[0]); ?></h3>
                        <p class="qs-muted"><?php echo htmlspecialchars($item[1]); ?></p>
                    </article>
                <?php } ?>
            </div>
            <p class="qs-partner-note">All compensation is subject to the official compensation plan, applicable terms, and geographic restrictions.</p>
        </div>
    </section>

    <section class="qs-section" id="apply">
        <div class="qs-wrap qs-apply">
            <div>
                <p class="qs-eyebrow">Apply</p>
                <h2 class="qs-h2">Become a partner.</h2>
                <p class="qs-lead">Tell us about yourself. Applications are reviewed and subject to approval.</p>
            </div>
            <form class="qs-form qs-partner-form" method="post" action="partners#apply">
                <?php if ($formNotice !== '') { ?>
                    <p class="<?php echo $formOk ? 'qs-form-ok' : 'qs-form-err'; ?>"><?php echo htmlspecialchars($formNotice); ?></p>
                <?php } ?>
                <label>Full Name <span class="qs-req">*</span>
                    <input type="text" name="full_name" placeholder="Jane Doe" required value="<?php echo htmlspecialchars($formValues['full_name']); ?>">
                </label>
                <label>Email <span class="qs-req">*</span>
                    <input type="email" name="email" placeholder="jane@example.com" required value="<?php echo htmlspecialchars($formValues['email']); ?>">
                </label>
                <label>Country
                    <input type="text" name="country" placeholder="Country" value="<?php echo htmlspecialchars($formValues['country']); ?>">
                </label>
                <label>Phone
                    <input type="text" name="phone" placeholder="+1..." value="<?php echo htmlspecialchars($formValues['phone']); ?>">
                </label>
                <label>Program
                    <select name="program_type">
                        <?php foreach ($programs as $program) { ?>
                            <option value="<?php echo htmlspecialchars($program); ?>"<?php echo $formValues['program_type'] === $program ? ' selected' : ''; ?>><?php echo htmlspecialchars($program); ?></option>
                        <?php } ?>
                    </select>
                </label>
                <label>Relevant Experience
                    <input type="text" name="experience" placeholder="Community building, sales, tech..." value="<?php echo htmlspecialchars($formValues['experience']); ?>">
                </label>
                <label>Message
                    <textarea name="message" placeholder="Tell us more..."><?php echo htmlspecialchars($formValues['message']); ?></textarea>
                </label>
                <button class="qs-btn qs-btn--primary qs-btn--block" type="submit">Submit Application</button>
            </form>
        </div>
    </section>
</main>
<?php include 'footer.php'; include 'inc/public-end.php'; ?>
