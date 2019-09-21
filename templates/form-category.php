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

    <a class="button button--transparent button--plus content__side-button"
       href="/add-cat.php" target="project_add">Добавить проект</a>
</section>


<main class="content__main">
    <h2 class="content__main-heading">Добавление проекта</h2>

    <form class="form"  action="/add-cat.php" method="post" autocomplete="off">
        <div class="form__row">
            <label class="form__label" for="project_name">Название <sup>*</sup></label>
            <?= isset($errors['name']) ? "<p class='form__message'>" . $errors['name'] . "</p>" : "" ?>
            <input class="form__input <?= isset($errors['name']) ? 'form__input--error' : '' ?>"
                   type="text" name="name" id="project_name" value="" placeholder="Введите название проекта">
        </div>

        <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Добавить">
        </div>
    </form>
</main>
