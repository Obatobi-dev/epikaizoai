-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 19, 2023 at 07:09 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

INSERT INTO `bot` (`sn`, `id`, `version`, `active`, `detail`, `stamp`) VALUES
(1, '235b5n', '2.1', 0, '{\"min\":20,\"max\":2000,\"return\":1,\"lock_duration\":\"24hrs\"}', '2023-09-19 23:52:54'),
(4, '45b46g', '4.0', 1, '{\"min\":50,\"max\":20000,\"return\":2,\"lock_duration\":\"30 days\"}', '2023-09-19 23:52:54'),
(5, 'svrv45gh', '7.0', 0, '{\"min\":100,\"max\":50000,\"return\":2,\"lock_duration\":\"60 days\"}', '2023-09-19 23:52:54');