<?php
$pageTitle = 'Corporate Events | Quantum Scalp AI';
$pageDescription = 'Meetings, webinars, and product demonstrations. Global leadership meetings, webinars, technology presentations, training sessions, and regional events.';
$currentPage = 'events';
include 'inc/public-start.php';
include 'header.php';

$events = array(
    array(
        'tag' => 'Leadership',
        'tag_class' => 'leadership',
        'date' => 'Jul 17, 2026',
        'title' => 'Leadership Meeting',
        'copy' => 'Strategic leadership meeting for regional and global business builders.',
        'location' => 'Dubai, UAE',
        'status' => '',
    ),
    array(
        'tag' => 'Webinar',
        'tag_class' => 'webinar',
        'date' => 'Jul 1, 2026',
        'title' => 'Q-Core Technology Webinar',
        'copy' => 'Deep-dive product demonstration of the Q-Core engine and strategy analysis.',
        'location' => 'Online',
        'status' => '',
    ),
    array(
        'tag' => 'Training',
        'tag_class' => 'training',
        'date' => 'Jul 24, 2026',
        'title' => 'Arbitrage Strategy Training',
        'copy' => 'Educational session covering the six arbitrage strategies and platform usage.',
        'location' => 'Online',
        'status' => '',
    ),
    array(
        'tag' => 'Regional',
        'tag_class' => 'regional',
        'date' => 'Aug 13, 2026',
        'title' => 'Regional Partner Summit',
        'copy' => 'Regional community building and product awareness event.',
        'location' => 'Singapore',
        'status' => 'Done',
        'status_class' => 'done',
    ),
    array(
        'tag' => 'Upcoming',
        'tag_class' => 'upcoming',
        'date' => 'March 27, 2027',
        'title' => 'Q-Core Product Demonstration',
        'copy' => 'Upcoming product demonstration and platform briefing for partners and regional leaders.',
        'location' => 'To be announced',
        'status' => '',
    ),
);
?>
<main>
    <section class="qs-hero qs-section--tight">
        <div class="qs-wrap">
            <p class="qs-eyebrow">Corporate Events</p>
            <h1 class="qs-h1">Meetings, webinars, and product demonstrations.</h1>
            <p class="qs-lead qs-lead--wide">Global leadership meetings, webinars, technology presentations, training sessions, and regional events.</p>
        </div>
    </section>

    <section class="qs-section qs-section--tight">
        <div class="qs-wrap">
            <div class="qs-event-list">
                <?php foreach ($events as $event) { ?>
                    <article class="qs-event-card">
                        <span class="qs-event-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="4" y="5" width="16" height="15" rx="2"/><path d="M8 3v4M16 3v4M4 10h16"/></svg>
                        </span>
                        <div class="qs-event-body">
                            <div class="qs-event-meta">
                                <span class="qs-event-tag qs-event-tag--<?php echo htmlspecialchars($event['tag_class']); ?>"><?php echo htmlspecialchars($event['tag']); ?></span>
                                <?php if (!empty($event['status'])) { ?>
                                    <span class="qs-event-tag qs-event-tag--<?php echo htmlspecialchars($event['status_class']); ?>"><?php echo htmlspecialchars($event['status']); ?></span>
                                <?php } ?>
                                <span class="qs-event-date"><?php echo htmlspecialchars($event['date']); ?></span>
                            </div>
                            <h3 class="qs-h3"><?php echo htmlspecialchars($event['title']); ?></h3>
                            <p class="qs-muted"><?php echo htmlspecialchars($event['copy']); ?></p>
                        </div>
                        <p class="qs-event-loc">
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M12 21s7-5.4 7-11a7 7 0 1 0-14 0c0 5.6 7 11 7 11z"/><circle cx="12" cy="10" r="2.2"/></svg>
                            <?php echo htmlspecialchars($event['location']); ?>
                        </p>
                    </article>
                <?php } ?>
            </div>
        </div>
    </section>
</main>
<?php include 'footer.php'; include 'inc/public-end.php'; ?>
