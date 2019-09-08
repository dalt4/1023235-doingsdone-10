<section class="content__side">
    <h2 class="content__side-heading">Проекты</h2>

    <nav class="main-navigation">
        <ul class="main-navigation__list">
            <?php foreach ($categories as $value): ?>
                <li class="main-navigation__list-item <?=($_GET['id'] === $value['id']) ? 'main-navigation__list-item--active' : ''; ?>">
                    <a class="main-navigation__list-item-link" href="/index.php?id=<?= $value['id'] ?>"><?= strip_tags($value['name']) ?></a>
                    <span class="main-navigation__list-item-count">
                       <?= $value['tasksCount']; ?>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <a class="button button--transparent button--plus content__side-button"
       href="pages/form-project.html" target="project_add">Добавить проект</a>
</section>

<main class="content__main">
    <h2 class="content__main-heading">Список задач</h2>

    <form class="search-form" action="index.php" method="post" autocomplete="off">
        <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

        <input class="search-form__submit" type="submit" name="" value="Искать">
    </form>

    <div class="tasks-controls">
        <nav class="tasks-switch">
            <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
            <a href="/" class="tasks-switch__item">Повестка дня</a>
            <a href="/" class="tasks-switch__item">Завтра</a>
            <a href="/" class="tasks-switch__item">Просроченные</a>
        </nav>

        <label class="checkbox">
            <!--добавить сюда атрибут "checked", если переменная $show_complete_tasks равна единице-->
            <input class="checkbox__input visually-hidden show_completed" type="checkbox"
                <?= $show_complete_tasks ? 'checked':'' ?>
            >
            <span class="checkbox__text">Показывать выполненные</span>
        </label>
    </div>

    <table class="tasks">
        <?php foreach ($tasks as $value):?>
            <?php if ($value['done'] && !$show_complete_tasks) {continue;} ?>
            <tr class="tasks__item task
                <?= $value['done'] ? 'task--completed ':' ' ?>
                <?= (strtotime($value['doneDate']) - time())/3600 <= 24 && $value['doneDate'] && !$value['done']? 'task--important':'' ?>
            ">
                <td class="task__select">
                    <label class="checkbox task__checkbox">
                        <input class="checkbox__input visually-hidden" type="checkbox" >
                        <span class="checkbox__text"><?= strip_tags($value['name'])?></span>
                    </label>
                </td>
                <td class="task__file">
                    <?= $value['userFile'] ? '<a href="' . $value['userFile'] . '">прикрепленный файл</a>' : ''?>
                </td>
                <td class="task__date"><?= $value['doneDate'] ? date('d.m.Y', strtotime($value['doneDate'])): '' ?></td>
                <td class="task__controls"></td>
            </tr>
        <?php endforeach ?>
    </table>
</main>
