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

    <a class="button button--transparent button--plus content__side-button" href="form-project.html">Добавить проект</a>
</section>

<main class="content__main">
    <h2 class="content__main-heading">Добавление задачи</h2>

    <form class="form" action="/add-task.php" method="post" autocomplete="off" enctype='multipart/form-data'>
        <div class="form__row">
            <label class="form__label" for="name">Название <sup>*</sup></label>
            <?= isset($errors['name']) ? "<p class='form__message'>" . $errors['name'] . "</p>" : "" ?>
            <input class="form__input  <?= isset($errors['name']) ? 'form__input--error' : '' ?>"
                   type="text" name="name" id="name" value="<?= $_POST['name'] ?? '' ?>" placeholder="Введите название">
        </div>

        <div class="form__row">
            <label class="form__label" for="category">Проект <sup>*</sup></label>
            <?= isset($errors['category']) ? "<p class='form__message'>" . $errors['category'] . "</p>" : "" ?>
            <select class="form__input form__input--select <?= isset($errors['category']) ? 'form__input--error' : '' ?>"
                    name="category" id="category">
                <option value="<?= $_POST['category'] ?? '' ?>"><?= $_POST['category'] ? $returnCategory : 'Выберите проект' ?></option>
                <?php foreach ($categories as $value): ?>
                    <option value='<?= strip_tags($value['id']) ?>'><?= strip_tags($value['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form__row">
            <label class="form__label" for="date">Дата выполнения</label>
            <?= isset($errors['date']) ? "<p class='form__message'>" . $errors['date'] . "</p>" : "" ?>
            <input class="form__input form__input--date <?= isset($errors['date']) ? 'form__input--error' : '' ?>"
                   type="text" name="date" id="date" value="<?= $_POST['date'] ?? '' ?>"
                   placeholder="Введите дату в формате ГГГГ-ММ-ДД">
        </div>

        <div class="form__row">
            <label class="form__label" for="file">Файл</label>

            <div class="form__input-file">
                <input class="visually-hidden" type="file" name="file" id="file" value="">

                <label class="button button--transparent" for="file">
                    <span>Выберите файл</span>
                </label>
            </div>
        </div>

        <div class="form__row form__row--controls">
            <input class="button" type="submit" name="submit" value="Добавить">
        </div>
    </form>
</main>
