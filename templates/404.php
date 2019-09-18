<section class="content__side">
    <h2 class="content__side-heading">Проекты</h2>
    <nav class="main-navigation">
        <ul class="main-navigation__list">
            <?php foreach ($categories as $value): ?>
                <li class="main-navigation__list-item <?= ($_GET['id'] === $value['id']) ? 'main-navigation__list-item--active' : ''; ?>">
                    <a class="main-navigation__list-item-link"
                       href="/index.php?id=<?= $value['id'] ?>"><?= strip_tags($value['name']) ?></a>
                    <span class="main-navigation__list-item-count">
                       <?= $value['tasksCount']; ?>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
</section>

<main class="content__main">
    <h2 class="content__main-heading">Список задач</h2>
    <hr>
    <p>По вашему запросу ничего не найдено</p>
</main>