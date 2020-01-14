<?php if ($pages_count > 1): ?>
<div class="pagination">
    <ul class="pagination__control">
        <?php foreach ($pages as $page): ?>
            <li class="pagination__item <?= ($page == $current_page) ? "pagination__item--active" : ""; ?>">
                <a href="?page=<?= $page; ?>"><?= $page; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>

    <ul class="pagination__control">
        <li class="pagination__item">
            <a href="#">◀</a>
        </li>
        <li class="pagination__item">
            <a href="?page=2">▶</a>
        </li>
    </ul>
</div>
<?php endif; ?>
