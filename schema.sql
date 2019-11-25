CREATE DATABASE giftube
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE giftube;

CREATE TABLE categories (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  name          CHAR(128) NOT NULL
);

CREATE TABLE gifs (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  dt_add        TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  category_id   INT NOT NULL,
  user_id       INT NOT NULL,
  title         CHAR(128) NOT NULL,
  description   TEXT NOT NULL,
  img_path      CHAR(128),
  likes_count   INT,
  favs_count    INT,
  views_count   INT
);

CREATE TABLE users (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  dt_add        TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  name          CHAR(128) NOT NULL,
  email         CHAR(128) NOT NULL,
  password      CHAR(64) NOT NULL,
  avatar_path   CHAR(128)
);

CREATE UNIQUE INDEX email ON users(email);

CREATE TABLE gifs_like (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  user_id       INT NOT NULL,
  gif_id        INT NOT NULL
);

CREATE TABLE gifs_fav (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  user_id       INT NOT NULL,
  gif_id        INT NOT NULL
);

CREATE TABLE comments (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  dt_add        TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  user_id       INT NOT NULL,
  gif_id        INT NOT NULL,
  comment_text  TEXT NOT NULL
);
