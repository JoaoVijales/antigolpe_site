<!-- Conteúdo existente... -->

<section id="faq" class="faq-section">
    <h2><?= $faq['title'] ?></h2>
    <div class="faq-container">
        <?php foreach ($faq['items'] as $item): ?>
            <details class="faq-item" <?= $item['is_open'] ? 'open' : '' ?>>
                <summary class="faq-question">
                    <?= htmlspecialchars($item['question']) ?>
                    <span class="material-icons">expand_more</span>
                </summary>
                <div class="faq-answer">
                    <?php if (is_array($item['answer'])): ?>
                        <ul>
                            <?php foreach ($item['answer'] as $point): ?>
                                <li><?= htmlspecialchars($point) ?></li>
                            <?php endforeach ?>
                        </ul>
                    <?php else: ?>
                        <p><?= htmlspecialchars($item['answer']) ?></p>
                    <?php endif ?>
                </div>
            </details>
        <?php endforeach ?>
    </div>
</section>

<!-- Conteúdo existente... -->