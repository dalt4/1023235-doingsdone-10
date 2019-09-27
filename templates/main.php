<main class="content__main">
    <h2 class="content__main-heading">Список задач</h2>

    <form class="search-form" action="/index.php" method="get" autocomplete="off">
        <input class="search-form__input" type="text" name="ft_search" value="" placeholder="Поиск по задачам">

        <input class="search-form__submit" type="submit" name="" value="Искать">
    </form>

    <div class="tasks-controls">
        <nav class="tasks-switch">
            <a href="/index.php"
               class="tasks-switch__item <?= !isset($_GET['choice']) ? 'tasks-switch__item--active' : '' ?>">Все
                задачи</a>
            <a href="/index.php?choice=today"
               class="tasks-switch__item <?= isset($_GET['choice']) && $_GET['choice'] === 'today' ? 'tasks-switch__item--active' : '' ?>">Повестка
                дня</a>
            <a href="/index.php?choice=tomorrow"
               class="tasks-switch__item <?= isset($_GET['choice']) && $_GET['choice'] === 'tomorrow' ? 'tasks-switch__item--active' : '' ?>">Завтра</a>
            <a href="/index.php?choice=yesterday"
               class="tasks-switch__item <?= isset($_GET['choice']) && $_GET['choice'] === 'yesterday' ? 'tasks-switch__item--active' : '' ?>">Просроченные</a>
        </nav>

        <label class="checkbox">
            <!--добавить сюда атрибут "checked", если переменная $show_complete_tasks равна единице-->
            <input class="checkbox__input visually-hidden show_completed" type="checkbox"
                <?= $show_complete_tasks ? 'checked' : '' ?>
            >
            <span class="checkbox__text">Показывать выполненные</span>
        </label>
    </div>

    <table class="tasks">
        <?php foreach ($tasks as $value): ?>
            <?php if ($value['done'] && !$show_complete_tasks) {
                continue;
            } ?>
            <tr class="tasks__item task
                <?= $value['done'] ? 'task--completed ' : '' ?>
                <?= (strtotime($value['doneDate']) - time()) / 3600 <= 24 && $value['doneDate'] && !$value['done'] ? 'task--important' : '' ?>
            ">
                <td class="task__select">
                    <label class="checkbox task__checkbox">
                        <input class="checkbox__input visually-hidden task__checkbox" type="checkbox"
                               value="<?= $value['id'] ?>" data-cat="<?= $value['categories_id'] ?>"
                            <?= $value['done'] ? 'checked' : '' ?>>

                        <span class="checkbox__text"><?= strip_tags($value['name']) ?></span>
                    </label>
                </td>
                <td class="task__file">
                    <?= $value['userFile'] ? '<a href="' . $value['userFile'] . '">прикрепленный файл</a>' : '' ?>
                </td>
                <td class="task__date"><?= $value['doneDate'] ? date('d.m.Y',
                        strtotime($value['doneDate'])) : '' ?></td>
                <td class="task__controls"></td>
            </tr>
        <?php endforeach ?>
    </table>
</main>
