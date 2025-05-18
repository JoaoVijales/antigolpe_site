<?php use App\Utils\View; /** @var array $metrics */ ?>
<section class="dashboard">
  <div class="dashboard-header">
    <h1><?= View::translate('dashboard.security_title') ?></h1>
  </div>
  
  <div class="metrics-grid">
    <?php foreach ($metrics as $metric): ?>
      <div class="metric-card">
        <h3><?= View::translate($metric['title']) ?></h3>
        <div class="metric-value"><?= $metric['value'] ?></div>
        <div class="metric-trend <?= $metric['trend'] ?>">
          <?= View::translate('dashboard.trend_' . $metric['trend']) ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="security-events">
    <h2><?= View::translate('dashboard.recent_events') ?></h2>
    <div class="events-list">
      <?php foreach ($events as $event): ?>
        <div class="event-item <?= $event['severity'] ?>">
          <div class="event-icon">
            <?= View::svg('alert-' . $event['type']) ?>
          </div>
          <div class="event-details">
            <div class="event-title"><?= $event['title'] ?></div>
            <div class="event-time"><?= $event['timestamp'] ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>