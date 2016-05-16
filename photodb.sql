-- phpMyAdmin SQL Dump
-- version 4.6.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Май 15 2016 г., 23:59
-- Версия сервера: 10.1.13-MariaDB
-- Версия PHP: 7.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `photodb`
--

-- --------------------------------------------------------

--
-- Структура таблицы `albums`
--

CREATE TABLE `albums` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `author` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `albums`
--

INSERT INTO `albums` (`id`, `author`, `name`, `active`, `created_at`, `updated_at`) VALUES
(1, 21, 'Kitties', 1, '2016-05-15 07:13:14', '2016-05-15 07:13:14');

-- --------------------------------------------------------

--
-- Структура таблицы `migrations`
--

CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2014_10_12_000000_create_users_table', 1),
('2016_05_13_051535_create_password_resets_table', 1),
('2016_05_13_051552_create_albums_table', 1),
('2016_05_13_051610_create_permissions_table', 1),
('2016_05_13_051626_create_photos_table', 1),
('2016_05_13_051632_create_resized_photos_table', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `password_resets`
--

CREATE TABLE `password_resets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user` bigint(20) UNSIGNED NOT NULL,
  `token` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `used` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user` bigint(20) UNSIGNED NOT NULL,
  `album` bigint(20) UNSIGNED NOT NULL,
  `access` enum('read','full') COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `permissions`
--

INSERT INTO `permissions` (`id`, `user`, `album`, `access`, `created_at`, `updated_at`) VALUES
(3, 23, 1, 'full', '2016-05-15 10:29:05', '2016-05-15 10:29:05'),
(4, 24, 1, 'read', '2016-05-15 14:36:48', '2016-05-15 14:36:48');

-- --------------------------------------------------------

--
-- Структура таблицы `photos`
--

CREATE TABLE `photos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `album` bigint(20) UNSIGNED DEFAULT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `photos`
--

INSERT INTO `photos` (`id`, `album`, `image`, `created_at`, `updated_at`) VALUES
(1, 1, 'c2db1f45e648c4084b51281f3b067803.png', '2016-05-15 07:18:28', '2016-05-15 07:18:28');

-- --------------------------------------------------------

--
-- Структура таблицы `resized_photos`
--

CREATE TABLE `resized_photos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `photo` bigint(20) UNSIGNED DEFAULT NULL,
  `size` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `src` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('new','in_progress','complete','error') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'new',
  `comment` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `resized_photos`
--

INSERT INTO `resized_photos` (`id`, `photo`, `size`, `src`, `status`, `comment`, `created_at`, `updated_at`) VALUES
(1, 1, '100x100', 'c2db1f45e648c4084b51281f3b067803100x100.png', 'new', NULL, '2016-05-15 07:18:28', '2016-05-15 08:33:32'),
(2, 1, '400x400', 'c2db1f45e648c4084b51281f3b067803_400x400.png', 'new', NULL, '2016-05-15 07:18:28', '2016-05-15 14:32:38');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role` enum('admin','photographer','client') COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `role`, `username`, `name`, `email`, `password`, `created_at`, `updated_at`) VALUES
(20, 'admin', 'first', 'meow', 'mrmrmrtest@gmail.com', '$2y$10$WzotxtxaFXqIEEvl8R4VL.Ijevcvp6Aj6TLTAyi5sAuSFaDGTEsSK', '2016-05-15 07:09:07', '2016-05-15 07:09:07'),
(21, 'photographer', 'second', 'meow', 'mrmrmrte1st@gmail.com', '$2y$10$.oQwX2LYfIKVUiu0ttV5vePnbsD/qyeEqWSXTIs1WdtxlxG0.cTMa', '2016-05-15 07:09:29', '2016-05-15 07:09:29'),
(22, 'photographer', 'third', 'meow', 'mrmr1mrte1st@gmail.com', '$2y$10$qUmEa6/jB9DRkUQ1OlN3wOd13XjT4K5V40DM8h2k5U8KaDq6Ga4mC', '2016-05-15 07:09:37', '2016-05-15 07:09:37'),
(23, 'client', 'fourth', 'meow', 'mrm1r1mrte1st@gmail.com', '$2y$10$gzvNY.2dSy9FegA4AHjWM.KN.8U18KAtDvnRz53ZabVzqCozf4li6', '2016-05-15 07:09:55', '2016-05-15 07:09:55'),
(24, 'client', 'fifth', 'meow', '1mrm1r1mrte1st@gmail.com', '$2y$10$t.8B.L1cbA3TjlczJJxb.eaqCoyfmlTsfWUgLAvyrdRyMNzn9qrvK', '2016-05-15 07:10:16', '2016-05-15 07:10:16'),
(25, 'client', 'sixth', 'meow', '1m1rm1r1mrte1st@gmail.com', '$2y$10$jLDMYh0gxS4cE9fyg/DD5eMqXWZsT.XAPjmQH80fLotAWSxo8v6s.', '2016-05-15 07:10:24', '2016-05-15 07:10:24');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `albums`
--
ALTER TABLE `albums`
  ADD PRIMARY KEY (`id`),
  ADD KEY `albums_author_index` (`author`);

--
-- Индексы таблицы `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `password_resets_user_index` (`user`);

--
-- Индексы таблицы `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permissions_user_index` (`user`),
  ADD KEY `permissions_album_index` (`album`);

--
-- Индексы таблицы `photos`
--
ALTER TABLE `photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `photos_album_index` (`album`);

--
-- Индексы таблицы `resized_photos`
--
ALTER TABLE `resized_photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `resized_photos_photo_index` (`photo`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `albums`
--
ALTER TABLE `albums`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `photos`
--
ALTER TABLE `photos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `resized_photos`
--
ALTER TABLE `resized_photos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `albums`
--
ALTER TABLE `albums`
  ADD CONSTRAINT `albums_author_foreign` FOREIGN KEY (`author`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_user_foreign` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `permissions`
--
ALTER TABLE `permissions`
  ADD CONSTRAINT `permissions_album_foreign` FOREIGN KEY (`album`) REFERENCES `albums` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permissions_user_foreign` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `photos`
--
ALTER TABLE `photos`
  ADD CONSTRAINT `photos_album_foreign` FOREIGN KEY (`album`) REFERENCES `albums` (`id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `resized_photos`
--
ALTER TABLE `resized_photos`
  ADD CONSTRAINT `resized_photos_photo_foreign` FOREIGN KEY (`photo`) REFERENCES `photos` (`id`) ON DELETE SET NULL;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
