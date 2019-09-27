<main class="content__main">
    <h2 class="content__main-heading">Добавление задачи</h2>

    <form class="form" action="/add-task.php" method="post" autocomplete="off" enctype='multipart/form-data'>
        <div class="form__row">
            <label class="form__label" for="name">Название <sup>*</sup></label>
            <input class="form__input  <?= isset($errors['name']) ? 'form__input--error' : '' ?>"
                   type="text" name="name" id="name" value="<?= $_POST['name'] ?? '' ?>" placeholder="Введите название">
            <?= isset($errors['name']) ? "<p class='form__message'>" . $errors['name'] . "</p>" : "" ?>
        </div>

        <div class="form__row">
            <label class="form__label" for="category">Проект <sup>*</sup></label>
            <select class="form__input form__input--select <?= isset($errors['category']) ? 'form__input--error' : '' ?>"
                    name="category" id="category">
                <option value=''>Выберите проект</option>
                <?php foreach ($categories as $value): ?>
                    <option value='<?= $value['id'] ?>'
                        <?= isset ($_POST['category']) && $value['id'] === $_POST['category'] ? 'selected' : '' ?>>
                        <?= strip_tags($value['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <?= isset($errors['category']) ? "<p class='form__message'>" . $errors['category'] . "</p>" : "" ?>
        </div>

        <div class="form__row">
            <label class="form__label" for="date">Дата выполнения</label>
            <input class="form__input form__input--date <?= isset($errors['date']) ? 'form__input--error' : '' ?>"
                   type="text" name="date" id="date" value="<?= $_POST['date'] ?? '' ?>"
                   placeholder="Введите дату в формате ГГГГ-ММ-ДД">
            <?= isset($errors['date']) ? "<p class='form__message'>" . $errors['date'] . "</p>" : "" ?>
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
