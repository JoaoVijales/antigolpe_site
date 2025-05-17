<?php /** @var array $metrics */ ?>
<section class="dashboard">
  <div class="dashboard-header">
    <h1><?= $this->translate('dashboard.security_title') ?></h1>
  </div>
  
  <div class="metrics-grid">
    <?php foreach ($metrics as $metric): ?>
      <div class="metric-card">
        <h3><?= $this->translate($metric['title']) ?></h3>
        <div class="metric-value"><?= $metric['value'] ?></div>
        <div class="metric-trend <?= $metric['trend'] ?>">
          <?= $this->translate('dashboard.trend_' . $metric['trend']) ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="security-events">
    <h2><?= $this->translate('dashboard.recent_events') ?></h2>
    <div class="events-list">
      <?php foreach ($events as $event): ?>
        <div class="event-item <?= $event['severity'] ?>">
          <div class="event-icon">
            <?= $this->svg('alert-' . $event['type']) ?>
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