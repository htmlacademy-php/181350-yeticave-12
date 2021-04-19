<?php require_once('helpers.php');
?>
    <main>
        <nav class="nav">
            <ul class="nav__list container">
            <?php foreach ($categories as $category):?>
                <li class="nav__item">
                    <a href="all-lots.html"><?= htmlspecialchars($category['name']) ?></a>
                </li>
            <?php endforeach;?>
            </ul>
        </nav>
        <form class="form form--add-lot container form--invalid" action="../add.php" method="post"> <!-- form--invalid -->
            <h2>Добавление лота</h2>
            <div class="form__container-two">
                <div class="form__item <?= isset($errors['name']) ? "form__item--invalid" : ""; ?>">
                    <!-- form__item--invalid -->
                    <label for="lot-name">Наименование <sup>*</sup></label>
                    <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота">
                    value="<?= getPostVal(htmlspecialchars('name')); ?>">
                    <span class="form__error"><?= $errors['name'] ?></span>
                </div>
                <div class="form__item <?= isset($errors['category']) ? "form__item--invalid" : ""; ?>">
                    <label for="category">Категория <sup>*</sup></label>
                    <select id="category" name="category">
                        value="<?= getPostVal('category'); ?>">
                        <option>Выберите категорию</option>
                        <?php foreach ($categories as $category):?>
                        <option><?= htmlspecialchars($category['name']) ?></option>
                        <?php endforeach;?>
                    </select>
                    <span class="form__error"><?= $errors['category'] ?></span>
                </div>
            </div>
            <div class="form__item form__item--wide <?= isset($errors['description']) ? "form__item--invalid" : ""; ?>">
                <label for="message">Описание <sup>*</sup></label>
                <textarea id="message" type="text" name="message"
                          placeholder="Напишите описание лота"><?= getPostVal('message'); ?></textarea>
                <span class="form__error"><?= $errors['description'] ?></span>
            </div>
            <div class="form__item form__item--file <?= isset($errors['picture']) ? "form__item--invalid" : ""; ?>">
                <label>Изображение <sup>*</sup></label>
                <div class="form__input-file">
                    <input class="visually-hidden" type="file" name="lot-image" id="lot-img" >
                    <label for="lot-img">
                        Добавить
                    </label>
                </div>
                <span class="form__error"><?= $errors['picture'] ?></span>
            </div>
            <div class="form__container-three">
                <div class="form__item form__item--small <?= isset($errors['start_price']) ? "form__item--invalid" : ""; ?>">
                    <label for="lot-rate">Начальная цена <sup>*</sup></label>
                    <input id="lot-rate" type="int" name="lot-rate" placeholder="0"
                           value="<?= getPostVal('start_price'); ?>">
                    <span class="form__error"><?= $errors['start_price'] ?></span>
                </div>
                <div class="form__item form__item--small <?= isset($errors['bid_step']) ? "form__item--invalid" : ""; ?>">
                    <label for="lot-step">Шаг ставки <sup>*</sup></label>
                    <input id="lot-step" type="int" name="lot-step" placeholder="0"
                           value="<?= getPostVal('bid_step'); ?>">
                    <span class="form__error"><?= $errors['bid_step']?></span>
                </div>
                <div class="form__item <?= isset($errors['end_date']) ? "form__item--invalid" : ""; ?>">
                    <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
                    <input class="form__input-date" id="lot-date" type="text" name="lot-date"
                           placeholder="Введите дату в формате ГГГГ-ММ-ДД"
                           value="<?= getPostVal('end_date'); ?>">
                    <span class="form__error"><?= $errors['end_date'] ?></span>
                </div>
            </div>
            <span class="form__error form__error--bottom"><?= $warning_about_errors?></span>
            <button type="submit" class="button" name="submit_btn">Добавить лот</button>
        </form>
    </main>

<script src="../flatpickr.js"></script>
<script src="../script.js"></script>
